<template>
  <div>
    <q-card-section>
      <div class="text-h5 q-mb-md">Get notified via email <q-icon name="fal fa-mailbox" color="green"></q-icon></div>
      <p class="text-body1" v-if="!connectedEmailGateway">
        Dobby is able to send you messages to an email address.
      </p>
      <p class="caption text-weight-light q-mb-none" v-if="!connectedEmailGateway">
        Please paste in your email address below.
      </p>
    </q-card-section>

    <q-card-section v-if="!connectedEmailGateway">
      <q-input
        v-model="email"
        debounce="250"
        outlined
        dense
        type="text"
        label="paste in an email address"

      />
    </q-card-section>

    <q-card-section v-if="!connectedEmailGateway">
      <q-btn
        unelevated
        class="full-width"
        icon="fal fa-mailbox"
        label="Activate email notifications"
        color="primary"
        :disabled="!isEmail()"
        :loading="loading"
        @click="createEmailGateway()"
      />
    </q-card-section>

    <q-card-section v-if="connectedEmailGateway">
      <p>
        Successfully connected. Let's give it a try!
      </p>
      <TestChannel
        label="Send test message to email"
        channel="mail"
        color="green"
        icon="fal fa-mailbox"
        rounded
        outline
      />
    </q-card-section>
  </div>
</template>

<script>
import TestChannel from 'components/ManageNotifications/NotificationChannels/TestChannel.vue'

import { mapGetters, mapActions } from 'vuex'

export default {
  name: 'ConnectEmailChannel',
  components: {
    TestChannel,
  },
  data() {
    return {
      loading: false,
      email: '',
      connectedEmailGateway: false,
      sentTestMessage: false,
      emailPattern: /^(?=[a-zA-Z0-9@.\-_+]{6,254}$)[a-zA-Z0-9.\-_+]{1,64}@(?:[a-zA-Z0-9-]{1,63}\.){1,8}[a-zA-Z]{2,63}$/,
    }
  },
  methods: {
    isEmail() {
      return this.emailPattern.test(this.email)
    },
    createEmailGateway() {
      this.loading = true

      this.$api
      .post('user/gateways', {type: 'mail', value: this.email})
      .then((result) => {
        setTimeout(() => {
          this.connectedEmailGateway = true
          this.loading = false
        }, 1500)
      })
      .catch((error) => {
        console.log(error)
      })
    },
    ...mapActions({
      fetchGateways: 'notifications/fetchGateways',
    })
  },
  computed: {
    ...mapGetters({
      isDemo: 'account/isDemo',
      userId: 'account/userId',
      hasGatewayType: 'notifications/hasGatewayType',
    }),
  }
}
</script>

<style>

</style>
