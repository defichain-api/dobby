<template>
  <q-banner v-if="!hasNotificationGateways" class="bg-primary text-white">
    <template v-slot:avatar>
      <q-icon name="fal fa-bells" color="white" />
    </template>
    Dobby has noticed that you don't have any notifications set up. Please head over to Manage Notifications to enable Dobby sending you messages.
    <template v-slot:action>
      <q-btn flat color="white" to="manage-notifications" label="Manage Notifications" />
    </template>
  </q-banner>

</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: "NoNotificationGatways",
  computed: {
    ...mapGetters({
      hasNotificationGateways: 'notifications/hasGateways',
    }),
  }
}
</script>

<style>

</style>
