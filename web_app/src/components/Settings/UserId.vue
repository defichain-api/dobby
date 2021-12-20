<template>
  <q-card flat :bordered="$q.dark.isActive" style="min-width: 320px;">

    <q-card-section>
      <div class="text-primary text-h6">{{ $t('Your Dobby User Key') }}</div>
    </q-card-section>

    <q-card-section class="bg-accent text-white text-center">
      <q-chip clickable @click="showUserId = !showUserId">
        <q-avatar
          color="primary"
          text-color="white"
          :icon="(showUserId) ? 'fal fa-eye-slash' : 'fal fa-eye'"
        />
        <span class="text-caption" v-if="showUserId">{{ userId }}</span>
        <!-- <span v-if="!showUserId">########-####-####-##############</span> -->
        <span v-if="!showUserId">********-****-****-**************</span>
      </q-chip>
    </q-card-section>

    <q-card-section v-if="showUserId" class="text-center bg-white">
      <qrcode-vue :value="userId" :size="300" level="M" />
    </q-card-section>

    <q-card-section class="text-center">
      <q-btn
        rounded
        bordered
        outline
        dense
        class="text-center q-px-md"
        color="primary"
        icon="fal fa-clipboard-check"
        @click="toClipboard(userId)"
        label="Copy to Clipboard"
      >

      </q-btn>
    </q-card-section>


  </q-card>
</template>

<script>
import { defineComponent } from 'vue';
import { mapGetters } from 'vuex'
import { copyToClipboard } from 'quasar'
import QrcodeVue from 'qrcode.vue'

export default defineComponent({
  name: 'UserIdSetting',
  components: {
    QrcodeVue,
  },
  data () {
    return {
      showUserId: false
    }
  },
  methods: {
    /**
     * Copies a string to the clipboard
     */
    toClipboard: function (text) {
      copyToClipboard(text)
        .then(() => {
            this.$q.notify({
              type: 'positive',
              message: 'Your dobby user key has been copied to your clipboard',
            })
        })
        .catch((error) => {
          console.log(error)
        })
    },
  },
  computed: {
    ...mapGetters({
      userId: 'account/userId',
    }),
  },
})
</script>

<style lang="sass">

</style>
