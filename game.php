<?php
session_start();

// 資料庫連線設定
$servername = "localhost";
$username_db = "root"; // 你的 MySQL 使用者名稱
$password_db = ""; // 你的 MySQL 密碼
$dbname = "1128mid"; // 資料庫名稱

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// 檢查資料庫連線
if ($conn->connect_error) {
    die("資料庫連線失敗：" . $conn->connect_error);
}

// 模擬的用戶進度資料
if (!isset($_SESSION['user_data'])) {
    $_SESSION['user_data'] = [];
}

// 登入邏輯
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);

    if (!empty($username)) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;

        // 如果是新用戶，儲存用戶名到資料庫
        $stmt = $conn->prepare("INSERT INTO players (username) VALUES (?) ON DUPLICATE KEY UPDATE username=username");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->close();

        // 如果是新用戶，初始化進度
        if (!isset($_SESSION['user_data'][$username])) {
            $_SESSION['user_data'][$username] = [
                'number' => rand(1, 100),
                'attempts' => 0,
                'guessed_numbers' => [],
            ];
        }

        $_SESSION['number'] = $_SESSION['user_data'][$username]['number'];
        $_SESSION['attempts'] = $_SESSION['user_data'][$username]['attempts'];
        $_SESSION['guessed_numbers'] = $_SESSION['user_data'][$username]['guessed_numbers'];

        // 記錄遊戲開始的時間
        $_SESSION['start_time'] = microtime(true);
        $_SESSION['message'] = "歡迎，{$username}！請輸入1到100間的數字！";
    } else {
        $login_error = "請輸入用戶名！";
    }
}

// 登出邏輯
if (isset($_GET['logout'])) {
    // 保存當前用戶進度
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $_SESSION['user_data'][$username] = [
            'number' => $_SESSION['number'],
            'attempts' => $_SESSION['attempts'],
            'guessed_numbers' => $_SESSION['guessed_numbers'],
        ];
    }

    // 清除所有登入相關的 Session
    session_unset();
    session_destroy();

    // 重定向到登入畫面
    header('Location: game.php');
    exit;
}

// 遊戲邏輯
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['guess']) && isset($_SESSION['loggedin'])) {
    $guess = (int)$_POST['guess'];
    $_SESSION['attempts']++;
    $_SESSION['guessed_numbers'][] = $guess; // 將猜過的數字加入陣列

    if ($guess < $_SESSION['number']) {
        $_SESSION['message'] = "太小了！再試一次。";
    } elseif ($guess > $_SESSION['number']) {
        $_SESSION['message'] = "太大了！再試一次。";
    } else {
        $_SESSION['message'] = "恭喜 {$_SESSION['username']} 猜中了 {$_SESSION['number']} ！你共花了 {$_SESSION['attempts']} 次。";

        // 計算遊戲總時間
        $end_time = microtime(true);
        $time_taken = round($end_time - $_SESSION['start_time'], 2); // 取小數點後兩位
        $_SESSION['message'] .= " 總共花了 {$time_taken} 秒。";

        unset($_SESSION['number']); // 遊戲完成後重置
        unset($_SESSION['attempts']);
        unset($_SESSION['guessed_numbers']);
        unset($_SESSION['start_time']); // 重置遊戲開始時間
    }
}

// 重新開始遊戲邏輯
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['restart'])) {
    // 重置遊戲狀態
    $username = $_SESSION['username'];
    $_SESSION['user_data'][$username] = [
        'number' => rand(1, 100),
        'attempts' => 0,
        'guessed_numbers' => [],
    ];

    $_SESSION['number'] = $_SESSION['user_data'][$username]['number'];
    $_SESSION['attempts'] = 0;
    $_SESSION['guessed_numbers'] = [];
    $_SESSION['start_time'] = microtime(true); // 記錄新的遊戲開始時間
    $_SESSION['message'] = "遊戲重新開始，開始猜數字吧！";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width
