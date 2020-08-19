<?php

header("Content-type: application/json; charset=utf-8");

$post = $_GET['post'] ?? false;

// Accessing Database
$dbHost = "localhost";
$dbName = "php_class";
$dbUser = "root";

$con = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser);

if ($post) {
  $query = "INSERT INTO post (words) VALUE (:words)";

  $stm = $con->prepare($query);

  $stm->bindParam(":words", $post);

  $stm->execute();
}

// Get list of posts
$query = "SELECT * FROM post";
$stm = $con->prepare($query);
$stm->execute();
$stm->setFetchMode(PDO::FETCH_ASSOC);

$results = array();

while ($row = $stm->fetch()) {
  array_push($results, $row);
}

echo json_encode($results);
