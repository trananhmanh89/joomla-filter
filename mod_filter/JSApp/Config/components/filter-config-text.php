<?php defined('_JEXEC') or die('Restricted access'); ?>
<!-- start-template -->
<div class="filter-config-text">
    <div>
        <p>Title</p>
        <el-input
            placeholder="Title"
            :value="item.config.title"
            @input="updateTitle">
        </el-input>
    </div>

    <div>
        <p>Max Length</p>
        <el-input
            placeholder="Max Length"
            :value="item.config.maxlength"
            @input="updateMaxLength">
        </el-input>
    </div>
</div>
<!-- end-template -->

<script>
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
</script>