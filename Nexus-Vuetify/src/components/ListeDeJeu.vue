<template>

  <!-- <div v-for="activity in activities" :key="activity.id" class="activities">-->
  <div class="container glass3 glow  roundBorderSmall">
    <div class="img">
      <img src="../assets//img/apex.png" alt="image jeu" class=" roundBorderSmall">
    </div>
    <div class="jeu">
      <span>Joué à Super Mario Bros le 17/04/2022</span><br />
      <div class="fieldBtn">
        <div class="btn-layer"></div>
        <v-btn to="/Game"  density="default" class="submit glow">
          Voir plus
        </v-btn>
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.container {
  display: flex;
  justify-content: space-between;
  border: 1px solid rgba(0, 0, 0, 0.132);
  padding: 20px;
  margin-top: 1.5%;
  gap: 40%;
}

.img {
  flex: 3;
  align-self: flex-start;
  width: 10%;
  img {
    width: 100%;
    //display: none;
    //width: 1rem;
  }
}

.jeu {
  flex: 4;
  margin-left: 40px;
  align-self: flex-end;
  justify-content: space-around;
}
</style>
