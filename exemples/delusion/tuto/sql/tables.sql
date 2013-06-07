/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50508
Source Host           : localhost:3306
Source Database       : grobe_other

Target Server Type    : MYSQL
Target Server Version : 50508
File Encoding         : 65001

Date: 2011-06-23 18:03:54
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `guess_book`
-- ----------------------------
DROP TABLE IF EXISTS `guess_book`;
CREATE TABLE `guess_book` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `text` blob NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`text`(767))
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of guess_book
-- ----------------------------
INSERT INTO `guess_book` VALUES ('51', 0x4A65207369676E65206C65206C697672652064276F72, 'Admin');

-- ----------------------------
-- Table structure for `news`
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `text` longblob NOT NULL,
  `auteur` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'news',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of news
-- ----------------------------
INSERT INTO `news` VALUES ('6', 'Une news', 0x566F696CE020756E65206E657773, 'Admin', 'news');
INSERT INTO `news` VALUES ('7', 'Une info', 0x756E6520696E666F, 'Admin', 'info');
INSERT INTO `news` VALUES ('8', 'Un plus', 0x457420656E66696E20756E207479706520706C7573, 'Admin', 'plus');
