<?php defined('_JEXEC') or die('Restricted access'); ?>
<!-- start-template -->
<div class="filter-config-custom-value">
    <el-button 
        size="mini" 
        type="primary"
        @click="addItem">
        Add
    </el-button>
    <table>
        <thead>
            <tr>
                <th>Text</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(item, index) in list">
                <td>
                    <input 
                        type="text" 
                        v-model="item.text"
                        @input="updateItem" />
                </td>
                <td>
                    <input 
                        type="text" 
                        v-model="item.value"
                        @input="updateItem" />
                </td>
                <td>
                    <el-button 
                        type="danger" 
                        size="mini"
                        icon="el-icon-delete" 
                        circle @click="removeItem(index)">
                    </el-button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<!-- end-template -->

<script>
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
</script>