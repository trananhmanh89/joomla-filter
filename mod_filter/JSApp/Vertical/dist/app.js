(function() { 

var jcomponent = {};
var JDATA = Joomla.getOptions('AdminConfig_b50fa12d2665fdf39a3b4360e5ec100d');

var initApp = function initApp() {
  var store = getAppStore();
  new Vue({
    el: '#' + JDATA.element,

    template: JDATA.tmpl.app,

    components: {
      'filter-add': jcomponent['filter-add'],
      'filter-list': jcomponent['filter-list'],
      'filter-config': jcomponent['filter-config']
    },
    
    store: store,

    computed: {
      value: function() {
        return 'value';
      }
    }
  });
}

window.initAppAdminConfig_b50fa12d2665fdf39a3b4360e5ec100d = initApp;

jcomponent['filter-add'] = {
  template: JDATA.tmpl['filter-add']
};

jcomponent['filter-config'] = {
  template: JDATA.tmpl['filter-config']
};

jcomponent['filter-list-item'] =  {
  template: JDATA.tmpl['filter-list-item']
};

jcomponent['filter-list'] = {
  template: JDATA.tmpl['filter-list'],

  components: {
    'filter-list-item': jcomponent['filter-list-item']
  }
};

var getAppStore = function getAppStore() {
  console.log(JDATA);
  return new Vuex.Store({
    state: {
      count: 0
    },
    mutations: {
      increment(state) {
        state.count++
      }
    }
  })
}

})()