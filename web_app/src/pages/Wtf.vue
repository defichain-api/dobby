<template>
  <div class="q-pa-md row items-start q-gutter-md">
    <q-card flat :bordered="$q.dark.isActive">
      <q-card-section>
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
      </q-card-section>
    </q-card>
    <q-card flat :bordered="$q.dark.isActive">
      <q-card-section>
        <div class="text-h6 q-my-lg">Meet my developers <q-icon name="fal fa-flask-potion" /></div>
        <p>
          They are happy when you send them some feedback and they answer questions, too!
        </p>
        <div class="text-body1 text-italic q-mt-lg">
            <q-btn type="a" class="full-width q-mb-md" color="primary" href="https://twitter.com/dobby_dfi" icon="fab fa-twitter" label="Twitter (@dobby_dfi)" />
            <q-btn tyle="a" class="full-width" color="primary" href="https://t.me/defichain_dobby" icon="fab fa-telegram" label="Telegram group" />
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      showMore: false
    }
  }
}
</script>

<style lang="sass" scoped>
  ul
    padding: 0
    li
      list-style: none
  .q-card
      min-width: 290px
      max-width: 23%

  body.screen--xs
    .q-card
      width: 100%
      max-width: inherit

  body.screen--sm
    .q-card
      width: 31%
</style>
