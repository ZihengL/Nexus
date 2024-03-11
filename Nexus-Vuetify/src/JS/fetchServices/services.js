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

const parseResponse = (result) => {
  if (Object.prototype.hasOwnProperty.call(result, "ERROR")) {
    console.log("ERROR", result);
    return result;
  }

  return true;
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

export async function updateUser(data) {
  if (isLoggedIn()) {
    const result = await fetchData("users", "update", {
      credentials: getCredentials(),
      request_data: data,
    });

    if (result) {
      storeTokens(result.tokens);
    }
  }
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

export function prepGetOne(column, value, included_columns = null, joined_tables = null) {
  return filterNulls({
    column: column,
    value: value,
    included_columns: included_columns,
    joined_tables: joined_tables
  });
}

export function prepGetAll(column = null, value = null, included_columns = null, sorting = null, joined_tables = null, paging = null) {
  return filterNulls({
    column: column,
    value: value,
    included_columns: included_columns,
    sorting: sorting,
    joined_tables: joined_tables,
    paging: paging
  });
}

export function prepGetAllMatching(filters = null, sorting = null, included_columns = null, joined_tables = null, paging = null) {
  const res = filterNulls({
    filters: filters,
    included_columns: included_columns,
    sorting: sorting,
    joined_tables: joined_tables,
    paging: paging
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
      }
    });
  }
}
