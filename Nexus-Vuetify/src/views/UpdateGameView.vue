<template>
  <div id="pageContainer">
    <h2>
      {{ props.mode === "create" ? "Ajouter un Jeu" : "Modifier un Jeu" }}
    </h2>
    <div v-if="state.errorMessage" class="error-message">
      {{ state.errorMessage }}
    </div>
    <!-- <div>
      <label for="title">Titre du Jeu</label> 
      <input
        class="title_readonly"
        type="text"
        id="title"
        :value="
          'Titre du Jeu : ' +
          (state.gameObject.title || 'Entrer le titre du jeu...')
        "
        readonly
      />
    </div> -->

    <div>
      <label class="title_readonly" id="title">
        Titre du Jeu :
        {{ state.gameObject.title || "Entrer le titre du jeu..." }}
      </label>
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
              @click="addClickedTagToTagsArray(tag)"
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
              {{ tag.name }}
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
        :placeholder="
          state.gameObject.description ||
          'Mon jeu est super cool ;) parce que...'
        "
        v-on:input="validateDescriptionLength"
        v-model="state.gameObject.description"
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
        <!-- <p>File Size: {{ fileSize }} KB</p> -->
        <p>File Type: ZIP</p>
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
import { useRoute } from "vue-router";
import storageManager from "../JS/localStorageManager";
import { reactive, defineProps, computed, onMounted, ref } from "vue";
import {
  create,
  updateData,
  deleteData,
  getAll,
  getOne,
} from "../JS/fetchServices.js";

// import { getStorage, ref as firebaseRef, uploadBytes } from "firebase/storage";
import {
  getStorage,
  ref as firebaseRef,
  listAll,
  uploadBytes,
  getDownloadURL,
} from "firebase/storage";
const storage = getStorage();
const filesAndFolders = ref([]);
const route = useRoute();
const validImageTypes = ["image/jpeg", "image/png"];
const validFileTypes = [
  "application/x-msdownload", // MIME type for .exe files
  "application/java-archive", // MIME type for .jar files
];

const props = defineProps({
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
    default: "update",
  },
});

