<template>
  <div>
    <q-expansion-item
      v-model="infoExpanded"
      expand-icon="fal fa-info-circle"
      expanded-icon="fal fa-times"
      header-class="q-pl-none q-mr-sm"
      class="q-mb-md"
    >
      <template v-slot:header>
        <div class="text-h5 col-xs-12 col-sm-5">Your Notification Channels<q-badge class="q-ml-xs" color="primary" align="top">{{ gateways.length }}</q-badge></div>
      </template>
      <template v-slot:default>
        <q-card flat style="max-width: 500px;">
          <q-card-section class="container">
            <div class="row">
              <div class="col-2">
                <q-icon name="fal fa-info-circle" size="lg" />
              </div>
              <div class="col-10">
                These are the services, Dobby is connected to and can send you messages with.<br />
                If you want to add another channel, find it at the 'Available' column and tap on it.<br />
                A little setup wizard will open up and guide you trough the necessary processes.
              </div>
            </div>
          </q-card-section>
        </q-card>
      </template>
    </q-expansion-item>

    <q-card flat style="max-width: 500px;">
      <q-card-section class="container">
        <div class="row">
          <div class="col-5">
            <div class="text-body1">Connected</div>
            <q-chip v-if="hasGatewayType('telegram')" icon="fab fa-telegram-plane" color="telegram" text-color="white">
              Telegram
            </q-chip>
            <q-chip v-if="hasGatewayType('webhook')" icon="fal fa-send-back" color="red" text-color="white">
              Webhook
            </q-chip>
          </div>
          <div class="col-7">
            <div class="text-body1">Available</div>
            <q-chip v-if="!hasGatewayType('telegram')" clickable @click="showConnectTelegram = !showConnectTelegram" icon="fab fa-telegram-plane" color="telegram" text-color="white">
              connect Telegram
            </q-chip>
            <q-chip v-if="!hasGatewayType('webhook')" clickable @click="showConnectWebhook = !showConnectWebhook" icon="fal fa-send-back" color="red" text-color="white">
              &nbsp;webhook
            </q-chip>
            <q-chip disabled icon="fal fa-mailbox" style="" color="green" text-color="white">
              &nbsp;email (soon)
            </q-chip>
          </div>
        </div>
      </q-card-section>

      <q-slide-transition>
        <ConnectTelegramChannel v-if="showConnectTelegram" />
      </q-slide-transition>
      <q-slide-transition>
        <ConnectWebhookChannel v-if="showConnectWebhook" />
      </q-slide-transition>

      <q-separator />

      <q-card-actions clickable @click="testChannelExpanded = !testChannelExpanded">
        <span class="text-body1 q-ml-sm">Send Test Message</span>
        <q-space />
        <q-btn
          color="grey"
          round
          flat
          dense
          :icon="testChannelExpanded ? 'keyboard_arrow_up' : 'keyboard_arrow_down'"
        />
      </q-card-actions>

      <q-slide-transition>
        <div v-show="testChannelExpanded">
          <q-card-section class="text-subitle2 q-gutter-md">
            <!--<TestTelegramChannel unelevated rounded outline v-if="hasGatewayType('telegram')" />-->
            <TestChannel
              v-if="hasGatewayType('telegram')"
              label="Test Telegram"
              channel="telegram"
              color="telegram"
              icon="fab fa-telegram-plane"
              rounded
              outline
            />
            <TestChannel
              v-if="hasGatewayType('webhook')"
              label="Test webhook"
              channel="webhook"
              color="red"
              icon="fal fa-send-back"
              rounded
              outline
            />
          </q-card-section>
        </div>
      </q-slide-transition>
    </q-card>
  </div>
</template>

<script>
import ConnectTelegramChannel from 'components/ManageNotifications/NotificationChannels/ConnectTelegramChannel.vue'
import ConnectWebhookChannel from 'components/ManageNotifications/NotificationChannels/ConnectWebhookChannel.vue'
//import TestTelegramChannel from 'components/ManageNotifications/NotificationChannels/TestTelegramChannel.vue'
import TestChannel from 'components/ManageNotifications/NotificationChannels/TestChannel.vue'

import { mapGetters } from 'vuex'

export default {
    components: {
        ConnectTelegramChannel,
        //TestTelegramChannel,
        ConnectWebhookChannel,
        TestChannel,
    },
    data() {
      return {
        infoExpanded: false,
        showConnectTelegram: false,
        showConnectWebhook: false,
        testChannelExpanded: false,
      }
    },
    computed: {
      ...mapGetters({
        gateways: 'notifications/gateways',
        hasGatewayType: 'notifications/hasGatewayType',
      }),
    }
}
</script>

<style>

</style>
