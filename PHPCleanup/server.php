<?php

define("UPLOAD_DIR", "uploads/");

require 'modules/db.php';
require 'modules/auth.php';
require 'modules/post.php';
require 'modules/upload.php';

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
  case 'editPost':
    editPost();
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
