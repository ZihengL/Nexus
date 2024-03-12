import { getStorage, ref, listAll, getDownloadURL } from "firebase/storage";

const storage = getStorage();

// Assuming 'GameTitle/' is the directory where your image is stored
// and you're looking to list all files within this directory
const listRef = ref(storage, 'GameTitle/');

// Find all the prefixes and items under 'GameTitle/'
listAll(listRef)
  .then((res) => {
    console.log(res);
    res.prefixes.forEach((folderRef) => {
      // If there are sub-folders under 'GameTitle/', you can list them here
      // This example assumes you're directly interested in images, so no recursive listAll call is made
    });
    res.items.forEach((itemRef) => {
      // For each item found, assuming these are your images, get the download URL
      getDownloadURL(itemRef).then((url) => {
        // Here, you have the URL of each image, and you can use it as needed
        // For example, setting it as the src attribute of an <img> element:
        console.log("Image URL:", url); // Log the URL or handle it as needed
        // Example: document.querySelector('img').src = url;
      }).catch((error) => {
        // Handle any errors in getting the download URL
        console.error("Error getting image download URL", error);
      });
    });
  }).catch((error) => {
    // Handle any errors in listing the files
    console.error("Error listing files:", error);
  });
