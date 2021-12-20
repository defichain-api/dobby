<template>

  <q-pull-to-refresh
    @refresh="refresh"
    color="white"
    bg-color="primary"
    icon="autorenew"
  >
    <NoNotificationGateways v-if="!requestRunning" />

    <div class="q-pa-md row items-start q-gutter-md">
      <!-- Show hint when no user is set -->
      <q-card flat :bordered="$q.dark.isActive" v-if="vaults.length == 0 && !isDemo && !requestRunning">
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
      <q-card flat :bordered="$q.dark.isActive" v-if="userId == demoAccountID">
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

      <!-- Beta hint -->
      <q-card flat v-if="vaults.length > 0 && userId != demoAccountID" style="width: 100%;">
        <q-card-section>
          <div class="text-h5 q-mb-md"><q-icon name="fal fa-flask-potion" /> Beta Dobby</div>
          <p>
            Dobby is so proud to show you this beta version of himself! But please keep in mind that he may be buggy. Don't trust him blindly!<br />
            Feel free to join Dobby's developers at their <a href="https://t.me/defichain_dobby" target="_blank">Telegram channel</a> for questions and feedback.
            And please consider following his <a href="https://twitter.com/dobby_dfi" target="_blank">Twitter account @dobby_dfi</a> for some updates and insights<br />
            <br />
            Oh, and by the way: Thank you so much for accepting our Community Fund Proposal! We're very proud to be part of this journey.
          </p>
          <p>
            Adrian, Chris &amp; Michael
          </p>
        </q-card-section>
      </q-card>

    </div>

    <q-separator inset />

    <div class="q-pa-md row items-start q-gutter-md">
      <transition-group
        appear
        enter-active-class="animated pulse"
      >
        <q-card
          flat
          :bordered="$q.dark.isActive"
          class="q-mb-md vault"
          v-for="vault in vaults" :key="vault.vaultId"
        >

          <q-inner-loading
            :showing="requestRunning"
            color="primary"
          />

          <q-card-section
            class="q-py-xs"
            style="height: 15px"
            :class="{'bg-positive': vault.state == 'active', 'bg-warning': vault.state == 'mayLiquidate', 'bg-negative': vault.state == 'inLiquidation'}"
          >
          </q-card-section>

          <q-card-section>
            <div class="row no-wrap text-left">
              <div class="col-12">
                <div class="ellipsis" :class="{'text-h5': vault.name.length > 0, 'text-caption': vault.name.length == 0}">
                  <q-icon name="fal fa-box-usd" size="sm" class="q-mr-sm" />
                  <span v-if="vault.name.length > 0">{{ vault.name }}</span>
                  <span v-else>
                    <span v-if="!privacy">{{ vault.vaultId }}</span>
                    <span v-else>🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦</span>
                  </span>
                </div>
              </div>
            </div>
          </q-card-section>

          <q-separator />

          <q-card-section class="main-info" v-if="vault.state != 'inLiquidation'">
            <div class="row items-center no-wrap">
              <div :class="{'col-4': vault.state != 'inLiquidation', 'col-12': vault.state == 'inLiquidation'}">
                <div class="text-h6"><span v-if="!privacy || vault.loanValue == 0 ">{{ vault.loanValue.toLocaleString(locale, numberFormats.currency) }}</span><span v-if="privacy && vault.loanValue > 0">$ <span class="text-body1">🧦🧦🧦</span></span></div>
                <div class="caption">Loan Value</div>
              </div>
              <div v-if="vault.state != 'inLiquidation' && vault.loanValue > 0" class="col-8 text-right">
                <div class="text-h3 text-primary">{{ vault.collateralRatio }} %</div>
                <div class="caption">Coll. Ratio</div>
              </div>
            </div>
            <div class="row q-mt-md">
              <q-linear-progress v-if="vault.state != 'inLiquidation'" size="md" :value="awayFromLiqudationRelative(vault)" :color="trackColor(vault)" :track-color="trackColor(vault)" />
              <q-linear-progress v-if="vault.state == 'inLiquidation'" size="md" :value="0" :color="trackColor(vault)" :track-color="trackColor(vault)" />
              <div class="row full-width">
                <div class="col-2 text-left">{{ vault.loanScheme.minCollateral }} %</div>
                <div class="col-8 text-center"><span v-if="!privacy" class="text-primary">+ {{ awayFromLiqudationTotal(vault).toLocaleString(locale, numberFormats.currency) }}</span><span v-if="privacy">🧦🧦🧦</span></div>
                <div class="col-2 text-right">{{ vault.loanScheme.minCollateral * overCollateralizationFactor}} %</div>
              </div>
            </div>
          </q-card-section>

          <q-card-section class="main-info" v-if="vault.state == 'inLiquidation'">
            <div class="row items-center no-wrap">
              <div class="col">
                <div class="text-h6">In Liquidation</div>
                <div class="text-subtitle2">At Block Height {{ vault.liquidationHeight }}</div>
              </div>
            </div>
          </q-card-section>

          <q-separator />

          <q-card-section class="coll-progress q-py-sm">
            <div v-if="vault.loanValue > 0">
              <div class="row">
                <div class="col-12 text-caption q-mb-md">
                  <span class="text-overline">This vault...</span><br />
                  <ul>
                    <li>is <span class="text-primary">{{ awayFromLiqudation(vault) }} %</span> over liquidation collateral</li>
                  </ul>
                  <span class="text-overline">will liquidate when...</span>
                  <ul>
                    <li>losing <span v-if="!privacy" class="text-primary">{{ awayFromLiqudationTotal(vault).toLocaleString(locale, numberFormats.currency) }}</span><span v-if="privacy">🧦🧦🧦</span> of collateral</li>
                    <li>collateral's worth falls below <span v-if="!privacy" class="text-primary">{{ minCollateralAmount(vault).toLocaleString(locale, numberFormats.currency) }}</span><span v-if="privacy">🧦🧦🧦</span></li>
                    <li>collateral's prices drop by <span class="text-primary">{{ maximumPriceDrop(vault).toLocaleString(locale, {minimumFractionDigits: 1, maximumFractionDigits: 1}) }} %</span></li>
                  </ul>
                </div>
                <!--
                <div class="col-12 text-center text-subtitle2">
                  <span v-if="vault.state != 'inLiquidation'" style="font-size: 1em" class="text-primary">
                    {{ awayFromLiqudationTotal(vault).toLocaleString(locale, numberFormats.currency) }} ({{ awayFromLiqudation(vault) }} %) To Liquidation
                  </span>
                  <span v-if="vault.state == 'inLiquidation'">oh, oh 😭</span>
                </div>
                -->
                <!-- <div class="col-2 text-right" style="font-size: 0.8em">{{ vault.loanScheme.minCollateral * overCollateralizationFactor}} %</div> -->
              </div>
              <!--
              <q-linear-progress v-if="vault.state != 'inLiquidation'" size="md" :value="awayFromLiqudationRelative(vault)" :color="trackColor(vault)" :track-color="trackColor(vault)" />
              <q-linear-progress v-if="vault.state == 'inLiquidation'" size="md" :value="0" :color="trackColor(vault)" :track-color="trackColor(vault)" />
              <div class="row">
                <div class="col-6 text-left">{{ vault.loanScheme.minCollateral }} %</div>
                <div class="col-6 text-right">{{ vault.loanScheme.minCollateral * overCollateralizationFactor}} %</div>
              </div>
              -->
            </div>
          </q-card-section>

          <q-separator />

          <q-card-section class="coll-info row">
            <div class="col-7">
              <span class="text-h6 text-primary" v-if="vault.state != 'inLiquidation'"><span v-if="!privacy">{{ vault.collateralValue.toLocaleString(locale, numberFormats.currency) }}</span><span v-if="privacy">$🧦🧦🧦</span></span>
              <span class="text-h6 text-primary" v-if="vault.state == 'inLiquidation'">Funds Frozen</span>
              <div class="caption">Collateral Amount</div>
            </div>
            <div class="col-5" v-if="vault.state != 'inLiquidation'">
              <span class="text-h6 text-primary" v-if="vault.state != 'inLiquidation'">{{ vault.loanScheme.minCollateral }} %</span>
              <span class="text-h6 text-primary" v-if="vault.state == 'inLiquidation'">Funds Frozen</span>
              <div class="caption">Min Coll. Ratio</div>
            </div>
            <p class="col-6 text-subtitle2">
            </p>
          </q-card-section>

          <q-separator />
          <!--
          <q-card-section class="coll-info row">
            ADD collateral worth $14444 to reach 400%
            ---
            REMOVE collateral worth $8234 to reach 400%
          </q-card-section>
          -->
          <!--
          <q-card-section
            class="q-py-xs"
            style="height: 15px"
            :class="{'bg-positive': vault.state == 'active', 'bg-warning': vault.state == 'mayLiquidate', 'bg-negative': vault.state == 'inLiquidation'}" >
          </q-card-section>
          -->
        </q-card>

      </transition-group>
    </div>
    <q-separator inset />
    <div class="q-pa-md" v-if="vaults.length > 0">
      <q-btn
        outline
        rounded
        dense
        to="manage-vaults"
        icon="fas fa-plus-circle"
        class="text-center full-width"
        color="primary"
        label="add another vault"
      />
    </div>
  </q-pull-to-refresh>
