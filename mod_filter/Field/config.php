<?php
defined('_JEXEC') or die('Restricted access');
require JPATH_ROOT . '/modules/mod_filter/Helper/JSApp.php';

use Joomla\Module\Filter\Site\Helper\JSApp;

class JFormFieldConfig extends JFormField
{
    protected $type = 'config';
    protected $categories = array();

    public function renderField($option = array())
    {
        $data = $this->loadData();
        $jsApp = JSApp::getInstance('config', 'admin-config-app');

        return $jsApp->render($data);
    }

    protected function loadData()
    {
        $apps = $this->getFilterApps();
        $baseFields = $this->getBaseFields();
        $customFields = $this->getCustomFields();
        $fields = array_merge($baseFields, $customFields);
        $categories = $this->getCategories();

        return array(
            'apps' => $apps,
            'fields' => $fields,
            'categories' => $categories,
            'value' => json_decode($this->value),
        );
    }

    protected function getFilterApps()
    {
        $path = JPATH_ROOT . '/modules/mod_filter/JSApp/';
        $folders = JFolder::folders($path);
        $apps = array();
        foreach ($folders as $folder) {
            $hasAppJSON = JFile::exists($path . $folder . '/app.json');
            $hasAppPHP = JFile::exists($path . $folder . '/components/app.php');
            if (!$hasAppJSON || !$hasAppPHP) {
                continue;
            }

            $data = json_decode(file_get_contents($path . $folder . '/app.json'));
            if (!is_object($data) || empty($data->title)) {
                continue;
            }

            if (empty($data->type) || $data->type !== 'filter') {
                continue;
            }
            
            $data->id = strtolower($folder);
            $apps[] = $data;
        }

        return $apps;
    }

    protected function getCategories()
    {
        if ($this->categories) {
            return $this->categories;
        }
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select($db->qn(array('id', 'title', 'level', 'parent_id')))
            ->from($db->qn('#__categories'))
            ->where($db->qn('extension') . '=' . $db->q('com_content'))
            ->where($db->qn('published') . '=' . $db->q('1'))
            ->order('lft');
        
        $categories = $db->setQuery($query)->loadObjectList();
        $all = new stdClass;
        $all->id = 'all';
        $all->title = 'All';
        $all->level = '1';
        $all->parent_id = '0';

        $categories = array_merge(
            array($all),
            $categories
        );

        $categories = array_map(function($cat) {
            $cat->level = +$cat->level;
            if ($cat->level > 1) {
                $cat->treeName = str_repeat('._. ', $cat->level - 2) . '|_. ' . $cat->title;
            } else {
                $cat->treeName = $cat->title;
            }

            return $cat;
        }, $categories);

        $this->categories = $categories;
        return $this->categories;
    }

    protected function getBaseFields()
    {
        return array(
            array(
                'group' => 'base',
                'name' => 'base-title',
                'title' => 'Title',
                'filterTypes' => array('text')
            ),
            array(
                'group' => 'base',
                'name' => 'base-created',
                'title' => 'Created Date',
                'filterTypes' => array('date')
            ),
            array(
                'group' => 'base',
                'name' => 'base-publish_up',
                'title' => 'Published Date',
                'filterTypes' => array('date')
            ),
            array(
                'group' => 'base',
                'name' => 'base-featured',
                'title' => 'Featured',
                'filterTypes' => array('single', 'multiple')
            ),
            array(
                'group' => 'base',
                'name' => 'base-cat',
                'title' => 'Category',
                'filterTypes' => array('single', 'multiple')
            ),
            array(
                'group' => 'base',
                'name' => 'base-tag',
                'title' => 'Tag',
                'filterTypes' => array('single', 'multiple')
            ),
            array(
                'group' => 'base',
                'name' => 'base-hit',
                'title' => 'Hit',
                'filterTypes' => array('range', 'range-below')
            ),
        );
    }

    protected function getCustomFields()
    {
        $db = JFactory::getDbo();
        $types = $db->q(array(
            'text',
            'calendar',
            'checkboxes',
            'color',
            'integer',
            'list',
            'radio',
        ));

        $query = $db->getQuery(true)
            ->select($db->qn(array('id', 'title', 'name', 'type')))
            ->from($db->qn('#__fields'))
            ->where($db->qn('type') . ' IN (' . implode(',', $types) . ')')
            ->where($db->qn('state') . '= 1');

        $rows = $db->setQuery($query)->loadObjectList();
        $fields = array_map(function($item) use ($db) {
            $field = array();
            $field['group'] = 'custom';
            $field['name'] = 'custom-' . $item->name;
            $field['title'] = $item->title;

            if ($item->type === 'text') {
                $field['filterTypes'] = array('text');
            }

            if ($item->type === 'calendar') {
                $field['filterTypes'] = array('date');
            }

            if ($item->type === 'checkboxes' || $item->type === 'radio' || $item->type === 'list') {
                $field['filterTypes'] = array('single', 'multiple');
            }

            if ($item->type === 'color') {
                $field['filterTypes'] = array('color');
            }

            if ($item->type === 'integer') {
                $field['filterTypes'] = array('range');
            }

            $query = $db->getQuery(true)
                ->select($db->qn('category_id'))
                ->from($db->qn('#__fields_categories'))
                ->where($db->qn('field_id') . '=' . $db->q($item->id));

            $result = $db->setQuery($query)->loadColumn();
            $field['category'] = $this->getTreeCategory($result);

            return $field;
        }, $rows) ;

        return $fields;
    }

    protected function getTreeCategory($catids)
    {
        if (!$catids) {
            return array();
        }

        $tree = array();
        foreach ($catids as $catid) {
            $children = $this->getChildren($catid);
            $tree = array_merge($tree, array($catid));
            $tree = array_merge($tree, $children);
        }

        $tree = array_unique($tree);
        return $tree;
    }

    protected function getChildren($catid)
    {
        $categories = $this->getCategories();
        $children = array();
        $list = array_filter($categories, function($category) use ($catid) {
            return $category->parent_id === $catid;
        });
        
        foreach ($list as $item) {
            $children[] = $item->id;
            $children = array_merge($children, $this->getChildren($item->id));
        }

        return $children;
    }
}
