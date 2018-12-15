<?php
session_start();
 
header("Content-type: text/html; charset=UTF-8");
 
//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
if ($_POST['token'] != $_SESSION['token']){
	echo "You may have possibility of unauthorized access! ";
	exit();
}
 
//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

//データベース接続
require_once("db.php");
$dbh = db_connect();


//エラーメッセージの初期化
$errors = array();

if(empty($_POST)) {
	header("Location: email_registration.php");
	exit();
}else{
	//POSTされたデータを変数に入れる
	$mail = isset($_POST['mail']) ? $_POST['mail'] : NULL;

	//メール入力判定
	if ($mail == ''){
		$errors['mail'] = "Write your email address.";
	}else{
		if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail)){
			$errors['mail_check'] = "Your email address form isn't right.";
		}
		
		


	}



}

if (count($errors) === 0){
	
	$token = hash('sha256',uniqid(rand(),1));
	$url = "http://tt-555.99sv-coco.com/registration_form.php"."?urltoken=".$token;
	
	//ここでデータベースに登録する
	try{
		//例外処理を投げる（スロー）ようにする
		
		
		$sql = $dbh->prepare("INSERT INTO pre_member (urltoken,mail,date) VALUES (:urltoken,:mail,,now() )");
		
		//プレースホルダへ実際の値を設定する
		$sql->bindParam(':urltoken', $urltoken, PDO::PARAM_STR);
		$sql->bindParam(':mail', $mail, PDO::PARAM_STR);
		$sql-> execute();
			
		//データベース接続切断
		$dbh = null;	
		
	}catch (PDOException $e){
		print('Error:'.$e->getMessage());
		die();
	}
	
	//メールの宛先
	$mailTo = $mail;
 
	//Return-Pathに指定するメールアドレス
	$returnMail = 'tt-555.99sv-coco.com';
 
	$name = "japanese foods book";
	$mail = 'tt-555.99sv-coco.com';
	$subject = "Registration on Japanese Foods Book";
 
$body = <<< EOM
Registrate it within 24h from the folowing URL.
{$url}
EOM;
 
	mb_language('en');
	mb_internal_encoding('UTF-8');
 
	//Fromヘッダーを作成
	$header = 'From: ' . mb_encode_mimeheader($name). ' <' . $mail. '>';
 
	if (mb_send_mail($mailTo, $subject, $body, $header, '-f'. $returnMail)) {
	
	 	//セッション変数を全て解除
		$_SESSION = array();
	
		//クッキーの削除
		if (isset($_COOKIE["PHPSESSID"])) {
			setcookie("PHPSESSID", '', time() - 1800, '/');
		}
	
 		//セッションを破棄する
 		session_destroy();
 	
 		$message = "We sent you an email.Please registrate your account within 24h from folowing URL.";
 	
	 } else {
		$errors['mail_error'] = "Failed sending email";
	}	
}

?>

<!DOCTYPE html>
<html>
<head lang="en">
<meta charset="UTF-8">
<title>email_confirmation</title>
</head>

<body>
<form>
<center>
<br><br><br>
<h1>Email confirmation screen</h1>

<?php if (count($errors) === 0): ?>

<p><?=$message?></p>

<p>We sent you a email with the following URL</p>
<a href="<?=$url?>"><?=$url?></a>

<?php elseif(count($errors) > 0): ?>

<?php
foreach($errors as $value){
	echo "<p>".$value."</p>";
}
?>
 
<input type="button" value="back" onClick="history.back()">
 
<?php endif; ?>
 
</center>
</form>
</body>
</html>