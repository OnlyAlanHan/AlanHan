<?php

// 连接数据库
$conn = mysqli_connect('localhost', 'root', '456123', 'php_test');

if (!$conn) {
    die("连接失败: " . mysqli_connect_error());
}
?>