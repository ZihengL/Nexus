// all other params in data
export function fetchData2(table, action, data = null) {
  const uri = `http://localhost:4208/Nexus/Backend/table=${table}&action=${action}`;
  const options = {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  };

  return fetch(uri, options)
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

// URL COMPRESSION
// async function compressAndEncode(data) {
//   const jsonString = JSON.stringify(object);
//   const compressed = pako.deflate(jsonString, { to: "string" });
//   const encoded = encodeURIComponent(compressed); // Encode for URI compatibility

//   return encoded;

// const compressed = await new Promise((resolve) => {
//   const blob = new Blob([jsonData], { type: "application/json" });
//   const reader = new FileReader();

//   reader.onload = function (event) {
//     const compressedData = pako.deflate(
//       new TextEncoder().encode(event.target.result),
//       { to: "string" }
//     );

//     resolve(window.btoa(compressedData));
//   };

//   reader.readAsText(blob);
// });
// }

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
