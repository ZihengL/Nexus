<template>
  <div class="test_view">
    TEST
    <!-- Using Vuetify's v-button component -->
    <v-btn class="bordered-btn" @click="fetchDataMethod">Fetch Data</v-btn>
  </div>
</template>

<script scoped>
import { fetchData } from "../JS/fetch";

export default {
  components: {},
  mounted() {
   
  },
  methods: {
    fetchDataMethod() {
      //  fetchData("games", "getAll", null, null, ["id","files","title"],{id: true}, null, "GET")
      fetchData(
        "games",
        "getAll",
        null,
        null,
        ["id", "ratingAverage", "title"],
        null,
        null,
        "GET"
      );

      ///////////////////////////////////////////////
      // const filters = {
      //   ratingAverage: { gt: 1, lte: 7 },
      // };
      // const sorting = {
      //   ratingAverage: true,
      // };
      // const includedColumns = ["id", "developerID", "title"];

      // const jsonBody = { filters, sorting, includedColumns };

      // fetchData("games", "getAllMatching", null, null, null, null, jsonBody, "POST")

      //////////////////////////////////////////

      // const create = {
      //   email: "e",
      //   name: "Katty",
      //   password: "e",
      // };

      // const createBody = { create };
      // fetchData('users', 'create', null, null, null, null, createBody, 'POST')

      ////////////////////////////////////////////////////////////////////////

      // const login = {
      //   email: "e",
      //   password: "e",
      // };
      // const loginBody = { login };
      // const login_tokens = fetchData(
      //   "users",
      //   "login",
      //   null,
      //   null,
      //   null,
      //   null,
      //   loginBody,
      //   "POST"
      // );

      // console.log("login tokens : ", login_tokens);

      /////////////////////////////

      //   const deleteData = {
      //   id : 18,
      //   userID: "4",
      //   gameID: "4",
      //   tokens: {
      // "access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwODcwMjQ2NCwiZXhwIjoxNzA4NzA2MDY0LCJzdWIiOjM1fQ.0IAJjZbSUlposeGDPaPnD0LYj1oOwMuWTR9jNwHaOkg",
      //  "refresh_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwODcwMjQ2NCwiZXhwIjoxNzA4Nzg4ODY0LCJzdWIiOjM1fQ.bOVJEpwyj98vjNTgZ8mITEVzqf2zxle16byzqbLP-rI",
      // },
      // }
      //  const body = { deleteData };

      //  fetchData("reviews", "delete", null, null, null, null, body, "POST");

      ////////////////////////////////////////

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

      //////////////////////////////////////////////
      // const createData = {
      //   userID: "4",
      //   gameID: "4",
      //   rating: "1",
      //   tokens: {
      //    "access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwODY1MjM3MywiZXhwIjoxNzA4NjU1OTczLCJzdWIiOjM1fQ.mtB7BxFhkni0dhevIR7e57jz1xwNqQ7bZQsfC5vuRSo",
      //   "refresh_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwODY1MjM3MywiZXhwIjoxNzA4NzM4NzczLCJzdWIiOjM1fQ.f0e2GNXi50jX5nuXYRD3DcukvNQiSEAvtHDVxrUO22E",
      //   },
      //   comment: "This is a review comment",
      // };

      //  const createBody = { createData };
      //   fetchData("reviews", "create", null, null, null, null, createBody, "POST");
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

<!-- 
<template>
    <div class="test_view">
      TEST
    </div>
  </template>
  
  <script>
  import { fetchData } from "../JS/fetch";
  
  export default {
    async mounted() {
      const loginBody = {
        login: {
          email: "e",
          password: "e",
        }
      };
  
      try {
        // Perform the login operation and wait for its completion
        // const loginResponse = await fetchData("users", "login", null, null, loginBody, "POST");
        
        // Assuming fetchData resolves with the response body directly
        // if (loginResponse && loginResponse.access_token && loginResponse.refresh_token) {
        //   console.log("login tokens : ", loginResponse);
          
          const logoutBody = {
            logout: {
              email: "e",
              password: "e",
              tokens: {
                access_token: "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwODQ4MTUzNywiZXhwIjoxNzA4NDg1MTM3LCJzdWIiOjM0fQ.irjaTzteo0DtpwcMgW2xlPyYKEFNedEWSqpY-B8D28g",
                refresh_token: "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwODQ4MTUzNywiZXhwIjoxNzA4NTY3OTM3LCJzdWIiOjM0fQ.DtzXxw5mjQGPRpO92rH2wP-MYhvp4A6KK92jzlrtxU8",
              }
            }
          };
  
          // Perform the logout operation
        //   const logoutResponse = 
        //   await fetchData("users", "logout", null, null, logoutBody, "POST");
        //   console.log("logout infos : ",  await fetchData("users", "logout", null, null, logoutBody, "POST"));
        // } else {
        //   console.error("Login failed or tokens missing");
        // }
      } catch (error) {
        console.error("Error in login/logout process:", error);
      }
    },
  };
  </script>
  
  <style lang="scss" scoped>
  .test_view {
    text-align: center;
    font-size: 40px;
    color: aliceblue;
  }
  </style>




 -->
