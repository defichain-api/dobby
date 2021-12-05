<template>
  <!-- <q-card flat :bordered="!this.$q.dark.isActive" style="min-width: 320px;"> -->
  <q-card flat :bordered="$q.dark.isActive" style="min-width: 320px;">

    <q-card-section>
    <div class="text-primary text-h6">{{ $t('Color Theme') }}</div>
    </q-card-section>

    <q-card-section class="q-pt-none">
      Light
      <q-toggle
        toggle-indeterminate
        indeterminate-value="auto"
        v-model="this.$q.dark.mode"
        size="lg"
        icon="auto_awesome"
        checked-icon="dark_mode"
        unchecked-icon="light_mode"
      />
      Dark
      <div v-if="mode =='auto'">{{ $t('Determined by your system settings') }}</div>
    </q-card-section>

  </q-card>
</template>

<script>
import { defineComponent } from 'vue';
import { mapGetters } from 'vuex'

export default defineComponent({
  name: 'DarkModeSetting',
  data() {
    return {
      //mode: this.$q.dark.mode,
      options: [
        { value: 'auto', label: 'Auto' },
        { value: false, label: this.$t('Off') },
        { value: true, label: this.$t('On') },
      ]
    }
  },
  watch: {
    mode(mode) {
      this.$store.dispatch('settings/set', { key: 'darkMode', value: mode })
      this.$q.dark.set(mode)

      let message = ''
      switch (mode) {
        case true:
          message = this.$t('Dark mode enabled')
          break;

        case false:
          message = this.$t('Light mode enabled')
          break;

        case 'auto':
          message = this.$t('Color mode detected automatically')
          break;
      }

      this.$q.notify({
        group: 'darkMode',
        type: 'positive',
        message: message,
      })
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
