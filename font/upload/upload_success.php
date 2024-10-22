<?php
// 获取传递的文件信息
$fileName = isset($_GET['file_name']) ? htmlspecialchars($_GET['file_name']) : '未知文件';
$fileSize = isset($_GET['file_size']) ? htmlspecialchars($_GET['file_size']) : '未知大小';
$uploadPath = isset($_GET['upload_path']) ? htmlspecialchars($_GET['upload_path']) : '未知路径';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>上传成功</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            background-image: url('img5.png'); /* 确保 img.png 存在于同一目录 */
            background-size: cover; /* 确保背景图像覆盖整个屏幕 */
            background-position: center; /* 居中背景图 */
            background-repeat: no-repeat; /* 不重复背景图 */
            display: flex;
            justify-content: center;
            align-items: center;
            color: black; /* 文字颜色改为黑色以适应白色背景 */
            text-align: center;
        }
        #success-container {
            background-color: rgba(255, 255, 255, 0.5); /* 半透明白色背景 */
            padding: 20px;
            border-radius: 10px;
            max-width: 400px;
            width: 100%;
        }
    </style>
</head>

<body>
<div id="success-container">
    <h1>文件上传成功</h1>
    <p><strong>文件名:</strong> <?php echo $fileName; ?></p>
    <p><strong>存放路径:</strong> <?php echo $uploadPath; ?></p>
    <p><strong>文件大小:</strong> <?php echo $fileSize; ?> bytes</p>
</div>
</body>
</html>