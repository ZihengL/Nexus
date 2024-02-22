<template>
  <div class="test_view">TEST</div>
</template>

<script scoped>
import { fetchData } from "../JS/fetch";

export default {
  components: {},
  // mounted() {
  //   const filters = {
  //     ratingAverage: { gt: 1, lte: 7 },
  //   };
  //   const sorting = {
  //     ratingAverage: true,
  //   };

  //   const includedColumns = ["id", "developerID", "title"];

  //   // const create = {
  //   //   email: "e",
  //   //   name: "Katty",
  //   //   password: "e",
  //   // };

  //   const login = {
  //     email: "e",
  //     password: "e",
  //   };

  //   const create = {
  //     userID: "2",
  //     gameID: "4",
  //     rating:"3",
  //     tokens:{
  //       access_token:"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwODYxMzg4OCwiZXhwIjoxNzA4NjE3NDg4LCJzdWIiOjM1fQ.i6Y1ArGXSD30iBilm5nMH6SB61_8ZVqrOTd7Uy-yNxE",
  //       refresh_token:"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwODYxMzg4OCwiZXhwIjoxNzA4NzAwMjg4LCJzdWIiOjM1fQ.TpO6qWEJD0PPTnebchFKDO7nor9vfamNviTxjcXIWTI",
  //     },
  //     comment: "This is a review comment",
  //   };

  //   // const jsonBody = { filters, sorting, includedColumns };
  //   const createBody = { create };
  //   // const loginBody = { login };

  //   fetchData("reviews", "create", null, null, null, null, createBody, "POST");
  //   //  fetchData('reviews', 'getAll', "id", 1, null, 'GET')
  //   //  fetchData('users', 'getOne', "id", 1, ["id", "user"], null, null, 'GET')
  //   // fetchData('users', 'create', null, null, null, null, createBody, 'POST')
  //   // fetchData('games', 'getAllMatching', null, null, jsonBody, 'POST')

  //       // const login_tokens = fetchData(
  //       //   "users",
  //       //   "login",
  //       //   null,
  //       //   null,
  //       //   null,
  //       //   null,
  //       //   loginBody,
  //       //   "POST"
  //       // );
  //       // console.log("login tokens : ", login_tokens);

  //   //     if (login_tokens) {
  //   //       const logout = {
  //   //         email: "e",
  //   //         password: "e",
  //   //         access_token: login_tokens["access_token"],
  //   //         refresh_token: login_tokens["refresh_token"],
  //   //       };
  //   //       const logoutBody = { logout };

  //   //       console.log(
  //   //         "logout infos : ",
  //   //         fetchData("users", "logout", null, null, logoutBody, "POST")
  //   //       );
  //   //     }
  // },
};
</script>

<style lang="scss" scoped>
.test_view {
  text-align: center;
  font-size: 40;
  color: aliceblue;
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
