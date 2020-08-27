<?php

require_once __DIR__ . '/../db.php';

class Posts
{
  public static function showPosts($forCurrentUser = false, $forAdmin = false)
  {

    // If for current user, but not logged in
    if ($forCurrentUser && !$_SESSION['userId']) return;

    if ($forAdmin && !$_SESSION['admin']) return;

    global $con;

    $forUserPostId = ($forCurrentUser || $forAdmin ? "session_post.id," : "");
    $whereClause = ($forCurrentUser
      ? "WHERE session_post.user_id=:user_id"
      : ($forAdmin ? "" : "WHERE session_post.approved = 1"));

    $query = <<<JOINEDPOSTS
    SELECT 
      session_user.username,
      $forUserPostId
      session_post.content,
      session_post.posted_on,
      session_post.approved
    FROM session_post
    INNER JOIN session_user
      ON session_post.user_id=session_user.id
    $whereClause
    ORDER BY session_post.posted_on DESC
    JOINEDPOSTS;

    $stm = $con->prepare($query);

    if ($forCurrentUser) {
      $stm->bindParam(':user_id', $_SESSION['userId']);
    }

    $stm->execute();

    echo '<ul class="card-list">';
    while ($row = $stm->fetch(PDO::FETCH_ASSOC)) {
      $forUserDelete = ($forCurrentUser || $forAdmin ? <<<DELETEPOST
      <form action="server.php" method="POST">
        <input name="action" type="hidden" value="deletePost" />
        <input name="postId" type="hidden" value="{$row['id']}" />
        <button class="delete">
          <i class="fa fa-times"></i>
        </button>
      </form>
      DELETEPOST : "");

      $approved = ($row['approved']
        ? '<button class="revoke"><i class="fa fa-times"></i> Revoke Approval</button>'
        : '<button><i class="fa fa-check"></i> Approve</button>');

      $forAdminApprove = ($forAdmin ? <<<ADMINAPPROVE
      <form action="server.php" method="POST">
        <input name="action" type="hidden" value="togglePostApproval" />
        <input name="postId" type="hidden" value="{$row['id']}" />
         $approved
      </form>
      ADMINAPPROVE : "");

      echo <<<EACHPOST
      <li>
        $forAdminApprove
        $forUserDelete
        <h3>{$row['content']}</h3>
        <div>
          {$row['username']}
          <span>{$row['posted_on']}</span>
        </div>
      </li>
      EACHPOST;
    }
    echo "</ul>";
  }
}
