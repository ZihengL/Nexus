<template>
    <div id="search">
      <head>
        <!-- ... other links and scripts ... -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
      </head>
      <div class="search-container  roundBorderSmall glass">
        <input type="text" class="search-input" placeholder="Trouver un jeux...">
        <button class="search-button">
          <i class="fa-solid fa-magnifying-glass"></i> <!-- FontAwesome icon for search -->
        </button>
      </div>
    </div>
  </template>
  
  <style lang="scss" scoped>
  #search {
    .search-container {
    margin-top:2%;
    margin-left: 30%;
    position: relative;
    display: flex;
    justify-content: flex-end; 
    align-items: center;
    //background: #fdfdff; /* Light background */
    //border-radius: 5px;
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
    width: 100%; /* Full width to fill the container */
  }
  
  .search-button {
    position: absolute;
    right: 0; /* Align to the right */
    border: none;
    background: none;
    cursor: pointer;
    color: var(--light-trans-2);
   margin-right: 2%;
  }
  }

</style>  