<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>我的第一個PHP程式</title>
</head>
<body>
	<?php
	echo "嗨，PHP您好!<br>";
	echo "目前的 PHP 版本是 : " . PHP_VERSION . "<br>";
	echo "目前的server os 版本 : " . PHP_OS . "<br>";
	echo "目前執行檔案的路徑與檔名是 : " . __FILE__ . "<br>";// 合併路徑與檔名
	echo "目前網頁的虛擬路徑是 : " . $_SERVER['PHP_SELF'] . "<br>";
	echo "目前網頁的server位置 : " . $_SERVER['HTTP_HOST'] . "<br>";
	?>
</body>
</html>