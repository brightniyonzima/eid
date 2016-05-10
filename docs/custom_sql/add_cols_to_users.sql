ALTER TABLE  `users` ADD  `deactivated` TINYINT( 1 ) NOT NULL DEFAULT  '0',
ADD  `loggedon` TINYINT( 1 ) NOT NULL DEFAULT  '0',
ADD  `created` DATETIME NOT NULL ,
ADD  `createdby` VARCHAR( 250 ) NOT NULL ;s