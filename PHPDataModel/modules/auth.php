<?php

require './model/User.php';

// --- Handler functions

function register()
{
  $username = $_POST['username'] ?? false;
  $password = $_POST['password'] ?? false;

  if (!$username || !$password) {
    echo "Need username and password";
    return;
  }

  $user = null;
  try {
    $user = new UserModel($username, $password);
    $user->register();
    redirect("posts.php");
  } catch (UsernameTakenException $ex) {
    redirect("index.php");
    authError("Username taken");
  }
}

function login()
{
  $username = $_POST['username'] ?? false;
  $password = $_POST['password'] ?? false;

  if (!$username || !$password) {
    echo "Need username and password";
    return;
  }

  $user = new UserModel($username, $password);
  if ($user->login()) {
    redirect("posts.php");
  } else {
    redirect("index.php");
    authError();
  }
}

function logout()
{
  session_start();
  session_destroy();
  redirect("index.php");
}
