import { LocalStorage } from 'quasar'
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
        //LocalStorage.set(process.env.LOCAL_STORAGE_ACCOUNT_ID_KEY, 'demo-demo-demo-demo-demodemodemo')
        return
    }

    commit('setUserId', LocalStorage.getItem(localStorageAccountKey))
}
