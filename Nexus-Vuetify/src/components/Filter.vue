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
    width: 90%;
    background: #0698e1;
    padding: 20px;
    box-shadow: 0 2px 5px rgba(227, 224, 224, 0.707);
    margin: 10% auto 7% auto;
}

.filter-container h3 {
    margin-bottom: 10px;
    font-size: 30px;
    color: var(--light-trans-2);
}

.checkbox-group {
    display: flex;
    flex-direction: column;
}

.filter-label {
    display: flex;
    align-items: center;
    margin-bottom: 5px;
}

.filter-label span {
    margin-left: 5px;
    font-size: 20px; 
}

input[type="checkbox"] {
    display: none; 
}

.filter-label:before {
    content: '';
    display: inline-block;
    width: 25px;
    height: 25px; 
    margin-right: 10px;
    background-color: transparent; 
    border: 2px solid #ffffff75; 
    cursor: pointer;
}

input[type="checkbox"]:checked + span:before {
    background-color: #0096FF; 
}

.filter-label:hover:before {
    border-color: #0096FF; 
}

.filter-label input[type="checkbox"]:checked + span:before {
    content: "\\2713"; 
    color: #fff; 
    font-size: 20px; 
    text-align: center;
    line-height: 21px; 
}
</style>
