<?php

header("Content-type: application/json; charset=utf-8");

require 'db.php';

$res = new stdClass();
$res->success = false;

// Action dispatcher

$action = $_POST['action'] ?? false;

switch ($action) {
  case 'register':
    register();
    break;
  case 'login':
    login();
    break;
  default:
    $res->message = "Unhandled action";
}

echo json_encode($res);

// --- Handler functions


function register()
{
  $username = $_POST['username'] ?? false;
  $password = $_POST['password'] ?? false;

  if (!$username || !$password) {
    echo "Need username and password";
    return;
  }

  global $con, $res;

  $query = <<<QUERY
  INSERT INTO session_user (username, password) VALUE (:uname , :pword)
  QUERY;

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  $stm = $con->prepare($query);

  $stm->bindParam(":uname", $username);
  $stm->bindParam(":pword", $hashedPassword);

  if ($stm->execute()) {
    loginSuccess([
      'id' => $con->lastInsertId(),
      'username' => $username,
      'admin' => false
    ]);
  } else {
    $res->message =  "Username taken";
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

  global $con, $res;

  $query = <<<QUERY
  SELECT id, username, password, last_login, admin 
  FROM session_user 
  WHERE username = :uname
  QUERY;

  $stm = $con->prepare($query);

  $stm->bindParam(":uname", $username);

  if ($stm->execute()) {
    if ($user = $stm->fetch(PDO::FETCH_ASSOC)) {
      // Found a user
      if (password_verify($password, $user['password'])) {
        // Login successful
        loginSuccess($user);
      } else {
        $res->message = "Bad Credentials";
      }
    } else {
      $res->message = "Bad Credentials";
    }
  } else {
    // Something went wrong with the DB
    $res->message = "Bad Credentials";
  }
}

function loginSuccess($user)
{
  global $con, $res;

  $res->success = true;
  $res->username = $user['username'];
  $res->admin = !!$user['admin'];

  // Update the last_login
  $query = "UPDATE session_user SET (last_login = UTC_TIMESTAMP()) WHERE id = :uid";

  $stm = $con->prepare($query);
  $stm->bindParam(':uid', $user['id']);

  // TODO Why does this fail?
  if ($stm->execute()) {
    echo 'good';
  } else {
    echo 'bad';
  }

  // Remember that we're logged in - WITH SESSIONS!!!
  session_start();
  $_SESSION['userId'] = $user['id'];
  $_SESSION['username'] = $user['username'];
  $_SESSION['admin'] = $user['admin'];
}
