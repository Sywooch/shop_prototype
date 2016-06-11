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
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `brand` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brand` (`brand`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES (1,'Adidas'),(11,'Ergo'),(4,'Goorte'),(13,'Gosha'),(3,'Manson'),(8,'Minuel Gorat'),(2,'Montana'),(7,'Nine Lines'),(6,'Orin'),(12,'Pradella'),(10,'Reebok'),(5,'Zegna');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `seocode` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`(20))
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Мужская обувь','mensfootwear'),(2,'Мужская одежда','menswear');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colors`
--

LOCK TABLES `colors` WRITE;
/*!40000 ALTER TABLE `colors` DISABLE KEYS */;
INSERT INTO `colors` VALUES (1,'black'),(2,'white'),(3,'green'),(4,'red');
/*!40000 ALTER TABLE `colors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `text` varchar(2000) NOT NULL,
  `name` varchar(255) NOT NULL,
  `id_emails` smallint(5) unsigned NOT NULL,
  `id_products` smallint(5) unsigned NOT NULL,
  `active` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_emails` (`id_emails`),
  KEY `id_products` (`id_products`),
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`id_products`) REFERENCES `products` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`id_emails`) REFERENCES `emails` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency`
--

DROP TABLE IF EXISTS `currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `currency` char(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency`
--

LOCK TABLES `currency` WRITE;
/*!40000 ALTER TABLE `currency` DISABLE KEYS */;
INSERT INTO `currency` VALUES (1,'USD'),(2,'EUR'),(3,'UAH');
/*!40000 ALTER TABLE `currency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emails`
--

DROP TABLE IF EXISTS `emails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emails` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emails`
--

LOCK TABLES `emails` WRITE;
/*!40000 ALTER TABLE `emails` DISABLE KEYS */;
INSERT INTO `emails` VALUES (1,'superadmin@tsalmin.com');
/*!40000 ALTER TABLE `emails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `date` int(10) unsigned NOT NULL,
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
INSERT INTO `products` VALUES (1,1462453595,'GH56tg','Ботинки Pradella','Ботинки Pradella выполнены из искусственной замши и текстиля. Детали: текстильная внутренняя отделка, стелька из натуральной кожи, контрастная шнуровка и отстрочка, гибкая резиновая подошва.',764.00,'images/',1,1),(2,1464453595,'HJyu6','Ботинки Nine Lines','Ботинки выполнены из искусственной замши и текстиля. Детали: текстильная внутренняя отделка, стелька из натуральной кожи, контрастная шнуровка и отстрочка, гибкая резиновая подошва.',1870.00,'images/',1,1),(3,1464453705,'JOI7','Ботинки Goorte','Ботинки выполнены из искусственной замши и текстиля. Детали: текстильная внутренняя отделка, стелька из натуральной кожи, контрастная шнуровка и отстрочка, гибкая резиновая подошва.',986.89,'images/',1,1),(4,1464453595,'QWER','Ботинки Minuel Gorat','Ботинки выполнены из искусственной замши и текстиля. Детали: текстильная внутренняя отделка, стелька из натуральной кожи, контрастная шнуровка и отстрочка, гибкая резиновая подошва.',2568.00,'images/',1,1),(5,1464453705,'PPoKL8','Кроссовки Adidas','Беговые кроссовки выполнены из сетчатого текстиля с полимерным покрытием. Детали: усиленный мыс, текстильная подкладка и стелька; технология Powerframe обеспечивает поддержку средней части стопы; система ускоренной шнуровки для надежной посадки по ноге; промежуточная подошва ZStrike гарантирует непревзойденную амортизацию.',658.00,'images/',1,2),(6,1464453595,'NBGHT','Кроссовки Reebok','Беговые кроссовки выполнены из сетчатого текстиля с полимерным покрытием. Детали: усиленный мыс, текстильная подкладка и стелька; технология Powerframe обеспечивает поддержку средней части стопы; система ускоренной шнуровки для надежной посадки по ноге; промежуточная подошва ZStrike гарантирует непревзойденную амортизацию.',854.78,'images/',1,2),(7,1464453705,'8EZsD','Кроссовки Montana','Беговые кроссовки выполнены из сетчатого текстиля с полимерным покрытием. Детали: усиленный мыс, текстильная подкладка и стелька; технология Powerframe обеспечивает поддержку средней части стопы; система ускоренной шнуровки для надежной посадки по ноге; промежуточная подошва ZStrike гарантирует непревзойденную амортизацию.',854.78,'images/',1,2),(8,1464453595,'OIUj','Кроссовки Ergo','Беговые кроссовки выполнены из сетчатого текстиля с полимерным покрытием. Детали: усиленный мыс, текстильная подкладка и стелька; технология Powerframe обеспечивает поддержку средней части стопы; система ускоренной шнуровки для надежной посадки по ноге; промежуточная подошва ZStrike гарантирует непревзойденную амортизацию.',1632.00,'images/',1,2),(9,1464453595,'JKiUty09-0','Брюки Zegna','Брюки зауженного кроя от Topman выполнены из тонкого хлопкового денима. Детали: застежка на пуговицы, шлевки под ремень, два боковых и два задних кармана..',895.00,'images/',2,3),(10,1464453595,'HjTY-4','Брюки Orin','Брюки зауженного кроя от Topman выполнены из тонкого хлопкового денима. Детали: застежка на пуговицы, шлевки под ремень, два боковых и два задних кармана..',384.00,'images/',2,3),(11,1464453595,'Opput-4','Брюки Orin','Брюки зауженного кроя от Topman выполнены из тонкого хлопкового денима. Детали: застежка на пуговицы, шлевки под ремень, два боковых и два задних кармана..',567.00,'images/',2,3),(12,1464453595,'LkioJ','Брюки Orin','Брюки зауженного кроя от Topman выполнены из тонкого хлопкового денима. Детали: застежка на пуговицы, шлевки под ремень, два боковых и два задних кармана..',1398.00,'images/',2,3),(13,1464453595,'HJyt','Пиджак Orin','Пиджак oodji приталенного силуэта выполнен из плотного мягкого хлопка. Детали: отложной воротник; контрастные светлые пуговицы; два внешних и два внутренних кармана; контрастная подкладка.',896.00,'images/',2,4),(14,1464453705,'HJyt','Пиджак Manson','Пиджак oodji приталенного силуэта выполнен из плотного мягкого хлопка. Детали: отложной воротник; контрастные светлые пуговицы; два внешних и два внутренних кармана; контрастная подкладка.',562.00,'images/',2,4),(15,1464453595,'HJyt','Пиджак Orin','Пиджак oodji приталенного силуэта выполнен из плотного мягкого хлопка. Детали: отложной воротник; контрастные светлые пуговицы; два внешних и два внутренних кармана; контрастная подкладка.',896.00,'images/',2,4),(16,1464453595,'HJyt','Пиджак Orin','Пиджак oodji приталенного силуэта выполнен из плотного мягкого хлопка. Детали: отложной воротник; контрастные светлые пуговицы; два внешних и два внутренних кармана; контрастная подкладка.',896.00,'images/',2,4);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_brands`
--

DROP TABLE IF EXISTS `products_brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products_brands` (
  `id_products` smallint(5) unsigned NOT NULL,
  `id_brands` tinyint(3) unsigned NOT NULL,
  UNIQUE KEY `id_products_id_brands` (`id_products`,`id_brands`),
  KEY `id_brands` (`id_brands`),
  CONSTRAINT `products_brands_ibfk_1` FOREIGN KEY (`id_products`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `products_brands_ibfk_2` FOREIGN KEY (`id_brands`) REFERENCES `brands` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products_brands`
--

LOCK TABLES `products_brands` WRITE;
/*!40000 ALTER TABLE `products_brands` DISABLE KEYS */;
INSERT INTO `products_brands` VALUES (5,1),(7,2),(14,3),(3,4),(9,5),(10,6),(11,6),(12,6),(13,6),(15,6),(16,6),(2,7),(4,8),(6,10),(8,11),(1,12);
/*!40000 ALTER TABLE `products_brands` ENABLE KEYS */;
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
INSERT INTO `products_colors` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(1,2),(2,2),(4,2),(5,2),(7,2),(8,2),(10,2),(11,2),(13,2),(14,2),(16,2),(3,3),(6,3),(9,3),(12,3),(15,3);
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
INSERT INTO `products_sizes` VALUES (1,1),(2,1),(8,1),(16,1),(1,2),(2,2),(8,2),(9,2),(12,2),(16,2),(1,3),(8,3),(10,3),(15,3),(16,3),(1,4),(4,4),(8,4),(16,4),(1,5),(2,5),(3,5),(5,5),(11,5),(2,6),(6,6),(13,6),(2,7),(7,7),(14,7);
/*!40000 ALTER TABLE `products_sizes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `related_products`
--

DROP TABLE IF EXISTS `related_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `related_products` (
  `id_products` smallint(5) unsigned NOT NULL,
  `id_related_products` smallint(5) unsigned NOT NULL,
  UNIQUE KEY `two_colls` (`id_products`,`id_related_products`),
  KEY `id_related_products` (`id_related_products`),
  CONSTRAINT `related_products_ibfk_1` FOREIGN KEY (`id_products`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `related_products_ibfk_2` FOREIGN KEY (`id_related_products`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `related_products`
--

LOCK TABLES `related_products` WRITE;
/*!40000 ALTER TABLE `related_products` DISABLE KEYS */;
INSERT INTO `related_products` VALUES (15,1),(15,12),(2,15);
/*!40000 ALTER TABLE `related_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rules`
--

DROP TABLE IF EXISTS `rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rules` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `rule` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rules`
--

LOCK TABLES `rules` WRITE;
/*!40000 ALTER TABLE `rules` DISABLE KEYS */;
INSERT INTO `rules` VALUES (1,'catalog view'),(2,'change products'),(3,'add products'),(4,'change personal info'),(5,'assign rule');
/*!40000 ALTER TABLE `rules` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sizes`
--

LOCK TABLES `sizes` WRITE;
/*!40000 ALTER TABLE `sizes` DISABLE KEYS */;
INSERT INTO `sizes` VALUES (1,34.0),(2,35.0),(3,48.0),(4,50.0),(5,42.5),(6,45.0),(7,56.5),(8,60.0);
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
  `seocode` varchar(255) NOT NULL,
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
INSERT INTO `subcategory` VALUES (1,'Ботинки','boots',1),(2,'Кроссовки','snickers',1),(3,'Брюки','pants',2),(4,'Пиджаки','coats',2);
/*!40000 ALTER TABLE `subcategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_rules`
--

DROP TABLE IF EXISTS `users_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_rules` (
  `id_users` smallint(5) unsigned NOT NULL,
  `id_rules` tinyint(3) unsigned NOT NULL,
  UNIQUE KEY `id_users_id_rules` (`id_users`,`id_rules`),
  KEY `id_rules` (`id_rules`),
  CONSTRAINT `users_rules_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_rules_ibfk_2` FOREIGN KEY (`id_rules`) REFERENCES `rules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_rules`
--

LOCK TABLES `users_rules` WRITE;
/*!40000 ALTER TABLE `users_rules` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_rules` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-06-11 17:53:10
