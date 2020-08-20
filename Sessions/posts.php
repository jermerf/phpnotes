<!DOCTYPE html>
<html lang="en">

<head>
  <?php require "partials/head.php" ?>
</head>

<body>
  <?php require "partials/navbar.php" ?>
  <h1>Posts</h1>
  <?php

  session_start();
  if ($_SESSION['userId']) {
    // get this user's posts
  }
  ?>
</body>

</html>