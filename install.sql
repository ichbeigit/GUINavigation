-- CREATE TABLE IF NOT EXISTS `%TABLE_PREFIX%navigation` (
--  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
--  `nav_name` varchar(63),
--  `nav_type` varchar(15),
--  `nav_unit` varchar(15),
--  `base_id` int(6) DEFAULT NULL,
--  `flex_show` int(6) DEFAULT NULL,
--  `depth` int(6) DEFAULT 1,
--  `home` varchar(7),
--  `link_on_self` varchar(7),
--  `last_level_articles` varchar(7),
--  `exclude_start_article` varchar(7),
--  `current_class` varchar(63) DEFAULT NULL,
--  `active_link_class` varchar(63) DEFAULT NULL,
--  `exclude` varchar(255),

--  PRIMARY KEY (`id`), UNIQUE KEY (`nav_name`)	
-- ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `rex_navigation` (
  `id` int(11) unsigned NOT NULL,
  `nav_name` varchar(63) DEFAULT NULL,
  `nav_type` varchar(15) DEFAULT NULL,
  `nav_unit` varchar(15) DEFAULT NULL,
  `base_id` int(6) DEFAULT NULL,
  `list_starting_point` varchar(7) DEFAULT NULL,
  `depth` int(6) DEFAULT '1',
  `home` varchar(7) DEFAULT NULL,
  `link_on_self` varchar(7) DEFAULT NULL,
  `last_level_articles` varchar(7) DEFAULT NULL,
  `exclude_start_article` varchar(7) DEFAULT NULL,
  `current_class` varchar(63) DEFAULT NULL,
  `active_link_class` varchar(63) DEFAULT NULL,
  `link_first` varchar(255) NOT NULL,
  `exclude` varchar(255) DEFAULT NULL,
  `ctxt_show` int(11) DEFAULT NULL,
  `simple_link` varchar(255) DEFAULT NULL,
  `separator_string` varchar(63) DEFAULT NULL,
  `langswitch_show_active` varchar(7) DEFAULT NULL,
  `langswitch_offline_class` varchar(63) DEFAULT NULL,
  `langswitch_show_offline` varchar(63) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;