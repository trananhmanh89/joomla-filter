<?php defined('_JEXEC') or die('Restricted access'); ?>
<!-- start-template -->
<div class="filter-list">
  <filter-list-item></filter-list-item>
</div>
<!-- end-template -->

<script>

jcomponent['filter-list'] = {
  template: JDATA.tmpl['filter-list'],

  components: {
    'filter-list-item': jcomponent['filter-list-item']
  }
};
</script>