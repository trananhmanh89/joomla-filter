<?php defined('_JEXEC') or die('Restricted access'); ?>
<!-- start-template -->
<div class="filter-app">
  <h4>Filter App</h4>
  <div class="filter-app-selector">
    <el-select 
      v-model="app" 
      placeholder="Select App"
      @change="changeFilterApp">
      <el-option
        v-for="app in apps"
        :key="app.id"
        :label="app.title"
        :value="app.id">
      </el-option>
    </el-select>
  </div>
</div>
<!-- end-template -->

<script>
jcomponent['filter-app'] = {
  template: JDATA.tmpl['filter-app'],

  data: function() {
    var app = this.$store.state.value.app;
    return {
      app: app,
    }
  },

  computed: {
    apps: function() {
      return this.$store.state.apps;
    },
  },

  methods: {
    changeFilterApp: function() {
      this.$store.commit('changeFilterApp', this.app);
    }
  }
}
</script>