import StorageManager from "../localStorageManager";

/*******************************************************************/
/************************** LOCAL STORAGE **************************/
/*******************************************************************/

export function storeData(key, data) {
  localStorage.setItem(key, JSON.stringify(data));
}

export function getStoredData(key, field = null) {
  let item = localStorage.getItem(key);

  if (item !== null) {
    item = JSON.parse(item);

    return field !== null ? item[field] : item;
  }

  return null;
}

export function hasData(key) {
  return localStorage.getItem(key) !== null;
}

// User or tokens
export function clearFromStorage(key = null) {
  if (key) {
    localStorage.removeItem(key);
  } else {
    localStorage.clear();
  }
}

// USER OBJ INFOS -- todo: save password only if user chooses to

export function isLoggedIn() {
  return hasData("user");
}

export function storeUser(user) {
  storeData("user", user);
}

export function getUser(field = null) {
  if (isLoggedIn()) {
    return getStoredData("user", field);
  }

  return null;
}

export function getFromUser(field) {
  return getStoredData("user", field);
}

// TOKENS

export function storeTokens(tokens) {
  storeData("tokens", tokens);
}

export function getTokens() {
  return getStoredData("tokens");
}

export function getCredentials() {
  if (isLoggedIn() && hasData("tokens")) {
    return {
      id: getFromUser("id"),
      tokens: getTokens(),
    };
  }

  return null;
}

/*******************************************************************/
/****************************** FETCH ******************************/
/*******************************************************************/

// Local functions

// const standardizeData = (data) => {
//   const keymap = {
//     columnName: "column",
//     includedColumns: "included_columns",
//     joinedTables: "joined_tables",
//   };

//   Object.keys(keymap).forEach((key) => {
//     if (Object.prototype.hasOwnProperty.call(data, key)) {
//       const newKey = keymap[key];

//       data[newKey] = data[key];
//       delete data[key];
//     }
//   });

//   return data;
// };

const uri = (table, action) => {
  return `http://localhost:4208/Nexus/Backend/table=${table}&action=${action}`;
};

const options = (body = null) => {
  return {
    method: body ? "POST" : "GET",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(body) ?? null,
  };
};

// BASE FETCH

export async function fetchData(table, action, body = null) {
  const URI = uri(table, action);
  const OPTIONS = options(body);

  console.log("fetch uri", URI, "fetch options", OPTIONS);

  const result = await fetch(URI, OPTIONS)
    .then((response) => {
      // if (response.ok && response.status === 200) {
      if (response.ok) {
        return response.json();
      }

      console.error(`Error: ${response.status} ${response.statusText}`);
      return null;
    })
    .catch((error) => {
      console.error("Network error:", error);
      return null;
    });

  if (result) {
    if (Object.prototype.hasOwnProperty.call(result, "ERROR")) {
      console.log("ERROR", result);
    }

    return result;
  }

  return null;
}

export async function fetchCreate(table, data) {
  return await fetchData(table, "create", data);
}

export async function fetchUpdate(table, data) {
  return await fetchData(table, "update", data);
}

export async function fetchDelete(table, data) {
  return await fetchData(table, "delete", data);
}

/*******************************************************************/
/*************************** OTHER FETCH ***************************/
/*******************************************************************/

export const refreshInterval = 55 * 60 * 1000; // 55 mins
export const tokenRefreshInterval = setInterval(refreshToken, refreshInterval);

export async function refreshToken() {
  if (StorageManager.getIsConnected()) {
    console.log("Refreshing Token");

    const result = await fetchData(
      "users",
      "authenticate",
      getAuthentificationCredentials()
    );

    if (result) {
      console.log("Refreshed Access Token.", result);
      StorageManager.setAccessToken(result);
    } else {
      console.log("Failed to refresh Access Token.");
    }
  }
}

export function getValidationCredentials() {
  return {
    id: StorageManager.getIdDev(),
    access_token: StorageManager.getAccessToken(),
  };
}

export function getAuthentificationCredentials() {
  return {
    id: StorageManager.getIdDev(),
    refresh_token: StorageManager.getRefreshToken(),
  };
}

// USER OPERATIONS

export async function register(createData) {
  return await fetchData("users", "create", createData);
}

export async function login(email, password) {
  const result = await fetchData("users", "login", {
    email: email,
    password: password,
  });

  if (result) {
    storeUser(result.user);
    storeTokens(result.tokens);

    return result.user;
  }

  return null;
}

export async function logout() {
  if (isLoggedIn()) {
    const credentials = getCredentials();

    const result = await fetchData("users", "logout", {
      credentials: credentials,
      request_data: credentials,
    });

    if (result) {
      clearFromStorage();

      return result;
    }
  }

  return false;
}

export async function updateWithValidation(table, data) {
  if (isLoggedIn()) {
    return await fetchData(table, "update", {
      credentials: getValidationCredentials(),
      request_data: data,
    });
  }
}

export async function updateUser(data) {
  const result = await updateWithValidation("users", data);

  return result;
}

export async function updateGame(data) {
  const result = await updateWithValidation("games", data);

  return result;
}

// GENERICS

const filterNulls = (obj) => {
  return Object.entries(obj).reduce((filtered, [key, value]) => {
    if (value !== null) {
      filtered[key] = value;
    }
    return filtered;
  }, {});
};

export function prepGetOne(
  column,
  value,
  included_columns = null,
  joined_tables = null
) {
  return filterNulls({
    column: column,
    value: value,
    included_columns: included_columns ?? [],
    joined_tables: joined_tables ?? [],
  });
}

export function prepGetAll(
  column = null,
  value = null,
  included_columns = null,
  sorting = null,
  joined_tables = null,
  paging = null
) {
  return filterNulls({
    column: column,
    value: value,
    included_columns: included_columns ?? [],
    sorting: sorting ?? [],
    joined_tables: joined_tables ?? [],
    paging: paging ?? [],
  });
}

export function prepGetAllMatching(
  filters = null,
  sorting = null,
  included_columns = null,
  joined_tables = null,
  paging = null
) {
  const res = filterNulls({
    filters: filters,
    included_columns: included_columns ?? [],
    sorting: sorting ?? [],
    joined_tables: joined_tables ?? [],
    paging: paging ?? [],
  });

  return res;
}

export function getOne(table, preppedData) {
  return fetchData(table, "getOne", preppedData);
}

export function getAll(table, preppedData) {
  return fetchData(table, "getAll", preppedData);
}

export function getAllMatching(table, preppedData) {
  return fetchData(table, "getAllMatching", preppedData);
}

// OTHER CRUDS

/*******************************************************************/
/***************************** STRIPE ******************************/
/*******************************************************************/

export async function getDonationLink(developerID) {
  if (isLoggedIn()) {
    const credentials = getCredentials();

    return await fetchData("transactions", "getLink", {
      credentials: credentials,
      request_data: {
        donatorID: credentials.id,
        donateeID: developerID,
      },
    });
  }
}
