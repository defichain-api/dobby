<template>
  <q-card flat :bordered="$q.dark.isActive">
    <q-card-section class="q-pb-none">
      <div class="text-h6">User Accounts</div>
    </q-card-section>
    <q-card-section>
      <div class="text-h3 text-primary">{{ this.latest.user_count }}</div>
    </q-card-section>
    <q-separator />
    <q-card-section>
      <area-chart :data="history" :colors="[getColor('accent')]" :download="true" style="height: 200px;"/>
    </q-card-section>
  </q-card>
</template>

<script>
import { colors } from 'quasar'
const { getPaletteColor } = colors

export default {
  name: 'UserCount',
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
    history: function() {
      let collection = {}
      this.statistics.forEach(function(day) {
        collection[day.date] = day.user_count
      })
      return collection
    },
  }
}
</script>

<style>

</style>
