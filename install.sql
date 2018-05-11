--
-- Tabellenstruktur für Tabelle `rex_guinavigation`
--

CREATE TABLE IF NOT EXISTS `rex_guinavigation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nav_name` varchar(63) DEFAULT NULL,
  `nav_type` varchar(15) DEFAULT NULL,
  `nav_unit` varchar(15) DEFAULT NULL,
  `nav_disable` varchar(7) DEFAULT NULL,
  `base_id` int(6) DEFAULT NULL,
  `list_starting_point` varchar(7) DEFAULT NULL,
  `depth` int(6) DEFAULT '1',
  `home` varchar(7) DEFAULT NULL,
  `link_on_self` varchar(7) DEFAULT NULL,
  `current_class` varchar(63) DEFAULT NULL,
  `active_link_class` varchar(63) DEFAULT NULL,
  `individual_id` varchar(7) DEFAULT NULL,
  `link_first` text,
  `exclude` text,
  `ctxt_show` int(11) DEFAULT NULL,
  `simple_link` varchar(255) DEFAULT NULL,
  `context_start_depth` int(6) DEFAULT NULL,
  `context_start_level` int(6) DEFAULT NULL,
  `separator_string` varchar(63) DEFAULT NULL,
  `langswitch_show_active` varchar(7) DEFAULT NULL,
  `langswitch_offline_class` varchar(63) DEFAULT NULL,
  `langswitch_show_offline` varchar(63) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nav_name` (`nav_name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- AUTO_INCREMENT für Tabelle `rex_guinavigation`
--
