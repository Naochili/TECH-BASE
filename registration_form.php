<?php
session_start();
 
header("Content-type: text/html; charset=utf-8");
 
//クロスサイトリクエストフォージェリ（CSRF）対策
$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes);
$token = $_SESSION['token'];
 
//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');
 
//データベース接続
require_once("db.php");
$dbh = db_connect();
 
//エラーメッセージの初期化
$errors = array();
 
if(empty($_GET)) {
	header("Location: email_registration.php");
	exit();
}else{
	//GETデータを変数に入れる
	$token = isset($_GET['urltoken']) ? $_GET['urltoken'] : NULL;
	//メール入力判定
	if ($token == ''){
		$errors['urltoken'] = "Try registration again.";
	}else{
		try{
			//例外処理を投げる（スロー）ようにする
			$sql = $dbh->prepare("INSERT INTO pre_member (urltoken,mail,date) VALUES (:urltoken,:mail,,now() )");
			
			//flagが0の未登録者・仮登録日から24時間以内
			$sql = $dbh->prepare("SELECT COUNT(*) FROM pre_member WHERE urltoken=(:urltoken) AND flag =0 AND date > now() - interval 24 hour");
			$sql->bindParam(':urltoken', $token, PDO::PARAM_STR);
			$sql->execute();
			
			//レコード件数取得
			$fetch = $sql->fetchColumn();
			echo $fetch;
			
			//24時間以内に仮登録され、本登録されていないトークンの場合
			if($fetch==1){
				$mail_array = $sql->fetch();
				$mail = $mail_array[mail];
				$_SESSION['mail'] = $mail;
			}else{
				$errors['urltoken'] = "You can't use this URL.You may have the problems like overdue.Try registrate it again.";
			}
			
			//データベース接続切断
			$dbh = null;
			
		}catch (PDOException $e){
			print('Error:'.$e->getMessage());
			die();
		}
	}
}
 
?>
 
<!DOCTYPE html>
<html lang="en">
<head >
<title>Registration Form</title>
<meta charset="utf-8">
</head>
<body>
<h1>User Registration Form Screen</h1>
 
<?php if (count($errors) === 0): ?>
 
<form action="check_registration.php" method="post">
 
<p>E-mail：<?=htmlspecialchars($mail, ENT_QUOTES, 'UTF-8')?></p>
<p>Account name：<input type="text" name="account"></p>
<p>Password：<input type="password" name="password"></p>
 
<input type="hidden" name="token" value="<?=$token?>">
<input type="submit" value="Confirmation">
 
</form>
 
<?php elseif(count($errors) > 0): ?>
 
<?php
foreach($errors as $value){
	echo "<p>".$value."</p>";
}
?>
 
<?php endif; ?>
 
</body>
</html>