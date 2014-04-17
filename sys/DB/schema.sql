CREATE TABLE `as_languages` (
  `id_language` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `language` char(2) NOT NULL,
  `language_name` varchar(45) NOT NULL,
  `charset` varchar(15) DEFAULT 'UTF-8',
  `locale` varchar(10) DEFAULT 'en_GB',
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_language`),
  UNIQUE KEY `as_languages_UK_language` (`language`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `as_locations` (
  `id_location` int(10) unsigned NOT NULL,
  `country` char(2) NOT NULL,
  `region` char(2) NOT NULL,
  `city` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_location`),
  KEY `as_locations_IX_country_AND_city` (`country`,`city`),
  KEY `as_locations_IX_city` (`city`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE  `as_users` (
  `id_user` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_language` int(10) unsigned NOT NULL,
  `id_location` int(10) unsigned NOT NULL,
  `id_role` int(10) unsigned NOT NULL,
  `name` varchar(145) DEFAULT NULL,
  `email` varchar(145) NOT NULL,
  `paypal_email` varchar(145) NOT NULL,
  `website` varchar(145) NOT NULL,
  `password` varchar(64) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_modified` datetime DEFAULT NULL,
  `logins` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `last_login` datetime DEFAULT NULL,
  `last_ip`  float DEFAULT NULL,
  `user_agent` varchar(40) DEFAULT NULL,
  `token` varchar(40) DEFAULT NULL,
  `token_created` datetime DEFAULT NULL,
  `token_expires` datetime DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `as_users_UK_email` (`email`),
  UNIQUE KEY `as_users_UK_token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `as_roles` (
  `id_role` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(245) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_role`),
  UNIQUE KEY `as_roles_UK_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `as_access` (
  `id_access` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_role` int(10) unsigned NOT NULL,
  `access` varchar(100) NOT NULL,
  PRIMARY KEY (`id_access`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `as_adformats` (
  `id_adformat` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `max_ad_slots` tinyint(1) unsigned NOT NULL,
  `orientation` enum('horizontal','vertical','square') NOT NULL,
  `width` smallint(5) unsigned NOT NULL,
  `height` smallint(5) unsigned NOT NULL,
  `title_size` tinyint(3) unsigned NOT NULL default '25',
  `description_size` tinyint(3) unsigned NOT NULL default '35',
  `description2_size` tinyint(3) unsigned NOT NULL default '35',
  `url_size` tinyint(3) unsigned NOT NULL default '25',
  `image` tinyint(1) NOT NULL default '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY  (`id_adformat`),
  UNIQUE KEY `as_adformat_UK_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--future think about products to publish multiple ads? so you actually buy displays...mmm
CREATE TABLE `as_products` (
  `id_product` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(145) NOT NULL,
  `price` decimal(14,3) NOT NULL DEFAULT '0',
  `vat` decimal(14,3) NOT NULL DEFAULT '0',
  `affiliate_commission`int(10) unsigned DEFAULT NULL,
  `displays` int(10) unsigned DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_product`),
  UNIQUE KEY `as_products_UK_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--coupons? future...
CREATE TABLE  `as_orders` (
  `id_order` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_affiliate` int(10) unsigned DEFAULT NULL,
  `id_ad` int(10) unsigned NOT NULL,
  `id_product` int(10) unsigned NOT NULL,
  `paymethod` varchar(20) DEFAULT 'paypal',
  `transaction` varchar(45) DEFAULT NULL,
  `payer_email` varchar(145) DEFAULT NULL,
  `amount` decimal(14,2) NOT NULL DEFAULT '0',
  `vat` decimal(14,3) NOT NULL DEFAULT '0',
  `ip_address` float DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_paid` DATETIME  NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--for help tips,pages/FAQ and email templates using the type.
CREATE TABLE `as_content` (
  `id_content` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_language` int(10) unsigned NOT NULL,
  `id_content_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `parent_deep` int(2) unsigned NOT NULL DEFAULT '0',
  `order` int(2) unsigned NOT NULL DEFAULT '0',
  `title` varchar(145) NOT NULL,
  `seotitle` varchar(145) NOT NULL,
  `description` TEXT NULL,
  `from_email` varchar(145) NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type` enum('page','email','help') NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_content`),
  UNIQUE KEY `as_content_UK_id_language_AND_seotitle` (`id_language`,`seotitle`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--send emails
--lyfecile, remember
--clean DB unpaid ads delete images
--optimize DB
-- create reports

CREATE TABLE `as_crontab` (
  `id_cron` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `period` varchar(50) NOT NULL,
  `callback` varchar(140) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_cron`),
  UNIQUE KEY `as_crontab_UK_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `as_cronjobs` (
  `id_cronjob` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_cronjob_locking` int(10) unsigned DEFAULT NULL,
  `id_cron` int(10) unsigned NOT NULL,
  `output` varchar(50) DEFAULT NULL,
  `date_start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_finished` datetime NOT NULL,
  `pid` int(10) unsigned DEFAULT NULL,
  `running` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_cronjob`),
  KEY `as_cronjobs_IK_id_cron` (`id_cron`),
  KEY `as_cronjobs_IK_running` (`running`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


------------------------



CREATE TABLE `as_config` ( 
  `group_name` VARCHAR(128)  NOT NULL, 
  `config_key` VARCHAR(128)  NOT NULL, 
  `config_value` TEXT, 
  PRIMARY KEY (`group_name`, `config_key`) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;



--default configs
INSERT INTO `oc_config` (`group_name`, `config_key`, `config_value`) VALUES
('appearance', 'theme', 'default'),
('i18n', 'charset', 'utf-8'),
('init', 'base_url', '/'),
('i18n', 'timezone', 'Europe/Madrid'),
('i18n', 'locale', 'en_US'),
('cookie', 'salt', '13413mdksdf-948jd');

--admin user
INSERT INTO `as_users` (`name`, `email`, `password`, `status`, `role`)
VALUES ('chema', 'neo22s@gmail.com', '15ecfab1f55bea08e836dc0a393cc267969e9b35e4468aa5c90e2f22bd5d44fd', '1', '10');




--@todo

--
-- Table structure for table `color_schemes`
--

DROP TABLE IF EXISTS `color_schemes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `color_schemes` (
  `id_color_scheme` int(10) unsigned NOT NULL auto_increment,
  `id_entity_publisher` int(10) unsigned NOT NULL,
  `name` char(50) NOT NULL,
  `border_color` char(6) NOT NULL,
  `title_color` char(6) NOT NULL,
  `title_id_font` int(10) unsigned NOT NULL,
  `title_font_size` tinyint(3) unsigned NOT NULL,
  `title_font_style` enum('normal','italic') NOT NULL default 'normal',
  `title_font_weight` enum('normal','bold') NOT NULL default 'normal',
  `background_color` char(6) NOT NULL,
  `text_color` char(6) NOT NULL,
  `text_id_font` int(10) unsigned NOT NULL,
  `text_font_size` tinyint(3) NOT NULL,
  `text_font_style` enum('normal','italic') NOT NULL default 'normal',
  `text_font_weight` enum('normal','bold') NOT NULL default 'normal',
  `url_color` char(6) NOT NULL,
  `url_id_font` int(10) unsigned NOT NULL,
  `url_font_size` tinyint(3) unsigned NOT NULL,
  `url_font_style` enum('normal','italic') NOT NULL default 'normal',
  `url_font_weight` enum('normal','bold') NOT NULL default 'normal',
  PRIMARY KEY  (`id_color_scheme`),
  KEY `publisher` (`id_entity_publisher`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `color_schemes`
--

INSERT INTO `color_schemes` VALUES (1,1,'Default','AAAAAA','0000FF',2,12,'','','FFFFFF','000000',2,12,'','','008000',2,12,'','');