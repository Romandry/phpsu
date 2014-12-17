-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 17, 2014 at 10:00 PM
-- Server version: 5.1.73
-- PHP Version: 5.3.3-7+squeeze19

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `phpsu`
--

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `is_protected` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `priority` smallint(4) unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
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
  `group_id` smallint(5) unsigned NOT NULL,
  `permission_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`group_id`,`permission_id`),
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
  `cookie` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'unknown column',
  `email` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `time_zone` char(6) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'unknown column',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_ip` binary(16) NOT NULL COMMENT 'use php.net/inet_pton',
  `last_visit` datetime NOT NULL,
  `status` tinyint(1) unsigned NOT NULL COMMENT 'unknown column',
  `activation_hash` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'unknown column',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `login` (`login`),
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
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `permissions`
--


--
-- Constraints for dumped tables
--

--
-- Constraints for table `groups_permissions`
--
ALTER TABLE `groups_permissions`
  ADD CONSTRAINT `groups_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`),
  ADD CONSTRAINT `groups_permissions_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`);
SET FOREIGN_KEY_CHECKS=1;
