<!DOCTYPE html>
<html lang="en">

<head>
  <?php require "partials/head.php" ?>
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
  <form action="server.php" method="POST" enctype="multipart/form-data" class="center">
    <input name="action" value="uploadFile" type="hidden" />
    <input name="newFile" type="file" required />
    <input name="title" required />
    <button>Upload</button>
  </form>
  <div class="center">
    <div class="contain">
      <?php require "content/listUserUploads.php" ?>
    </div>
  </div>


</body>

</html>