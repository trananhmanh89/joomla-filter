<?php defined('_JEXEC') or die('Restricted access'); ?>
<!-- start-template -->
<div class="filter-config-selection">
    <div>
        <p>Title</p>
        <el-input
            placeholder="Title"
            :value="item.config.title"
            @input="updateTitle">
        </el-input>
    </div>

    <div>
        <p>Custom value</p>
        <el-input
            placeholder="Custom value"
            :value="item.config.custom"
            @input="updateCustomValue">
        </el-input>
    </div>

    <div>
        <p>Value ordering</p>
        <el-select 
            placeholder="Select" 
            :value="item.config.ordering" 
            @change="updateOrdering">
            <el-option
                v-for="option in orderOptions"
                :key="option.value"
                :label="option.text"
                :value="option.value">
            </el-option>
        </el-select>
    </div>

</div>
<!-- end-template -->

<script>
jcomponent['filter-config-selection'] = function() {
    return {
        template: JDATA.tmpl['filter-config-selection'],

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
            }
        }
    }
};
</script>