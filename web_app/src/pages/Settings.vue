<template>
  <div class="q-pa-md row items-start q-gutter-md">
    <transition-group
      appear
      enter-active-class="animated pulse"
    >
      <UserId key="userId" />
      <DarkMode key="darkMode" />
    </transition-group>
  </div>
</template>

<script>
import { defineComponent } from 'vue';
import UserId from 'components/Settings/UserId.vue'
import DarkMode from 'components/Settings/DarkMode.vue'

export default defineComponent({
  name: 'Settings',
  components: {
    UserId,
    DarkMode,
  },
  created() {
    this.$store.dispatch('setHeadline', {text: 'Dobby Settings', icon: 'fal fa-sliders-h'})
  },
})
</script>

<style lang="sass">
  .q-card
    min-width: 290px

    .main-info
      min-height: 105px

    .coll-progress
      min-height: 70px

    .coll-info
      min-height: 100px

  body.screen--xs
    .q-card
      width: 100%

  body.screen--sm
    .q-card
      width: 31%
</style>
