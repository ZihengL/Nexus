<template>
  <div class="amazon">
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
        <a href="rebeccadmombrun@outlook.com" target="_blank" class="info-link"
          >me contacter : rebeccadmombrun@outlook.com</a
        >
      </div>
    </div>
  </div>
</template>

<style>
.myName {
  color: white;
}

.all-info {
  display: flex;
  gap: 20px; /* Adjusted spacing */
  justify-content: center;
  align-items: center;
  padding: 5%;
}

.amazon {
  display: flex;
  align-items: center;
  gap: 10%;
}
.info-link {
  color: #007bff;
  text-decoration: none;
  font-weight: bold;
  position: relative;
  padding-left: 20px;
  animation: slideInRight 2s ease-out;
}

.info-link::after {
  content: "→";
  position: absolute;
  left: 0;
  top: 0;
  font-size: 16px;
  color: #007bff;
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

.profile img {
  width: 250px;
  height: auto;
  border-radius: 10%;
}

.info {
  max-width: 100%;
  margin-top: 0%;
}

.info h1,
.info h2,
.info p {
  animation: fadeIn 1.5s ease-out;
  animation: slideInLeft 1.5s ease-out;
}

.info h1 {
  color: #ffffff;
  font-size: 2.5em;
  margin-bottom: 2%;
}

.info h2 {
  color: #666;
  font-size: 1.5em;
  margin-bottom: 2%;
}

.info p {
  font-size: 30px;
  color: #999;
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
