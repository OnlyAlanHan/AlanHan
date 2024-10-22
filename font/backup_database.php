<?php
session_start();
include 'mysql_connect.php';

if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'root') {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 数据库信息
    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = '456123';
    $db_name = 'php_test';
    $backup_file = 'backups/' . $db_name . '_' . date("Y-m-d_H-i-s") . '.sql';

    // 要备份的表
    $tables = 'admin messages user_avatars';

    // 创建备份命令
    $command = "\"D:\\tools\\phpstudy\\phpstudy_pro\\Extensions\\MySQL5.7.26\\bin\\mysqldump\" --opt --default-character-set=utf8 -h localhost -u root -p456123 $db_name $tables > $backup_file 2>&1";

    // 打印命令用于调试
    echo "Executing command: $command<br>";

    // 执行命令并捕获输出
    $output = [];
    $return_var = null;
    exec($command, $output, $return_var);

    if ($return_var === 0) {
        echo "数据库备份成功！备份文件: " . htmlspecialchars($backup_file);
    } else {
        echo "数据库备份失败。错误信息: " . implode("\n", $output);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>备份数据库</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
            background-image: url('img2.png'); /* 设置背景图像 */
            background-size: cover; /* 使背景图像覆盖整个屏幕 */
            background-position: center; /* 背景图像居中 */
        }
        .container {
            text-align: center;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        button {
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
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
    </style>
</head>
<body>
<div class="container">
    <h1>备份数据库</h1>
    <form action="" method="post">
        <button type="submit">备份数据库</button>
    </form>
    <a href="index.php">返回首页</a>
</div>
</body>
</html>