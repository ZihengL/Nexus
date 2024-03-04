/* GLOBAL FETCH (reworked) */
function getMethodByAction(action) {
  const get = ["getOne", "getAll", "getAllMatching"];
}

export function fetchData(action = "getOne", data = null) {
  const options = {
    method: getMethodByAction,
    headers: {
      "Content-Type": "application/json",
    },
    body: data,
  };

  const baseURL = "http://localhost:4208/Nexus/Backend/";

  return fetch(url, options)
    .then((response) => {
      if (!response.ok) {
        console.error(`Error: ${response.status} ${response.statusText}`);
        return null;
      }
      if (response.status !== 200) {
        console.error(
          `Error: Non-200 status code(${response.status}), ${response.statusText}`
        );
        return [];
      }

      return response.json();
    })
    .catch((error) => {
      console.error("Network error:", error);
      return null;
    });
}

// app.config.globalProperties.$fetchData = function (url, method = 'GET', data = null) {
//   const options = {
//     method,
//     headers: {
//       'Content-Type': 'application/json',
//     },

//     body: method === 'POST' ? JSON.stringify(data) : null,
//   };

//   return fetch(url, options)
//     .then(response => {
//       if (!response.ok) {
//         console.error(`Error: ${response.status} ${response.statusText}`);
//         return null;
//       }
//       if (response.status !== 200) {
//         console.error(`Error: Non-200 status code(${response.status}), ${response.statusText}`);
//         return [];
//       }

//       return response.json();
//     })
//     .catch(error => {
//       console.error('Network error:', error);
//       return null;
//     });
// };
