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
        <q-btn
          flat
          dense
          round
          @click="toggleLeftDrawer"
          aria-label="Menu"
          icon="fas fa-bars"
        />

        <q-btn flat no-caps no-wrap class="q-ml-xs">
          <q-icon name="fas fa-socks" size="28px" />
          <q-toolbar-title shrink class="text-weight-bold">
            D.O.B.B.Y.
          </q-toolbar-title>
        </q-btn>
        <!--
        <q-space />

        <div class="YL__toolbar-input-container row no-wrap">
          <q-input dense outlined square v-model="search" placeholder="Search" class="bg-white col" />
          <q-btn class="YL__toolbar-input-btn" color="grey-3" text-color="grey-8" icon="search" unelevated />
        </div>
        -->

        <q-space />

        <div class="q-gutter-sm row items-center no-wrap">
          <!--
          <q-btn round dense flat color="grey-8" icon="video_call" v-if="$q.screen.gt.sm">
            <q-tooltip>Create a video or post</q-tooltip>
          </q-btn>
          <q-btn round dense flat color="grey-8" icon="apps" v-if="$q.screen.gt.sm">
            <q-tooltip>Apps</q-tooltip>
          </q-btn>
          <q-btn round dense flat color="grey-8" icon="message" v-if="$q.screen.gt.sm">
            <q-tooltip>Messages</q-tooltip>
          </q-btn>
          -->
          <q-btn round dense flat icon="fas fa-bell">
            <q-badge color="accent" floating>
              2
            </q-badge>
            <q-tooltip>Notifications</q-tooltip>
          </q-btn>
          <q-btn round flat>
            <q-avatar size="26px">
              <q-icon name="fas fa-user-astronaut" />
            </q-avatar>
            <q-tooltip>Account</q-tooltip>
            <q-menu
              transition-show="jump-left"
              transition-hide="jump-right"
            >
              <div class="row no-wrap q-pa-md">
                <div class="column">
                  <div class="text-h6 q-mb-md">Settings</div>
                  <q-toggle
                    label="Color Scheme"
                    v-model="colorScheme"
                    toggle-indeterminate
                    indeterminate-value="auto"
                    indeterminate-icon="fas fa-adjust"
                    checked-icon="fas fa-moon"
                    unchecked-icon="fas fa-sun"
                  />
                  <q-toggle v-model="autoReload" label="Auto Reload" />
                </div>

                <q-separator vertical inset class="q-mx-lg" />

                <div class="column items-center">
                  <q-avatar size="72px">
                    <q-icon name="fas fa-user-astronaut" />
                  </q-avatar>

                  <div class="text-subtitle1 q-mt-md q-mb-xs">F4B...3CB</div>

                  <q-btn
                    color="primary"
                    label="Logout"
                    push
                    size="sm"
                    v-close-popup
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
      :width="240"
    >
      <q-scroll-area class="fit">
        <q-list padding>
          <q-item v-for="link in links1" :key="link.text" v-ripple clickable>
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

          <q-separator class="q-mt-md q-mb-xs" />

          <q-item-label header class="text-weight-bold text-uppercase">
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

          <q-separator class="q-my-md" />

          <q-item-label header class="text-weight-bold text-uppercase">
            D.O.B.B.Y.
          </q-item-label>

          <q-item v-for="link in links4" :key="link.text" v-ripple clickable>
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
                class="YL__drawer-footer-link"
              >
                Version 1
              </a>
            </div>
          </div>
        </q-list>
      </q-scroll-area>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { ref } from 'vue'
import { useQuasar } from 'quasar'

export default {
  name: 'MainLayout',

  setup () {
    const $q = useQuasar()
    const leftDrawerOpen = ref(false)
    const search = ref('')
    const bar = ref(null)
    $q.dark.set(true)

    function toggleLeftDrawer () {
      leftDrawerOpen.value = !leftDrawerOpen.value
    }

    return {
      leftDrawerOpen,
      search,
      bar,

      toggleLeftDrawer,

      colorScheme: false,
      autoReload: true,
      links1: [
        { icon: 'fab fa-fort-awesome-alt', text: 'Dashboard' },
        { icon: 'fas fa-dumpster-fire', text: 'Needs Your Attention', badge: 2 },
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
        { icon: 'fab fa-telegram', text: 'Telegram' , active: true},
        { icon: 'fas fa-envelope', text: 'Email', active: true },
        { icon: 'far fa-comment', text: 'Push', active: false },
      ],
      links4: [
        { icon: 'fas fa-archive', text: 'Manage Vaults' },
        { icon: 'fas fa-sliders-h', text: 'Settings' },
        { icon: 'fas fa-question-circle', text: 'WTF?!' },
        { icon: 'fas fa-comments', text: 'Send feedback' }
      ],
      buttons1: [
        { text: 'Version' },
        { text: 'Press' },
        { text: 'Copyright' },
        { text: 'Contact us' },
        { text: 'Creators' },
        { text: 'Advertise' },
        { text: 'Developers' }
      ],
      buttons2: [
        { text: 'Terms' },
        { text: 'Privacy' },
        { text: 'Policy & Safety' },
        { text: 'Test new features' }
      ]
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





<!--
<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />

        <q-toolbar-title>
          Quasar App
        </q-toolbar-title>

        <div>Quasar v{{ $q.version }}</div>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
    >
      <q-list>
        <q-item-label
          header
        >
          Essential Links
        </q-item-label>

        <EssentialLink
          v-for="link in essentialLinks"
          :key="link.title"
          v-bind="link"
        />
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import EssentialLink from 'components/EssentialLink.vue'

const linksList = [
  {
    title: 'Docs',
    caption: 'quasar.dev',
    icon: 'school',
    link: 'https://quasar.dev'
  },
  {
    title: 'Github',
    caption: 'github.com/quasarframework',
    icon: 'code',
    link: 'https://github.com/quasarframework'
  },
  {
    title: 'Discord Chat Channel',
    caption: 'chat.quasar.dev',
    icon: 'chat',
    link: 'https://chat.quasar.dev'
  },
  {
    title: 'Forum',
    caption: 'forum.quasar.dev',
    icon: 'record_voice_over',
    link: 'https://forum.quasar.dev'
  },
  {
    title: 'Twitter',
    caption: '@quasarframework',
    icon: 'rss_feed',
    link: 'https://twitter.quasar.dev'
  },
  {
    title: 'Facebook',
    caption: '@QuasarFramework',
    icon: 'public',
    link: 'https://facebook.quasar.dev'
  },
  {
    title: 'Quasar Awesome',
    caption: 'Community Quasar projects',
    icon: 'favorite',
    link: 'https://awesome.quasar.dev'
  }
];

import { defineComponent, ref } from 'vue'

export default defineComponent({
  name: 'MainLayout',

  components: {
    EssentialLink
  },

  setup () {
    const leftDrawerOpen = ref(false)

    return {
      essentialLinks: linksList,
      leftDrawerOpen,
      toggleLeftDrawer () {
        leftDrawerOpen.value = !leftDrawerOpen.value
      }
    }
  }
})
</script>
-->
