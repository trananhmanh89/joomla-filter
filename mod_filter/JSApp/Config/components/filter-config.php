<?php defined('_JEXEC') or die('Restricted access'); ?>
<!-- start-template -->
<div class="filter-config-wrapper">
    <h4>Filter Config</h4>
    <div v-if="item" class="filter-config-inner">
        <b>Choose template</b>
        {{item}}
        <el-select 
            :value="item.template"
            @change="changeTemplate"
            placeholder="Select Type">
            <el-option
                v-for="component in item.components"
                :key="component.template"
                :label="component.title"
                :value="component.template">
            </el-option>
        </el-select>
        <filter-config-text 
            v-if="item.type === 'text'"
            :key="item.id"
            :item="item">
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
</script>