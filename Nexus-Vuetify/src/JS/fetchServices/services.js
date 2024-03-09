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
      credentials: {
        id: getFromUser("id"),
        tokens: getTokens(),
      },
    };
  }

  return null;
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
  const result = await fetch(uri(table, action), options(body))
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

  if (Object.prototype.hasOwnProperty.call(result, "ERROR")) {
    console.log(result);
    return false;
  } else {
    return result;
  }
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
    storeUser(result.user);
    storeTokens(result.tokens);

    return result.user;
  }

  return null;
}

export async function logout() {
  if (isLoggedIn()) {
    const result = await fetchData("users", "logout", getCredentials());

    if (result) {
      clearFromStorage();

      return result;
    }
  }

  return false;
}

export async function updateUser(data) {
  if (isLoggedIn()) {
    const result = await fetchData("users", "update", {
      credentials: getCredentials(),
      data,
    });

    console.log("UPDATE", result);
    if (result) {
      storeTokens(result.tokens);
    }
  }
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
