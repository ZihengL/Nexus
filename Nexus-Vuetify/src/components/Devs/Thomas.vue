<template>
  <div class="profile-container">
    <div class="profile-info glass roundBorderAll">
      <img class="profile-picture roundBorderAll" src="../../assets/Dev_Picture/Thomas.png"
        alt="Photo de Thomas Prud'Homme" />
      <div class="profile-text glass3 roundBorderAll">
        <h1 class="profile-name">Thomas Prud'homme</h1>
        <h2 class="profile-title">Développeur front-end et back-end</h2>
        <p class="profile-description">
          En tant que développeur web, Thomas excelle dans l'art de transformer des idées complexes en solutions
          fonctionnelles
          et intuitives. Sa passion pour le codage se manifeste à travers des sites web dynamiques et réactifs qui
          captivent les
          utilisateurs dès leur première visite. Grâce à son expertise technique et sa créativité, il crée des
          expériences
          en
          ligne fluides et engageantes, propulsant ainsi les projets de ses clients vers de nouveaux sommets dans le
          monde
          numérique.
        </p>
        <blockquote class="profile-quote">“L’innovation est le moteur de la créativité et de la transformation.”
        </blockquote>
        <cite class="profile-cite">Marc Benioff</cite>
        <div class="profile-links">
          <div class="fieldBtn">
            <div class="btn-layer"></div>
            <v-btn density="default" class="submit" href="https://github.com/pruprut?tab=repositories">
              Github
            </v-btn>
          </div>
          <div class="fieldBtn">
            <div class="btn-layer"></div>
            <v-btn density="default" class="submit" href="mailto:thom.prud@videotron.ca">
              Email
            </v-btn>
          </div>
          <div class="fieldBtn">
            <div class="btn-layer"></div>
            <v-btn density="default" class="submit" href="https://www.linkedin.com/in/thomas-prud-homme-7921b8261/">
              Linkedin
            </v-btn>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.profile-info {
  display: flex;
  padding: 5%
}

.profile-container {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

.profile-content {
  display: flex;



}

.profile-image {
  margin-right: 20px;
}

.profile-image img {
  max-width: 200px;
  border-radius: 5px;
  /* Ajout d'un léger arrondi aux bords de l'image */
}

.profile-name {
  font-size: 28px;
  margin-bottom: 10px;
}

.profile-title {
  font-size: 20px;
  font-style: italic;
  margin-bottom: 20px;
}

.profile-description {
  font-size: 16px;
  line-height: 1.6;
  margin-bottom: 20px;
}

.profile-quote {
  font-style: italic;
  margin-bottom: 10px;
}

.profile-cite {
  font-size: 14px;
  font-style: normal;
  /* Retrait de l'italique pour la citation */
}

.profile-links {
  margin-top: 20px;
  display: flex;
  gap: 5%;
}

.profile-link {
  margin-right: 10px;
  text-decoration: none;
  color: #007bff;
}

.profile-link {
  margin-right: 10px;
  text-decoration: none;
  color: #007bff;
  transition: color 0.3s ease;
  /* Transition de couleur sur 0.3s avec un effet d'accélération */
}

.profile-link:hover {
  color: #ff7f50;
  /* Nouvelle couleur au survol */
}

/* Effet de survol sur l'image */
.profile-picture {
  width: 30%;
  height: 30%;
  margin-right: 20px;
  transition: transform 0.3s ease;
  /* Transition de transformation sur 0.3s avec un effet d'accélération */
}

.profile-picture:hover {
  transform: scale(1.1);
  /* Zoom de l'image au survol */
}

.profile-text {
  transition: color 0.3s ease;
  padding: 5%;
  color: var(--light-trans-2);

  /* Transition de couleur sur 0.3s avec un effet d'accélération */
}



.profile-background {
  background-image: url('votre-image.jpg');
  background-attachment: fixed;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  /* Ajoutez ici les styles CSS pour l'effet de parallaxe */
}

.profile-picture {
  transition: transform 0.3s ease;
  /* Transition de transformation sur 0.3s avec un effet d'accélération */
}

.profile-picture:hover {
  transform: scale(1.1);
  /* Zoom de l'image au survol */
}

.profile-link {
  transition: color 0.3s ease;
  /* Transition de couleur sur 0.3s avec un effet d'accélération */
}

.profile-link:hover {
  color: #ff7f50;
  /* Nouvelle couleur au survol */
}
</style>

<script>
</script>
