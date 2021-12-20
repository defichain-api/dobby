<template>
  <div style="max-width: 600px; margin: 0 auto;">
    <transition
      appear
      enter-active-class="animated pulse"
      leave-active-class="animated flipOutX"
    >
      <div class="q-pa-lg" v-if="!showSetup">
        <div class="text-h4 q-my-lg">Hello, friend <q-icon name="fas fa-hat-wizard" /></div>
        <div class="text-body1 text-italic q-mt-lg">
          This is Dobby - your personal <a href="https://defichain.com/" class="text-primary">DeFiChain</a> house elf. Dobby is happy because you found your way to him.
          He is very useful because he keeps you informed about your <a href="https://defichain.com/" class="text-primary">DeFiChain</a> vaults when they get in trouble.
          <br />
          <q-chip v-if="!showMore" dense clickable color="secondary" text-color="dark" icon="fas fa-info-circle" @click="showMore = !showMore">Read more...</q-chip>
          <q-chip v-if="showMore" dense clickable color="secondary" text-color="dark" icon="fas fa-compress-alt" @click="showMore = !showMore">Show less</q-chip>
        </div>
        <transition appear enter-active-class="animated flipInX" leave-active-class="animated flipOutX">
          <div class="container q-mt-lg" v-if="showMore">
            <div class="row">
              <div class="col-3 text-center q-pt-lg">
                <q-icon name="fas fa-bell" size="xl" />
              </div>
              <div class="col-9 text-body2">
                This Service will send you messages when some of your vaults on DeFiChain need attention.
                For example when they're close to being liquidated or if you just want to
                keep track of what's going on with your loans and how healty your vaults
                are.
                <br />
                This is achieved by a combination of this app and - what we call -
                notification channels.
                <br />
                You can choose from receiving messages via Telegram and Email for now.
                More will follow.
              </div>
            </div>
          </div>
        </transition>
        <q-card flat :bordered="!this.$q.dark.isActive" class="q-mt-xl text-center">
          <q-card-section>
            <!-- <q-icon name="fas fa-socks" size="xl" /> -->
            <q-img
              src="/img/dobby-logo-white-border.png"
              spinner-color="white"
              style="height: 60px; max-width: 60px"
            />
          </q-card-section>
          <q-separator inset />
          <q-card-section>
              <q-btn
                unelevated
                rounded
                color="primary"
                label="Start Setup (It's easy)"
                class="full-width "
                icon="fal fa-wand-magic"
                @click="showSetup = !showSetup"
              ></q-btn>
              <div class="q-my-md">
              - OR -
              </div>
              <q-input
                ref="existingUserId"
                rounded
                outlined
                dense
                color="primary"
                label-color="primary"
                v-model="userId"
                label="paste in an existing user key"
                debounce="250"
                error-message="This is not a valid Dobby User ID"
                :error="!userIdIsValid"
              >
                <template v-slot:append>
                  <!--<q-icon name="fas fa-paste" color="primary" cliackable />-->
                  <q-icon v-if="isUuid(userId)" @click="setExistingUserId" name="fad fa-arrow-circle-right" color="primary" cliackable />
                </template>
              </q-input>
          </q-card-section>
        </q-card>
        <div class="text-center">
          <q-btn
            outline
            rounded
            color="accent"
            label="Just Show Me A Demo"
            class="q-mt-xl"
            icon="fas fa-quidditch"
            size="sm"
            @click="prepareDemo()"
          ></q-btn>
        </div>
      </div>
    </transition>

    <transition
      appear
      enter-active-class="animated backInUp"
      leave-active-class="animated backOutDown"
    >
      <div v-if="showSetup">
        <div class="text-h4 q-my-lg q-ml-md">Set Up Dobby <q-icon name="fas fa-magic" /></div>
        <q-chip clickable @click="showSetup = !showSetup" class="q-ml-md" color="secondary" text-color="dark" icon="fas fa-arrow-left">
          back
        </q-chip>
        <div>
          <q-stepper
            v-model="step"
            vertical
            color="primary"
            animated
            class="q-mt-md"
            flat
          >
            <template v-slot:message>
              <transition
                appear
                enter-active-class="animated pulse"
              >
                <q-banner v-if="step === 1" class="bg-primary text-white q-px-lg">
                  Okay, let's get started...
                </q-banner>
              </transition>

              <transition
                appear
                enter-active-class="animated pulse"
              >
                <q-banner v-if="step === 2" class="bg-secondary text-dark q-px-lg">
                  You can name your vaults and add more later.
                </q-banner>
              </transition>

              <transition
                appear
                enter-active-class="animated pulse"
              >
                <q-banner v-if="step === 3" class="bg-secondary text-black q-px-lg">
                  You can copy your key to the clipboard.
                </q-banner>
              </transition>

              <transition
                appear
                enter-active-class="animated pulse"
              >
                <q-banner v-if="step === 4" class="bg-accent text-black q-px-lg">
                  Woohoo, that's it!
                </q-banner>
              </transition>

            </template>

            <q-step
              :name="1"
              title="What to do"
              icon="fas fa-question"
              :done="step > 1"
            >
              <p>
                Dobby will ask you for your vaults and automatically create a pseudo-anonymous dobby
                account for you.
              </p>
              <p>
                This account is free and neccessary for connecting your vaults with your notification channels.
              </p>
              <p>
                This app can't maniplulate your loans and does not track your activities.
                It just shows and uses publicly available data.
              </p>
              <q-stepper-navigation>
                <q-btn unelevated rounded @click="step = 2" color="primary" label="Continue" />
              </q-stepper-navigation>
            </q-step>

            <q-step
              :name="2"
              title="Your Vaults"
              caption="at least one"
              icon="create_new_folder"
              :done="step > 2"
            >
              Fill in your <b>DeFiChain addresses</b> holding vaults or your <b>vault IDs</b>
              directly.
              Please add at least one. If you don't have a vault yet, you can take
              a look at the <!--<q-chip outline clickable color="accent" text-color="dark" icon="fas fa-quidditch" >demo</q-chip>--> demo.

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
              <q-btn @click="addAddress(addressToAdd); addressToAdd = ''" :disabled="!(addressToAdd.length == 34 || addressToAdd.length == 42 || addressToAdd.length == 64)"  outline rounded dense icon="fas fa-plus-circle " color="primary" label="add" class="q-my-sm full-width"></q-btn>

              <q-list bordered padding v-if="addresses.length > 0" class="q-mt-md">
                <!--
                <q-item-label header>Your Addresses/Vaults</q-item-label>
                <q-separator spaced />
                -->
                <span v-for="(address, key) in addresses" :key="address">
                  <transition
                    appear
                    enter-active-class="animated pulse"
                  >
                    <q-item>

                      <q-item-section top avatar class="text-center">
                        <q-avatar color="primary" text-color="white" :icon="getEntryIcon(address)"></q-avatar>
                        <span v-if="stringIsDfiAddress(address)" class="text-caption">Addr.</span>
                        <span v-if="stringIsVaultId(address)" class="text-caption">Vault</span>
                      </q-item-section>

                      <q-item-section style="word-break: break-all;">
                        {{ address }}
                      </q-item-section>
                      <q-item-section top side>
                        <div class="text-grey-8 q-gutter-xs">
                          <q-btn @click="removeAddress(address)" size="md" flat dense round icon="delete" color="secondary" />
                        </div>
                      </q-item-section>
                    </q-item>
                  </transition>
                  <q-separator spaced v-if="addresses.length > key+1" />
                </span>
              </q-list>


              <q-stepper-navigation>
                <q-btn unelevated rounded :disabled="addresses.length < 1" @click="makeAccount()" color="primary" label="Continue and create account" />
                <q-btn flat @click="step = 1" color="primary" label="Back" class="q-ml-sm" />
                <q-btn flat @click="prepareDemo()" color="primary" label="demo" class="q-ml-sm" />
              </q-stepper-navigation>
            </q-step>

            <q-step
              :name="3"
              title="Your Account Key"
              caption="Don't lose it!"
              icon="assignment"
              :done="step > 3"
            >
              <q-card class="text-center q-mb-md">
                <q-card-section class="bg-primary text-white">
                  {{ userId }}
                </q-card-section>
                <q-card-section class="bg-secondary">
                  <q-btn class="bg-primary" @click="toClipboard(userId)">Copy to Clipboard</q-btn>
                </q-card-section>
              </q-card>

              Please store it in a safe place like your password manager.
              <q-stepper-navigation>
                <q-btn unelevated rounded @click="step = 4" color="primary" label="OK, saved it!" />
              </q-stepper-navigation>
            </q-step>

            <q-step
              :name="4"
              title="Done"
              icon="add_comment"
              :done="step == 4"
            >
              You've got it! Just to secure you have saved your account key. You can also find it in the settings:
              <div class="q-my-md">{{ userId }}</div>

              <q-stepper-navigation>
                <q-btn unelevated rounded to="dashboard" color="primary" label="Show my vaults" />
              </q-stepper-navigation>
            </q-step>
          </q-stepper>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import { copyToClipboard } from 'quasar'
