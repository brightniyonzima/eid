DROP TABLE IF EXISTS `user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_roles` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(200) NOT NULL,
  `permissions` text NOT NULL,
  `permission_parents` text NOT NULL,
  `created` datetime NOT NULL,
  `createdby` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;