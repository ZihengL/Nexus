<template>
    <v-container>
      <v-row>
        <v-col cols="12" md="8">
          <v-card>
            <v-card-title>Shopping Cart</v-card-title>
            <v-card-text>
              <v-data-table
                :headers="headers"
                :items="cartItems"
                :hide-default-footer="true"
                class="elevation-1"
              >
                <template v-slot:item.actions="{ item }">
                  <v-icon small class="mr-2" @click="removeItem(item)">mdi-delete</v-icon>
                </template>
                <template v-slot:item.quantity="{ item }">
                  <v-text-field
                    type="number"
                    min="1"
                    v-model="item.quantity"
                    @input="updateQuantity(item)"
                    solo
                    dense
                    hide-details
                  ></v-text-field>
                </template>
              </v-data-table>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col cols="12" md="4">
          <v-card>
            <v-card-title>Promotion Code</v-card-title>
            <v-card-text>
              <v-text-field
                label="Coupon code"
                solo
                dense
                hide-details
                v-model="couponCode"
              ></v-text-field>
            </v-card-text>
            <v-card-actions>
              <v-btn color="primary" @click="applyCoupon">Apply Coupon</v-btn>
            </v-card-actions>
          </v-card>
          <v-card>
            <v-card-title>Cart Totals</v-card-title>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title>Subtotal</v-list-item-title>
                <v-list-item-title class="text-right">{{ subtotal | currency }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title>Total</v-list-item-title>
                <v-list-item-title class="text-right">{{ total | currency }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-card-actions>
              <v-btn color="success" block @click="proceedToCheckout">Proceed to Checkout</v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </template>
  
  <script>
  export default {
    data() {
      return {
        headers: [
          { text: 'Product', value: 'name' },
          { text: 'Price', value: 'price' },
          { text: 'Quantity', value: 'quantity' },
          { text: 'Subtotal', value: 'subtotal' },
          { text: 'Actions', value: 'actions', sortable: false },
        ],
        cartItems: [
          { name: 'Summer T-Shirt', price: 177.0, quantity: 1, subtotal: 177.0 },
          { name: 'Warm Jacket', price: 245.0, quantity: 1, subtotal: 245.0 },
        ],
        couponCode: '',
      };
    },
    methods: {
      removeItem(item) {
        const index = this.cartItems.indexOf(item);
        if (index > -1) {
          this.cartItems.splice(index, 1);
        }
      },
      updateQuantity(item) {
        item.subtotal = item.price * item.quantity;
      },
      applyCoupon() {
        // Implement coupon logic here
      },
      proceedToCheckout() {
        // Implement checkout logic here
      },
    },
    computed: {
      subtotal() {
        return this.cartItems.reduce((acc, item) => acc + item.subtotal, 0);
      },
      total() {
        // Apply discounts or taxes if applicable
        return this.subtotal;
      },
    },
    filters: {
      currency(value) {
        const number = new Number(value);
        return number.toFixed(2);
      },
    },
  };
  </script>
  