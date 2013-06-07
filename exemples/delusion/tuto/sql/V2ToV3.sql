/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50508
Source Host           : localhost:3306
Source Database       : grobe_other

Target Server Type    : MYSQL
Target Server Version : 50508
File Encoding         : 65001

Date: 2011-06-27 19:26:47
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `commentaires`
-- ----------------------------
DROP TABLE IF EXISTS `commentaires`;
CREATE TABLE `commentaires` (
  `guid` int(255) NOT NULL AUTO_INCREMENT,
  `id` int(255) NOT NULL,
  `text` blob NOT NULL,
  `auteur` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL DEFAULT '?? / ?? / ????',
  PRIMARY KEY (`guid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of commentaires
-- ----------------------------
INSERT INTO `commentaires` VALUES ('1', '8', 0x6A6520636F6D6D656E7465, 'Admin', '27 / 06 / 2011');
ALTER TABLE `news`
ADD COLUMN `date`  varchar(255) NOT NULL DEFAULT '?? / ?? / ????' AFTER `type`;
ALTER TABLE `guess_book`
ADD COLUMN `date`  varchar(255) NOT NULL DEFAULT '?? / ?? / ????' AFTER `pseudo`;
