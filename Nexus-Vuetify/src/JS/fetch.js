export function fetchData (
  table,
  crud_action,
  columnName = null,
  value = null,
  jsonBody = null,
  method
) {
  const baseURL = 'http://localhost:4208/Nexus/Backend/'
  //console.log({table, crud_action, columnName, value});
  // let uri = `${baseURL}${table}/${crud_action}/${columName}/${value}`;
  let uri = `${baseURL}${table}/${crud_action}`
  if (columnName && value) {
    uri += `/${columnName}/${value}`
  }
  const fetchOptions = {
    method: method,
    headers: {
      'Content-Type': 'application/json'
    }
  }

  if ((method === 'POST' || method === 'PUT') && jsonBody) { 
    // console.log("hi");
    fetchOptions.body = JSON.stringify(jsonBody)
  }
  //console.log(`Fetching: ${uri} with options:`, fetchOptions)

  // Use the 'uri' variable directly instead of concatenating the baseURL and uri again
  return fetch(uri, fetchOptions)
    .then(response => {
      if (!response.ok) {
        return Promise.reject(response)
      }
      // console.log(' response : ', response.text())
      return response.json();
    })
    .then(data => {
      return data;
      //console.log(' data : ', data)
    })
    .catch(error => {
      console.log('Fetch error:', error)
      throw error
    })
}
