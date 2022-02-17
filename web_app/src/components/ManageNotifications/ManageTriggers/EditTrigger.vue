<template>
  <q-popup-edit
    v-model.number="ratio"
    buttons
    label-set="Save"
    label-cancel="Close"
    v-slot="scope"
    ref="editor"
  >
    <q-input
      type="number"
      v-model.number="scope.value"
      dense
      autofocus
      @keyup.enter="scope.set"
    />
  </q-popup-edit>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  name: 'EditTrigger',
  props: {
    triggerId: {
      type: Number,
      required: true,
    }
  },
  methods: {
    show: function() {
      this.$refs.editor.show()
    },
    ...mapActions({
      updateTrigger: 'notifications/updateTrigger',
    })
  },
  computed: {
    ratio: {
      get() {
        return this.triggerData(this.triggerId).ratio
      },
      set(newRatio) {
        this.updateTrigger({triggerId: this.triggerId, ratio: newRatio})
        this.$q.notify({
          type: 'positive',
          message: 'Notification trigger set to ' + newRatio + ' %' ,
        })
      }
    },
    ...mapGetters({
      triggerData: 'notifications/trigger',
    })
  }
}
</script>
