<template>
    <div class="filter-container roundBorderSmall glass">
        <h3>Game Filters</h3>
        <div class="checkbox-group">
            <label class="filter-label glow">
                <input type="checkbox" name="action" value="action"> <span>Action</span>
            </label>
            <label class="filter-label glow">
                <input type="checkbox" name="adventure" value="adventure"> <span>Adventure</span>
            </label>
            <label class="filter-label glow">
                <input type="checkbox" name="rpg" value="rpg"> <span>RPG</span>
            </label>
            <label class="filter-label glow">
                <input type="checkbox" name="simulation" value="simulation"> <span>Simulation</span>
            </label>
            <label class="filter-label glow">
                <input type="checkbox" name="strategy" value="strategy"> <span>Strategy</span>
            </label>
            <label class="filter-label glow">
                <input type="checkbox" name="sports" value="sports"> <span>Sports</span>
            </label>
        </div>
    </div>    
</template>
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
</style>
