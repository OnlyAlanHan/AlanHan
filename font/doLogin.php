<?php
// 引入数据库连接文件
require_once 'mysql_connect.php';

// 启动 Session
session_start();

// 检查 POST 请求数据
$user = isset($_POST['user']) ? trim($_POST['user']) : null;
$password = isset($_POST['password']) ? trim($_POST['password']) : null;
$verifyCode = isset($_POST['verifyCode']) ? strtolower(trim($_POST['verifyCode'])) : null;

// 检查验证码
$sessionVerifyCode = isset($_SESSION['verifyCode']) ? $_SESSION['verifyCode'] : null;
if ($verifyCode !== $sessionVerifyCode) {
    // 验证码错误，显示错误页面
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>验证码错误</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                background-image: url('img3.jpg');
                background-size: cover;
                background-position: center;
                color: white;
                text-align: center;
            }
            .error-message {
                color: red; /* 红色字体 */
            }
        </style>
        <meta http-equiv="refresh" content="5;url=login.html">
    </head>
    <body>
    <div>
        <h1>验证码错误!</h1>
        <p>您将被自动跳转到登录页面...</p>
        <p class="error-message">被我抓到了吧！！小黑子！！</p>
    </div>
    </body>
    </html>
    <?php
    exit; // 结束执行
}

function checkLogin($conn, $user, $password)
{
    // 处理用户输入以防止 SQL 注入
    $user = mysqli_real_escape_string($conn, $user);
    $password = mysqli_real_escape_string($conn, $password);

    // 查询用户的密码
    $query = "SELECT password FROM admin WHERE user = '$user'";
    $result = mysqli_query($conn, $query);

    // 检查查询是否成功
    if ($result) {
        // 检查用户是否存在
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $storedPassword = $row['password'];

            // 直接比较输入的密码与数据库中存储的密码
            return $password === $storedPassword;
        } else {
            return 'user_not_found'; // 用户不存在
        }
    }

    return 'error'; // 查询错误
}

function logToFile($message)
{
    // 获取上海时间
    $dateTime = new DateTime('now', new DateTimeZone('Asia/Shanghai'));
    $formattedTime = $dateTime->format('Y-m-d H:i:s');

    // 创建日志内容
    $logEntry = "[$formattedTime] $message\n";

    // 将日志写入文件
    file_put_contents('log.txt', $logEntry, FILE_APPEND);
}

if ($user != null && $password != null) {
    $loginResult = checkLogin($conn, $user, $password);

    if ($loginResult === true) {
        // 登录成功，设置 Session 和 Cookie
        $_SESSION['user'] = $user;
        setcookie('user', $user, time() + (86400 * 30), "/"); // 30天有效期的 Cookie

        logToFile("登录成功: 用户名: $user");
        header('Location: index.php');
        exit;
    } elseif ($loginResult === 'user_not_found' || $loginResult === false) {
        // 用户不存在或密码错误，显示错误页面
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>登录失败</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                    background-image: url('img3.jpg');
                    background-size: cover;
                    background-position: center;
                    color: white;
                    text-align: center;
                }
                .error-message {
                    color: red; /* 红色字体 */
                }
            </style>
            <meta http-equiv="refresh" content="5;url=login.html">
        </head>
        <body>
        <div>
            <h1><?php echo $loginResult === 'user_not_found' ? '用户不存在！' : '用户名或密码错误！'; ?></h1>
            <p>您将被自动跳转到登录页面...</p>
            <p class="error-message">被我抓到了吧！！小黑子！！</p>
        </div>
        </body>
        </html>
        <?php
        logToFile("登录失败: 用户名: $user - " . ($loginResult === 'user_not_found' ? '用户不存在' : '密码错误'));
        exit; // 结束执行
    }
} else {
    $message = '用户名或密码不能为空';
    logToFile("登录失败: 用户名或密码为空");
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>错误</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                background-image: url('img3.jpg');
                background-size: cover;
                background-position: center;
                color: white;
                text-align: center;
            }
            .error-message {
                color: red; /* 红色字体 */
            }
        </style>
        <meta http-equiv="refresh" content="5;url=login.html">
    </head>
    <body>
    <div>
        <h1><?php echo $message; ?></h1>
        <p>您将被自动跳转到登录页面...</p>
        <p class="error-message">被我抓到了吧！！小黑子！！</p>
    </div>
    </body>
    </html>
    <?php
    exit; // 结束执行
}

// 关闭数据库连接
mysqli_close($conn);
?>