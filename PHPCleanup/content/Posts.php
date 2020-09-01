<?php

require_once __DIR__ . '/../modules/db.php';

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

    $today = new DateTime();
    $todayDateStr = $today->format("Ymd");
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

      $forUserEdit = ($forCurrentUser || $forAdmin ? <<<EDITPOST
      <div>
        <button onclick="toggleEditPost({$row['id']})">
          <i class="fa fa-pencil"></i>
        </button>
        
        <form action="server.php" method="POST" class="edit-hidden">
          <input name="action" type="hidden" value="editPost" />
          <input name="postId" type="hidden" value="{$row['id']}" />
          <textarea name="content" cols="60" rows="5">{$row['content']}</textarea>
          <br>
          <button>
            <i class="fa fa-save"></i> Save
          </button>
        </form>
      </div>
      EDITPOST : "");

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

      // Formats date nicely
      $date = new DateTime($row['posted_on']);
      $formattedDate = $date->format("l, j F Y");

      $dateStr = $date->format("Ymd");
      if (strcmp($todayDateStr, $dateStr) == 0) {
        $formattedDate = "Today at " . $date->format("g:ia");
      }

      $postId = $row['id'] ?? '';

      if ($postId) {
        $postId = "id='post$postId'";
      }
      $content = Posts::processContent($row['content']);

      echo <<<EACHPOST
      <li $postId>
        $forAdminApprove
        $forUserEdit
        $forUserDelete
        <h3>{$content}</h3>
        <div>
          {$row['username']}
          <span>$formattedDate</span>
        </div>
      </li>
      EACHPOST;
    }
    echo "</ul>";
  }
  public static function processContent($raw)
  {
    $content = "";
    $parts1 = explode("{{", $raw);
    foreach ($parts1 as $p) {
      $parts2 = explode("}}", $p);
      if (count($parts2) == 1) {
        $content .= $p;
      } else {
        $token = $parts2[0];
        $content .= Posts::processContentToken($token);
        $content .= $parts2[1];
      }
    }

    $content = str_replace("\n", "<br>", $content);

    return $content;
  }
  // Tokens are of this format
  // href:Go to Google:https://google.com
  // img:3[:h[:w]]
  // bold:destroy
  public static function processContentToken($token)
  {
    $parts = explode(":", $token);

    if (count($parts) < 2) {
      return $token; // Invalid token
    }

    $protocol = $parts[0];

    switch ($protocol) {
      case "href":
        array_shift($parts);
        $title = array_shift($parts);
        $url = implode(":", $parts);
        return "<a href='$url'>$title</a>";
      case "img":
        $url = UploadedImages::getImageURL($parts[1]);
        $h = $parts[2] ?? '';
        $w = $parts[3] ?? '';
        $style = "";
        if ($h || $w) {
          $style = "style='height:{$h}px;";
          if ($h) $style .= "width:{$w}px";
          $style .= "'";
        }
        return "<img src='$url' $style>";
      case "bold":
        $text = $parts[1];
        return "<span class='bold'>$text</span>";
      default:
        return $token; // Invalid token
    }
  }
}
