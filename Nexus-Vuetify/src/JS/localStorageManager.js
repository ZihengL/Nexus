
const StorageKeys = {
  USER: 'user',
  ID_DEV: 'idDev',
  ACCESS_TOKEN: 'access_token',
  REFRESH_TOKEN: 'refresh_Token',
  IS_CONNECTED: 'isConnected'
}

const StorageManager = {
  setUser(user) {
    localStorage.setItem(StorageKeys.USER, JSON.stringify(user));
  },
  getUser() {
    try {
      const user = JSON.parse(localStorage.getItem(StorageKeys.USER) || '{}');
      console.log("STORED USER", user);

      return user;
    } catch (e) {
        console.error("Error parsing JSON from localStorage", e);
        return null;
    }
  },
  setIdDev (id) {
    // console.log('set id d ', id);
    localStorage.setItem(StorageKeys.ID_DEV, id)
  },
  getIdDev () {
    let data = localStorage.getItem(StorageKeys.ID_DEV);
    // console.log('get id d ', data);
    return parseInt(data);
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
    console.log('isConnected', isConnected);
    localStorage.setItem(
      StorageKeys.IS_CONNECTED,
      isConnected ? 'true' : 'false'
    )
  },
  getIsConnected () {
    const isConnected = localStorage.getItem(StorageKeys.IS_CONNECTED)
    console.log('isConnected', isConnected);
    return isConnected === 'true';
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
  },
  clearAll() {
    this.clearIdDev();
    this.clearAccessToken();
    this.clearRefreshToken();
  },
}

export default StorageManager
