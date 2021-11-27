<template>
  <div class="q-pa-md text-h4" v-if="vaults.lenght > 0">Your Vaults <q-icon name="fas fa-archive" /> </div>
  <div class="q-pa-md row items-start q-gutter-md">

    <!-- Show hint when no user is set -->
    <q-card flat v-if="vaults.length == 0">
      <q-img src="/img/banner.jpg">
        <div class="absolute-bottom text-h6">
          No vaults set yet
        </div>
      </q-img>

      <q-card-section>
        <p>
          Seems like you're new. Welcome, friend!
        </p>
        <p>
          There's nothing to see on the dashboard until you have set up you dobby account.
        </p>
      </q-card-section>
      <q-card-actions class="text-center">
        <q-btn
          to="setup"
          unelevated
          color="primary"
          label="Go To Setup Wizard"
          icon="fal fa-wand-magic"
          class="full-width"
        />
        <q-btn
          outline
          rounded
          color="accent"
          label="Just Show Me A Demo"
          class="q-mt-md full-width"
          icon="fas fa-quidditch"
          size="sm"
          @click="prepareDemo"
        />
      </q-card-actions>
    </q-card>

    <!-- Show hint when demo user is active -->
    <!-- <q-banner  class="text-white bg-accent col-11" v-if="userId == 'demo-demo-demo-demo-demodemodemo'">
      <p>
        Alright, take your time to look around. Dobby will be ready to monitor your own vaults when you are. Just go to the setup wizard again whenever you like.
      </p>
      <template v-slot:action>
        <q-btn to="setup" unelevated color="primary" label="Go To Setup Wizard" icon="fal fa-wand-magic" class="full-width" />
      </template>
    </q-banner> -->
    <q-card flat v-if="userId == demoAccountID">
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
  </div>
  <div class="q-pa-md row items-start q-gutter-md">
    <!-- <q-card flat>
      <q-card-section>
        <q-btn color="primary" icon="chat" label="Change notification settings"></q-btn>
      </q-card-section>
    </q-card>-->
    <q-card v-for="vault in vaults" :key="vault.vaultId" flat>
      <q-card-section
        class="q-py-xs"
        style="height: 15px"
        :class="{'bg-positive': vault.state == 'active', 'bg-warning': vault.state == 'mayLiquidate', 'bg-negative': vault.state == 'inLiquidation'}"
      >
      </q-card-section>

      <q-card-section>
        <div class="row no-wrap text-center">
          <div class="col-12">
            <div class="text-caption">{{ vault.ownerAddress }}</div>
          </div>
        </div>
      </q-card-section>

      <q-separator />

      <q-card-section class="main-info" v-if="vault.state != 'inLiquidation'">
        <div class="row items-center no-wrap">
          <div :class="{'col-6': vault.state != 'inLiquidation', 'col-12': vault.state == 'inLiquidation'}">
            <div class="text-h6">{{ vault.loanValue.toLocaleString(locale, numberFormats.currency) }}</div>
            <div class="caption">Loan Value</div>
          </div>
          <div v-if="vault.state != 'inLiquidation'" class="col-6">
            <div class="text-h3 text-primary">{{ vault.collateralRatio }} %</div>
            <div class="caption">Coll. Ratio</div>
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

      <q-card-section class="coll-progress">
        <div class="row">
          <!-- <div class="col-2 text-left" style="font-size: 0.8em">{{ vault.loanScheme.minCollateral }} %</div> -->
          <div class="col-12 text-center text-subtitle2">
            <span v-if="vault.state != 'inLiquidation'" style="font-size: 1em" class="text-primary">{{ awayFromLiqudation(vault) }} % To Liquidation</span>
            <span v-if="vault.state == 'inLiquidation'">oh, oh 😭</span>
          </div>
          <!-- <div class="col-2 text-right" style="font-size: 0.8em">{{ vault.loanScheme.minCollateral * overCollateralizationFactor}} %</div> -->
        </div>

        <q-linear-progress v-if="vault.state != 'inLiquidation'" size="md" :value="awayFromLiqudationRelative(vault)" :color="trackColor(vault)" :track-color="trackColor(vault)" />
        <q-linear-progress v-if="vault.state == 'inLiquidation'" size="md" :value="0" :color="trackColor(vault)" :track-color="trackColor(vault)" />
        <div class="row">
          <div class="col-6 text-left">{{ vault.loanScheme.minCollateral }} %</div>
          <div class="col-6 text-right">{{ vault.loanScheme.minCollateral * overCollateralizationFactor}} %</div>
        </div>
      </q-card-section>

      <q-separator />

      <q-card-section class="coll-info row">
        <div class="col-6">
          <span class="text-h6 text-primary" v-if="vault.state != 'inLiquidation'">{{ vault.collateralValue.toLocaleString(locale, numberFormats.currency) }}</span>
          <span class="text-h6 text-primary" v-if="vault.state == 'inLiquidation'">Funds Frozen</span>
          <div class="caption">Collateral Amount</div>
        </div>
        <div class="col-6" v-if="vault.state != 'inLiquidation'">
          <span class="text-h6 text-primary" v-if="vault.state != 'inLiquidation'">{{ vault.loanScheme.minCollateral }} %</span>
          <span class="text-h6 text-primary" v-if="vault.state == 'inLiquidation'">Funds Frozen</span>
          <div class="caption">Min Coll. Ratio</div>
        </div>
        <p class="col-6 text-subtitle2">
        </p>
      </q-card-section>

      <q-separator />

      <q-card-section class="coll-info row">
        Add collateral worth $14444 to reach 400%
      </q-card-section>
      <!--
      <q-card-section
        class="q-py-xs"
        style="height: 15px"
        :class="{'bg-positive': vault.state == 'active', 'bg-warning': vault.state == 'mayLiquidate', 'bg-negative': vault.state == 'inLiquidation'}" >
      </q-card-section>
      -->
    </q-card>
  </div>
</template>

<script>
import { defineComponent } from 'vue';
import { mapGetters, mapActions } from 'vuex'

export default defineComponent({
  name: 'PageIndex',
  data () {
    return {
      loans: [],
      overCollateralizationFactor: 2,
      numberFormats: {
        currency: { style: 'currency', currency: 'USD', minimumFractionDigits: 2, maximumFractionDigits: 2 },
      },
      demoAccountID: process.env.DEMO_ACCOUNT_ID,
      darkMode: this.$q.dark.isActive,
    }
  },
  methods: {
    async prepareDemo() {
      await this.$store.dispatch('account/setUserId', this.demoAccountID)
      this.loadUserData
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
    awayFromLiqudationRelative(vault) {
      return this.awayFromLiqudation(vault) / (this.overCollateralizationFactor * vault.loanScheme.minCollateral)
    }
  },
  computed: {
    locale: function() {
      return this.$root.$i18n.locale
    },
    ...mapGetters({
      vaults: 'account/vaults',
      userId: 'account/userId',
    }),
    ...mapActions({
      loadUserData: 'account/loadUserData',
    })
  }

})
</script>

<style lang="sass">
  .q-card
    min-width: 290px

    .main-info
      min-height: 105px

    .coll-progress
      min-height: 70px

    .coll-info
      min-height: 100px

  body.screen--xs
    .q-card
      width: 100%

  body.screen--sm
    .q-card
      width: 31%
</style>
