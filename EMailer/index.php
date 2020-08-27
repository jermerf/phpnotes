<?php

require('vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_GET) {
  sendMail();
}

function sendMail()
{
  $targetName = $_GET['targetName'];
  $targetEmail = $_GET['targetEmail'];
  $subject = $_GET['subject'];
  $message = $_GET['message'];

  if (!($targetName && $targetEmail && $subject && $message)) {
    echo "Missing required parameter";
    return;
  }

  $mail = new PHPMailer(true);

  try {
    // Server setup, to connect to smtp server
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = 'jermerf.teacher@gmail.com';
    $mail->Password = 'M7w^mQ4&FR2Q';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Email Details
    $mail->setFrom('jermerf.teacher@gmail.com', "Jermaine Francoeur");
    $mail->addAddress($targetEmail, $targetName);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->send();
  } catch (Exception $ex) {
    echo "Error sending mail<hr>";
    var_dump($ex);
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <form action="index.php" method="get">
    <input name="targetName" value="Jermaine"><br>
    <input name="targetEmail" value="jermerf@gmail.com" type="email"><br>
    <input name="subject" value="Using PHPMailer" /><br>
    <textarea name="message" cols="30">The cats have taken over. The end is nigh!</textarea><br>
    <button>Send mail()</button>
  </form>
</body>

</html>