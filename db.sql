-- MySQL dump 10.13  Distrib 5.7.23, for Win64 (x86_64)
--
-- Host: localhost    Database: admin
-- ------------------------------------------------------
-- Server version	5.7.23

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin','admin','96e79218965eb72c92a549dd5a330112','17316948813','/uploads/20181117/153f01a03e6a7631e2d524575d4ccd12.png','2018-10-23 15:17:03','2018-11-17 11:09:46','<p>&nbsp;</p><p>哈哈哈哈哈哈</p><p>&nbsp;</p><blockquote><p>to be or not to be, this is a question.</p></blockquote>',NULL);
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_node`
--

DROP TABLE IF EXISTS `admin_node`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `nid` int(11) NOT NULL,
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_node`
--

LOCK TABLES `admin_node` WRITE;
/*!40000 ALTER TABLE `admin_node` DISABLE KEYS */;
INSERT INTO `admin_node` VALUES (1,1,4,'2018-11-16 15:47:28',NULL),(2,1,1,'2018-11-19 14:16:00','2018-11-19 14:16:47'),(3,1,28,'2018-11-22 12:32:04',NULL),(4,1,29,'2018-11-22 12:46:54',NULL),(5,1,35,'2018-12-18 19:50:18',NULL);
/*!40000 ALTER TABLE `admin_node` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_role`
--

DROP TABLE IF EXISTS `admin_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_role`
--

LOCK TABLES `admin_role` WRITE;
/*!40000 ALTER TABLE `admin_role` DISABLE KEYS */;
INSERT INTO `admin_role` VALUES (1,1,1,'2018-10-23 19:02:03',NULL),(2,2,2,'2018-10-24 19:03:24',NULL),(3,3,2,'2018-10-24 19:03:30',NULL),(4,4,2,'2018-10-24 19:03:35',NULL),(5,5,2,'2018-10-24 19:03:39','2018-12-07 12:10:38'),(6,26,1,'2018-12-07 12:12:42',NULL);
/*!40000 ALTER TABLE `admin_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hello`
--

DROP TABLE IF EXISTS `hello`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hello` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(64) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hello`
--

LOCK TABLES `hello` WRITE;
/*!40000 ALTER TABLE `hello` DISABLE KEYS */;
INSERT INTO `hello` VALUES (1,'123','1234','2018-12-19 16:17:23'),(2,'12tyaf13123','002','2019-03-01 00:00:00');
/*!40000 ALTER TABLE `hello` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='菜单';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (11,1,NULL,'首页',100,'layui-icon layui-icon-home','2018-11-16 15:51:56','2019-01-23 12:36:41'),(13,2,NULL,'管理员',50,'layui-icon layui-icon-group','2018-11-16 15:52:55','2019-03-01 17:39:44'),(17,NULL,NULL,'权限和菜单',50,'layui-icon layui-icon-set-fill	','2018-11-19 17:36:53','2019-01-23 12:37:01'),(18,8,17,'角色管理',50,'layui-icon layui-icon-group','2018-11-19 17:38:05','2019-03-01 17:36:06'),(19,NULL,17,'权限管理',50,'layui-icon layui-icon-app','2018-11-19 17:38:21','2019-03-01 17:37:05'),(20,24,17,'菜单管理',50,'layui-icon layui-icon-tabs','2018-11-19 17:38:31','2019-03-01 17:37:44'),(24,14,19,'权限管理',50,'layui-icon layui-icon-app','2018-11-21 11:05:10','2019-03-01 17:37:58'),(25,20,19,'用户权限管理',50,'layui-icon-username layui-icon','2018-11-21 11:05:24','2019-03-01 17:38:42'),(26,17,19,'角色权限管理',50,'layui-icon-user layui-icon','2018-11-21 11:05:42','2019-03-01 17:39:08'),(28,34,NULL,'代码生成',50,'layui-icon layui-icon-fonts-code','2018-12-01 12:50:17','2019-01-23 12:37:12'),(29,39,NULL,'测试模块',50,'','2019-03-02 18:11:43',NULL);
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `node`
--

DROP TABLE IF EXISTS `node`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='节点';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `node`
--

LOCK TABLES `node` WRITE;
/*!40000 ALTER TABLE `node` DISABLE KEYS */;
INSERT INTO `node` VALUES (1,'admin','index','index',NULL,'2018-10-19 18:27:23','2018-10-22 11:01:49'),(2,'admin','user','index',NULL,'2018-10-22 11:01:49',NULL),(3,'admin','user','update',NULL,'2018-10-22 11:01:49',NULL),(7,'admin','user','add',NULL,'2018-11-16 18:29:27',NULL),(8,'admin','role','index',NULL,'2018-11-21 11:17:33',NULL),(9,'admin','role','update',NULL,'2018-11-21 11:17:41',NULL),(10,'admin','role','add',NULL,'2018-11-21 11:17:50',NULL),(14,'admin','node','index',NULL,'2018-11-21 13:14:40',NULL),(15,'admin','node','add',NULL,'2018-11-21 13:14:46',NULL),(16,'admin','node','update',NULL,'2018-11-21 13:14:53',NULL),(17,'admin','role_node','index',NULL,'2018-11-21 13:31:30',NULL),(18,'admin','role_node','add',NULL,'2018-11-21 13:31:42',NULL),(19,'admin','role_node','update',NULL,'2018-11-21 13:31:58',NULL),(20,'admin','user_node','index',NULL,'2018-11-21 13:33:24',NULL),(21,'admin','user_node','add',NULL,'2018-11-21 13:33:40',NULL),(22,'admin','user_node','update',NULL,'2018-11-21 13:33:54',NULL),(23,'admin','node','delete',NULL,'2018-11-21 15:24:35',NULL),(24,'admin','menu','index',NULL,'2018-11-21 16:54:24',NULL),(25,'admin','menu','add',NULL,'2018-11-21 16:54:38',NULL),(26,'admin','menu','update',NULL,'2018-11-21 16:54:48',NULL),(27,'admin','menu','delete',NULL,'2018-11-21 16:54:57',NULL),(29,'admin','user','upload',NULL,'2018-11-22 12:46:35',NULL),(34,'admin','generate','index',NULL,'2018-12-01 12:49:21',NULL),(35,'admin','game','index',NULL,'2018-12-18 19:06:14',NULL),(37,'admin','role_node','delete',NULL,'2018-12-18 19:53:28',NULL),(38,'admin','user','export',NULL,'2019-03-02 15:07:10',NULL),(44,'admin','user','delete',NULL,'2019-03-04 11:09:59',NULL),(45,'admin','menu','export',NULL,'2019-03-04 15:24:31',NULL);
/*!40000 ALTER TABLE `node` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_bin NOT NULL COMMENT ' ',
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'管理员','2018-10-23 19:02:13','2018-10-24 19:00:59'),(2,'普通用户','2018-10-24 19:00:34','2018-10-24 19:00:53'),(3,'测试角色01','2018-11-21 13:09:28',NULL);
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_node`
--

DROP TABLE IF EXISTS `role_node`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `nid` int(11) NOT NULL,
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rid_nid` (`rid`,`nid`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_node`
--

LOCK TABLES `role_node` WRITE;
/*!40000 ALTER TABLE `role_node` DISABLE KEYS */;
INSERT INTO `role_node` VALUES (1,1,1,'2018-11-16 15:46:41',NULL),(2,1,2,'2018-11-16 15:46:47','2018-11-19 17:50:39'),(3,1,3,'2018-11-16 15:47:05',NULL),(4,1,7,'2018-11-16 18:31:29','2018-11-19 17:50:37'),(5,1,8,'2018-11-21 11:18:41',NULL),(6,1,9,'2018-11-21 11:18:46',NULL),(7,1,10,'2018-11-21 11:18:52',NULL),(8,1,11,'2018-11-21 11:30:52',NULL),(9,1,12,'2018-11-21 11:30:56',NULL),(10,1,13,'2018-11-21 11:31:01',NULL),(11,1,14,'2018-11-21 13:15:15',NULL),(12,1,15,'2018-11-21 13:15:19',NULL),(13,1,16,'2018-11-21 13:15:23',NULL),(14,1,17,'2018-11-21 13:57:29',NULL),(15,1,18,'2018-11-21 13:57:34',NULL),(17,1,20,'2018-11-21 15:05:50',NULL),(18,1,21,'2018-11-21 15:05:57',NULL),(19,1,22,'2018-11-21 15:06:03',NULL),(20,1,23,'2018-11-21 15:24:54',NULL),(21,1,24,'2018-11-21 17:07:14',NULL),(22,1,25,'2018-11-21 17:07:22',NULL),(23,1,26,'2018-11-21 17:07:28',NULL),(24,1,27,'2018-11-21 17:07:33',NULL),(25,1,34,'2018-12-01 12:49:33',NULL),(26,1,19,'2018-12-03 16:44:33',NULL),(29,1,37,'2018-12-04 12:51:26','2018-12-04 12:52:10'),(34,1,35,'2018-12-18 19:54:02',NULL),(35,1,38,'2019-03-02 15:07:24',NULL),(41,1,44,'2019-03-04 11:10:21',NULL),(42,1,45,'2019-03-04 15:24:43',NULL);
/*!40000 ALTER TABLE `role_node` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-04 15:31:18
