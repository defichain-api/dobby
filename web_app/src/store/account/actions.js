import { LocalStorage } from 'quasar'
import { api } from 'boot/axios'

/**
 * Actions for Settings
 */

/**
 * Get data from dobbyAccount key in local storage and pass it to Vuex
 */
export function initFromLocalStorage({ commit }) {

    // get local storage key name from .env file
    const localStorageAccountKey = process.env.LOCAL_STORAGE_ACCOUNT_ID_KEY

    if (!LocalStorage.has(localStorageAccountKey) || LocalStorage.getItem(localStorageAccountKey).length == 0) {
        if (process.env.DEV) { console.log("[DEBUG] no dobby account key found in local storage, or key is empty") }
        return
    }

    commit('setUserId', LocalStorage.getItem(localStorageAccountKey))
}

// ----------------------------------------------------------------------------------

export function setUserId({ commit }, userId) {
    // store user id in vuex and local storage
    commit('setUserId', userId)

    // Remove all vaults from the vault list
    commit('clearVaultList')

    // set user id for api auth
    api.defaults.headers.common['x-user-auth'] = userId
}

// ----------------------------------------------------------------------------------

export function logout({ commit }) {
    // reset user id in vuex and local storage
    commit('setUserId', '')

    // reset api auth
    delete api.defaults.headers.common['x-user-auth']

    // reload page
    location.reload()
}

// ----------------------------------------------------------------------------------

export function loadUserData({ commit, dispatch }) {
    return api
        .get('/user')
        .then((response) => {
            response?.data?.vaults?.forEach((vault) => {
                if (process.env.DEV) { console.log("[DEBUG] adding vault to vuex store " + vault.vaultId) }
                commit('addVault', vault)
                // todo: add settings
            })
        })
        /*
        .catch((error) => {
            // whoops, error :(
        })
        */
}

// ----------------------------------------------------------------------------------

export function processLoanData({ state, commit }) {
    state.vaults.forEach((vault) => {
        /**
         * grab loans from all vaults and store them to vuex to be able to list all
         * loans, independend from vaults
         */
    })
}

// ----------------------------------------------------------------------------------

export function addVault({ commit }, vaultData) {
    commit('addVault', vaultData)
}

// ----------------------------------------------------------------------------------

export function clearVaultList({ commit }) {
    commit('clearVaultList')
}
