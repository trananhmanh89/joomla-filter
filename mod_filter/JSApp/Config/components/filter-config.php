<?php defined('_JEXEC') or die('Restricted access'); ?>
<!-- start-template -->
<div class="filter-config-wrapper">
  <h4>Filter Config</h4>
  <div v-if="active" class="filter-config-inner">
    <el-select 
      v-model="component" 
      placeholder="Select Type">
      <el-option
        v-for="component in active.components"
        :key="component.template"
        :label="component.title"
        :value="component.template">
      </el-option>
    </el-select>
    <filter-config-text 
      v-if="active.type === 'text'" 
      :item="active">
    </filter-config-text>
  </div>
</div>
<!-- end-template -->

<script>
  jcomponent['filter-config'] = {
    template: JDATA.tmpl['filter-config'],

    components: {
      'filter-config-text': jcomponent['filter-config-text']
    },

    computed: {
      component: {
        get: function() {
          return this.active.template;
        },

        set: function(value) {
          console.log('object');
        }
      },

      active: function() {
        var activeFilter = this.$store.state.activeFilter;
        var filters = this.$store.state.value.filters;

        return filters.find(function(filter) {
          return filter.id === activeFilter;
        });
      },
    }
  };
</script>