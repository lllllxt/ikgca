-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-03-09 03:32:35
-- 服务器版本： 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ikgca`
--

-- --------------------------------------------------------

--
-- 表的结构 `activity`
--

CREATE TABLE `activity` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` varchar(1024) NOT NULL,
  `createDate` date NOT NULL COMMENT '发布时间',
  `userId` int(11) NOT NULL COMMENT '发布人Id',
  `startDate` date DEFAULT NULL COMMENT '开始时间',
  `endDate` date DEFAULT NULL COMMENT '结束时间',
  `deadline` date DEFAULT NULL COMMENT '报名截止时间',
  `leaderId` int(11) DEFAULT NULL COMMENT '负责人Id',
  `acAddress` varchar(255) DEFAULT NULL,
  `fee` float(9,2) NOT NULL DEFAULT '0.00',
  `signInList` varchar(255) DEFAULT NULL COMMENT '报名成员Id集',
  `acState` int(11) NOT NULL DEFAULT '0' COMMENT '0未开始，1进行中，2已结束，3报名中，4已取消'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `activity`
--

INSERT INTO `activity` (`id`, `title`, `content`, `createDate`, `userId`, `startDate`, `endDate`, `deadline`, `leaderId`, `acAddress`, `fee`, `signInList`, `acState`) VALUES
(1, 'test11', 'contentcontentcontentcontentcontentcontentcontentcontentcontentcontentcontentcontentcontent', '2016-01-25', 4, '2016-01-10', '2016-01-15', '2016-01-08', 2, '中广', 0.00, '1,2,3,4,5,6', 0),
(3, 'test1', 'contentcontentcontentcontentcontentcontentcontentcontentcontentcontentcontentcontentcontent', '2016-01-25', 4, '2016-01-12', '2016-01-17', '2016-01-10', 3, '中广', 0.00, '4,5,6', 1),
(4, 'test2', 'contentcontentcontentcontentcontentcontentcontentcontentcontentcontentcontentcontentcontent', '2016-01-25', 3, '2016-01-10', '2016-01-15', '2016-01-08', 4, '中广', 0.00, '3,5,4', 2),
(5, 'test3', 'contentcontentcontentcontentcontentcontentcontentcontentcontentcontentcontentcontentcontent', '2016-01-25', 4, '2016-01-10', '2016-01-15', '2016-01-08', 4, '中广', 0.00, '3,5,4', 3),
(6, 'test4', 'contentcontentcontentcontentcontentcontentcontentcontentcontentcontentcontentcontentcontent', '2016-01-25', 4, '2016-01-10', '2016-01-15', '2016-01-08', 4, '中广', 0.00, '3,5,4', 4);

-- --------------------------------------------------------

--
-- 表的结构 `home_repair`
--

