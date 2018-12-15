<?php

session_start();
 
header("Content-type: text/html; charset=utf-8");
 
//クロスサイトリクエストフォージェリ（CSRF）対策
$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes);
$token = $_SESSION['token'];
 
//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');
?>


<!DOCTYPE html>
<html>
<head lang="en">
<meta charset="UTF-8">
<title>email_registration</title>
</head>

<body>
<form action="check_email_registration.php" method="post">
<center>

<h1>Email Registration Screen</h1>

<br><br>
<input type="text" name="mail" size="50" placeholder="Enter your email"><br><br>
<br><br>
<input type="hidden" name="token" value="<?=$token?>">
<button type="submit" name="submitbutton">Submit</button>

</center>
</form>

</html>