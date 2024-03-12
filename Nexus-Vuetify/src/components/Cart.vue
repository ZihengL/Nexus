<template>
    <v-card>
      <v-list>
        <v-subheader>Your Cart</v-subheader>
        <v-list-item
          v-for="(item, index) in cartItems"
          :key="index"
        >
          <v-list-item-content>
            <v-list-item-title>{{ item.name }}</v-list-item-title>
            <v-list-item-subtitle>${{ item.price }}</v-list-item-subtitle>
          </v-list-item-content>
  
          <v-list-item-action>
            <v-btn icon @click="removeFromCart(index)">
              <v-icon>mdi-delete</v-icon>
            </v-btn>
          </v-list-item-action>
        </v-list-item>
      </v-list>
  
      <v-divider></v-divider>
  
      <v-card-text>
        <div class="d-flex justify-space-between">
          <span>Total:</span>
          <span>${{ totalPrice }}</span>
        </div>
      </v-card-text>
  
      <v-card-actions>
        <v-btn color="primary" @click="checkout">Checkout</v-btn>
      </v-card-actions>
    </v-card>
  </template>
  
  <script>
  export default {
    data() {
      return {
        // Example cart items
        cartItems: [
          { name: "Game 1", price: 59.99 },
          { name: "Game 2", price: 39.99 },
          { name: "Game 3", price: 49.99 },
        ],
      };
    },
    computed: {
      totalPrice() {
        return this.cartItems.reduce((total, item) => total + item.price, 0).toFixed(2);
      },
    },
    methods: {
      removeFromCart(index) {
        this.cartItems.splice(index, 1);
      },
      checkout() {
        alert('Proceeding to checkout...');
        // Here you can add your logic to proceed to checkout
      },
    },
  };
  </script>
  
  <!-- Add "scoped" attribute if your styles are specific to this component -->
  <style scoped>
  </style>
  