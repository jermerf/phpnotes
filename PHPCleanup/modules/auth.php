<?php

// --- Handler functions

function register()
{
  $username = $_POST['username'] ?? false;
  $password = $_POST['password'] ?? false;

  if (!$username || !$password) {
    echo "Need username and password";
    return;
  }

  global $con;

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

  global $con;

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
        redirect("index.php");
        authError();
      }
    } else {
      redirect("index.php");
      authError();
    }
  } else {
    // Something went wrong with the DB
    redirect("index.php");
    authError("Internal DB Error");
  }
}

function loginSuccess($user)
{
  global $con;

  // Update the last_login
  $query = "UPDATE session_user SET last_login = NOW() WHERE id = :uid";

  $stm = $con->prepare($query);
  $stm->bindParam(':uid', $user['id']);

  $stm->execute();

  redirect("posts.php");

  // Remember that we're logged in - WITH SESSIONS!!!
  session_start();
  $_SESSION['userId'] = $user['id'];
  $_SESSION['username'] = $user['username'];
  $_SESSION['admin'] = $user['admin'];
}

function logout()
{
  session_start();
  session_destroy();
  redirect("index.php");
}
