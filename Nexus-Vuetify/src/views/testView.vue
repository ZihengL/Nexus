<template>
  <div class="test_view">
    TEST
    <!-- Using Vuetify's v-button component -->
    <v-btn class="bordered-btn" @click="getAll">Get All</v-btn>
    <v-btn class="bordered-btn" @click="getOne">Get One</v-btn>
    <v-btn class="bordered-btn" @click="login">Login</v-btn>
    <v-btn class="bordered-btn" @click="logout">Logout</v-btn>
    <v-btn class="bordered-btn" @click="register">register</v-btn>
    <v-btn class="bordered-btn" @click="filter">filter</v-btn>
    <v-btn class="bordered-btn" @click="deleteData">delete</v-btn>
    <v-btn class="bordered-btn" @click="updateData">update</v-btn>
    <v-btn class="bordered-btn" @click="createData">create</v-btn>
  </div>
  <div>
    <ReviewsListComponent :sorting="{timestamp: false}"></ReviewsListComponent>
  </div>
</template>

<script>
import { fetchData } from "../JS/fetch";
import ReviewsListComponent from "../components/reviewsListComponent.vue";

export default {
  components: {
    ReviewsListComponent,
  },
  data() {
    return {
      // Store tokens here
      loginTokens: {
        access_token: "",
        refresh_token: "",
      },
    };
  },
  methods: {
    getAll() {
      // let results = fetchData("games", "getAll", null, null, ["id","files","title"],{id: true}, null, "GET")
      let results = fetchData(
        "games",
        "getAll",
        null,
        null,
        ["id", "ratingAverage", "title", "tags"],
        null,
        null,
        "GET"
      );
      // let results = fetchData(
      //   "games",
      //   "getAll",
      //   null,
      //   null,
      //   ["id", "ratingAverage", "title"],
      //   null,
      //   null,
      //   "GET"
      // );
      // let results = fetchData(
      //   "users",
      //   "getAll",
      //   null,
      //   null,
      //   null,
      //   null,
      //   null,
      //   "GET"
      // );
      console.log(results);
    },
    getOne() {
      // let results = fetchData("games", "getAll", null, null, ["id","files","title"],{id: true}, null, "GET")
      let results = fetchData(
        "games",
        "getOne",
        "id",
        "",
        null,
        null,
        null,
        "GET"
      );


      /////////////////GET ONE USER///////////////////////////
      // let results = fetchData(
      //   "users",
      //   "getOne",
      //   "id",
      //   "4",
      //   ["id", "username"],
      //   null,
      //   null,
      //   "GET"
      // );
      console.log(results);
    },
    
    logout() {
      const access_token = localStorage.getItem("accessToken");
      const refresh_token = localStorage.getItem("refreshToken");

      const logout = {
        id: "7",
        tokens: {
          access_token: access_token,
          refresh_token: refresh_token,
        },
      };

      this.loginTokens.access_token = "";
      this.loginTokens.refresh_token = "";

      localStorage.removeItem("accessToken");
      localStorage.removeItem("refreshToken");

      const body = { logout };

      let results = fetchData(
        "users",
        "logout",
        null,
        null,
        null,
        null,
        body,
        "POST"
      );
      console.log(results);
    },
    async login() {
      const login = {
        email: "b",
        password: "b",
      };
      const loginBody = { login };
      try {
        const loginResponse = await fetchData(
          "users",
          "login",
          null,
          null,
          null,
          null,
          loginBody,
          "POST"
        );
        // console.log("Login successful: ",loginResponse);
        if (loginResponse) {
          this.loginTokens.access_token = loginResponse.access_token;
          this.loginTokens.refresh_token = loginResponse.refresh_token;
          localStorage.setItem("accessToken", loginResponse.access_token);
          localStorage.setItem("refreshToken", loginResponse.refresh_token);
          console.log("Login successful: ", this.loginTokens);
        }
      } catch (error) {
        console.error("Login failed: ", error);
      }
    },
    register() {
      const createData = {
        email: "c",
        username: "meki",
        password: "c",
      };
      const createBody = { createData };
      let results = fetchData(
        "users",
        "create",
        null,
        null,
        null,
        null,
        createBody,
        "POST"
      );
      console.log(results);
    },
    createData() {
      ////////////CREATE REVIEWS///////////////
      // const createData = {
      //   userID: "1",
      //   gameID: "10",
      //   rating: "1",
      //   tokens: {
      //     access_token: this.loginTokens.access_token,
      //     refresh_token: this.loginTokens.refresh_token,
      //   },
      //   comment: "This is a review comment",
      // };
      // const createBody = { createData };
      // let results = fetchData(
      //   "reviews",
      //   "create",
      //   null,
      //   null,
      //   null,
      //   null,
      //   createBody,
      //   "POST"
      // );
      // console.log(results);

      ////////////////////CREATE TAG//////////////////////
      const createData = {
        gameId: "10",
        name: "princess",
      };
      const createBody = { createData };
      let results = fetchData(
        "tags",
        "create",
        null,
        null,
        null,
        null,
        createBody,
        "POST"
      );
      console.log(results);
    },
    updateData() {
      ////////////////////UPDATE TAG/////////////////////////

      const updateData = {
        id: "11",
        gameId: "10",
        oldName: "princess",
        newName: "chocolate",
      };
      const body = { updateData };
      let results = fetchData(
        "tags",
        "update",
        null,
        null,
        null,
        null,
        body,
        "POST"
      );
      console.log(results);

      ///////////////////UPDATE USERS////////////////////

      // const access_token = localStorage.getItem("accessToken");
      // const refresh_token = localStorage.getItem("refreshToken");
      // // console.log("access_token:", this.loginTokens.access_token);
      // // console.log("refresh_token:", this.loginTokens.refresh_token);
      // const updateData = {
      //   id: "7",
      //   drive_id: "taxes",
      //   tokens: {
      //     access_token: access_token,
      //     refresh_token: refresh_token,
      //   },
      // };
      // const body = { updateData };
      // let results = fetchData(
      //   "users",
      //   "update",
      //   null,
      //   null,
      //   null,
      //   null,
      //   body,
      //   "POST"
      // );
      // console.log(results);

      ///////////////////UPDATE REVIEWS////////////////////
      // const updateData = {
      //   id: "17",
      //   gameID: "4",
      //   userID: "2",
      //   timestamp : "2024-02-22",
      //   rating: "6",
      //   tokens: {
      //     access_token:
      //       "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwODY1MDMwMCwiZXhwIjoxNzA4NjUzOTAwLCJzdWIiOjM1fQ.FXa6aLKfyPKIDxDTVwpj-usbg2EARvahUAoMh6pf08Y",
      //     refresh_token:
      //       "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwODY1MDMwMCwiZXhwIjoxNzA4NzM2NzAwLCJzdWIiOjM1fQ.QFVpEGyOyAO87VZgGPGF8WvU2KHM5Ex-RmgMgctmc7s",
      //   },
      //   comment: "This is a review comment update",
      // };
      // const body = { updateData };
      // fetchData("reviews", "update", null, null, null, null, body, "POST");
    },
    deleteData() {
      ////////////////////DELETE REVIEW/////////////////////////

      //   const deleteData = {
      //   id : "23",
      //   userID: "1",
      //   gameID: "10",
      //   rating: "1",
      //   tokens: {
      // "access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwODcwMjQ2NCwiZXhwIjoxNzA4NzA2MDY0LCJzdWIiOjM1fQ.0IAJjZbSUlposeGDPaPnD0LYj1oOwMuWTR9jNwHaOkg",
      //  "refresh_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwODcwMjQ2NCwiZXhwIjoxNzA4Nzg4ODY0LCJzdWIiOjM1fQ.bOVJEpwyj98vjNTgZ8mITEVzqf2zxle16byzqbLP-rI",
      // },
      // }
      //  const body = { deleteData };
      //  fetchData("reviews", "delete", null, null, null, null, body, "POST");

      ////////////////////DELETE GAME/////////////////////////

      // const deleteData = {
      //   id: "9",
      // };
      // const body = { deleteData };
      // let results =  fetchData("games", "delete", null, null, null, null, body, "POST")
      // console.log(results)

      ////////////////////DELETE USER/////////////////////////
      // const access_token = localStorage.getItem("accessToken");
      // const refresh_token = localStorage.getItem("refreshToken");

      // const deleteData = {
      //   id: "8",
      //   tokens: {
      //     access_token: access_token,
      //     refresh_token: refresh_token,
      //   },
      // };
      // const body = { deleteData };
      // let results = fetchData(
      //   "users",
      //   "delete",
      //   null,
      //   null,
      //   null,
      //   null,
      //   body,
      //   "POST"
      // );
      // console.log(results);

      ////////////////////DELETE TAG/////////////////////////

      const deleteData = {
        gameId: "10",
        name: "chocolate",
      };
      const body = { deleteData };
      let results = fetchData(
        "tags",
        "delete",
        null,
        null,
        null,
        null,
        body,
        "POST"
      );
      console.log(results);
    },
    filter() {
      ///////////////////FILTER GAMES//////////////////////////
      const filters = {
        ratingAverage: "5",
      };
      const sorting = {
        ratingAverage: true,
      };
      const includedColumns = ["id", "developerID", "tags", "ratingAverage"];
      const jsonBody = { filters, sorting, includedColumns };
      console.log(
        fetchData(
          "games",
          "getAllMatching",
          null,
          null,
          null,
          null,
          jsonBody,
          "POST"
        )
      );
    },
  },
};
</script>

<style lang="scss" scoped>
.test_view {
  text-align: center;
  font-size: 40;
  color: aliceblue;
  display: flex;
  flex-direction: column;
  // margin-right: 50%;
  // margin-left: 50%;
  justify-items: center;
  align-items: center;
  gap: 5%;
}

.bordered-btn {
  border: 2px solid #1976d2; /* Example border: 2px solid, using Vuetify's primary color */
  border-radius: 4px; /* Optional: Adjust border-radius for rounded corners */
}
</style>
