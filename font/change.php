<?php
include 'mysql_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    // 验证旧密码
    $sql = "SELECT password FROM admin WHERE user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($stored_password);
        $stmt->fetch();

        // 验证旧密码
        if ($old_password === $stored_password) {
            // 更新用户密码
            $sql = "UPDATE admin SET password = ? WHERE user = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $new_password, $user);

            if ($stmt->execute()) {
                echo "密码修改成功！正在跳转到登录页面...";
                header("Refresh:2; url=login.html");
                exit;
            } else {
                echo "错误: " . $stmt->error;
            }
        } else {
            echo "旧密码错误，请重新输入。";
        }
    } else {
        echo "用户不存在。";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修改密码</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url('img1.jpg');
            background-size: cover;
            background-position: center;
        }
        .container {
            text-align: center;
            background: rgba(255, 255, 255, 0.3);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            width: 300px;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 24px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input[type="text"],
        input[type="password"] {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 0;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin: 10px 0;
        }
        .button:hover {
            background-color: #45a049;
        }
        .link {
            margin-top: 10px;
        }
        .link a {
            color: #4CAF50;
            text-decoration: none;
        }
        .link a:hover {
            color: #45a049;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>修改密码</h1>
    <form action="" method="post">
        <input type="text" name="user" placeholder="用户名" required>
        <input type="password" name="old_password" placeholder="旧密码" required>
        <input type="password" name="new_password" placeholder="新密码" required>
        <button type="submit" class="button">修改密码</button>
        <div class="error-message">
            <?php if (isset($error_message)) echo $error_message; ?>
        </div>
    </form>
    <div class="link">
        <a href="login.html">返回登录</a>
    </div>
</div>
</body>
</html>