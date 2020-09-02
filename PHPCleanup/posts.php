<!DOCTYPE html>
<html lang="en">

<head>
  <?php require "partials/head.php" ?>
</head>

<body>
  <?php require "partials/navbar.php" ?>
  <h1>Posts</h1>
  <div id="status" class="center"></div>
  <?php
  if ($loggedIn) {
    echo <<<NEWPOST
    <form action="server.php" method="post" class="center">
      <input name="action" value="addPost" type="hidden" />
      <textarea name="content" cols="60" rows="5"></textarea>
      <br>
      <button>Submit New Post</button>
    </form>
    NEWPOST;
  }
  ?>
  <?php Posts::showPosts(true); ?>
  <?php require 'partials/footer.php'; ?>
  <script>
    if (cookies.statusMsg) {
      document.getElementById('status').innerText = cookies.statusMsg
    }
  </script>
</body>

</html>