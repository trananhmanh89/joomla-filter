<?php defined('_JEXEC') or die('Restricted access'); ?>
<!-- start-template -->
<div class="filter-add">
  <h4>Filter Add</h4>
  <el-button 
    type="primary" 
    @click="dialogVisible = true">Add field</el-button>

  <el-dialog
    title="Fields"
    :visible.sync="dialogVisible"
    width="30%"
    class="field-dialog">
    <div class="base-fields">
      <h4>Base Fields</h4>
      <div class="base-field-list">
        <el-button 
          v-for="field in baseFields" 
          :key="field.name"
          :type="added.indexOf(field.name) > - 1 ? 'primary' : ''"
          @click="addFilter(field)">
          {{field.title}}
        </el-button>
      </div>
    </div>
    
    <div class="custom-fields">
      <h4>Custom Fields</h4>
      <div class="categories">
        <el-select v-model="categoryId" placeholder="Select Category">
          <el-option
            v-for="cat in categories"
            :key="cat.id"
            :label="cat.treeName"
            :value="cat.id">
          </el-option>
        </el-select>
      </div>
      <div class="custom-field-list">
        <el-button 
          v-for="field in customFields" 
          :key="field.name"
          :type="added.indexOf(field.name) > - 1 ? 'primary' : ''"
          @click="addFilter(field)">
          {{field.title}}
        </el-button>
      </div>
    </div>
  </el-dialog>
</div>
<!-- end-template -->

<script>
  jcomponent['filter-add'] = {
    template: JDATA.tmpl['filter-add'],
    
    data: function() {
      return {
        dialogVisible: false,
        categoryId: 'all',
      };
    },

    computed: {
      baseFields: function() {
        var fields = this.$store.state.fields;

        return fields.filter(function(field) {
          return field.group === 'base';
        });
      },

      customFields: function() {
        var fields = this.$store.state.fields;
        var categoryId = this.categoryId;
        return fields.filter(function(field) {
          if (field.group !== 'custom') {
            return false;
          }

          return categoryId === 'all' 
            || !field.category.length 
            || field.category.indexOf(categoryId) > -1;
        });
      },

      categories: function() {
        return this.$store.state.categories;
      },

      added: function() {
        var filters = this.$store.state.value.filters;
        var added = filters.map(function(item) {
          return item.name;
        });

        return added;
      }
    },

    methods: {
      addFilter: function(field) {
        if (this.added.indexOf(field.name) === -1) {
          this.$store.commit('addFilter', field);
        }
      }
    }
  };
</script>