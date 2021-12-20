<template>
  <q-layout view="hHh lpR fFf" :class="{'bg-blue-grey-2': !$q.dark.isActive }">
    <q-ajax-bar
      ref="bar"
      position="top"
      color="accent"
      size="3px"
      skip-hijack
    />
    <q-header class="q-py-xs bg-primary-dark" height-hint="58">
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          @click="toggleLeftDrawer"
          aria-label="Menu"
          icon="fas fa-bars"
        />

        <q-btn flat no-caps no-wrap class="q-ml-xs">
          <q-icon :name="this.$store.getters.headline.icon" size="28px" />
          <q-toolbar-title shrink class="text-weight-bold text-h6">
            {{ this.$store.getters.headline.text }}
          </q-toolbar-title>
        </q-btn>

        <q-space />

        <q-spinner
          color="white"
          size="2.5em"
          v-if="requestRunning"
        />

        <div class="q-gutter-sm row items-center no-wrap">
          <q-btn round flat>
            <q-avatar size="35px" class="text-caption">
              <q-icon name="far fa-hat-wizard" style="font-size: 1.5em" />
            </q-avatar>
            <q-tooltip>Account</q-tooltip>
            <q-menu
              transition-show="jump-left"
              transition-hide="jump-right"
            >
              <div class="row no-wrap q-pa-md">
                <div class="column">
                  <div class="text-h6 q-mb-md">Quick Settings</div>
                  <q-toggle
                    label="Color Scheme"
                    v-model="darkMode"
                    toggle-indeterminate
                    indeterminate-value="auto"
                    indeterminate-icon="fas fa-adjust"
                    checked-icon="fas fa-moon"
                    unchecked-icon="fas fa-sun"
                  />
                  <!--
                  <q-toggle v-model="autoReload" label="Auto Reload" />
                  -->
                  <q-toggle v-model="privacy" label="Hide sensible data" />
                  <!--
                  <div>All Settings</div>
                  -->
                </div>

                <q-separator vertical inset class="q-mx-lg" />

                <div class="column items-center">
                  <q-avatar size="72px">
                    <q-icon name="far fa-hat-wizard" />
                  </q-avatar>

                  <!-- <div class="text-subtitle1 q-mt-md q-mb-xs">F4B...3CB</div> -->

                  <q-btn
                    color="primary"
                    label="Logout"
                    push
                    size="sm"
                    v-close-popup
                    @click="logout"
                  />
                </div>
              </div>
            </q-menu>
          </q-btn>
        </div>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
      :width="250"
    >
      <!--<q-scroll-area class="fit">-->
        <q-list>
          <q-item-label header class="text-weight-bold text-uppercase text-center">
            <q-img
              src="/img/dobby-logo-white-border.png"
              spinner-color="white"
              style="height: 40px; max-width: 40px"
              class="q-mr-sm"
            />
          </q-item-label>

          <q-separator class="q-mt-ms q-mb-xs" />

          <q-item v-for="link in links1" :key="link.text" v-ripple clickable :to="link.to">
            <q-item-section avatar>
              <q-icon color="grey" :name="link.icon" />
            </q-item-section>
            <q-item-section>
              <q-item-label>{{ link.text }}
              </q-item-label>
            </q-item-section>
            <q-badge v-if="link.badge && link.badge > 0" color="accent" floating>
              {{ link.badge }}
            </q-badge>
          </q-item>

          <q-separator class="q-mt-ms q-mb-xs" />

          <!-- <q-item-label header class="text-weight-bold text-uppercase">
            Your Notification Channels
          </q-item-label>


          <q-item v-for="link in links2" :key="link.text" v-ripple clickable>
            <q-item-section avatar>
              <q-icon color="grey" :name="link.icon" />
            </q-item-section>
            <q-item-section>
              <q-item-label>{{ link.text }}</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-toggle
              v-model="link.active"
              checked-icon="check"
              color="primary"
              unchecked-icon="clear"
              />
            </q-item-section>
          </q-item>

          <q-separator class="q-my-md" /> -->

          <q-item v-for="link in links4" :key="link.text" :to="link.to" v-ripple clickable>
            <q-item-section avatar>
              <q-icon color="grey" :name="link.icon" />
            </q-item-section>
            <q-item-section>
              <q-item-label>{{ link.text }}</q-item-label>
            </q-item-section>
          </q-item>

          <q-separator class="q-mt-md q-mb-lg" />

          <div class="q-px-md text-grey-9">
            <div class="row items-center q-gutter-x-sm q-gutter-y-xs">
              <a
                class="YL__drawer-footer-link text-grey"
              >
                Version {{ version }} <br />
                (Build: {{ release }})<br />
                (Date: {{ releaseDate }})
              </a>
            </div>
          </div>
        </q-list>
      <!--</q-scroll-area>-->
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { ref, watch, computed } from 'vue'
import { useQuasar } from 'quasar'
import { useStore } from 'vuex'

