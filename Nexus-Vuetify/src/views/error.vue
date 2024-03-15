<template>
  <div id="failureContainer">
    <h1>Votre transaction n'a pas fonctionnée veuillez ressayer. :(</h1>
    <a href="/">Retourner à la Page Acceuil</a>
  </div>
</template>

<style scoped>
#failureContainer {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 100vh; /* Use the full height of the viewport */
  background-color: #000; 
  color: #FF4136; /* Using a red tone for the failure message */
  text-align: center;
  padding: 20px; /* Ensures text doesn't touch the edges */
}

#failureContainer h1 {
  margin: 20px; /* Spacing around the text */
  border: 2px solid #FF4136; /* Red border to emphasize failure */
  padding: 20px; /* Padding inside the border */
  border-radius: 10px; /* Rounded corners for the border */
}

#failureContainer a {
  color: #FF4136; /* Ensuring link matches the message color */
  text-decoration: none; /* Removing underline from link */
  border: 1px solid #FF4136; /* Border color to match text */
  padding: 10px 15px; /* Padding inside the link */
  border-radius: 5px; /* Rounded corners for the link */
  transition: background-color 0.3s, color 0.3s; /* Smooth transition for hover effects */
}

#failureContainer a:hover {
  background-color: #FF4136; /* Background becomes red */
  color: white; /* Text color becomes white for contrast */
}
</style>
