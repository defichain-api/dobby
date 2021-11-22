/**
 * Getters for Settings
 */

export function vaults(state) {
    return state.vaults
}
/**
 * Get a setting value
 *
 * @param {*} state
 * @param {*} key
 * @returns mixed
 */
export function value(state, key) {
    return (key) => {
        return state[key]
    }
}
