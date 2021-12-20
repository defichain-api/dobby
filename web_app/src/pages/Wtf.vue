<template>
  <div class="q-pa-md row items-start q-gutter-md">
    <q-card flat :bordered="$q.dark.isActive">
      <q-card-section>
        <div class="text-h4 q-mb-lg">Hello, friend <q-icon name="fas fa-hat-wizard" /></div>
        <div class="q-mt-lg">
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
                You can choose from receiving messages via Telegram, Webhook and Email for now.
              </div>
            </div>
          </div>
        </transition>
      </q-card-section>
    </q-card>

    <q-card flat :bordered="$q.dark.isActive">
      <q-card-section>
        <div class="text-h6 q-mb-lg">Meet my developers <q-icon name="fal fa-flask-potion" /></div>
        <p>
          You might know them from other DeFiChain community projects.<br />
        </p>
        <p>
          They are happy when you send them some feedback and they answer questions, too!
        </p>
        <div class="text-body1 text-italic q-mt-lg">
            <q-btn unelevated rounded type="a" class="full-width q-mb-md" color="primary" @click="toTwitter()" icon="fab fa-twitter" label="Twitter (@dobby_dfi)" />
            <q-btn unelevated rounded tyle="a" class="full-width" color="primary" @click="toTelegramGroup()" icon="fab fa-telegram" label="Telegram group" />
        </div>
      </q-card-section>
      <q-card-section>
        <q-card flat bordered class="q-mb-md" v-for="dev in devs" :key="dev.name">
            <q-card-section>
              <div class="text-h6">{{ dev.name }}</div>
              {{ dev.role }}
            </q-card-section>
            <q-card-section>
              <q-chip style="background-color: #1DA1F2" text-color="white" icon="fab fa-twitter" clickable @click="openInNewTab('https://twitter.com/' + dev.twitter)">
                @{{ dev.twitter }}
              </q-chip>
              <q-chip style="background-color: #0e76a8" text-color="white" icon="fab fa-linkedin" clickable @click="openInNewTab('https://linkedin.com/in/' + dev.linkedin)">
                {{ dev.linkedin }}
              </q-chip>
              <q-chip v-if="dev.website" color="accent" text-color="white" icon="fal fa-browser" clickable @click="openInNewTab(dev.website)">
                {{ dev.website }}
              </q-chip>
              <q-chip v-for="website in dev.other" :key="website.name" color="positive" text-color="white" icon="fal fa-browser" clickable @click="openInNewTab(website.link)">
                {{ website.name }}
              </q-chip>
            </q-card-section>
          </q-card>
      </q-card-section>
    </q-card>

    <q-card flat :bordered="$q.dark.isActive">
      <q-card-section class="q-pb-none">
        <div class="text-h6 q-mb-lg">Buy us a coffee <q-icon name="fal fa-mug-hot" /></div>
        <p>
          We've got a bunch of questions where to send DFI to make a donation. So, here it is:<br />
        </p>
      </q-card-section>
      <q-card-section class="q-pt-none">
        <q-card flat class="text-center q-mb-md">
          <q-card-section class="bg-primary text-white text-caption" style="font-size: 0.8em;">
            <q-img src="/img/qr-donations.jpg" alt="" style="max-width: 400px;"/>
            <br />
            df1qw0522d3tc8t3p5656a0u69mfauwg99xkdst50w
          </q-card-section>
          <q-card-section class="bg-accent">
            <q-btn unelevated rounded color="primary" class="text-white" @click="toClipboard(donationAddress)">Copy to Clipboard</q-btn>
          </q-card-section>
        </q-card>
        <span class="text-body2">(Dobby got socks already)</span>
      </q-card-section>
    </q-card>

    <q-card flat :bordered="$q.dark.isActive">
      <q-card-section>
        <div class="text-h6 q-mb-lg">Funded by the community <q-icon name="fal fa-coins" /></div>
        <p>
          Dobby's development has been supported by the DeFiChain community. Have a look a the Community Fund Proposal, which has been accepted with more than 90% yes votes.
        </p>
        <div class="text-body1 text-italic q-mt-lg">
          <q-btn unelevated rounded type="a" class="full-width q-mb-md" color="primary" @click="toCfp()" icon="fab fa-twitter" label="CFP 2111-02 on GitHub" />
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import { openURL } from 'quasar'
import { copyToClipboard } from 'quasar'

export default {
  data() {
    return {
      showMore: false,
      donationAddress: process.env.DONATION_ADDRESS,
      devs: [
        {
          name: 'Adrian',
          role: 'API (backend logic)',
          twitter: 'adrian_schnell',
          linkedin: 'adrian-schnell-287a2250',
          website: 'https://roestfrisch.com',
          other: [
            {
              name: 'DFI Signal',
              link: 'https://www.dfi-signal.com'
            },
            {
              name: 'Masternode Health',
              link: 'https://docs.defichain-masternode-health.com/#introduction'
            },
          ],
        },
        {
          name: 'Chris',
          role: 'Hosting & Data Sources',
          twitter: 'sandric28869249',
          linkedin: 'christian-sandrini-060b8039',
          website: null,
          other: [
            {
              name: 'Masternode Health',
              link: 'https://docs.defichain-masternode-health.com/#introduction'
            },
          ],
        },
        {
          name: 'Michael',
          role: 'User Interface (this app)',
          twitter: 'dt_buzzjoe',
          linkedin: 'derfuchs',
          website: 'https://www.derfuchs.net',
          other: [
            {
              name: 'Masternode Monitor',
              link: 'https://www.defichain-masternode-monitor.com'
            },
            {
              name: 'Masternode Health',
              link: 'https://docs.defichain-masternode-health.com/#introduction'
            },
          ],
        },
      ]
    }
  },
  methods: {
    toTwitter() {
      openURL(process.env.TWITTER_LINK)
    },
    toTelegramGroup() {
      openURL(process.env.TELEGRAM_GROUP_LINK)
    },
    toCfp() {
      console.log("test")
      openURL("https://github.com/DeFiCh/dfips/issues/75")
    },
    openInNewTab(url) {
      openURL(url)
    },
    toClipboard(text) {
      copyToClipboard(text)
        .then(() => {
            this.$q.notify({
              type: 'info',
              message: 'donation address copied to your clipboard'
            })
        })
        .catch((error) => {
          console.log(error)
        })
    },
  },
}
</script>

<style lang="sass" scoped>
  ul
    padding: 0
    li
      list-style: none
  .q-card
      width: 100%


  body.screen--xs
    .q-card
      width: 100%
      max-width: inherit

  body.screen--sm
    .q-card
      width: 31%
</style>
