<template>
    <div class="filter-container roundBorderSmall glass">
        <h3>Game Filters</h3>
        <div class="checkbox-group">
            <label class="filter-label glow">
                <input type="checkbox" name="action" value="action"> Action
            </label>
            <label class="filter-label glow">
                <input type="checkbox" name="adventure" value="adventure"> Adventure
            </label>
            <label class="filter-label glow">
                <input type="checkbox" name="rpg" value="rpg"> RPG
            </label>
            <label class="filter-label glow">
                <input type="checkbox" name="simulation" value="simulation"> Simulation
            </label>
            <label class="filter-label glow">
                <input type="checkbox" name="strategy" value="strategy"> Strategy
            </label>
            <label class="filter-label glow">
                <input type="checkbox" name="sports" value="sports"> Sports
            </label>
        </div>
    </div>    
</template>

<style scoped>
.filter-container {
    width: 90%;
    background: #f9f9f9;
    padding: 20px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    margin: 10% auto 7% auto;
}

.filter-container h3 {
    margin-bottom: 10px;
    font-size: 18px;
    color: var(--light-trans-2);
}

.checkbox-group {
    display: flex;
    flex-direction: column;
}

.filter-label{
    display: flex;
    align-items: center;
    margin-bottom: 5px;
    font-size: 14px;
}

.filter-label glow input[type="checkbox"] {
    margin-right: 10px;
}

</style>
