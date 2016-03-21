-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-03-20 17:11:24
-- 服务器版本： 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ikgca`
--
CREATE DATABASE IF NOT EXISTS `ikgca` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ikgca`;

-- --------------------------------------------------------

--
-- 表的结构 `activity`
--

CREATE TABLE IF NOT EXISTS `activity` (
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `home_repair`
--

CREATE TABLE IF NOT EXISTS `home_repair` (
`id` int(11) NOT NULL,
  `content` varchar(1024) NOT NULL,
  `address` varchar(255) DEFAULT NULL COMMENT '报修人宿舍',
  `phone` varchar(15) NOT NULL COMMENT '报修人联系电话',
  `userId` int(11) DEFAULT NULL COMMENT '接单人Id',
  `state` int(11) NOT NULL COMMENT '0未完成，1完成',
  `remark` varchar(1024) DEFAULT NULL,
  `createDate` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `notice`
--

CREATE TABLE IF NOT EXISTS `notice` (
`id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` varchar(1024) NOT NULL,
  `createDate` date NOT NULL,
  `userId` int(11) NOT NULL COMMENT '发布人Id'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tool`
--

CREATE TABLE IF NOT EXISTS `tool` (
`id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0' COMMENT '0空闲，1已出借',
  `remark` varchar(1024) DEFAULT '无'
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tool_flow`
--

CREATE TABLE IF NOT EXISTS `tool_flow` (
`id` int(11) NOT NULL,
  `toolId` int(11) NOT NULL COMMENT '出借工具ID',
  `userId` int(11) NOT NULL COMMENT '出借人ID',
  `lendDate` date NOT NULL COMMENT '出借时间',
  `returnDate` date DEFAULT NULL COMMENT '归还时间'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `birthDate` date DEFAULT NULL,
  `sex` varchar(10) DEFAULT NULL COMMENT 'male、female',
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `shortPhone` varchar(15) DEFAULT NULL,
  `password` varchar(255) DEFAULT '123456',
  `qq` varchar(20) DEFAULT NULL,
  `power` int(11) DEFAULT NULL COMMENT '0：会员，1：干事，2：可上门干事，3：干部',
  `userImg` varchar(1024) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='用户表';

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
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `home_repair`
--
ALTER TABLE `home_repair`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `notice`
--
ALTER TABLE `notice`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tool`
--
ALTER TABLE `tool`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `tool_flow`
--
ALTER TABLE `tool_flow`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
