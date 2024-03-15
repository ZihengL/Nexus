<template>
  <div id="pageContainer">
    <h2>
      {{ props.mode === "create" ? "Ajouter un Jeu" : "Modifier un Jeu" }}
    </h2>
    <div v-if="state.errorMessage" class="error-message">
      {{ state.errorMessage }}
    </div>
    <div>
      <label for="title">Titre du Jeu</label>
      <input
        type="text"
        id="title"
        v-model="state.gameTitle"
        placeholder="Entrer le titre du jeu..."
      />
    </div>

    <div class="tags-creator-container">
      <label for="tags">Genre:</label>
      <div class="tags-input-container">
        <input
          type="text"
          id="tags"
          v-model="state.tags"
          placeholder="action, sport, solo..."
        />
        <btnComp
          :propClass="'addTagBtn'"
          :contenu="'Add Tag'"
          @toggle-btn="updateTagsArray"
        ></btnComp>
      </div>

      <div class="tags-display-container">
        <div class="tags-from-db-container">
          <p>Genres existants :</p>
          <div class="tags-from-db">
            <div
              v-for="tag in state.tagsFromDatabase"
              :key="tag.id"
              class="tag_element_DB"
              @click="addClickedTagToTagsArray(tag.name)"
            >
              {{ tag.name }}
            </div>
          </div>
        </div>

        <div class="user-added-tags-container">
          <p>Genres à ajouter :</p>
          <div class="user-added-tags">
            <div
              v-for="(tag, index) in state.tagsArray"
              :key="`user-${index}`"
              class="tag_element"
            >
              {{ tag }}
              <btnComp
                :propClass="'removeBtn'"
                :contenu="'X'"
                @toggle-btn="() => removeItem(index, state.tagsArray)"
              ></btnComp>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="description_container">
      <p class="description_title">Description :</p>
      <textarea
        class="description"
        name="description"
        placeholder="Mon jeu est super cool ;) parce que..."
        v-on:input="validateDescriptionLength"
        v-model="state.description"
      ></textarea>
      <p id="charCount">{{ charCountText }}</p>
    </div>
    <div>
      <btnComp
        :contenu="'Sélectionner le fichier du jeu'"
        @toggle-btn="openFileBrowser"
      ></btnComp>
      <div v-if="state.gameFile">
        <p>Selected Game File: {{ state.gameFile.name }}</p>
        <p>File Size: {{ fileSize }} KB</p>
        <p>File Type: {{ state.gameFile.type }}</p>
      </div>
    </div>
    <div>
      <btnComp
        :contenu="'Téléverser des images (Max ' + state.MAX_IMG_LIST + ')'"
        @toggle-btn="openImageBrowser"
      ></btnComp>
      <div class="imgList" v-if="state.imageFiles.length">
        <div
          class="img_element"
          v-for="(file, index) in state.imageFiles"
          :key="index"
        >
          {{ file.name }}
          <img :src="file.url" :alt="file.name" />
          <btnComp
            :propClass="'removeBtn'"
            :contenu="'X'"
            @toggle-btn="() => removeItem(index, state.imageFiles)"
          ></btnComp>
        </div>
      </div>
    </div>
    <div>
      <btnComp
        :contenu="
          'Téléverser une image d\'icone (Max ' + state.MAX_IMG_STORE + ')'
        "
        @toggle-btn="openImageBrowser_forStore"
      ></btnComp>
      <div class="imgList" v-if="state.imageStoreObject.length">
        <div
          class="img_element"
          v-for="(file, index) in state.imageStoreObject"
          :key="index"
        >
          {{ file.name }}
          <img :src="file.url" :alt="file.name" />
          <btnComp
            :propClass="'removeBtn'"
            :contenu="'X'"
            @toggle-btn="() => removeItem(index, state.imageStoreObject)"
          ></btnComp>
        </div>
      </div>
    </div>
    <div>
      <btnComp
        :contenu="'Téléverser des vidéos (Max ' + state.MAX_VIDS + ')'"
        @toggle-btn="openVideoBrowser"
      ></btnComp>
      <div class="vidList" v-if="state.videoFiles.length">
        <div
          class="vid_item"
          v-for="(file, index) in state.videoFiles"
          :key="index"
        >
          {{ file.name }}
          <video controls :src="file.url" :alt="file.name"></video>
          <btnComp
            :propClass="'vid_RemoveBtn'"
            :contenu="'X'"
            @toggle-btn="() => removeItem(index, state.videoFiles)"
          ></btnComp>
        </div>
      </div>
    </div>
    <btnComp
      :contenu="
        props.mode === 'create' ? 'Créer un jeu' : 'Mettre à jour le jeu'
      "
      @toggle-btn="submitGame"
    ></btnComp>
  </div>
