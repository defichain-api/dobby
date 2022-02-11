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
      :class="{'bg-negative': collateralState == 'warning', 'bg-warning': collateralState == 'info', 'bg-positive': collateralState == 'healthy', 'bg-blue': vault.state == 'frozen'}"
    />

    <q-card-section class="q-my-sm q-py-none">
      <div class="row no-wrap text-left">
        <div class="col-12">
          <div class="ellipsis" :class="{'text-h5': vault.name.length > 0, 'text-caption': vault.name.length == 0}">
            <q-icon v-if="!isFrozen" name="fal fa-box-usd" size="sm" class="q-mr-sm" />
            <q-icon v-if="isFrozen" name="fal fa-snowman" size="sm" class="q-mr-sm" color="blue" />
            <span v-if="vault.name.length > 0">{{ vault.name }}</span>
            <span v-else>
              <span v-if="!privacy">{{ vault.vaultId }}</span>
              <span v-else>🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦🧦</span>
            </span>
          </div>
        </div>
      </div>
    </q-card-section>

    <q-card-section v-if="!hasTriggers && vault.state != 'in_liquidation'" class="text-white bg-warning">
      <q-icon name="fal fa-exclamation-triangle"></q-icon>
      Please head over to <q-btn dense flat type="href" to="manage-notifications">Manage Notifications</q-btn> to set up notifications for this vault.
    </q-card-section>

    <q-separator inset />

    <q-card-section class="main-info" v-if="vault.state != 'in_liquidation'">
      <div class="row">
        <div :class="{'col-4': vault.state != 'in_liquidation', 'col-12': vault.state == 'in_liquidation'}">
          <div class="text-h6"><span v-if="!privacy || vault.loanValue == 0 ">{{ vault.loanValue.toLocaleString(locale, numberFormats.currency) }}</span><span v-if="privacy && vault.loanValue > 0">$ <span class="text-body1">🧦🧦🧦</span></span></div>
          <div class="caption">Loan Value</div>
        </div>
        <div v-if="vault.loanValue > 0" class="col-8 text-right">
          <div class="text-h4" :class="{'text-primary': !isFrozen, 'text-blue': isFrozen}">
            <span v-if="vault.loanValue > 0">{{ vault.collateralRatio.toLocaleString(locale) }} %</span>
            <span v-if="vault.loanValue <= 0">N/A</span>
          </div>
          <div class="caption" v-if="vault.nextCollateralRatio">Next: <span class="text-primary">{{ vault.nextCollateralRatio.toLocaleString(locale) }} %</span></div>
        </div>
      </div>
      <!--
      <div class="col-12 text-center q-mt-md" :class="{'text-negative': collateralState == 'warning', 'text-warning': collateralState == 'info', 'text-positive': collateralState == 'healthy'}">
        {{ vault.collateralRatio.toLocaleString(locale) }} %
      </div>
      -->
      <div class="row q-mt-sm" v-if="hasTriggers">
        <div class="col-2">
          <q-linear-progress v-if="vault.state != 'in_liquidation'" size="lg" :value="awayFromLiquidationState" color="negative" track-color="negative">
          </q-linear-progress>
        </div>
        <div class="col-3">
          <q-linear-progress v-if="vault.state != 'in_liquidation'" size="lg" :value="awayFromWarningState" color="warning" track-color="warning" label="test">
          </q-linear-progress>
        </div>
        <div class="col-7">
          <q-linear-progress v-if="vault.state != 'in_liquidation'" size="lg" :value="awayFromInfoState" color="positive" track-color="positive">
          </q-linear-progress>
        </div>
        <div class="row full-width">
          <div class="col-2 text-left text-negative">{{ vault.loanScheme.minCollateral }} %</div>
          <div class="col-3 text-left text-warning" style="margin-left: -12px">{{ this.triggers['warning']?.ratio.toLocaleString(locale) }} %</div>
          <div class="col-5 text-left text-positive">{{ this.triggers['info']?.ratio.toLocaleString(locale) }} %</div>
          <div class="col-2 text-right text-positive" style="margin-left: 12px">{{ vault.loanScheme.minCollateral * overCollateralizationFactor}} %</div>
        </div>
      </div>
    </q-card-section>

    <q-card-section class="main-info" v-if="vault.state == 'in_liquidation'">
      <div class="text-center">
          <div class="text-h6">In Liquidation</div>
          <div class="text-subtitle2">At Block Height {{ vault.liquidationHeight }}</div>
          <q-icon class="q-my-lg" name="fal fa-skull-crossbones" size="xl" />
          <p>You're in trouble, friend.</p>
          <p>
            You can try to bid on the corresponding auction, using your DeFiChain wallet app.
          </p>
          <p>
            <q-btn
              outline
              rounded
              color="primary"
              type="a"
              target="blank"
              :href="'https://defiscan.live/vaults/' + vault.vaultId + '/auctions/0'"
              label="see auction details on DeFiScan"
            />
          </p>
      </div>
    </q-card-section>

    <q-separator inset v-if="isFrozen" />

    <q-card-section class="coll-progress q-py-sm text-center" v-if="isFrozen">
      <q-icon name="fal fa-snowman" size="xl" color="blue" />
      <div class="text-h6 text-blue">Vault is frozen</div>
    </q-card-section>

    <q-separator inset />

    <q-card-section class="coll-progress q-py-sm" v-if="settingValue('dashboardCardsInfo.healthSummary') && vault.state != 'in_liquidation'">
      <div v-if="vault.loanValue > 0">
        <div class="row">
          <div class="col-12 text-caption q-mb-md">
            <span class="text-overline">This vault...</span><br />
            <ul class="q-mt-none q-mb-md">
              <li>is <span class="text-primary">{{ awayFromLiqudation(vault) }} %</span> over liquidation collateral</li>
              <li>
                will change it's collateral ratio by
                <span class="text-primary">
                  <span v-if="vault.nextCollateralRatio - vault.informativeRatio > 0">+</span>{{ (vault.nextCollateralRatio - vault.informativeRatio).toLocaleString(locale, numberFormats.twoDecimals) }} %
                </span>
                within the next
                <span v-if="nextTick.minutes_left > 0">{{ nextTick.minutes_left }} minutes</span><span v-else>block</span>
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

    <q-separator inset v-if="settingValue('dashboardCardsInfo.collateralInfo') && vault.state != 'in_liquidation'" />

    <q-card-section class="coll-info row text-center" v-if="settingValue('dashboardCardsInfo.collateralInfo') && vault.state != 'in_liquidation'">
      <div class="col-12 text-overline text-left">Collateral</div>
      <div class="col-4">
        <span class="text-h6 text-accent" v-if="vault.state != 'in_liquidation'"><span v-if="!privacy">{{ vault.collateralValue.toLocaleString(locale, numberFormats.currency) }}</span><span v-if="privacy">$🧦🧦🧦</span></span>
        <span class="text-h6 text-accent" v-if="vault.state == 'in_liquidation'">Funds Frozen</span>
        <div class="caption">Amount</div>
      </div>
      <div class="col-4" v-if="vault.state != 'in_liquidation'">
        <span class="text-h6 text-accent" v-if="vault.state != 'in_liquidation'">{{ vault.loanScheme.minCollateral }} %</span>
        <span class="text-h6 text-accent" v-if="vault.state == 'in_liquidation'">Funds Frozen</span>
        <div class="caption">Min Ratio</div>
      </div>
      <div class="col-4" v-if="vault.state != 'in_liquidation' && vault.loanValue > 0">
          <div class="text-h6 text-accent">{{ vault.nextCollateralRatio }} %</div>
          <div class="caption">Next Ratio</div>
      </div>
    </q-card-section>

    <q-separator inset v-if="settingValue('dashboardCardsInfo.collateralWaypoints') && vault.state != 'in_liquidation'" />

    <q-card-section class="coll-info row text-center" v-if="settingValue('dashboardCardsInfo.collateralWaypoints') && vault.state != 'in_liquidation'">
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
  },
  methods: {
    trackColor(vault) {
      let color = ''
      if (vault.state == 'active') {
        color = 'positive'
      } else if (vault.state == 'mayLiquidate') {
        color = 'warning'
      } else if (vault.state == 'in_liquidation') {
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
    maximumDisplayedOvercollateralisationRatio() {
      return this.overCollateralizationFactor * this.vault.loanScheme.minCollateral
    },
    awayFromInfoState() {
      const infoStateRatio = this.triggers['info']?.ratio || 0
      const vaultCollateralRatio = this.vault.collateralRatio
      const aboveInfoStateSpectrum = this.maximumDisplayedOvercollateralisationRatio - infoStateRatio
      const aboveInfoStateRatio = vaultCollateralRatio - infoStateRatio

      if ((aboveInfoStateRatio) < 0) {
        return 0
      }
      return aboveInfoStateRatio / aboveInfoStateSpectrum
    },
    awayFromWarningState() {
      const infoStateRatio = this.triggers['info']?.ratio || 0
      const warningStateRatio = this.triggers['warning']?.ratio || 0
      const vaultCollateralRatio = this.vault.collateralRatio
      const aboveWarningStateSpectrum = infoStateRatio - warningStateRatio
      const aboveWarningStateRatio = vaultCollateralRatio - warningStateRatio

      if ((aboveWarningStateRatio) < 0) {
        return 0
      }

      return aboveWarningStateRatio / aboveWarningStateSpectrum
    },
    awayFromLiquidationState() {
      const warningStateRatio = this.triggers['warning']?.ratio || 0
      const liquidationStateRatio = this.vault.loanScheme.minCollateral
      const vaultCollateralRatio = this.vault.collateralRatio
      const aboveLiquidationStateSpectrum = warningStateRatio - liquidationStateRatio
      const aboveLiquidationStateRatio = vaultCollateralRatio - liquidationStateRatio

      if (aboveLiquidationStateRatio < 0) {
        return 0
      }
      return aboveLiquidationStateRatio / aboveLiquidationStateSpectrum
    },
    collateralState() {
      if (this.awayFromLiquidationState < 1) return 'warning'
      if (this.awayFromWarningState < 1) return 'info'
      return 'healthy'
    },
    isFrozen() {
      return this.vault.state == 'frozen'
    },
    hasTriggers() {
      return Object.keys(this.triggers).length > 0
    },
    ...mapGetters({
      settingValue: 'settings/value',
      requestRunning: 'requestRunning',
      numberFormats: 'settings/numberFormats',
      vaultTriggers: 'notifications/vaultTriggers',
      nextTick: 'chain/nextTick',
    }),
  }
}
</script>
<!--

  .vault
    min-width: 290px
    max-width: 32vw

  .screen--xs .vault
    width: 100%
    max-width: inherit
-->
<style lang="sass" scoped>
  ul
    padding-left: 10px
</style>
