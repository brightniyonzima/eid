DROP TABLE IF EXISTS `commodity_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commodity_categories` (
  `id` mediumint(6) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(200) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY uniqueIndex (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `commodities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commodities` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `commodity` varchar(200) NOT NULL,
  `categoryID` mediumint(8) unsigned NOT NULL,
  `initial_quantity` int(10) unsigned,
  `alert_quantity` int(10) unsigned,
  `correlates_to_tests` tinyint(1) unsigned,
  `tests_per_unit` int(10) unsigned,
  `created` datetime NOT NULL,
  `createdby` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY uniqueIndex (`commodity`),
  KEY categoryIDIndex (`categoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `commodity_stockin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commodity_stockin` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `commodityID` mediumint(8) unsigned NOT NULL,
  `quantity` int(10) unsigned,
  `arrival_date` date,
  `expiry_date` date,
  `batchno` varchar(250),
  `created` datetime NOT NULL,
  `createdby` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY commodityIDIndex (`commodityID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `commodity_requisitions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commodity_requisitions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `commodityID` mediumint(8) unsigned NOT NULL,
  `requisition_number` int(10) unsigned,
  `quantity_requisitioned` int(10) unsigned,
  `approved` tinyint(1) unsigned,
  `created` datetime NOT NULL,
  `createdby` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY commodityIDIndex (`commodityID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `commodity_req_approvals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commodity_req_approvals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `requisitionID` bigint(20) unsigned NOT NULL,
  `quantity_approved` int(10) unsigned,
  `comments` varchar(250) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY requisitionIDIndex (`requisitionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `commodity_facility_req_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commodity_facility_req_methods` (
  `id` mediumint(6) unsigned NOT NULL AUTO_INCREMENT,
  `method` varchar(200) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY uniqueIndex (`method`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `commodity_facility_requisitions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commodity_facility_requisitions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `commodityID` mediumint(8) unsigned NOT NULL,
  `facilityID` int(10) unsigned,
  `quantity_requisitioned` int(10) unsigned,
  `requisition_date` datetime NOT NULL,
  `req_methodID` mediumint(6) unsigned NOT NULL, 
  `approved` tinyint(1) unsigned,
  `created` datetime NOT NULL,
  `createdby` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY commodityIDIndex (`commodityID`),
  KEY facilityIDIndex (`facilityID`),
  KEY req_methodID (`req_methodID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `commodity_facility_req_approvals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commodity_facility_req_approvals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `requisitionID` bigint(20) unsigned NOT NULL,
  `quantity_approved` int(10) unsigned,
  `comments` varchar(250) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY requisitionIDIndex (`requisitionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `commodity_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commodity_config` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item` varchar(50) NOT NULL,
  `value` varchar(250) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY uniqueIndex (`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;