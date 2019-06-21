<?php defined('_JEXEC') or die('Restricted access'); ?>
<!-- start-template -->
<div class="filter-list">
    <h4>Filter List</h4>
    <filter-draggable
        class="list-group"
        v-model="list"
        v-bind="dragOptions"
        @start="drag = true"
        @end="drag = false">
        <transition-group type="transition" :name="!drag ? 'flip-list' : null">
            <div v-for="item in list" :key="item.id" class="filter-item">
                <el-button 
                    :type="active === item.id ? 'primary' : ''"
                    @click="setActive(item.id)">
                    {{item.group}}.{{item.title}}
                </el-button>
                <a @click.prevent="duplicateFilter(item)" href="#">Duplicate</a>
                <a @click.prevent="deleteFilter(item.id)" href="#">Delete</a>
            </div>
        </transition-group>
    </filter-draggable>
</div>
<!-- end-template -->

<script>
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
</script>