DROP TABLE IF EXISTS `ictpbx_did`;
CREATE TABLE `ictpbx_did` (
  `ictpbx_did_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `did` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `forwarded_to` varchar(255) DEFAULT NULL,
  `trunk_id` int(11) DEFAULT NULL,
  `created` int(10) unsigned DEFAULT NULL,
  `created_by` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ictpbx_did_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ictpbx_gateway`;
CREATE TABLE `ictpbx_gateway` (
  `ictpbx_gateway_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `type` int(11) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `created` int(10) unsigned DEFAULT NULL,
  `created_by` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ictpbx_gateway_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ictpbx_service`;
CREATE TABLE `ictpbx_service` (
  `ictpbx_service_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `active` int(11) DEFAULT NULL,
  `created` int(10) unsigned DEFAULT NULL,
  `created_by` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ictpbx_service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ictpbx_technology`;
CREATE TABLE `ictpbx_technology` (
  `ictpbx_technology_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `type` int(11) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `created` int(10) unsigned DEFAULT NULL,
  `created_by` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ictpbx_technology_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ictpbx_trunk`;
CREATE TABLE `ictpbx_trunk` (
  `ictpbx_trunk_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(11) DEFAULT NULL,
  `name` varchar(32) NOT NULL,
  `host` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `port` text NOT NULL,
  `channel` int(11) DEFAULT NULL,
  `prefix` varchar(255) DEFAULT NULL,
  `dial_string` varchar(255) DEFAULT NULL,
  `register_string` varchar(255) DEFAULT NULL,
  `gateway_id` int(11) DEFAULT NULL,
  `service_flag` int(11) DEFAULT NULL,
  `technology_id` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `created` int(10) unsigned DEFAULT NULL,
  `created_by` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ictpbx_trunk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ictpbx_user`;
CREATE TABLE `ictpbx_user` (
  `ictpbx_user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `mail` varchar(32) NOT NULL,
  `phone_number` varchar(32) NOT NULL,
  `mobile_number` varchar(32) NOT NULL,
  `fax_number` varchar(32) NOT NULL,
  `address` text NOT NULL,
  `country` varchar(32) NOT NULL,
  `company` varchar(32) NOT NULL,
  `website` varchar(32) NOT NULL,
  `uid` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  `credit` float DEFAULT NULL,
  `free_bundle` int(11) DEFAULT NULL,
  `reserve_credit` float DEFAULT NULL,
  `reserve_free_bundle` int(11) DEFAULT NULL,
  `package` varchar(32) DEFAULT NULL,
  `created` int(10) unsigned DEFAULT NULL,
  `created_by` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ictpbx_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;