</template>

<script setup>
import JSZip from "jszip";
import btnComp from "../components/btnComponent.vue";
import storageManager from "../JS/localStorageManager";
import { reactive, defineProps, computed, onMounted } from "vue";
import {
  create,
  getAllMatching,
  deleteData,
  getAll,
} from "../JS/fetchServices.js";
const storage = getStorage();
// import { getStorage, ref, uploadBytes, getDownloadURL } from "firebase/storage";
import { getStorage, ref as firebaseRef, uploadBytes } from "firebase/storage";
const validImageTypes = ["image/jpeg", "image/png"];
const validFileTypes = [
  "application/x-msdownload", // MIME type for .exe files
  "application/java-archive", // MIME type for .jar files
];
// const maxFileSize = 5 * 1024 * 1024;

const props = defineProps({
  developerID: {
    type: Number,
    default: Number(storageManager.getIdDev()),
  },
  pageTitle: {
    type: String,
    default: "Create Game",
  },
  initialData: {
    type: Object,
    default: () => ({}),
  },
  mode: {
    type: String,
    default: "create",
  },
});

const state = reactive({
  pageTitle: props.pageTitle,
  gameTitle: "",
  tags: "",
  tagsArray: [],
  gameFile: null,
  gameFilePath: "",
  imageStoreObject: [],
  imageFiles: [],
  videoFiles: [],
  MIN_IMG_LIST: 4,
  MIN_IMG_STORE: 1,
  MAX_IMG_LIST: 4,
  MAX_IMG_STORE: 1,
  MAX_VIDS: 2,
  MIN_TAG: 1,
  MIN_DESC_LENGTH: 10,
  MAX_DESC_LENGTH: 250,
  errorMessage: "",
  creation_date: new Date().toISOString().replace("T", " ").substring(0, 16),
  description: "",
  tagsFromDatabase: [],
});

function addClickedTagToTagsArray(tagName) {
  if (!state.tagsArray.includes(tagName)) {
    state.tagsArray.push(tagName);
  } else {
    console.log(`${tagName} already added.`);
  }
}

const getTagsFrom_DB = async () => {
  try {
    let data = await getAll("tags");
    state.tagsFromDatabase = data;
    console.log("Tags from DB:", data);
  } catch (error) {
    console.error("Error fetching tags from database:", error);
  }
};

const fileSize = computed(() => {
  return state.gameFile ? (state.gameFile.size / 1024).toFixed(2) : "0.00";
});

const charCountText = computed(() => {
  let currentLength = state.description.length;
  return `${currentLength}/${state.MAX_DESC_LENGTH} caractêres`;
});

function validateDescriptionLength() {
  if (state.description.length > state.MAX_DESC_LENGTH) {
    state.description = state.description.substring(0, state.MAX_DESC_LENGTH);
  }
}