const state = reactive({
  gameId: "",
  gameObject: {},
  pageTitle: props.pageTitle,
  gameTitle: "",
  tags: "",
  tagsArray: [],
  tagsArray_original: [],
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

/*******************************************************************/
/****************************** LIFE CYCLE ***********************/
/*******************************************************************/

onMounted(async () => {
  try {
    await setDefaultValues();
    await eraseGamesTags();
  } catch (error) {
    console.error("Error setting default values:", error);
  }
});

const removeItem = (indexToRemove, fileList) => {
  URL.revokeObjectURL(fileList[indexToRemove].url);
  fileList.splice(indexToRemove, 1);
};

async function setDefaultValues() {
  const route = useRoute();
  const gameToUpdateId = route.params.gameToUpdateId;
  state.gameId = gameToUpdateId;
  console.log("state.gameId : ", state.gameId);

  let data = await getOne("games", "id", gameToUpdateId);
  if (data) {
    state.gameObject = data[0];
    console.log("state.gameObject : ", state.gameObject);
    state.tagsArray = state.gameObject.tags;
    state.tagsArray_original.push(JSON.parse(JSON.stringify(data)));

    // state.tagsArray = state.gameObject.tags.map((tag) => tag.name);
    console.log("Updated tagsArray with names: ", state.tagsArray);
    await getTagsFrom_DB();

    await fetchFiles(state.gameObject.id);
    categorizeFiles(filesAndFolders.value);
  }
}

/*******************************************************************/
/****************************** OPEN BROWSER ***********************/
/*******************************************************************/

const openFileBrowser = () => {
  console.log("selected game file");
  const fileInput = document.createElement("input");
  fileInput.type = "file";
  fileInput.onchange = (e) => {
    const file = e.target.files[0];
    console.log("selected game file : ", file);
    const fileUrl = URL.createObjectURL(file);

    state.gameFile = {
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
  console.log("hi");
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
    const newImageFiles = newFiles.slice(0, availableSlots).map((file) => ({
      ...file,
      url: URL.createObjectURL(file),
    }));

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

/*******************************************************************/
/***************************** FIREBASE *****************************/
/*******************************************************************/

async function fetchFilesAndFolders(folderPath) {
  const folderRef = firebaseRef(storage, folderPath);
  try {
    const response = await listAll(folderRef);
    // Fetch details for individual files
    const filesDetails = await Promise.all(
      response.items.map(async (itemRef) => {
        const url = await getDownloadURL(itemRef);
        return {
          type: "file",
          item: itemRef,
          name: itemRef.name,
          url: url,
        };
      })
    );

    // Recursively fetch contents for each subfolder
    const foldersDetails = await Promise.all(
      response.prefixes.map(async (subFolderRef) => {
        const subFolderPath = subFolderRef.fullPath;
        return {
          type: "folder",
          name: subFolderRef.name,
          path: subFolderPath,
          contents: await fetchFilesAndFolders(subFolderPath), // Recursive call
        };
      })
    );

    return [...filesDetails, ...foldersDetails];
  } catch (error) {
    console.error("Error fetching files and folders:", error);
    return []; // Return an empty array in case of error
  }
}

// Example of how to use fetchFilesAndFolders within a Vue component
const fetchFiles = async (gameID) => {
  let folderPath = `Games/${gameID}`;
  filesAndFolders.value = await fetchFilesAndFolders(folderPath);
  console.log("filesAndFolders.value", filesAndFolders.value);
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

  for (let i = 0; i < state.MAX_IMG_LIST; i++) {
    const imageElement = state.imageFiles[i];
    const fileName = `${gameId}_${i + 1}.png`;

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

const categorizeFiles = async (fetchedStructure) => {
  state.imageFiles = [];
  state.imageStoreObject = [];
  state.gameFile = null;

  const zipFile = fetchedStructure.find((file) => file.name.endsWith(".zip"));
  if (zipFile) {
    console.log("ZIP file found at root:", zipFile);
    state.gameFile = zipFile;
    // const blob = await fetchZipFileBlob(zipFile.url);
    // const unzippedFiles = await unzipBlob(blob);
    // console.log("unzippedFiles : ", unzippedFiles);
  }

  const mediaFolder = fetchedStructure.find(
    (folder) => folder.name === "media"
  );
  if (!mediaFolder || !mediaFolder.contents) return;

  mediaFolder.contents.forEach((file) => {
    if (file.name.endsWith("_Store.png")) {
      state.imageStoreObject.push(file);
    } else if (!file.name.endsWith("0.png") && file.type === "file") {
      state.imageFiles.push(file);
    }
  });

  console.log("Image Files:", state.imageFiles);
  console.log("Store Image:", state.imageStoreObject);
  console.log(
    "Game File (ZIP):",
    state.gameFile ? state.gameFile.url : "No ZIP file found"
  );
};

async function fetchZipFileBlob(url) {
  const response = await fetch(url);
  if (!response.ok) throw new Error("Failed to fetch ZIP file");
  return response.blob();
}

async function unzipBlob(blob) {
  const zip = new JSZip();
  const zipContents = await zip.loadAsync(blob);
  const fileContents = {};

  // Iterate over each file and extract its contents
  zipContents.forEach(async (relativePath, fileEntry) => {
    if (!fileEntry.dir) {
      // Ensure it's not a directory
      const fileData = await fileEntry.async("blob"); // Get the file as a Blob
      fileContents[relativePath] = fileData;
      // You can also use fileEntry.async("string") if you know it's a text file

      console.log(`Extracted file: ${relativePath}`); // Log file path
      // Process the file here (e.g., display it, upload it, etc.)
    }
  });

  return fileContents; // Returns an object with filenames as keys and their contents as Blob
}
/*******************************************************************/
/***************************** GAMES *****************************/
/*******************************************************************/

const updateGame = async () => {
  let jsonObject = {
    id: state.gameId,
    description:state.gameObject.description,
    tokens: {
      access_token: storageManager.getAccessToken(),
      refresh_token: storageManager.getRefreshToken(),
    },
  };
  let wasGameCreated = await updateData("games", jsonObject);
  console.log("wasGameCreated:", wasGameCreated);
};

// const deleteGame = async () => {
//   const gameId = await get_CreatedGameID();
//   if (!gameId) {
//     console.error("Failed to get game ID for deletion");
//     return;
//   }

//   const response = await deleteData("games", { id: gameId });

//   if (response.ok) {
//     console.log("Game was successfully deleted");
//   } else {
//     console.error("Failed to delete the game");
//   }
// };

// async function get_CreatedGameID() {
//   const filters = {
//     title: state.gameTitle,
//     developerID: props.gameToUpdateId,
//     description: state.description,
//   };

//   try {
//     let game = await getAllMatching("games", filters);
//     if (game.length > 0) {
//       console.log("created game :", game);
//       return game[0].id;
//     } else {
//       console.log("No games found with the specified criteria.");
//       return null;
//     }
//   } catch (error) {
//     console.error("Error fetching game ID:", error);
//     return null;
//   }
// }

/*******************************************************************/
/***************************** TAGS *****************************/
/*******************************************************************/

function updateTagsArray() {
  let newTags = state.tags
    .split(",")
    .map((tag) => tag.trim())
    .filter(Boolean);

  newTags.forEach((newTagName) => {
    const isTagPresent = state.tagsArray.some((tag) => tag.name === newTagName);

    if (!isTagPresent) {
      state.tagsArray.push({ name: newTagName });
    } else {
      console.log(`${newTagName} already added.`);
    }
  });

  // Clear the input string after processing
  state.tags = "";
}

function addClickedTagToTagsArray(clickedTag) {
  const isTagPresent = state.tagsArray.some((tag) => tag.id === clickedTag.id);

  if (!isTagPresent) {
    state.tagsArray.push(clickedTag);
  } else {
    console.log(`${clickedTag.name} is already added.`);
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

const eraseGamesTags = async () => {
  const gameId = state.gameId;
  if (!gameId) {
    console.error("Failed to get game ID");
    return false;
  }

  let game = await getOne("games", "id", gameId);

  if (game) {
    const originalTags = game[0].tags;

    // Find tags that are in the original list but not in the current tags array
    const tagsToRemove = originalTags.filter(
      (originalTag) =>
        !state.tagsArray.some((newTag) => newTag.id === originalTag.id)
    );

    console.log("originalTags: ", originalTags);
    console.log("tagsToRemove: ", tagsToRemove);
    console.log("state.tagsArray: ", state.tagsArray);

    let gamesTagsList = await getAll("gamestags", "gameId", gameId);
    if (gamesTagsList) {
      console.log("gamesTagsList : ", gamesTagsList);

      for (const tagToRemove of tagsToRemove) {
        const gamesTagsListElement = gamesTagsList.find(
          (element) => element.tagId === tagToRemove.id
        );
        if (gamesTagsListElement) {
          console.log("gamesTagsListElement : ", gamesTagsListElement)
          // await deleteGameTag(gamesTagsListElement);
        }
      }
    }

    // Remove each tag not present in the new set
    // for (const tag of tagsToRemove) {
    // await deleteTag(tag.id);
    // console.log(`Tag removed: ${tag.name}`);
    // }

    return true; // If the function completes successfully
  }
};

async function deleteGameTag(gamesTagsListElement) {

  let delete_data = {
    id: gamesTagsListElement.id,
    tokens: {
      access_token: storageManager.getAccessToken(),
      refresh_token: storageManager.getRefreshToken(),
    },
  };
  console.log("delete_data : ", delete_data);

  const response = await deleteData("gamestags", delete_data);
  if (response.success) {
    console.log(`Tag ${gamesTagsListElement.tagId} removed successfully.`);
  } else {
    console.error(`Failed to remove tag ${gamesTagsListElement.tagId}.`);
  }
}
const createTags = async () => {
  const gameId = state.gameId;
  if (!gameId) {
    console.error("Failed to get game ID");
    return false;
  }

  let allTagsCreated = true;
  for (const tag of state.tagsArray) {
    const jsonObject = {
      name: tag.name,
      gameId: gameId,
    };
    const result = await create("tags", jsonObject);
    console.log("Tag was created:", tag.name);
    // if (result.isSuccessful == false) {
    //   allTagsCreated = false;
    //   break;
    // }
  }
  return allTagsCreated;
};

/*******************************************************************/
/***************************** VALIDATE *****************************/
/*******************************************************************/

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

const formatData = () => {
  // console.log("state.gameObject.description.trim().length : ", state.gameObject.description.trim().length)
  if (!state.gameObject.title) {
    state.errorMessage = "Game title is required.";
    return false;
  } else if (state.tagsArray.length < state.MIN_TAG) {
    state.errorMessage = `At least ${state.MIN_TAG} tags are required.`;
    return false;
  } else if (state.imageFiles.length < state.MIN_IMG_LIST) {
    state.errorMessage = `At least ${state.MIN_IMG_LIST} images are required.`;
    return false;
  } else if (
    state.gameObject.description.trim().length < state.MIN_DESC_LENGTH
  ) {
    state.errorMessage = `Description between  ${state.MIN_DESC_LENGTH} and  ${state.MAX_DESC_LENGTH} characters is required.`;
    return false;
  } else if (state.imageStoreObject.length < state.MAX_IMG_STORE) {
    state.errorMessage = `At least ${state.MAX_IMG_STORE} images are required for the store.`;
    return false;
  } else if (state.gameFile == null) {
    state.errorMessage = `A Game File is required`;
    return false;
  } else {
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

/*******************************************************************/
/************************* UPDATE GAMES AND TAGS ******************/
/*******************************************************************/

const update_gamesAndTags = async () => {
  await updateGame();

  const tagsCreationResult = await createTags();
  if (tagsCreationResult.isSuccessful ==false) {
    console.error(
      "Game couldn't be added because tags were not successfully created."
    );
  } else {
    console.log("Game creation succeeded.");
    await eraseGamesTags();
  }
};

const submitGame = async () => {
  if (formatData()) {
    await update_gamesAndTags();
    await uploadImageFiles(state.gameId);
    await uploadZipFile(state.gameId);

  }
};
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
  width: 100%;
}

.title_readonly {
  background-color: transparent;
  text-align: center;
  font-size: 2em;
  font-weight: bolder;
  /* margin-bottom: 0.5rem; */
  /* color: #0062cc; */
  /* margin : 0.5em 0; */
}

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
  gap: 0.5rem;
  flex-direction: column;
}

.tags-display-container {
  flex-direction: column;
  justify-items: center;

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
  align-items: center;
  justify-content: space-between;
}

img {
  width: auto;
  height: 5rem;
  object-fit: cover;
}

video {
  width: 100%;
  max-width: 20rem;
  height: auto;
}

.vid_item {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 5%;
}

textarea {
  min-height: 10rem;
}

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

@media (max-width: 768px) {
  .tags-display-container {
    flex-direction: column;
  }

  video {
    width: 100%;
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
