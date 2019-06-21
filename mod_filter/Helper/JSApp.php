<?php
namespace Joomla\Module\Filter\Site\Helper;
use Joomla\CMS\Filter\OutputFilter;
defined('_JEXEC') or die('Restricted access');

class JSApp
{
    protected static $instances = array();

    /**
     * @param string $name Name of app folder
     * @param string $client 'admin' or 'site'
     * @param string $element id of dom element
     */

    static function getInstance($name, $element)
    {
        $element = trim($element);
        $element = str_replace(' ', '', $element);
        $name = strtolower($name);
        $path = JPATH_ROOT . '/modules/mod_filter/JSApp/' . ucfirst($name);

        if (!\JFolder::exists($path)) {
            throw new \Exception("JSApp path not found: \"$name\"");
        }

        if (!\JFile::exists($path . '/app.json')) {
            throw new \Exception("JSApp \"$name\" error: Missing app.json");
        }

        if (!\JFile::exists($path . '/components/app.php')) {
            throw new \Exception("JSApp \"$name\" error: Missing app.php");
        }

        if (empty(self::$instances[$element])) {
            self::$instances[$element] = new JSApp($name, $element);
            return self::$instances[$element];
        } else {
            throw new \Exception("\"$element\":  This element name is already used. Please choose another.");
        }
    }

    protected $name = '';
    protected $id = '';
    protected $devmode = false;
    protected $element = '';
    protected $data = array();
    protected $paths = array();
    protected $files = array();
    protected $appPath = '';

    public function __construct($name, $element)
    {
        $this->element = $element;
        $this->name = ucfirst($name);
        
        $id = OutputFilter::stringUrlSafe($this->element);
        $id = str_replace('-', '_', $id);
        $this->id = ucfirst($name) . '_' . $id;

        $app = \JFactory::getApplication();
        $templatePath = JPATH_BASE . '/templates/' . $app->getTemplate() . '/html/layouts/JSApp/' . $this->name;
        $this->paths['template'] = $templatePath;

        $this->appPath = JPATH_ROOT . '/modules/mod_filter/JSApp/' . $this->name;
        $this->paths['base'] = $this->appPath . '/components';

        foreach ($this->paths as $path) {
            if (!\JFolder::exists($path)) {
                continue;
            }

            $arr = \JFolder::files($path, '.php');
            $arr = $arr ? $arr : array();
            $this->files = array_merge($this->files, $arr);
        }

        usort($this->files, function ($a, $b) {
            if ($a === 'app.php') {
                return -1;
            }

            if ($b === 'app.php') {
                return 1;
            }

            return $a < $b ? -1 : 1;
        });
    }

    public function render($data)
    {
        $this->devmode = !empty($data['value']->devmode);
        $this->data = $data;
        $this->data['element'] = $this->element;
        $this->loadBaseAssets();
        $this->loadAppAssets();

        return '<div id="' . $this->element . '"></div>'
            . '<script>initApp' . $this->id . '()</script>';
    }

    protected function loadBaseAssets()
    {
        \JHtml::_('jquery.framework');
        \JHtml::_('behavior.core');

        $doc = \JFactory::getDocument();
        if ($this->devmode) {
            $doc->addScript(\JUri::root(true) . '/modules/mod_filter/Asset/vue/vue.js');
            if (in_array('store.php', $this->files)) {
                $doc->addScript(\JUri::root(true) . '/modules/mod_filter/Asset/vue/vuex.js');
            }
        } else {
            $doc->addScript(\JUri::root(true) . '/modules/mod_filter/Asset/vue/vue.min.js');
            if (in_array('store.php', $this->files)) {
                $doc->addScript(\JUri::root(true) . '/modules/mod_filter/Asset/vue/vuex.min.js');
            }
        }

        $doc->addStyleSheet(\JUri::root(true) . '/modules/mod_filter/Asset/element-ui/index.css');
        $doc->addScript(\JUri::root(true) . '/modules/mod_filter/Asset/element-ui/index.js');
        $doc->addScript(\JUri::root(true) . '/modules/mod_filter/Asset/sortable/Sortable.min.js');
        $doc->addScript(\JUri::root(true) . '/modules/mod_filter/Asset/sortable/vuedraggable.umd.min.js');
    }

    protected function loadAppAssets()
    {
        $doc = \JFactory::getDocument();
        $compiled = file_exists($this->appPath . '/dist/app.js') && file_exists($this->appPath . '/dist/tmpl.json');
        $version = '';
        if ($this->devmode || !$compiled) {
            $version = '?v=' . time();
            $js = "(function($) { \n'use strict';\n";
            $tmpl = array();
            foreach ($this->files as $file) {
                $info = pathinfo($file);
                $filePath = \JPath::find($this->paths, $file);

                ob_start();
                include $filePath;
                $output = ob_get_clean();

                $js .= "\n" . $this->getAppScript($output) . "\n";
                $tmpl[$info['filename']] = $this->getAppTemplate($output);
            }

            $js .= "\n})(jQuery)";
            \JFile::write($this->appPath . '/dist/app.js', $js);
            \JFile::write($this->appPath . '/dist/tmpl.json', json_encode($tmpl));
        }

        $this->data['tmpl'] = json_decode(file_get_contents($this->appPath . '/dist/tmpl.json'));

        $doc->addScriptOptions($this->id, $this->data);
        $doc->addStyleSheet(\JUri::root() . '/modules/mod_filter/JSApp/' . $this->name . '/dist/style.css' . $version);
        $doc->addScript(\JUri::root() . '/modules/mod_filter/JSApp/' . $this->name . '/dist/app.js' . $version);
    }

    protected function getAppScript($content)
    {
        $start = '<script>';
        $end = '</script>';
        return $this->parseTemplate($start, $end, $content);
    }

    protected function getAppTemplate($content)
    {
        $start = '<!-- start-template -->';
        $end = '<!-- end-template -->';
        return $this->parseTemplate($start, $end, $content);
    }

    protected function parseTemplate($start, $end, $content)
    {
        $exploder1 = explode($start, $content);
        if (count($exploder1) !== 2) {
            return '';
        }

        $exploder2 = explode($end, $exploder1[1]);
        if (count($exploder2) !== 2) {
            return '';
        }

        return trim($exploder2[0]);
    }
}
