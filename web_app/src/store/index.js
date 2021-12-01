import { store } from 'quasar/wrappers'
import { createStore } from 'vuex'

import account from './account'
import settings from './settings'

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
      settings
    },
    state: {
      headline: {
        text: 'D.O.B.B.Y.',
        icon: 'fad fa-socks',
      },
    },
    getters: {
      headline(store) {
        return store.headline
      }
    },
    actions: {
      setHeadline({ commit }, headline) {
        commit('setHeadline', headline)
      }
    },
    mutations: {
      setHeadline(store, headline) {
        store.headline = headline
      }
    },

    // enable strict mode (adds overhead!)
    // for dev mode and --debug builds only
    strict: process.env.DEBUGGING
  })

  return Store
})
