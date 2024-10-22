<?php
// 启动 Session
session_start();

// 清除 Session 变量
$_SESSION = [];

// 如果需要，销毁 Session
session_destroy();

// 清除 Cookie（如果有设置）
if (isset($_COOKIE['user'])) {
    setcookie('user', '', time() - 3600, '/'); // 设置 Cookie 过期时间为过去的时间
}

// 重定向到 login.html
header('Location: login.html');
exit;
?>