<?php defined('_JEXEC') or die('Restricted access'); ?>
<!-- start-template -->
<div class="filter-config-text">
  <b>config text</b>
</div>
<!-- end-template -->

<script>
  jcomponent['filter-config-text'] = {
    template: JDATA.tmpl['filter-config-text'],

    props: {
      item: Object
    },

    data: function() {
      return {

      }
    }
  };
</script>