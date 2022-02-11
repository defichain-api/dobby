<template>
  <div>
    <q-card-section>
      <div class="text-h5 q-mb-md">Get notified via Webhook <q-icon name="fal fa-send-back" color="red"></q-icon></div>
      <p class="text-body1" v-if="!connectedWebhookGateway">
        Dobby is able to send you messages to a webhook URL.
      </p>
      <p class="caption text-weight-light q-mb-none" v-if="!connectedWebhookGateway">
        Please paste in your webhook URL below.
      </p>
    </q-card-section>

    <q-card-section v-if="!connectedWebhookGateway">
      <q-input
        v-model="webhookUrl"
        debounce="250"
        outlined
        dense
        type="text"
        label="paste in a webhook url"

      />
    </q-card-section>

    <q-card-section v-if="!connectedWebhookGateway">
      <q-btn
        unelevated
        class="full-width"
        icon="fal fa-send-back"
        label="activate webhook now"
        color="primary"
        :disabled="!webhookIsUrl()"
        :loading="loading"
        @click="createWebbhookGateway()"
      />
    </q-card-section>

    <q-card-section v-if="connectedWebhookGateway">
      <p>
        Successfully connected. Let's give it a try!
      </p>
      <TestChannel
        label="Send test message to webhook"
        channel="webhook"
        color="red"
        icon="fal fa-send-back"
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
  name: 'ConnectWebhookChannel',
  components: {
    TestChannel,
  },
  data() {
    return {
      loading: false,
      webhookUrl: '',
      connectedWebhookGateway: false,
      sentTestMessage: false,
    }
  },
  methods: {
    webhookIsUrl() {
      var urlRegex = '^(?!mailto:)(?:(?:http|https|ftp)://)(?:\\S+(?::\\S*)?@)?(?:(?:(?:[1-9]\\d?|1\\d\\d|2[01]\\d|22[0-3])(?:\\.(?:1?\\d{1,2}|2[0-4]\\d|25[0-5])){2}(?:\\.(?:[0-9]\\d?|1\\d\\d|2[0-4]\\d|25[0-4]))|(?:(?:[a-z\\u00a1-\\uffff0-9]+-?)*[a-z\\u00a1-\\uffff0-9]+)(?:\\.(?:[a-z\\u00a1-\\uffff0-9]+-?)*[a-z\\u00a1-\\uffff0-9]+)*(?:\\.(?:[a-z\\u00a1-\\uffff]{2,})))|localhost)(?::\\d{2,5})?(?:(/|\\?|#)[^\\s]*)?$'
      var url = new RegExp(urlRegex, 'i')
      return this.webhookUrl.length < 2083 && url.test(this.webhookUrl)
    },
    createWebbhookGateway() {
      this.loading = true

      this.$api
      .post('user/gateways', {type: 'webhook', value: this.webhookUrl})
      .then((result) => {
        setTimeout(() => {
          this.connectedWebhookGateway = true
          this.loading = false
          console.log(result)
        }, 1500)
      })
      .catch((error) => {
        console.log(error)
      })
      /*
      setTimeout(() => {
        this.connectedWebhookGateway = true
        this.loading = false
      }, 1500)
      */
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