const openFileBrowser = () => {
  console.log("selected game file");
  const fileInput = document.createElement("input");
  fileInput.type = "file";
  fileInput.accept = validFileTypes.join(","); // Suggest to the browser to filter files by these types

  fileInput.onchange = (e) => {
    const file = e.target.files[0];

    if (!file || !validFileTypes.includes(file.type)) {
      alert("Invalid file type selected. Please select an executable file.");
      return; // Exit the function if the file type is not valid
    }

    console.log("selected game file : ", file);
    const fileUrl = URL.createObjectURL(file);

    state.gameFile = {
      file: file,
      name: file.name,
      size: file.size,
      type: file.type,
      lastModified: file.lastModified,
      lastModifiedDate: file.lastModifiedDate,
      url: fileUrl,
    };
    console.log("Updated state.gameFile : ", state.gameFile);
  };
  fileInput.click();
};

const openVideoBrowser = () => {
  const fileInput = document.createElement("input");
  fileInput.type = "file";
  fileInput.multiple = true;
  fileInput.accept = "video/*";
  fileInput.onchange = (e) => {
    const newFiles = Array.from(e.target.files);
    const availableSlots = state.MAX_VIDS - state.videoFiles.length;

    if (newFiles.length > availableSlots) {
      alert(
        `You can only upload a maximum of ${availableSlots} more video(s).`
      );
      return;
    }

    const newVideoFiles = newFiles.slice(0, availableSlots).map((file) => ({
      file:file,
      name: file.name,
      size: file.size,
      type: file.type,
      lastModified: file.lastModified,
      url: URL.createObjectURL(file),
    }));

    state.videoFiles = [...state.videoFiles, ...newVideoFiles];
    console.log("Updated state.videoFiles : ", state.videoFiles);
  };
  fileInput.click();
};

const openImageBrowser = () => {
  const fileInput = document.createElement("input");
  fileInput.type = "file";
  fileInput.multiple = true;
  fileInput.accept = "image/*";
  fileInput.onchange = (e) => {
    const newFiles = Array.from(e.target.files);
    const availableSlots = state.MAX_IMG_LIST - state.imageFiles.length;

    if (newFiles.length > availableSlots) {
      alert(
        `You can only upload a maximum of ${availableSlots} more image(s).`
      );
      return;
    }
    const newImageFiles = newFiles
      .slice(0, availableSlots)
      .map((file) => {
        if (!validImageTypes.includes(file.type)) {
          alert("Invalid file type.");
          return null;
        }
        return {
          ...file,
          file,
          url: URL.createObjectURL(file),
          name: file.name,
          type: file.type,
        };
      })
      .filter((file) => file !== null);
    console.log("newImageFiles : ", newImageFiles);

    state.imageFiles = [...state.imageFiles, ...newImageFiles];
  };
  fileInput.click();
};

const openImageBrowser_forStore = () => {
  const fileInput = document.createElement("input");
  fileInput.type = "file";
  fileInput.multiple = true;
  fileInput.accept = "image/*";
  fileInput.onchange = (e) => {
    const newFiles = Array.from(e.target.files);
    const availableSlots = state.MAX_IMG_STORE - state.imageStoreObject.length;

    if (newFiles.length > availableSlots) {
      alert(
        `You can only upload a maximum of ${availableSlots} more image(s).`
      );
      return;
    }
    const newImage_forStore = newFiles
      .slice(0, availableSlots)
      .map((file) => {
        if (!validImageTypes.includes(file.type)) {
          alert("Invalid file type.");
          return null;
        }
        return {
          ...file,
          file,
          url: URL.createObjectURL(file),
          name: file.name,
          type: file.type,
        };
      })
      .filter((file) => file !== null);
    console.log("newImageFiles : ", newImage_forStore);

    state.imageStoreObject = [...state.imageStoreObject, ...newImage_forStore];
  };
  fileInput.click();
};

const removeItem = (indexToRemove, fileList) => {
  URL.revokeObjectURL(fileList[indexToRemove].url);
  fileList.splice(indexToRemove, 1);
};

