<template>
  <div class="q-pa-md row items-start q-gutter-md" v-if="statistics.length > 0">
    <div class="text-h5 col-12">Until yesterday</div>
    <UserCount :statistics="statistics" />
    <VaultCount :statistics="statistics" />
    <Collateral :statistics="statistics" />
    <Loans :statistics="statistics" />
    <MessagesDelivered :statistics="statistics" />
  </div>
</template>

<script>
import UserCount from 'components/Statistics/UserCount.vue'
import VaultCount from 'components/Statistics/VaultCount.vue'
import MessagesDelivered from 'components/Statistics/MessagesDelivered.vue'
import Collateral from 'components/Statistics/Collateral.vue'
import Loans from 'components/Statistics/Loans.vue'

export default {
  components: {
    UserCount,
    VaultCount,
    MessagesDelivered,
    Collateral,
    Loans,
  },
  data() {
    return {
      statistics: {},
    }
  },
  created() {
    this.$store.dispatch('setHeadline', {text: 'Dobby likes numbers', icon: 'fal fa-chart-bar'})
    this.getStatistics()
  },
  methods: {
    getStatistics: function() {
      //this.$api.get('statistics')
      this.$axios.get('https://api.defichain-dobby.com/statistics')
        .then((result) => {
          this.statistics = result.data.data
        })
    }
  },
}
</script>

<style lang="sass" scoped>
.q-card
    width: 100%
/*
  .q-card
    min-width: 340px
    max-width: 23%
    min-height: 250px

  body.screen--xs, body.screen--sm
    .q-card
      width: 100%
      max-width: inherit

  body.screen--md
    .q-card
      width: 47vw
      max-width: inherit
</style>
