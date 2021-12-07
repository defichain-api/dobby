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

        <q-btn v-if="!waitingForTelegramGateway && !isDemo" unelevated class="full-width" icon="fab fa-telegram" label="Connect to Telegram now" color="primary" @click="checkForTelegramGateway(); openTelegram(); waitingForTelegramGateway = true;"></q-btn>
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

  <div v-if="hasGateways" class="q-pa-md container">
    <q-card flat :bordered="$q.dark.isActive" class="notifications">
      <q-card-section>
          <div class="text-h5">Your Notification Channels</div>
      </q-card-section>

      <q-separator />

      <q-card-section class="container">
        <div class="row">
          <div class="col-6">
            <div class="text-body1">Connected</div>
            <q-chip v-if="hasTelegramGateway" icon="fab fa-telegram-plane" color="telegram" text-color="white">
              Telegam
            </q-chip>
          </div>
          <div class="col-6">
            <div class="text-body1">Available Soon</div>
            <q-chip disabled icon="fal fa-mailbox" style="" color="green" text-color="white">
              &nbsp; email
            </q-chip>
            <q-chip disabled icon="fal fa-send-back" color="red" text-color="white">
              &nbsp; webhook
            </q-chip>
          </div>
        </div>
      </q-card-section>

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
          <q-card-section class="text-subitle2">
            <q-btn rounded outline @click="testTelegram()" label="test Telegram" icon="fab fa-telegram-plane" color="telegram"></q-btn>
          </q-card-section>
        </div>
      </q-slide-transition>
    </q-card>

  </div>

  <q-separator inset />

  <div v-if="hasGateways && vaultsWithoutTriggers.size > 0" class="q-pa-md container">
    <div class="row">
      <div class="text-h5 col-12 q-mb-md">
        Add Notifications
        <q-badge class="q-ml-xs" color="primary" align="top">{{ vaultsWithoutTriggers.size }}</q-badge>
      </div>
    </div>
    <div class="row q-mb-md">
      Dobby noticed that you added vaults for him to monitor, but you haven't set up notifications yet.
    </div>
    <div class="row q-gutter-md items-start">
      <transition-group
        appear
        enter-active-class="animated pulse"
      >
        <q-card flat :bordered="$q.dark.isActive" v-for="vault in vaultsWithoutTriggers" :key="vault.vaultId" class="vault">
          <q-card-section class="container">
            <div class="row">
              <div class="col-2 text-center q-pt-sm">
                <q-icon name="fal fa-box-usd" size="sm" />
              </div>
              <div class="col-10">
                <div class="row">
                  <div class="col-12">Add Notifications for vault</div>
                </div>
                <div class="row">
                  <div class="col-12 text-caption ellipsis">
                    <span v-if="!privacy">{{ vault.vaultId }}</span>
                    <span v-if="privacy">🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦</span>
                  </div>
                </div>
              </div>
            </div>
          </q-card-section>

          <q-separator />

          <q-card-section class="container">
            <div class="row">
              <div class="col-6 text-right q-pr-sm">
                <span class="text-body1 text-primary" v-if="!privacy">{{ vault.collateralValue.toLocaleString(locale, numberFormats.currency) }}</span>
                <span class="text-body1 text-primary" v-if="privacy">$🧦🧦🧦</span>
                <br />
                Collateral value
              </div>
              <div class="col-6 q-pl-sm" style="border-left: 1px solid rgba(0,0,0,0.3)">
                <span class="text-body1 text-primary">{{ vault.loanScheme.minCollateral }} %</span>
                <br />
                Min collateral
              </div>
            </div>
          </q-card-section>

          <q-separator />

          <q-card-section class="container">
            <div class="row">
              <div class="col-12 text-center q-mb-md">
                Dobby will add these notifications:
              </div>
            </div>
            <div class="row">
              <div class="col-6 text-right q-pr-sm">
                <q-avatar color="orange" text-color="white" size="md" icon="fal fa-siren-on" />
                <br />
                info
              </div>
              <div class="col-6 q-pl-sm q-mb-md">
                <span class="text-h6 text-primary">&lt; {{ Math.ceil(vault.loanScheme.minCollateral * this.triggerMultipleInfo) }} %</span><br />
                when below
              </div>
            </div>
            <q-separator inset class="q-mb-md" />
            <div class="row">
              <div class="col-6 text-right q-pr-sm">
                <q-avatar color="negative" text-color="white" size="md" icon="fal fa-bomb" />
                <br />
                warning
              </div>
              <div class="col-6 q-pl-sm">
                <span class="text-h6 text-primary">&lt; {{ Math.ceil(vault.loanScheme.minCollateral * this.triggerMultipleWarning) }} %</span><br />
                when below
              </div>
            </div>
          </q-card-section>

          <q-separator />

          <q-card-actions>
            <q-btn rounded outline color="primary" icon="fal fa-bells" label="add notifications" class="full-width" @click="addNotifications(vault)"></q-btn>
          </q-card-actions>
        </q-card>
      </transition-group>
    </div>
    </div>

  <q-separator inset />

  <div v-if="hasTriggers" class="q-pa-md container">
    <div class="row q-mb-md">
      <div class="text-h5 col-12">Your Notifications<q-badge class="q-ml-xs" color="primary" align="top">{{ triggers.length }}</q-badge></div>
    </div>

    <div class="row q-mb-md">
      These are your vaults, containing lists of notifications. It shows the type of
      the notification, when it occurs and on which channels you'll receive notifications.<br />
      <br />
      You'll be able to edit these in a future version of Dobby.
      If you changed your loan scheme and wand to change your notification trigger points, too, then simply go to the 'manage vaults' section, remove it and add it back again.
    </div>

    <div class="row items-start q-gutter-md">
    <transition-group
      appear
      enter-active-class="animated pulse"
    >
      <q-card flat v-for="(triggerList, vaultId) in triggersByVault" :key="vaultId" :bordered="$q.dark.isActive" class="vault">
        <q-card-section class="container">
          <div class="row text-center">
            <div class="col-12">
              <div class="text-caption ellipsis"><q-icon name="fal fa-box-usd" size="sm" class="q-mr-sm" /><span v-if="!privacy">{{ vaultId }}</span><span v-if="privacy">🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦</span></div>
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
                <!--Actions-->
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
                  <q-avatar v-if="gateway.type == 'telegram'" color="telegram" class="q-my-sm q-mr-sm" text-color="white" icon="fab fa-telegram-plane" size="sm" />
                </div>
              </q-item-section>

              <q-item-section side top>
                <!--<q-avatar text-color="grey" icon="fal fa-trash" size="md" />-->
              </q-item-section>
            </q-item>
            <q-separator inset="item" v-if="trigger.triggerId != triggerList[lastKeyOfObject(triggerList)].triggerId" />
            </span>
          </q-list>
        </q-card-section>

      </q-card>
    </transition-group>
    </div>
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
      triggerMultipleInfo: 1.5,
      triggerMultipleWarning: 1.25,
      waitingForTelegramGateway: false,
      waitingForTelegramLoop: null,
      testChannelExpanded: false,
      numberFormats: {
        currency: { style: 'currency', currency: 'USD', minimumFractionDigits: 2, maximumFractionDigits: 2 },
      },
    }
  },
  created() {
    this.$store.dispatch('setHeadline', { text: 'Dobby likes to talk', icon: 'fal fa-bells'})
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
            this.makeTelegramNotifications()
          }
        })
      }, 2500)
    },
    openTelegram() {
      openURL(process.env.TELEGRAM_BOT_LINK + '?start=' + this.userId)
    },
    testTelegram() {
      this.$api.post("/user/gateways/test", { "type":"telegram" })
        .then((result) => {
          this.$q.notify({
            group: 'telegram',
            type: 'positive',
            message: 'Dobby should have said hello via Telegram :)',
          })
        })
    },
    addNotifications(vault) {
      const minCollateral = vault.loanScheme.minCollateral
      let triggerConfig = {
        "vaultId": vault.vaultId,
        "ratio": Math.ceil(minCollateral * this.triggerMultipleInfo),
        "type": "info",
        "gateways": [ this.telegramGateway.gatewayId ]
      }
      this.makeNotification(triggerConfig)

      triggerConfig.ratio = Math.ceil(minCollateral * this.triggerMultipleWarning)
      triggerConfig.type = "warning"
      this.makeNotification(triggerConfig)
    },
    makeTelegramNotifications() {
      this.vaults.forEach((vault) => {
        this.addNotifications(vault)
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
    privacy: function() {
      return this.settingValue('privacy')
    },
    hasVaultsWithoutTriggers: function() {
      return false
    },
    vaultsWithoutTriggers: function() {
      let vaultList = new Set()
      this.vaults.forEach((vault) => {
        if(!(vault.vaultId in this.triggersByVault)) {
          vaultList.add(vault)
        }
      })
      /*
      let vaultList = {}
      this.vaults.forEach((vault) => {
        if(!(vault.vaultId in this.triggersByVault)) {
          vaultList[vault.vaultId] = vault
        }
      })
      */
      return vaultList
    },
    isDemo: function() {
      return process.env.DEMO_ACCOUNT_ID == this.userId
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
.q-card.notifications
    min-width: 400px

.q-card.vault
    min-width: 300px
    max-width: 400px

body.screen--xs
  .q-card
    width: 100%
    max-width: inherit
  .q-card.notifications
    min-width: inherit

body.screen--sm
  .q-card
    width: 31%
</style>
