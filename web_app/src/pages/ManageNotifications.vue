<template>
  <div v-if="!hasTelegramGateway" class="q-pa-md row items-start q-gutter-md">

    <q-card flat :bordered="$q.dark.isActive">
      <q-card-section>
        <div class="text-h5 q-mb-md">Get notified via Telegram <q-icon name="fab fa-telegram"></q-icon></div>
        <p class="text-body1">
          Dobby kindly asks you to connect him to your Telegram account, so that he'll be able to send you
          messages.
        </p>
        <p class="caption text-weight-thin">
          (He'll offer other communication channels like email and webhooks in a
          later version.)
        </p>

        <q-btn v-if="!waitingForTelegramGateway" unelevated class="full-width" icon="fab fa-telegram" label="Connect to Telegram now" color="primary" @click="checkForTelegramGateway(); openTelegram(); waitingForTelegramGateway = true;"></q-btn>
        <q-card flat v-if="waitingForTelegramGateway" class="bg-accent text-white" style="max-width: 380px;">
          <q-card-section class="row">
            <div class="col-3 q-pa-sm">
              <q-icon name="fad fa-cauldron" size="xl"/>
            </div>
            <div class="col-9 q-pa-sm q-pl-md" style="border-left: 1px solid rgba(255,255,255,0.5);">
              Waiting for you to connect your Telegram account to the Dobby Telegram Bot
              <q-btn dense unelevated size="sm" class="full-width q-mt-sm" icon="fab fa-telegram" label="retry" color="primary" @click="openTelegram();"></q-btn>
            </div>
          </q-card-section>

          <q-linear-progress indeterminate color="primary" style="height:10px" class="q-mt-sm" />
        </q-card>
      </q-card-section>
    </q-card>
  </div>

  <q-separator inset />

  <div v-if="hasGateways" class="q-pa-md row items-start q-gutter-md">

    <div class="text-h5 col-12">Your Notification Channels</div>
    <div v-if="hasTelegramGateway">
      <q-chip icon="fab fa-telegram" style="background-color: #0088cc;" text-color="white">
        Telegam is connected
      </q-chip>
      <q-chip disabled icon="fal fa-mailbox" color="green" text-color="white">
        &nbsp;Add email
      </q-chip>
      <q-chip disabled icon="fal fa-send-back" color="red" text-color="white">
        &nbsp;Add webhook
      </q-chip>
      <div class="text-grey">
        (only Telegram is available yet)
      </div>
    </div>
    <!--
    <q-card class="q-mr-md q-mb-md" v-for="gateway in gateways" :key="gateway.gatewayId">
      <q-card-section v-if="gateway.type == 'telegram'" class="text-h6">
        <q-icon name="fab fa-telegram"></q-icon>
        Telegram
      </q-card-section>
      <q-card-section>
        User: {{ gateway.value }}
      </q-card-section>
    </q-card>
    -->
  </div>

  <q-separator inset />


  <div v-if="hasTriggers" class="q-pa-md row items-start q-gutter-md">

    <div class="text-h5 col-12">Your Notifications<q-badge color="primary" align="top">{{ triggers.length }}</q-badge></div>

    <div>
      These are your vaults, containing lists of notifications. It shows the type of
      the notification, when it occurs and on which channels you'll receive notifications.
    </div>

    <!--
    <span v-for="(triggerList, vaultId) in triggersByVault" :key="vaultId" class="full-width">
      <transition
        appear
        enter-active-class="animated pulse"
      > -->
      <transition-group
        appear
        enter-active-class="animated pulse"
      >
        <q-card flat v-for="(triggerList, vaultId) in triggersByVault" :key="vaultId" :bordered="$q.dark.isActive" class="vault">
          <q-card-section>
            <div class="row text-center">
              <div class="col-12">
                <div class="text-caption ellipsis"><q-icon name="fal fa-box-usd" size="sm" class="q-mr-sm" /><span v-if="!privacy">{{ vaultId }}</span><span v-if="privacy">🙈🙈🙈🙈🙈🙈🙈🙈🙈🙈🙈🙈🙈🙈🙈🙈🙈🙈🙈🙈🙈🙈🙈🙈</span></div>
              </div>
            </div>
          </q-card-section>

          <q-separator />

          <q-card-section class="q-px-none">
            <q-list>
              <q-item dense>
                <q-item-section avatar class="text-grey-6">
                  Type
                </q-item-section>

                <q-item-section class="text-grey-6">
                  Event &amp; Channels
                </q-item-section>

                <q-item-section side class="text-grey-6">
                  Actions
                </q-item-section>
              </q-item>
              <span v-for="trigger in triggerList" :key="trigger.triggerId">
              <q-item>
                <q-item-section top avatar>
                  <q-avatar v-if="trigger.type == 'info'" color="orange" text-color="white" icon="fal fa-siren-on" />
                  <q-avatar v-if="trigger.type == 'warning'" color="negative" text-color="white" icon="fal fa-bomb" />
                </q-item-section>

                <q-item-section>
                  <q-item-label>Vault drops <span class="text-primary">below {{ trigger.ratio }} %</span></q-item-label>
                  <div v-for="gateway in trigger.gateways" :key="gateway.gatewayId">
                    <q-avatar v-if="gateway.type == 'telegram'" style="background-color: #0088cc;" class="q-my-sm q-mr-sm" text-color="white" icon="fab fa-telegram-plane" size="sm" />
                  </div>
                </q-item-section>

                <q-item-section side top>
                  <q-avatar  text-color="white" icon="fal fa-trash" size="md" />
                </q-item-section>
              </q-item>
              <q-separator inset="item" v-if="trigger.triggerId != triggerList[lastKeyOfObject(triggerList)].triggerId" />
              </span>
            </q-list>

            <!--
            <div class="text-body1">When vault drops <span class="text-primary">below {{ trigger.ratio }} %</span></div>
            <q-chip icon="fab fa-telegram" style="background-color: #0088cc;" text-color="white" v-for="gateway in trigger.gateways" :key="gateway.gatewayId">
              Telegram
            </q-chip>

            <p>
              Type: {{ trigger.type }}
            </p>
            -->


          </q-card-section>

        </q-card>
      </transition-group>
    <!--
    </span> -->

  </div>
