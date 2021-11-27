<template>
  <router-view v-slot="{ Component }">
    <transition
      enter-active-class="animated fadeIn"
      leave-active-class="animated fadeOut"
      appear
    >
      <component :is="Component" />
    </transition>
  </router-view>
</template>
<script>
import { defineComponent } from 'vue';
import { mapGetters, mapActions } from 'vuex'

export default defineComponent({
  name: 'App',
  created() {
    // FIRST THING TO DO ============================================================
    // Read settings from local storage and write it to the vuex store
    this.$store.dispatch("account/initFromLocalStorage")

    // PROCEED WITH OTHER STUFF =====================================================

    // Load user's vaults when there's a userId set in local storage

    if (this.userId !== '') {
      if (process.env.DEV) { console.log("[DEBUG] initializing with dobby account " + this.userId) }

      // Set auth header for API communication
      this.$api.defaults.headers.common['x-user-auth'] = this.userId

      //Receive user data from Dobby API and store it in vuex store
      this.loadUserData

    } else {
      // no user account set
      // relocate to setup
      //this.$router.push({ name: 'user', params: { userId: 123 } })
    }
  },
  computed: {
    ...mapGetters({
      userId: 'account/userId',
    }),
    ...mapActions({
      loadUserData: 'account/loadUserData',
    })
  }
})
</script>
