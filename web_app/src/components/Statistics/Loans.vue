<template>
  <q-card flat :bordered="$q.dark.isActive">
    <q-card-section class="q-pb-none">
      <div class="text-h6">Loans Monitored</div>
    </q-card-section>
    <q-card-section>
      <div class="text-h4 text-primary">{{ this.latest.sum_loan.toLocaleString(locale, numberFormats.currency) }}</div>
      <div class="text-caption">Sum</div>
    </q-card-section>
    <q-card-section class="container q-pt-none">
      <div class="row">
        <div class="col-12 text-overline">
          Collateral Ratios
        </div>
      </div>
      <div class="row">
        <div class="col-6">
          <div class="text-h4 text-primary">{{ (this.latest.sum_collateral / this.latest.sum_loan * 100).toLocaleString(locale, {maximumFractionDigits: 0}) }} %</div>
          <div class="text-caption">Coll To Loans</div>
        </div>
        <div class="col-6">
          <div class="text-h4 text-primary">{{ this.latest.avg_ratio.toLocaleString(locale, { maximumFractionDigits: 0 }) }} %</div>
          <div class="text-caption">AVG Per User</div>
        </div>
      </div>
    </q-card-section>
  </q-card>
</template>

<script>
export default {
  name: 'Loans',
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
  computed: {
    latest: function() {
      return this.statistics[0]
    },
    locale: function() {
      return this.$root.$i18n.locale
    },
  }
}
</script>

<style lang="sass" scoped>
  .border-left
    border-left: 1px solid rgba(0,0,0,0.3)
</style>
