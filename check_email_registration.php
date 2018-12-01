<?php
session_start();
 
header("Content-type: text/html; charset=UTF-8");
 
//�N���X�T�C�g���N�G�X�g�t�H�[�W�F���iCSRF�j�΍�̃g�[�N������
if ($_POST['token'] != $_SESSION['token']){
	echo "You may have possibility of unauthorized access! ";
	exit();
}
 
//�N���b�N�W���b�L���O�΍�
header('X-FRAME-OPTIONS: SAMEORIGIN');

//�f�[�^�x�[�X�ڑ�
require_once("db.php");
$dbh = db_connect();

//�G���[���b�Z�[�W�̏�����
$errors = array();

if(empty($_POST)) {
	header("Location: email_registration.php");
	exit();
}else{
	//POST���ꂽ�f�[�^��ϐ��ɓ����
	$mail = isset($_POST['email']) ? $_POST['email'] : NULL;

	//���[�����͔���
	if ($mail == ''){
		$errors['mail'] = "Write your email address.";
	}else{
		if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail)){
			$errors['mail_check'] = "Your email address form isn't right.";
		}
		
		


	}



}

if (count($errors) === 0){
	
	$urltoken = hash('sha256',uniqid(rand(),1));
	$url = "http://tt-555.99sv-coco.com/email_registration.php"."?urltoken=".$urltoken;
	
	//�����Ńf�[�^�x�[�X�ɓo�^����
	try{
		//��O�����𓊂���i�X���[�j�悤�ɂ���
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$statement = $dbh->prepare("INSERT INTO pre_member (urltoken,email,date) VALUES (:urltoken,:email,now() )");
		
		//�v���[�X�z���_�֎��ۂ̒l��ݒ肷��
		$statement->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
		$statement->bindValue(':email', $mail, PDO::PARAM_STR);
		$statement->execute();
			
		//�f�[�^�x�[�X�ڑ��ؒf
		$dbh = null;	
		
	}catch (PDOException $e){
		print('Error:'.$e->getMessage());
		die();
	}
	
	//���[���̈���
	$mailTo = $email;
 
	//Return-Path�Ɏw�肷�郁�[���A�h���X
	$returnMail = 'tt-555.99sv-coco.com';
 
	$name = "japanese foods book";
	$email = 'tt-555.99sv-coco.com';
	$subject = "Registration on Japanese Foods Book";
 
$body = <<< EOM
Registrate it within 24h from the folowing URL.
{$url}
EOM;
 
	mb_language('en');
	mb_internal_encoding('UTF-8');
 
	//From�w�b�_�[���쐬
	$header = 'From: ' . mb_encode_mimeheader($name). ' <' . $email. '>';
 
	if (mb_send_mail($mailTo, $subject, $body, $header, '-f'. $returnMail)) {
	
	 	//�Z�b�V�����ϐ���S�ĉ���
		$_SESSION = array();
	
		//�N�b�L�[�̍폜
		if (isset($_COOKIE["PHPSESSID"])) {
			setcookie("PHPSESSID", '', time() - 1800, '/');
		}
	
 		//�Z�b�V������j������
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
a href="<?=$url?>"><?=$url?></a>

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