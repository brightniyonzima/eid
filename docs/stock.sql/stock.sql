-- MySQL dump 10.13  Distrib 5.7.8-rc, for Linux (x86_64)
--
-- Host: localhost    Database: crud
-- ------------------------------------------------------
-- Server version	5.7.8-rc

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
-- Table structure for table `commodities`
--

DROP TABLE IF EXISTS `commodities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commodities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `commodity_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category_id` smallint(6) NOT NULL,
  `tests_per_unit` smallint(6) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT '1925-01-01 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commodities`
--

LOCK TABLES `commodities` WRITE;
/*!40000 ALTER TABLE `commodities` DISABLE KEYS */;
INSERT INTO `commodities` VALUES (1,'HIV-1 Qual Test Kit(reagent kit)',3,48,'2015-12-14 17:14:45','2015-12-14 17:14:45'),(2,'SPEX Reagent',3,350,'2015-12-14 17:38:46','2015-12-14 17:38:46'),(3,'Consumable bundle',3,960,'2015-12-14 17:39:09','2015-12-14 17:39:09'),(4,'SPU',4,288,'2015-12-14 17:39:33','2015-12-14 17:39:33'),(5,'K-Tips',4,432,'2015-12-14 17:40:00','2015-12-14 17:40:00'),(6,'K-Tubes',4,1152,'2015-12-14 17:40:34','2015-12-14 17:40:34'),(7,'Wash buffer',4,96,'2015-12-14 17:40:54','2015-12-14 17:40:54'),(8,'Reagent tip',5,0,'2015-12-14 17:41:16','2015-12-14 17:41:16'),(9,'Halogen lamps',5,0,'2015-12-14 17:41:32','2015-12-14 17:41:32'),(10,'Seal-tip gripper (O-rings)',5,0,'2015-12-14 17:41:50','2015-12-14 17:41:50'),(11,'EID Dispatch Form',6,0,'2015-12-14 17:42:11','2015-12-14 17:42:11'),(12,'Infant Referral Forms',6,0,'2015-12-14 17:42:37','2015-12-14 17:42:37'),(13,'A3 Envelopes',6,100,'2015-12-14 17:42:55','2015-12-14 17:42:55');
/*!40000 ALTER TABLE `commodities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commodity_categories`
--

DROP TABLE IF EXISTS `commodity_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commodity_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT '1925-01-01 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commodity_categories`
--

LOCK TABLES `commodity_categories` WRITE;
/*!40000 ALTER TABLE `commodity_categories` DISABLE KEYS */;
INSERT INTO `commodity_categories` VALUES (3,'REAGENTS KITS','2015-12-14 17:04:42','2015-12-14 17:04:42'),(4,'TEST SPECIFIC CONSUMABLES','2015-12-14 17:04:55','2015-12-14 17:04:55'),(5,'MACHINE CONSUMABLE','2015-12-14 17:05:07','2015-12-14 17:05:07'),(6,'STATIONERY','2015-12-14 17:05:17','2015-12-14 17:05:17'),(7,'FORMS','2015-12-14 17:05:28','2015-12-14 17:05:28'),(8,'OTHERS','2015-12-14 17:05:37','2015-12-14 17:05:37');
/*!40000 ALTER TABLE `commodity_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `receivestocks`
--

DROP TABLE IF EXISTS `receivestocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `receivestocks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `commodity_id` int(10) unsigned NOT NULL,
  `qty_rcvd` mediumint(8) unsigned NOT NULL,
  `batch_number` varchar(32) DEFAULT NULL,
  `arrival_date` date NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT '1925-01-01 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receivestocks`
--

LOCK TABLES `receivestocks` WRITE;
/*!40000 ALTER TABLE `receivestocks` DISABLE KEYS */;
/*!40000 ALTER TABLE `receivestocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_adjustments`
--

DROP TABLE IF EXISTS `stock_adjustments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_adjustments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facility_id` int(10) unsigned DEFAULT NULL,
  `commodity_id` int(10) unsigned DEFAULT NULL,
  `adjustment_date` date DEFAULT NULL,
  `adjustment_type` enum('INCREASE','DECREASE') DEFAULT NULL,
  `change_in_quantity` mediumint(8) unsigned DEFAULT NULL,
  `remarks` varchar(256) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT '1925-01-01 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_adjustments`
--

LOCK TABLES `stock_adjustments` WRITE;
/*!40000 ALTER TABLE `stock_adjustments` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_adjustments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_requisition_headers`
--

DROP TABLE IF EXISTS `stock_requisition_headers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_requisition_headers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facility_id` int(11) NOT NULL,
  `requisition_date` date NOT NULL,
  `requisition_method` enum('AUTO_FORECAST','PHONE','EMAIL','DBS_COMMENTS','IN_PERSON','OTHER') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'OTHER',
  `requestors_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `requestors_phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `requestors_batch_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `date_approved` date DEFAULT NULL,
  `dispatched_by` int(11) DEFAULT NULL,
  `date_dispatched` date DEFAULT NULL,
  `receivers_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `receivers_phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_received` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT '1925-01-01 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_requisition_headers`
--

LOCK TABLES `stock_requisition_headers` WRITE;
/*!40000 ALTER TABLE `stock_requisition_headers` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_requisition_headers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_requisition_line_items`
--

DROP TABLE IF EXISTS `stock_requisition_line_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_requisition_line_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `requisition_header_id` int(11) NOT NULL,
  `commodity_id` int(11) NOT NULL,
  `quantity_requested` int(11) DEFAULT NULL,
  `quantity_forecasted` int(11) DEFAULT NULL,
  `quantity_approved` int(11) DEFAULT NULL,
  `approval_result` enum('PENDING_APPROVAL','APPROVED_REQUESTED_QTY','APPROVED_FORECAST_QTY','APPROVED_MORE_THAN_REQUESTED','APPROVED_LESS_THAN_REQUESTED','REJECTED') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'PENDING_APPROVAL',
  `approval_comment` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  `requisition_status` enum('PENDING_APPROVAL','STOCK_REQUEST_APPROVED','STOCK_DISPATCHED','STOCK_RECEIVED') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'PENDING_APPROVAL',
  `created_at` datetime NOT NULL DEFAULT '1925-01-01 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_requisition_line_items`
--

LOCK TABLES `stock_requisition_line_items` WRITE;
/*!40000 ALTER TABLE `stock_requisition_line_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_requisition_line_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_status`
--

DROP TABLE IF EXISTS `stock_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `facility_id` int(10) unsigned DEFAULT NULL,
  `commodity_id` int(10) unsigned DEFAULT NULL,
  `stock_changed_by` enum('STOCK_REQUISITION','STOCK_ADJUSTMENT') NOT NULL,
  `stock_change_details_id` int(10) unsigned NOT NULL,
  `is_most_recent_change` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `average_monthly_consumption` mediumint(9) NOT NULL DEFAULT '1',
  `alert_quantity` mediumint(9) NOT NULL DEFAULT '0',
  `initial_quantity` mediumint(9) NOT NULL DEFAULT '0',
  `restock_date` date NOT NULL,
  `restock_quantity` mediumint(9) NOT NULL DEFAULT '0',
  `total_stock_on_hand` mediumint(8) unsigned GENERATED ALWAYS AS (initial_quantity + restock_quantity) STORED,
  `forecasted_stockout_date` date GENERATED ALWAYS AS (if(average_monthly_consumption = 0, '2056-01-01'  , (restock_date + INTERVAL ((total_stock_on_hand / average_monthly_consumption) * 30) DAY))) STORED,
  `created_at` datetime NOT NULL DEFAULT '1925-01-01 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `facility_restockDate_commodity` (`facility_id`,`restock_date`,`commodity_id`),
  KEY `forecasted_stockout_date` (`forecasted_stockout_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_status`
--

LOCK TABLES `stock_status` WRITE;
/*!40000 ALTER TABLE `stock_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_status` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-03-19  8:56:53
