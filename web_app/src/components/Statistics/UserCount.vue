<template>
  <q-card flat :bordered="$q.dark.isActive">
    <q-card-section class="q-pb-none">
      <div class="text-h6">User Accounts</div>
    </q-card-section>
    <q-card-section>
      <div class="text-h3 text-primary">{{ this.latest.user_count }}</div>
    </q-card-section>
  </q-card>
</template>

<script>
export default {
  name: 'UserCount',
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
    }
  }
}
</script>

<style>

</style>