import { validate as validateUuid } from 'uuid'

export default defineComponent({
  name: 'PageIndex',
  data () {
    return {
      showMore: (process.env.DEV && process.env.PREFILL_SETUP) ? false : false,
      showSetup: (process.env.DEV && process.env.PREFILL_SETUP) ? false : false,
      step: (process.env.DEV && process.env.PREFILL_SETUP) ? 2 : 1,
      addresses: (process.env.DEV && process.env.PREFILL_SETUP) ? [
        //'tedT9idRxCzmmxT4sST9gHmAZ5Mh24a2Wm',
        '054d66b6837355384e888c4ea3dd06bd1bf30a4dfa38c625f8fd430e9d321607',
      ] : [],
      userId: (process.env.DEV && process.env.PREFILL_SETUP) ? 'c00e07e1-0705-47a1-aef5-8544fe13adc1' : '',

      addressToAdd: (process.env.DEV && process.env.PREFILL_SETUP) ? 'tedT9idRxCzmmxT4sST9gHmAZ5Mh24a2Wm' : '',

    }
  },
  computed: {
    userIdIsValid() {
      if (this.userId.length == 0) return true
      return this.isUuid(this.userId)
    }
  },
  methods: {
    /**
     * Check if a string is a valid UUID
     */
    isUuid: function() {
      return validateUuid(this.userId)
    },

    /**
     * Set a user id and fetch user's data from dobby API
     */
    setExistingUserId: function () {
      this.$store.dispatch('account/setUserId', this.userId)
      this.$store.dispatch('account/loadUserData')
      this.$store.dispatch('notifications/fetch')

      // redirect to dashboard
      this.$router.push({ name: 'dashboard' })
    },

    /**
     * Set dobby to demo mode.
     * Essentially, this means setting the user id to a specific one
     */
    prepareDemo: async function () {

      await this.$store.dispatch('account/setUserId', process.env.DEMO_ACCOUNT_ID)
      this.$store.dispatch('account/loadUserData')

      // redirect to dashboard
      this.$router.push({ name: 'dashboard' })
    },

    /**
     * Adding a new addres to the addresses array at first position.
     * Does not add addresses already known
     */
    addAddress: function (addressToAdd) {
      if (this.addresses.includes(addressToAdd)) return;
      this.addresses.unshift(addressToAdd);
    },

    /**
     * Removes an address from the addresses array when this entry is known
     */
    removeAddress: function (address) {
      const index = this.addresses.indexOf(address);
      if (index > -1) {
        this.addresses.splice(index, 1);
      }
    },

    /**
     * Generates a new account based on the user's input:
     * - theme (dark/light)
     * - addresses
     *
     * Generates a new user id (UUID) and stores it in the settings
     */
    makeAccount: function () {
      this.$api.post('setup', this.getSetupData())
        .then((response) => {
          this.userId = response.data.userId
          this.$store.dispatch('account/setUserId', this.userId)
          this.$store.dispatch('account/loadUserData')
          this.step = 3
        })
        .catch((error) => {
          console.log(error)
          this.$q.notify({
            type: 'negative',
            message: 'Whoops. There was an error :(',
            position: 'bottom',
          })
        })
    },

    /**
     * Processes neccessary data for the setup process and delivers a suitable data
     * format for calling the dobby API
     */
    getSetupData: function () {
      return {
        "language": "en",
        "theme": (this.$q.dark.isActive) ? "dark" : "light",
        "ownerAddresses": JSON.parse(JSON.stringify(this.addresses))
      }
    },

    /**
     * Checks if string is most likely a vault ID
     *
     * TODO: check for alphanumeric characters only
     */
    stringIsVaultId(string) {
      return string.length == 64
    },

    /**
     * Checks if string is most likely a DFI address
     *
     * TODO: check for alphanumeric characters only
     */
    stringIsDfiAddress(string) {
      return string.length == 34
    },

    /**
     * Decides wheater the icon in the adresses list is that for DFI address or for a
     * vault ID
     */
    getEntryIcon(string) {
      if (this.stringIsVaultId(string)) return 'fas fa-archive'
      else if (this.stringIsDfiAddress(string)) return 'fas fa-address-book'
    },

    /**
     * Copies a string to the clipboard
     */
    toClipboard: function (text) {
      copyToClipboard(text)
        .then(() => {
            this.$q.notify({
              type: 'info',
              message: this.userId + ' copied to your clipboard'
            })
        })
        .catch((error) => {
          console.log(error)
        })
    },
  },
})
</script>

<style lang="sass">

</style>
