<?php



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
