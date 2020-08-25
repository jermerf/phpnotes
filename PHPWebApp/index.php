<!DOCTYPE html>
<html lang="en">

<head>
  <?php require 'partials/head.php'; ?>
</head>

<body>
  <?php require "partials/navbar.php" ?>
  <h1>Home</h1>
  <div class="center">
    <div class="contain">
    <?php require "content/listPosts.php"; ?>
    <?php require "content/listUploads.php"; ?>
    </div>
  </div>
</body>

</html>