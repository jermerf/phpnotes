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
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<style>
  body,
  html {
    background-color: #333;
    color: #fff;
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
  }

  img {
    max-width: 100%;
  }

  .center {
    text-align: center;
  }

  .bold {
    font-weight: bold;
  }

  button {
    color: white;
    font-weight: bold;
    text-transform: uppercase;
    border-radius: 3px;
    border: 1px solid #041;
    background-color: #162;
    padding: 6px 14px;
  }

  #logo {
    max-height: 2em;
    margin: -4px 0;
  }

  nav {
    background-color: #444;
    padding: 10px 10px;
  }

  nav a {
    text-decoration: none;
    color: #ffc;
    padding: 11px 12px;
  }

  nav a:hover {
    background-color: #567;
  }

  h1 {
    text-align: center;
  }

  div#loginbox {
    position: absolute;
    top: 0;
    right: 0;
  }

  div#loginbox input {
    display: block;
  }

  div#currentUser {
    padding: 12px 12px;
    cursor: pointer;
    -moz-user-select: none;
    user-select: none;
    -webkit-user-select: none;
    text-transform: capitalize;
  }

  div#loginForm {
    display: none;
    background-color: #444;
    padding: 5px;
  }

  div#loginForm.visible {
    display: block;
  }

  input {
    border: 2px solid grey;
    border-radius: 3px;
    margin: 5px;
    padding: 4px 12px;
  }

  div#loginStatus {
    font-weight: bold;
    color: #e52;
  }

  ul.card-list {
    padding: 10px;
    list-style-type: none;
  }

  ul.card-list h3 {
    font-weight: normal;
  }

  ul.card-list li {
    background-color: #444;
    padding: 10px;
    margin: 4px 4px 12px 4px;
    position: relative;
    box-shadow: 0 0 15px #222;
  }

  ul.card-list li div {
    text-align: left;
    text-transform: capitalize;
  }

  ul.card-list li div span {
    float: right;
  }

  .contain {
    max-width: 500px;
    display: inline-block;
  }

  .delete {
    background-color: red;
    border-radius: 30px;
    border: 4px solid #770000;
    padding: 0 0 0px 1px;
    width: 30px;
    height: 30px;
    cursor: pointer;
    position: absolute;
    top: -7px;
    right: -7px;
    box-shadow: 0 0 7px black;
  }

  .delete:hover {
    border: 4px solid #ff4444;
    background-color: #ff7777;
  }

  button.revoke {
    border: 1px solid #401;
    background-color: #612;
  }

  form.edit-hidden {
    display: none;
  }
</style>