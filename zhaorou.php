<?php
// 数据库连接信息
require_once 'database.php';

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检测连接
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 循环取得有效的 sid
while (true) {
    // 随机取一个 sid
    $result = $conn->query("SELECT sid FROM yh_sid ORDER BY RAND() LIMIT 1");

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $sid = $row["sid"];

        // 构建 URL
        $url = "https://www.yaohuo.me/bbs/book_list.aspx?gettotal=2023&action=new&sid=" . $sid;

        // 发送 GET 请求
        $html = file_get_contents($url);

        // 判断 HTML 中是否包含 '所有最新贴子'
        if (strpos($html, '所有最新贴子') !== false) {
            // 在返回的 HTML 中找到‘<img src="/NetImages/li.gif" alt="礼"/>’ 后边的连接
            preg_match_all('/<img src="\/NetImages\/li.gif" alt="礼"\/>\s*<a class="topic-link" href="\/bbs-(\d+).html"/', $html, $matches);

            if (!empty($matches[1])) {
                foreach ($matches[1] as $tiezi_num) {
                    // 检查链接是否已存在
                    $checkResult = $conn->query("SELECT COUNT(*) as count FROM rou WHERE tiezi = '$tiezi_num'");
                    $checkRow = $checkResult->fetch_assoc();

                    if ($checkRow['count'] == 0) {
                        // 打印链接
                        echo "Number: $tiezi_num\n";

                        // 将连接和当前时间保存到数据库的 rou 表的 tiezi 和 time 列，zhuangtai 列设置为 0
                        $conn->query("INSERT INTO rou (tiezi, time, zhuangtai) VALUES ('$tiezi_num', CURRENT_TIMESTAMP, 0)");
                    }
                }

                // 有效 sid，退出循环
                break;
            }
        } else {
            // 无效 sid，从数据库中删除
            //$conn->query("DELETE FROM yh_sid WHERE sid = '$sid'");
        }
    } else {
        // 没有可用的 sid，退出循环
        break;
    }
}

// 关闭连接
$conn->close();
?>
