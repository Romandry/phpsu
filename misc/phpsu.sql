-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 17, 2014 at 07:26 AM
-- Server version: 5.1.73
-- PHP Version: 5.3.3-7+squeeze19

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `phpsu`
--

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `is_protected` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `priority` int(4) unsigned NOT NULL,
  `name` char(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `is_protected`, `priority`, `name`) VALUES
(1, 1, 1001, 'Guest'),
(2, 1, 0, 'CLI (cron)'),
(3, 1, 0, 'root');

-- --------------------------------------------------------

--
-- Table structure for table `groups_permissions`
--

DROP TABLE IF EXISTS `groups_permissions`;
CREATE TABLE IF NOT EXISTS `groups_permissions` (
  `group_id` bigint(20) unsigned NOT NULL,
  `permission_id` bigint(20) unsigned NOT NULL,
  KEY `group_id` (`group_id`),
  KEY `permission_id` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `groups_permissions`
--


-- --------------------------------------------------------

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cookie` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `email` char(255) NOT NULL,
  `login` char(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `time_zone` char(6) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `creation_date` datetime NOT NULL,
  `last_ip` char(15) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `last_visit` datetime NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `activation_hash` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `activation_hash` (`activation_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `members`
--


-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `permissions`
--

