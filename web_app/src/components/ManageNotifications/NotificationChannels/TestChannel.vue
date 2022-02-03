<template>
  <q-btn
    :rounded="rounded"
    :outline="outline"
    :label="label"
    :color="color"
    :unelevated="unelevated"
    @click="testChannel()"
    :icon="icon"
  />
</template>

<script>
export default {
  name: 'TestChannel',
  props: {
    channel: {
      required: true,
    },
    label: {
      required: true,
    },
    color: {
      required: true,
    },
    icon: {
      required: true,
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
    testChannel: function () {
      this.$api.post("/user/gateways/test", { "type": this.channel })
        .then((result) => {
          this.$q.notify({
            group: 'gatewayTest',
            type: 'positive',
            message: 'Dobby should have said hello via ' + this.channel + ' :)',
          })
        })
    },
  }
}
</script>

<style>

</style>
