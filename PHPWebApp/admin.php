<!DOCTYPE html>
<html lang="en">

<head>
  <?php require 'partials/head.php'; ?>
</head>

<body>
  <?php require "partials/navbar.php" ?>
  <h1>Admin</h1>
  <h3>
    <?php

    $firstVisit = $_COOKIE['isFirstVisit'] ?? true;

    if ($firstVisit) {
      echo "Welcome! This is your first time!";
      setcookie('isFirstVisit', '0', time() + 3600);
    } else {
      echo "Welcome back your lordship";
      // Delete cookie
      setcookie('isFirstVisit', null, 0);
    }

    ?>
  </h3>
</body>

</html>