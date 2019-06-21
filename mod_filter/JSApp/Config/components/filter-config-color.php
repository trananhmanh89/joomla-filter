<?php defined('_JEXEC') or die('Restricted access'); ?>
<!-- start-template -->
<div class="filter-config-color">
    <div>
        <p>Title</p>
        <el-input
            placeholder="Title"
            :value="item.config.title"
            @input="updateTitle">
        </el-input>
    </div>

    <div>
        <p>Color</p>
        <filter-config-point 
            :points="item.config.color"
            @change="updateColor">
        </filter-config-point>
    </div>
</div>
<!-- end-template -->

<script>
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
</script>