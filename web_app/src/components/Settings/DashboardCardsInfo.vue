<template>
  <q-card flat>

    <q-card-section>
    <div class="text-primary text-h6">{{ $t('Information on Dashboard Cards') }}</div>
    </q-card-section>

    <q-card-section class="q-pt-none">
      <div>
        <q-toggle
          v-model="healthSummary"
          color="primary"
          icon="fal fa-heartbeat"
          label="Health Summary"
          size="xl"
        />
      </div>
      <div>
        <q-toggle
          v-model="collateralInfo"
          color="primary"
          icon="fal fa-cauldron"
          label="Collateral Info"
          size="xl"
        />
      </div>
      <div>
        <q-toggle
          v-model="collateralWaypoints"
          color="primary"
          icon="fal fa-telescope"
          label="Collateral Waypoints"
          size="xl"
        />
      </div>
    </q-card-section>

  </q-card>
</template>

<script>
import { defineComponent } from 'vue';
import { mapGetters, mapActions } from 'vuex'

export default defineComponent({
  name: 'DashboardCardsInfo',
  data() {
    return {
        healthSummary: true,
        collateralInfo: true,
        collateralWaypoints: true,
    }
  },
  created() {
    this.healthSummary = this.settingValue('dashboardCardsInfo.healthSummary')
    this.collateralInfo = this.settingValue('dashboardCardsInfo.collateralInfo')
    this.collateralWaypoints = this.settingValue('dashboardCardsInfo.collateralWaypoints')
  },
  watch: {
    healthSummary(setting) {
      this.setSetting({ key: 'dashboardCardsInfo.healthSummary', value: setting })
    },
    collateralInfo(setting) {
      this.setSetting({ key: 'dashboardCardsInfo.collateralInfo', value: setting })
    },
    collateralWaypoints(setting) {
      this.setSetting({ key: 'dashboardCardsInfo.collateralWaypoints', value: setting })
    },
  },
  methods: {
    ...mapActions({
      setSetting: 'settings/set',
    }),
  },
  computed: {
    ...mapGetters({
      settingValue: 'settings/value',
    }),
  },
})
</script>

<style lang="sass">

</style>
