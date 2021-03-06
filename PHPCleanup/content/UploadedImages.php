<?php

require_once __DIR__ . '/../modules/db.php';

class UploadedImages
{
  const UPLOAD_DIR = "uploads/";
  public static function showUploads($forCurrentUser = false, $forAdmin = false)
  {
    // If for current user, but not logged in
    if ($forCurrentUser && !$_SESSION['userId']) return;

    if ($forAdmin && !$_SESSION['admin']) return;

    global $con;

    $forUserUploadId = ($forCurrentUser || $forAdmin ? "uploaded_image.id," : "");
    $whereClause = ($forCurrentUser
      ? "WHERE uploaded_image.user_id=:user_id"
      : ($forAdmin ? "" : "WHERE uploaded_image.approved = 1"));

    $query = <<<JOINEDUPLOADS
    SELECT 
      session_user.username,
      $forUserUploadId
      uploaded_image.title,
      uploaded_image.stored_name,
      uploaded_image.uploaded_on,
      uploaded_image.approved
    FROM uploaded_image
    INNER JOIN session_user
      ON uploaded_image.user_id=session_user.id
    $whereClause                  
    ORDER BY uploaded_image.uploaded_on DESC
    JOINEDUPLOADS;

    $stm = $con->prepare($query);

    if ($forCurrentUser) {
      $stm->bindParam(':user_id', $_SESSION['userId']);
    }

    if ($forCurrentUser) {
      $stm->bindParam(':user_id', $_SESSION['userId']);
    }

    $stm->execute();


    $today = new DateTime();
    $todayDateStr = $today->format("Ymd");
    echo '<ul class="card-list">';
    while ($row = $stm->fetch(PDO::FETCH_ASSOC)) {
      $uploadPath = UploadedImages::UPLOAD_DIR . $row['stored_name'];
      $forUserDelete = ($forCurrentUser || $forAdmin ? <<<DELETEUPLOAD
      <form action="server.php" method="POST">
        <input name="action" type="hidden" value="deleteUpload" />
        <input name="uploadId" type="hidden" value="{$row['id']}" />
        <button class="delete">X</button>
      </form>
      DELETEUPLOAD : "");

      $approved = ($row['approved']
        ? '<button class="revoke"><i class="fa fa-times"></i> Revoke Approval</button>'
        : '<button><i class="fa fa-check"></i> Approve</button>');

      $forAdminApprove = ($forAdmin ? <<<ADMINAPPROVE
      <form action="server.php" method="POST">
        <input name="action" type="hidden" value="toggleUploadApproval" />
        <input name="uploadId" type="hidden" value="{$row['id']}" />
        $approved
      </form>
      ADMINAPPROVE : "");

      // Formats date nicely
      $date = new DateTime($row['uploaded_on']);
      $formattedDate = $date->format("l, j F Y");

      $dateStr = $date->format("Ymd");
      if (strcmp($todayDateStr, $dateStr) == 0) {
        $formattedDate = "Today at " . $date->format("g:ia");
      }

      echo <<<EACHUPLOAD
      <li>
        $forAdminApprove
        $forUserDelete
        <img src="$uploadPath" />
        <h3>{$row['title']}</h3>
        <div>
          {$row['username']}
          <span>$formattedDate</span>
        </div>
      </li>
      EACHUPLOAD;
    }
    echo "</ul>";
  }
  public static function getImageURL($id)
  {
    global $con;

    $query = "SELECT stored_name FROM uploaded_image WHERE id = :imageId";

    $stm = $con->prepare($query);
    $stm->bindParam(':imageId', $id);

    try {
      $stm->execute();
      $row = $stm->fetch(PDO::FETCH_ASSOC);

      return UploadedImages::UPLOAD_DIR . $row['stored_name'];
    } catch (Exception $ex) {
      return "img/logo.png";
    }
  }
}
