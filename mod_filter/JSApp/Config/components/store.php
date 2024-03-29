<?php defined('_JEXEC') or die('Restricted access'); ?>
<script>
var getAppStore = function getAppStore(JDATA) {
    var value = $.extend(
        true, {
            appid: JDATA.apps[0].id,
            filters: [],
            devmode: false,
        },
        JDATA.value
    );

    var sessionKey = location.href + '_activeFilter';
    var activeFilterId = '';
    if (sessionStorage.getItem(sessionKey)) {
        var val = sessionStorage.getItem(sessionKey);
        var existed = value.filters.find(function(f) {
            return f.id === val;
        });
        
        if (existed) {
            activeFilterId = val;
        }
    }

    if (!activeFilterId) {
        activeFilterId = value.filters[0] ? value.filters[0].id : '';
    }

    var currentApp = JDATA.apps.find(function(a) {
        return a.id === value.appid;
    });

    return new Vuex.Store({
        strict: true,

        state: {
            activeFilterId: activeFilterId,
            currentApp: currentApp || JDATA.apps[0],
            apps: JDATA.apps,
            fields: JDATA.fields,
            categories: JDATA.categories,
            value: value
        },

        mutations: {
            updateDevMode: function(state, value) {
                Vue.set(state.value, 'devmode', value);
            },

            addFilter: function(state, field) {
                var app = state.apps.find(function(a) {
                    return a.id === state.value.appid;
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

            updateFilterList: function(state, items) {
                var value = state.value;
                Vue.set(value, 'filters', items);
            },

            changeFilterApp: function(state, value) {
                state.value.appid = value;
                state.activeFilterId = '';
                var currentApp = state.apps.find(function(app) {
                    return app.id === value;
                });

                Vue.set(state, 'currentApp', currentApp);

                var filters = state.value.filters;
                filters.splice(0, filters.length);
            },

            setActiveFilter: function(state, filterId) {
                state.activeFilterId = filterId;

                sessionStorage.setItem(sessionKey, filterId);
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

            changeFilterTemplate: function(state, payload) {
                var item = state.value.filters.find(function(f) {
                    return f.id === payload.id;
                });

                var app = state.currentApp;
                var config = app.defaultConfig;
                var component = app.components.find(function(com) {
                    return com.template === payload.template;
                });

                Vue.set(item, 'template', payload.template);
                Vue.set(item, 'type', component.type);
                Vue.set(item, 'config', $.extend(true, {}, config[component.type], item.config));
            },

            updateConfig: function(state, payload) {
                var filterId = payload.id;
                var name = payload.name;
                var value = payload.value;

                var item = state.value.filters.find(function(f) {
                    return f.id === filterId;
                });

                var config = item.config;
                Vue.set(config, name, value);
            }
        }
    })
}
</script>