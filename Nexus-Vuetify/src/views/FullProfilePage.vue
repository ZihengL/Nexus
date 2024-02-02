<template>
  <div>
    <h1>
      <img src="../assets/logoNexus.png" alt="bugmanguy logo" style="background-color: blue;"/>
      <span>FANS </span>
      <span>GAMES</span>
    </h1>
  </div>
  <div>
    <label for="username">Username:</label>
    <input type="text" id="username">
  </div>
  <div>
    <label for="email">Email:</label>
    <input type="email" id="email">
  </div>
  <div>
    <label for="bio">Bio:</label>
    <textarea id="bio"></textarea>
  </div>
  <div>
    <button>Save</button>
  </div>

  <div>
    <h2>Your Profile</h2>
    <p><strong>Username:</strong>pruprut </p>
    <p><strong>Email:</strong> thom.prud@videotron.ca</p>
    <p><strong>Password:</strong> admin</p>
  </div>
</template>
<style scoped>
body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 20px;
}

#app {
  max-width: 600px;
  margin: 0 auto;
}

h1,
h2 {
  text-align: center;
}

label {
  display: block;
  margin-bottom: 5px;
}

input,
textarea {
  width: 100%;
  padding: 10px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  box-sizing: border-box;
}

button {
  padding: 10px 20px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}
</style>
