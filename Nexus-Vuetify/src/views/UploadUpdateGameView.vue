<template>
  <div id="upload_game">
    <h2>{{ props.pageTitle }}</h2>
    <div v-if="state.errorMessage" class="error-message">
      {{ state.errorMessage }}
    </div>
    <div>
      <label for="title">Game Title*</label>
      <input
        type="text"
        id="title"
        v-model="state.gameTitle"
        placeholder="Enter game title"
      />
    </div>
    <div>
      <label for="tags">Tags:*</label>
      <input
        type="text"
        id="tags"
        v-model="state.tags"
        placeholder="action, relax, wholesome"
        @input="updateTagsArray"
      />
    </div>
    <div>
      <p class="description">Description : *</p>
      <textarea
        class="description"
        name="description"
        placeholder="Enter a description..."
        v-on:input="validateDescriptionLength"
        v-model="state.description"
      ></textarea>
      <p id="charCount">{{ charCountText }}</p>
    </div>
    <div>
      <button @click="openFileBrowser">Select Game File</button>
      <div v-if="state.gameFile">
        <p>Selected Game File: {{ state.gameFile.name }}</p>
        <!-- <p>
          File URL:
          <a :href="state.gameFile.url" target="_blank">{{
            state.gameFile.url
          }}</a>
        </p> -->
        <p>File Size: {{ (state.gameFile.size / 1024).toFixed(2) }} KB</p>
        <p>File Type: {{ state.gameFile.type }}</p>
      </div>
    </div>
    <div>
      <button @click="openImageBrowser">
        Upload Images (Max {{ state.MAX_IMGS }})
      </button>
      <div v-if="state.imageFiles.length">
        <p>Selected Images:</p>
        <ol>
          <li v-for="(file, index) in state.imageFiles" :key="index">
            {{ file.name }}
            <img :src="file.url" :alt="file.name" style="width: 100px" />
            <button @click="removeFile(index, state.imageFiles)">X</button>
          </li>
        </ol>
      </div>
    </div>

    <div>
      <button @click="openVideoBrowser">
        Upload Videos (Max {{ state.MAX_VIDS }})
      </button>
      <div v-if="state.videoFiles.length">
        <p>Selected videos:</p>
        <ol>
          <li v-for="(file, index) in state.videoFiles" :key="index">
            {{ file.name }}
            <video
              controls
              :src="file.url"
              :alt="file.name"
              style="width: 25%"
            ></video>
            <button @click="removeFile(index, state.videoFiles)">X</button>
          </li>
        </ol>
      </div>
    </div>
    <button @click="submitGame">Submit Game</button>
  </div>
</template>
<script setup>
import { reactive, defineProps, computed } from "vue";
import { create, getAllMatching, deleteData } from "../JS/fetchServices.js";
import { getStorage, ref, uploadBytes, getDownloadURL } from "firebase/storage";

const props = defineProps({
  gameObject: Object,
  pageTitle: {
    type: String,
    default: "Add or Update Game",
  },
  gameTitle: {
    type: String,
  },
  tagsArray: {
    type: Array,
  },
  gameFile: Object,
  gameFilePath: String,
  imageFiles: [],
  videoFiles: [],
  developerID: {
    type: Number,
    default: 5,
  },
});

const state = reactive({
  gameTitle: "",
  tags: "",
  tagsArray: [],
  gameFile: null,
  gameFilePath: "",
  imageFiles: [],
  videoFiles: [],
  MIN_IMG: 0,
  MAX_IMGS: 10,
  MAX_VIDS: 2,
  MIN_TAG: 1,
  MIN_DESC_LENGTH: 1,
  MAX_DESC_LENGTH: 500,
  errorMessage: "",
  creation_date: new Date().toISOString().replace("T", " ").substring(0, 16),
  description: "",
});

const charCountText = computed(() => {
  let currentLength = state.description.length;
  return `${currentLength}/${state.MAX_DESC_LENGTH} characters`;
});

function validateDescriptionLength() {
  if (state.description.length > state.MAX_DESC_LENGTH) {
    state.description = state.description.substring(0, state.MAX_DESC_LENGTH);
  }
}

const openFileBrowser = () => {
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
  const fileInput = document.createElement("input");
  fileInput.type = "file";
  fileInput.multiple = true;
  fileInput.accept = "image/*";
  fileInput.onchange = (e) => {
    const newFiles = Array.from(e.target.files);
    const availableSlots = state.MAX_IMGS - state.imageFiles.length;

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

const removeFile = (indexToRemove, fileList) => {
  URL.revokeObjectURL(fileList[indexToRemove].url);
  if (fileList === state.imageFiles) {
    state.imageFiles.splice(indexToRemove, 1);
  } else if (fileList === state.videoFiles) {
    state.videoFiles.splice(indexToRemove, 1);
  }
};

const formatData = () => {
  if (!state.gameTitle) {
    state.errorMessage = "Game title is required.";
    return false;
  } else if (state.tagsArray.length < state.MIN_TAG) {
    (state.errorMessage = "At least"), state.MIN_TAG, "tags are required.";
    return false;
  } else if (state.imageFiles.length < state.MIN_IMG) {
    (state.errorMessage = "At least "), state.MIN_IMG, "images are required.";
    return false;
  } else if (!state.description.trim()) {
    state.errorMessage = "Description is required.";
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

function updateTagsArray() {
  state.tagsArray = state.tags
    .split(",")
    .map((tag) => tag.trim())
    .filter(Boolean);

  // console.log("updating tags array : ", state.tagsArray);
}

const create_gameAndTags = async () => {
  await createGame();

  const tagsCreationResult = await createTags();
  if (!tagsCreationResult) {
    console.error(
      "Game couldn't be added because tags were not successfully created."
    );
    await deleteGame();
  } else {
    console.error("Game creation failed.");
  }
};

const createGame = async () => {
  let jsonObject = {
    title: state.gameTitle,
    developerID: props.developerID,
    description: state.description,
    releaseDate: state.creation_date,
    ratingAverage: 0,
  };
  let wasGameCreated = await create("games", jsonObject);
  console.log("wasGameCreated:", wasGameCreated);
};

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
    if (!result) {
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

  const response = await deleteData("games", { id: gameId });

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
</script>

<style scoped>
textarea {
  background-color: #555;
  color: white;
  padding: 10px;
  border: 2px solid #777;
  border-radius: 5px;
  width: 50%;
  box-sizing: border-box;
  margin: 5px 0;
}

textarea:focus {
  outline: none;
  border-color: #007bff;
}

img {
  width: 100px;
}

#upload_game {
  background-color: #333;
  padding: 20px;
  border-radius: 8px;
}

#upload_game * {
  color: white;
}

#upload_game button {
  background-color: #0062cc;
  border: none;
  color: white;
  padding: 10px 20px;
  margin: 5px;
  border-radius: 5px;
  cursor: pointer;
}

#upload_game button:hover {
  background-color: #004da2;
}

#upload_game input[type="text"] {
  background-color: #555;
  border: none;
  color: white;
  padding: 10px;
  margin: 5px 0;
  border-radius: 5px;
}

#upload_game input[type="text"]:focus {
  outline: 2px solid #007bff;
}

.error-message {
  color: #ff3860;
  background-color: #ffe5e7;
  padding: 10px;
  margin-bottom: 20px;
  border-radius: 5px;
  border: 1px solid #ff3860;
}
</style>
