/**
 * Actions for Settings
 */

/**
 * Get all settings from local storage and pass them to Vuex
 */
export function initFromLocalStorage ({ commit }) {
    /*
    let settings = JSON.parse(localStorage.getItem('settings'))

    if (!settings) {
        settings = {
            language: 'en-us'
        }
        localStorage.setItem('settings', JSON.stringify(settings))
    }

    if (settings && Object.keys(settings).length > 0) {
        commit('IMPORT_ALL', settings)
    }
    */
}

/**
 * Save a setting
 *
 * @param {*} commit
 * @param {*} key
 * @param {*} value
 */
export function set({ commit }, key, value) {
    commit('SET', key, value)
}
