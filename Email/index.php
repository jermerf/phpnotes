<?php

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
  $from = "$targetEmail <$targetName>";

  $subject = '=?utf-8?b?' . base64_encode($subject) . '?=';

  $headers  = "Content-type: text/html; charset=\"utf-8\"\r\n";
  $headers .= "From: " . $from . "\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Date: " . date('D, d M Y h:i:s O') . "\r\n";

  if (mail($targetEmail, $subject, $message, $headers)) {
    echo "Email sent";
  } else {
    echo "Error occurred";
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
    <input name="subject" value="Testing Email" /><br>
    <textarea name="message" cols="30">This is a test of the emergency broadcast kitten</textarea><br>
    <button>Send mail()</button>
  </form>
</body>

</html>