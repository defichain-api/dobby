<template>
  <q-layout view="hHh lpR fFf">
    <q-ajax-bar
      ref="bar"
      position="top"
      color="accent"
      size="3px"
      skip-hijack
    />
    <q-header elevated class="q-py-xs" height-hint="58">
      <q-toolbar>
        <q-btn flat no-caps no-wrap>
          <q-icon name="fad fa-socks" size="28px" />
          <q-toolbar-title shrink class="text-weight-bold">
            Dobby
          </q-toolbar-title>
        </q-btn>
        <q-space />

        <div class="q-gutter-sm row items-center no-wrap">
          <q-toggle
            v-model="colorScheme"
            checked-icon="fas fa-moon"
            unchecked-icon="fas fa-sun"
            color="dark"
          />
        </div>
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>

export default {
  name: 'SetupWizardLayout',
  data() {
    return {
      bar: null,
      colorScheme: 'auto',
    }
  },
  watch: {
    colorScheme(newValue, oldValue) {
      this.$store.dispatch('settings/set', { key: 'darkMode', value: newValue })
      this.$q.dark.set(newValue)
    }
  },
  created() {
    this.colorScheme = this.$q.dark.isActive
  }
}
</script>

<style lang="sass">

</style>
