<!DOCTYPE html>
<html lang="en">

<head>
  <?php require 'partials/head.php'; ?>
</head>

<body>
  <?php require "partials/navbar.php" ?>
  <h1>Admin</h1>
  <hr>
  <h3>

    <div class="center">
      <div class="contain">
        <i class="fa fa-check"></i>
        <i class="fa fa-times"></i>


        <?php Posts::showPosts(false, true) ?>
        <?php UploadedImages::showUploads(false, true) ?>
      </div>
    </div>
  </h3>
</body>

</html>