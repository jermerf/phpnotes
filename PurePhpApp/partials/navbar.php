<nav>
  <a href="index.php">Home</a>
  <a href="posts.php">Posts</a>
  <?php
  if ($loggedIn && $isAdmin) {
    echo '<a href="admin.php">Admin</a>';
  }
  ?>
  <div id="loginbox">
    <div id="currentUser">
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
<script>
  document.querySelector('#currentUser').addEventListener('click', ev => {
    document.querySelector('#loginForm').classList.toggle('visible')
  })

  function register() {
    document.querySelector("input[name=action]").value = "register"
  }

  function login() {
    document.querySelector("input[name=action]").value = "login"
  }

  // Handled by php
  function loginSuccess(user) {
    $('#currentUser').text(user.username)
    $('.notLoggedIn').hide()
    $('.loggedIn').show()
  }
</script>