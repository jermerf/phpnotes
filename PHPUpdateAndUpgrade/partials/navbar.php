<nav>
  <img id="logo" src="img/logo.png" />
  <a href="index.php">Home</a>
  <?php

  if ($loggedIn) {
    echo '<a href="posts.php">Posts</a>';
    echo '<a href="upload.php">Upload</a>';
  }
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


  var cookies = {}

  function getCookies() {
    var cookieParts = document.cookie.split(';')
    for (const p of cookieParts) {
      let subParts = p.trim().split('=')
      let k = subParts[0]
      let v = decodeURI(subParts[1])
      cookies[k] = v
    }
  }
  getCookies()

  if (cookies.authError) {
    document.getElementById('loginStatus').innerText = cookies.authError
  }

  function toggleEditPost(id) {
    var form = document.querySelector('#post' + id + " form.edit-hidden")
    var h3 = document.querySelector('#post' + id + " h3")

    if (form) {
      form.classList.remove('edit-hidden')
      form.classList.add('edit')
      h3.style.display = "none"
    } else {
      form = document.querySelector('#post' + id + " form.edit")
      form.classList.remove('edit')
      form.classList.add('edit-hidden')
      h3.style.display = "block"
    }


  }
</script>