<template>
  <div class="pagination glass">

    <div class="fieldBtn prev">
      <div class="btn-layer"></div>
      <v-btn density="default" class="submit glow" @click="prevPage"> &laquo; Précédent
      </v-btn>
    </div>

    <a v-for="pageNumber in totalPages" :key="pageNumber" :class="{ active: currentPage === pageNumber }"
      @click="changePage(pageNumber)">{{ pageNumber }}</a>
    <div class="fieldBtn next">
      <div class="btn-layer"></div>
      <v-btn density="default" class="submit glow" @click="nextPage">Suivant &raquo;
      </v-btn>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      games: [], // Vos jeux à paginer
      currentPage: 1,
      pageSize: 6 // Nombre de jeux par page
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.games.length / this.pageSize);
    },
    displayedGames() {
      const start = (this.currentPage - 1) * this.pageSize;
      const end = start + this.pageSize;
      return this.games.slice(start, end);
    }
  },
  methods: {
    changePage(pageNumber) {
      this.currentPage = pageNumber;
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    }
  }
};
</script>

<style lang="scss">
.pagination {
  display: flex;
  margin-top: 20px;
  padding: 5%;

}

.pagination a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
  transition: background-color .3s;
  border: 1px solid #ddd;
}

.pagination a.active {
  background-color: #4CAF50;
  color: white;
  border: 1px solid #4CAF50;
}

.pagination a:hover:not(.active) {
  background-color: #ddd;
}

.pagination .prev,
.pagination .next {
  background-color: #f1f1f1;
  color: black;

}

.pagination .next {
  margin: 10px;
}

@media screen and (max-width: 600px) {
  .pagination a {
    float: none;
  }

  .pagination .prev,
  .pagination .next {
    display: block;
  }
}
</style>
