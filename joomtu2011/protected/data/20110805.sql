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

/*Table structure for table `jt_assignment` */

DROP TABLE IF EXISTS `jt_assignment`;

CREATE TABLE `jt_assignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `jt_assignment` */

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

/*Table structure for table `jt_item` */

DROP TABLE IF EXISTS `jt_item`;

CREATE TABLE `jt_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `jt_item` */

insert  into `jt_item`(`name`,`type`,`description`,`bizrule`,`data`) values ('Authority',2,NULL,NULL,NULL),('Administrator',2,NULL,NULL,NULL),('User',2,NULL,NULL,NULL),('Post Manager',1,NULL,NULL,NULL),('User Manager',1,NULL,NULL,NULL),('Delete Post',0,NULL,NULL,NULL),('Create Post',0,NULL,NULL,NULL),('Edit Post',0,NULL,NULL,NULL),('View Post',0,NULL,NULL,NULL),('Delete User',0,NULL,NULL,NULL),('Create User',0,NULL,NULL,NULL),('Edit User',0,NULL,NULL,NULL),('View User',0,NULL,NULL,NULL);

/*Table structure for table `jt_item_children` */

DROP TABLE IF EXISTS `jt_item_children`;

CREATE TABLE `jt_item_children` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `jt_item_children` */

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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='用户表';

/*Data for the table `jt_user` */

insert  into `jt_user`(`id`,`username`,`password`,`salt`,`email`,`profile`) values (1,'admin','9401b8c7297832c567ae922cc596a4dd','28b206548469ce62182048fd9cf91760','webmaster@example.com',NULL),(2,'demo','2e5c7db760a33498023813489cfadc0b','28b206548469ce62182048fd9cf91760','webmaster@example.com',NULL),(3,'admin1','5aca33bcd833d38b777746ead3bf3917','4e3a0cb9c51f97.89125071','hu@163.com',NULL),(4,'admin2','70af329f6d19915650f6de79b8a927db','4e3a0d23a152c1.19083459','hu@163.com',NULL),(5,'admin3','0a39035c14db0f62d9512fc20047cd4c','4e3a0d585066c9.99132300','hu@163.com',''),(6,'admin4','09e159565bc92588e313592fd20499e9','4e3a0d6540e651.14803607','hu@163.com',''),(7,'admin5','1ddd9b97d24dd470e3ac57b5833823ff','4e3a0d73e94427.24409684','hu@163.com',''),(8,'admin6','0eb20d01c8c77b4f5085a6e9ce4c2ffe','4e3a0d85367e39.58214973','hu@163.com',''),(9,'admin7','3d1c378a2a54c05e2cee4bb45eb66eb2','4e3a0d9344fd92.20420272','hu@163.com',''),(10,'admin8','d54da9bda454d9880a9dea0a41b10bda','4e3a0da06e2e22.60446078','hu@163.com',''),(11,'admin9','aff015c13c87fe9741e86d0edc674030','4e3a0db33dfa98.76005245','hu@163.com','');

/*Table structure for table `jt_user_ext` */

DROP TABLE IF EXISTS `jt_user_ext`;

CREATE TABLE `jt_user_ext` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `jt_user_ext` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
