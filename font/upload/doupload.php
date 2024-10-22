<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['upload']) && $_FILES['upload']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'upload_files/';

        // 确保上传目录存在
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // 生成新的文件名
        $fileCount = count(scandir($uploadDir)) - 2; // 减去 . 和 ..
        $newFileName = 'img' . ($fileCount + 1) . '.' . pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);
        $uploadFile = $uploadDir . $newFileName;

        // 移动上传的文件
        if (move_uploaded_file($_FILES['upload']['tmp_name'], $uploadFile)) {
            // 文件上传成功，重定向到成功页面
            header('Location: upload_success.php?file_name=' . urlencode($newFileName) . '&file_size=' . $_FILES['upload']['size'] . '&upload_path=' . urlencode($uploadFile));
            exit();
        } else {
            echo '文件上传失败。';
        }
    } else {
        echo '没有文件上传或发生错误。';
    }
} else {
    echo '无效的请求方法。';
}
?>