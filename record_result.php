<?php
// 資料庫連線
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "1128mid";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);
if ($conn->connect_error) {
    die("資料庫連線失敗：" . $conn->connect_error);
}

// 接收遊戲結果
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $result = $_POST['result'];

    $stmt = $conn->prepare("INSERT INTO game_records (username, result) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $result);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
?>
