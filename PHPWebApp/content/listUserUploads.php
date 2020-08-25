<ul class="card-list">
  <?php

  if (!$loggedIn) return;

  define("UPLOAD_DIR", "uploads/");

  require 'db.php';

  $query = <<<JOINEDUPLOADS
  SELECT 
    session_user.username,
    uploaded_image.id,
    uploaded_image.title,
    uploaded_image.stored_name,
    uploaded_image.uploaded_on
  FROM uploaded_image
  INNER JOIN session_user
    ON uploaded_image.user_id=session_user.id
  WHERE uploaded_image.user_id=:user_id
  ORDER BY uploaded_image.uploaded_on DESC
  JOINEDUPLOADS;

  $stm = $con->prepare($query);

  $stm->bindParam(':user_id', $_SESSION['userId']);

  $stm->execute();

  while ($row = $stm->fetch(PDO::FETCH_ASSOC)) {
    $uploadPath = UPLOAD_DIR . $row['stored_name'];
    echo <<<EACHPOST
    <li>
      <form action="server.php" method="POST">
        <input name="action" type="hidden" value="deleteUpload" />
        <input name="uploadId" type="hidden" value="{$row['id']}" />
        <button class="delete">X</button>
      </form>
      <img src="$uploadPath" />
      <h3>{$row['title']}</h3>
      <div>
        {$row['username']}
        <span>{$row['uploaded_on']}</span>
      </div>
    </li>
    EACHPOST;
  }

  ?>
</ul>