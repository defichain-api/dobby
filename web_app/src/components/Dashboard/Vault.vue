<template>
  <q-card
    ref="vault"
    flat

    class="q-mb-none vault"
  >

    <q-inner-loading
      :showing="requestRunning"
      color="primary"
    />

    <q-card-section
      class="q-py-xs"
      style="height: 15px"
      :class="{'bg-positive': vault.state == 'active', 'bg-warning': vault.state == 'mayLiquidate', 'bg-negative': vault.state == 'inLiquidation'}"
    />

    <q-card-section class="q-my-sm q-py-none">
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

    <q-separator inset />

    <q-card-section class="main-info" v-if="vault.state != 'inLiquidation'">
      <div class="row">
        <div :class="{'col-4': vault.state != 'inLiquidation', 'col-12': vault.state == 'inLiquidation'}">
          <div class="text-h6"><span v-if="!privacy || vault.loanValue == 0 ">{{ vault.loanValue.toLocaleString(locale, numberFormats.currency) }}</span><span v-if="privacy && vault.loanValue > 0">$ <span class="text-body1">🧦🧦🧦</span></span></div>
          <div class="caption">Loan Value</div>
        </div>
        <div v-if="vault.loanValue > 0" class="col-8 text-right">
          <div class="text-h4 text-primary">{{ vault.collateralRatio.toLocaleString(locale) }} %</div>
          <div class="caption">Next: {{ vault.nextCollateralRatio.toLocaleString(locale) }} %</div>
        </div>
      </div>
      <div class="row q-mt-md">
        <div class="col-2">
          <q-linear-progress v-if="vault.state != 'inLiquidation'" size="md" :value="1" color="negative" track-color="negative" />
        </div>
        <div class="col-3">
          <q-linear-progress v-if="vault.state != 'inLiquidation'" size="md" :value="1" color="warning" track-color="warning" />
        </div>
        <div class="col-7">
          <q-linear-progress v-if="vault.state != 'inLiquidation'" size="md" :value="awayFromInfoState" color="positive" track-color="positive" />
        </div>
        <q-linear-progress v-if="vault.state == 'inLiquidation'" size="md" :value="0" :color="trackColor(vault)" :track-color="trackColor(vault)" />
        <div class="row full-width">
          <div class="col-2 text-left">{{ vault.loanScheme.minCollateral }} %</div>
          <div class="col-8 text-center text-primary">{{ vault.collateralRatio.toLocaleString(locale) }} %</div>
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

    <q-separator inset />

    <q-card-section class="coll-progress q-py-sm" v-if="settingValue('dashboardCardsInfo.healthSummary')">
      <div v-if="vault.loanValue > 0">
        <div class="row">
          <div class="col-12 text-caption q-mb-md">
            <span class="text-overline">This vault...</span><br />
            <ul class="q-mt-none q-mb-md">
              <li>is <span class="text-primary">{{ awayFromLiqudation(vault) }} %</span> over liquidation collateral</li>
              <li>
                will change it's collateral ratio by <span class="text-primary">{{ (vault.nextCollateralRatio - vault.collateralRatio).toLocaleString(locale) }} %</span> within less than 120 blocks
              </li>
            </ul>
            <span class="text-overline">will liquidate when...</span>
            <ul class="q-my-none">
              <li>losing <span v-if="!privacy" class="text-primary">{{ awayFromLiqudationTotal(vault).toLocaleString(locale, numberFormats.currency) }}</span><span v-if="privacy">🧦🧦🧦</span> of collateral</li>
              <li>collateral's worth falls below <span v-if="!privacy" class="text-primary">{{ minCollateralAmount(vault).toLocaleString(locale, numberFormats.currency) }}</span><span v-if="privacy">🧦🧦🧦</span></li>
              <li>collateral's prices drop by <span class="text-primary">{{ maximumPriceDrop(vault).toLocaleString(locale, {minimumFractionDigits: 1, maximumFractionDigits: 1}) }} %</span></li>
            </ul>
          </div>
        </div>
      </div>
    </q-card-section>

    <q-separator inset v-if="settingValue('dashboardCardsInfo.healthSummary')" />

    <q-card-section class="coll-info row text-center" v-if="settingValue('dashboardCardsInfo.collateralInfo')">
      <div class="col-12 text-overline text-left">Collateral</div>
      <div class="col-4">
        <span class="text-h6 text-accent" v-if="vault.state != 'inLiquidation'"><span v-if="!privacy">{{ vault.collateralValue.toLocaleString(locale, numberFormats.currency) }}</span><span v-if="privacy">$🧦🧦🧦</span></span>
        <span class="text-h6 text-accent" v-if="vault.state == 'inLiquidation'">Funds Frozen</span>
        <div class="caption">Amount</div>
      </div>
      <div class="col-4" v-if="vault.state != 'inLiquidation'">
        <span class="text-h6 text-accent" v-if="vault.state != 'inLiquidation'">{{ vault.loanScheme.minCollateral }} %</span>
        <span class="text-h6 text-accent" v-if="vault.state == 'inLiquidation'">Funds Frozen</span>
        <div class="caption">Min Ratio</div>
      </div>
      <div v-if="vault.state != 'inLiquidation' && vault.loanValue > 0" class="col-4">
          <div class="text-h6 text-primary">{{ vault.collateralRatio }} %</div>
          <div class="caption">Current Ratio</div>
      </div>
    </q-card-section>

    <q-separator inset v-if="settingValue('dashboardCardsInfo.collateralInfo')" />

    <q-card-section class="coll-info row text-center" v-if="settingValue('dashboardCardsInfo.collateralWaypoints')">
      <div class="col-12 text-overline text-left">Add/Remove Collateral</div>
      <div class="col-3">
        <span class="text-accent">200 %</span>
        <br />
        <span class="text-primary text-smaller">{{ (vault.loanValue * 2 - vault.collateralValue).toLocaleString(locale, numberFormats.currencyNoDecimals) }}</span>
        <br />
        <span class="text-caption text-smaller">({{ (vault.loanValue * 2).toLocaleString(locale, numberFormats.currencyNoDecimals) }})</span>
      </div>
      <div class="col-3">
        <span class="text-accent">250 %</span>
        <br />
        <span class="text-primary text-smaller">{{ (vault.loanValue * 2.5 - vault.collateralValue).toLocaleString(locale, numberFormats.currencyNoDecimals) }}</span>
        <br />
        <span class="text-caption text-smaller">({{ (vault.loanValue * 2.5).toLocaleString(locale, numberFormats.currencyNoDecimals) }})</span>
      </div>
      <div class="col-3">
        <span class="text-accent">300 %</span>
        <br />
        <span class="text-primary text-smaller">{{ (vault.loanValue * 3 - vault.collateralValue).toLocaleString(locale, numberFormats.currencyNoDecimals) }}</span>
        <br />
        <span class="text-caption text-smaller">({{ (vault.loanValue * 3).toLocaleString(locale, numberFormats.currencyNoDecimals) }})</span>
      </div>
      <div class="col-3">
        <span class="text-accent">350 %</span>
        <br />
        <span class="text-primary text-smaller">{{ (vault.loanValue * 3.5 - vault.collateralValue).toLocaleString(locale, numberFormats.currencyNoDecimals) }}</span>
        <br />
        <span class="text-caption text-smaller">({{ (vault.loanValue * 3.5).toLocaleString(locale, numberFormats.currencyNoDecimals) }})</span>
      </div>
    </q-card-section>
  </q-card>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'Vault',
  props: {
    vault: {
      type: Object,
      required: true,
    },
    height: {
      required: false,
    },
  },
  data() {
    return {
      overCollateralizationFactor: 3,
    }
  },
  mounted() {
    this.$emit('update:height', this.$refs.vault.$el.clientHeight)
    //console.log(this.triggers)
  },
  methods: {
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
      return vault.collateralValue - this.minCollateralAmount(vault)
    },
    awayFromLiqudationRelative(vault) {
      return (this.awayFromLiqudation(vault) + vault.loanScheme.minCollateral) / (this.overCollateralizationFactor * vault.loanScheme.minCollateral)
    },
    minCollateralAmount(vault) {
      return vault.loanValue * (vault.loanScheme.minCollateral / 100)
    },
    maximumPriceDrop(vault) {
      return ((100 * this.awayFromLiqudationTotal(vault)) / vault.collateralValue)
    },
  },
  computed: {
    locale: function() {
      return this.$root.$i18n.locale
    },
    privacy() {
      return this.settingValue('privacy')
    },
    triggers() {
      let vaultTriggers = this.vaultTriggers(this.vault.vaultId)
      let triggerTypes = {}

      if (Object.keys(vaultTriggers).length == 0) { return triggerTypes }

      vaultTriggers.forEach((trigger) => {
        triggerTypes[trigger.type] = trigger
      })
      return triggerTypes
    },
    awayFromInfoState() {
      const infoStateRatio = this.triggers['info']?.ratio || 0
      const vaultCollateralRatio = this.vault.collateralRatio
      if ((vaultCollateralRatio - infoStateRatio) < 0) {
        return 0.5
      }
      return vaultCollateralRatio - infoStateRatio
    },
    awayFromWarningState() {
      return 0
    },
    ...mapGetters({
      settingValue: 'settings/value',
      requestRunning: 'requestRunning',
      numberFormats: 'settings/numberFormats',
      vaultTriggers: 'notifications/vaultTriggers',
    }),
  }
}
</script>

<style lang="sass" scoped>
  .vault
    min-width: 290px
    max-width: 23%

  ul
    padding-left: 10px
</style>
