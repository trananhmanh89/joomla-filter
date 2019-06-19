<?php defined('_JEXEC') or die('Restricted access'); ?>
<script>
  var getAppStore = function getAppStore(JDATA) {
    var value = $.extend(
      true, {
        app: JDATA.apps[0].id,
        filters: [],
      },
      JDATA.value,
    );

    return new Vuex.Store({
      strict: true,

      state: {
        activeFilter: '',
        apps: JDATA.apps,
        fields: JDATA.fields,
        categories: JDATA.categories,
        value: value
      },

      mutations: {
        addFilter: function(state, field) {
          var app = state.apps.find(function(a) {
            return a.id === state.value.app;
          });

          var components = app.components.filter(function(f) {
            return field.filterTypes.indexOf(f.type) > -1;
          }).map(function(item) {
            return {
              title: item.title,
              template: item.template,
              type: item.type,
            }
          });

          if (components.length === 0) {
            return;
          }

          var first = components[0];
          var clone = {
            group: field.group,
            name: field.name,
            title: field.title,
            id: field.name,
            components: components,
            type: first.type,
            template: first.template,
            config: $.extend(true, {}, app.defaultConfig[first.type]),
          };

          state.value.filters.push(clone);
        },

        changeFilterApp: function(state, value) {
          state.value.app = value;
          state.activeFilter = '';

          var filters = state.value.filters;
          filters.splice(0, filters.length);
        },

        setActiveFilter: function(state, value) {
          state.activeFilter = value;
        },

        duplicateFilter: function(state, filter) {
          var filters = state.value.filters;
          var idx = filters.findIndex(function(item) {
            return item.id === filter.id;
          });

          var clone = $.extend(true, {}, filter);
          clone.id = filter.name + '-' + new Date().getTime();

          filters.splice(idx + 1, 0, clone);
        },

        deleteFilter: function(state, id) {
          var filters = state.value.filters;
          var idx = filters.findIndex(function(item) {
            return item.id === id;
          });

          filters.splice(idx, 1);
        },
      }
    })
  }
</script>