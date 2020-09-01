<?php
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


function editPost()
{
  session_start();
  $loggedIn = $_SESSION['userId'] ?? false;
  $content = $_POST['content'] ?? false;
  $postId = $_POST['postId'] ?? false;

  if (!($loggedIn && $content && $postId)) {
    redirect("posts.php");
    setStatusMessage("Missing parameters");
    return;
  }

  global $con;

  $query = <<<QUERY
  UPDATE session_post SET content = :content 
  WHERE 
    id = :postId 
    AND user_id = :uid
  QUERY;

  $stm = $con->prepare($query);

  $stm->bindParam(":content", $content);
  $stm->bindParam(":postId", $postId);
  $stm->bindParam(":uid", $_SESSION['userId']);

  try {
    if ($stm->execute()) {
      setStatusMessage("Post saved");
    } else {
      setStatusMessage("Failed to save post");
    }
  } catch (Exception $ex) {
    setStatusMessage("Internal Server Error");
  }
  redirect("posts.php");
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

  $isFromPosts = strrpos($_SERVER['HTTP_REFERER'], "posts.php");
  if ($_SESSION['admin'] && !$isFromPosts) {
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
