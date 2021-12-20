import { store } from 'quasar/wrappers'
import { createStore } from 'vuex'

import account from './account'
import settings from './settings'
import notifications from './notifications'

/*
 * If not building with SSR mode, you can
 * directly export the Store instantiation;
 *
 * The function below can be async too; either use
 * async/await or return a Promise which resolves
 * with the Store instance.
 */

export default store(function (/* { ssrContext } */) {
  const Store = createStore({
    modules: {
      account,
      settings,
      notifications,
    },
    state: {
      headline: {
        text: 'Dobby',
        icon: 'fad fa-socks',
      },
      requestCount: 0,
      noApiResponse: false,
      requestIdentifiers: [],
    },
    getters: {
      headline(store) {
        return store.headline
      },
      requestRunning: (state) => {
        return state.requestCount > 0;
      },
      noApiResponse: (state) => {
        return state.noApiResponse;
      },
    },
    actions: {
      setHeadline({ commit }, headline) {
        commit('setHeadline', headline)
      },
    },
    mutations: {
      setHeadline(store, headline) {
        store.headline = headline
      },
      requestStart(state, identifier) {
        state.requestCount++
        if (process.env.DEV) { console.log("[DEBUG] Starting API request on " + identifier) }
      },
      requestDone(state, identifier) {
        state.requestCount--
        if (process.env.DEV) { console.log("[DEBUG] API request done on " + identifier) }
      },
      noApiResponse(state) {
        state.noApiResponse = true;
      },
      apiResponded(state) {
        state.noApiResponse = false;
      },
    },

    // enable strict mode (adds overhead!)
    // for dev mode and --debug builds only
    strict: process.env.DEBUGGING
  })

  return Store
})
