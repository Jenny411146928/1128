<?php
// 資料庫連線設定
$servername = "localhost";
$username_db = "root";   // 你的 MySQL 帳號
$password_db = "";       // 你的 MySQL 密碼
$dbname = "1128mid";     // 資料庫名稱

// 建立連線
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// 檢查連線
if ($conn->connect_error) {
    die("資料庫連線失敗：" . $conn->connect_error);
}

// 確認 POST 是否包含帳號名稱
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    // 獲取使用者輸入的帳號名稱
    $username = htmlspecialchars($_POST['username']);  // 避免 XSS 攻擊

    // 檢查玩家是否已存在，若無則插入帳號名稱
    $stmt = $conn->prepare("INSERT IGNORE INTO players (username) VALUES (?)");
    $stmt->bind_param("s", $username);  // "s" 表示綁定一個字串參數
    $stmt->execute();
    $stmt->close();

    // 顯示歡迎訊息
    echo "<h1>歡迎，" . $username . "！</h1>";
    echo "<p>成功登入並儲存帳號名稱到資料庫。</p>";

} else {
    // 如果未提交資料，重定向回登入頁面
    header('Location: index11280.php');
    exit;
}

// 關閉資料庫連線
$conn->close();
?>
