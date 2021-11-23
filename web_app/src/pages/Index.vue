<template>
  <div class="q-pa-md text-h4">Your Vaults <q-icon name="fas fa-archive" /> </div>
  <div class="q-pa-md row items-start q-gutter-md">
    <q-card v-for="vault in vaults" :key="vault.vaultId" flat>
      <q-card-section>
        <div class="row items-center no-wrap">
          <div class="col">
            <div class="text-h6">{{ vault.collateralValue.toLocaleString($root.$i18n.locale, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ vault.name }}</div>
            <div class="text-subtitle2">{{ vault.currentRatio / 100 }}</div>
          </div>
        </div>
      </q-card-section>

      <q-card-section>
        <q-linear-progress size="xs" :value="vault.currentRatio / 100" color="positive" />
      </q-card-section>

      <q-card-section class="row">
        <div class="col-6">
          <span class="text-h6 text-primary">$ {{ vault.loanValue.toLocaleString($root.$i18n.locale, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }}</span>
          <br>
          <span class="text-caption">Per Share</span>
        </div>
        <p class="col-6 text-subtitle2">
        </p>
      </q-card-section>

      <q-separator />

      <q-card-section class="q-py-xs bg-positive" >

      </q-card-section>
    </q-card>
  </div>

  <q-separator />

  <div class="q-pa-md text-h4">Your Loans <q-icon name="fas fa-hand-holding-usd" /></div>

  <div class="q-pa-md row items-start q-gutter-md">

    <q-card v-for="loan in loans" :key="loan.name" flat>
      <q-card-section>
        <div class="row items-center no-wrap">
          <div class="col-auto">
            <q-avatar>
              <q-icon :name="loan.icon"></q-icon>
            </q-avatar>
          </div>
          <div class="col">
            <div class="text-h6">{{ loan.amount.toLocaleString($root.$i18n.locale, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ loan.name }}</div>
            <div class="text-subtitle2">{{ loan.symbol }}</div>
          </div>
        </div>
      </q-card-section>

      <q-card-section>
        <q-linear-progress size="xs" :value="loan.indicatorNumber" :color="loan.indicatorColor" />
      </q-card-section>

      <q-card-section class="row">
        <div class="col-6">
          <span class="text-h6 text-primary">$ {{ loan.price.toLocaleString($root.$i18n.locale, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }}</span>
          <br>
          <span class="text-caption">Per Share</span>
        </div>
        <p class="col-6 text-subtitle2">
           $ {{ (loan.price * loan.amount).toLocaleString($root.$i18n.locale, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }}
           <!--
          <br>
          <span class="text-caption"><q-icon name="fas fa-wallet"></q-icon> Worth</span>-->
        </p>
      </q-card-section>

      <q-separator />

      <q-card-section class="q-py-xs" :class="'bg-' + loan.indicatorColor">

      </q-card-section>
    </q-card>
    <!--
    <q-card flat>
      <q-card-section>
        <div class="row items-center no-wrap">
          <div class="col-auto">
            <q-avatar>
              <q-icon name="fab fa-twitter-square"></q-icon>
            </q-avatar>
          </div>
          <div class="col">
            <div class="text-h6">1337 Twitter</div>
            <div class="text-subtitle2">TWTR / USD</div>
          </div>
        </div>
      </q-card-section>

      <q-card-section>
        <q-linear-progress size="xs" value="0.1" color="warning" />
      </q-card-section>

      <q-card-section class="text-right">
        <p>
          <span class="text-h6 text-primary">$ 54.83</span>
          <br>
          <span class="text-caption">Per Share</span>
        </p>
        <p class="text-subtitle2">
           $ 7330.771

        </p>
      </q-card-section>

      <q-separator />

      <q-card-section class="bg-warning q-py-xs">

      </q-card-section>
    </q-card>
    -->
  </div>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'PageIndex',
  data () {
    return {
      loans: [
        {
          name: "USD",
          symbol: "dUSD",
          icon: "fas fa-dollar-sign",
          amount: 13337.69,
          price: 13337.69,
          collateralizationRatio: 250,
          loanSchemeId: "C_150",
          indicatorColor: "positive",
          indicatorNumber: 0.9,
        },
        {
          name: "Tesla",
          symbol: "TSLA / USD",
          icon: "fas fa-charging-station",
          amount: 420.69,
          price: 1045.00,
          collateralizationRatio: 250,
          loanSchemeId: "C_150",
          indicatorColor: "positive",
          indicatorNumber: 0.9,
        },
        {
          name: "Twitter",
          symbol: "TWTR / USD",
          icon: "fab fa-twitter-square",
          amount: 1337,
          price: 54.83,
          collateralizationRatio: 250,
          loanSchemeId: "C_150",
          indicatorColor: "positive",
          indicatorNumber: 0.8,
        },
        {
          name: "Google",
          symbol: "GOOG / USD",
          icon: "fab fa-google",
          amount: 42,
          price: 2922.52,
          collateralizationRatio: 250,
          loanSchemeId: "C_150",
          indicatorColor: "warning",
          indicatorNumber: 0.4,
        },
        {
          name: "GME",
          symbol: "GME / USD",
          icon: "fas fa-gamepad",
          amount: 9001,
          price: 182.85,
          collateralizationRatio: 250,
          loanSchemeId: "C_150",
          indicatorColor: "negative",
          indicatorNumber: 0.01,
        }

      ],
      vaults: [
        {
          "name": "Vault 1",
          "vaultId": "e507b8adb489ae772040654632aea7b30c307bc43b6a5d6e1400b6b35c200b28",
          "loanSchemeId": "C_150",
          "ownerAddress": "tc5Yw92oAJwqq1FzGHK4sZyX45TgmEiUhK",
          "isUnderLiquidation": false,
          "collateralAmounts": [
            "9710.00000000@DFI"
          ],
          "loanAmount": [
            "420.69@dTSLA",
            "1337@dTWTR",
          ],
          "collateralValue": 24490.61928900,
          "loanValue": 16381.35795762,
          "currentRatio": 150
        },
        {
          "name": "Vault 2",
          "vaultId": "e507b8adb489ae772040654632aea7b30c307bc43b6a5d6e1400b6b35c200b28",
          "loanSchemeId": "C_150",
          "ownerAddress": "tc5Yw92oAJwqq1FzGHK4sZyX45TgmEiUhK",
          "isUnderLiquidation": false,
          "collateralAmounts": [
            "9710.00000000@DFI"
          ],
          "loanAmount": [
            "420.69@dTSLA"
          ],
          "collateralValue": 24490.61928900,
          "loanValue": 16381.35795762,
          "currentRatio": 150
        },
        {
          "name": "Vault 3",
          "vaultId": "e507b8adb489ae772040654632aea7b30c307bc43b6a5d6e1400b6b35c200b28",
          "loanSchemeId": "C_150",
          "ownerAddress": "tc5Yw92oAJwqq1FzGHK4sZyX45TgmEiUhK",
          "isUnderLiquidation": false,
          "collateralAmounts": [
            "9710.00000000@DFI"
          ],
          "loanAmount": [
            "420.69@dTSLA"
          ],
          "collateralValue": 24490.61928900,
          "loanValue": 16381.35795762,
          "currentRatio": 150
        },
      ],
      vaultStates: [
        "active",
        "frozen",
        "inliquidation",
        "frozeninliquidation",
        "mayliquidate",
      ]
    }
  },
})
</script>

<style lang="sass">
  .q-card
    min-width: 250px

  body.screen--xs
    .q-card
      width: 100%

  body.screen--sm
    .q-card
      width: 32%
</style>
