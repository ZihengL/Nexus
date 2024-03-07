/*******************************************************************/
/************************** LOCAL STORAGE **************************/
/*******************************************************************/

export function storeData(key, data) {
  localStorage.setItem(key, JSON.stringify(data));
}

export function getStoredData(key, field = null) {
  if (localStorage.getItem(key)) {
    const data = JSON.parse(localStorage.getItem(key));

    return field ? data[field] : data;
  }

  return null;
}

export function clearFromStorage(key = null) {
  if (key) {
    localStorage.removeItem(key);
  } else {
    localStorage.clear();
  }
}

// USER OBJ INFOS -- todo: save password only if user chooses to

export function isLoggedIn() {
  return localStorage.getItem("user") !== null;
}

export function storeUser(user) {
  storeData("user", user);
}

export function getStoredUser() {
  return getStoredData("user");
}

export function getFromUser(field) {
  return getStoredData("user", field);
}

// USER TOKENS

export function storeTokens(tokens) {
  storeData("tokens", tokens);
}

export function getStoredTokens() {
  return getStoredData("tokens");
}

/*******************************************************************/
/****************************** FETCH ******************************/
/*******************************************************************/

// Local functions

const standardizeData = (data) => {
  const keymap = {
    columnName: "column",
    includedColumns: "included_columns",
    joinedTables: "joined_tables",
  };

  Object.keys(keymap).forEach((key) => {
    if (Object.prototype.hasOwnProperty.call(data, key)) {
      const newKey = keymap[key];

      data[newKey] = data[key];
      delete data[key];
    }
  });

  return data;
};

const uri = (table, action) => {
  return `http://localhost:4208/Nexus/Backend/table=${table}&action=${action}`;
};

const options = (body = null) => {
  return {
    method: body ? "POST" : "GET",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(standardizeData(body)) ?? null,
  };
};

// BASE FETCH

export async function fetchData(table, action, body = null) {
  return fetch(uri(table, action), options(body))
    .then((response) => {
      if (response.ok && response.status === 200) {
        return response.json();
      }

      console.error(`Error: ${response.status} ${response.statusText}`);
      return null;
    })
    .catch((error) => {
      console.error("Network error:", error);
      return null;
    });
}

/*******************************************************************/
/*************************** OTHER FETCH ***************************/
/*******************************************************************/

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
    // console.log("result.user", result.user);
    // console.log(result.tokens);

    storeUser(result.user);
    storeTokens(result.tokens);

    return result.user;
  }

  return null;
}

export async function logout() {
  const result = await fetchData("users", "logout", {
    id: getFromUser("id"),
    tokens: getStoredTokens(),
  });

  if (result) {
    clearFromStorage();
    return true;
  }

  return false;
}

// GENERICS

export function getOne(table, data) {
  return fetchData(table, "getOne", data);
}

export function getAll(table, data) {
  return fetchData(table, "getAll", data);
}

export function getAllMatching(table, data) {
  return fetchData(table, "getAllMatching", data);
}
