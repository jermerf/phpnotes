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
      <?php Posts::showPosts(); ?>
    </div>
  </div>
  <?php require 'partials/footer.php'; ?>
</body>

</html>