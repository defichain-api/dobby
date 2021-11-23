<template>
  <router-view />
</template>
<script>
import { defineComponent } from 'vue';
import { mapGetters } from 'vuex'

export default defineComponent({
  name: 'App',
  created() {
    // FIRST THING TO DO ============================================================
    // Read settings from local storage and write it to the vuex store
    this.$store.dispatch("account/initFromLocalStorage")

    // PROCEED WITH OTHER STUFF =====================================================

    // Load user's vaults when there's a userId set in local storage
    if (this.userId) {
      if (process.env.DEV) { console.log("[DEBUG] initializing with dobby account " + this.userId) }

      // Set auth header for API communication
      this.$api.defaults.headers.common['x-user-auth'] = this.userId

      // receive user's data including settings and vaults
      this.getUserData()

    } else {
      // no user account set
      // relocate to setup
    }
  },
  methods: {
    getUserData: function () {
      this.$api
        .get('/user')
        .then((response) => {
          console.log(response)
        })
        .catch((error) => {
          // whoops, error :()
        })
    }
  },
  computed: {
    // mix the getters into computed with object spread operator
    ...mapGetters({
      userId: 'account/userId'
    })
  }
})
</script>
