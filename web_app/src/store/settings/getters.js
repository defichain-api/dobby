/**
 * Getters for Settings
 */

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
