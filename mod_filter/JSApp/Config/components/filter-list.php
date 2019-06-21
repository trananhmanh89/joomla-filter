<?php defined('_JEXEC') or die('Restricted access'); ?>
<!-- start-template -->
<div class="filter-list">
    <h4>Filter List</h4>
        <div v-for="item in list" class="filter-item">
            <el-button 
                :type="active === item.id ? 'primary' : ''"
                @click="setActive(item.id)">
                {{item.group}}.{{item.title}}
            </el-button>
            <a @click.prevent="duplicateFilter(item)" href="#">Duplicate</a>
            <a @click.prevent="deleteFilter(item.id)" href="#">Delete</a>
        </div>
</div>
<!-- end-template -->

<script>
jcomponent['filter-list'] = function() {
    return {
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
    }
};
</script>