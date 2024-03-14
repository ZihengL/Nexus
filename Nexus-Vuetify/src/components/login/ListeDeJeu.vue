<template>
  <!-- <div v-for="activity in activities" :key="activity.id" class="activities">-->
  <div v-if="LeGame_title" class="container glass2 roundBorderSmall">
    <div class="up">
      <div class="img">
        <img
          :src=" UrlGameImg"
          alt="image jeu"
          class="roundBorderSmall"
        />
      </div>
      <div class="jeu">
        <span v-if="props.himself && !props.buy"
          > Joué à {{ LeGame_title }} le 17/04/2022
        </span>
        <span v-if="props.himself && props.buy">
          Titre : {{ LeGame_title || 'Erreur Titre Introuvable' }} - Televerser le : {{formattedReleaseDate}}
         </span>
        <span v-else>{{ LeGame_title }}</span>
        <br />
      </div>
    </div>
    <div class="listeBtn">
      <div class="fieldBtn">
        <div class="btn-layer"></div>
        <v-btn
          :to="{ name: 'Game', params: { idGame: props.idJeu } }"
          density="default"
          class="submit glow"
        >
          Voir plus
        </v-btn>
      </div>
      <btnComp
        v-if="props.himself"
        :contenu="'Supprimmer'"
        @toggle-btn="deleteGame"
      />
      <btnComp
        v-if="props.himself"
        :contenu="'Mettre a jour'"
        @toggle-btn="routeToUpload"
        title="update"
      />
    </div>
  </div>
</template>

<script setup>
import btnComp from "../btnComponent.vue";
import { defineProps, onMounted, ref , computed} from "vue";
import { useRouter } from 'vue-router';
import storageManager from "../../JS/localStorageManager.js";
import { getStorage, ref as firebaseRef, getDownloadURL, uploadBytes} from "firebase/storage";
import { deleteData, getOne } from "../../JS/fetchServices.js";
const props = defineProps(["himself", "idJeu", "buy"]);
const LeGame_title = ref(null);
const gameObject = ref(null);
let UrlGameImg = ref(""); 
const defaultPath = '/src/assets/imgJeuxLogo/noImg.jpg';
const router = useRouter();
const storage = getStorage();


onMounted(async () => {
  try {
    const dataGame = await getOne("games", "id", props.idJeu);
    // fetchData("games", "getOne", "id", props.idJeu,null, null, null, "GET");
    LeGame_title.value = dataGame[0].title;
    gameObject.value = dataGame[0];
    console.log("leDevs : ", dataGame);
    UrlGameImg.value = await fetchGameUrl(props.idJeu); 
    console.log("UrlGameImg : ", UrlGameImg);
  } catch (error) {
    console.error("Error fetching data:", error);
  }
});

async function fetchGameUrl(gameId) {
  const imagePath = `Games/${gameId}/media/${gameId}_Store.png`;
  const imageRef = firebaseRef(storage, imagePath);
  try {
    const url = await getDownloadURL(imageRef);
    return url; // Directly return the URL string
  } catch (error) {
    console.error(`Error fetching image for ${gameId}:`, error);
    return defaultPath; // Return the default image path on error
  }
}

const formattedReleaseDate = computed(() => {
  if (!gameObject.value || !gameObject.value.releaseDate) return 'Erreur Date Introuvable';
  const date = new Date(gameObject.value.releaseDate);
  return date.toISOString().split('T')[0]; // Correctly formats the date
});

function routeToUpload() {
  router.push({ name: 'update', params: { gameToUpdateId: props.idJeu } }); 
}


async function deleteGame() {
  const isConfirmed = confirm(`Êtes-vous sûr de vouloir supprimer le jeu ${LeGame_title.value}?`);
  if (!isConfirmed) {
    console.log("Deletion canceled by the user.");
    return; 
  }

  if (
    props.idJeu &&
    storageManager.getAccessToken() &&
    storageManager.getRefreshToken()
  ) {
    try {
      let delete_data = {
        id: props.idJeu,
        tokens: {
          access_token: storageManager.getAccessToken(),
          refresh_token: storageManager.getRefreshToken(),
        },
      };
      console.log("delete_data : ", delete_data);
      const response = await deleteData("games", delete_data);
      if (response != false) {
        console.log("Game deleted successfully", response);


             /*
        var dossierRef = firebaseRef(storage,'Games/18');

        console.log("allo dossierref=",dossierRef);

        // Supprimer le dossier et son contenu
        dossierRef.delete().then(function () {
          console.log("Dossier supprimé avec succès.");
        }).catch(function (error) {
          console.error("Erreur lors de la suppression du dossier:", error);
        });
*/

        window.location.reload();
      }
    } catch (error) {
      console.error("Error deleting game:", error);
    }
  }
}

</script>

<style lang="scss">
.container {
  display: flex;
  flex-direction: column;
  border: 1px solid rgba(0, 0, 0, 0.132);
  margin-top: 1.5%;

  .up {
    display: flex;
    flex-direction: row;
    justify-content: space-between;

    .img {
      flex: 2;

      img {
        width: 100%;
      }
    }

    .jeu {
      flex: 5;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 0%;
    }
  }
  .listeBtn {
    display: flex;
    flex-direction: row;
    gap: 3%;
    padding: 0% 1% 2% 1%;
  }
}
</style>
