<?php defined('_JEXEC') or die('Restricted access'); ?>
<!-- start-template -->
<div class="filter-config-point">
    <el-button 
        size="mini" 
        type="primary"
        @click="addPoint">
        Add
    </el-button>
    <div v-for="(point, index) in list">
        <input 
            type="number" 
            v-model="list[index]"
            @input="updatePoint">
    </div>
</div>
<!-- end-template -->

<script>
jcomponent['filter-config-point'] = {
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
            this.list.push('0');
            this.$emit('change', $.extend(true, [], this.list));
        },

        updatePoint: function() {
            this.$emit('change', $.extend(true, [], this.list));
        }
    },
};
</script>