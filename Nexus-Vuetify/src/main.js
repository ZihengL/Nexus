/**
 * main.js
 *
 * Bootstraps Vuetify and other plugins then mounts the App`
 */

// Plugins
import { registerPlugins } from "@/plugins";
import App from "./App.vue";
import { createApp } from "vue";

// Components


const app = createApp(App)

// Utilisez app.config.globalProperties pour définir une propriété globale
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

// Enregistrez les plugins
registerPlugins(app)

// Montez l'application
app.mount('#app')
