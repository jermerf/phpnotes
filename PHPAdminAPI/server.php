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
  case 'deletePost':
    deletePost();
    break;
  case 'uploadFile':
    uploadFile();
    break;
  case 'deleteUpload':
    deleteUpload();
    break;
  case 'togglePostApproval':
    togglePostApproval();
  case 'toggleUploadApproval':
    toggleUploadApproval();
    break;
  default:
    redirect("404.php");
}
// --- Helper functions

function authError($msg = "Bad Credentials")
{
  setcookie("authError", $msg, time() + 3);
}

function setStatusMessage($msg = "")
{
  setcookie("statusMsg", $msg, time() + 3);
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

function addPost()
{
  session_start();
  $loggedIn = $_SESSION['userId'] ?? false;
  $content = $_POST['content'] ?? false;

  if (!$loggedIn || !$content) {
    redirect("posts.php");
    setStatusMessage("Missing parameters");
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

  if ($stm->execute()) {
    setStatusMessage("Added post");
  } else {
    setStatusMessage("Failed to add post");
  }
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

  if (!$stm->execute()) {
    redirect("upload.php");
    setStatusMessage("Upload failed");
    return;
  }

  move_uploaded_file($newFile['tmp_name'], UPLOAD_DIR . $storedName);

  redirect("upload.php");
  setStatusMessage("Upload Success");
}

function deleteUpload()
{
  session_start();
  $userId = $_SESSION['userId'] ?? false;
  $uploadId = $_POST['uploadId'] ?? false;

  if (!$userId || !$uploadId) {
    redirect("upload.php");
    return;
  }

  global $con;

  $query = <<<QUERY
  DELETE FROM uploaded_image WHERE id = :upid AND user_id = :user_id;
  QUERY;

  $stm = $con->prepare($query);

  $stm->bindParam(":user_id", $_SESSION['userId']);
  $stm->bindParam(":upid", $uploadId);

  if ($stm->execute()) {
    setStatusMessage("Upload deleted");
  } else {
    setStatusMessage("Upload delete failed. Something went wrong.");
  }
  redirect("upload.php");
}



function deletePost()
{
  session_start();
  $userId = $_SESSION['userId'] ?? false;
  $postId = $_POST['postId'] ?? false;

  if (!$userId || !$postId) {
    redirect("posts.php");
    setStatusMessage("Missing parameters");
    return;
  }

  global $con;

  $forAdminWhereClause = ($_SESSION['admin'] ? "" : " AND user_id = :user_id");

  $query = <<<QUERY
  DELETE FROM session_post WHERE id = :postid $forAdminWhereClause;
  QUERY;

  $stm = $con->prepare($query);
  if (!$_SESSION['admin']) {
    $stm->bindParam(":user_id", $_SESSION['userId']);
  }
  $stm->bindParam(":postid", $postId);

  if ($stm->execute()) {
    setStatusMessage("Post deleted");
  } else {
    setStatusMessage("Post delete failed. Something went wrong.");
  }

  if ($_SESSION['admin']) {
    redirect("admin.php");
  } else {
    redirect("posts.php");
  }
}

function togglePostApproval()
{
  session_start();

  if (!$_SESSION['admin']) return;

  $postId = $_POST['postId'] ?? false;

  if (!$postId) {
    redirect("admin.php");
    setStatusMessage("Missing parameters");
    return;
  }

  global $con;

  $query = <<<QUERY
  UPDATE session_post SET approved = 1 - approved WHERE id = :postid;
  QUERY;

  $stm = $con->prepare($query);

  $stm->bindParam(":postid", $postId);

  if ($stm->execute()) {
    setStatusMessage("Post approval toggled");
  } else {
    setStatusMessage("Post approval toggle failed. Something went wrong.");
  }

  redirect("admin.php");
}

function toggleUploadApproval()
{
  session_start();

  if (!$_SESSION['admin']) return;

  $uploadId = $_POST['uploadId'] ?? false;

  if (!$uploadId) {
    redirect("admin.php");
    setStatusMessage("Missing parameters");
    return;
  }

  global $con;

  $query = <<<QUERY
  UPDATE uploaded_image SET approved = 1 - approved WHERE id = :uploadid;
  QUERY;

  $stm = $con->prepare($query);

  $stm->bindParam(":uploadid", $uploadId);

  if ($stm->execute()) {
    setStatusMessage("Upload approval toggled");
  } else {
    setStatusMessage("Upload approval toggle failed. Something went wrong.");
  }

  redirect("admin.php");
}
