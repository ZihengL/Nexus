function uploadFile(accessToken, file) {
  const formData = new FormData();
  formData.append(
    "metadata",
    new Blob(
      [
        JSON.stringify({
          name: file.name,
          mimeType: file.type,
        }),
      ],
      { type: "application/json" }
    )
  );
  formData.append("file", file);

  fetch(
    "https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart",
    {
      method: "POST",
      headers: new Headers({ Authorization: "Bearer " + accessToken }),
      body: formData,
    }
  )
    .then((response) => response.json())
    .then((data) => console.log(data))
    .catch((error) => console.error(error));
}
