<nav>
  <a href="index.php">Home</a>
  <a href="posts.php">Posts</a>
  <?php
  session_start();
  if ($_SESSION['userId'] && $_SESSION['admin']) {
    echo '<a href="admin.php">Admin</a>';
  }
  ?>
  <div id="loginbox">
    <div id="currentUser">Not Logged In</div>
    <div id="loginForm">
      <div class="notLoggedIn">
        <input id="username" value="jermerf" />
        <input id="password" type="password" value="puppies" />
        <button onclick="login()">Login</button>
        <button onclick="register()">Register</button>
      </div>
      <div class="loggedIn">
        <button onclick="logout()">Logout</button>
      </div>
      <div id="loginStatus"></div>
    </div>
  </div>
</nav>
<script>
  $('#currentUser').click(event => {
    $('#loginForm').toggleClass("visible")
  })

  function register() {
    $('div#loginStatus').text("")
    let data = {
      action: "register",
      username: $("#username").val(),
      password: $("#password").val()
    }
    $.post(SERVER, data, res => {
      if (res.success) {
        loginSuccess(res)
      } else {
        $('div#loginStatus').text(res.message)
      }
    })
  }

  function login() {
    $('div#loginStatus').text("")
    let data = {
      action: "login",
      username: $("#username").val(),
      password: $("#password").val()
    }
    $.post(SERVER, data, res => {
      if (res.success) {
        loginSuccess(res)
      } else {
        $('div#loginStatus').text(res.message)
      }
    })
  }

  function loginSuccess(user) {
    $('#currentUser').text(user.username)
    $('.notLoggedIn').hide()
    $('.loggedIn').show()
  }
</script>