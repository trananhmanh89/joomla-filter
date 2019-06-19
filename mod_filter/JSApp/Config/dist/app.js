(function($) { 

var jcomponent = {};
    var JDATA = Joomla.getOptions('Config_admin_config_app');

    var initApp = function initApp() {
        var store = getAppStore(JDATA);
        Vue.prototype._ = function _ (str) {
            return Joomla.JText._(str);
        }
        
        new Vue({
            el: '#' + JDATA.element,

            template: JDATA.tmpl.app,

            data: {
                value: JDATA.value,
            },

            components: {
                'filter-add': jcomponent['filter-add'],
                'filter-list': jcomponent['filter-list'],
                'filter-config': jcomponent['filter-config'],
                'filter-app': jcomponent['filter-app'],
            },

            store: store,
        });
    }

    window.initAppConfig_admin_config_app = initApp;

jcomponent['filter-add'] = {
        template: JDATA.tmpl['filter-add'],
        
        data: function() {
            return {
                dialogVisible: false,
                categoryId: 'all',
            };
        },

        computed: {
            baseFields: function() {
                var fields = this.$store.state.fields;

                return fields.filter(function(field) {
                    return field.group === 'base';
                });
            },

            customFields: function() {
                var fields = this.$store.state.fields;
                var categoryId = this.categoryId;
                return fields.filter(function(field) {
                    if (field.group !== 'custom') {
                        return false;
                    }

                    return categoryId === 'all' 
                        || !field.category.length 
                        || field.category.indexOf(categoryId) > -1;
                });
            },

            categories: function() {
                return this.$store.state.categories;
            },

            added: function() {
                var filters = this.$store.state.value.filters;
                var added = filters.map(function(item) {
                    return item.name;
                });

                return added;
            }
        },

        methods: {
            addFilter: function(field) {
                if (this.added.indexOf(field.name) === -1) {
                    this.$store.commit('addFilter', field);
                }
            }
        }
    };

jcomponent['filter-app'] = {
    template: JDATA.tmpl['filter-app'],

    data: function () {
        var appid = this.$store.state.value.appid;
        return {
            appid: appid,
        }
    },

    computed: {
        apps: function () {
            return this.$store.state.apps;
        },
    },

    methods: {
        changeFilterApp: function () {
            this.$store.commit('changeFilterApp', this.appid);
        }
    }
}

jcomponent['filter-config-text'] = {
        template: JDATA.tmpl['filter-config-text'],

        props: {
            item: Object
        },

        methods: {
            updateTitle: function(value) {
                this.$store.commit('updateConfig', {
                    id: this.item.id,
                    name: 'title',
                    value: value,
                });
            },

            updateMaxLength: function(value) {
                this.$store.commit('updateConfig', {
                    id: this.item.id,
                    name: 'maxlength',
                    value: value,
                });
            }
        }
    };

jcomponent['filter-config'] = {
        template: JDATA.tmpl['filter-config'],

        components: {
            'filter-config-text': jcomponent['filter-config-text']
        },

        computed: {
            item: function () {
                var activeFilter = this.$store.state.activeFilter;
                var filters = this.$store.state.value.filters;

                return filters.find(function(filter) {
                    return filter.id === activeFilter;
                });
            },
        },

        methods: {
            changeTemplate: function (value) {
                this.$store.commit('changeFilterTemplate', {
                    id: this.item.id,
                    template: value,
                });
            }
        }
    };

jcomponent['filter-list'] = {
        template: JDATA.tmpl['filter-list'],

        components: {
            'filter-list-item': jcomponent['filter-list-item']
        },

        computed: {
            list: function() {
                return this.$store.state.value.filters;
            },

            active: function() {
                return this.$store.state.activeFilter;
            },
        },

        methods: {
            setActive: function(id) {
                this.$store.commit('setActiveFilter', id);
            },

            duplicateFilter: function(filter) {
                this.$store.commit('duplicateFilter', filter);
            },

            deleteFilter: function(id) {
                if (this.active === id) {
                    this.$store.commit('setActiveFilter', '');
                }

                this.$store.commit('deleteFilter', id);
            }
        }
    };

var getAppStore = function getAppStore(JDATA) {
        var value = $.extend(
            true, {
                appid: JDATA.apps[0].id,
                filters: [],
            },
            JDATA.value
        );

        return new Vuex.Store({
            strict: true,

            state: {
                activeFilter: '',
                currentApp: JDATA.apps[0],
                apps: JDATA.apps,
                fields: JDATA.fields,
                categories: JDATA.categories,
                value: value
            },

            mutations: {
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

                changeFilterApp: function(state, value) {
                    state.value.appid = value;
                    state.activeFilter = '';
                    var currentApp = state.apps.find(function(app) {
                        return app.id === value;
                    });

                    Vue.set(state, 'currentApp', currentApp);

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
                    Vue.set(item, 'config', $.extend(true, {}, config[component.type]));
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

})(jQuery)