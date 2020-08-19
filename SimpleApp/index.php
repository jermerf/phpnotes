<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    body,
    html {
      margin: 0;
      padding: 0;
      background-color: #333333;
      color: #fff;
    }

    nav {
      padding: 5px 10px;
      background-color: #444;
    }
  </style>
</head>

<body>
  <nav>
    <form action="register.php" method="post">
      <input name="username" placeholder="username" value="jermerf" />
      <input name="password" placeholder="password" value="puppies" type="password" />
      <button>Register</button>
    </form>
  </nav>
  <hr>
  <nav>
    <form action="login.php" method="post">
      <input name="username" placeholder="username" value="jermerf" />
      <input name="password" placeholder="password" value="puppies" type="password" />
      <button>Login</button>
    </form>
  </nav>
</body>

</html>