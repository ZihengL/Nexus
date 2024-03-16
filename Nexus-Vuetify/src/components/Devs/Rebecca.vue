<template>
  <div class="Rebecca">
    <div class="profile">
      <img src="/src/assets/Dev_Picture/rebPic.jpg" alt="Amazigh" />
    </div>
    <div class="info">
      <h1 class="myName">Rebecca</h1>
      <h2>Pogrammeuse Junior</h2>
      <p>
        Rebecca se donne à la programmation en utilisant sa curiosité et sa passion pour
        le codage, voyant chaque défi comme une chance d'innover. Sa vision de
        la programmation comme art et science lui permet de créer des solutions
        élégantes et pratiques. Engagée dans le partage de connaissances via
        GitHub, elle valorise l'impact humain de la technologie, concevant des
        outils qui répondent aux besoins réels des utilisateurs. Son approche
        empathique et créative fait d'elle une source d'inspiration, montrant
        que le code peut transformer positivement le monde.
      </p>
      <div class="all-info">
        <a
          href=""
          target="_blank"
          class="info-link"
          >Visitez mon Github</a
        >
        <a href="mailto:rebeccadmombrun@outlook.com" target="_blank" class="info-link"
          >me contacter </a
        >
      </div>
    </div>
  </div>
</template>
<style scoped>
.all-info {
  display: flex;
  height: 100%;
  gap: 20px;
  color: rgb(6, 6, 136);
  /* justify-content: center; */
  /* align-items: center; */
}

.Rebecca {
  margin: 25px;
  display: flex;
  align-items: center;
  gap: 30px;
  padding: 20px;
  /* background: -webkit-linear-gradient(right, var(--purple), var(--dark-blue), var(--purple), var(--dark-blue)); */
  background: -webkit-linear-gradient(orange, yellow, white);
  /* background-color: linear-gradient(orange, yellow, white); */
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
    color: blue;
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