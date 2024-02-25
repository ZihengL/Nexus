<template>
  <div class="test_view">
    TEST
    <!-- Using Vuetify's v-button component -->
    <v-btn class="bordered-btn" @click="getData">get Data</v-btn>
    <v-btn class="bordered-btn" @click="login">Login</v-btn>
    <v-btn class="bordered-btn" @click="register">register</v-btn>
    <v-btn class="bordered-btn" @click="filter">filter</v-btn>
    <v-btn class="bordered-btn" @click="deleteData">delete</v-btn>
    <v-btn class="bordered-btn" @click="updateData">update</v-btn>
    <v-btn class="bordered-btn" @click="createData">create</v-btn>
  </div>
</template>

<script scoped>
import { fetchData } from "../JS/fetch";

export default {
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
    getData() {
      //  fetchData("games", "getAll", null, null, ["id","files","title"],{id: true}, null, "GET")
      // fetchData(
      //   "games",
      //   "getAll",
      //   null,
      //   null,
      //   ["id", "ratingAverage", "title", "tags"],
      //   null,
      //   null,
      //   "GET"
      // );
      // fetchData(
      //   "games",
      //   "getAll",
      //   null,
      //   null,
      //   ["id", "ratingAverage", "title"],
      //   null,
      //   null,
      //   "GET"
      // );
      // fetchData(
      //   "games",
      //   "getAll",
      //   null,
      //   null,
      //   null,
      //   null,
      //   null,
      //   "GET"
      // );
    },
    logout() {
      // const logout = {
      //   id : ""
      //   email: "e",
      //   name: "Katty",
      //   password: "e",
      // };
      // const body = { logout };
      // fetchData('users', 'logout', null, null, null, null, body, 'POST')
    },
    async login() {
      const login = {
        email: "b",
        name: "charles",
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
        // Assuming the API response structure
        if (loginResponse.data && loginResponse.data.tokens) {
          this.loginTokens.accessToken = loginResponse.data.tokens.access_token;
          this.loginTokens.refreshToken =
            loginResponse.data.tokens.refresh_token;
          console.log("Login successful: ", this.loginTokens);
        }
      } catch (error) {
        console.error("Login failed: ", error);
      }
    },
    register() {
      const createData = {
        email: "b",
        name: "charles",
        password: "b",
      };
      const createBody = { createData };
      fetchData('users', 'create', null, null, null, null, createBody, 'POST')
    },
    createData() {

      //create review
      const createData = {
        userID: "1",
        gameID: "10",
        rating: "1",
        tokens: {
          access_token: this.loginTokens.access_token,
          refresh_token: this.loginTokens.refresh_token,
        },
        comment: "This is a review comment",
      };
      const createBody = { createData };
      fetchData(
        "reviews",
        "create",
        null,
        null,
        null,
        null,
        createBody,
        "POST"
      );
    },
    updateData() {
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
      //////////////////////////////////////////

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

      /////////////////////////////

      // delete game

      const deleteData = {
        id: "9",
      };
      const body = { deleteData };
      fetchData("games", "delete", null, null, null, null, body, "POST");
    },
    filter() {
      ///////////////////////////////////////////////
      // const filters = {
      //   ratingAverage: "5",
      // };
      // const sorting = {
      //   ratingAverage: true,
      // };
      // const includedColumns = ["id", "developerID", "tags", "ratingAverage"];
      // const jsonBody = { filters, sorting, includedColumns };
      // fetchData("games", "getAllMatching", null, null, null, null, jsonBody, "POST")
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
