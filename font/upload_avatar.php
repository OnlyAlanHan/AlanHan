<?php
session_start();
include 'mysql_connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    $user = $_SESSION['user'];
    $target_dir = "avatar/";

    // 确保目标目录存在
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true); // 创建目录，权限设置为 0755
    }

    // 定义目标文件名为 用户名.jpg
    $target_file = $target_dir . $user . '.jpg';
    $imageFileType = strtolower(pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION));

    // 检查文件类型
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_extensions)) {
        echo "只允许上传 JPG, JPEG, PNG & GIF 文件。";
        exit;
    }

    // 检查文件是否已存在
    if (file_exists($target_file)) {
        echo "文件已存在，请更改文件名。";
        exit;
    }

    // 限制文件大小，最大 2MB
    if ($_FILES["avatar"]["size"] > 2 * 1024 * 1024) {
        echo "文件大小超过 2MB。";
        exit;
    }

    // 移动上传的文件
    if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
        echo "头像上传成功！";

        // 更新用户头像路径到数据库
        $sql = "INSERT INTO user_avatars (user, avatar_url) VALUES (?, ?) ON DUPLICATE KEY UPDATE avatar_url = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("准备 SQL 语句时出错: " . $conn->error);
        }

        $stmt->bind_param("sss", $user, $target_file, $target_file);

        if ($stmt->execute()) {
            echo "头像路径已更新。";
        } else {
            echo "更新头像路径时出错: " . $stmt->error;
        }
    } else {
        echo "上传头像时出错。";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>上传头像</title>
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
            color: #333;
        }
        .container {
            text-align: center;
            background: rgba(255, 255, 255, 0.8);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 400px;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #5cb85c;
        }
        input[type="file"] {
            margin: 10px 0;
        }
        button {
            padding: 10px 20px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #4cae4c;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #5cb85c;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>上传头像</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="avatar" required>
        <button type="submit">上传</button>
    </form>
    <a href="index.php">返回首页</a>
</div>
</body>
</html>