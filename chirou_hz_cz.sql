-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- 主机： 10.10.0.1
-- 生成日期： 2023-11-26 13:56:58
-- 服务器版本： 10.3.23-MariaDB-0+deb10u1
-- PHP 版本： 7.3.19-1~deb10u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `chirou_hz_cz`
--

-- --------------------------------------------------------

--
-- 表的结构 `rou`
--

CREATE TABLE `rou` (
  `id` int(11) NOT NULL,
  `tiezi` varchar(255) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `zhuangtai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `rou`
--

INSERT INTO `rou` (`id`, `tiezi`, `time`, `zhuangtai`) VALUES
(1, '1252946', '2023-11-25 10:40:36', 1),
(2, '1252945', '2023-11-25 10:45:07', 1),
(3, '1252941', '2023-11-25 10:45:07', 1),
(4, '1252940', '2023-11-25 10:45:07', 0),
(5, '1253213', '2023-11-26 12:26:04', 0),
(6, '1253205', '2023-11-26 12:26:04', 0);

-- --------------------------------------------------------

--
-- 表的结构 `yh_sid`
--

CREATE TABLE `yh_sid` (
  `id` int(11) NOT NULL,
  `sid` varchar(255) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `userid` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `yh_sid`
--

INSERT INTO `yh_sid` (`id`, `sid`, `time`, `userid`) VALUES
(11, '111111111111', '2023-11-26 12:39:10', 0);

--
-- 转储表的索引
--

--
-- 表的索引 `rou`
--
ALTER TABLE `rou`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `yh_sid`
--
ALTER TABLE `yh_sid`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `rou`
--
ALTER TABLE `rou`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `yh_sid`
--
ALTER TABLE `yh_sid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
