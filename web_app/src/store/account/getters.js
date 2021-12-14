/**
 * Getters for account
 */

export function userId(state) {
    return state.userId
}

export function vaults(state) {
    return state.vaults
}

export function isDemo(state) {
    return process.env.DEMO_ACCOUNT_ID == state.userId
}
