<?php
//テーブル作成
$sql= "CREATE TABLE member"	//テーブル作成
."("
."id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
."account VARCHAR(50) NOT NULL,"
."mail VARCHAR(50) NOT NULL,"
."password VARCHAR(128) NOT NULL,"
."flag TINYINT(1) NOT NULL DEFAULT 1"
.");";
$stmt = $pdo->query($sql);	//queryメソッドで実行
?>