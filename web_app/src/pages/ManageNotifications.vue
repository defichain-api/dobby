<template>
  <div class="q-pa-md row items-start q-gutter-md">

    <q-list bordered padding v-if="vaults.length > 0" class="q-mt-md"
      :class="{'bg-white' : !this.$q.dark.isActive, 'bg-dark': this.$q.dark.isActive}"
    >
      <!--
      <q-item-label header>Your Addresses/Vaults</q-item-label>
      <q-separator spaced />
      -->
      <span v-for="(vault) in vaults" :key="vault.vaultId">
        <transition
          appear
          enter-active-class="animated pulse"
        >
          <q-item>
            <q-item-section top avatar class="text-center">
              <q-avatar
                :class="{'bg-positive': vault.state == 'active', 'bg-warning': vault.state == 'mayLiquidate', 'bg-negative': vault.state == 'inLiquidation'}"
                text-color="white" icon="fas fa-archive"></q-avatar>
            </q-item-section>

            <q-item-section style="word-break: break-all;">
              <div class="row text-caption">
                Notification Name
              </div>
              <div class="row q-mt-md text-grey">
                A detail (Email Address, Telegram Name, ...)
              </div>
            </q-item-section>

            <q-item-section top side>
              <div class="text-grey-8 q-gutter-xs">
                <q-btn @click="removeVault(vault.vaultId)" size="md" flat dense round icon="delete" color="secondary" />
              </div>
            </q-item-section>
          </q-item>
        </transition>
        <q-separator spaced  />
      </span>
    </q-list>

  </div>
</template>

<script>
import { defineComponent } from 'vue'
import { mapGetters, mapActions } from 'vuex'

export default defineComponent({
  name: 'ManageNotifications',
  data () {
    return {
      addressToAdd: '',
    }
  },
  created() {
    this.$store.dispatch('setHeadline', { text: 'Notification settings', icon: 'fal fa-sliders-h'})
  },
  computed: {
    locale: function() {
      return this.$root.$i18n.locale
    },
    ...mapGetters({
      vaults: 'account/vaults',
    }),
  }
})
</script>

<style lang="sass">

</style>