</template>

<script>
import { defineComponent } from 'vue'
import { mapGetters } from 'vuex'
import { openURL } from 'quasar'

export default defineComponent({
  name: 'ManageNotifications',
  data () {
    return {
      gateways: [],
      triggers: [],
      waitingForTelegramGateway: false,
      waitingForTelegramLoop: null,
    }
  },
  created() {
    this.$store.dispatch('setHeadline', { text: 'Notification settings', icon: 'fal fa-sliders-h'})
    this.getGateways()
    this.getTriggers()
  },
  methods: {
    getGateways(callback = false) {
      this.$api.get("/user/gateways")
        .then((result) => {
          this.gateways = result.data.data
          if (typeof callback === 'function') {
            callback()
          }
        })
    },
    getTriggers() {
      this.$api.get("/user/notification")
        .then((result) => {
          this.triggers = result.data.data
          return this
        })
    },
    checkForTelegramGateway() {
        this.waitingForTelegramLoop = setInterval(() => {
          this.getGateways(()=> {
            if (this.hasTelegramGateway) {
              clearTimeout(this.waitingForTelegramLoop)
              this.makeTelegramTemplate()
            }
          })
        }, 2500)
    },
    openTelegram() {
      openURL(process.env.TELEGRAM_BOT_LINK + '?start=' + this.userId)
    },
    makeTelegramTemplate() {
      this.vaults.forEach((vault) => {
        const minCollateral = vault.loanScheme.minCollateral
        let triggerConfig = {
          "vaultId": vault.vaultId,
          "ratio": minCollateral * 1.5,
          "type": "info",
          "gateways": [ this.telegramGateway.gatewayId ]
        }
        this.makeNotification(triggerConfig)

        triggerConfig.ratio = minCollateral * 1.25
        triggerConfig.type = "warning"
        this.makeNotification(triggerConfig)
      })
    },
    makeNotification(triggerConfig) {
      this.$api.post("/user/notification", triggerConfig)
        .then((result) => {
          console.log = result.data
          this.getTriggers()
        })
        .catch((error) => {
          console.log(error)
        })
    },
    lastKeyOfObject(object) {
      var last = (last = Object.keys(object))[last.length - 1]
      return last
    },
  },
  computed: {
    locale: function() {
      return this.$root.$i18n.locale
    },
    hasGateways: function() {
      return (this.gateways.length > 0)
    },
    hasTriggers: function() {
      return (this.triggers.length > 0)
    },
    hasTelegramGateway: function() {
      return this.gateways.some(function(gateway) {
        return gateway.type == 'telegram';
      });
    },
    telegramGateway: function() {
      function isTelegram(gateway) {
        return gateway.type === 'telegram'
      }
      return this.gateways.find(isTelegram)
    },
    /**
     * returns an object, containing every vault id as key and an array of sorted triggers
     */
    triggersByVault: function() {
      let vaultTriggers = {}
      this.triggers.forEach(function (trigger) {
        if (typeof vaultTriggers[trigger.vaultId] == 'undefined') {
          vaultTriggers[trigger.vaultId] = []
        }
        vaultTriggers[trigger.vaultId].push(trigger)
        vaultTriggers[trigger.vaultId].sort(( a, b ) => {
          if ( a.ratio < b.ratio ){return 1}
          if ( a.ratio > b.ratio ){return -1}
          return 0;
        })
      })
      return vaultTriggers
    },
    privacy() {
      return this.settingValue('privacy')
    },
    ...mapGetters({
      vaults: 'account/vaults',
      userId: 'account/userId',
      settingValue: 'settings/value',
    }),
  }
})
</script>

<style lang="sass" scoped>
.q-card.vault
    min-width: 290px
    max-width: 23%

body.screen--xs
  .q-card
    width: 100%
    max-width: inherit

body.screen--sm
  .q-card
    width: 31%
</style>
