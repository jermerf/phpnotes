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

function comment()
{
  session_start();
  $loggedIn = $_SESSION['userId'] ?? false;
  $postId = $_POST['postId'] ?? false;
  $content = $_POST['content'] ?? false;

  if (!($loggedIn && $postId && $content)) {
    redirect("index.php");
    setStatusMessage("Missing parameters");
    return;
  }

  global $con;

  $query = <<<QUERY
  INSERT INTO post_comment (user_id, post_id, content, posted_on) 
  VALUE (:uid, :pid, :content, NOW())
  QUERY;

  $stm = $con->prepare($query);

  $stm->bindParam(":uid", $_SESSION['userId']);
  $stm->bindParam(":pid", $postId);
  $stm->bindParam(":content", $content);

  if ($stm->execute()) {
    setStatusMessage("Added comment");
  } else {
    setStatusMessage("Failed to add comment");
  }
  redirect("index.php");
}

function commentsForPost()
{
  header("Content-Type: application/json; charset=UTF-8");
  $res = new stdClass();
  $res->success = false;

  $postId = $_POST['postId'];

  if (!$postId) {
    $res->message = "No postId provided";
    echo json_encode($res);
    return;
  }

  global $con;

  $query = <<<COMMENTSFORPOST
  SELECT
    post_comment.content,
    post_comment.posted_on,
    session_user.username
  FROM post_comment
  INNER JOIN session_user
    ON post_comment.user_id=session_user.id
  WHERE post_comment.post_id=:pid
  COMMENTSFORPOST;

  $stm = $con->prepare($query);
  $stm->bindParam(':pid', $postId);

  if ($stm->execute()) {
    $res->success = true;
    $res->comments = array();
    while ($row = $stm->fetch(PDO::FETCH_ASSOC)) {
      array_push($res->comments, $row);
    }
  } else {
    $res->message = "Database error";
  }

  echo json_encode($res);
}
