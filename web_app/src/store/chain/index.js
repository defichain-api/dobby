import { api } from 'boot/axios'

export default {
  namespaced: true,

  state: {
    nextTick: [],
  },

  getters: {
    /**
     * Returns the user's gateways, stored locally
     */
    nextTick: (state) => {
      return state.nextTick
    },

  },

  actions: {
    /**
     * Loads the user's gateways and triggers from Dobby API
     */
    fetch({ dispatch }) {
      dispatch('fetchNextTick')
    },

    /**
     * Call Dobby API für a list of user's gateways
     */
    fetchNextTick({ commit }) {
      return api.get("/price_ticker")
        .then((result) => {
          commit('setNextTick', result.data.next_tick)
        })
    },
  },

  mutations: {
    /**
     * Call Dobby API für a list of user's gateways
     */
    setNextTick(state, nextTick) {
      state.nextTick = nextTick
    },

  },
}
