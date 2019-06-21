<?php defined('_JEXEC') or die('Restricted access'); ?>
<!-- start-template -->
<div class="filter-config-range">
    <div>
        <p>Title</p>
        <el-input
            placeholder="Title"
            :value="item.config.title"
            @input="updateTitle">
        </el-input>
    </div>

    <div>
        <p>Auto detect limit</p>
        <el-switch
            :value="item.config.auto"
            inactive-color="#ff4949"
            @change="updateAutoDetect">
        </el-switch>
    </div>
    
    <div v-show="!item.config.auto">
        <p>Min</p>
        <el-input
            placeholder="Title"
            :value="item.config.min"
            @input="updateMin">
        </el-input>
    </div>
    
    <div v-show="!item.config.auto">
        <p>Max</p>
        <el-input
            placeholder="Title"
            :value="item.config.max"
            @input="updateMax">
        </el-input>
    </div>

    <div v-show="!item.config.auto">
        <p>Step</p>
        <el-input
            placeholder="Title"
            :value="item.config.step"
            @input="updateStep">
        </el-input>
    </div>

</div>
<!-- end-template -->

<script>
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
</script>