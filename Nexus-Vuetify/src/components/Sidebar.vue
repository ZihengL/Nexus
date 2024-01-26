<template>
	<aside :class="`${is_expanded ? 'is-expanded' : ''}`" class="goldenBackground">
		<div class="glass2">
			<div class="logo">
				<img :src="logoURL" alt="Vue" />
			</div>
			<div class="menu-toggle-wrap">
				<button class="menu-toggle" @click="ToggleMenu" title="Expand">
					<span class="material-icons silver">keyboard_double_arrow_right</span>
				</button>
			</div>
			<div class="menu">
				<router-link to="/" class="button" title="Accueil">
					<span class="material-icons silver">home</span>
					<span class="text silver  silverToGolden">Accueil</span>
				</router-link>
				<router-link to="/Catalogue" class="button" title="Catalogue">
					<span class="material-symbols-outlined silver">checkroom</span>
					<span class="text silver silverToGolden">Catalogue</span>
				</router-link>
				<router-link to="/Infolettre" class="button" title="Infolettre">
					<span class="material-symbols-outlined silver">newspaper</span>
					<span class="text silver silverToGolden">Infolettre</span>
				</router-link>
				<router-link to="/Panier" class="button" title="Infolettre">
					<span class="material-symbols-outlined silver">shopping_cart</span>
					<span class="text silver silverToGolden">Panier</span>
				</router-link>

			</div>
			<div class="flex"></div>
			
			<div class="menu">
				<div id="meteo" class="meteo"></div>
				<router-link to="/Login" class="button" title="Login">
					<span class="material-symbols-outlined silver">account_circle</span>
					<span class="text silver silverToGolden">Se connecter</span>
				</router-link>
			</div>
		</div>
	</aside>
</template>

<script setup>
</script>
