-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2018-08-06 10:55:16
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
-- 替换视图以便查看 `agent_com_rank`
-- (See below for the actual view)
--
CREATE TABLE `agent_com_rank` (
`agent_comp` varchar(64)
,`agent_comp_num` bigint(21)
);

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
  `b_title` varchar(64) DEFAULT NULL COMMENT '公告标题',
  `bid` int(10) UNSIGNED NOT NULL COMMENT '公告编号'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 替换视图以便查看 `bidinfo_views_rank`
-- (See below for the actual view)
--
CREATE TABLE `bidinfo_views_rank` (
`about_id` int(10) unsigned
,`b_title` varchar(64)
,`views` int(11)
);

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
-- 替换视图以便查看 `detail_info`
-- (See below for the actual view)
--
CREATE TABLE `detail_info` (
`bid` int(10) unsigned
,`b_title` varchar(64)
,`org_href` varchar(128)
,`btime_begin` datetime
,`b_detail` text
,`views` int(11)
);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `notice`
--

CREATE TABLE `notice` (
  `notid` int(10) UNSIGNED NOT NULL COMMENT '公告详情表编号',
  `about_id` int(10) UNSIGNED DEFAULT NULL COMMENT '对应它表编号',
  `b_detail` text COMMENT '公告详情html',
  `views` int(11) DEFAULT '1' COMMENT '访问量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE `user` (
  `u_agent` varchar(64) DEFAULT NULL COMMENT '代理公司',
  `u_ind_type` varchar(64) DEFAULT NULL COMMENT '行业类型',
  `u_place` varchar(16) DEFAULT NULL COMMENT '用户定位',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '存在状态',
  `great_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `face` varchar(256) DEFAULT 'https://m.ctrltab.xyz/picture/baoma.jpg' COMMENT '头像',
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '昵称',
  `openid` varchar(32) NOT NULL COMMENT '微信标识',
  `uid` int(10) UNSIGNED NOT NULL COMMENT '用户编号'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 视图结构 `agent_com_rank`
--
DROP TABLE IF EXISTS `agent_com_rank`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `agent_com_rank`  AS  select `agent_comp` AS `agent_comp`,count(`agent_comp`) AS `agent_comp_num` from `bidinfo` group by `agent_comp` having (`agent_comp_num` > 1) order by `agent_comp_num` desc ;

-- --------------------------------------------------------

--
-- 视图结构 `bidinfo_views_rank`
--
DROP TABLE IF EXISTS `bidinfo_views_rank`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bidinfo_views_rank`  AS  select `notice`.`about_id` AS `about_id`,`b_title` AS `b_title`,`notice`.`views` AS `views` from (`notice` join `bidinfo`) where (`notice`.`about_id` = `bid`) order by `notice`.`views` desc ;

-- --------------------------------------------------------

--
-- 视图结构 `detail_info`
--
DROP TABLE IF EXISTS `detail_info`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `detail_info`  AS  select `bid` AS `bid`,`b_title` AS `b_title`,`org_href` AS `org_href`,`btime_begin` AS `btime_begin`,`notice`.`b_detail` AS `b_detail`,`notice`.`views` AS `views` from (`notice` join `bidinfo`) where (`bid` = `notice`.`about_id`) ;

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
-- Indexes for table `notice`
--
ALTER TABLE `notice`
  ADD PRIMARY KEY (`notid`);

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
  MODIFY `bid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '公告编号', AUTO_INCREMENT=1074;
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
-- 使用表AUTO_INCREMENT `notice`
--
ALTER TABLE `notice`
  MODIFY `notid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '公告详情表编号', AUTO_INCREMENT=1074;
--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户编号', AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
