<template>
    <v-app-bar :elevation="21">
        <template v-slot:prepend>
          <v-app-bar-nav-icon></v-app-bar-nav-icon>
        </template>
    
        <v-app-bar-title>Application Bar</v-app-bar-title>
      </v-app-bar>
</template>

<script setup>
import { ref } from 'vue'
</script>