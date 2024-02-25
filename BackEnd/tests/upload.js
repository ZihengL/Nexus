function upload(url) {
  const fileInput = document.querySelector("input[type=file]");
  
  fileInput.addEventListener("change", async (event) => {
    const file = event.target.files[0];
    const uploadUrl = url; // The URL obtained from the backend

    try {
      const response = await fetch(uploadUrl, {
        method: "PUT",
        headers: {
          "Content-Type": "image/jpeg",
        },
        body: file, // Directly upload the file binary data
      });

      if (response.ok) {
        console.log("Upload successful");
      } else {
        console.error("Upload failed");
      }
    } catch (error) {
      console.error("Error during upload:", error);
    }
  });
}
