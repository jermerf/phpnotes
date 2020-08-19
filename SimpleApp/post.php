<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<form action="post.php" method="get">
  <input name="post" />
  <button>Post</button>
</form>
<?php


$post = $_GET['post'] ?? false;

// Accessing Database
$dbHost = "localhost";
$dbName = "php_class";
$dbUser = "root";

$con = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser);

if (!$post) {
  echo "Not inserting new post";
} else {
  $query = "INSERT INTO post (words) VALUE (:words)";

  $stm = $con->prepare($query);

  $stm->bindParam(":words", $post);

  if ($stm->execute()) {
    echo "New post inserted";
  } else {
    echo "error occured";
  }
}
?>
<hr>
<h1>Existing Posts</h1>
<ul>
  <?php
  $query = "SELECT * FROM post";
  $stm = $con->prepare($query);
  $stm->execute();
  $stm->setFetchMode(PDO::FETCH_ASSOC);
  while ($row = $stm->fetch()) {
    echo "<li>{$row['id']} &rarr; {$row['words']}</li>";
  }
  ?>
</ul>