</template>

<script>
import NoNotificationGateways from 'src/components/Dashboard/NoNotificationGateways.vue'

import { defineComponent } from 'vue';
import { mapGetters, mapActions } from 'vuex'

export default defineComponent({
  name: 'PageIndex',
  components: {
    NoNotificationGateways,
  },
  data () {
    return {
      loans: [],
      overCollateralizationFactor: 3,
      numberFormats: {
        currency: { style: 'currency', currency: 'USD', minimumFractionDigits: 2, maximumFractionDigits: 2 },
      },
      demoAccountID: process.env.DEMO_ACCOUNT_ID,
      darkMode: this.$q.dark.isActive,
      autoReload: null,
    }
  },
  created() {
    this.$store.dispatch('setHeadline', {text: 'Your Vaults', icon: 'fal fa-box-usd'})
    this.autoReload = setInterval(() => {
      if (process.env.DEV) { console.log("[DEBUG] Fetching latest data from API") }
      this.$store.dispatch('account/loadUserData')
    }, 60 * 1000)
  },
  unmounted() {
    clearInterval(this.autoReload)
  },
  methods: {
    async refresh(done) {
      await this.$store.dispatch('account/loadUserData')
      setTimeout(() => {
        done()
      }, 1000)
    },
    async prepareDemo() {
      await this.$store.dispatch('account/setUserId', this.demoAccountID)
      this.$store.dispatch('account/loadUserData')
    },
    trackColor(vault) {
      let color = ''
      if (vault.state == 'active') {
        color = 'positive'
      } else if (vault.state == 'mayLiquidate') {
        color = 'warning'
      } else if (vault.state == 'inLiquidation') {
        color = 'negative'
      }
      return color
    },
    awayFromLiqudation(vault) {
      return vault.collateralRatio - vault.loanScheme.minCollateral
    },
    awayFromLiqudationTotal(vault) {
      // min coll amount = loan Value * loan scheme
      // vault.loanValue,
      // vault.collateralValue
      return vault.collateralValue - this.minCollateralAmount(vault)
    },
    awayFromLiqudationRelative(vault) {
      return (this.awayFromLiqudation(vault) + vault.loanScheme.minCollateral) / (this.overCollateralizationFactor * vault.loanScheme.minCollateral)
    },
    minCollateralAmount(vault) {
      return vault.loanValue * (vault.loanScheme.minCollateral / 100)
    },
    maximumPriceDrop(vault) {
      //vault.collateralValue
      // awayFromLiqudationTotal
      // = (1 - (loanScheme / currentCollateral)) * 100
      return ((100 * this.awayFromLiqudationTotal(vault)) / vault.collateralValue)
    }
  },
  computed: {
    locale: function() {
      return this.$root.$i18n.locale
    },
    privacy() {
      return this.settingValue('privacy')
    },
    isDemo() {
      return this.demoAccountID == this.userId
    },
    ...mapGetters({
      vaults: 'account/vaults',
      userId: 'account/userId',
      settingValue: 'settings/value',
      requestRunning: 'requestRunning',
    }),
  }

})
</script>

<style lang="sass" scoped>
  a:hover a:link a:visited
    color: #000000

  .q-card.vault
    min-width: 290px

    max-width: 23%

    .main-info
      min-height: 105px

    .coll-progress

    .coll-info
      min-height: 100px

  body.screen--xs
    .q-card
      width: 100%
      max-width: inherit

  body.screen--sm
    .q-card
      width: 31%

  ul
    margin: 0
    padding-left: 1em
</style>
