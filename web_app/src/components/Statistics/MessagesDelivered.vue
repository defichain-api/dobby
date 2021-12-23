<template>
  <q-card flat :bordered="$q.dark.isActive">
    <q-card-section class="q-pb-none">
      <div class="text-h6">Messages Delivered</div>
    </q-card-section>
    <q-card-section class="container text-center">
      <div class="row">
        <div class="col-6 text-left">
          <div class="text-h4 text-primary">{{ this.latest.messages.sum_messages.toLocaleString(locale) }}</div>
          <div class="text-caption">Sum</div>
        </div>
        <div class="col-2">
          <div class="text-body1 text-primary">{{ this.latest.messages.gateways.telegram.toLocaleString(locale) }}</div>
          <q-avatar color="telegram" font-size="0.6em" text-color="white" size="md" icon="fab fa-telegram-plane" />
        </div>
        <div class="col-2 ">
          <div class="text-body1 text-primary">{{ this.latest.messages.gateways.mail.toLocaleString(locale) }}</div>
          <q-avatar color="green" font-size="0.6em" text-color="white" size="md" icon="fal fa-mailbox" />
        </div>
        <div class="col-2 ">
          <div class="text-body1 text-primary">{{ this.latest.messages.gateways.webhook.toLocaleString(locale) }}</div>
          <q-avatar color="red" font-size="0.5em" text-color="white" size="md" icon="fal fa-send-back" />
        </div>
      </div>
    </q-card-section>
    <q-card-section class="container q-pt-none">
      <div class="row">
        <div class="col-12 text-overline">
          Types
        </div>
      </div>
      <div class="row">
        <div class="col-4">
          <div class="text-h6 text-primary">{{ this.latest.messages.types.info.toLocaleString(locale) }}</div>
          <div class="text-caption">Info</div>
        </div>
        <div class="col-4 ">
          <div class="text-h6 text-primary">{{ this.latest.messages.types.warning.toLocaleString(locale) }}</div>
          <div class="text-caption">Warning</div>
        </div>
        <div class="col-4 ">
          <div class="text-h6 text-primary">{{ this.latest.messages.types.daily.toLocaleString(locale) }}</div>
          <div class="text-caption">Daily</div>
        </div>
      </div>
    </q-card-section>
    <q-separator />
    <q-card-section>
      <area-chart :data="history" :colors="[getColor('accent')]" :download="true" style="height: 200px;"/>
    </q-card-section>
    <q-card-section>
      <line-chart :data="historyTypes" :colors="[getColor('accent'), getColor('primary'), getColor('secondary')]" :download="true" style="height: 200px;"/>
    </q-card-section>
  </q-card>
</template>

<script>
import { colors } from 'quasar'
const { getPaletteColor } = colors

export default {
  name: 'MessagesDelivered',
  props: {
    statistics: {
      required: true,
    },
  },
  data() {
    return {

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
        collection[day.date] = day.messages.sum_messages
      })
      return [{name:"Sum", data: collection}]
    },
    historyTypes: function() {
      let collection = []

      // Info
      let tmp = {name: 'Info', data: {}}
      this.statistics.forEach(function(day) {
        tmp['data'][day.date] = day.messages.types.info
      })
      collection.push(tmp)

      // Warning
      tmp = {name: 'Warning', data: {}}
      this.statistics.forEach(function(day) {
        tmp['data'][day.date] = day.messages.types.warning
      })
      collection.push(tmp)

      // Daily
      tmp = {name: 'Daily', data: {}}
      this.statistics.forEach(function(day) {
        tmp['data'][day.date] = day.messages.types.daily
      })
      collection.push(tmp)

      return collection
    },
  }
}
</script>

<style lang="sass" scoped>
</style>