const formatData = () => {
  if (!state.gameTitle) {
    state.errorMessage = "Le titre du jeu est requis.";
    return false;
  } else if (state.tagsArray.length < state.MIN_TAG) {
    state.errorMessage = `Au moins ${state.MIN_TAG} genre(s) est/sont requis.`;
    return false;
  } else if (state.imageFiles.length < state.MIN_IMG_LIST) {
    state.errorMessage = `Au moins ${state.MIN_IMG_LIST} images est/sont requise(s).`;
    return false;
  } else if (state.description.trim().length < state.MIN_DESC_LENGTH) {
    state.errorMessage =  `Une description entre ${state.MIN_DESC_LENGTH} et ${state.MAX_DESC_LENGTH} caractères est requise.`;
    return false;
  } else if (state.imageStoreObject.length < state.MAX_IMG_STORE) {
    state.errorMessage = `Au moins ${state.MAX_IMG_STORE} images est/sont requise(s) pour la boutique.`;
    return false;
  } else if (state.gameFile == null) {
    state.errorMessage = "Un fichier de jeu est requis.";
    return false;
  } 
   else {
    state.errorMessage = "";
    console.log("Submitting:", {
      gameTitle: state.gameTitle,
      tags: state.tagsArray,
      description: state.description,
      gameFilePath: state.gameFilePath,
      imageFiles: state.imageFiles,
    });
    return true;
  }
};


const uploadImageFiles = async (gameId) => {
  for (let i = 0; i < state.imageStoreObject.length; i++) {
    const imageElement = state.imageStoreObject[i];
    const fileName = `${gameId}_Store.png`;

    const fileRef = firebaseRef(storage, `Games/${gameId}/media/${fileName}`);
    const metadata = {
      contentType: imageElement.type,
    };
    try {
      await uploadBytes(fileRef, imageElement.file, metadata);
      console.log(`${fileName} uploaded successfully.`);
    } catch (error) {
      console.error(`Failed to upload ${fileName}:`, error);
    }
  }

  for (let i = 0; i < state.imageStoreObject.length; i++) {
    const imageElement = state.imageStoreObject[i];
    const fileName = `${gameId}_${i}.png`; 

    const fileRef = firebaseRef(storage, `Games/${gameId}/media/${fileName}`);
    const metadata = {
      contentType: imageElement.type,
    };
    try {
      await uploadBytes(fileRef, imageElement.file, metadata);
      console.log(`${fileName} uploaded successfully.`);
    } catch (error) {
      console.error(`Failed to upload ${fileName}:`, error);
    }
  }

 
  for (let i = 0; i < (state.MAX_IMG_LIST ); i++) {
    const imageElement = state.imageFiles[i];
    const fileName = `${gameId}_${i+1}.png`; 

    const fileRef = firebaseRef(storage, `Games/${gameId}/media/${fileName}`);
    const metadata = {
      contentType: imageElement.type,
    };
    try {
      await uploadBytes(fileRef, imageElement.file, metadata);
      console.log(`${fileName} uploaded successfully.`);
    } catch (error) {
      console.error(`Failed to upload ${fileName}:`, error);
    }
  }
};

const zipFile = async (file, fileName) => {
  const zip = new JSZip();
  // Add a file to the zip. The first argument is the filename inside the zip
  // The second argument is the content of the file
  zip.file(fileName, file);

  try {
    const content = await zip.generateAsync({ type: "blob" });

    return content;
  } catch (error) {
    console.error("Failed to zip the file:", error);
    throw error;
  }
};

const uploadZipFile = async (gameId) => {
  if (!state.gameFile || !state.gameFile.file || !state.gameTitle) {
    console.error("No file selected for upload");
    return;
  }

  const zippedFileName = `${state.gameTitle}.zip`;

  const fileRef = firebaseRef(storage, `Games/${gameId}/${zippedFileName}`);

  try {
    const zippedFileBlob = await zipFile(state.gameFile.file, zippedFileName);

    const metadata = {
      contentType: "application/zip",
    };

    await uploadBytes(fileRef, zippedFileBlob, metadata);
    console.log(`${zippedFileBlob} uploaded successfully`);
  } catch (error) {
    console.error("Failed to upload zipped file:", error);
  }
};

