-- MySQL dump 10.13  Distrib 5.5.41, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: keeper
-- ------------------------------------------------------
-- Server version	5.5.41-0ubuntu0.14.04.1

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
-- Table structure for table `Category`
--

DROP TABLE IF EXISTS `Category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Category`
--

LOCK TABLES `Category` WRITE;
/*!40000 ALTER TABLE `Category` DISABLE KEYS */;
INSERT INTO `Category` VALUES (1,1,'Без категории'),(2,1,'Авто'),(3,1,'Ком.услуги'),(4,1,'Питание');
/*!40000 ALTER TABLE `Category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `DictionaryExpenseName`
--

DROP TABLE IF EXISTS `DictionaryExpenseName`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DictionaryExpenseName` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userIdValue` (`userId`,`name`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DictionaryExpenseName`
--

LOCK TABLES `DictionaryExpenseName` WRITE;
/*!40000 ALTER TABLE `DictionaryExpenseName` DISABLE KEYS */;
INSERT INTO `DictionaryExpenseName` VALUES (7,1,'альбуцид'),(6,1,'анализы'),(8,1,'бензин'),(24,1,'булочка'),(14,1,'валентинка'),(22,1,'врач'),(31,1,'гараж'),(2,1,'дорога'),(33,1,'ДР Дена'),(36,1,'ДР Саша'),(25,1,'киевстар'),(9,1,'колготки'),(12,1,'колготы'),(5,1,'копейка'),(26,1,'кофе'),(27,1,'лампочки'),(23,1,'лекарство'),(1,1,'лор'),(19,1,'масло'),(29,1,'метро'),(30,1,'мойка'),(17,1,'музкомедия'),(3,1,'обед'),(34,1,'окорочка'),(18,1,'парикмахерская'),(20,1,'пепси'),(21,1,'сиги'),(16,1,'суши'),(10,1,'таблетки от горла'),(15,1,'таврия'),(35,1,'тортик'),(32,1,'ученики'),(13,1,'уш. палочки'),(37,1,'фоззи'),(11,1,'чайник'),(4,1,'школа'),(28,1,'электрика');
/*!40000 ALTER TABLE `DictionaryExpenseName` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Expense`
--

DROP TABLE IF EXISTS `Expense`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Expense` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  `ownerId` int(11) NOT NULL,
  `dictionaryExpenseNameId` int(11) NOT NULL,
  `dateAdd` date NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `categoryId` (`categoryId`),
  KEY `ownerId` (`ownerId`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Expense`
--

LOCK TABLES `Expense` WRITE;
/*!40000 ALTER TABLE `Expense` DISABLE KEYS */;
INSERT INTO `Expense` VALUES (1,1,1,2,1,'2014-02-11',170),(2,1,1,2,2,'2014-02-11',3),(3,1,1,1,3,'2014-02-11',27),(5,1,4,3,4,'2014-02-12',14),(6,1,4,2,5,'2014-02-12',108),(8,1,1,2,2,'2014-02-12',2),(9,1,1,2,6,'2014-02-12',10),(10,1,1,2,7,'2014-02-12',7),(11,1,4,1,3,'2014-02-12',25),(12,1,4,1,5,'2014-02-12',75),(13,1,2,1,8,'2014-02-12',200),(14,1,1,2,9,'2014-02-13',32),(15,1,1,2,10,'2014-02-13',15),(16,1,1,2,11,'2014-02-13',90),(17,1,1,4,12,'2014-02-13',25),(18,1,1,3,4,'2014-02-13',10),(19,1,1,2,13,'2014-02-13',3),(20,1,1,2,2,'2014-02-13',3),(21,1,1,2,14,'2014-02-13',1),(22,1,4,1,3,'2014-02-13',25),(23,1,1,2,6,'2014-02-14',200),(24,1,4,2,5,'2014-02-14',38),(25,1,4,1,5,'2014-02-14',180),(26,1,4,1,3,'2014-02-14',25),(27,1,4,1,15,'2014-02-15',77),(28,1,4,1,16,'2014-02-15',168),(29,1,1,1,17,'2014-02-15',100),(30,1,4,2,5,'2014-02-16',90),(31,1,1,1,18,'2014-02-16',60),(32,1,2,1,19,'2014-02-16',645),(33,1,4,1,20,'2014-02-16',13),(34,1,1,2,2,'2014-02-17',3),(35,1,4,1,3,'2014-02-17',26),(36,1,2,1,8,'2014-02-17',200),(37,1,1,2,2,'2014-02-18',2),(38,1,4,1,3,'2014-02-18',22),(39,1,1,1,21,'2014-02-18',104),(40,1,1,2,22,'2014-02-19',150),(41,1,1,2,23,'2014-02-19',52),(42,1,4,1,3,'2014-02-19',33),(43,1,4,3,24,'2014-02-20',5),(44,1,4,2,5,'2014-02-20',117),(45,1,1,2,2,'2014-02-20',5),(46,1,1,2,23,'2014-02-20',20),(47,1,1,2,2,'2014-02-21',5),(48,1,1,2,6,'2014-02-21',20),(49,1,4,1,5,'2014-02-21',153),(50,1,1,1,25,'2014-02-21',50),(51,1,4,2,5,'2014-02-22',20),(52,1,4,2,26,'2014-02-22',12),(53,1,2,1,27,'2014-02-22',35),(54,1,2,1,28,'2014-02-22',300),(55,1,4,1,29,'2014-02-22',440),(56,1,4,1,5,'2014-02-23',18),(57,1,2,1,30,'2014-02-23',70),(58,1,2,1,31,'2014-02-24',350),(59,1,4,1,3,'2014-02-24',28),(60,1,1,2,18,'2014-02-24',50),(61,1,1,2,2,'2014-02-24',5),(62,1,1,2,2,'2014-02-25',8),(63,1,1,3,32,'2014-02-25',270),(64,1,4,1,3,'2014-02-25',20),(65,1,1,1,33,'2014-02-25',50),(66,1,1,2,18,'2014-02-26',35),(67,1,4,2,34,'2014-02-26',30),(68,1,1,2,2,'2014-02-26',5),(69,1,4,1,3,'2014-02-26',24),(70,1,4,1,35,'2014-02-26',25),(71,1,4,2,5,'2014-02-27',17),(72,1,1,2,2,'2014-02-27',3),(73,1,1,1,36,'2014-02-27',50),(74,1,4,1,3,'2014-02-27',20),(75,1,2,1,8,'2014-02-27',150),(76,1,4,1,3,'2014-02-28',24),(77,1,4,1,37,'2014-02-28',105),(78,1,4,1,5,'2014-02-28',72);
/*!40000 ALTER TABLE `Expense` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Owner`
--

DROP TABLE IF EXISTS `Owner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Owner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Owner`
--

LOCK TABLES `Owner` WRITE;
/*!40000 ALTER TABLE `Owner` DISABLE KEYS */;
INSERT INTO `Owner` VALUES (1,1,'Паша'),(2,1,'Ксюша'),(3,1,'Дима'),(4,1,'Макс');
/*!40000 ALTER TABLE `Owner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `hash` varchar(25) NOT NULL,
  `expire` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`),
  UNIQUE KEY `loginPassword` (`login`,`password`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User`
--

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
INSERT INTO `User` VALUES (1,'odmin','odmin','b7081hbjn1w8ossk8gsoccow4',1425930637);
/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-03-09 21:40:54
