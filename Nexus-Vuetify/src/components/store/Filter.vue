<template>
    <div class="filter-container roundBorderSmall glass">
        <h3>Game Filters</h3>
        <div class="checkbox-group">
            <label class="filter-label glow" v-for="(genre, index) in genres" :key="index">
                <input type="checkbox" :name="genre.name" :value="genre.value"> <span>{{ genre.label }}</span>
            </label>
        </div>
        <!-- Search Bar moved here, under the filters -->
        <div class="search-container">
            <input type="text" id="gameSearch" name="gameSearch" placeholder="Chercher par filtre">
        </div>
    </div>
</template>
<script>
export default {
  data() {
    return {
      genres: [
        { name: 'action', value: 'action', label: 'Action' },
        { name: 'adventure', value: 'adventure', label: 'Adventure' },
        { name: 'rpg', value: 'rpg', label: 'RPG' },
        { name: 'simulation', value: 'simulation', label: 'Simulation' },
        { name: 'strategy', value: 'strategy', label: 'Strategy' },
        { name: 'sports', value: 'sports', label: 'Sports' }
      ]
    };
  }
};
</script>
<style scoped>
.filter-container {
    background: rgba(0, 0, 0, 0.8);
    color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
    margin: 2% auto;
    width: auto;
}

.filter-container h3 {
    color: #66c0f4;
    margin-bottom: 15px;
}

.checkbox-group {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 10px;
}

.filter-label {
    background: rgba(77, 77, 77, 0.8); 
    padding: 5px 10px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: start;
    position: relative;
    cursor: pointer;
    transition: background 0.3s ease;
}

.filter-label:hover {
    background: rgba(102, 192, 244, 0.2); 
}

.filter-label:before {
    content: '';
    width: 20px;
    height: 20px;
    margin-right: 10px;
    background-color: #ccc; 
    border-radius: 3px;
    position: relative;
    display: inline-block;
}

input[type="checkbox"]:checked + .filter-label {
    background-color: #0aade8;
    color: #13aebf; 
}

input[type="checkbox"]:checked + .filter-label:before {
    content: '\2713'; 
    color: #10cde2; 
    font-size: 14px; 
    text-align: center;
    line-height: 20px; 
}

input[type="checkbox"] {
    display: none;
}
.search-container {
    margin-top: 20px; /* Adjust space above search bar as needed */
}

#gameSearch {
    width: 100%;
    padding: 10px;
    border-radius: 4px;
    border: 2px solid #303c3f;
    background: rgba(4, 148, 251, 0.256);
    color: #ffffff;
}
#gameSearch::placeholder {
    color: #ffffff; /* Custom color for placeholder text */
    opacity: 1;
}
</style>
