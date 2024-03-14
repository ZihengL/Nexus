<template>
  <div class="filter-container roundBorderSmall">
    <h3>{{ props.filter_title }}</h3>
    <div class="tag">
      <h4>{{ filter_data.tags_container_title }}</h4>
      <div class="checkbox-group">
        <!--  -->
        <label
          v-for="genre in filter_data.genres.slice(0, 6)"
          :key="genre.id"
          :class="{ 'filter-label': true, glow: true, checked: genre.checked }"
          class="roundBorderSmall label"
        >
          <input
            @change="handleUserInteraction"
            type="checkbox"
            v-model="genre.checked"
            :name="genre.name"
            :value="genre.name"
          />
          <span>{{ genre.name }}</span>
        </label>
      </div>
      <div class="search-container roundBorderSmall glass">
        <input
          v-model="filter_data.searchInput"
          type="text"
          id="gameSearch"
          name="gameSearch"
          placeholder="Chercher par genre"
        />
      </div>
    </div>

    <div class="sort-container">
      <h4>{{ filter_data.sort_container_title }}</h4>
      <select v-model="filter_data.selectedSort" class="roundBorderSmall glass">
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

    <!-- <v-btn elevation="2" variant="tonal" class="" @click="returnFiltersData">
        Rechercher
      </v-btn> -->

    <btnComp :contenu="'Rechercher'" @toggle-btn="handleUserInteraction" />
  </div>
</template>

<script setup>
import { onMounted, reactive, watch } from "vue";
// import SearchComponent from "../game/SearchComponent.vue";
import { getAll, filterSearchedTags, getTags } from "../../JS/fetchServices";
import btnComp from "../btnComponent.vue";
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
  tags_container_title: "Genres",
  sort_container_title: "Trier Par :",
  userInteracted: false,

  sortList: [
    //{ label: "Trier par : ", value: "" },
    { label: "Développeur", value: "devName" },
    { label: "Date de sortie", value: { releaseDate: false } },
    { label: "Note d'évaluation", value: { ratingAverage: false } },
    { label: "Titre", value: { title: true } },
  ],
});

function handleUserInteraction() {
  filter_data.userInteracted = true;
  returnFiltersData();
}


async function getAllTags() {
  const fetchedGenres = await getTags();
  //console.log('all tags : ', fetchedGenres);
  filter_data.genres = fetchedGenres.map((genre) => ({
    ...genre,
    checked: false,
  }));
}

function returnFiltersData() {
  let selectedGenres = filter_data.genres
    .filter((genre) => genre.checked)
    .map((genre) => genre.name);

  let data = {
    tags: selectedGenres,
    sorting: filter_data.selectedSort,
  };

    emit("update:filters", data);
  
}

watch(
  () => filter_data.searchInput,
  async (newVal, oldVal) => {
    const fetchedGenres = await filterSearchedTags(newVal);
    const updatedGenres = fetchedGenres.map((fetchedGenre) => {
      const existingGenre = filter_data.genres.find(
        (genre) => genre.id === fetchedGenre.id
      );
      return {
        ...fetchedGenre,
        checked: existingGenre ? existingGenre.checked : false,
      };
    });

    // Keep already checked genres that are not included in the new search result
    const checkedGenresNotIncluded = filter_data.genres.filter(
      (genre) =>
        genre.checked &&
        !updatedGenres.find((fetchedGenre) => fetchedGenre.id === genre.id)
    );

    // Combine and sort so checked genres are first
    filter_data.genres = [...checkedGenresNotIncluded, ...updatedGenres].sort(
      (a, b) => b.checked - a.checked
    );
  }
);

watch(
  () => filter_data.genres.map((genre) => genre.checked),
  (newVal, oldVal) => {
    if (filter_data.userInteracted) { // change the game list once any of the tags have been checked
    if (newVal.some((checked, index) => checked !== oldVal[index])) {
      returnFiltersData();
    }}
   
  },
  { deep: true }
);

onMounted(async () => {
  await getAllTags();
});
</script>

<style lang="scss">
.label {
  /* From https://css.glass */
  background: rgba(255, 255, 255, 0.06);
  //border-radius: 0px;
  box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
  backdrop-filter: blur(0px);
  -webkit-backdrop-filter: blur(0px);
  border: 1px solid rgba(255, 255, 255, 0);
}
.label:hover,
.checked {
  /* From https://css.glass */
  background: rgba(255, 255, 255, 0.25);
  //border-radius: 0px;
  box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
  backdrop-filter: blur(0px);
  -webkit-backdrop-filter: blur(0px);
  border: 1px solid rgba(255, 255, 255, 0);
}
.checked {
  color: var(--light);
  text-shadow: 0 0 1.5px #ffffffa1, 0 0 2.5px #ffffffa1, 0 0 5px #ffffffa1,
    0 0 10px var(--marin-b), 0 0 20px var(--marin-b), 0 0 25px var(--marin-b),
    0 0 30px var(--marin-b), 0 0 33px var(--marin-b);
  animation: neonGlow 0.5s ease-in-out infinite alternate;
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
  padding: 5px 10px;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: start;
  position: relative;
  cursor: pointer;
}

/*  */
.filter-container {
  display: flex;
  flex-direction: column;
  color: #fff;
  padding: 20px;
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
  color: var(--light-trans);
}
.sort-container select option {
  color: rgba(0, 0, 0, 0.674);
}

.search-container {
  margin-top: 20px; /* Adjust space above search bar as needed */
}

#gameSearch {
  width: 100%;
  padding: 10px;
  /*border: 2px solid #303c3f;
  background: rgba(4, 148, 251, 0.256);*/
  color: var(--light-trans);
}
#gameSearch::placeholder {
  color: var(--light-trans); /* Custom color for placeholder text */
  opacity: 1;
}
</style>
