// var tabCarousell = [];

// const account = {
//   id: this.id,
//   image: this.image,
//   title: this.title,
//   description: this.description,
// };
//onDataReceived=null
export function fetchData(uri, jsonBody = null, method = "GET") {
    const fetchOptions = {
      method: method,
      headers: {
        "Content-Type": "application/json",
      },
    };
  
    // Only add the body property for methods that send data
    if (method === "POST" && jsonBody) {
      fetchOptions.body = JSON.stringify(jsonBody);
    }
    console.log(`fetchOptions`, fetchOptions);
    console.log(uri)
    return fetch("http://localhost:4208/Nexus/Backend/" + uri, fetchOptions)
      .then((response) => {
        if (!response.ok) {
          // A more generic way to catch all HTTP errors
        //   console.log(`Error: Non-200 status code (${response.status})`);
          return Promise.reject(response);
        }
        // console.log("response",response.json)
        return response.json();
      })
      .then((data) => {
        // Handle the JSON data here
        console.log(data);
        return data; // Return the data for further processing by the caller
      })
      .catch((error) => {
        console.log('Fetch error:', error);
        // It might be beneficial to re-throw the error after logging so that the calling code can handle it
        throw error;
      });
  }
  /*
  
  */
  
  