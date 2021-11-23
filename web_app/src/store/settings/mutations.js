/**
 * Mutations for Settings
 */
export function set(state, key, value) {

    //Vue.set(state, key, value)
    state = { ...state, key: value };

    // add setting to local storage
    localStorage.setItem('dobbySettings', JSON.stringify(state.settings))

}

/**
 * Set all settings at once
 * (used for retrieving settings from local storage)
 *
 * @param {*} state
 * @param {*} settings
 */
export function importAll(state, settings) {
    Vue.set(state, 'dobbySettings', settings)
}
