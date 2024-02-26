export function fetchData (
  table,
  crud_action,
  columnName = null,
  value = null,
  includedColumns = null,
  sorting = null,
  jsonBody = null,
  method
) {
  const baseURL = 'http://localhost:4208/Nexus/Backend/'
  console.log({ table, crud_action, columnName, value })

  let uri = `${baseURL}${table}/${crud_action}`
  // Convert arrays to comma-separated strings if they are not empty


  // Append to URI only if values are present
  if (columnName && value !== null) {
    uri += `/${columnName}/${value}`
  }
  if (includedColumns && includedColumns.length) {
    uri += `?includedColumns=${includedColumns.join(',')}`;
  }
  if (sorting && sorting.length) {
    // Append sorting as a query parameter, or adjust to match your server's expected format
    uri += `&sorting=${sorting.join(',')}`;
  }
  const fetchOptions = {
    method: method,
    headers: {
      'Content-Type': 'application/json'
    }
  }

  if ((method === 'POST' || method === 'PUT' || method === 'DELETE') && jsonBody) {
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
      return response.json()
    })
    .then(data => {
      // console.log(data)
      return data
    })
    .catch(error => {
      console.log('Fetch error:', error)
      throw error
    })
}
