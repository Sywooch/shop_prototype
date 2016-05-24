-- MySQL dump 10.13  Distrib 5.5.49, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: shop
-- ------------------------------------------------------
-- Server version	5.5.49-0ubuntu0.14.04.1-log

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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`(20))
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Мужская обувь'),(2,'Мужская одежда');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `colors`
--

DROP TABLE IF EXISTS `colors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `colors` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `color` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colors`
--

LOCK TABLES `colors` WRITE;
/*!40000 ALTER TABLE `colors` DISABLE KEYS */;
INSERT INTO `colors` VALUES (1,'black'),(2,'white'),(3,'green');
/*!40000 ALTER TABLE `colors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(2000) NOT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `images` varchar(500) NOT NULL,
  `id_categories` tinyint(3) unsigned NOT NULL,
  `id_subcategory` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_subcategory` (`id_subcategory`),
  KEY `id_categories` (`id_categories`),
  CONSTRAINT `products_ibfk_2` FOREIGN KEY (`id_subcategory`) REFERENCES `subcategory` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `products_ibfk_3` FOREIGN KEY (`id_categories`) REFERENCES `categories` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'GH56tg','Ботинки Pradella','Ботинки Pradella выполнены из искусственной замши и текстиля. Детали: текстильная внутренняя отделка, стелька из натуральной кожи, контрастная шнуровка и отстрочка, гибкая резиновая подошва.',764.00,'images/',1,1),(2,'HJyu6','Ботинки Nine Lines','Ботинки выполнены из искусственной замши и текстиля. Детали: текстильная внутренняя отделка, стелька из натуральной кожи, контрастная шнуровка и отстрочка, гибкая резиновая подошва.',1870.00,'images/',1,1),(3,'JOI7','Ботинки Goorte','Ботинки выполнены из искусственной замши и текстиля. Детали: текстильная внутренняя отделка, стелька из натуральной кожи, контрастная шнуровка и отстрочка, гибкая резиновая подошва.',986.89,'images/',1,1),(4,'QWER','Ботинки Minuel Gorat','Ботинки выполнены из искусственной замши и текстиля. Детали: текстильная внутренняя отделка, стелька из натуральной кожи, контрастная шнуровка и отстрочка, гибкая резиновая подошва.',2568.00,'images/',1,1),(5,'PPoKL8','Кроссовки Adidas','Беговые кроссовки выполнены из сетчатого текстиля с полимерным покрытием. Детали: усиленный мыс, текстильная подкладка и стелька; технология Powerframe обеспечивает поддержку средней части стопы; система ускоренной шнуровки для надежной посадки по ноге; промежуточная подошва ZStrike гарантирует непревзойденную амортизацию.',658.00,'images/',1,2),(6,'NBGHT','Кроссовки Reebok','Беговые кроссовки выполнены из сетчатого текстиля с полимерным покрытием. Детали: усиленный мыс, текстильная подкладка и стелька; технология Powerframe обеспечивает поддержку средней части стопы; система ускоренной шнуровки для надежной посадки по ноге; промежуточная подошва ZStrike гарантирует непревзойденную амортизацию.',854.78,'images/',1,2),(7,'8EZsD','Кроссовки Montana','Беговые кроссовки выполнены из сетчатого текстиля с полимерным покрытием. Детали: усиленный мыс, текстильная подкладка и стелька; технология Powerframe обеспечивает поддержку средней части стопы; система ускоренной шнуровки для надежной посадки по ноге; промежуточная подошва ZStrike гарантирует непревзойденную амортизацию.',854.78,'images/',1,2),(8,'OIUj','Кроссовки Ergo','Беговые кроссовки выполнены из сетчатого текстиля с полимерным покрытием. Детали: усиленный мыс, текстильная подкладка и стелька; технология Powerframe обеспечивает поддержку средней части стопы; система ускоренной шнуровки для надежной посадки по ноге; промежуточная подошва ZStrike гарантирует непревзойденную амортизацию.',1632.00,'images/',1,2),(9,'JKiUty09-0','Брюки Zegna','Брюки зауженного кроя от Topman выполнены из тонкого хлопкового денима. Детали: застежка на пуговицы, шлевки под ремень, два боковых и два задних кармана..',895.00,'images/',2,3),(10,'HjTY-4','Брюки Orin','Брюки зауженного кроя от Topman выполнены из тонкого хлопкового денима. Детали: застежка на пуговицы, шлевки под ремень, два боковых и два задних кармана..',384.00,'images/',2,3),(11,'Opput-4','Брюки Orin','Брюки зауженного кроя от Topman выполнены из тонкого хлопкового денима. Детали: застежка на пуговицы, шлевки под ремень, два боковых и два задних кармана..',567.00,'images/',2,3),(12,'LkioJ','Брюки Orin','Брюки зауженного кроя от Topman выполнены из тонкого хлопкового денима. Детали: застежка на пуговицы, шлевки под ремень, два боковых и два задних кармана..',1398.00,'images/',2,3),(13,'HJyt','Пиджак Orin','Пиджак oodji приталенного силуэта выполнен из плотного мягкого хлопка. Детали: отложной воротник; контрастные светлые пуговицы; два внешних и два внутренних кармана; контрастная подкладка.',896.00,'images/',2,4),(14,'HJyt','Пиджак Manson','Пиджак oodji приталенного силуэта выполнен из плотного мягкого хлопка. Детали: отложной воротник; контрастные светлые пуговицы; два внешних и два внутренних кармана; контрастная подкладка.',562.00,'images/',2,4),(15,'HJyt','Пиджак Orin','Пиджак oodji приталенного силуэта выполнен из плотного мягкого хлопка. Детали: отложной воротник; контрастные светлые пуговицы; два внешних и два внутренних кармана; контрастная подкладка.',896.00,'images/',2,4),(16,'HJyt','Пиджак Orin','Пиджак oodji приталенного силуэта выполнен из плотного мягкого хлопка. Детали: отложной воротник; контрастные светлые пуговицы; два внешних и два внутренних кармана; контрастная подкладка.',896.00,'images/',2,4);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_colors`
