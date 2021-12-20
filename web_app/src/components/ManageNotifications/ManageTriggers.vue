<template>
  <q-separator v-if="hasTriggers" />

  <q-expansion-item
    v-model="infoExpanded"
    expand-icon="fal fa-info-circle"
    expanded-icon="fal fa-times"
    header-class="q-pl-none q-mr-sm"
  >
    <template v-slot:header>
      <div class="text-h5 col-xs-12 col-sm-5">Your Notifications<q-badge class="q-ml-xs" color="primary" align="top">{{ triggers.length }}</q-badge></div>
    </template>
    <template v-slot:default>
      <q-card flat>
        <q-card-section class="container">
          <div class="row">
            <div class="col-2">
              <q-icon name="fal fa-info-circle" size="lg" />
            </div>
            <div class="col-10">
              The following cards are showing the configured notifications, separated by your vaults.
              They list the type of the notification, when it occurs and on which channels you'll receive notifications.<br />
            </div>
          </div>
        </q-card-section>
      </q-card>
    </template>
  </q-expansion-item>

  <div class="row q-gutter-md q-ml-none q-mt-none">
  <transition-group
    appear
    enter-active-class="animated pulse"
  >

    <q-card flat v-for="(triggerList, vaultId) in triggersByVault" :key="vaultId" :bordered="$q.dark.isActive" class="vault">
      <q-card-section class="container">
        <div class="row text-left">
          <div class="col-12">
            <div class="ellipsis" :class="{'text-h5': vault(vaultId).name.length > 0, 'text-caption': vault(vaultId).name.length == 0}">
              <q-icon name="fal fa-box-usd" size="sm" class="q-mr-sm" />
              <span v-if="vault(vaultId).name.length > 0">{{ vault(vaultId).name }}</span>
              <span v-else>
                <span v-if="!privacy">{{ vaultId }}</span>
                <span v-else>🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦</span>
              </span>
            </div>
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
                <q-item-label>
                  Vault drops <span class="text-primary">below {{ trigger.ratio }} %</span>
                  <EditTrigger
                    :triggerId="trigger.triggerId"
                    :ref="'editTrigger-' + trigger.triggerId"
                  />
                </q-item-label>
                <div v-for="gateway in trigger.gateways" :key="gateway.gatewayId">
                  <q-avatar v-if="gateway.type == 'telegram'" color="telegram" class="q-my-sm q-mr-sm" text-color="white" icon="fab fa-telegram-plane" size="sm" />
                </div>
              </q-item-section>

              <q-item-section side top>
                <q-avatar
                  clickable
                  icon="fal fa-edit"
                  size="md"
                  @click="showEditTrigger(trigger.triggerId)"
                />
                <q-avatar
                  icon="fal fa-trash"
                  size="md"
                  @click="showConfirmDeleteTrigger(trigger.triggerId)"
                />
              </q-item-section>
            </q-item>
            <q-separator inset="item" v-if="trigger.triggerId != triggerList[lastKeyOfObject(triggerList)].triggerId" />
          </span>
        </q-list>
      </q-card-section>
    </q-card>
  </transition-group>
  </div>
</template>

<script>
import EditTrigger from 'components/ManageNotifications/ManageTriggers/EditTrigger.vue'

import { mapGetters, mapActions } from 'vuex'

export default {
  name: 'ManageTriggers',
  components: {
    EditTrigger,
  },
  data() {
    return {
      infoExpanded: false,
      tmpTriggerRatio: 0,
    }
  },
  methods: {
    /**
     * Returns the last key of given object
     */
    lastKeyOfObject(object) {
      var last = (last = Object.keys(object))[last.length - 1]
      return last
    },

    /**
     * Toggles the edit dialog for trigger ratio
     */
    showEditTrigger(triggerId) {
      this.$refs['editTrigger-' + triggerId].show()
    },

    /**
     * Dispalys a confirmation dialog before deleting a notification
     */
    showConfirmDeleteTrigger(triggerId) {
      this.$q.dialog({
        title: 'Confirm notification removal',
        message: 'Do you really want to remove this notification?',
        color: 'primary',
        cancel: true,
        focus: 'cancel',
        persistent: true,
      }).onOk(() => {
        this.deleteTrigger(triggerId)
      })
    },

    /**
     * returns a specific vault, found by it's id
     */
    vault: function(vaultId) {
      return this.vaults.find(vault => vault.vaultId == vaultId);
    },

    ...mapActions({
      deleteTrigger: 'notifications/deleteTrigger'
    })
  },
  computed: {
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

    /**
     * Some Vuex getters
     */
    ...mapGetters({
      vaults: 'account/vaults',
      triggers: 'notifications/triggers',
      hasTriggers: 'notifications/hasTriggers',
      settingValue: 'settings/value',
    })
  },
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
