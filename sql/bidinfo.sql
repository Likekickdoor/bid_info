-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2018-07-16 08:41:02
-- 服务器版本： 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bidinfo`
--

-- --------------------------------------------------------

--
-- 表的结构 `bidinfo`
--

CREATE TABLE `bidinfo` (
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '存在状态',
  `great_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '搜集时间',
  `fromsite` tinyint(3) UNSIGNED DEFAULT NULL COMMENT '信息来源网站',
  `btime_begin` datetime DEFAULT NULL COMMENT '发布时间',
  `b_place` varchar(64) DEFAULT NULL COMMENT '发布地区',
  `agent_comp` varchar(64) DEFAULT NULL COMMENT '代理公司',
  `org_href` varchar(128) DEFAULT NULL COMMENT '源链接',
  `b_btype` varchar(16) DEFAULT NULL COMMENT '公告大类型',
  `b_stype` varchar(64) DEFAULT NULL COMMENT '公告小类型',
  `b_detail` text COMMENT '公告详情',
  `b_title` varchar(64) DEFAULT NULL COMMENT '公告标题',
  `bid` int(10) UNSIGNED NOT NULL COMMENT '公告编号'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `collect`
--

CREATE TABLE `collect` (
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '存在状态',
  `great_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收录时间',
  `cbid` int(10) UNSIGNED NOT NULL COMMENT '收藏信息编号',
  `cuid` int(10) UNSIGNED NOT NULL COMMENT '收藏人编号',
  `collid` int(10) UNSIGNED NOT NULL COMMENT '收藏编号'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `history`
--

CREATE TABLE `history` (
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '存在状态',
  `histime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '访问时间',
  `hbid` int(10) UNSIGNED NOT NULL COMMENT '公告编号',
  `huid` int(10) UNSIGNED NOT NULL COMMENT '用户编号',
  `hisid` int(10) UNSIGNED NOT NULL COMMENT '足迹行编号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE `user` (
  `timelong` tinyint(1) UNSIGNED DEFAULT NULL COMMENT '时间段',
  `u_ind_type` varchar(64) DEFAULT NULL COMMENT '行业类型',
  `u_place` varchar(16) DEFAULT NULL COMMENT '用户定位',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '存在状态',
  `great_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '昵称',
  `openid` varchar(32) NOT NULL COMMENT '微信标识',
  `uid` int(10) UNSIGNED NOT NULL COMMENT '用户编号'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bidinfo`
--
ALTER TABLE `bidinfo`
  ADD PRIMARY KEY (`bid`);

--
-- Indexes for table `collect`
--
ALTER TABLE `collect`
  ADD PRIMARY KEY (`collid`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`hisid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `bidinfo`
--
ALTER TABLE `bidinfo`
  MODIFY `bid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '公告编号';
--
-- 使用表AUTO_INCREMENT `collect`
--
ALTER TABLE `collect`
  MODIFY `collid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '收藏编号';
--
-- 使用表AUTO_INCREMENT `history`
--
ALTER TABLE `history`
  MODIFY `hisid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '足迹行编号';
--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户编号';
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
