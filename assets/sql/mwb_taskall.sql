-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- ホスト: localhost
-- 生成日時: 2015 年 4 月 16 日 18:55
-- サーバのバージョン: 5.1.73
-- PHP のバージョン: 5.3.29

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- データベース: `mwb_taskall`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `apply_tbl`
--

CREATE TABLE IF NOT EXISTS `apply_tbl` (
  `apply_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `job_id` bigint(20) NOT NULL,
  `apply_user_id` bigint(20) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0',
  `decide_flg` int(1) NOT NULL DEFAULT '0',
  `update_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`apply_id`),
  UNIQUE KEY `apply_id` (`apply_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `job_tbl`
--

CREATE TABLE IF NOT EXISTS `job_tbl` (
  `job_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `workpanel_id` int(11) DEFAULT NULL,
  `client_user_id` bigint(20) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `img_path` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` text,
  `zipcode` varchar(8) DEFAULT NULL,
  `start_dt` timestamp NULL DEFAULT NULL,
  `price` int(11) NOT NULL DEFAULT '0',
  `workload` int(11) NOT NULL DEFAULT '0',
  `apply_end_flg` int(1) NOT NULL DEFAULT '0',
  `done_flg` int(1) NOT NULL DEFAULT '0',
  `client_user_review_flg` int(1) NOT NULL DEFAULT '0',
  `apply_user_review_flg` int(1) NOT NULL DEFAULT '0',
  `pay_flg` int(1) NOT NULL DEFAULT '0',
  `update_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `job_id` (`job_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `message_tbl`
--

CREATE TABLE IF NOT EXISTS `message_tbl` (
  `message_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sender_user_id` bigint(20) DEFAULT NULL,
  `receiver_user_id` bigint(20) DEFAULT NULL,
  `body` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `read_flg` int(1) DEFAULT '0',
  `create_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `message_id` (`message_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `user_review_tbl`
--

CREATE TABLE IF NOT EXISTS `user_review_tbl` (
  `review_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `job_id` bigint(20) NOT NULL,
  `sender_user_id` bigint(20) NOT NULL,
  `receiver_user_id` bigint(20) NOT NULL,
  `review_value` int(1) DEFAULT NULL,
  `body` text,
  `create_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `review_id` (`review_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `user_tbl`
--

CREATE TABLE IF NOT EXISTS `user_tbl` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `facebook_id` bigint(20) DEFAULT NULL,
  `mail_address` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `img_path` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `sex` int(1) DEFAULT NULL,
  `location` int(11) DEFAULT NULL,
  `area` int(11) DEFAULT NULL,
  `address` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel` varchar(14) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `profile` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `file_path` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `identify_flg` int(11) NOT NULL DEFAULT '0',
  `job_request_sum` int(11) NOT NULL DEFAULT '0',
  `job_done_sum` int(11) NOT NULL DEFAULT '0',
  `review_rows` int(11) NOT NULL DEFAULT '0',
  `review_value_avg` float NOT NULL DEFAULT '0',
  `update_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