CREATE TABLE `home_repair` (
  `id` int(11) NOT NULL,
  `content` varchar(1024) NOT NULL,
  `address` varchar(255) DEFAULT NULL COMMENT '报修人宿舍',
  `phone` varchar(15) NOT NULL COMMENT '报修人联系电话',
  `userId` int(11) DEFAULT NULL COMMENT '接单人Id',
  `state` int(11) NOT NULL COMMENT '0未完成，1完成',
  `remark` varchar(1024) DEFAULT NULL,
  `createDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `home_repair`
--

INSERT INTO `home_repair` (`id`, `content`, `address`, `phone`, `userId`, `state`, `remark`, `createDate`) VALUES
(1, '笔记本黑屏', '14栋222', '123456', 4, 0, '', '2016-01-23'),
(2, '电脑开不了机', '10栋222', '123456', 5, 1, '', '2016-01-23');

-- --------------------------------------------------------

--
-- 表的结构 `notice`
--

CREATE TABLE `notice` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` varchar(1024) NOT NULL,
  `createDate` date NOT NULL,
  `userId` int(11) NOT NULL COMMENT '发布人Id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `notice`
--

INSERT INTO `notice` (`id`, `title`, `content`, `createDate`, `userId`) VALUES
(1, '开会1', '本周四J2-209开会', '2016-01-24', 4),
(3, '开会1', '本周四J2-209开会', '2016-01-24', 3),
(4, '开会2', '本周四J2-209开会', '2016-01-24', 1),
(5, '开会3', '本周二J2-209开会', '2016-01-24', 5);

-- --------------------------------------------------------

--
-- 表的结构 `tool`
--

CREATE TABLE `tool` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0' COMMENT '0空闲，1已出借',
  `remark` varchar(1024) DEFAULT '无'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tool`
--

INSERT INTO `tool` (`id`, `name`, `state`, `remark`) VALUES
(2, '螺丝刀', 0, '长十字'),
(3, '工具盒', 1, '无'),
(4, '硅脂', 0, '新'),
(5, '检测卡', 1, '红色'),
(6, '键盘', 1, '罗技 黑色'),
(7, '吹风机', 0, '无');

-- --------------------------------------------------------

--
-- 表的结构 `tool_flow`
--

CREATE TABLE `tool_flow` (
  `id` int(11) NOT NULL,
  `toolId` int(11) NOT NULL COMMENT '出借工具ID',
  `userId` int(11) NOT NULL COMMENT '出借人ID',
  `lendDate` date NOT NULL COMMENT '出借时间',
  `returnDate` date DEFAULT NULL COMMENT '归还时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tool_flow`
--

INSERT INTO `tool_flow` (`id`, `toolId`, `userId`, `lendDate`, `returnDate`) VALUES
(1, 2, 6, '2016-01-02', '2016-01-03'),
(3, 3, 5, '2016-01-01', NULL),
(5, 5, 6, '2016-01-23', '0000-00-00'),
(6, 4, 5, '2016-01-01', '2016-03-06'),
(7, 6, 1, '2016-01-08', '0000-00-00'),
(8, 2, 1, '2016-01-08', '2016-01-09');

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `birthDate` date DEFAULT NULL,
  `sex` varchar(10) DEFAULT NULL COMMENT 'male、female',
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `shortPhone` varchar(15) DEFAULT NULL,
  `password` varchar(255) DEFAULT '123456',
  `qq` varchar(20) DEFAULT NULL,
  `power` int(11) DEFAULT NULL COMMENT '0：会员，1：干部，2：可上门干事，3：干事',
  `userImg` varchar(1024) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `name`, `birthDate`, `sex`, `address`, `phone`, `shortPhone`, `password`, `qq`, `power`, `userImg`) VALUES
(1, '张三', '1994-02-02', 'male', 'aaa', '13232165498', '12333', '123456', '123123123', 0, '../images/head/1457426955.jpg'),
(2, '李四', '0000-00-00', 'male', '22栋', '15987654321', '', '123456', '321321321', 2, NULL),
(3, '雪哥', '1997-09-01', 'female', '14栋', '13219970109', '660109', '123456', '123456789', 1, NULL),
(4, 'Holy', '1994-05-16', 'male', '22栋', '15992672260', '622259', '123456', '532543299', 3, '../images/head/1456142816.ico'),
(5, '张五', '0000-00-00', 'male', '11栋', '12345678901', '验证！！', '123456', '222222222', 1, NULL),
(6, '刘晓堂', '1994-05-16', 'male', '22栋514', '15992672259', '622259', '123', '53254299', 3, '../images/head/1456135210.jpg'),
(8, '张五', '0000-00-00', 'male', '11栋', '12345678902', NULL, '123456', '222222222', 1, NULL),
(9, '张五', '0000-00-00', 'male', '11栋', '12345678903', NULL, '123456', '222222222', 1, NULL),
(10, '张五', '0000-00-00', 'male', '11栋', '12345678904', NULL, '123456', '222222222', 1, NULL),
(11, '张五', '0000-00-00', 'male', '11栋', '12345678905', NULL, '123456', '222222222', 1, NULL),
(12, '张五', '0000-00-00', 'male', '11栋', '12345678906', NULL, '123456', '222222222', 1, NULL),
(13, '张五', '0000-00-00', 'male', '11栋', '12345678907', NULL, '123456', '222222222', 1, NULL),
(14, '张五', '0000-00-00', 'male', '11栋', '12345678908', NULL, '123456', '222222222', 1, NULL),
(15, '张五', '0000-00-00', 'male', '11栋', '12345678909', NULL, '123456', '222222222', 1, NULL),
(16, '张五', '0000-00-00', 'male', '11栋', '12345678900', NULL, '123456', '222222222', 1, NULL),
(17, 'haha', '0000-00-00', 'female', '', '12344444444', '', '123456', '', 1, '../images/head/1457432381.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home_repair`
--
ALTER TABLE `home_repair`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notice`
--
ALTER TABLE `notice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tool`
--
ALTER TABLE `tool`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tool_flow`
--
ALTER TABLE `tool_flow`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `activity`
--
ALTER TABLE `activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `home_repair`
--
ALTER TABLE `home_repair`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `notice`
--
ALTER TABLE `notice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `tool`
--
ALTER TABLE `tool`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- 使用表AUTO_INCREMENT `tool_flow`
--
ALTER TABLE `tool_flow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
