import * as GlobalFunctions from "./services";

export default {
  install(app) {
    for (const [key, value] of Object.entries(GlobalFunctions)) {
      app.config.globalProperties[`$${key}`] = value;
    }
  },
};
