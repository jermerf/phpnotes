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

$query = "INSERT INTO student (username, password) VALUE (:uname , :pword)";

$stm = $con->prepare($query);

$stm->bindParam(":uname", $username);
$stm->bindParam(":pword", $password);

$stm->execute();