function updateTagsArray() {
  let newTags = state.tags
    .split(",")
    .map((tag) => tag.trim())
    .filter(Boolean);

  newTags.forEach((newTag) => {
    if (!state.tagsArray.includes(newTag)) {
      state.tagsArray.push(newTag);
    } else {
      console.log(`${newTag} already added.`);
    }
  });

  state.tags = "";
}

const create_gameAndTags = async () => {
  await createGame();
  const gameId = await get_CreatedGameID();
  console.log("create_gameAndTags gameId : ", gameId)
  if (gameId) {
    const tagsCreationResult = await createTags();
    if (tagsCreationResult === false) {
      console.error(
        "Game couldn't be added because tags were not successfully created."
      );
      await deleteGame();
    } else {
      console.log("Game creation succeeded.");
      try {
        await uploadImageFiles(gameId);
        await uploadZipFile(gameId);
        alert("Le téléversement a été fait avec succès, veuillez rafraîchir votre page.");
        // Refresh the page
        window.location.reload();
      } catch (error) {
        console.error("Error during file upload:", error);
        alert("An error occurred during file upload. Please try again.");
      }
    }
  }
};

const createGame = async () => {
  if (
    state.imageStoreObject.length == state.MAX_IMG_STORE &&
    state.MAX_IMG_LIST == state.imageFiles.length
  ) {
    let jsonObject = {
      title: state.gameTitle,
      developerID: props.developerID,
      description: state.description,
      releaseDate: state.creation_date,
      ratingAverage: 0,
    };
    let wasGameCreated = await create("games", jsonObject);
    console.log("wasGameCreated:", wasGameCreated);
  }

  console.log(" state.imageStoreObject.length : ",  state.imageStoreObject.length);
  console.log("state.imageFiles.length : ", state.imageFiles.length);
}

const createTags = async () => {
  const gameId = await get_CreatedGameID();
  if (!gameId) {
    console.error("Failed to get game ID");
    return false;
  }

  let allTagsCreated = true;
  for (const tag of state.tagsArray) {
    const jsonObject = {
      name: tag,
      gameId: gameId,
    };
    const result = await create("tags", jsonObject);
    console.log("Tag was created:", result);
    if (result.isSuccessful == false) {
      allTagsCreated = false;
      break;
    }
  }
  return allTagsCreated;
};

const deleteGame = async () => {
  const gameId = await get_CreatedGameID();
  if (!gameId) {
    console.error("Failed to get game ID for deletion");
    return;
  }

  let delete_data = {
    id: gameId,
    tokens: {
      access_token: storageManager.getAccessToken(),
      refresh_token: storageManager.getRefreshToken(),
    },
  };
  console.log("delete_data : ", delete_data);

  const response = await deleteData("games", delete_data);

  if (response.ok) {
    console.log("Game was successfully deleted");
  } else {
    console.error("Failed to delete the game");
  }
};

async function get_CreatedGameID() {
  const filters = {
    title: state.gameTitle,
    developerID: props.developerID,
    description: state.description,
  };

  try {
    let game = await getAllMatching("games", filters);
    if (game.length > 0) {
      console.log("created game :", game);
      return game[0].id;
    } else {
      console.log("No games found with the specified criteria.");
      return null;
    }
  } catch (error) {
    console.error("Error fetching game ID:", error);
    return null;
  }
}

const submitGame = async () => {
  if (formatData()) {
    await create_gameAndTags();
  }
};

async function setDefaultValues() {
  if (props.mode === "update" && props.initialData) {
    state.gameTitle = props.initialData.gameTitle || "";
    state.tags = props.initialData.tags.join(", ") || "";
    state.description = props.initialData.description || "";
  }
}

onMounted(async () => {
  try {
    await setDefaultValues();
    await getTagsFrom_DB();
  } catch (error) {
    console.error("Error setting default values:", error);
  }
});
</script>

