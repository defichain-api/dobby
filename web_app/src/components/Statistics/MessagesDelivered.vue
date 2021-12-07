<template>
  <q-card flat :bordered="$q.dark.isActive">
    <q-card-section class="q-pb-none">
      <div class="text-h6">Messages Delivered</div>
    </q-card-section>
    <q-card-section class="container text-center">
      <div class="row">
        <div class="col-6 text-left">
          <div class="text-h4 text-primary">{{ this.latest.messages.sum_messages.toLocaleString(locale) }}</div>
          <div class="text-caption">Sum</div>
        </div>
        <div class="col-2">
          <div class="text-body1 text-primary">{{ this.latest.messages.gateways.telegram.toLocaleString(locale) }}</div>
          <q-avatar style="background-color: #0088cc;" font-size="0.6em" text-color="white" size="md" icon="fab fa-telegram-plane" />
        </div>
        <div class="col-2 ">
          <div class="text-body1 text-primary">{{ this.latest.messages.gateways.mail.toLocaleString(locale) }}</div>
          <q-avatar color="green" font-size="0.6em" text-color="white" size="md" icon="fal fa-mailbox" />
        </div>
        <div class="col-2 ">
          <div class="text-body1 text-primary">{{ this.latest.messages.gateways.webhook.toLocaleString(locale) }}</div>
          <q-avatar color="red" font-size="0.5em" text-color="white" size="md" icon="fal fa-send-back" />
        </div>
      </div>
    </q-card-section>
    <q-card-section class="container q-pt-none">
      <div class="row">
        <div class="col-12 text-overline">
          Types
        </div>
      </div>
      <div class="row">
        <div class="col-4">
          <div class="text-h6 text-primary">{{ this.latest.messages.types.info.toLocaleString(locale) }}</div>
          <div class="text-caption">Info</div>
        </div>
        <div class="col-4 ">
          <div class="text-h6 text-primary">{{ this.latest.messages.types.warning.toLocaleString(locale) }}</div>
          <div class="text-caption">Warning</div>
        </div>
        <div class="col-4 ">
          <div class="text-h6 text-primary">{{ this.latest.messages.types.daily.toLocaleString(locale) }}</div>
          <div class="text-caption">Daily</div>
        </div>
      </div>
    </q-card-section>
  </q-card>
</template>

<script>
export default {
  name: 'MessagesDelivered',
  props: {
    statistics: {
      required: true,
    },
  },
  data() {
    return {

    }
  },
  computed: {
    latest: function() {
      return this.statistics[0]
    },
    locale: function() {
      return this.$root.$i18n.locale
    },
  }
}
</script>

<style lang="sass" scoped>
</style>
