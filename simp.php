<form method="post">
  <input name="words">
  <button>Send</button>
</form>
<?php


$dbHost = "localhost";
$dbName = "php_class";
$dbUser = "root";

$con = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser);

if ($_POST) {

  $words = $_POST['words'];

  $query = <<<QUERY
  INSERT INTO notes (words) VALUE (:worwor)
  QUERY;

  $stm = $con->prepare($query);

  $stm->bindParam(":worwor", $words);

  $stm->execute();
}

$query = <<<QUERY
SELECT * FROM notes
QUERY;

$stm = $con->prepare($query);


$stm->execute();

echo "<ul>";
while ($row = $stm->fetch(PDO::FETCH_ASSOC)) {
  echo "<li>{$row['words']}</li>";
}
echo "</ul>";
