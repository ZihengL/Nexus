<template>
   <div>
      <h2 class="title">Bienvenue chez Nexus, votre destination ultime pour les jeux vidéo !</h2>
      <p class="text">
         Chez Nexus, nous partageons votre passion pour le gaming. Notre plateforme est le paradis des joueurs,
         offrant une vaste sélection de jeux vidéo pour tous les goûts et tous les âges. Que vous soyez un fan de
         RPG immersifs, de jeux d'action palpitants, ou de simulations réalistes, Nexus vous propose une expérience
         d'achat en ligne fluide et agréable.
      </p>
   </div>
</template>

<style lang="scss" scoped>
div {
   width: 70%;
   margin: 1% 0% 2% 0%;
   padding: 1% 0% 3% 0%;
   /* From https://css.glass */
   background: rgba(255, 255, 255, 0.061);
   border-radius: 16px;
   backdrop-filter: blur(0.5px);
   -webkit-backdrop-filter: blur(0.5px);

   .title{
      margin-bottom: 1%;
      text-align: center;
      color: var(--purple);
   }
   .text{
      font-size: 150%; 
      margin-left: 3%;
      margin-right: 3%;
      text-align: center;
      color: var(--dark);
   }
}

</style>