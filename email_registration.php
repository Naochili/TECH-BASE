<?php
//テーブル作成
$sql= "CREATE TABLE registration"
."("
."id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
."urltoken VARCHAR(128) NOT NULL,"
."email varchar(64) NOT NULL,"
."date DATETIME NOT NULL,"
.");";

session_start();
 
header("Content-type: text/html; charset=UTF-8");
 
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
<form action="check_email__registration.php" method="post">
<center>

<h1>Email registration screen</h1>

<br><br>
<input type="text" name="email" size="50" placeholder="Enter your email"><br><br>
<br><br>
<input type="hidden" name="token" value="<?=$token?>">
<button type="submit" name="submitbutton">Submit</button>

</center>
</form>

</html>