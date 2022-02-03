<template>

  <q-pull-to-refresh
    @refresh="refresh"
    color="white"
    bg-color="primary"
    icon="autorenew"
  >
    <NoNotificationGateways v-if="!requestRunning && !isDemo" />

    <div class="q-pa-md row q-gutter-md">
      <!-- Show hint when no user is set -->
      <q-card flat v-if="vaults.size == 0 && !isDemo && !requestRunning">
        <q-img src="/img/banner.jpg">
          <div class="absolute-bottom text-h6">
            No vaults set yet
          </div>
        </q-img>

        <q-card-section>
          <p>
            There's nothing to see on the dashboard until you have set up your first vault.
          </p>
        </q-card-section>
        <q-card-actions class="text-center">
          <q-btn
            to="manage-vaults"
            unelevated
            color="primary"
            label="add a vault"
            icon="fas fa-plus-circle"
            class="full-width"
          />
        </q-card-actions>
      </q-card>

      <!-- Show hint when demo user is active -->
      <q-card flat v-if="isDemo">
        <q-card-section>
          <div class="text-h6">Demo Mode</div>
          <div class="text-subtitle2">take your time to look around :)</div>
        </q-card-section>

        <q-separator inset />

        <q-card-section>
          <p>
            Dobby will be ready to monitor your own vaults when you are. Just go to the setup wizard again whenever you like.
          </p>
          <q-btn to="setup" unelevated color="primary" label="Go To Setup Wizard" icon="fal fa-wand-magic" class="full-width"></q-btn>
        </q-card-section>
      </q-card>

      <q-card flat v-if="frozenVaults.size > 0">
        <q-card-section
          class="q-py-xs bg-blue"
          style="height: 15px"
        />
        <q-card-section>
          <div class="text-h6 text-blue">Frozen vaults</div>
        </q-card-section>
        <q-card-section>
          Dobby has detected that you've got frozen vaults.
          That's no reason to panic. This happens due to several reasons.
          The most common one is that some price oracle did not respond or a price dropped unusally fast.
          To protect affected vaults, DeFiChain has automatically put them into freezing state.
          This will be undone when everything is fine again.
        </q-card-section>
      </q-card>

      <q-card flat>
        <q-inner-loading
          :showing="requestRunning"
          color="primary"
        />
        <q-card-section class="q-py-sm">
          <div class="text-body1"><q-icon name="fal fa-hourglass-half" class="q-mr-sm" />Next Price Tick in...</div>
        </q-card-section>
        <q-separator inset />
        <q-card-section class="row q-py-sm text-center">
          <div class="col-5">
            <div class="text-primary text-h4">{{ nextTick.minutes_left }} min</div>
          </div>
          <div class="col-3">
            <div>at block</div>
            <div class="text-primary text-body1">{{ nextTick.block_height }}</div>
          </div>
          <div class="col-4">
            <div>last tick</div>
            <div class="text-primary text-body1">{{ moment(nextTick.time).format('LTS') }}</div>
          </div>
        </q-card-section>
      </q-card>

    </div>

    <q-separator class="q-mb-md" inset />

    <div class="q-px-md" v-if="showVaultsAsCarousel">
      <q-carousel
        v-model="slide"
        transition-prev="slide-right"
        transition-next="slide-left"
        swipeable
        animated
        control-color="primary"
        control-type="flat"
        navigation-icon="far fa-circle"
        navigation-active-icon="fas fa-circle"
        navigation
        padding
        class="bg-transparent rounded-borders q-mt-none"
        :height="slideHeight + 60 + 'px'"
      >
        <q-carousel-slide
          :name="'slide-' + index"
          class="q-pa-none q-ma-none"
          v-for="(vault, index) in vaults" :key="vault.vaultId"
        >
          <Vault :vault="vault" v-model:height="slideHeight" />
        </q-carousel-slide>
      </q-carousel>
    </div>

    <div
      v-if="!showVaultsAsCarousel"
      class="row"
      :class="{'q-gutter-md q-mx-none': $q.screen.gt.xs, 'q-mx-none q-mr-md q-gutter-md': $q.screen.lt.sm}"
    >
      <Vault
        :vault="vault"
        v-for="vault in vaults"
        :key="vault.vaultId"
      />
    </div>

    <q-separator class="q-mt-md" inset />

    <div class="q-pa-md" v-if="vaults.size > 0">
      <q-btn
        outline
        rounded
        :dense="$q.screen.lt.sm"
        to="manage-vaults"
        icon="fas fa-plus-circle"
        class="text-center"
        :class="{'full-width': $q.screen.lt.sm}"
        color="primary"
        label="add another vault"
      />
    </div>
  </q-pull-to-refresh>
</template>

<script>
import NoNotificationGateways from 'src/components/Dashboard/NoNotificationGateways.vue'
import Vault from 'src/components/Dashboard/Vault.vue'

import { defineComponent } from 'vue';
import { mapGetters } from 'vuex'

import moment from 'moment'

export default defineComponent({
  name: 'PageIndex',
  components: {
    NoNotificationGateways,
    Vault,
  },
  data () {
    return {
      slide: "slide-0",
      loans: [],
      demoAccountID: process.env.DEMO_ACCOUNT_ID,
      autoReload: null,
      slideHeight: 0,
    }
  },
  created() {
    this.$store.dispatch('setHeadline', {text: 'Your Vaults', icon: 'fal fa-box-usd'})
    this.autoReload = setInterval(() => {
      if (process.env.DEV) { console.log("[DEBUG] Fetching latest data from API") }
      this.$store.dispatch('account/loadUserData')
      this.$store.dispatch('chain/fetch')
    }, 60 * 1000)
    this.moment = moment
  },
  unmounted() {
    clearInterval(this.autoReload)
  },
  methods: {
    async refresh(done) {
      await this.$store.dispatch('account/loadUserData')
      await this.$store.dispatch('chain/fetch')
      setTimeout(() => {
        done()
      }, 1000)
    },
  },
  computed: {
    showVaultsAsCarousel() {
      if (this.settingsValue('dashboardCardsAsCarousel') == 'auto') {
        return this.$q.screen.lt.sm
      }
      return this.settingsValue('dashboardCardsAsCarousel')
    },
    isDemo() {
      return this.demoAccountID == this.userId
    },
    frozenVaults() {
      let vaultList = new Set()
      this.allVaults.forEach((vault) => {
        if(vault.state == 'frozen') {
          vaultList.add(vault)
        }
      })
      return vaultList
    },
    vaults() {
      let vaultList = new Set()
      this.allVaults.forEach((vault) => {
        if (vault.state != 'inactive') {
          vaultList.add(vault)
        }
      })
      return vaultList
    },
    ...mapGetters({
      allVaults: 'account/vaults',
      userId: 'account/userId',
      settingsValue: 'settings/value',
      requestRunning: 'requestRunning',
      nextTick: 'chain/nextTick',
    }),
  }
})
</script>

<style lang="sass" scoped>
  a:hover a:link a:visited
    color: #000000

  .q-carousel
    width: 100%

  .screen--xs
    .q-card
      width: 100%

  .screen--sm
    .q-card
      width: 47vw

  .screen--md
    .q-card
      width: 31vw

  .screen--lg
    .q-card
      width: 23vw

  .q-carousel
    .q-card
      width: 100%

</style>
