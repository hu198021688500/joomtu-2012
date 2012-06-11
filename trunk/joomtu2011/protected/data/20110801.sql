/*
SQLyog 企业版 - MySQL GUI v7.14 
MySQL - 5.1.44-ndb-7.1.3-cluster-gpl : Database - joomtu
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`joomtu` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `joomtu`;

/*Table structure for table `jt_assignments` */

DROP TABLE IF EXISTS `jt_assignments`;

CREATE TABLE `jt_assignments` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `jt_assignments` */

insert  into `jt_assignments`(`itemname`,`userid`,`bizrule`,`data`) values ('Authority','1','','s:0:\"\";');

/*Table structure for table `jt_image` */

DROP TABLE IF EXISTS `jt_image`;

CREATE TABLE `jt_image` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parentId` int(10) unsigned NOT NULL,
  `parent` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `extension` varchar(255) NOT NULL,
  `byteSize` int(10) unsigned NOT NULL,
  `mimeType` varchar(255) NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `jt_image` */

/*Table structure for table `jt_itemchildren` */

DROP TABLE IF EXISTS `jt_itemchildren`;

CREATE TABLE `jt_itemchildren` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `jt_itemchildren` */

/*Table structure for table `jt_items` */

DROP TABLE IF EXISTS `jt_items`;

CREATE TABLE `jt_items` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `jt_items` */

insert  into `jt_items`(`name`,`type`,`description`,`bizrule`,`data`) values ('Authority',2,NULL,NULL,NULL),('Administrator',2,NULL,NULL,NULL),('User',2,NULL,NULL,NULL),('Post Manager',1,NULL,NULL,NULL),('User Manager',1,NULL,NULL,NULL),('Delete Post',0,NULL,NULL,NULL),('Create Post',0,NULL,NULL,NULL),('Edit Post',0,NULL,NULL,NULL),('View Post',0,NULL,NULL,NULL),('Delete User',0,NULL,NULL,NULL),('Create User',0,NULL,NULL,NULL),('Edit User',0,NULL,NULL,NULL),('View User',0,NULL,NULL,NULL);

/*Table structure for table `jt_user` */

DROP TABLE IF EXISTS `jt_user`;

CREATE TABLE `jt_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `salt` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `profile` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `jt_user` */

insert  into `jt_user`(`id`,`username`,`password`,`salt`,`email`,`profile`) values (1,'admin','9401b8c7297832c567ae922cc596a4dd','28b206548469ce62182048fd9cf91760','webmaster@example.com',NULL),(2,'demo','2e5c7db760a33498023813489cfadc0b','28b206548469ce62182048fd9cf91760','webmaster@example.com',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
