/**
 * Mutations for Settings
 */
export function SET(state, key, value) {

    //Vue.set(state, key, value)
    state = { ...state, key: value };

    // add setting to local storage
    localStorage.setItem('settings', JSON.stringify(state.settings))

}

/**
 * Set all settings at once
 * (used for retrieving settings from local storage)
 *
 * @param {*} state
 * @param {*} settings
 */
export function IMPORT_ALL(state, settings) {
    Vue.set(state, 'settings', settings)
}
