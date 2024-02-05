<template>
    <div class="cartComponent">
      <div class="listJeu">
        <!-- Utilisation du composant avec des props -->
        <GameCart
          :image="'./src/assets/image/img1.png'"
          :name="'Palworld'"
          :price="'90$'"
          @remove-game="handleRemoveGame"
        />
        <GameCart
          :image="'./src/assets/image/img3.png'"
          :name="'Solo\'s'"
          :price="'100$'"
          @remove-game="handleRemoveGame"
        />
        <GameCart
          :image="'./src/assets/image/img4.png'"
          :name="'Teken'"
          :price="'60$'"
          @remove-game="handleRemoveGame"
        />
      </div>
      <div class="resume">
        <h1>truc</h1>
      </div>
    </div>
  </template>
  
  <script>
  import GameCart from './GameCart.vue'; // Assurez-vous de spécifier le bon chemin d'accès à votre composant
  
  export default {
    components: {
      GameCart,
    },
    methods: {
      // Logique pour gérer la suppression du jeu
      handleRemoveGame() {
        // Ajoutez ici la logique pour supprimer le jeu du panier
        console.log('Removing game from cart');
      },
    },
  };
  </script>
  
  <style>
    .cartComponent {
      display: flex;
      flex-direction: row;
      border: 2px solid rgb(255, 0, 0);
      padding: 2% 2%;
    }
    .cartComponent .listJeu {
      flex: 2.5;
      margin-right: 3%;
    }
    .cartComponent .resume {
      border: 2px solid black;
      flex: 1;
    }
  
  </style>