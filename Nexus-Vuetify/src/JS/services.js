// import { fetchData2 } from "./fetch2";

/*******************************************************************/
/************************** USER STORAGE ***************************/
/*******************************************************************/

export const storeData = (key, data) => {
  localStorage.setItem(key, JSON.stringify(data));
};

export const getStoredData = (key, field = null) => {
  if (localStorage.getItem(key)) {
    const data = JSON.parse(localStorage.getItem(key));

    return field ? data[field] : data;
  }

  return null;
};

export const clearFromStorage = (key = null) => {
  if (key) {
    localStorage.removeItem(key);
  } else {
    localStorage.clear();
  }
};

// user

export const isLoggedIn = () => {
  return localStorage.getItem("user") !== null;
};

export const storeUser = (user) => {
  storeData("user", user);
};

export const getStoredUser = () => {
  return getStoredData("user");
};

export const getFromUser = (field) => {
  return getStoredData("user", field);
};

// tokens

export const storeTokens = (tokens) => {
  storeData("tokens", tokens);
};

export const getStoredTokens = () => {
  return getStoredData("tokens");
};

/*******************************************************************/
/***************************** USER DB *****************************/
/*******************************************************************/

export const register = (createData) => {
  return fetch("users", "create", createData);
};

export const login = (email, password) => {
  const result = fetch("users", "login", {
    email: email,
    password: password,
  });

  if (result) {
    // const [user, tokens] = result;
    console.log(result);
    
    // storeUser(user);
    // storeTokens(tokens);

    // return user;
  }

  return null;
};

export const logout = () => {
  const body = {
    id: getFromUser("id"),
    tokens: getStoredTokens(),
  };

  if (fetch("users", "logout", body)) {
    clearFromStorage();
    return true;
  }

  return false;
};

/*******************************************************************/
/***************************** GETTERS *****************************/
/*******************************************************************/

export const getOne = (table, data) => {
  return fetch(table, "getOne", data);
};

export const getAll = (table, data) => {
  return fetch(table, "getAll", data);
};

export const getAllMatching = (table, data) => {
  return fetch(table, "getAllMatching", data);
};
