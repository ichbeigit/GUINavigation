-- Erstellungszeit: 11. Apr 2018 um 15:32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Tabellenstruktur für Tabelle `rex_guinavigation`
--

CREATE TABLE `rex_guinavigation` (
  `id` int(11) unsigned NOT NULL,
  `nav_name` varchar(63) DEFAULT NULL,
  `nav_type` varchar(15) DEFAULT NULL,
  `nav_unit` varchar(15) DEFAULT NULL,
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
  `separator_string` varchar(63) DEFAULT NULL,
  `langswitch_show_active` varchar(7) DEFAULT NULL,
  `langswitch_offline_class` varchar(63) DEFAULT NULL,
  `langswitch_show_offline` varchar(63) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Indizes für die Tabelle `rex_guinavigation`
--
ALTER TABLE `rex_guinavigation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nav_name` (`nav_name`);

--
-- AUTO_INCREMENT für Tabelle `rex_guinavigation`
--
ALTER TABLE `rex_guinavigation`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;