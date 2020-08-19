<?php
var_dump($_POST);

$username = $_POST['username'] ?? false;
$password = $_POST['password'] ?? false;

if (!$username || !$password) {
  echo "Need username and password";
  return;
}

// Accessing Database
$dbHost = "localhost";
$dbName = "php_class";
$dbUser = "root";

$con = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser);

$query = "SELECT * FROM student WHERE username = :uname";

$stm = $con->prepare($query);

$stm->bindParam(":uname", $username);

$stm->execute();

if ($row = $stm->fetch()) {
  if (strcmp($row['password'], $password) == 0) {
    echo "password match, you are logged in!";
  } else {
    echo "password mismatch, you are mistaken...";
  }
}
