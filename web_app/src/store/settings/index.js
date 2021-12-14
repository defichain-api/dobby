import { LocalStorage } from 'quasar'

export default {
    namespaced: true,
    state: {
        settings: {
            darkMode: 'auto',
            language: 'en',
            privacy: false,
            numberFormats: {
                currency: { style: 'currency', currency: 'USD', minimumFractionDigits: 2, maximumFractionDigits: 2 },
            },
            triggerMultipleInfo: 1.5,
            triggerMultipleWarning: 1.25,
        }
    },
    getters: {
        value: (state) => (key) => {
            return state.settings[key]
        }
    },
    actions: {
        initFromLocalStorage ({ commit }) {
            let settings = LocalStorage.getItem(process.env.LOCAL_STORAGE_SETTINGS_KEY)

            // iterate over all settings keys found in local storage and store them in vuex
            if (settings && Object.keys(settings).length > 0) {
                Object.entries(settings).forEach((value) => {
                    commit('set', { key: value[0], value: value[1] })
                })
            }
        },
        set({ commit }, data) {
            commit('set', data)
        },
    },
    mutations: {
        set (state, data) {
            state.settings = { ...state.settings, [data.key]: data.value }
            // write setting to local storage
            LocalStorage.set(process.env.LOCAL_STORAGE_SETTINGS_KEY, state.settings)
        },
    },
}
