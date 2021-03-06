<?php

define("UPLOAD_DIR", "uploads/");

require 'db.php';

function redirect($url)
{
  header("Location: $url");
}

// Action dispatcher

$action = $_POST['action'] ?? false;

switch ($action) {
  case 'register':
    register();
    break;
  case 'login':
    login();
    break;
  case 'logout':
    logout();
    break;
  case 'addPost':
    addPost();
    break;
  case 'uploadFile':
    uploadFile();
    break;
  default:
    redirect("404.php");
}

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
      }
    } else {
      redirect("index.php");
    }
  } else {
    // Something went wrong with the DB
    redirect("index.php");
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

function addPost()
{
  session_start();
  $loggedIn = $_SESSION['userId'] ?? false;
  $content = $_POST['content'] ?? false;

  if (!$loggedIn || !$content) {
    redirect("posts.php");
    return;
  }

  global $con;

  $query = <<<QUERY
  INSERT INTO session_post (content, user_id, posted_on) 
  VALUE (:content , :uid, NOW())
  QUERY;

  $stm = $con->prepare($query);

  $stm->bindParam(":content", $content);
  $stm->bindParam(":uid", $_SESSION['userId']);

  $stm->execute();
  redirect("posts.php");
}


function uploadFile()
{
  session_start();
  $loggedIn = $_SESSION['userId'] ?? false;
  $title = $_POST['title'] ?? false;
  $newFile = $_FILES['newFile'] ?? false;

  if (!$loggedIn || !$title || !$newFile) {
    redirect("upload.php");
    return;
  }

  $storedName = time() . "_" . rand(1000, 9999);

  global $con;
  
  $query = <<<QUERY
  INSERT INTO uploaded_image (user_id, filename, stored_name, title, uploaded_on) 
  VALUE (:user_id, :filename, :stored_name, :title, NOW())
  QUERY;
  
  $stm = $con->prepare($query);
  
  $stm->bindParam(":user_id", $_SESSION['userId']);
  $stm->bindParam(":filename", $newFile['name']);
  $stm->bindParam(":stored_name", $storedName);
  $stm->bindParam(":title", $title);

  if(!$stm->execute()){
    redirect("upload.php");
    return;
  }

  move_uploaded_file($newFile['tmp_name'], UPLOAD_DIR . $storedName);

  redirect("upload.php");
}