<style scoped lang="scss">
#pageContainer {
  display: flex;
  flex-direction: column;
  margin: auto;
  background-color: rgb(64, 86, 119, 0.2);
  padding: 2rem;
  border-radius: 0.5rem;
  color: white;
  width: 60%;
  margin-top: 5%;
  margin-bottom: 5%;
}

.description_container {
  margin-top: 5%;
}

input,
textarea {
  background-color: rgb(64, 86, 119);
  border: none;
  color: white;
  padding: 0.5rem;
  margin-bottom: 0.5rem;
  border-radius: 0.5rem;
  width: 100%; // Ensures input fields stretch to container width
}

// input[type="text"]:focus,
// textarea:focus {
//   outline: 2px solid #007bff;
// }

.error-message {
  color: #ff3860;
  background-color: #ffe5e7;
  padding: 0.5rem;
  margin-bottom: 1rem;
  border-radius: 0.5rem;
  border: 1px solid #ff3860;
}

.tags-input-container {
  display: flex;
  flex-direction: row;
  align-items: center;
  gap: 0.5rem;
}

.tags-creator-container {
  display: flex;
  flex-wrap: wrap;
  flex-direction: column;
  gap: 0.5rem;
}

.imgList {
  margin: 2rem 0;
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
}

.vidList {
  margin: 2rem 0;
  display: flex;
  flex-wrap: wrap;
  flex-direction: column;
  gap: 2rem;
}

.tags-from-db {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 0.5rem;
  flex-direction: row;
}

.tags-from-db-container {
  display: flex;
  align-items: center;
  // flex-wrap: wrap;
  gap: 0.5rem;
  flex-direction: column;
}

.user-added-tags {
  display: grid;
  grid-template-columns: 25% 25% 25% 25%;
  gap: 2%;
}

.user-added-tags-container {
  display: flex;
  align-items: center;
  width: 100%;
  // flex-wrap: wrap;
  gap: 0.5rem;
  flex-direction: column;
}

.tags-display-container {
  flex-direction: column;
  justify-items: center;
  // justify-content: space-between;
  margin-top: 1rem;
  display: flex;
  gap: 0.5rem;
}

.tag_element_DB {
  background-color: rgba(150, 181, 226, 0.205);
  padding: 0.5rem;
  border-radius: 0.5rem;
  display: flex;
  flex-wrap: wrap;
  font-weight: bolder;
  align-items: center;
  justify-content: center;
}

.tag_element {
  background-color: rgba(150, 181, 226, 0.205);
  padding: 2%;
  border-radius: 0.5rem;
  display: grid;
  grid-template-columns: 80% 20%;
  font-weight: bolder;
  align-items: center;
  justify-items: center;
  gap: 3%;
}

.img_element {
  background-color: rgba(150, 181, 226, 0.205);
  padding: 0.5rem;
  border-radius: 0.5rem;
  display: flex;
  // flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
}

img {
  width: auto; // Allows the image to maintain aspect ratio
  height: 5rem; // Adjust based on your needs
  object-fit: cover;
}

video {
  width: 100%; // Adapts to the width of its container
  max-width: 20rem; // Limits video width while allowing it to be responsive
  height: auto; // Maintain aspect ratio
}

.vid_item {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 5%;
}

textarea {
  min-height: 10rem; // Adjust based on your needs
}

// Buttons
.btnComp {
  background-color: #0062cc;
  border: none;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  cursor: pointer;
}

.btnComp:hover {
  background-color: #004da2;
}

// Responsive adjustments
@media (max-width: 768px) {
  .tags-display-container {
    flex-direction: column;
  }

  video {
    width: 100%; // Ensures video is not wider than the screen on smaller devices
  }
}
</style>

