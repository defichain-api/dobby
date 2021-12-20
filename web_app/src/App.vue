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
    this.$store.dispatch("settings/initFromLocalStorage")

    // PROCEED WITH OTHER STUFF =====================================================

    // set dark mode setting
    this.$q.dark.set(this.getSettingValue('darkMode'))

    this.setupAxiosInterceptors()

    // Load user's vaults when there's a userId set in local storage
    if (this.userId && this.userId != null && this.userId != '') {
      if (process.env.DEV) { console.log("[DEBUG] initializing with dobby account " + this.userId) }

      // Set auth header for API communication
      this.$api.defaults.headers.common['x-user-auth'] = this.userId

      // Receive user data from Dobby API and store it in vuex store
      this.loadUserData.catch((error) => {
        // TODO show information that user's accound was not found
        //this.$router.push({ name: 'setup' })
      })

      // Fetch user's notifications
      this.loadUserNotifications

    } else {
      // no user account set
      // redirect to setup
      this.$router.push({ name: 'setup' })
    }
  },
  methods: {
    setupAxiosInterceptors() {
      this.$api.interceptors.request.use((request) =>{
        this.$store.commit('requestStart', request.url)
        return request
      }, (error) => {
        this.dataRefreshError()
        return Promise.reject(error)
      })
      this.$api.interceptors.response.use((response) => {
        this.$store.commit('requestDone', response.config.url)
        this.$store.commit('apiResponded')
        return response
      }, (error) => {
        this.dataRefreshError()
        return Promise.reject(error)
      })
    },
    dataRefreshError(message) {
      this.$store.commit('noApiResponse')
      if (!message) {
          message = 'Whoops. Looks like the Dobby API does not respond :/'
      }
      this.$q.notify({
        group: 'dataRefreshError',
        type: 'negative',
        message: message,
      })
    }
  },
  computed: {
    ...mapGetters({
      userId: 'account/userId',
      getSettingValue: 'settings/value',
    }),
    ...mapActions({
      loadUserData: 'account/loadUserData',
      loadUserNotifications: 'notifications/fetch',
    })
  }
})
</script>
