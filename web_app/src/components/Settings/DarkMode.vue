<template>
  <q-card flat>

    <q-card-section>
    <div class="text-primary text-h6">{{ $t('Color Theme') }}</div>
    </q-card-section>

    <q-card-section class="q-pt-none text-center row">
      <div class="col-4 text-right q-mt-md" :class="{'text-grey-6': mode != false}">
        Light
      </div>
      <q-toggle
        class="col-3 text-center"
        toggle-indeterminate
        indeterminate-value="auto"
        v-model="this.$q.dark.mode"
        size="xl"
        icon="fal fa-hat-wizard"
        checked-icon="fal fa-moon-stars"
        unchecked-icon="fal fa-sun"
        color="primary"
        :keep-color="true"
      />
      <div class="col-5 text-left q-mt-md" :class="{'text-grey-6': mode != true}">
        Dark
      </div>
      <div class="text-center full-width" :class="{'text-grey-6': mode != 'auto'}">{{ $t('Determined by device\'s system settings') }}</div>
    </q-card-section>

  </q-card>
</template>

<script>
import { defineComponent } from 'vue';
import { mapGetters } from 'vuex'

export default defineComponent({
  name: 'DarkModeSetting',
  watch: {
    mode(mode) {
      this.$store.dispatch('settings/set', { key: 'darkMode', value: mode })
      this.$q.dark.set(mode)
    }
  },
  computed: {
    ...mapGetters({
      settingValue: 'settings/value',
    }),
    mode() {
      return this.$q.dark.mode
    }
  },
})
</script>

<style lang="sass">

</style>
