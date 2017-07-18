<?php
include '../dbconfig.php';

$query = array();

$query[] = "CREATE TABLE IF NOT EXISTS `ust_clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(128) NOT NULL,
  `token` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ;";

$query[] = "CREATE TABLE IF NOT EXISTS `ust_clientpage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `last_activity` datetime NOT NULL,
  `page` varchar(128) NOT NULL,
  `resolution` varchar(16) NOT NULL,
  `clientid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_clientpage` FOREIGN KEY (`clientid`) REFERENCES ust_clients(`id`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ;";

$query[] = "CREATE TABLE IF NOT EXISTS `ust_client_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientid` int(11) NOT NULL,
  `tag` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_clientid_tag` (`clientid`, `tag`),
  CONSTRAINT `FK_tag_clients` FOREIGN KEY (`clientid`) REFERENCES ust_clients(`id`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ;";

$query[] = "CREATE TABLE IF NOT EXISTS `ust_clicks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client` int(11) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_clicks` FOREIGN KEY (`client`) REFERENCES ust_clientpage(`id`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ;";

$query[] = "CREATE TABLE IF NOT EXISTS `ust_movements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client` int(11) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  CONSTRAINT `FK_movements` FOREIGN KEY (`client`) REFERENCES ust_clientpage(`id`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ;";


$query[] = "CREATE TABLE IF NOT EXISTS `ust_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `record` text NOT NULL,
  `client` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_records` FOREIGN KEY (`client`) REFERENCES ust_clientpage(`id`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ;";

$query[] = "CREATE TABLE IF NOT EXISTS `ust_partials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `record` text NOT NULL,
  `client` int(11) NOT NULL UNIQUE,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_partials` FOREIGN KEY (`client`) REFERENCES ust_clientpage(`id`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ;";

$query[] = "CREATE TABLE IF NOT EXISTS `ust_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL UNIQUE,
  `password` varchar(128) NOT NULL,
  `level` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ;";

$query[] = "CREATE TABLE IF NOT EXISTS `ust_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `domain` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_access` FOREIGN KEY (`userid`) REFERENCES ust_users(`id`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ;";

$query[] = "INSERT IGNORE INTO `ust_users` (`id`, `name`, `password`, `level`) VALUES
(1, 'admin', '0d107d09f5bbe40cade3de5c71e9e9b7', 5);";

$query[] = "CREATE TABLE IF NOT EXISTS `ust_limits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(128) NOT NULL,
  `record_limit` int(11) NOT NULL DEFAULT '1000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain` (`domain`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4;";

foreach($query as $q){
    $db->query($q);
}

header("location:autoConfig.php");
?>