<style>
.addTagBtn {
  height: 100%;
  width: 30%;
  position: relative;
  overflow: hidden;

  .btn-layer {
    height: 100%;
    width: 300%;
    position: absolute;
    margin: auto;
    left: -100%;
    /* background: -webkit-linear-gradient(right, var(--purple), pink, yellow); */
    background: -webkit-linear-gradient(
      right,
      var(--purple),
      var(--dark-blue),
      var(--purple),
      var(--dark-blue)
    );
    border-radius: 5%;
    transition: all 0.4s ease;
  }
  .submit {
    height: 100%;
    width: 100%;
    z-index: 1;
    position: relative;
    background: none;
    border: none;
    color: var(--light);
    padding-left: 0;
    border-radius: 5px;
    font-size: 1em;
    font-weight: 500;
    cursor: pointer;
  }
}
.fieldBtn:hover {
  .btn-layer {
    left: 0;
  }
  .submit {
    text-shadow: 0 0 7px #fff, 0 0 10px #fff, 0 0 21px #fff, 0 0 42px #0fa,
      0 0 82px #0fa, 0 0 92px #0fa, 0 0 102px #0fa, 0 0 151px #0fa;
    animation: neonGlow 1.5s ease-in-out infinite alternate;
  }
}

.removeBtn {
  width: 30%;
  position: relative;
  overflow: hidden;
  display: flex; /* Enable Flexbox */
  justify-content: center; /* Center horizontally */
  align-items: center; /* Center vertically */

  .btn-layer {
    /* height: 100%; */
    /* width: 300%; */
    position: absolute;
    left: -100%;
    background: -webkit-linear-gradient(
      right,
      var(--purple),
      var(--dark-blue),
      var(--purple),
      var(--dark-blue)
    );
    border-radius: 5%;
    transition: all 0.4s ease;
  }

  .submit {
    z-index: 1;
    position: relative;
    background: none;
    border: none;
    /* color: var(--light); */
    color: rgb(89, 14, 14);
    padding: 0; /* Adjust padding as needed */
    border-radius: 5%;
    font-size: 20px; /* Adjust font size as needed */
    /* font-weight: 500; */
    cursor: pointer;
    /* Removed margin and width/height 100% to let flexbox handle centering */
  }
}

.fieldBtn:hover .btn-layer {
  left: 0;
}

.fieldBtn:hover .submit {
  text-shadow: 0 0 7px #fff, 0 0 10px #fff, 0 0 21px #fff, 0 0 42px #0fa,
    0 0 82px #0fa, 0 0 92px #0fa, 0 0 102px #0fa, 0 0 151px #0fa;
  animation: neonGlow 1.5s ease-in-out infinite alternate;
}

.vid_RemoveBtn {
  /* width: 30%; */
  position: relative;
  overflow: hidden;
  display: flex; /* Enable Flexbox */
  justify-content: center; /* Center horizontally */
  align-items: center; /* Center vertically */

  .btn-layer {
    height: 100%;
    width: 300%;
    position: absolute;
    left: -100%;
    background: -webkit-linear-gradient(
      right,
      var(--purple),
      var(--dark-blue),
      var(--purple),
      var(--dark-blue)
    );
    border-radius: 5%;
    transition: all 0.4s ease;
  }

  .submit {
    z-index: 1;
    position: relative;
    background: none;
    border: none;
    color: var(--light);
    /* color: rgb(89, 14, 14); */
    padding: 0; /* Adjust padding as needed */
    border-radius: 5%;
    font-size: 20px; /* Adjust font size as needed */
    /* font-weight: 500; */
    cursor: pointer;
    /* Removed margin and width/height 100% to let flexbox handle centering */
  }
}

.fieldBtn:hover .btn-layer {
  left: 0;
}

.fieldBtn:hover .submit {
  text-shadow: 0 0 7px #fff, 0 0 10px #fff, 0 0 21px #fff, 0 0 42px #0fa,
    0 0 82px #0fa, 0 0 92px #0fa, 0 0 102px #0fa, 0 0 151px #0fa;
  animation: neonGlow 1.5s ease-in-out infinite alternate;
}
</style>
