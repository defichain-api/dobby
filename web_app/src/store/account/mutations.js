import { LocalStorage } from 'quasar'

/**
 * Mutations for Accounts
 */

/**
 * Set a dobby user id
 *
 * @param {*} state
 * @param {*} userId
 */
export function setUserId(state, userId) {
    state.userId = userId

    LocalStorage.set(process.env.LOCAL_STORAGE_ACCOUNT_ID_KEY, userId)
}

/*
export function addVault(state, vaultData) {
    state.vaults.push(vaultData)
}
*/
