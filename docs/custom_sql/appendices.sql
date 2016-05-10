DROP TABLE IF EXISTS `appendix_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appendix_categories` (
  `id` mediumint(6) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(200) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY uniqueIndex (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `appendices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appendices` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `appendix` varchar(200) NOT NULL,
  `categoryID` mediumint(6) unsigned NOT NULL,
  `inactive` TINYINT(1) NOT NULL DEFAULT  '0',
  `created` datetime NOT NULL,
  `createdby` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY categoryIDIndex (`categoryID`),
  UNIQUE KEY uniqueCategoryAppendixIndex (`categoryID`,`appendix`),
  UNIQUE KEY uniqueCategoryCodeIndex (`categoryID`,`code`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;