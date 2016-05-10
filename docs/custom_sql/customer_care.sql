DROP TABLE IF EXISTS `cc_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_categories` (
  `id` mediumint(6) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(200) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY uniqueIndex (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cc_complaints`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_complaints` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `categoryID` mediumint(8) unsigned NOT NULL,
  `complaint` text NOT NULL,
  `status` int(1) unsigned,
  `resolved` int(1) unsigned,
  `facilityID` int(10) unsigned,
  `complainant` varchar(100) NOT NULL,
  `complainant_telephone` varchar(30),
  `complainant_email` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY categoryIDIndex (`categoryID`),
  KEY facilityIDIndex (`facilityID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cc_responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cc_responses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `complaintID` int(10) unsigned NOT NULL,
  `response` text NOT NULL,
  `created` datetime NOT NULL,
  `createdby` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY complaintIDIndex (`complaintID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


