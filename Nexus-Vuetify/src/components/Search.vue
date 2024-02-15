<template>
    <head>
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    </head>
    <div class="search-container">
      <input type="text" class="search-input" placeholder="Trouver un jeux...">
      <button class="search-button">
        <i class="fa-solid fa-magnifying-glass"></i> 
      </button>
    </div>
  </template>
  
  <style scoped>
  .search-container {
    margin-top:2%;
    margin-left: 30%;
    position: relative;
    display: flex;
    justify-content: flex-end; 
    align-items: center;
    background: #fdfdff; 
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    height: 40px; 
  }
  
  .search-input {
    padding: 10px 10px 10px 10px; /* Uniform padding */
    font-size: 16px;
    border: none;
    outline: none;
    color: #000000; /* Black text */
    background: transparent; /* Inherit from container */
    border-radius: 5px;
    width: 100%; /* Full width to fill the container */
  }
  
  .search-button {
    position: absolute;
    right: 0; /* Align to the right */
    border: none;
    background: none;
    cursor: pointer;
    color: black;
   
  }
</style>  