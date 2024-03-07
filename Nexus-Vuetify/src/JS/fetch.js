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
  const baseURL = 'http://localhost:4208/Nexus/Backend/';
  let uri = `${baseURL}${table}/${crud_action}`;

  const queryParams = [];
  
  // Check and append columnName and value
  if (columnName && value !== null) {
    uri += `/${columnName}/${value}`;
    // console.log("value fetch : ", value);
  }
  
  // Handle includedColumns
  if (includedColumns && includedColumns.length) {
    queryParams.push(`includedColumns=${includedColumns.join(',')}`);
  }

  if (sorting && Object.keys(sorting).length) {
    // console.log("sorting fetch : ", sorting);
    const sortingParams = Object.entries(sorting).map(([key, value]) => `${key}:${value}`).join(',');
    queryParams.push(`sorting=${encodeURIComponent(sortingParams)}`);
  }
  
  // Append query parameters to URI
  if (queryParams.length > 0) {
    uri += `?${queryParams.join('&')}`;
  }

  const fetchOptions = {
    method: method,
    headers: {
      'Content-Type': 'application/json'
    }
  };

  if ((method === 'POST' || method === 'PUT' || method === 'DELETE') && jsonBody) {
    fetchOptions.body = JSON.stringify(jsonBody);
  }
  //  console.log(`Fetching: ${uri} with options:`, fetchOptions, "query params ", queryParams);

  return fetch(uri, fetchOptions)
    .then(response => {
      if (!response.ok) { 
        return Promise.reject(response);
      }
      //  console.log(" response : ", response.text());

      return response.json();
    })
    .then(data => {
      return data;
    })
    .catch(error => {
      console.log('Fetch error:', error);
      throw error;
    });
}