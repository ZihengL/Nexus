
const StorageKeys = {
  ID_DEV: 'idDev',
  ACCESS_TOKEN: 'access_token',
  REFRESH_TOKEN: 'refresh_Token',
  IS_CONNECTED: 'isConnected'
}

const StorageManager = {
  setIdDev (id) {
    localStorage.setItem(StorageKeys.ID_DEV, id)
  },
  getIdDev () {
    return localStorage.getItem(StorageKeys.ID_DEV)
  },
  setAccessToken (token) {
    localStorage.setItem(StorageKeys.ACCESS_TOKEN, token)
  },
  getAccessToken () {
    return localStorage.getItem(StorageKeys.ACCESS_TOKEN)
  },
  setRefreshToken (token) {
    localStorage.setItem(StorageKeys.REFRESH_TOKEN, token)
  },
  getRefreshToken () {
    return localStorage.getItem(StorageKeys.REFRESH_TOKEN)
  },
  getTokens() {
    return {
      access_token: this.getAccessToken(),
      refresh_token: this.getRefreshToken()
    }
  },
  setIsConnected (isConnected) {
    localStorage.setItem(
      StorageKeys.IS_CONNECTED,
      isConnected ? 'true' : 'false'
    )
  },
  getIsConnected () {
    const isConnected = localStorage.getItem(StorageKeys.IS_CONNECTED)
    return isConnected === 'true'
  },
  clearIdDev () {
    localStorage.removeItem(StorageKeys.ID_DEV)
  },
  clearAccessToken () {
    localStorage.removeItem(StorageKeys.ACCESS_TOKEN)
  },
  clearRefreshToken () {
    localStorage.removeItem(StorageKeys.REFRESH_TOKEN)
  },
  clearIsConnected () {
    localStorage.removeItem(StorageKeys.IS_CONNECTED)
  }
}

export default StorageManager
