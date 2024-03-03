<template>
  <div id="upload_game">
    <h2>Upload Your Game</h2>
    <div>
      <label for="title">Game Title:</label>
      <input
        type="text"
        id="title"
        v-model="state.gameTitle"
        placeholder="Enter game title"
      />
    </div>
    <div>
      <label for="tags">Tags:</label>
      <input
        type="text"
        id="tags"
        v-model="state.tags"
        placeholder="action, relax, wholesome"
      />
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
        Upload Images (Max {{ state.maxImgs }})
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
        Upload Videos (Max {{ state.maxVids }})
      </button>
      <div v-if="state.videoFiles.length">
        <p>Selected videos:</p>
        <ol>
          <li v-for="(file, index) in state.videoFiles" :key="index">
            {{ file.name }}
            <video controls :src="file.url" :alt="file.name" style="width: 25%"></video> 
            <button @click="removeFile(index, state.videoFiles)">X</button>
          </li>
        </ol>
      </div>
    </div>
    <button @click="submitGame">Submit Game</button>
  </div>
</template>
<script setup>
import { reactive } from "vue";

const state = reactive({
  gameTitle: "",
  tags: "",
  gameFile: null,
  gameFilePath: "",
  imageFiles: [],
  videoFiles: [],
  maxImgs: 10,
  maxVids: 2,
});

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
  fileInput.accept = "video/*"; // Accept only video files
  fileInput.onchange = (e) => {
    const newFiles = Array.from(e.target.files);
    const availableSlots = state.maxVids - state.videoFiles.length;

    if (newFiles.length > availableSlots) {
      alert(`You can only upload a maximum of ${availableSlots} more video(s).`);
      return;
    }

    const newVideoFiles = newFiles.slice(0, availableSlots).map(file => ({
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
    const availableSlots = state.maxImgs - state.imageFiles.length;

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

const submitGame = () => {
  console.log("Submitting:", {
    gameTitle: state.gameTitle,
    tags: state.tags.trim().split(","),
    gameFilePath: state.gameFilePath,
    imageFiles: state.imageFiles,
  });
};

const removeFile = (indexToRemove, fileList) => {
  URL.revokeObjectURL(fileList[indexToRemove].url);
  if (fileList === state.imageFiles) {
    state.imageFiles.splice(indexToRemove, 1);
  } else if (fileList === state.videoFiles) {
    state.videoFiles.splice(indexToRemove, 1);
  }
};
</script>

<style scoped>
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
</style>
