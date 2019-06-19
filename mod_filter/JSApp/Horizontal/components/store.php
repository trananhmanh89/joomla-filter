<?php defined('_JEXEC') or die('Restricted access'); ?>
<script>
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
</script>