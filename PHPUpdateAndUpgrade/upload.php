<!DOCTYPE html>
<html lang="en">

<head>
  <?php require "partials/head.php" ?>
  <style>
    #fileChooser {
      display: none;
    }

    ul#fileList {
      list-style-type: none;
      width: 50%;
      min-width: 200px;
      display: inline-block;
    }

    ul#fileList li {
      padding: 5px;
      background-color: #444;
      margin: 1px;
    }
  </style>
</head>
<?php
if (!$loggedIn) {
  header("Location: index.php");
  return;
}
?>

<body>
  <?php require "partials/navbar.php" ?>
  <h1>Upload A File</h1>
  <div id="status" class="center"></div>
  <form id="uploadForm" action="server.php" method="POST" enctype="multipart/form-data" class="center">
    <input name="action" value="uploadFile" type="hidden" />
    <button onclick="chooseFile(event)">Choose File</button>
    <input id="fileChooser" name="newFile" type="file" required onchange="validateInputs()" />
    <input name="title" placeholder="title" required onkeyup="validateInputs()" />
    <button id="uploadBtn" style="display: none">Upload</button>
    <div>
      <ul id="fileList"></ul>
    </div>
  </form>
  <div class="center">
    <div class="contain">
      <?php UploadedImages::showUploads(true); ?>
    </div>
  </div>
  <script>
    function chooseFile(event) {
      event.preventDefault()
      let clickEvent = new MouseEvent("click")
      document.getElementById('fileChooser').dispatchEvent(clickEvent)
    }

    function validateInputs(event) {
      var files = document.getElementById('fileChooser').files
      var fileList = document.getElementById('fileList')
      fileList.innerHTML = ""
      for (const f of files) {
        let newLi = document.createElement('li')
        newLi.innerText = f.name
        fileList.appendChild(newLi)
      }

      if (document.getElementById('uploadForm').checkValidity()) {
        document.getElementById('uploadBtn').style.display = "inline-block"
      } else {
        document.getElementById('uploadBtn').style.display = "none"
        document.getElementById('status').innerText = "Must choose file and have title"
      }
    }

    if (cookies.statusMsg) {
      document.getElementById('status').innerText = cookies.statusMsg
    }
  </script>
</body>

</html>