<?php

// 定义数据库连接信息
//主机地址
$servername = "mysql_g1";
//用户名
$username = "chirou_hz_cz";
//密码
$password = "123456";
//数据库名
$dbname = "chirou_hz_cz";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接是否成功
if ($conn->connect_error) {
    $error_message = "连接数据库失败: " . $conn->connect_error;
    error_log($error_message, 3, "error.log");
    die($error_message);
}

// 设置字符集
mysqli_set_charset($conn, "utf8mb4");

?>