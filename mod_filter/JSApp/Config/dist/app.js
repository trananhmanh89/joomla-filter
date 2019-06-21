(function($) { 
'use strict';

var jcomponent = {};
var JDATA = Joomla.getOptions('Config_admin_config_app');

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

window.initAppConfig_admin_config_app = initApp;

jcomponent['filter-add'] = function() {
    return {
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
    }
};

jcomponent['filter-app'] = function() {
    return {
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
}

jcomponent['filter-config-color'] = function() {
    return {
        template: JDATA.tmpl['filter-config-color'],

        components: {
            'filter-config-point': jcomponent['filter-config-point'](),
        },

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

            updateColor: function(points) {
                this.$store.commit('updateConfig', {
                    id: this.item.id,
                    name: 'color',
                    value: points,
                });
            }
        },
    }
};

jcomponent['filter-config-custom-value'] = function() {
    return {
        template: JDATA.tmpl['filter-config-custom-value'],

        props: {
            options: Array,
        },

        data: function() {
            var list = $.extend(true, [], this.options);
            return {
                list: list,
            }
        },

        methods: {
            addItem: function() {
                this.list.push({
                    value: '',
                    text: '',
                });

                this.$emit('change', $.extend(true, [], this.list));
            },

            updateItem: function() {
                this.$emit('change', $.extend(true, [], this.list));
            },

            removeItem: function(index) {
                this.list.splice(index, 1);
                this.$emit('change', $.extend(true, [], this.list));
            }
        },
    }
};

jcomponent['filter-config-date'] = function() {
    return {
        template: JDATA.tmpl['filter-config-date'],

        props: {
            item: Object
        },

        data: function() {
            var startdate = this.item.config.startdate;
            var endate = this.item.config.endate;

            return {
                startdate: startdate,
                endate: endate
            }
        },

        methods: {
            updateTitle: function(value) {
                this.$store.commit('updateConfig', {
                    id: this.item.id,
                    name: 'title',
                    value: value,
                });
            },

            updateStartDate: function(value) {
                this.$store.commit('updateConfig', {
                    id: this.item.id,
                    name: 'startdate',
                    value: value,
                });
            },

            updateEndDate: function(value) {
                this.$store.commit('updateConfig', {
                    id: this.item.id,
                    name: 'endate',
                    value: value,
                });
            },
        }
    }
};

jcomponent['filter-config-point'] = function() {
    return {
        template: JDATA.tmpl['filter-config-point'],

        props: {
            points: Array,
        },

        data: function() {
            var list = $.extend(true, [], this.points);
            return {
                list: list,
            }
        },

        methods: {
            addPoint: function() {
                this.list.push('');
                this.$emit('change', $.extend(true, [], this.list));
            },

            updatePoint: function() {
                this.$emit('change', $.extend(true, [], this.list));
            },

            removePoint: function(index) {
                this.list.splice(index, 1);
                this.$emit('change', $.extend(true, [], this.list));
            }
        },
    }
};

jcomponent['filter-config-range-below'] = function() {
    return {
        template: JDATA.tmpl['filter-config-range-below'],

        components: {
            'filter-config-point': jcomponent['filter-config-point'](),
        },

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

            updatePoint: function(points) {
                this.$store.commit('updateConfig', {
                    id: this.item.id,
                    name: 'points',
                    value: points,
                });
            }
        },
    }
};

jcomponent['filter-config-range'] = function() {
    return {
        template: JDATA.tmpl['filter-config-range'],

        props: {
            item: Object
        },

        data: function() {
            var auto = this.item.config.auto;
            return {
                auto: auto,
            }
        },

        methods: {
            updateTitle: function(value) {
                this.$store.commit('updateConfig', {
                    id: this.item.id,
                    name: 'title',
                    value: value,
                });
            },

            updateAutoDetect: function(value) {
                this.$store.commit('updateConfig', {
                    id: this.item.id,
                    name: 'auto',
                    value: value,
                });
            },

            updateMin: function(value) {
                this.$store.commit('updateConfig', {
                    id: this.item.id,
                    name: 'min',
                    value: value,
                });
            },

            updateMax: function(value) {
                this.$store.commit('updateConfig', {
                    id: this.item.id,
                    name: 'max',
                    value: value,
                });
            },

            updateStep: function(value) {
                this.$store.commit('updateConfig', {
                    id: this.item.id,
                    name: 'step',
                    value: value,
                });
            },
        }
    }
};

jcomponent['filter-config-selection'] = function() {
    return {
        template: JDATA.tmpl['filter-config-selection'],

        components: {
            'filter-config-custom-value': jcomponent['filter-config-custom-value'](),
        },

        props: {
            item: Object
        },

        data: function() {
            var orderOptions = [
                {
                    text: 'Ordering ASC',
                    value: 'ordering_asc',
                },
                {
                    text: 'Ordering DESC',
                    value: 'ordering_desc',
                },
                {
                    text: 'Count ASC',
                    value: 'count_asc',
                },
                {
                    text: 'Count DESC',
                    value: 'count_desc',
                },
                {
                    text: 'Name ASC',
                    value: 'name_asc',
                },
                {
                    text: 'Name DESC',
                    value: 'name_desc',
                },
            ];

            return {
                orderOptions: orderOptions,
            }
        },

        methods: {
            updateTitle: function(value) {
                this.$store.commit('updateConfig', {
                    id: this.item.id,
                    name: 'title',
                    value: value,
                });
            },

            updateCustomValue: function(value) {
                this.$store.commit('updateConfig', {
                    id: this.item.id,
                    name: 'custom',
                    value: value,
                });
            },

            updateOrdering: function(value) {
                this.$store.commit('updateConfig', {
                    id: this.item.id,
                    name: 'ordering',
                    value: value,
                });
            },


            updateCustom: function(value) {
                this.$store.commit('updateConfig', {
                    id: this.item.id,
                    name: 'custom',
                    value: value,
                });
            },

            updateCustomValue: function(value) {
                this.$store.commit('updateConfig', {
                    id: this.item.id,
                    name: 'values',
                    value: value,
                });
            }
        }
    }
};

jcomponent['filter-config-text'] = function() {
    return {
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
    }
};

jcomponent['filter-config'] = function() {
    return {
        template: JDATA.tmpl['filter-config'],

        components: {
            'filter-config-text': jcomponent['filter-config-text'](),
            'filter-config-selection': jcomponent['filter-config-selection'](),
            'filter-config-date': jcomponent['filter-config-date'](),
            'filter-config-range': jcomponent['filter-config-range'](),
            'filter-config-range-below': jcomponent['filter-config-range-below'](),
            'filter-config-color': jcomponent['filter-config-color'](),
        },

        computed: {
            item: function () {
                var activeFilterId = this.$store.state.activeFilterId;
                var filters = this.$store.state.value.filters;

                return filters.find(function(filter) {
                    return filter.id === activeFilterId;
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
    }
};

jcomponent['filter-devmode'] = function() {
    return {
        template: JDATA.tmpl['filter-devmode'],

        computed: {
            devmode: function () {
                return !!this.$store.state.value.devmode;
            },
        },

        methods: {
            changeDevMode: function (value) {
                this.$store.commit('updateDevMode', value);
            }
        }
    }
}

jcomponent['filter-list'] = function() {
    return {
        template: JDATA.tmpl['filter-list'],

        components: {
            'filter-draggable': window.vuedraggable,
        },

        data: function() {
            return {
                drag: false,
            }
        },

        computed: {
            list: {
                get: function() {
                    return this.$store.state.value.filters;
                },

                set: function(items) {
                    this.$store.commit('updateFilterList', items);
                }
            },

            active: function() {
                return this.$store.state.activeFilterId;
            },

            dragOptions() {
                return {
                    animation: 200,
                    group: "description",
                    disabled: false,
                    ghostClass: "ghost"
                };
            }
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
            },
        }
    }
};

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

})(jQuery)