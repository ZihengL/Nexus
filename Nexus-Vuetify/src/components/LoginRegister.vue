<template>
   <div class="wrapper">
     <div class="title-text">
       <div class="title login" v-show="isLogin">
         Login Form
       </div>
       <div class="title signup" v-show="!isLogin">
         Signup Form
       </div>
     </div>
     <div class="form-container">
       <div class="slide-controls">
         <input type="radio" name="slide" id="login" v-model="isLogin" value="true" checked>
         <input type="radio" name="slide" id="signup" v-model="isLogin" value="false">
         <label for="login" class="slide login">Login</label>
         <label for="signup" class="slide signup">Signup</label>
         <div class="slider-tab"></div>
       </div>
       <div class="form-inner">

         <form action="#" class="login" v-show="isLogin">
           <!-- ... Login form content ... -->
            <div class="field">
               <input type="text" placeholder="Email Address" required>
            </div>
            <div class="field">
               <input type="password" placeholder="Password" required>
            </div>
            <div class="pass-link">
               <a href="#">Forgot password?</a>
            </div>
            <div class="field btn">
               <div class="btn-layer"></div>
               <input type="submit" value="Login">
            </div>
            <div class="signup-link">
               Not a member? <a href="">Signup now</a>
            </div>
         </form>

         <form action="#" class="signup" v-show="!isLogin">
           <!-- ... Signup form content ... -->
           <div class="field">
               <input type="text" placeholder="Email Address" required>
            </div>
            <div class="field">
               <input type="password" placeholder="Password" required>
            </div>
            <div class="field">
               <input type="password" placeholder="Confirm password" required>
            </div>
            <div class="field btn">
               <div class="btn-layer"></div>
               <input type="submit" value="Signup">
            </div>
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
 