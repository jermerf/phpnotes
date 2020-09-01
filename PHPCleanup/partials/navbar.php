<nav>
  <img id="logo" src="img/logo.png" />
  <a href="index.php">Home</a>
  <?php

  if ($loggedIn) {
    echo '<a href="posts.php">Posts</a>';
    echo '<a href="upload.php">Upload</a>';
  }
  if ($loggedIn && $isAdmin) {
    echo '<a href="admin.php"><i class="fa fa-user-shield"></i>Admin</a>';
  }

  ?>
  <div id="loginbox">
    <div id="currentUser">

      <i class="fa fa-user"></i>
      <?php
      if ($loggedIn) {
        echo $_SESSION['username'];
      } else {
        echo 'Not Logged In';
      }
      ?>
    </div>
    <div id="loginForm">
      <?php
      if ($loggedIn) {
        echo <<<LOGGEDIN
        <form action="server.php" method="POST">
          <input name="action" value="logout" type="hidden" />
          <button>Logout</button>
        </form>
        LOGGEDIN;
      } else {
        echo <<<NOTLOGGEDIN
        <form action="server.php" method="POST">
          <input name="username" value="jermerf" />
          <input name="password" type="password" value="puppies" />
          <input name="action" value="login" type="hidden" />
          <button onclick="login()">Login</button>
          <button onclick="register()">Register</button>
        </form>
        NOTLOGGEDIN;
      }
      ?>
      <div id="loginStatus"></div>
    </div>
  </div>
</nav>