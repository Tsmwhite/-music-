/*
Navicat MySQL Data Transfer

Source Server         : 121.40.118.97
Source Server Version : 50725
Source Host           : 121.40.118.97:3306
Source Database       : yishu

Target Server Type    : MYSQL
Target Server Version : 50725
File Encoding         : 65001

Date: 2019-09-11 16:52:04
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `admin`
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_bin NOT NULL COMMENT '用户名',
  `nickname` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL COMMENT '昵称',
  `password` varchar(64) COLLATE utf8mb4_bin DEFAULT NULL COMMENT '密码',
  `mobile` varchar(11) COLLATE utf8mb4_bin DEFAULT NULL COMMENT '手机号码',
  `avatar` varchar(255) COLLATE utf8mb4_bin DEFAULT '/static/img/avatar.jpg' COMMENT '头像',
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `html` text COLLATE utf8mb4_bin,
  `state` int(1) DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `username` (`username`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='用户表';

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES ('1', 'admin', 'admin', '96e79218965eb72c92a549dd5a330112', '17316948813', '/uploads/20181117/153f01a03e6a7631e2d524575d4ccd12.png', '2018-10-23 15:17:03', '2018-11-17 11:09:46', 0x3C703E266E6273703B3C2F703E3C703EE59388E59388E59388E59388E59388E593883C2F703E3C703E266E6273703B3C2F703E3C626C6F636B71756F74653E3C703E746F206265206F72206E6F7420746F2062652C20746869732069732061207175657374696F6E2E3C2F703E3C2F626C6F636B71756F74653E, null);

-- ----------------------------
-- Table structure for `admin_node`
-- ----------------------------
DROP TABLE IF EXISTS `admin_node`;
CREATE TABLE `admin_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `nid` int(11) NOT NULL,
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of admin_node
-- ----------------------------
INSERT INTO `admin_node` VALUES ('1', '1', '4', '2018-11-16 15:47:28', null);
INSERT INTO `admin_node` VALUES ('2', '1', '1', '2018-11-19 14:16:00', '2018-11-19 14:16:47');
INSERT INTO `admin_node` VALUES ('3', '1', '28', '2018-11-22 12:32:04', null);
INSERT INTO `admin_node` VALUES ('4', '1', '29', '2018-11-22 12:46:54', null);
INSERT INTO `admin_node` VALUES ('5', '1', '35', '2018-12-18 19:50:18', null);

-- ----------------------------
-- Table structure for `admin_role`
-- ----------------------------
DROP TABLE IF EXISTS `admin_role`;
CREATE TABLE `admin_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of admin_role
-- ----------------------------
INSERT INTO `admin_role` VALUES ('1', '1', '1', '2018-10-23 19:02:03', null);
INSERT INTO `admin_role` VALUES ('2', '2', '2', '2018-10-24 19:03:24', null);
INSERT INTO `admin_role` VALUES ('3', '3', '2', '2018-10-24 19:03:30', null);
INSERT INTO `admin_role` VALUES ('4', '4', '2', '2018-10-24 19:03:35', null);
INSERT INTO `admin_role` VALUES ('5', '5', '2', '2018-10-24 19:03:39', '2018-12-07 12:10:38');
INSERT INTO `admin_role` VALUES ('6', '26', '1', '2018-12-07 12:12:42', null);

-- ----------------------------
-- Table structure for `article`
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of article
-- ----------------------------

-- ----------------------------
-- Table structure for `assess`
-- ----------------------------
DROP TABLE IF EXISTS `assess`;
CREATE TABLE `assess` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(255) DEFAULT NULL,
  `class_id` varchar(255) DEFAULT NULL,
  `teacher_star` varchar(255) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `star` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `class_list_id` varchar(255) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `class_content` varchar(255) DEFAULT NULL,
  `class_star` varchar(255) DEFAULT NULL,
  `teacher_content` varchar(255) DEFAULT NULL,
  `teacher_teach_star` varchar(255) DEFAULT NULL,
  `teacher_teach_mode_star` varchar(255) DEFAULT NULL,
  `teacher_teach_bearing_star` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of assess
-- ----------------------------

-- ----------------------------
-- Table structure for `class_list`
-- ----------------------------
DROP TABLE IF EXISTS `class_list`;
CREATE TABLE `class_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) DEFAULT NULL,
  `start_time` varchar(255) DEFAULT NULL,
  `stop_time` varchar(255) DEFAULT NULL,
  `teacher_id` varchar(255) DEFAULT NULL,
  `class_id` varchar(255) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `student_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of class_list
-- ----------------------------

-- ----------------------------
-- Table structure for `classss`
-- ----------------------------
DROP TABLE IF EXISTS `classss`;
CREATE TABLE `classss` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `mix_time_type` varchar(255) DEFAULT NULL,
  `max_time_type` varchar(255) DEFAULT NULL,
  `class_star` varchar(255) DEFAULT NULL,
  `star` varchar(255) DEFAULT NULL,
  `money` varchar(255) DEFAULT NULL,
  `class_name` varchar(255) DEFAULT NULL,
  `class_photo` varchar(255) DEFAULT NULL,
  `body` varchar(255) DEFAULT NULL,
  `environment_photo1` varchar(255) DEFAULT NULL,
  `environment_photo2` varchar(255) DEFAULT NULL,
  `environment_photo3` varchar(255) DEFAULT NULL,
  `environment_photo4` varchar(255) DEFAULT NULL,
  `environment_photo5` varchar(255) DEFAULT NULL,
  `environment_photo6` varchar(255) DEFAULT NULL,
  `music_id` int(11) DEFAULT NULL,
  `money2` varchar(255) DEFAULT NULL,
  `money3` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of classss
-- ----------------------------
INSERT INTO `classss` VALUES ('1', '测试课程一', '测试课程一简介', '/uploads/20190911/5c88954bc2415b7e59df819811dcc4af.png', '20', '60', null, null, '150', null, null, '测试课程一内容', '', '', '', '', '', '', '1', '100', '50');
INSERT INTO `classss` VALUES ('2', '测试课程二', '简介', '/uploads/20190911/b3d936d0173573b2a4234cf597139eef.jpg', '20', '60', null, null, '150', null, null, '测试课程一内容', '', '', '', '', '', '', '2', '100', '50');
INSERT INTO `classss` VALUES ('3', '测试课程三', '简介', '/uploads/20190911/5442670211d42036d87e30dd8d5e4be9.png', '20', '60', null, null, '150', null, null, '测试课程一内容', '/uploads/20190911/d514d8033b257dccbc25d53aa2e441f2.png', '/uploads/20190911/13602d922ca68ef0ff7015f597589b30.jpg', '', '', '', '', '3', null, null);
INSERT INTO `classss` VALUES ('4', '测试课程四', '简介', '/uploads/20190911/65af62e2bd81beec3236a763b485ed2b.jpg', '20', '60', null, null, '150', null, null, '测试课程一内容', '/uploads/20190911/0c120d71871598839cfca4371d8d0571.png', '', '', '', '', '', '3', null, null);

-- ----------------------------
-- Table structure for `feedback`
-- ----------------------------
DROP TABLE IF EXISTS `feedback`;
CREATE TABLE `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `body` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of feedback
-- ----------------------------

-- ----------------------------
-- Table structure for `finance`
-- ----------------------------
DROP TABLE IF EXISTS `finance`;
CREATE TABLE `finance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) DEFAULT NULL,
  `start_time` varchar(255) DEFAULT NULL,
  `stop_time` varchar(255) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `money` varchar(255) DEFAULT NULL,
  `student_num` varchar(255) DEFAULT NULL,
  `class_count` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of finance
-- ----------------------------
INSERT INTO `finance` VALUES ('1', '2', '1234568', '1234567', null, null, null, null, null);

-- ----------------------------
-- Table structure for `forward`
-- ----------------------------
DROP TABLE IF EXISTS `forward`;
CREATE TABLE `forward` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_id` int(11) DEFAULT NULL,
  `people_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of forward
-- ----------------------------

-- ----------------------------
-- Table structure for `friend_message`
-- ----------------------------
DROP TABLE IF EXISTS `friend_message`;
CREATE TABLE `friend_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `body` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `people_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `add_time` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of friend_message
-- ----------------------------
INSERT INTO `friend_message` VALUES ('1', '这是一条测试内容', '这是一条假的视频路径', '1', '1', '1568189988');

-- ----------------------------
-- Table structure for `hello`
-- ----------------------------
DROP TABLE IF EXISTS `hello`;
CREATE TABLE `hello` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(64) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of hello
-- ----------------------------
INSERT INTO `hello` VALUES ('1', '123', '1234', '2018-12-19 16:17:23');
INSERT INTO `hello` VALUES ('2', '12tyaf13123', '002', '2019-03-01 00:00:00');

-- ----------------------------
-- Table structure for `menu`
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nid` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT '0' COMMENT '父级id',
  `name` varchar(50) COLLATE utf8mb4_bin NOT NULL COMMENT '名称',
  `order` int(3) NOT NULL DEFAULT '50',
  `class` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='菜单';

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES ('11', '1', null, '首页', '100', 'layui-icon layui-icon-home', '2018-11-16 15:51:56', '2019-01-23 12:36:41');
INSERT INTO `menu` VALUES ('13', '2', null, '管理员', '50', 'layui-icon layui-icon-group', '2018-11-16 15:52:55', '2019-03-01 17:39:44');
INSERT INTO `menu` VALUES ('17', null, null, '权限和菜单', '50', 'layui-icon layui-icon-set-fill	', '2018-11-19 17:36:53', '2019-01-23 12:37:01');
INSERT INTO `menu` VALUES ('18', '8', '17', '角色管理', '50', 'layui-icon layui-icon-group', '2018-11-19 17:38:05', '2019-03-01 17:36:06');
INSERT INTO `menu` VALUES ('19', null, '17', '权限管理', '50', 'layui-icon layui-icon-app', '2018-11-19 17:38:21', '2019-03-01 17:37:05');
INSERT INTO `menu` VALUES ('20', '24', '17', '菜单管理', '50', 'layui-icon layui-icon-tabs', '2018-11-19 17:38:31', '2019-03-01 17:37:44');
INSERT INTO `menu` VALUES ('24', '14', '19', '权限管理', '50', 'layui-icon layui-icon-app', '2018-11-21 11:05:10', '2019-03-01 17:37:58');
INSERT INTO `menu` VALUES ('25', '20', '19', '用户权限管理', '50', 'layui-icon-username layui-icon', '2018-11-21 11:05:24', '2019-03-01 17:38:42');
INSERT INTO `menu` VALUES ('26', '17', '19', '角色权限管理', '50', 'layui-icon-user layui-icon', '2018-11-21 11:05:42', '2019-03-01 17:39:08');
INSERT INTO `menu` VALUES ('28', '51', null, '代码生成', '50', 'layui-icon layui-icon-fonts-code', '2018-12-01 12:50:17', '2019-09-10 15:29:41');
INSERT INTO `menu` VALUES ('29', '39', null, '测试模块', '50', '', '2019-03-02 18:11:43', null);
INSERT INTO `menu` VALUES ('31', '52', null, '课程管理', '50', 'layui-icon-star layui-icon', '2019-09-10 15:40:00', '2019-09-10 17:04:10');
INSERT INTO `menu` VALUES ('32', '58', null, '意见反馈', '50', 'layui-icon-star layui-icon', '2019-09-10 15:42:24', '2019-09-10 17:04:25');
INSERT INTO `menu` VALUES ('33', '67', null, '薪资管理', '50', 'layui-icon-star layui-icon', '2019-09-10 16:23:54', '2019-09-10 17:04:40');
INSERT INTO `menu` VALUES ('34', '73', null, '分类管理', '50', 'layui-icon-star layui-icon', '2019-09-10 16:26:20', '2019-09-10 17:05:05');
INSERT INTO `menu` VALUES ('35', '77', null, '订单管理', '50', 'layui-icon-star layui-icon', '2019-09-10 16:35:49', '2019-09-10 17:07:31');
INSERT INTO `menu` VALUES ('36', '82', null, '学生管理', '50', 'layui-icon-star layui-icon', '2019-09-10 16:40:28', '2019-09-10 17:08:35');
INSERT INTO `menu` VALUES ('37', '88', null, '优惠券', '50', 'layui-icon-star layui-icon', '2019-09-10 16:44:51', '2019-09-10 17:08:43');
INSERT INTO `menu` VALUES ('38', '92', null, '教师管理', '50', 'layui-icon-star layui-icon', '2019-09-10 16:58:10', '2019-09-10 17:08:50');
INSERT INTO `menu` VALUES ('39', '99', null, '订单管理', '50', 'layui-icon-star layui-icon', '2019-09-10 17:00:31', '2019-09-10 17:08:58');

-- ----------------------------
-- Table structure for `message`
-- ----------------------------
DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_id` int(11) DEFAULT NULL,
  `people_id` int(11) DEFAULT NULL,
  `body` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of message
-- ----------------------------

-- ----------------------------
-- Table structure for `money_shop`
-- ----------------------------
DROP TABLE IF EXISTS `money_shop`;
CREATE TABLE `money_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_money` varchar(255) DEFAULT NULL,
  `give_money` varchar(255) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of money_shop
-- ----------------------------

-- ----------------------------
-- Table structure for `music`
-- ----------------------------
DROP TABLE IF EXISTS `music`;
CREATE TABLE `music` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `body` varchar(255) DEFAULT NULL,
  `pid` int(11) DEFAULT '0',
  `sort` varchar(255) DEFAULT NULL,
  `class_star` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of music
-- ----------------------------
INSERT INTO `music` VALUES ('1', '分类一', '/uploads/20190911/e7c789b84587044627d883e8b788bb7b.png', '分类一简介', '分类一内容', '0', '0', null);
INSERT INTO `music` VALUES ('2', '分类二', '/uploads/20190911/0a23d08851c4ec8df1de82a7bc69ebd3.jpg', '分类二简介', '分类二内容', '0', '0', null);
INSERT INTO `music` VALUES ('3', '分类三', '/uploads/20190911/f21460218e8a0bf5bed169be6437f5b3.png', '分类二简介', '分类一内容', '0', null, null);

-- ----------------------------
-- Table structure for `music_teacher_list`
-- ----------------------------
DROP TABLE IF EXISTS `music_teacher_list`;
CREATE TABLE `music_teacher_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) DEFAULT NULL,
  `music_sun_id` int(11) DEFAULT NULL,
  `class_star` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of music_teacher_list
-- ----------------------------

-- ----------------------------
-- Table structure for `node`
-- ----------------------------
DROP TABLE IF EXISTS `node`;
CREATE TABLE `node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(64) COLLATE utf8mb4_bin DEFAULT NULL,
  `controller` varchar(64) COLLATE utf8mb4_bin DEFAULT NULL,
  `action` varchar(64) COLLATE utf8mb4_bin DEFAULT NULL,
  `msg` varchar(45) COLLATE utf8mb4_bin DEFAULT NULL COMMENT '备注',
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mca` (`module`,`controller`,`action`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='节点';

-- ----------------------------
-- Records of node
-- ----------------------------
INSERT INTO `node` VALUES ('1', 'admin', 'index', 'index', null, '2018-10-19 18:27:23', '2018-10-22 11:01:49');
INSERT INTO `node` VALUES ('2', 'admin', 'user', 'index', null, '2018-10-22 11:01:49', null);
INSERT INTO `node` VALUES ('3', 'admin', 'user', 'update', null, '2018-10-22 11:01:49', null);
INSERT INTO `node` VALUES ('7', 'admin', 'user', 'add', null, '2018-11-16 18:29:27', null);
INSERT INTO `node` VALUES ('8', 'admin', 'role', 'index', null, '2018-11-21 11:17:33', null);
INSERT INTO `node` VALUES ('9', 'admin', 'role', 'update', null, '2018-11-21 11:17:41', null);
INSERT INTO `node` VALUES ('10', 'admin', 'role', 'add', null, '2018-11-21 11:17:50', null);
INSERT INTO `node` VALUES ('14', 'admin', 'node', 'index', null, '2018-11-21 13:14:40', null);
INSERT INTO `node` VALUES ('15', 'admin', 'node', 'add', null, '2018-11-21 13:14:46', null);
INSERT INTO `node` VALUES ('16', 'admin', 'node', 'update', null, '2018-11-21 13:14:53', null);
INSERT INTO `node` VALUES ('17', 'admin', 'role_node', 'index', null, '2018-11-21 13:31:30', null);
INSERT INTO `node` VALUES ('18', 'admin', 'role_node', 'add', null, '2018-11-21 13:31:42', null);
INSERT INTO `node` VALUES ('19', 'admin', 'role_node', 'update', null, '2018-11-21 13:31:58', null);
INSERT INTO `node` VALUES ('20', 'admin', 'user_node', 'index', null, '2018-11-21 13:33:24', null);
INSERT INTO `node` VALUES ('21', 'admin', 'user_node', 'add', null, '2018-11-21 13:33:40', null);
INSERT INTO `node` VALUES ('22', 'admin', 'user_node', 'update', null, '2018-11-21 13:33:54', null);
INSERT INTO `node` VALUES ('23', 'admin', 'node', 'delete', null, '2018-11-21 15:24:35', null);
INSERT INTO `node` VALUES ('24', 'admin', 'menu', 'index', null, '2018-11-21 16:54:24', null);
INSERT INTO `node` VALUES ('25', 'admin', 'menu', 'add', null, '2018-11-21 16:54:38', null);
INSERT INTO `node` VALUES ('26', 'admin', 'menu', 'update', null, '2018-11-21 16:54:48', null);
INSERT INTO `node` VALUES ('27', 'admin', 'menu', 'delete', null, '2018-11-21 16:54:57', null);
INSERT INTO `node` VALUES ('29', 'admin', 'user', 'upload', null, '2018-11-22 12:46:35', null);
INSERT INTO `node` VALUES ('37', 'admin', 'role_node', 'delete', null, '2018-12-18 19:53:28', null);
INSERT INTO `node` VALUES ('38', 'admin', 'user', 'export', null, '2019-03-02 15:07:10', null);
INSERT INTO `node` VALUES ('44', 'admin', 'user', 'delete', null, '2019-03-04 11:09:59', null);
INSERT INTO `node` VALUES ('45', 'admin', 'menu', 'export', null, '2019-03-04 15:24:31', null);
INSERT INTO `node` VALUES ('46', 'admin', 'assess', 'index', null, '2019-09-10 15:24:21', null);
INSERT INTO `node` VALUES ('47', 'admin', 'assess', 'update', null, '2019-09-10 15:24:31', null);
INSERT INTO `node` VALUES ('48', 'admin', 'assess', 'add', null, '2019-09-10 15:24:37', null);
INSERT INTO `node` VALUES ('49', 'admin', 'assess', 'import', null, '2019-09-10 15:24:44', null);
INSERT INTO `node` VALUES ('50', 'admin', 'assess', 'export', null, '2019-09-10 15:25:06', null);
INSERT INTO `node` VALUES ('51', 'admin', 'generate', 'index', null, '2019-09-10 15:28:04', null);
INSERT INTO `node` VALUES ('52', 'admin', 'classss', 'index', null, '2019-09-10 15:35:28', null);
INSERT INTO `node` VALUES ('53', 'admin', 'classss', 'import', null, '2019-09-10 15:35:35', null);
INSERT INTO `node` VALUES ('54', 'admin', 'classss', 'update', null, '2019-09-10 15:35:41', null);
INSERT INTO `node` VALUES ('55', 'admin', 'classss', 'add', null, '2019-09-10 15:35:54', null);
INSERT INTO `node` VALUES ('57', 'admin', 'classss', 'export', null, '2019-09-10 15:36:11', null);
INSERT INTO `node` VALUES ('58', 'admin', 'feedback', 'index', null, '2019-09-10 15:40:59', null);
INSERT INTO `node` VALUES ('59', 'admin', 'feedback', 'add', null, '2019-09-10 15:41:21', null);
INSERT INTO `node` VALUES ('60', 'admin', 'feedback', 'update', null, '2019-09-10 15:41:28', null);
INSERT INTO `node` VALUES ('61', 'admin', 'feedback', 'import', null, '2019-09-10 15:41:37', null);
INSERT INTO `node` VALUES ('62', 'admin', 'feedback', 'export', null, '2019-09-10 15:41:48', null);
INSERT INTO `node` VALUES ('63', 'admin', 'assess', 'delete', null, '2019-09-10 15:43:13', null);
INSERT INTO `node` VALUES ('65', 'admin', 'classss', 'delete', null, '2019-09-10 15:43:25', null);
INSERT INTO `node` VALUES ('66', 'admin', 'feedback', 'delete', null, '2019-09-10 15:43:32', null);
INSERT INTO `node` VALUES ('67', 'admin', 'finance', 'index', null, '2019-09-10 16:08:23', null);
INSERT INTO `node` VALUES ('68', 'admin', 'finance', 'add', null, '2019-09-10 16:08:31', null);
INSERT INTO `node` VALUES ('69', 'admin', 'finance', 'update', null, '2019-09-10 16:22:04', null);
INSERT INTO `node` VALUES ('70', 'admin', 'finance', 'delete', null, '2019-09-10 16:22:19', null);
INSERT INTO `node` VALUES ('71', 'admin', 'finance', 'type', null, '2019-09-10 16:22:41', null);
INSERT INTO `node` VALUES ('72', 'admin', 'finance', 'info', null, '2019-09-10 16:22:48', null);
INSERT INTO `node` VALUES ('73', 'admin', 'music', 'index', null, '2019-09-10 16:25:15', null);
INSERT INTO `node` VALUES ('74', 'admin', 'music', 'add', null, '2019-09-10 16:25:21', null);
INSERT INTO `node` VALUES ('75', 'admin', 'music', 'update', null, '2019-09-10 16:25:28', null);
INSERT INTO `node` VALUES ('76', 'admin', 'music', 'delete', null, '2019-09-10 16:25:36', null);
INSERT INTO `node` VALUES ('77', 'admin', 'order', 'index', null, '2019-09-10 16:33:12', null);
INSERT INTO `node` VALUES ('78', 'admin', 'order', 'add', null, '2019-09-10 16:33:22', null);
INSERT INTO `node` VALUES ('80', 'admin', 'order', 'update', null, '2019-09-10 16:33:41', null);
INSERT INTO `node` VALUES ('81', 'admin', 'order', 'delete', null, '2019-09-10 16:34:58', null);
INSERT INTO `node` VALUES ('82', 'admin', 'student', 'index', null, '2019-09-10 16:39:12', null);
INSERT INTO `node` VALUES ('83', 'admin', 'student', 'add', null, '2019-09-10 16:39:18', null);
INSERT INTO `node` VALUES ('84', 'admin', 'student', 'update', null, '2019-09-10 16:39:24', null);
INSERT INTO `node` VALUES ('85', 'admin', 'student', 'delete', null, '2019-09-10 16:39:29', null);
INSERT INTO `node` VALUES ('86', 'admin', 'student', 'user_info', null, '2019-09-10 16:39:42', null);
INSERT INTO `node` VALUES ('87', 'admin', 'student', 'export', null, '2019-09-10 16:42:00', null);
INSERT INTO `node` VALUES ('88', 'admin', 'student_coupon', 'index', null, '2019-09-10 16:43:38', '2019-09-10 16:46:34');
INSERT INTO `node` VALUES ('89', 'admin', 'student_coupon', 'add', null, '2019-09-10 16:43:59', '2019-09-10 16:46:17');
INSERT INTO `node` VALUES ('90', 'admin', 'student_coupon', 'update', null, '2019-09-10 16:44:09', '2019-09-10 16:46:26');
INSERT INTO `node` VALUES ('91', 'admin', 'student_coupon', 'delete', null, '2019-09-10 16:44:17', '2019-09-10 16:46:41');
INSERT INTO `node` VALUES ('92', 'admin', 'teacher', 'index', null, '2019-09-10 16:56:56', null);
INSERT INTO `node` VALUES ('93', 'admin', 'teacher', 'add', null, '2019-09-10 16:57:01', null);
INSERT INTO `node` VALUES ('94', 'admin', 'teacher', 'update', null, '2019-09-10 16:57:07', null);
INSERT INTO `node` VALUES ('95', 'admin', 'teacher', 'delete', null, '2019-09-10 16:57:12', null);
INSERT INTO `node` VALUES ('96', 'admin', 'teacher', 'type', null, '2019-09-10 16:57:31', null);
INSERT INTO `node` VALUES ('97', 'admin', 'teacher', 'info', null, '2019-09-10 16:57:38', null);
INSERT INTO `node` VALUES ('98', 'admin', 'trade', 'add', null, '2019-09-10 16:59:19', null);
INSERT INTO `node` VALUES ('99', 'admin', 'trade', 'index', null, '2019-09-10 16:59:26', null);
INSERT INTO `node` VALUES ('100', 'admin', 'trade', 'update', null, '2019-09-10 16:59:31', null);
INSERT INTO `node` VALUES ('101', 'admin', 'trade', 'delete', null, '2019-09-10 16:59:38', null);
INSERT INTO `node` VALUES ('103', 'admin', 'teacher', 'renzheng', null, '2019-09-10 17:11:05', null);
INSERT INTO `node` VALUES ('104', 'admin', 'teacher', 'shenhe', null, '2019-09-10 17:12:32', null);

-- ----------------------------
-- Table structure for `order`
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `punch` varchar(255) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `notes_photo` varchar(255) DEFAULT NULL,
  `notes_video` varchar(255) DEFAULT NULL,
  `notes_content` varchar(255) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of order
-- ----------------------------
INSERT INTO `order` VALUES ('1', '1', '1', '1', '1', '1', '/uploads/20190910/bfd8f443418b3a886bc5fa1d418e7ff5.jpg', '1', '1', '1');

-- ----------------------------
-- Table structure for `paypal_list`
-- ----------------------------
DROP TABLE IF EXISTS `paypal_list`;
CREATE TABLE `paypal_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `money` varchar(255) DEFAULT NULL,
  `give_money` varchar(255) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ok_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of paypal_list
-- ----------------------------

-- ----------------------------
-- Table structure for `praise`
-- ----------------------------
DROP TABLE IF EXISTS `praise`;
CREATE TABLE `praise` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_id` int(11) DEFAULT NULL,
  `people_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of praise
-- ----------------------------

-- ----------------------------
-- Table structure for `punch`
-- ----------------------------
DROP TABLE IF EXISTS `punch`;
CREATE TABLE `punch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `star` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of punch
-- ----------------------------

-- ----------------------------
-- Table structure for `revision_class`
-- ----------------------------
DROP TABLE IF EXISTS `revision_class`;
CREATE TABLE `revision_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `data` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of revision_class
-- ----------------------------

-- ----------------------------
-- Table structure for `role`
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_bin NOT NULL COMMENT ' ',
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES ('1', '管理员', '2018-10-23 19:02:13', '2018-10-24 19:00:59');
INSERT INTO `role` VALUES ('2', '普通用户', '2018-10-24 19:00:34', '2018-10-24 19:00:53');
INSERT INTO `role` VALUES ('3', '测试角色01', '2018-11-21 13:09:28', null);

-- ----------------------------
-- Table structure for `role_node`
-- ----------------------------
DROP TABLE IF EXISTS `role_node`;
CREATE TABLE `role_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `nid` int(11) NOT NULL,
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rid_nid` (`rid`,`nid`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of role_node
-- ----------------------------
INSERT INTO `role_node` VALUES ('1', '1', '1', '2018-11-16 15:46:41', null);
INSERT INTO `role_node` VALUES ('2', '1', '2', '2018-11-16 15:46:47', '2018-11-19 17:50:39');
INSERT INTO `role_node` VALUES ('3', '1', '3', '2018-11-16 15:47:05', null);
INSERT INTO `role_node` VALUES ('4', '1', '7', '2018-11-16 18:31:29', '2018-11-19 17:50:37');
INSERT INTO `role_node` VALUES ('5', '1', '8', '2018-11-21 11:18:41', null);
INSERT INTO `role_node` VALUES ('6', '1', '9', '2018-11-21 11:18:46', null);
INSERT INTO `role_node` VALUES ('7', '1', '10', '2018-11-21 11:18:52', null);
INSERT INTO `role_node` VALUES ('8', '1', '11', '2018-11-21 11:30:52', null);
INSERT INTO `role_node` VALUES ('9', '1', '12', '2018-11-21 11:30:56', null);
INSERT INTO `role_node` VALUES ('10', '1', '13', '2018-11-21 11:31:01', null);
INSERT INTO `role_node` VALUES ('11', '1', '14', '2018-11-21 13:15:15', null);
INSERT INTO `role_node` VALUES ('12', '1', '15', '2018-11-21 13:15:19', null);
INSERT INTO `role_node` VALUES ('13', '1', '16', '2018-11-21 13:15:23', null);
INSERT INTO `role_node` VALUES ('14', '1', '17', '2018-11-21 13:57:29', null);
INSERT INTO `role_node` VALUES ('15', '1', '18', '2018-11-21 13:57:34', null);
INSERT INTO `role_node` VALUES ('17', '1', '20', '2018-11-21 15:05:50', null);
INSERT INTO `role_node` VALUES ('18', '1', '21', '2018-11-21 15:05:57', null);
INSERT INTO `role_node` VALUES ('19', '1', '22', '2018-11-21 15:06:03', null);
INSERT INTO `role_node` VALUES ('20', '1', '23', '2018-11-21 15:24:54', null);
INSERT INTO `role_node` VALUES ('21', '1', '24', '2018-11-21 17:07:14', null);
INSERT INTO `role_node` VALUES ('22', '1', '25', '2018-11-21 17:07:22', null);
INSERT INTO `role_node` VALUES ('23', '1', '26', '2018-11-21 17:07:28', null);
INSERT INTO `role_node` VALUES ('24', '1', '27', '2018-11-21 17:07:33', null);
INSERT INTO `role_node` VALUES ('26', '1', '19', '2018-12-03 16:44:33', null);
INSERT INTO `role_node` VALUES ('29', '1', '37', '2018-12-04 12:51:26', '2018-12-04 12:52:10');
INSERT INTO `role_node` VALUES ('34', '1', '35', '2018-12-18 19:54:02', null);
INSERT INTO `role_node` VALUES ('35', '1', '38', '2019-03-02 15:07:24', null);
INSERT INTO `role_node` VALUES ('41', '1', '44', '2019-03-04 11:10:21', null);
INSERT INTO `role_node` VALUES ('42', '1', '45', '2019-03-04 15:24:43', null);
INSERT INTO `role_node` VALUES ('74', '1', '48', '2019-09-10 15:25:32', null);
INSERT INTO `role_node` VALUES ('75', '1', '50', '2019-09-10 15:25:32', null);
INSERT INTO `role_node` VALUES ('76', '1', '49', '2019-09-10 15:25:32', null);
INSERT INTO `role_node` VALUES ('77', '1', '46', '2019-09-10 15:25:32', null);
INSERT INTO `role_node` VALUES ('78', '1', '47', '2019-09-10 15:25:32', null);
INSERT INTO `role_node` VALUES ('79', '1', '51', '2019-09-10 15:29:22', null);
INSERT INTO `role_node` VALUES ('80', '1', '55', '2019-09-10 15:38:13', null);
INSERT INTO `role_node` VALUES ('81', '1', '57', '2019-09-10 15:38:13', null);
INSERT INTO `role_node` VALUES ('82', '1', '53', '2019-09-10 15:38:13', null);
INSERT INTO `role_node` VALUES ('83', '1', '52', '2019-09-10 15:38:13', null);
INSERT INTO `role_node` VALUES ('84', '1', '54', '2019-09-10 15:38:13', null);
INSERT INTO `role_node` VALUES ('85', '1', '59', '2019-09-10 15:42:59', null);
INSERT INTO `role_node` VALUES ('86', '1', '62', '2019-09-10 15:42:59', null);
INSERT INTO `role_node` VALUES ('87', '1', '61', '2019-09-10 15:42:59', null);
INSERT INTO `role_node` VALUES ('88', '1', '58', '2019-09-10 15:42:59', null);
INSERT INTO `role_node` VALUES ('89', '1', '60', '2019-09-10 15:42:59', null);
INSERT INTO `role_node` VALUES ('90', '1', '63', '2019-09-10 15:43:57', null);
INSERT INTO `role_node` VALUES ('91', '1', '65', '2019-09-10 15:43:57', null);
INSERT INTO `role_node` VALUES ('92', '1', '66', '2019-09-10 15:43:57', null);
INSERT INTO `role_node` VALUES ('93', '1', '68', '2019-09-10 16:23:27', null);
INSERT INTO `role_node` VALUES ('94', '1', '70', '2019-09-10 16:23:27', null);
INSERT INTO `role_node` VALUES ('95', '1', '67', '2019-09-10 16:23:27', null);
INSERT INTO `role_node` VALUES ('96', '1', '72', '2019-09-10 16:23:27', null);
INSERT INTO `role_node` VALUES ('97', '1', '71', '2019-09-10 16:23:27', null);
INSERT INTO `role_node` VALUES ('98', '1', '69', '2019-09-10 16:23:27', null);
INSERT INTO `role_node` VALUES ('99', '1', '74', '2019-09-10 16:25:51', null);
INSERT INTO `role_node` VALUES ('100', '1', '76', '2019-09-10 16:25:51', null);
INSERT INTO `role_node` VALUES ('101', '1', '73', '2019-09-10 16:25:51', null);
INSERT INTO `role_node` VALUES ('102', '1', '75', '2019-09-10 16:25:51', null);
INSERT INTO `role_node` VALUES ('103', '1', '78', '2019-09-10 16:35:16', null);
INSERT INTO `role_node` VALUES ('104', '1', '81', '2019-09-10 16:35:16', null);
INSERT INTO `role_node` VALUES ('105', '1', '77', '2019-09-10 16:35:16', null);
INSERT INTO `role_node` VALUES ('106', '1', '80', '2019-09-10 16:35:16', null);
INSERT INTO `role_node` VALUES ('107', '1', '83', '2019-09-10 16:40:08', null);
INSERT INTO `role_node` VALUES ('108', '1', '85', '2019-09-10 16:40:08', null);
INSERT INTO `role_node` VALUES ('109', '1', '82', '2019-09-10 16:40:08', null);
INSERT INTO `role_node` VALUES ('110', '1', '84', '2019-09-10 16:40:08', null);
INSERT INTO `role_node` VALUES ('111', '1', '86', '2019-09-10 16:40:08', null);
INSERT INTO `role_node` VALUES ('112', '1', '87', '2019-09-10 16:42:12', null);
INSERT INTO `role_node` VALUES ('113', '1', '89', '2019-09-10 16:44:31', null);
INSERT INTO `role_node` VALUES ('114', '1', '91', '2019-09-10 16:44:31', null);
INSERT INTO `role_node` VALUES ('115', '1', '88', '2019-09-10 16:44:31', null);
INSERT INTO `role_node` VALUES ('116', '1', '90', '2019-09-10 16:44:31', null);
INSERT INTO `role_node` VALUES ('118', '1', '93', '2019-09-10 16:57:50', null);
INSERT INTO `role_node` VALUES ('119', '1', '95', '2019-09-10 16:57:50', null);
INSERT INTO `role_node` VALUES ('120', '1', '92', '2019-09-10 16:57:50', null);
INSERT INTO `role_node` VALUES ('121', '1', '97', '2019-09-10 16:57:50', null);
INSERT INTO `role_node` VALUES ('122', '1', '96', '2019-09-10 16:57:50', null);
INSERT INTO `role_node` VALUES ('123', '1', '94', '2019-09-10 16:57:50', null);
INSERT INTO `role_node` VALUES ('124', '1', '98', '2019-09-10 17:00:04', null);
INSERT INTO `role_node` VALUES ('125', '1', '101', '2019-09-10 17:00:04', null);
INSERT INTO `role_node` VALUES ('126', '1', '99', '2019-09-10 17:00:04', null);
INSERT INTO `role_node` VALUES ('127', '1', '100', '2019-09-10 17:00:04', null);
INSERT INTO `role_node` VALUES ('128', '1', '103', '2019-09-10 17:11:20', null);
INSERT INTO `role_node` VALUES ('129', '1', '104', '2019-09-10 17:12:40', null);

-- ----------------------------
-- Table structure for `savor`
-- ----------------------------
DROP TABLE IF EXISTS `savor`;
CREATE TABLE `savor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of savor
-- ----------------------------

-- ----------------------------
-- Table structure for `student`
-- ----------------------------
DROP TABLE IF EXISTS `student`;
CREATE TABLE `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `sex` varchar(255) DEFAULT NULL,
  `birthday` varchar(255) DEFAULT NULL,
  `interest` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `token` text,
  `update_time` varchar(255) DEFAULT NULL,
  `pass` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `add_time` varchar(255) DEFAULT NULL,
  `money` varchar(255) DEFAULT NULL,
  `is_vip` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of student
-- ----------------------------
INSERT INTO `student` VALUES ('1', '1', '李雨浓', '1', '19950125', '钢琴', '杭州', 'FwmD0tbLQrPOBe8fHTun8VpYIqg82XkFd2U52tuENGHnGbQaD358a6LwyEDbpC2TCf6t1SgmzjVS7wQcDqjJvkdvd6YsVneEUrgBDin99bO1trbWOfPK/+EgungSt2KK57MkfjjKthF2Vb5E+YBWkG10zJ29ZMQGNsFTJ1ccfq19NEhib8fjcSTaVbSttKIyMj77yo0f7gT3p8t/q4dsce6Zwj+GB8xSX51G0fpCWOY4ceC4GblZj1j+L8R95WmX/Zaq5pUR2/YY66m2Wh5iWDW6KIubYIpkOp72mi3CVMr+zbABaICzxCkH1fP4qYai3L9UStGMpJMMPV8NsbdF2g==', '1568187294', '96e79218965eb72c92a549dd5a330112', '361162048@qq.com', '', '1568168900', '', '');

-- ----------------------------
-- Table structure for `student_coupon`
-- ----------------------------
DROP TABLE IF EXISTS `student_coupon`;
CREATE TABLE `student_coupon` (
  `id` int(11) NOT NULL,
  `end_time` varchar(255) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `d_price` varchar(255) DEFAULT NULL,
  `discount` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `s_time` varchar(255) DEFAULT NULL,
  `student_id` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of student_coupon
-- ----------------------------
INSERT INTO `student_coupon` VALUES ('1', '1569052541', '20', '30', '70', '1', '1', '0', '', '1', '1568170725');

-- ----------------------------
-- Table structure for `student_message`
-- ----------------------------
DROP TABLE IF EXISTS `student_message`;
CREATE TABLE `student_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `list` varchar(255) DEFAULT NULL,
  `val` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `body` varchar(255) DEFAULT NULL,
  `data` varchar(255) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of student_message
-- ----------------------------

-- ----------------------------
-- Table structure for `teacher`
-- ----------------------------
DROP TABLE IF EXISTS `teacher`;
CREATE TABLE `teacher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` text,
  `update_time` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `star` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `pass` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `sex` varchar(255) DEFAULT NULL,
  `birthday` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `ABN` varchar(255) DEFAULT NULL,
  `culture` varchar(255) DEFAULT NULL,
  `card` varchar(255) DEFAULT NULL,
  `gz_s_time` varchar(255) DEFAULT NULL,
  `gz_d_time` varchar(255) DEFAULT NULL,
  `j_photo` varchar(255) DEFAULT NULL,
  `is_number` varchar(255) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `approve_photo1` varchar(255) DEFAULT NULL,
  `approve_photo2` varchar(255) DEFAULT NULL,
  `ziliao_type` int(11) DEFAULT NULL,
  `music` varchar(255) DEFAULT NULL,
  `music_name` varchar(255) DEFAULT NULL,
  `teacher_style1_photo` varchar(255) DEFAULT NULL,
  `teacher_style2_photo` varchar(255) DEFAULT NULL,
  `teacher_style3_photo` varchar(255) DEFAULT NULL,
  `teacher_style4_photo` varchar(255) DEFAULT NULL,
  `teacher_style5_photo` varchar(255) DEFAULT NULL,
  `teacher_style6_photo` varchar(255) DEFAULT NULL,
  `add_time` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `ziliao_photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of teacher
-- ----------------------------
INSERT INTO `teacher` VALUES ('2', 'gDKGha682vQqdRXGMCMx6TdSmZLX7NjR9r0QIC2Rw9L0Lz7kz7FgBychoT48rnn4kEvF5k5TITpUzkn6HoQhdXW5ErI1AUHegUCDbR+I/a7C1xcqoGlUIQoxeiz4bzjdITw2FUnWpm9lACOYNLKP11m6KJfqiWlZLgaHJbnq0T0J28Es6wxxRmkggB4Q41/w7DJrOSLHmU+bqCtc7GKKPJ1B5FiMIu7y3t86tRNnWaSibZ48Ni7/T5+xMvU751FT08ECJQgHfgwr1Fg1ahO2di+9ak9fzlboGQJ9SumIVZtIx8WKDYRASbCqwtquT2g/ikNDGvYbactuvn75RTg0mQ==', '1568187998', '李雨浓', null, null, null, '13654499009', 'e10adc3949ba59abbe56e057f20f883e', '18513329996', '0', '-475113600', '滚滚滚', 'v', 'v', '56678888', '1415750400', '1415750400', '', '0', '1=2', null, null, null, null, null, null, null, null, null, null, null, null, '1', null);

-- ----------------------------
-- Table structure for `teacher_message`
-- ----------------------------
DROP TABLE IF EXISTS `teacher_message`;
CREATE TABLE `teacher_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) DEFAULT NULL,
  `data` varchar(255) DEFAULT NULL,
  `body` varchar(255) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of teacher_message
-- ----------------------------

-- ----------------------------
-- Table structure for `teacher_order`
-- ----------------------------
DROP TABLE IF EXISTS `teacher_order`;
CREATE TABLE `teacher_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `punch` varchar(255) DEFAULT NULL,
  `notes_photo` varchar(255) DEFAULT NULL,
  `notes_video` varchar(255) DEFAULT NULL,
  `notes_content` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of teacher_order
-- ----------------------------

-- ----------------------------
-- Table structure for `trade`
-- ----------------------------
DROP TABLE IF EXISTS `trade`;
CREATE TABLE `trade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` varchar(255) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `student_id` varchar(255) DEFAULT NULL,
  `class_list_id` varchar(255) DEFAULT NULL,
  `teacher_id` varchar(255) DEFAULT NULL,
  `class_id` varchar(255) DEFAULT NULL,
  `people_num` varchar(255) DEFAULT NULL,
  `student_name` varchar(255) DEFAULT NULL,
  `class_name` varchar(255) DEFAULT NULL,
  `teacher_name` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of trade
-- ----------------------------

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `pass` varchar(255) DEFAULT NULL,
  `vip` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `sex` varchar(255) DEFAULT NULL,
  `birthday` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `interest` varchar(255) DEFAULT NULL,
  `is_vip` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of user
-- ----------------------------

-- ----------------------------
-- Table structure for `user_money_log`
-- ----------------------------
DROP TABLE IF EXISTS `user_money_log`;
CREATE TABLE `user_money_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` varchar(255) DEFAULT NULL,
  `money` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `coupon_id` int(11) DEFAULT NULL,
  `coupon_money` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of user_money_log
-- ----------------------------

-- ----------------------------
-- Table structure for `vip_music`
-- ----------------------------
DROP TABLE IF EXISTS `vip_music`;
CREATE TABLE `vip_music` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `music_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of vip_music
-- ----------------------------

-- ----------------------------
-- Table structure for `yaoqing`
-- ----------------------------
DROP TABLE IF EXISTS `yaoqing`;
CREATE TABLE `yaoqing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `people_num` varchar(255) DEFAULT NULL,
  `body` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of yaoqing
-- ----------------------------
