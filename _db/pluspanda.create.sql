-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 16, 2009 at 08:11 AM
-- Server version: 5.1.33
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: 'pluspanda'
--

-- --------------------------------------------------------

--
-- Table structure for table 'reviews'
--

CREATE TABLE IF NOT EXISTS reviews (
  id int(9) unsigned NOT NULL AUTO_INCREMENT,
  site_id int(9) unsigned NOT NULL,
  tag_id int(9) unsigned NOT NULL,
  user_id int(9) NOT NULL,
  body text NOT NULL,
  rating int(1) NOT NULL,
  created int(10) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'sites'
--

CREATE TABLE IF NOT EXISTS sites (
  id int(7) unsigned NOT NULL AUTO_INCREMENT,
  subdomain varchar(50) NOT NULL,
  custom_domain varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  homepage varchar(30) NOT NULL DEFAULT 'home',
  created int(10) NOT NULL,
  PRIMARY KEY (id),
  KEY url (subdomain)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'tags'
--

CREATE TABLE IF NOT EXISTS tags (
  id int(9) NOT NULL AUTO_INCREMENT,
  site_id int(9) NOT NULL,
  `name` varchar(100) NOT NULL,
  `desc` text NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'users'
--

CREATE TABLE IF NOT EXISTS users (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  site_id int(7) NOT NULL,
  email varchar(127) NOT NULL,
  display_name varchar(32) NOT NULL,
  token varchar(32) NOT NULL,
  created int(10) NOT NULL,
  PRIMARY KEY (id),
  KEY email (email)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'version'
--

CREATE TABLE IF NOT EXISTS version (
  `at` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
