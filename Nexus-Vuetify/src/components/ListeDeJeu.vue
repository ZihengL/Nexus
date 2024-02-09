<template>

  <!-- <div v-for="activity in activities" :key="activity.id" class="activities">-->
  <div class="container">
    <img alt="image jeu" class="img">
    <div class="jeu">
      <span>Joué à Super Mario Bros le 17/04/2022</span><br />
      <button class="btn">Voir Plus</button>
    </div>
  </div>
</template>

<style lang="scss">
.container {
  display: flex;
  justify-content: space-between;
  border: 1px solid black;
  padding: 20px;
  border-radius: 30px;
  box-shadow: 10px 10px 10px black;
  margin-top: 30px;
}

.img {
  align-self: flex-start;
}

.jeu {
  margin-left: 40px;
  align-self: flex-end;
}

.btn {
  background-color: blue;
  padding: 10px;
  border-radius: 30px;
}
</style>
