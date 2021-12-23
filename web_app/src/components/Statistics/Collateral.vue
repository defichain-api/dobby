<template>
  <q-card flat :bordered="$q.dark.isActive">
    <q-card-section class="q-pb-none">
      <div class="text-h6">Collateral Monitored</div>
    </q-card-section>
    <q-card-section>
      <div class="text-h4 text-primary">{{ this.latest.sum_collateral.toLocaleString(locale, numberFormats.currency) }}</div>
      <div class="text-caption">Sum</div>
    </q-card-section>
    <q-card-section class="container q-pt-none">
      <div class="row">
        <div class="col-12 text-overline">
          Averages
        </div>
      </div>
      <div class="row">
        <div class="col-6">
          <div class="text-h6 text-primary">{{ (this.latest.sum_collateral / this.latest.user_count ).toLocaleString(locale, numberFormats.currency) }}</div>
          <div class="text-caption">Per User</div>
        </div>
        <div class="col-6">
          <span class="text-h6 text-primary">{{ (this.latest.sum_collateral / this.latest.vault_count ).toLocaleString(locale, numberFormats.currency) }}</span>
          <div class="text-caption">Per Vault</div>
        </div>
      </div>
    </q-card-section>
    <q-separator />
    <q-card-section>
      <area-chart :data="history" :colors="[getColor('accent')]" :download="true" style="height: 200px;"/>
    </q-card-section>
    <q-card-section>
      <line-chart :data="historyAvg" :colors="[getColor('accent'), getColor('primary')]" :download="true" style="height: 200px;"/>
    </q-card-section>
  </q-card>
</template>

<script>
import { colors } from 'quasar'
const { getPaletteColor } = colors

export default {
  name: 'Collateral',
  props: {
    statistics: {
      required: true,
    },
  },
  data() {
    return {
      numberFormats: {
        currency: { style: 'currency', currency: 'USD', maximumFractionDigits: 0 },
      },
    }
  },
  methods: {
    getColor(name) {
      return getPaletteColor(name)
    },
  },
  computed: {
    latest: function() {
      return this.statistics[0]
    },
    locale: function() {
      return this.$root.$i18n.locale
    },
    history: function() {
      let collection = {}
      this.statistics.forEach(function(day) {
        collection[day.date] = day.sum_collateral
      })
      return [{name:"Sum", data: collection}]
    },
    historyAvg: function() {
      let collection = []

      // Sum
      let tmp = {name: 'AVG User', data: {}}
      this.statistics.forEach(function(day) {
        tmp['data'][day.date] = (day.sum_collateral / day.user_count )
      })
      collection.push(tmp)

      // Per User
      tmp = {name: 'AVG Vault', data: {}}
      this.statistics.forEach(function(day) {
        tmp['data'][day.date] = (day.sum_collateral / day.vault_count )
      })
      collection.push(tmp)

      return collection
    },
  }
}
</script>

<style lang="sass" scoped>
  .border-left
    border-left: 1px solid rgba(0,0,0,0.3)
</style>
