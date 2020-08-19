<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    nav {
      padding: 10px 20px;
      margin: 15px;
      background: #294;
    }

    form {
      background: #68d;
      padding: 10px 20px;
      margin: 15px;
    }
  </style>
</head>

<body>
  <?php
  $loggedIn = false;
  $username = "Erkel Maximus";

  if ($loggedIn) {
    echo "<nav>You are logged in as $username</nav>";
  } else {
    echo '<nav>You are NOT logged in</nav>';
  }
  ?>
  <hr>

  <form action="receiveForm.php" method="get">
    <input name="username" />
    <input name="password" type="password" />
    <input type="submit" />
  </form>

  <script>

  </script>
</body>

</html>