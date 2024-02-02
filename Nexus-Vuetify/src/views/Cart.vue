<template>
    <div>
        <h1>Votre panier </h1>
        <Cart/>
    </div>
  </template>
  <script scoped>
    import Cart from '../components/Cart.vue';
    export default {
      components: {
        Cart,
      },
    };
  </script>