<template>
  <div class="listeJeux roundBorderAll">
    <img alt="image jeu">
    <div>
      <span>Joué à Super Mario Bros le 17/04/2022</span><br />
      <button class="roundBorderAll">Voir Plus</button>
    </div>
  </div>
</template>

<style lang="scss">
  .listeJeux {
    display: flex;
    justify-content:space-between;
    padding: 20px;
    box-shadow: 10px 10px 30px black;
    margin-top: 30px;

    img {
      align-self: flex-start;
    }
    div {
      margin-left: 40px;
      align-self: flex-end;

      button {
        background-color: blue;
        padding: 10px;
      }
    }
  }
</style>
