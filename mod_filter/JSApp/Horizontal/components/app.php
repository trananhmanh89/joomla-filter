<?php defined('_JEXEC') or die('Restricted access'); ?>
<!-- start-template -->
<div id="config-app">
  <textarea
      style="width: 100%;"
      rows="5"
      name="jform[config]"
      id="jform_config">{{value}}</textarea>

  <div class="filter-wrapper">
    <div class="sidebar">
      <filter-add></filter-add>
      <filter-list></filter-list>
    </div>
    <div class="config">
      <filter-config></filter-config>
    </div>
  </div>

  <div class="sorting-wrapper">
    <h4>ordering</h4>
  </div>
</div>
<!-- end-template -->

<script>
var jcomponent = {};
var JDATA = Joomla.getOptions('<?php echo $this->name ?>');

var initApp = function initApp() {
  var store = getAppStore();
  new Vue({
    el: '#' + JDATA.element,

    template: JDATA.tmpl.app,

    components: {
      'filter-add': jcomponent['filter-add'],
      'filter-list': jcomponent['filter-list'],
      'filter-config': jcomponent['filter-config']
    },
    
    store: store,

    computed: {
      value: function() {
        return 'value';
      }
    }
  });
}

window.initApp<?php echo $this->name ?> = initApp;
</script>