export default {
  name: 'MainLayout',

  setup () {
    const $q = useQuasar()
    const leftDrawerOpen = ref(false)
    const bar = ref(null)
    const darkMode = ref($q.dark.isActive)
    const store = useStore()
    const privacy = ref(store.getters['settings/value']('privacy'))
    const version = process.env.VERSION
    const release = process.env.CURRENT_RELEASE
    const releaseDate = process.env.RELEASE_DATE

    function toggleLeftDrawer () {
      leftDrawerOpen.value = !leftDrawerOpen.value
    }

    function logout () {
      store.dispatch('account/logout')
    }

    watch(darkMode, (darkMode) => {
      $q.dark.set(darkMode)
      store.dispatch('settings/set', { key: 'darkMode', value: darkMode })
    })

    watch(privacy, (privacyActive) => {
      store.dispatch('settings/set', { key: 'privacy', value: privacyActive })
    })

    return {
      leftDrawerOpen,
      bar,
      darkMode,
      privacy,
      version,
      release,
      releaseDate,

      toggleLeftDrawer,
      logout,

      requestRunning: computed(() => store.getters["requestRunning"]),

      autoReload: true,
      links1: [
        { icon: 'fab fa-fort-awesome-alt', text: 'Dashboard', to: "dashboard" },
        // { icon: 'fas fa-dumpster-fire', text: 'Needs Your Attention', badge: 2 },
        //{ icon: 'fas fa-dumpster-fire', text: 'Needs Your Attention' },
      ],
      /*
      links2: [
        { icon: 'fas fa-charging-station', text: 'TSLA / USD' },
        { icon: 'fab fa-twitter-square', text: 'TWTR / USD' },
        { icon: 'fab fa-google', text: 'GOOGL / USD' },
        { icon: 'fas fa-gamepad', text: 'GME / USD' }
      ],
      */
     links2: [
        { icon: 'fab fa-telegram', text: 'Telegram' , active: true },
        { icon: 'fas fa-envelope', text: 'Email', active: true },
        { icon: 'far fa-comment', text: 'Push', active: false },
      ],
      links4: [
        { icon: 'fal fa-bells', text: 'Manage Notifications', to: 'manage-notifications' },
        { icon: 'fal fa-archive', text: 'Manage Vaults', to: 'manage-vaults' },
        { icon: 'fal fa-sliders-h', text: 'Settings', to: 'settings' },
        { icon: 'fal fa-question-circle', text: 'WTF?!', to: 'wtf' },
        { icon: 'fal fa-chart-bar', text: 'Statistics', to: 'statistics' },
        //{ icon: 'fal fa-comments', text: 'Send feedback', to: 'feedback' }
      ],
    }
  }
}
</script>

<style lang="sass">
.YL

  &__toolbar-input-container
    min-width: 100px
    width: 55%

  &__toolbar-input-btn
    border-radius: 0
    border-style: solid
    border-width: 1px 1px 1px 0
    border-color: rgba(0,0,0,.24)
    max-width: 60px
    width: 100%

  &__drawer-footer-link
    color: inherit
    text-decoration: none
    font-weight: 500
    font-size: .75rem

    &:hover
      color: #000
</style>
