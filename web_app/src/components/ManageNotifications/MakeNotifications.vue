<template>
  <q-separator inset v-if="hasGateways && vaultsWithoutTriggers.size > 0" />

  <div v-if="hasGateways && missingTiggers.length > 0" class="container">
    <div class="row">
      <div class="text-h5 col-12 q-mb-md">
        Add Missing Notifications
        <q-badge class="q-ml-xs" color="primary" align="top">{{ missingTiggers.length }}</q-badge>
      </div>
    </div>
    <div class="row q-mb-md">
      Dobby noticed that at least one of your vaults is missing a notification trigger.
    </div>
    <div class="row q-gutter-md items-start">
      <transition-group
        appear
        enter-active-class="animated pulse"
      >
        <q-card flat :bordered="$q.dark.isActive" v-for="vault in missingTiggers" :key="vault.vaultId" class="vault">
          <q-card-section class="container">
            <div class="row">
              <div class="col-2 text-center q-pt-sm">
                <q-icon name="fal fa-box-usd" size="sm" />
              </div>
              <div class="col-10">
                <div class="ellipsis">
                  <span v-if="vault.name.length > 0" class="text-h6">{{ vault.name }}</span>
                  <span v-else class="text-caption">
                    <span v-if="!privacy">{{ vault.vaultId }}</span>
                    <span v-else>🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦</span>
                  </span>
                </div>
              </div>
            </div>
          </q-card-section>
          <q-separator />
          <q-card-section class="container">
            <q-btn
              color="primary"
              class="full-width"
              :label="'add ' + vault.missing + ' message when < ' + vault.triggerPoint + ' %'"
              :icon="(vault.missing == 'info') ? 'fal fa-siren-on' : 'fal fa-bomb'"
              @click="(vault.missing == 'info') ? addInfoTrigger(vault.vaultId) : addWarningTrigger(vault.vaultId)"
            />
          </q-card-section>
        </q-card>
      </transition-group>
    </div>
  </div>


  <div v-if="hasGateways && vaultsWithoutTriggers.size > 0" class="container">
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
                <div>Add Notifications for vault</div>
                <div class="text-caption ellipsis">
                  <span v-if="vault.name.length > 0">{{ vault.name }}</span>
                  <span v-else>
                    <span v-if="!privacy">{{ vault.vaultId }}</span>
                    <span v-else>🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦</span>
                  </span>
                  <!--
                  <span v-if="!privacy">{{ vault.vaultId }}</span>
                  <span v-if="privacy">🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦</span>
                  -->
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
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  name: 'MakeNotifications',
  methods: {
    addNotifications(vault) {
      const minCollateral = vault.loanScheme.minCollateral

      let triggerConfig = {
        "vaultId": vault.vaultId,
        "ratio": Math.ceil(minCollateral * this.triggerMultipleInfo),
        "type": "info",
        "gateways": [ this.gatewayType('telegram').gatewayId ]
      }
      this.makeNotification(triggerConfig)

      triggerConfig.ratio = Math.ceil(minCollateral * this.triggerMultipleWarning)
      triggerConfig.type = "warning"
      this.makeNotification(triggerConfig)
    },

    addInfoTrigger(vaultId) {
      const vault = this.vault(vaultId)
      const minCollateral = vault.loanScheme.minCollateral
      let triggerConfig = {
        "vaultId": vault.vaultId,
        "ratio": Math.ceil(minCollateral * this.triggerMultipleInfo),
        "type": "info",
        "gateways": [ this.gatewayType('telegram').gatewayId ]
      }
      this.makeNotification(triggerConfig).then(() => {
        this.$q.notify({
          type: 'positive',
          message: 'Notification trigger enabled',
        })
      })
    },

    addWarningTrigger(vaultId) {
      const vault = this.vault(vaultId)
      const minCollateral = vault.loanScheme.minCollateral
      let triggerConfig = {
        "vaultId": vault.vaultId,
        "ratio": Math.ceil(minCollateral * this.triggerMultipleWarning),
        "type": "warning",
        "gateways": [ this.gatewayType('telegram').gatewayId ]
      }
      this.makeNotification(triggerConfig).then(() => {
        this.$q.notify({
          type: 'positive',
          message: 'Notification trigger enabled',
        })
      })
    },

    makeNotification(triggerConfig) {
      return this.$api.post("/user/notification", triggerConfig)
        .then((result) => {
          this.fetchTriggers()
        })
    },

    /**
     * returns a specific vault, found by it's id
     */
    vault: function(vaultId) {
      return this.vaults.find(vault => vault.vaultId == vaultId);
    },

    ...mapActions({
      fetchTriggers: 'notifications/fetchTriggers',
    })
  },
  computed: {
    /**
     * Returns all vaults lacking all triggers
     */
    vaultsWithoutTriggers: function() {
      let vaultList = new Set()
      this.missingTiggers
      this.vaults.forEach((vault) => {
        if( !(vault.vaultId in this.triggersByVault)
            &&
            (vault.state != 'in_liquidation' && vault.state != 'inactive')
        ) {
          vaultList.add(vault)
        }
      })
      return vaultList
    },

    /**
     * Returns all vaults lacking at least one trigger
     */
    missingTiggers: function () {
      let vaultList = []

      for (const [key, vault] of Object.entries(this.triggersByVault)) {
        if (vault.length < 2) {
          let data = {
            vaultId: vault[0].vaultId,
            name: this.vault(vault[0].vaultId).name,
          }
          if (vault[0].type == 'warning') {
            data.missing = 'info'
            data.triggerPoint = Math.ceil(this.vault(vault[0].vaultId).loanScheme.minCollateral * this.triggerMultipleInfo)
          } else if (vault[0].type == 'info') {
            data.missing = 'warning'
            data.triggerPoint = Math.ceil(this.vault(vault[0].vaultId).loanScheme.minCollateral * this.triggerMultipleWarning)
          }
          vaultList.push(data)
        }
      }
      return vaultList
    },

    /**
     * Arranges triggers to vaults
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

    // returns true if privacy mode is enabled
    privacy: function() {
      return this.settingValue('privacy')
    },

    // returns the current locale
    locale: function() {
      return this.$root.$i18n.locale
    },

    // returns all configured number formats
    numberFormats: function () {
      return this.settingValue('numberFormats')
    },

    // how much % away from minimal collateral should an "information" message be triggered?
    triggerMultipleInfo: function() {
      return this.settingValue('triggerMultipleInfo')
    },

    // how much % away from minimal collateral should a "warning" message be triggered?
    triggerMultipleWarning: function() {
      return this.settingValue('triggerMultipleWarning')
    },

    /**
     * Some Vuex getters
     */
    ...mapGetters({
      vaults: 'account/vaults',
      triggers: 'notifications/triggers',
      hasGateways: 'notifications/hasGateways',
      gatewayType: 'notifications/gatewayType',
      settingValue: 'settings/value',
    })
  }
}
</script>

<style lang="sass" scoped>
  .q-card.vault
      min-width: 300px
      max-width: 400px

  body.screen--xs
    .q-card
      width: 100%
      max-width: inherit

  body.screen--sm
    .q-card
      width: 31%
</style>
