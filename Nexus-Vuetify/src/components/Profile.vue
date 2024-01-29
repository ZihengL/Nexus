<template>
    <!-- Created By CodingNepal -->
    <div>
        <form method="post" action="#" class="connexion_form glass">
				<div class="avatar">
					<span class="material-symbols-outlined silver">face</span>
				</div>
				<div class="formulaire_detail">
					
					<input type="text" name="page" value="<?php echo $page ?>" style="display: none;">
					
					<p class="errorDetail">wqr</p>


					<label for="nomDetail"  class="golden"><b>Nom</b></label><br><br>
					<input type="text" name="nomDetail" id="nomDetail" placeholder="Entrer votre nom" required> <br><br>

					<label for="prenomDetail" class="golden"><b>Prenom</b></label><br><br>
					<input type="text" name="prenomDetail" id="prenomDetail" placeholder="Entrer votre prenom" required><br><br>

					<label for="emailDetail"   class="golden"><b>Email</b></label><br><br>
					<input type="email" name="emailDetail" id="emailDetail" placeholder="Entrer votre email" required><br><br>

					<label for="identifiantDetail"   class="golden"><b>Nom d'utilisateur</b></label><br><br>
					<input type="text" name="identifiantDetail" id="identifiantDetail" placeholder="Entrer votre nom d'utilisateur" required><br><br>

					<label for="passwordDetail"   class="golden"><b>Mot de passe</b></label><br><br>
					<input type="password" name="passwordDetail" id="passwordDetail" placeholder="Entrer votre mot de passe"><br><br>

					<div class="infolettreDiv">
						<input type="checkbox" name="infolettre" id="infolettre" value="yes">
						<label for="infolettre"  class="golden">s'inscrire a l'infolettre</label>
					</div><br><br>
					<div class="buttonConnection">
						<button type="submit" class="goldenBackground"><span class="insideBtn" @click="toggleDetailForm">Mettre a jour</span></button>
						<button type="button" class="silverBackground"><span class="insideBtn2" @click="toggleConnexionForm">Deconnecter</span></button>
					</div>
					<br><br>
				</div>
			</form>
    </div>
</template>

<script>
</script>

<style scoped>
/* Add your styles here */
</style>
