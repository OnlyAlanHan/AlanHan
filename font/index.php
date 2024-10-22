<?php
session_start();
require_once 'mysql_connect.php'; // 包括数据库连接

// 创建消息表（如果不存在）
$sql = "CREATE TABLE IF NOT EXISTS messages (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($sql)) {
    die("创建表错误: " . $conn->error);
}

// 处理留言提交
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
    $message = $_POST['message'];
    $user = $_SESSION['user'];

    // 插入留言到数据库
    $sql = "INSERT INTO messages (user, message) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user, $message);

    if ($stmt->execute()) {
        // 留言成功后重定向到当前页面
        header("Location: " . $_SERVER['PHP_SELF']);
        exit; // 确保脚本结束
    } else {
        echo "留言提交失败: " . $stmt->error;
    }
}

// 处理留言删除
if (isset($_GET['delete']) && $_SESSION['user'] === 'root') {
    $message_id = intval($_GET['delete']);
    $sql = "DELETE FROM messages WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
}

// 获取留言
$sql = "SELECT * FROM messages ORDER BY created_at DESC";
$result = $conn->query($sql);

if (!$result) {
    echo "SQL 查询失败: " . $conn->error;
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登录成功</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
            background-image: url('img2.png');
            background-size: cover;
            background-position: center;
            overflow: hidden; /* 防止页面滚动 */
        }
        .container {
            text-align: center;
            background: rgba(255, 255, 255, 0.4);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            max-height: 90vh; /* 限制容器高度 */
            max-width: 800px; /* 增加容器的最大宽度 */
            width: 100%; /* 使容器宽度占满可用空间 */
            overflow: auto; /* 启用滚动 */
        }
        h1 {
            margin-bottom: 20px;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #5cb85c;
        }
        a:hover {
            text-decoration: underline;
        }
        .button {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }
        .button:hover {
            background-color: #4cae4c;
        }
        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }
        .messages {
            margin-top: 20px;
            text-align: left;
            max-width: 100%; /* 留言板宽度占满可用空间 */
            max-height: 300px; /* 限制留言板高度 */
            overflow-y: auto; /* 启用垂直滚动 */
        }
        .message {
            background: rgba(255, 255, 255, 0.7);
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
        }
        .delete {
            color: red;
            cursor: pointer;
        }
        .timestamp {
            font-size: 0.8em;
            color: gray;
        }
    </style>
</head>
<body>
<div class="container">
    <?php if (isset($_SESSION['user'])): ?>
        <h1>欢迎, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h1>
        <img src="avatar/<?php echo htmlspecialchars($_SESSION['user']); ?>.jpg" alt="用户头像" class="avatar">
        <p>您已成功登录。</p>

        <a href="logout.php" class="button">退出登录</a>
        <a href="upload_avatar.php" class="button">上传头像</a>
        <a href="backup_database.php" class="button">备份数据库</a>
        <a href="upload/upload.html" class="button">上传文件</a> <!-- 新增上传文件按钮 -->

        <form method="post">
            <textarea name="message" placeholder="留言..." required></textarea>
            <br>
            <button type="submit" class="button">提交留言</button>
        </form>

        <div class="messages">
            <h2>留言板</h2>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="message">
                    <strong><?php echo htmlspecialchars($row['user']); ?>:</strong> <?php echo htmlspecialchars($row['message']); ?>
                    <div class="timestamp"><?php echo htmlspecialchars($row['created_at']); ?></div>
                    <?php if ($_SESSION['user'] === 'root'): ?>
                        <a href="?delete=<?php echo $row['id']; ?>" class="delete">删除</a>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <h1>未登录</h1>
        <p>请<a href="login.html">登录</a>。</p>
    <?php endif; ?>
</div>
</body>
</html>