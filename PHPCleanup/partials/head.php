<?php

require __DIR__ . '/../content/Posts.php';
require __DIR__ . '/../content/UploadedImages.php';

session_start();
$loggedIn = $_SESSION['userId'] ?? false;
$isAdmin = $_SESSION['admin'] ?? false;

?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="img/logo.png">
<title>Pure PHP App</title>
<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<script src="js/main.js"></script>