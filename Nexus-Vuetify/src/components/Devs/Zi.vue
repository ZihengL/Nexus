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
      <h2>Développeur Back-end  |  Team lead</h2>
      <p>
        Ma passion pour la programmation se manifeste dans le souci du détail et une approche méthodique. 
        Cet enthousiasme est alimenté par l'analyse approfondie des spécifications techniques, 
        assurant une précision dans chaque aspect du développement. Je m'investis dans la création de solutions 
        performantes et sécurisées, répondant avec justesse aux exigences fonctionnelles.
      </p>
      <p>
        Mon engagement pour la qualité se traduit par une attention rigoureuse lors de la revue de code et 
        de la validation des processus, visant invariablement la fiabilité du produit final et la satisfaction des utilisateurs. 
        C'est cette quête d'excellence technique qui anime ma passion pour la programmation.
      </p>

    </div>
  </div>
</template>

<style>
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
  align-items: center;
  gap: 30px;
  padding: 20px;
  background-color: #7a2929fd;
  background-image: linear-gradient(135deg, #3a2c79a2 10%, #660d2071 60%);
  border-radius: 20px;
  border-width: 5px;
  padding-right: 40px;
}

.info-link {
  color: var(--light-gray);
  text-decoration: none;
  font-weight: bold;
  position: relative;
  padding-left: 20px;
  animation: slideInRight 2s ease-out;
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

.profile {
  flex-shrink: 0;
  animation: slideInLeft 1.5s ease-out;
}

.profile {
  img {
    object-fit: cover;
    min-width: 30rem;
    height: 80%;
    align-self: left;
    border-radius: 20px 4px 4px 4px;
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

.info h1,
.info h2,
.info p {
  margin-bottom: 15px;
  animation: fadeIn 1.5s ease-out;
  animation: slideInLeft 1.5s ease-out;
  font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
}

.info h1 {
  color: var(--light-gray);
  font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
  font-size: 5em;
  text-shadow: 0px 3px 0px #63615d,
    -10px 14px 10px rgba(0, 0, 0, 0.15),
    -10px 24px 2px rgba(0, 0, 0, 0.1),
    -10px 34px 30px rgba(0, 0, 0, 0.1);
  
}

.info h2 {
  color: var(--light-gray);
  margin-left: 20px;
  font-size: 1.5em;
  margin-bottom: 3%;
}

.info p {
  font-size: 20px;
  margin-left: 20px;
  font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
  color: var(--light-gray);
}

@keyframes slideInLeft {
  from {
    transform: translateY(100%);
    opacity: 0;
  }

  to {
    transform: translateY(0);
    opacity: 1;
  }
}

@keyframes slideInRight {
  from {
    transform: translateX(100%);
    opacity: 0;
  }

  to {
    transform: translateX(0);
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
