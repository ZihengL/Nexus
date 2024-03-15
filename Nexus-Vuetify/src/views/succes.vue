<template>
    <div id="confirmationContainer">
      <h1>Bravo t'as réussi ta transaction!!!!</h1>
      <a href="/">Retourner à la Page Acceuil</a>
    </div>
  </template>
  
  <style scoped>
  #confirmationContainer {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100vh; /* Use the full height of the viewport */
    /* background-color: #000;  */
    color: white; /* White text */
  }
  
  #confirmationContainer h1, #confirmationContainer a {
    text-shadow: -1px -1px 0 #fff;
    margin: 2%; 
  }
  
  #confirmationContainer a {
    color: white; /* Ensure link is white */
    text-decoration: none; /* Remove underline from link */
    border: 1px solid white; /* White border around the link */
    padding: 5px 10px; /* Padding inside the link border */
    border-radius: 5px; /* Optional: rounded corners for the link */
    transition: background-color 0.3s, color 0.3s; /* Smooth transition for hover effects */
  }
  
  #confirmationContainer a:hover {
    background-color: white; /* Background becomes white */
    color: black; /* Text color becomes black */
  }
  </style>
  