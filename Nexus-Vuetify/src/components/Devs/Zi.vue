<template>
  <div class="zi">
    <div class="profile">
      <img src="/src/assets/Dev_Picture/Zi.jpg" alt="Zi">
      <div class="all-info">
        <a href="https://github.com/ZihengL" target="_blank" class="info-link">Github</a>
        <a href="liu.ziheng@yahoo.ca" target="_blank" class="info-link">Contact</a>
      </div>
    </div>
    <div class="info">
      <h1>ZI HENG LIU</h1>
      <h2>Développeur Back-end | Team lead</h2>
      <p>
        Ma passion pour la programmation se manifeste dans le souci du détail et une approche méthodique.
        Cet enthousiasme est alimenté par l'analyse approfondie des spécifications techniques,
        assurant une précision dans chaque aspect du développement. Je m'investis dans la création de solutions
        performantes et sécurisées, répondant avec justesse aux exigences fonctionnelles.
      </p>
      <p>
        Mon engagement pour la qualité se traduit par une attention rigoureuse lors de la revue de code et
        de la validation des processus, visant invariablement la fiabilité du produit final et la satisfaction des
        utilisateurs.
        C'est cette quête d'excellence technique qui anime ma passion pour la programmation.
      </p>

    </div>
  </div>
</template>

<style scoped>
.all-info {
  display: flex;
  height: 100%;
  gap: 20px;
  /* justify-content: center; */
  /* align-items: center; */
}

.zi {
  margin: 25px;
  display: flex;
  align-items: start;
  gap: 30px;
  padding: 20px;
  background-color: #7a2929fd;
  background-image: linear-gradient(135deg, #3a2c79a2 10%, #660d2071 60%);
  border-radius: 20px;
  border-width: 5px;
  padding-right: 40px;
}

.info-link {
  color: #c5c3c0;
  margin-top: 30px;
  text-decoration: none;
  font-weight: bold;
  position: relative;
  animation: slideInBottom 1.5s ease-out;
}

.info-link::after {
  content: '→';
  position: absolute;
  left: 0;
  top: 0;
  font-size: 16px;
  color: var(--light-gray);
  transform: scaleX(1);
  transform: scaleY(1);
}

.info-link:hover {
  text-decoration: underline;
}

/* .profile {
  flex-shrink: 0;
  animation: slideInBottom 1s ease-out;
} */

.profile {
  animation: fadeIn 0.2s ease-out;
  animation: slideInBottom 0.5s ease-out;

  img {
    object-fit: cover;
    min-width: 30rem;
    height: 100%;
    align-self: left;
    border-radius: 20px 4px 4px 4px;
    box-shadow: 0px 3px 0px #63615d,
      -10px 14px 10px rgba(0, 0, 0, 0.15),
      -10px 24px 2px rgba(0, 0, 0, 0.1),
      -10px 34px 30px rgba(0, 0, 0, 0.1);
  }

  a {
    font-size: 16px;
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
  }
}

.info {
  text-align: left;
  max-width: 100%;
  margin-top: 0%;
}

.info {
  margin-left: 10px;

  h1,
  h2 {
    color: #c5c3c0;
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    animation: fadeIn 0.2s ease-out;
    animation: slideInRight 0.5s ease-out;
  }

  h1 {
    font-size: 5em;
    margin-bottom: 0;
    text-shadow: 0px 3px 0px #63615d,
      -10px 14px 10px rgba(0, 0, 0, 0.15),
      -10px 24px 2px rgba(0, 0, 0, 0.1),
      -10px 34px 30px rgba(0, 0, 0, 0.1);
  }

  h2 {
    font-size: 1.5em;
    margin-bottom: 30px;
  }

  p {
    margin-left: 10px;
    color: #c5c3c0;
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    font-size: 1.25em;
    margin-bottom: 15px;
    animation: fadeIn 0.9s ease-in-out;
    animation: slideInRight 1s ease-in-out;
  }
}

/* .info p {
  margin-bottom: 15px;
  animation: fadeIn 0.5s ease-out;
  animation: slideInBottom2 0.5s ease-out;
  font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
}

.info h1 {
  color: #c5c3c0;
  font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
  font-size: 5em;
  text-shadow: 0px 3px 0px #63615d,
    -10px 14px 10px rgba(0, 0, 0, 0.15),
    -10px 24px 2px rgba(0, 0, 0, 0.1),
    -10px 34px 30px rgba(0, 0, 0, 0.1);
}

.info h2 {
  color: #c5c3c0;
  margin-left: 20px;
  font-size: 1.5em;
  margin-bottom: 3%;
}

.info p {
  font-size: 20px;
  margin-left: 20px;
  font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
  color: #c5c3c0;
} */

@keyframes slideInRight {
  from {
    transform: translateX(20%);
    opacity: 0;
  }

  to {
    transform: translateY(0);
    opacity: 1;
  }
}

@keyframes slideInBottom {
  from {
    transform: translateY(-20%);
    opacity: 0.5;
  }

  to {
    transform: translateY(0);
    opacity: 1;
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }

  to {
    opacity: 1;
  }
}
</style>

<script>
// Script content remains unchanged
</script>
