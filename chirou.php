<?php
date_default_timezone_set('Asia/Shanghai');

// 数据库连接信息
require_once 'database.php';

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接是否成功
if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
}

// 读取全部sid储存到allsid数组中
$allsid = [];
$sidQuery = "SELECT sid FROM yh_sid";
$sidResult = $conn->query($sidQuery);

if ($sidResult->num_rows > 0) {
    while ($row = $sidResult->fetch_assoc()) {
        $allsid[] = $row["sid"];
    }
} else {
    echo "没有找到任何sid";
}

// 读取全部zhuangtai为0的tiezi储存到alltiezi数组中
$alltiezi = [];
$tieziQuery = "SELECT tiezi FROM rou WHERE zhuangtai = 0";
$tieziResult = $conn->query($tieziQuery);

if ($tieziResult->num_rows > 0) {
    while ($row = $tieziResult->fetch_assoc()) {
        $alltiezi[] = $row["tiezi"];
    }
} else {
    echo "没有找到任何状态为0的帖子";
}

// 执行POST请求
foreach ($allsid as $sid) {
    foreach ($alltiezi as $tiezi) {
        // 检查是否已经存在相同的sid和tiezi记录
        $checkExistingQuery = "SELECT * FROM task WHERE sid = '$sid' AND tiezi = '$tiezi'";
        $existingResult = $conn->query($checkExistingQuery);

        if ($existingResult->num_rows == 0) {
            // 构建POST请求的数据
            $postData = http_build_query([
                'content' => '吃',
                'action' => 'add',
                'id' => $tiezi,
                'siteid' => 1000,
                'lpage' => 1,
                'classid' => 177,
                'g' => '快速回复'
            ]);

            // 构建请求的URL
            $url = "https://www.yaohuo.me/bbs/book_re_QQ.aspx?sid=$sid";

            // 构建请求头
            $headers = [
                'Content-Type: application/x-www-form-urlencoded',
                'Content-Length: ' . strlen($postData)
            ];

            // 将sid、tiezi插入到task表
            $insertTaskQuery = "INSERT INTO task (sid, tiezi) VALUES ('$sid', '$tiezi')";
            $insertResult = $conn->query($insertTaskQuery);

            if ($insertResult) {
                // 使用cURL执行POST请求
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                // 执行请求并输出结果
                $result = curl_exec($ch);

                // 将返回结果和当前时间更新到task表
                $updateTaskQuery = "UPDATE task SET result = '$result', request_time = NOW() WHERE sid = '$sid' AND tiezi = '$tiezi'";
                $conn->query($updateTaskQuery);

                echo "Response: $result\n";

                // 更新tiezi的状态为1
                $updateQuery = "UPDATE rou SET zhuangtai = 1 WHERE tiezi = '$tiezi'";
                $conn->query($updateQuery);

                // 关闭cURL资源
                curl_close($ch);
            } else {
                echo "无法插入 sid=$sid 和 tiezi=$tiezi 的任务\n";
            }
        } else {
            echo "sid=$sid 和 tiezi=$tiezi 的任务已存在，跳过...\n";
        }
    }
}

// 关闭数据库连接
$conn->close();
?>
