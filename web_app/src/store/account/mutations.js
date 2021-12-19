import { LocalStorage } from 'quasar'

/**
 * Mutations for Accounts
 */

/**
 * Set a dobby user id
 *
 * @param {object} state
 * @param {string} userId
 */
export function setUserId(state, userId) {
    state.userId = userId

    LocalStorage.set(process.env.LOCAL_STORAGE_ACCOUNT_ID_KEY, userId)
}

// ----------------------------------------------------------------------------------

/**
 * Empty the vault list
 *
 * @param {object} state
 */
export function clearVaultList(state) {
    state.vaults = []
}

// ----------------------------------------------------------------------------------

/**
 * Add another vault to the vault list
 *
 * @param {object} state
 * @param {object} vaultData
 */
export function addVault(state, vaultData) {
    const index = state.vaults.findIndex((vault) => vault.vaultId == vaultData.vaultId)

    if (index > -1) {
        state.vaults[index] = { ...state.vaults[index], ...vaultData }
        if (process.env.DEV) { console.log("[DEBUG] ... successfully updated " + vaultData.vaultId) }
    } else {
        state.vaults = [...state.vaults, vaultData]
        if (process.env.DEV) { console.log("[DEBUG] ... successfully added " + vaultData.vaultId) }
    }
}

// ----------------------------------------------------------------------------------

/**
 * Add a loan to the loan list in state
 * @param {object} state
 * @param {object} loanData
 */
export function addLoan(state, loanData) {
    if (state.loans.length == 0) {
        // vault list is empty
        state.loans = [...state.loans, loanData]
        if (process.env.DEV) { console.log("[DEBUG] ... successfully added " + loanData.vaultId) }
    } else {
        /*
        // check if this vault already exists, update data if already existing
        state.vaults.forEach((vault, index) => {
            if (vault.vaultId == vaultData.vaultId) {
                // Update existing vault
                state.vaults[index] = { ...state.vaults[index], vaultData }
                if (process.env.DEV) { console.log("[DEBUG] ... successfully updated " + vaultData.vaultId) }
                return
            }
        })

        // Add new vault entry
        state.vaults = [...state.vaults, vaultData]
        if (process.env.DEV) { console.log("[DEBUG] ... successfully added " + vaultData.vaultId) }
        */
    }
}

// ----------------------------------------------------------------------------------
