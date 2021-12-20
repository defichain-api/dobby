<template>
  <div class="q-pa-md row items-start q-gutter-md">

    <q-list bordered padding class="q-mt-md"
      :class="{'bg-white' : !this.$q.dark.isActive, 'bg-dark': this.$q.dark.isActive}"
    >
      <span v-for="(vault) in vaults" :key="vault.vaultId">
        <transition
          appear
          enter-active-class="animated pulse"
        >
          <q-item>
            <q-item-section top avatar class="text-center">
              <q-avatar
                :class="{'bg-positive': vault.state == 'active', 'bg-warning': vault.state == 'mayLiquidate', 'bg-negative': vault.state == 'inLiquidation'}"
                text-color="white" icon="fas fa-archive"
              >
              </q-avatar>
            </q-item-section>

            <q-item-section style="word-break: break-all;">
              <div v-if="!privacy" class="row text-caption">
                {{ vault.vaultId }}
              </div>
              <div v-if="privacy" class="row text-caption">
                🧦🧦🧦🧦🧦🧦🧦🧦🧦
              </div>
              <div class="row q-mt-sm text-grey">
                Collateral:
                <span v-if="!privacy">
                  <span v-for="(collateral, index) in vault.collateralAmounts" :key="index">&nbsp;{{ collateral.token }} {{ collateral.amount.toLocaleString(locale, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</span>
                </span>
                <span v-if="privacy" >🧦🧦🧦</span>
              </div>
              <div class="row text-grey" v-if="vault.loanAmounts.length > 0">
                Loans:
                <span v-if="!privacy">
                  <span v-for="(loan, index) in vault.loanAmounts" :key="index">&nbsp;{{ loan.token }} {{ loan.amount.toLocaleString(locale, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</span>
                </span>
                <span v-if="privacy" >🧦🧦🧦</span>
              </div>
              <div class="row text-grey" v-if="vault.loanAmounts.length == 0">
                no loans yet
              </div>
              <div>
                <q-input v-if="vault.vaultId in vaultNames" v-model="vaultNames[vault.vaultId]" outlined dense label="give this vault a name" type="text">
                  <template v-slot:append>
                    <q-chip
                      v-if="vaultNames[vault.vaultId].length > 0"
                      @click="setVaultName(vault.vaultId, vaultNames[vault.vaultId])"
                      icon="fal fa-save"
                      color="primary"
                      text-color="white"
                      clickable
                      size="sm"
                      label="save"
                    />
                  </template>
                </q-input>
              </div>
            </q-item-section>

            <q-item-section top side>
              <div class="text-grey-8 q-gutter-xs">
                <q-btn @click="showConfirmRemoveVault(vault.vaultId)" size="md" flat dense round icon="delete" color="secondary" />
              </div>
            </q-item-section>
          </q-item>
        </transition>
        <q-separator spaced  />
      </span>
      <q-item>
        <q-item-section>
          <q-input
            outlined
            dense
            class="q-pt-md"
            color="primary"
            label-color="primary"
            v-model="addressToAdd"
            label="paste in a DFI address or a vault ID"
            :loading="false"
          ></q-input>
          <q-btn
            v-if="!isDemoUser()"
            @click="addVault(addressToAdd); addressToAdd = ''"
            :disabled="!(addressToAdd.length == 34 || addressToAdd.length == 42 || addressToAdd.length == 64)"  outline rounded dense icon="fas fa-plus-circle" color="primary" label="add" class="q-my-sm full-width"
          />
          <q-btn
            v-if="isDemoUser()"
            disabled
            outline
            rounded
            dense
            icon="fal fa-lightbulb-exclamation"
            color="primary"
            label="adding vaults is disabled in demo mode"
            class="q-my-sm full-width"
          />
        </q-item-section>
      </q-item>
    </q-list>

  </div>
</template>

<script>
import { defineComponent } from 'vue'
import { mapGetters, mapActions } from 'vuex'

export default defineComponent({
  name: 'ManageVaults',
  data () {
    return {
      addressToAdd: '',
      vaultNames: {},
    }
  },
  created() {
    this.$store.dispatch('setHeadline', {text: 'Your Vaults', icon: 'fal fa-archive'})
    this.vaults.forEach((vault) => {
      this.vaultNames[vault.vaultId] = vault.name
    })
  },
  methods: {
    addVault(address) {
      this.$api.post("/user/vault", {"vaultId": address})
        .then((result) => {
          this.reloadVaults()
        })
        .catch((error) => {
          const errorMessage = JSON.parse(error.request.response)
          this.$q.notify({
            type: 'error',
            message: errorMessage.message,
          })
        })
    },

    removeVault(address) {
      this.$api.delete("/user/vault", { "data": { "vaultId": address }})
        .then((result) => {
          this.clearVaultList()
          this.reloadVaults()
        })
        .catch((error) => {
          this.$q.notify({
            type: 'error',
            message: error.message,
          })
        })
    },

    showConfirmRemoveVault(address) {
      this.$q.dialog({
        title: 'Confirm vault removal',
        message: 'Do you really want to remove this vault from Dobby? He won\'t watch it anymore.',
        color: 'primary',
        cancel: true,
        focus: 'cancel',
        persistent: true,
      }).onOk(() => {
        this.removeVault(address)
      })
    },

    setVaultName(vaultId, vaultName) {
      this.$api.put('/user/vault/' + vaultId, { name: vaultName })
        .then(() => {
          this.$q.notify({
            group: 'vaultNameSaved',
            type: 'positive',
            message: 'Vault name saved',
          })
          this.reloadVaults()
        })
    },

    isDemoUser() {
      return (process.env.DEMO_ACCOUNT_ID == this.userId)
    },

    ...mapActions({
      reloadVaults: 'account/loadUserData',
      clearVaultList: 'account/clearVaultList',
    })
  },
  computed: {
    locale: function() {
      return this.$root.$i18n.locale
    },
    privacy() {
      return this.settingValue('privacy')
    },
    ...mapGetters({
      vaults: 'account/vaults',
      userId: 'account/userId',
      settingValue: 'settings/value',
    }),
  },
  // watch: {
  //   vaults: function (newVaults) {
  //     newVaults.forEach((vault) => {
  //       this.vaultNames[vault.vaultId] = vault.name
  //     })
  //   }
  // }
})
</script>

<style lang="sass">

</style>
