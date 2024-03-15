<template>
  <div class="zi">
    <div class="profile">
      <img src="/src/assets/Dev_Picture/amazigh.png" alt="Amazigh">
    </div>
    <div class="info">
      <h1>ZI HENG LIU</h1>
      <h2>Développeur Back-end</h2>
      <p>Développeur web junior, qui s'intéresse beaucoup à la création de sites innovants, mais
        aussi a l'interaction client.
      </p>
      <div class="all-info">
      <a href="https://github.com/ZihengL" target="_blank" class="info-link">Github</a>
      <a href="amazighmaloum04@gmail.com" target="_blank" class="info-link">me contacter</a>
    </div>
    </div>
  </div>
</template>

<style>
.all-info {
  display: flex;
  gap: 20px; /* Adjusted spacing */
  justify-content: center;
  align-items: center;
}

.zi {
  margin-top: 5%;
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
  content: '→'; 
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
  text-align: left;
  max-width: 100%; 
  margin-top: 0%;
}

.info h1, .info h2, .info p {
  animation: fadeIn 1.5s ease-out;
  animation: slideInLeft 1.5s ease-out;
}

.info h1 {
  color: var(--light-gray);
  font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
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
