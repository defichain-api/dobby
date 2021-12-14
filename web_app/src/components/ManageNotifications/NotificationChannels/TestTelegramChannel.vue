<template>
  <q-btn
    :rounded="rounded"
    :outline="outline"
    :label="label"
    :color="color"
    :unelevated="unelevated"
    @click="testTelegram()"
    icon="fab fa-telegram-plane"
  />
</template>

<script>
export default {
  name: 'TestTelegramChannel',
  props: {
    label: {
      default: 'test Telegram'
    },
    color: {
      default: 'telegram'
    },
    rounded: {
      default: false
    },
    outline: {
      default: false
    },
    unelevated: {
      default: false
    },
  },
  methods: {
    testTelegram: function () {
      this.$api.post("/user/gateways/test", { "type":"telegram" })
        .then((result) => {
          this.$q.notify({
            group: 'telegram',
            type: 'positive',
            message: 'Dobby should have said hello via Telegram :)',
          })
        })
    },
  }
}
</script>

<style>

</style>
