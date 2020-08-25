<!DOCTYPE html>
<html lang="en">

<head>
  <?php require "partials/head.php" ?>
</head>

<body>
  <?php require "partials/navbar.php" ?>
  <h1>Posts</h1>
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
  <?php require "content/listPosts.php"; ?>
</body>

</html>