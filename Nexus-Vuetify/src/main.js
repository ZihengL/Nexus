/**
 * main.js
 *
 * Bootstraps Vuetify and other plugins then mounts the App`
 */

// Plugins
import { registerPlugins } from "@/plugins";

// Components
import App from "./App.vue";
//import { Vue }  from 'vue';
//import { isConnected } from './JS/GlobalVar';

// Composables
import { createApp } from "vue";

const app = createApp(App);

registerPlugins(app);
//Vue.prototype.$isConnected = isConnected

app.config.globalProperties.$fetchData = function (
  table,
  crud_action,
  columnName = null,
  value = null,
  jsonBody = null,
  method
) {
  const baseURL = "http://localhost:4208/Nexus/Backend/";
  let uri = `${baseURL}${table}/${crud_action}`;
  if (columnName && value) {
    uri += `/${columnName}/${value}`;
  }

  const options = {
    method,
    headers: {
      "Content-Type": "application/json",
    },

    body: method === "POST" ? JSON.stringify(jsonBody) : null,
  };

  return fetch(uri, options)
    .then((response) => {
      if (!response.ok) {
        console.error(`Error: ${response.status} ${response.statusText}`);
        return null;
      }
      if (response.status !== 200) {
        console.error(
          `Error: Non-200 status code(${response.status}), ${response.statusText}`
        );
        return [];
      }

      return response.json();
    })
    .catch((error) => {
      console.error("Network error:", error);
      return null;
    });
};

app.mount("#app");
