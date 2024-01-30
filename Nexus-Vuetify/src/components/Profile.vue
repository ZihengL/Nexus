<template>
    <!-- Created By CodingNepal -->
    <div class="wrapper">
		<v-avatar size="10rem">
			<v-img
				src="../assets/Rich_Ricasso.png"
				alt="John"
			></v-img>
		</v-avatar>
        <h3>Nom Prenom</h3>
        <router-link to="/Profile" class="router">
			<v-icon icon="mdi-account-circle" />
          	<span class="link-btn" >Gerer son profil</span>
        </router-link>

		<div class="field btn">
			<div class="btn-layer"></div>
			<v-btn density="default" class="submit" @click="$emit('showLogin')">
				Se deconnecter
			</v-btn>
		</div>
    </div>
</template>


<script setup>
</script>

<style src="../styles/ProfileStyle.css" scoped></style>
