<template>
  <div class="filter-container roundBorderSmall glass">
    <h3>{{ props.filter_title }}</h3>
    <div class="checkbox-group">
      <label
        v-for="genre in filter_data.genres.slice(0, 5)"
        :key="genre.id"
        :class="{ 'filter-label': true, glow: true, checked: genre.checked }"
      >
        <input
          @checked="returnFiltersData"
          type="checkbox"
          v-model="genre.checked"
          :name="genre.name"
          :value="genre.name"
        />
        <span>{{ genre.name }}</span>
      </label>
    </div>
    <div class="search-container">
      <input
        v-model="filter_data.searchInput"
        type="text"
        id="gameSearch"
        name="gameSearch"
        placeholder="Chercher par genre"
      />
    </div>
    <div class="sort-container">
      <select v-model="filter_data.selectedSort">
        <option disabled value="">Trier par :</option>
        <option
          v-for="sort in filter_data.sortList"
          :key="sort.value"
          :value="sort.value"
        >
          {{ sort.label }}
        </option>
      </select>
    </div>
    <v-btn elevation="2" variant="tonal" class="" @click="returnFiltersData">
      Rechercher
    </v-btn>
  </div>
</template>

<script setup>
import { onMounted, reactive, watch } from "vue";
// import SearchComponent from "../game/SearchComponent.vue";
import { getAll, filterSearchedTags } from "../../JS/fetchServices";

const props = defineProps({
  filter_title: String,
  onFilter: Function,
});
const emit = defineEmits(["update:filters"]);

const filter_data = reactive({
  genres: [
    { id: 1, name: "simulation", checked: false },
    { id: 2, name: "chocolate", checked: false },
  ],
  placeholder_title: "Chercher par filtre",
  selectedSort: "",
  searchInput: "",
  filter_results: Object,
  sortList: [
    { label: "Trier par : ", value: "" },
    { label: "Développeur", value: "devName" },
    { label: "Date de sortie", value: "releaseDate" },
    { label: "Note d'évaluation", value: "ratingAverage" },
    { label: "Titre", value: "title" },
  ],
});

async function getTags() {
  const fetchedGenres = await getAll("tags");
  filter_data.genres = fetchedGenres.map((genre) => ({
    ...genre,
    checked: false,
  }));
}


function returnFiltersData() {
  let selectedGenres = filter_data.genres
    .filter((genre) => genre.checked)
    .map((genre) => genre.name);

  // const trimmedSearchInput = filter_data.searchInput.trim();

  // if (trimmedSearchInput) {
  //   selectedGenres.push(trimmedSearchInput);
  // }

  let data = {
    tags: selectedGenres,
    sorting: filter_data.selectedSort,
  };

  emit("update:filters", data);
}

watch(() => filter_data.searchInput, async (newVal, oldVal) => {
  const fetchedGenres = await filterSearchedTags(newVal);
  const updatedGenres = fetchedGenres.map((fetchedGenre) => {
    const existingGenre = filter_data.genres.find(genre => genre.id === fetchedGenre.id);
    return {
      ...fetchedGenre,
      checked: existingGenre ? existingGenre.checked : false,
    };
  });

  // Keep already checked genres that are not included in the new search result
  const checkedGenresNotIncluded = filter_data.genres.filter(genre => genre.checked && !updatedGenres.find(fetchedGenre => fetchedGenre.id === genre.id));

  // Combine and sort so checked genres are first
  filter_data.genres = [...checkedGenresNotIncluded, ...updatedGenres].sort((a, b) => b.checked - a.checked);
});


watch(
  () => filter_data.genres.map((genre) => genre.checked),
  (newVal, oldVal) => {
    // change the game list once any of the tags have been checked
    if (newVal.some((checked, index) => checked !== oldVal[index])) {
      returnFiltersData();
    }
  },
  { deep: true }
);

onMounted(async () => {
  await getTags();
});
</script>

<style scoped>
.checked {
  background-color: #0aade8;
  color: #13aebf;
}
input[type="checkbox"]:checked + .filter-label {
  background-color: #0aade8;
  color: #13aebf;
}

input[type="checkbox"]:checked + .filter-label:before {
  content: "\2713";
  color: #10cde2;
  font-size: 14px;
  text-align: center;
  line-height: 20px;
}

input[type="checkbox"] {
  display: none;
}

.filter-label {
  background: rgba(77, 77, 77, 0.8);
  padding: 5px 10px;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: start;
  position: relative;
  cursor: pointer;
  transition: background 0.3s ease;
}

.filter-label:hover {
  background: rgba(102, 192, 244, 0.2);
}

.filter-label:before {
  content: "";
  width: 20px;
  height: 20px;
  margin-right: 10px;
  background-color: #ccc;
  border-radius: 3px;
  position: relative;
  display: inline-block;
}

/*  */
.filter-container {
  display: flex;
  flex-direction: column;
  background: rgba(0, 0, 0, 0.8);
  color: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
  margin: 2% auto;
  width: auto;
  gap: 10px;
}

.filter-container h3 {
  color: #66c0f4;
  margin-bottom: 15px;
}

.checkbox-group {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
  gap: 10px;
}

.sort-container {
  margin-top: 5%;
}

.sort-container select {
  width: 100%;
  padding: 10px;
  border-radius: 4px;
  border: 2px solid #303c3f;
  background: rgba(68, 108, 137, 0.8);
  color: #ffffff;
}

.search-container {
  margin-top: 20px; /* Adjust space above search bar as needed */
}

#gameSearch {
  width: 100%;
  padding: 10px;
  border-radius: 4px;
  border: 2px solid #303c3f;
  background: rgba(4, 148, 251, 0.256);
  color: #ffffff;
}
#gameSearch::placeholder {
  color: #ffffff; /* Custom color for placeholder text */
  opacity: 1;
}
</style>
