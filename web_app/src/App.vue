<template>
  <router-view />
</template>
<script>
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'App',
  created() {
    // FIRST THING TO DO ========================================================
    // Read settings from local storage and write it to the vuex store
    this.$store.dispatch("account/initFromLocalStorage")
  },
  methods: {
    getUserData: function () {

    }
  },
})
</script>
