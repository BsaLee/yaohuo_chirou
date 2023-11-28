<?php
date_default_timezone_set('Asia/Shanghai');

// 数据库连接信息
require_once 'database.php';

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接是否成功
if ($conn->connect_error) {
    $response = array(
        "success" => false,
        "message" => "数据库连接失败: " . $conn->connect_error
    );
    die(json_encode($response));
}

// 检查是否有传入的 'sid' 参数
if (!isset($_POST['sid'])) {
    $response = array(
        "success" => false,
        "message" => "未提供有效的 'sid' 参数"
    );
    die(json_encode($response));
}

// 获取 POST 请求中的 'sid'
$sid = $_POST['sid'];

// 发送 GET 请求验证 sid
$get_url = "https://www.yaohuo.me/myfile.aspx?sid=$sid";
$ch = curl_init($get_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$html = curl_exec($ch);
curl_close($ch);

// 判断 HTML 内容中是否包含 "我的ID" 字符串
if (strpos($html, '我的ID') !== false) {
    // sid 有效的情况

    // 检查数据库中是否已存在相同的sid或userid
    $check_query = "SELECT * FROM yh_sid WHERE sid='$sid' OR userid=(SELECT userid FROM yh_sid WHERE sid='$sid' LIMIT 1)";
    $result = $conn->query($check_query);

    if ($result && $result->num_rows > 0) {
        // 如果存在相同的sid或userid，则更新sid
        $update_query = "UPDATE yh_sid SET sid='$sid', time=NOW() WHERE userid=(SELECT userid FROM yh_sid WHERE sid='$sid' LIMIT 1)";
        if ($conn->query($update_query) === TRUE) {
            $response = array(
                "success" => true,
                "message" => "记录已更新",
            );
            echo json_encode($response);
        } else {
            $response = array(
                "success" => false,
                "message" => "更新记录时出错: " . $conn->error
            );
            echo json_encode($response);
        }
    } else {
        // 如果不存在相同的sid或userid，则插入新记录
        $insert_query = "INSERT INTO yh_sid (sid, time) VALUES ('$sid', NOW())";
        if ($conn->query($insert_query) === TRUE) {
            $response = array(
                "success" => true,
                "message" => "记录已成功添加到数据库",
            );
            echo json_encode($response);
        } else {
            $response = array(
                "success" => false,
                "message" => "添加记录时出错: " . $conn->error
            );
            echo json_encode($response);
        }
    }
} else {
    // sid 无效的情况
    $response = array(
        "success" => false,
        "message" => "sid 无效"
        //"html_content" => $html  // 添加这一行来保存HTML内容
    );
    echo json_encode($response);

    // 在sid无效时打印HTML内容
    //echo "HTML内容：".$html;
}

// 关闭数据库连接
$conn->close();
?>
