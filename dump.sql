-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 17, 2013 at 10:05 PM
-- Server version: 5.5.33
-- PHP Version: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `scraper`
--

-- --------------------------------------------------------

--
-- Table structure for table `headings`
--

CREATE TABLE IF NOT EXISTS `headings` (
  `heading_id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT 'Heading 1, 2, 3, 4, 5 or 6',
  `page_id` int(11) NOT NULL,
  PRIMARY KEY (`heading_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `meta`
--

CREATE TABLE IF NOT EXISTS `meta` (
  `meta_id` int(11) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  `keywords` text,
  `title` varchar(256) DEFAULT NULL,
  `page_id` int(11) NOT NULL,
  PRIMARY KEY (`meta_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(200) NOT NULL,
  `words` int(10) unsigned NOT NULL,
  `site_id` int(11) NOT NULL,
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `url` (`url`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE IF NOT EXISTS `sites` (
  `site_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`site_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
