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
            :points="item.config.custom"
            @change="updatePoint">
        </filter-config-point>
    </div>
</div>
<!-- end-template -->

<script>
jcomponent['filter-config-color'] = {
    template: JDATA.tmpl['filter-config-color'],

    components: {
        'filter-config-point': jcomponent['filter-config-point'],
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
};
</script>