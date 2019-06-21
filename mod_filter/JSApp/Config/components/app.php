<?php defined('_JEXEC') or die('Restricted access'); ?>
<!-- start-template -->
<div id="config-app" style="height: 800px;">
    <textarea 
        style="width: 100%;" 
        rows="5" 
        name="jform[params][config]" 
        id="jform_params_config">{{value}}</textarea>
    <div class="filter-wrapper">
        <filter-devmode></filter-devmode>
        <filter-app></filter-app>
        <filter-add></filter-add>
        <hr style="clear: both;">
        <div class="sidebar" style="width: 30%; float: left;">
            <filter-list></filter-list>
        </div>
        <div class="config" style="width: 70%; float: left;">
            <filter-config></filter-config>
        </div>
    </div>
    <hr style="clear: both;">
    <div class="sorting-wrapper">
        <h4>ordering</h4>
    </div>
</div>
<!-- end-template -->

<script>
var jcomponent = {};
var JDATA = Joomla.getOptions('<?php echo $this->id ?>');

var initApp = function initApp() {
    var store = getAppStore(JDATA);

    Vue.prototype.$jtext = function (str) {
        return Joomla.JText._(str);
    }
    
    new Vue({
        el: '#' + JDATA.element,

        template: JDATA.tmpl.app,

        components: {
            'filter-add': jcomponent['filter-add'](),
            'filter-list': jcomponent['filter-list'](),
            'filter-config': jcomponent['filter-config'](),
            'filter-app': jcomponent['filter-app'](),
            'filter-devmode': jcomponent['filter-devmode'](),
        },

        store: store,

        computed: {
            value: function() {
                return JSON.stringify(this.$store.state.value);
            }
        }
    });
}

window.initApp<?php echo $this->id ?> = initApp;
</script>