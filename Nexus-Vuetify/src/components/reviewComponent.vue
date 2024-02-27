<template>
    <div class="review">
      <div class="review-header">
        <img :src="img" :alt="username" class="review-avatar" />
        <div class="review-user-details">
          <h3 class="review-username">{{ username }}</h3>
          <p class="review-userId">User ID: {{ userId }}</p>
        </div>
      </div>
      <div class="review-body">
        <p class="review-text">{{ text }}</p>
      </div>
      <div class="review-footer">
        <v-rating :value="rating" readonly size="14"></v-rating>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps } from 'vue';
  
  const props = defineProps({
    id: Number,
    text: String,
    img: String,
    rating: Number,
    username: String,
    userId: Number,
  });
  </script>
  <style scoped>
  .review {
    border: 1px solid #eee;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 16px;
    display: flex;
    flex-direction: column;
  }
  
  .review-header {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
  }
  
  .review-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 8px;
  }
  
  .review-user-details {
    display: flex;
    flex-direction: column;
  }
  
  .review-username, .review-userId {
    margin: 0;
  }
  
  .review-text {
    margin: 0 0 8px 0;
  }
  
  .review-footer {
    text-align: right;
  }
  </style>