<template>
  <div class="q-pa-md q-gutter-md">
    <NotificationChannels />
    <MakeNotifications />
    <ManageTriggers />
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

import NotificationChannels from 'components/ManageNotifications/NotificationChannels.vue'
import MakeNotifications from 'src/components/ManageNotifications/MakeNotifications.vue'
import ManageTriggers from 'src/components/ManageNotifications/ManageTriggers.vue'

export default {
  name: 'ManageNotifications',
  components: {
    NotificationChannels,
    MakeNotifications,
    ManageTriggers,
  },
  created() {
    this.$store.dispatch('setHeadline', { text: 'Dobby likes to talk', icon: 'fal fa-bells'})
    this.$store.dispatch('notifications/fetch')
  },
  computed: {
    ...mapGetters({
      gateways: 'notifications/gateways',
    }),
  }
}
</script>

<style>

</style>
