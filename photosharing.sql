/*
 Navicat Premium Data Transfer

 Source Server         : PhpStudy_Pro
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : photosharing

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 28/04/2020 13:07:27
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ps_classify
-- ----------------------------
DROP TABLE IF EXISTS `ps_classify`;
CREATE TABLE `ps_classify`  (
  `id` int(4) NOT NULL COMMENT '分类id',
  `name` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '分类名称',
  `front_cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '封面图片url',
  `create_time` int(10) NULL DEFAULT NULL,
  `update_time` int(10) NULL DEFAULT NULL,
  `delete_time` int(10) NULL DEFAULT NULL,
  `imgNum` int(10) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ps_collect
-- ----------------------------
DROP TABLE IF EXISTS `ps_collect`;
CREATE TABLE `ps_collect`  (
  `id` int(30) UNSIGNED NOT NULL AUTO_INCREMENT,
  `img_id` int(10) NOT NULL,
  `user_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `create_time` int(10) NULL DEFAULT NULL,
  `update_time` int(10) NULL DEFAULT NULL,
  `delete_time` int(10) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `img_id`(`img_id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ps_download
-- ----------------------------
DROP TABLE IF EXISTS `ps_download`;
CREATE TABLE `ps_download`  (
  `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '图片下载编号',
  `img_id` int(10) NOT NULL,
  `user_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `create_time` int(10) NOT NULL,
  `delete_time` int(10) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `img_id`(`img_id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ps_img
-- ----------------------------
DROP TABLE IF EXISTS `ps_img`;
CREATE TABLE `ps_img`  (
  `id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `width` int(4) NULL DEFAULT NULL,
  `height` int(4) NULL DEFAULT NULL,
  `tags` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `create_time` int(10) NULL DEFAULT NULL,
  `update_time` int(10) NULL DEFAULT NULL,
  `delete_time` int(10) NULL DEFAULT NULL,
  `like` int(6) NULL DEFAULT NULL,
  `collect` int(6) NULL DEFAULT NULL,
  `download` int(6) NULL DEFAULT NULL,
  `user_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `imgUrl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `zipUrl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `homeShow` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `name`(`name`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ps_img
-- ----------------------------
INSERT INTO `ps_img` VALUES ('15ea7a97ebd68f', 'logo.png', 200, 200, NULL, 1588046206, 1588046206, NULL, 0, 0, 0, '5ea6917d427b8', 'http://qdu17zs.com/1588045961023___logo.png', 'http://qdu17zs.com/1588045961023___logo.png?imageslim', 1);

-- ----------------------------
-- Table structure for ps_like
-- ----------------------------
DROP TABLE IF EXISTS `ps_like`;
CREATE TABLE `ps_like`  (
  `id` int(30) NOT NULL,
  `img_id` int(10) NOT NULL,
  `user_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `create_time` int(10) NOT NULL,
  `update_time` int(10) NULL DEFAULT NULL,
  `delete_time` int(10) NULL DEFAULT NULL,
  `canLike` tinyint(1) NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `img_id`(`img_id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ps_user
-- ----------------------------
DROP TABLE IF EXISTS `ps_user`;
CREATE TABLE `ps_user`  (
  `id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `create_time` int(10) NOT NULL,
  `update_time` int(10) NULL DEFAULT NULL,
  `delete_time` int(10) NULL DEFAULT NULL,
  `gender` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `birthday` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `collectNum` int(6) NULL DEFAULT NULL,
  `likeNum` int(6) NULL DEFAULT NULL,
  `likedNum` int(6) NULL DEFAULT NULL,
  `downloadNum` int(6) NULL DEFAULT NULL,
  `uploadNum` int(6) NULL DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ps_user
-- ----------------------------
INSERT INTO `ps_user` VALUES ('5ea5a62392536', 'zs', '25d55ad283aa400af464c76d713c07ad', 1587914275, 1587914275, NULL, '男', '1999-06-08', 0, 0, 0, 0, 0, 'http://qdu17zs.com/logo.png');
INSERT INTO `ps_user` VALUES ('5ea5a72193b28', 'zs1', '25d55ad283aa400af464c76d713c07ad', 1587914529, 1587914529, NULL, '男', '1999-06-08', 0, 0, 0, 0, 0, 'http://qdu17zs.com/logo.png');
INSERT INTO `ps_user` VALUES ('5ea5a73f8841f', 'zs12', '25d55ad283aa400af464c76d713c07ad', 1587914559, 1587914559, NULL, '男', '1999-06-08', 0, 0, 0, 0, 0, 'http://qdu17zs.com/logo.png');
INSERT INTO `ps_user` VALUES ('5ea5a74ee9d68', 'zs123', '25d55ad283aa400af464c76d713c07ad', 1587914574, 1587914574, NULL, '男', '1999-06-08', 0, 0, 0, 0, 0, 'http://qdu17zs.com/logo.png');
INSERT INTO `ps_user` VALUES ('5ea5a8e838ca9', 'zs1234', '25d55ad283aa400af464c76d713c07ad', 1587914984, 1587914984, NULL, '男', '1999-06-08', 0, 0, 0, 0, 0, 'http://qdu17zs.com/logo.png');
INSERT INTO `ps_user` VALUES ('5ea6917d427b8', 'admin', '21232f297a57a5a743894a0e4a801fc3', 1587974525, 1587974525, NULL, '男', '1999-06-07T16:00:00.000Z', 0, 0, 0, 0, 0, 'http://qdu17zs.com/logo.png');

SET FOREIGN_KEY_CHECKS = 1;
