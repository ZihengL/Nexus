<template>
  <footer class="footer">
    <div class="container">
      <span class="text-muted">
        <router-link to="/" class="betweenLinks router">

          <span class="link-btn" text>Accueil</span>
        </router-link>
        <router-link to="/store" class="betweenLinks router">

          <span class="link-btn" text>Magasin</span>
        </router-link>
        <router-link to="/about" class="betweenLinks router">

          <span class="link-btn" text>À propos</span>
        </router-link>
        <router-link to="/login" class="betweenLinks router">

          <span class="link-btn" text>login</span>
        </router-link>
        <router-link to="/register" class="betweenLinks router">

          <span class="link-btn" text>Accueil</span>
        </router-link>
        <router-link to="/cart" class="betweenLinks router">

          <span class="link-btn" text>Accueil</span>
        </router-link>
        <router-link to="/user/profil" class="betweenLinks router">

          <span class="link-btn" text>Profil</span>
        </router-link></span>
    </div>
  </footer>
</template>

<script scoped>


export default {

  name: 'Footer'
}

</script>

<style >
.footer {
  position: fixed;
  bottom: 0;
  width: 100%;
  background-color: #f8f9fa;
  text-align: center;
  padding: 20px 0;
}
</style>
