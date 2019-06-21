<?php defined('_JEXEC') or die('Restricted access'); ?>
<!-- start-template -->
<div class="filter-devmode">
    <h4>Filter Dev Mode</h4>
    <div>
        <el-switch
            :value="devmode"
            inactive-color="#ff4949"
            @change="changeDevMode">
        </el-switch>
    </div>
</div>
<!-- end-template -->

<script>
jcomponent['filter-devmode'] = function() {
    return {
        template: JDATA.tmpl['filter-devmode'],

        computed: {
            devmode: function () {
                return !!this.$store.state.value.devmode;
            },
        },

        methods: {
            changeDevMode: function (value) {
                this.$store.commit('updateDevMode', value);
            }
        }
    }
}
</script>