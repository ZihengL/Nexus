<template>
    <div class="search-container">
        <head>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        </head>
        <input type="text" class="search-input" placeholder="Search games...">
        <button class="search-button">
            <i class="fa fa-search"></i> <!-- FontAwesome icon for search -->
        </button>
    </div>   
</template>
 <style scoped>
 .search-container {
    max-width: 500px;
    margin: 0 auto;
    position: relative;
    display: flex;
    align-items: center;
    background: #fdfdff; /* Assuming dark blue background */
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.search-input {
    flex-grow: 1;
    padding: 10px 40px 10px 10px; /* Adjust right padding for the icon */
    font-size: 16px;
    border: none;
    outline: none;
    color: #000000; /* White text */
    background: transparent; /* Inherit from container */
    border-radius: 5px;
}

.search-button {
    position: absolute;
    right: 0; /* Align to the right */
    border: none;
    background: none;
    cursor: pointer;
    color: rgb(0, 0, 0);
    font-size: 16px; 
    border-radius: 0 5px 5px 0; 
    width: 40px;
    height: 40px;
}

.fa-search {
    color: black; 
    font-size: 20px; 

}


</style>