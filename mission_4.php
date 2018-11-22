<?php
//****テーブル作成****
//MySQLに接続
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password);


//テーブル作成
$sql= "CREATE TABLE tb_mission_4_Fujii"	//テーブル作成
."("
."id INT auto_increment,"	//投稿番号（投稿ごとに1ずつ増える）
."name char(32),"	//名前
."comment TEXT,"		//コメント
."date DATETIME,"	//投稿日時
."pass char(30)"	//パスワード
.");";
$stmt = $pdo->query($sql);	//queryメソッドで実行
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<title>mission_4</title>
<meta charset ="UTF-8">
</head>
<body>

<?php
/****新規投稿****/
$name=$_POST['name'];
$comment=$_POST['comment'];
$pass=$_POST['pass'];
$pass_1="1234";

//名前とコメントが送信され、削除が投稿されず、投稿番号が書いていないとき//
if(isset($_POST['submitbutton']) and !isset($_POST['deletebutton']) and empty($_POST['editnumber2'])){
if($pass != $pass_1){
 echo"パスワードが違います。";
}elseif($pass == $pass_1){
 /*****MySQLに接続*****/
 $dsn = 'データベース名';
 $user = 'ユーザー名';
 $password = 'パスワード';
 $pdo = new PDO($dsn,$user,$password);	//接続

 /*****テーブルに投稿内容挿入*******/
 $sql=$pdo->prepare("INSERT INTO tb_mission_4_Fujii (name,comment,date,pass) VALUES (:name,:comment,:date,:pass)");	//idは指定しない（idをauto_incrementにすればidの値指定しない時自動で振られる（連番で）ので）
 $sql->bindParam(':name', $name, PDO::PARAM_STR);  //名前
 $sql->bindParam(':comment', $comment, PDO::PARAM_STR); //コメント
 $sql->bindParam(':date', $date, PDO::PARAM_STR);//日時
 $sql->bindParam(':pass', $pass, PDO::PARAM_STR);//パスワード
 $name=$_POST['name'];   //name変数にフォームで送られた名前入れる
 $comment=$_POST['comment'];   //comment変数に送られたコメント入れる
 $date=date("Y/m/d H:i:s");  //date関数で取得した現在日時＝投稿日時
 $pass=$_POST['pass'];
 $sql-> execute();//クエリの実行
}//$pass == $pass_1
}//if(isset($_POST['submitbutton']) and !isset($_POST['deletebutton']) and empty($_POST['editnumber2']))
?>

<?php
/****** 削除 ********/
if(isset($_POST['deletebutton']) and !empty($_POST['pass_del'])){  //削除ボタン押され、編集ボタンが押されないとき
    $delnumber=$_POST['delnumber'];              //送信された番号(削除番号）
    $pass_del=$_POST['pass_del'];               //入力されたパスワード
    $pass_2="1234";
if($pass_del != $pass_2){
 echo"パスワードが違います。";
}elseif($pass_del == $pass_2){
 /*****MySQLに接続*****/
 $dsn = 'データベース名';
 $user = 'ユーザー名';
 $password = 'パスワード';
 $pdo = new PDO($dsn,$user,$password);	//接続
 /*****データを取り出す*****/
 $sql='SELECT*FROM tb_mission_4_Fujii'; //selectで全てのデータを取り出す
 $results= $pdo->query($sql);
  foreach ($results as $row){  //ループ処理(1つずつ取り出し）
    if($row['id']== $delnumber and $row['pass']==$pass_del){ //パスと投稿番号一致
     $id=$delnumber;
     $sql="delete from tb_mission_4_Fujii where id=$id"; //idが一致する投稿を削除
     $result=$pdo->query($sql);
    }// if($row['id']== $delnumber and $row['pass']==$pass_del)
  }//foreach
}//$pass_del == $pass_2
}//isset($_POST['deletebutton']) and !empty($_POST['pass_del'])

?>

