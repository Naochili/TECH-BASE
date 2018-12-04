<?php
//テーブル作成
$sql= "CREATE TABLE pre_member"	//テーブル作成
."("
."id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
."urltoken VARCHAR(128) NOT NULL,"
."mail VARCHAR(50) NOT NULL,"
."date DATETIME NOT NULL,"
."flag TINYINT(1) NOT NULL DEFAULT 0"
.");";
$stmt = $dbh->query($sql);	//queryメソッドで実行
?>