--

DROP TABLE IF EXISTS `products_colors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products_colors` (
  `id_products` smallint(5) unsigned NOT NULL,
  `id_colors` tinyint(3) unsigned NOT NULL,
  UNIQUE KEY `product_color` (`id_products`,`id_colors`),
  KEY `id_colors` (`id_colors`),
  CONSTRAINT `products_colors_ibfk_1` FOREIGN KEY (`id_products`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `products_colors_ibfk_2` FOREIGN KEY (`id_colors`) REFERENCES `colors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products_colors`
--

LOCK TABLES `products_colors` WRITE;
/*!40000 ALTER TABLE `products_colors` DISABLE KEYS */;
INSERT INTO `products_colors` VALUES (1,1),(4,1),(7,1),(10,1),(13,1),(16,1),(2,2),(5,2),(8,2),(11,2),(14,2),(3,3),(6,3),(9,3),(12,3),(15,3);
/*!40000 ALTER TABLE `products_colors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_sizes`
--

DROP TABLE IF EXISTS `products_sizes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products_sizes` (
  `id_products` smallint(5) unsigned NOT NULL,
  `id_sizes` tinyint(3) unsigned NOT NULL,
  UNIQUE KEY `product_size` (`id_products`,`id_sizes`),
  KEY `id_sizes` (`id_sizes`),
  CONSTRAINT `products_sizes_ibfk_1` FOREIGN KEY (`id_products`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `products_sizes_ibfk_2` FOREIGN KEY (`id_sizes`) REFERENCES `sizes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products_sizes`
--

LOCK TABLES `products_sizes` WRITE;
/*!40000 ALTER TABLE `products_sizes` DISABLE KEYS */;
INSERT INTO `products_sizes` VALUES (1,1),(8,1),(16,1),(2,2),(9,2),(12,2),(10,3),(15,3),(4,4),(3,5),(5,5),(11,5),(6,6),(13,6),(7,7),(14,7);
/*!40000 ALTER TABLE `products_sizes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sizes`
--

DROP TABLE IF EXISTS `sizes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sizes` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `size` float(4,1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sizes`
--

LOCK TABLES `sizes` WRITE;
/*!40000 ALTER TABLE `sizes` DISABLE KEYS */;
INSERT INTO `sizes` VALUES (1,34.0),(2,35.0),(3,48.0),(4,50.0),(5,42.5),(6,45.0),(7,56.5);
/*!40000 ALTER TABLE `sizes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subcategory`
--

DROP TABLE IF EXISTS `subcategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subcategory` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `id_categories` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_categories` (`id_categories`),
  CONSTRAINT `subcategory_ibfk_1` FOREIGN KEY (`id_categories`) REFERENCES `categories` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subcategory`
--

LOCK TABLES `subcategory` WRITE;
/*!40000 ALTER TABLE `subcategory` DISABLE KEYS */;
INSERT INTO `subcategory` VALUES (1,'Ботинки',1),(2,'Кроссовки',1),(3,'Брюки',2),(4,'Пиджаки',2);
/*!40000 ALTER TABLE `subcategory` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-05-24 15:02:56