<?php
/***** 編集 *****/
if(isset($_POST['editbutton']) and !empty($_POST['pass_edit'])){      //編集ボタン押されたとき
   $editnumber=$_POST['editnumber'];           //送信された値   
   $pass_edit=$_POST['pass_edit'];          //入力されたパスワード 
   $pass_3="1234";
 if($pass_edit != $pass_3){
  echo"パスワードが違います。";
 }elseif($pass_edit == $pass_3){
   /*******MySQLに接続********/
   $dsn = 'データベース名';
   $user = 'ユーザー名';
   $password = 'パスワード';
   $pdo = new PDO($dsn,$user,$password);	//接続
   /******データを取り出す******/
   $sql='SELECT*FROM tb_mission_4_Fujii'; //selectで全てのデータを取り出す
   $results= $pdo->query($sql);
  foreach ($results as $row){  //ループ処理(1つずつ取り出し）
    if($row['id']== $editnumber and $row['pass']==$pass_edit){ //パスと投稿番号一致
      $oldname=$row['name'];           //以前書き込まれた名前
      $oldcomment=$row['comment'];          //以前書き込まれたコメント
      $oldpass=$row['pass'];              //保存された以前のパスワード
    }// if($value_del[0]== $deleteNo and $pass_del==$value_del[4])
  }//foreach
 }//$pass_edit == $pass_3
}//if(isset($_POST['editbutton']) and !empty('pass_edit'))
?>
<?php
/******* 編集上書き ********/
if(isset($_POST['submitbutton']) and !empty($_POST['editnumber2']) and !empty($_POST['pass'])){   //送信ボタン押され、編集番号が入っている時
       $editnumber2=$_POST['editnumber2'];         //テキストボックスの値
       $newname=$_POST['name'];                //編集された名前
       $newcomment=$_POST['comment'];          //編集されたコメント
       $date= date("Y/m/d H:i:s");
       $pass_new=$_POST['pass'];                 //入力されたパスワード
 	/*******MySQLに接続********/
       $dsn = 'データベース名';
       $user = 'ユーザー名';
       $password = 'パスワード';
       $pdo = new PDO($dsn,$user,$password);	//接続

	/******データを取り出す******/
        $sql='SELECT*FROM tb_mission_4_Fujii'; //selectで全てのデータを取り出す
	$results= $pdo->query($sql);
         foreach ($results as $row){  //ループ処理(1つずつ取り出し)
             if($row['id']==$editnumber2){             //テキストボックスの値と投稿番号が一致する時
                $id=$editnumber2;
                $nm= $newname;
                $kome=$newcomment;
                $hizuke=$date;
                $pasu=$pass_new;
		$sql="update tb_mission_4_Fujii set name='$nm', comment='$kome', date='$hizuke', pass='$pasu' where id ='$id'";//上書き
                $results=$pdo->query($sql);
	     }//if($row['id']==$editnumber2)
         }//foreach ($results as $row)
}//if(isset($_POST['submitbutton']) and !empty($_POST['editnumber2']))
?>

<!--投稿フォーム-->
<form method="post" action="mission_4.php">
 名前:<br>
 <input type="text" name="name" placeholder="<?php if(isset($_POST['editbutton'])){ echo $oldname;} ?>"  /><br>
 コメント:<br>
 <input type="text" name="comment"  placeholder="<?php if(isset($_POST['editbutton'])){ echo $oldcomment;} ?>"/ ><br>
パスワード:<br>
  <input type="password" name="pass" value="<?php if(isset($_POST['editbutton'])){ echo $oldpass;} ?>"/><br>
<!-- 編集したい番号を表示-->
 <input type="hidden" name="editnumber2" value="<?php if(isset($_POST['editbutton'])){ echo $editnumber;} ?>"/>
 <button type="submit" name="submitbutton" value="ボタン">送信</button>
 </form>

<!--削除フォーム-->
 <form method="post" action="mission_4.php">
 削除対象番号:<br>
<input type="number" name="delnumber" value=""><br>
パスワード:<br/>
 <input type="password" name="pass_del" value=""><br>
<button type="submit" name="deletebutton">削除</button>
</form>

<!--編集フォーム-->
<form method="post" action="mission_4.php">
編集対象番号<br>
<input type="number" name="editnumber"><br>
パスワード:<br/>
 <input type="password" name="pass_edit"><br>
<button type="submit" name="editbutton">編集</button>
</form>

<?php
/*****データベースに入っている情報を表示******/
//MySQLに接続
/*******MySQLに接続********/
       $dsn = 'データベース名';
       $user = 'ユーザー名';
       $password = 'パスワード';
       $pdo = new PDO($dsn,$user,$password);	//接続
	//データベースに入っているデータを表示（3-6)
	$sql='SELECT*FROM tb_mission_4_Fujii'; //selectで全てのデータを取り出す
	$results= $pdo->query($sql);
	foreach ($results as $row){ //1つずつ取り出し
 	 echo $row['id'].',';
	 echo $row['name'].',';
 	 echo $row['comment'].',';
 	 echo $row['date'].'<br>'; 
	}//foreach
?>

</body>
</html>
