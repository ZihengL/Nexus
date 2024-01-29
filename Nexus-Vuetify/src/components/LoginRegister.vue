<template>
   <!-- Created By CodingNepal -->
   <div class="wrapper">
     <div class="title-text">
       <div class="title login" v-show="isLogin">
         Se connecter
       </div>
       <div class="title signup" v-show="!isLogin">
         S'inscrire
       </div>
     </div>
     <div class="form-container">
       <div class="slide-controls">
         <input type="radio" name="slide" id="login" v-model="isLogin" value="true" checked>
         <input type="radio" name="slide" id="signup" v-model="isLogin" value="false">
         <label for="login" class="slide login">Connexion</label>
         <label for="signup" class="slide signup">Inscription</label>
         <div class="slider-tab"></div>
       </div>
       <div class="form-inner">

         <form action="#" class="login" v-show="isLogin">
           <!-- ... Login form content ... -->
            <div class="field">
               <input type="text" placeholder="Addresse email" required>
            </div>
            <div class="field">
               <input type="password" placeholder="Mot de passe" required>
            </div>
            <div class="pass-link">
               <a href="#">Mot de passe oublier ?</a>
            </div>
            <!--<div class="field btn">
               <div class="btn-layer"></div>
               <input type="submit" value="Login">
            </div>-->
            <v-btn density="default" class="field btn">
                  Se connecter
            </v-btn>
            <div class="signup-link">
              Pas encore inscris ? <a href="">S'inscrire</a>
            </div>
         </form>

         <form action="#" class="signup" v-show="!isLogin">
           <!-- ... Signup form content ... -->
           <div class="field">
               <input type="text" placeholder="Email" required>
            </div>
            <div class="field">
               <input type="password" placeholder="Mot de passe" required>
            </div>
            <div class="field">
               <input type="password" placeholder="Confirmer le mot de passe" required>
            </div>
            <v-btn density="default" class="field btn">
                  S'inscrire
            </v-btn>
            <!--<div class="field btn">
               <div class="btn-layer"></div>
               <v-btn density="default">
                  Signup
               </v-btn>
                  <input type="submit" value="Signup">
               
            </div>-->
         </form>

       </div>
     </div>
   </div>
 </template>
 
 <script>
 import loginScript from '../JS/LoginScript.js';
 
 export default {
   data() {
     return {
       isLogin: true,
     };
   },
   mounted() {
     loginScript.init(this);
   },
   methods: {
     toggleLogin() {
       this.isLogin = true;
     },
     toggleSignup() {
       this.isLogin = false;
     },
   },
 };
 </script>
 
 <style src="../styles/Login.css" scoped>
   /* Add your styles here */
 </style>
 