<template>
  <q-card-section v-if="show">
    <div class="text-h5 q-mb-md">Get notified via Telegram <q-icon name="fab fa-telegram" color="telegram"></q-icon></div>
    <p class="text-body1" v-if="!connectedTelegramGateway">
      Dobby kindly asks you to connect him to your Telegram account, so that he'll be able to send you
      messages.
    </p>
    <p class="caption text-weight-light" v-if="!connectedTelegramGateway">
      By pressing that connect button below, you will be directed to your Telegram app. Please press the start button and return to this app when Telegram told you that your account has been connected.
    </p>

    <q-btn
      v-if="!waitingForTelegramGateway && !connectedTelegramGateway && !isDemo"
      unelevated
      class="full-width"
      icon="fab fa-telegram"
      label="Connect to Telegram now"
      color="primary"
      @click="checkForTelegramGateway(); openTelegram(); waitingForTelegramGateway = true;"
    />

    <q-card flat v-if="waitingForTelegramGateway" class="bg-accent text-white">
      <q-card-section class="row">
        <div class="col-3 q-pa-sm q-pl-none text-center">
          <q-icon name="fad fa-cauldron" size="xl"/>
        </div>
        <div class="col-9 q-pa-sm q-pl-md" style="border-left: 1px solid rgba(255,255,255,0.5);">
          Waiting for you to connect your Telegram account to the Dobby Telegram Bot
          <!--<q-btn dense unelevated size="sm" class="full-width q-mt-sm" icon="fab fa-telegram" label="retry" color="primary" @click="openTelegram();"></q-btn>-->
        </div>
      </q-card-section>

      <q-linear-progress indeterminate color="primary" style="height:10px" class="q-mt-sm" />
    </q-card>
    <q-card flat v-if="connectedTelegramGateway" class="bg-positive text-white">
      <q-card-section class="row">
        <div class="col-3 q-pa-sm q-pr-md text-right">
          <q-icon name="fad fa-badge-check" size="xl"/>
        </div>
        <div class="col-9 q-pa-sm q-pl-md" style="border-left: 1px solid rgba(255,255,255,0.5);">
          Yay, Dobby is happy because everything went just fine. His Telegram bot is now authorized to send you messages!
        </div>
      </q-card-section>
    </q-card>
    <q-card flat v-if="connectedTelegramGateway">
      <q-slide-transition>
        <q-card-section class="container q-px-none" v-if="receivedTestMessage == null">
          <div class="row">
            <div class="col-12 text-body1 text-center">
              Please let Dobby send you a test message:
            </div>
          </div>
          <div class="row q-my-md">
            <TestTelegramChannel unelevated rounded class="full-width" @click="sentTestMessage = true" />
          </div>
          <div class="row" v-if="sentTestMessage">
            <div class="col-12 text-center">
              Have you received a message from Dobby in Telegram?
            </div>
          </div>
          <div class="row" v-if="sentTestMessage">
            <div class="col-6 q-pa-sm">
              <q-btn
                outline
                rounded
                dense
                icon="fal fa-times"
                class="full-width"
                label="no" />
            </div>
            <div class="col-6 q-pa-sm">
              <q-btn
                outline
                rounded
                dense
                icon="fal fa-check"
                class="full-width"
                @click="receivedTestMessage = true"
                label="yes" />
            </div>
          </div>
        </q-card-section>
      </q-slide-transition>

      <q-slide-transition>
        <q-card-section v-if="receivedTestMessage" class="container">
          <div class="row">
            That's it! You can now make some notifications triggers :)
            <q-btn
              rounded
              dense
              color="accent"
              class="full-width q-mt-md"
              label="ok, got it"
              @click="show = false"
            />
          </div>
          <!--
          <div class="row">
            <div class="col-3 q-pa-sm">
              <q-btn
                outline
                rounded
                dense
                class="full-width"
                label="no" />
            </div>
            <div class="col-9 q-pa-sm">
              <q-btn
                rounded
                dense
                color="accent"
                class="full-width"
                label="make notifications" />
            </div>

          </div>-->
        </q-card-section>
      </q-slide-transition>

    </q-card>
  </q-card-section>
</template>

<script>
import TestTelegramChannel from 'components/ManageNotifications/NotificationChannels/TestTelegramChannel.vue'

import { mapGetters, mapActions } from 'vuex'
import { openURL } from 'quasar'

export default {
  name: 'ConnectTelegramChannel',
  components: {
    TestTelegramChannel,
  },
  data() {
    return {
      show: true,
      waitingForTelegramGateway: false,
      waitingForTelegramLoop: null,
      connectedTelegramGateway: false,
      sentTestMessage: false,
      receivedTestMessage: null,
    }
  },
  methods: {
    openTelegram() {
      openURL(process.env.TELEGRAM_BOT_LINK + '?start=' + this.userId)
    },
    checkForTelegramGateway() {
      this.waitingForTelegramLoop = setInterval(() => {
        this.fetchGateways()
          .then(() => {
            if (this.hasGatewayType('telegram')) {
              clearTimeout(this.waitingForTelegramLoop)
              this.waitingForTelegramGateway = false
              this.connectedTelegramGateway = true
            }
        })
      }, 2500)
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
