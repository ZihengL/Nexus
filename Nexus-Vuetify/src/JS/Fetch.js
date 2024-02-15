// var tabCarousell = [];

// const account = {
//   id: this.id,
//   image: this.image,
//   title: this.title,
//   description: this.description,
// };
export function fetch(uri, jsonBody = null , method,) {
  fetch("https://localhost:4208/Nexus/backend/"+uri, {
    method: method,
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(jsonBody),
  })
    .then((response) => {
      if (response.status !== 200) {
        console.log("Error: Non-200 status code");
        return [];
      }
      // console.log(response.text())
      return response.json();
    })
    .then((data) => {
      // tabCarousell = this.processFetchedData(data)
      console.log("fetched data : ", data);
    })
    .catch((error) => console.log(error));

  //console.log(tabCarousell);
}
