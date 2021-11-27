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
            D.O.B.B.Y.
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
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import { useStore } from "vuex";

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
      this.$store.dispatch('settings/set', 'darkMode', newValue)
      this.$q.dark.set(newValue)
    }
  },
  created() {
    //this.$q.dark.set(this.$store.getters["settings/value"]('darkMode'))
    this.colorScheme = this.$q.dark.isActive
  }
  /*
  setup () {
    const $q = useQuasar()
    const bar = ref(null)
    const store = useStore();

    const setDarkMode = () => { currentTime.value = new Date(); };

    $q.dark.set(store.getters["settings/value"]('darkMode'))

    return {
      bar,
      colorScheme: store.getters["settings/value"]('darkMode'),
    }
  }
  /**/
}
</script>

<style lang="sass">

</style>
