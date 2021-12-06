<template>
  <div class="q-pa-md row items-start q-gutter-md">

    <q-list bordered padding class="q-mt-md"
      :class="{'bg-white' : !this.$q.dark.isActive, 'bg-dark': this.$q.dark.isActive}"
    >
      <!--
      <q-item-label header>Your Addresses/Vaults</q-item-label>
      <q-separator spaced />
      -->
      <span v-for="(vault) in vaults" :key="vault.vaultId">
        <transition
          appear
          enter-active-class="animated pulse"
        >
          <q-item>
            <q-item-section top avatar class="text-center">
              <q-avatar
                :class="{'bg-positive': vault.state == 'active', 'bg-warning': vault.state == 'mayLiquidate', 'bg-negative': vault.state == 'inLiquidation'}"
                text-color="white" icon="fas fa-archive"></q-avatar>
            </q-item-section>

            <q-item-section style="word-break: break-all;">
              <div class="row text-caption">
                {{ vault.vaultId }}
              </div>
              <div class="row q-mt-sm text-grey">
                Collateral:<span v-for="(collateral, index) in vault.collateralAmounts" :key="index">&nbsp;{{ collateral.token }} {{ collateral.amount.toLocaleString(locale, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</span>
              </div>
              <div class="row text-grey" v-if="vault.loanAmounts.length > 0">
                Loans:<span v-for="(loan, index) in vault.loanAmounts" :key="index">&nbsp;{{ loan.token }} {{ loan.amount.toLocaleString(locale, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</span>
              </div>
              <div class="row text-grey" v-if="vault.loanAmounts.length == 0">
                no loans yet
              </div>
            </q-item-section>

            <q-item-section top side>
              <div class="text-grey-8 q-gutter-xs">
                <q-btn @click="removeVault(vault.vaultId)" size="md" flat dense round icon="delete" color="secondary" />
              </div>
            </q-item-section>
          </q-item>
        </transition>
        <q-separator spaced  />
      </span>
      <q-item>
        <q-item-section style="word-break: break-all;">
          <div class="text-grey">
            (You'll be able to give your vaults a name in a later version of Dobby)
          </div>
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
            :disabled="!(addressToAdd.length == 34 || addressToAdd.length == 42 || addressToAdd.length == 64)"  outline rounded dense icon="fas fa-plus-circle " color="primary" label="add" class="q-my-sm full-width"
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
    }
  },
  created() {
    this.$store.dispatch('setHeadline', {text: 'Your Vaults', icon: 'fal fa-archive'})
  },
  methods: {
    /**
     * {
     *   "state": "ok",
     *   "message": "vault added to users repository"
     * }
     */
    addVault(address) {
      this.$api.post("/user/vault", {"vaultId": address})
        .then((result) => {
          this.reloadVaults()
          /*
          this.$q.notify({
            group: 'addVault',
            type: 'positive',
            message: 'Vault added',
          })
          */
        })
        .catch((error) => {
          this.$q.notify({
            type: 'error',
            message: error.message,
          })
        })
    },
    /**
     * {
     *   "state": "ok",
     *   "message": "removed vault from users repository"
     * }
     */
    removeVault(address) {
      this.$api.delete("/user/vault", { "data": { "vaultId": address }})
        .then((result) => {
          this.reloadVaults()
          /*
          this.$q.notify({
            group: 'removeVault',
            type: 'positive',
            message: 'Vault removed',
          })
          */
        })
        .catch((error) => {
          this.$q.notify({
            type: 'error',
            message: error.message,
          })
        })
    },
    isDemoUser() {
      return (process.env.DEMO_ACCOUNT_ID == this.userId)
    },
    ...mapActions({
      reloadVaults: 'account/loadUserData',
    })
  },
  computed: {
    locale: function() {
      return this.$root.$i18n.locale
    },
    ...mapGetters({
      vaults: 'account/vaults',
      userId: 'account/userId',
    }),
  }
})
</script>

<style lang="sass">

</style>
