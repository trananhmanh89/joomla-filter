<?php defined('_JEXEC') or die('Restricted access'); ?>
<!-- start-template -->
<div class="filter-config-date">
    <div>
        <p>Title</p>
        <el-input
            placeholder="Title"
            :value="item.config.title"
            @input="updateTitle">
        </el-input>
    </div>

    <div>
    <p>Start Date</p>
        <el-date-picker
            v-model="startdate"
            value-format="yyyy-MM-dd"
            type="date"
            placeholder="Pick a day"
            @change="updateStartDate">
        </el-date-picker>
    </div>

    <div>
        <p>End Date</p>
        <el-date-picker
            v-model="endate"
            value-format="yyyy-MM-dd"
            type="date"
            placeholder="Pick a day"
            @change="updateEndDate">
        </el-date-picker>
    </div>
</div>
<!-- end-template -->

<script>
jcomponent['filter-config-date'] = function() {
    return {
        template: JDATA.tmpl['filter-config-date'],

        props: {
            item: Object
        },

        data: function() {
            var startdate = this.item.config.startdate;
            var endate = this.item.config.endate;

            return {
                startdate: startdate,
                endate: endate
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

            updateStartDate: function(value) {
                this.$store.commit('updateConfig', {
                    id: this.item.id,
                    name: 'startdate',
                    value: value,
                });
            },

            updateEndDate: function(value) {
                this.$store.commit('updateConfig', {
                    id: this.item.id,
                    name: 'endate',
                    value: value,
                });
            },
        }
    }
};
</script>