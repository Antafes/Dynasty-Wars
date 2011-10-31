-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 14. März 2010 um 18:21
-- Server Version: 5.1.37
-- PHP-Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_build`
--

CREATE TABLE IF NOT EXISTS `dw_build` (
  `bid` int(255) NOT NULL,
  `upgrade` tinyint(1) NOT NULL,
  `starttime` int(11) NOT NULL,
  `endtime` int(11) NOT NULL,
  PRIMARY KEY (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_buildings`
--

CREATE TABLE IF NOT EXISTS `dw_buildings` (
  `bid` int(255) NOT NULL AUTO_INCREMENT,
  `uid` int(255) NOT NULL,
  `map_x` int(3) NOT NULL,
  `map_y` int(3) NOT NULL,
  `kind` int(2) NOT NULL,
  `lvl` int(255) NOT NULL DEFAULT '0',
  `upgrade_lvl` int(2) NOT NULL DEFAULT '0',
  `position` int(2) NOT NULL,
  PRIMARY KEY (`bid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=692 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_buildings_bak`
--

CREATE TABLE IF NOT EXISTS `dw_buildings_bak` (
  `bid` int(255) NOT NULL AUTO_INCREMENT,
  `uid` int(255) NOT NULL,
  `map_x` int(3) NOT NULL,
  `map_y` int(3) NOT NULL,
  `kind` int(2) NOT NULL,
  `class` int(1) NOT NULL,
  `lvl` int(255) NOT NULL,
  `upgrade_lvl` int(2) NOT NULL,
  `position` int(2) NOT NULL,
  PRIMARY KEY (`bid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=103 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_buildtimes`
--

CREATE TABLE IF NOT EXISTS `dw_buildtimes` (
  `kind` int(2) NOT NULL,
  `btime` int(5) NOT NULL,
  PRIMARY KEY (`kind`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_buildtimes_unit`
--

CREATE TABLE IF NOT EXISTS `dw_buildtimes_unit` (
  `kind` int(2) NOT NULL,
  `btime` int(4) NOT NULL,
  PRIMARY KEY (`kind`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_buildtimes_upgr`
--

CREATE TABLE IF NOT EXISTS `dw_buildtimes_upgr` (
  `kind` tinyint(2) NOT NULL,
  `kind_u` tinyint(1) NOT NULL,
  `upgrtime` int(5) NOT NULL,
  PRIMARY KEY (`kind`,`kind_u`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_build_unit`
--

CREATE TABLE IF NOT EXISTS `dw_build_unit` (
  `tid` int(255) NOT NULL AUTO_INCREMENT,
  `kind` int(1) NOT NULL,
  `uid` int(255) NOT NULL,
  `count` int(10) NOT NULL,
  `starttime` int(11) NOT NULL,
  `endtime` int(11) NOT NULL,
  `city` varchar(7) COLLATE latin1_german2_ci NOT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_clan`
--

CREATE TABLE IF NOT EXISTS `dw_clan` (
  `cid` int(255) NOT NULL AUTO_INCREMENT,
  `clanname` varchar(30) COLLATE latin1_german2_ci NOT NULL,
  `clantag` varchar(5) COLLATE latin1_german2_ci NOT NULL,
  `founder` varchar(20) COLLATE latin1_german2_ci NOT NULL,
  `public_text` text COLLATE latin1_german2_ci NOT NULL,
  `internal_text` text COLLATE latin1_german2_ci NOT NULL,
  `applications` int(255) NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_clan_applications`
--

CREATE TABLE IF NOT EXISTS `dw_clan_applications` (
  `appid` int(255) NOT NULL AUTO_INCREMENT,
  `cid` int(255) NOT NULL,
  `uid` int(255) NOT NULL,
  `applicationtext` text COLLATE latin1_german2_ci NOT NULL,
  `apptime` int(11) NOT NULL,
  PRIMARY KEY (`appid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_clan_rank`
--

CREATE TABLE IF NOT EXISTS `dw_clan_rank` (
  `cid` int(255) NOT NULL,
  `rankid` int(2) NOT NULL,
  `rnid` int(255) NOT NULL,
  `admin` int(1) NOT NULL,
  `standard` int(1) NOT NULL,
  PRIMARY KEY (`cid`,`rankid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_clan_rankname`
--

CREATE TABLE IF NOT EXISTS `dw_clan_rankname` (
  `rnid` int(255) NOT NULL AUTO_INCREMENT,
  `rankname` varchar(20) COLLATE latin1_german2_ci NOT NULL,
  PRIMARY KEY (`rnid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_costs_b`
--

CREATE TABLE IF NOT EXISTS `dw_costs_b` (
  `kind` int(2) NOT NULL,
  `food` int(10) NOT NULL,
  `wood` int(10) NOT NULL,
  `rock` int(10) NOT NULL,
  `iron` int(10) NOT NULL,
  `paper` int(10) NOT NULL,
  `koku` int(10) NOT NULL,
  PRIMARY KEY (`kind`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_costs_b_upgr`
--

CREATE TABLE IF NOT EXISTS `dw_costs_b_upgr` (
  `kind` tinyint(4) NOT NULL,
  `kind_u` tinyint(1) NOT NULL,
  `food` int(10) NOT NULL,
  `wood` int(10) NOT NULL,
  `rock` int(10) NOT NULL,
  `iron` int(10) NOT NULL,
  `paper` int(10) NOT NULL,
  `koku` int(10) NOT NULL,
  PRIMARY KEY (`kind`,`kind_u`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_costs_u`
--

CREATE TABLE IF NOT EXISTS `dw_costs_u` (
  `kind` int(2) NOT NULL,
  `food` int(6) NOT NULL,
  `wood` int(6) NOT NULL,
  `rock` int(6) NOT NULL,
  `iron` int(6) NOT NULL,
  `paper` int(6) NOT NULL,
  `koku` int(6) NOT NULL,
  PRIMARY KEY (`kind`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_game`
--

CREATE TABLE IF NOT EXISTS `dw_game` (
  `login_closed` int(1) NOT NULL,
  `reg_closed` int(1) NOT NULL,
  `board` varchar(50) COLLATE latin1_german2_ci NOT NULL,
  `show_board` int(1) NOT NULL,
  `season` int(1) NOT NULL,
  `adminmail` varchar(50) COLLATE latin1_german2_ci NOT NULL,
  `error_report` int(2) NOT NULL,
  `unitcosts` int(1) NOT NULL,
  `version` varchar(8) COLLATE latin1_german2_ci NOT NULL,
  `canattack` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_game_menu`
--

CREATE TABLE IF NOT EXISTS `dw_game_menu` (
  `game_menu_id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(20) COLLATE latin1_german2_ci NOT NULL,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `sort` int(2) NOT NULL,
  PRIMARY KEY (`game_menu_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_log`
--

CREATE TABLE IF NOT EXISTS `dw_log` (
  `actid` int(255) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `actor` varchar(20) COLLATE latin1_german2_ci NOT NULL,
  `concerned` varchar(20) COLLATE latin1_german2_ci NOT NULL,
  `type` int(10) NOT NULL,
  `extra` varchar(50) COLLATE latin1_german2_ci NOT NULL,
  PRIMARY KEY (`actid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=106 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_lostpw`
--

CREATE TABLE IF NOT EXISTS `dw_lostpw` (
  `lpid` int(255) NOT NULL AUTO_INCREMENT,
  `mailid` varchar(30) COLLATE latin1_german2_ci NOT NULL,
  `sent_time` int(11) NOT NULL,
  `uid` int(255) NOT NULL,
  PRIMARY KEY (`lpid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_map`
--

CREATE TABLE IF NOT EXISTS `dw_map` (
  `map_x` int(3) NOT NULL DEFAULT '0',
  `map_y` int(3) NOT NULL DEFAULT '0',
  `terrain` int(1) DEFAULT NULL,
  `uid` int(255) NOT NULL,
  `city` varchar(20) COLLATE latin1_german2_ci NOT NULL,
  `maincity` int(1) NOT NULL,
  `isle` int(1) NOT NULL,
  `harbour` int(1) NOT NULL,
  PRIMARY KEY (`map_x`,`map_y`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_map2`
--

CREATE TABLE IF NOT EXISTS `dw_map2` (
  `map_x` int(3) NOT NULL DEFAULT '0',
  `map_y` int(3) NOT NULL DEFAULT '0',
  `terrain` int(1) DEFAULT NULL,
  `uid` int(255) NOT NULL,
  `city` varchar(20) COLLATE latin1_german2_ci NOT NULL,
  `maincity` int(1) NOT NULL,
  `isle` int(1) NOT NULL,
  `harbour` int(1) NOT NULL,
  PRIMARY KEY (`map_x`,`map_y`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_map_bak`
--

CREATE TABLE IF NOT EXISTS `dw_map_bak` (
  `map_x` int(3) NOT NULL DEFAULT '0',
  `map_y` int(3) NOT NULL DEFAULT '0',
  `terrain` int(1) DEFAULT NULL,
  `uid` int(255) NOT NULL,
  `city` varchar(20) COLLATE latin1_german2_ci NOT NULL,
  `maincity` int(1) NOT NULL,
  `isle` int(1) NOT NULL,
  `harbour` int(1) NOT NULL,
  PRIMARY KEY (`map_x`,`map_y`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_market`
--

CREATE TABLE IF NOT EXISTS `dw_market` (
  `mid` int(255) NOT NULL AUTO_INCREMENT,
  `sid` int(255) NOT NULL DEFAULT '0',
  `sx` int(3) NOT NULL,
  `sy` int(3) NOT NULL,
  `bid` int(11) DEFAULT NULL,
  `s_resource` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `s_amount` int(255) NOT NULL DEFAULT '0',
  `e_resource` varchar(255) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  `e_amount` int(255) NOT NULL DEFAULT '0',
  `tax` int(10) unsigned DEFAULT NULL,
  `complete` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`mid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=52 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_message`
--

CREATE TABLE IF NOT EXISTS `dw_message` (
  `msgid` int(255) NOT NULL AUTO_INCREMENT,
  `uid_sender` int(255) NOT NULL,
  `uid_recipient` int(255) NOT NULL,
  `date` int(11) NOT NULL,
  `title` varchar(100) COLLATE latin1_german2_ci NOT NULL,
  `message` text COLLATE latin1_german2_ci NOT NULL,
  `unread` int(1) NOT NULL DEFAULT '1',
  `date_read` int(11) NOT NULL,
  `type` int(1) NOT NULL,
  `archive` int(1) NOT NULL DEFAULT '0',
  `del_sender` int(1) NOT NULL DEFAULT '0',
  `del_recipient` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`msgid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=41 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_missionary`
--

CREATE TABLE IF NOT EXISTS `dw_missionary` (
  `mid` int(255) NOT NULL AUTO_INCREMENT,
  `uid` int(255) NOT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_news`
--

CREATE TABLE IF NOT EXISTS `dw_news` (
  `nid` int(255) NOT NULL AUTO_INCREMENT,
  `uid` int(255) NOT NULL,
  `nick` varchar(20) COLLATE latin1_german2_ci NOT NULL,
  `title` varchar(100) COLLATE latin1_german2_ci NOT NULL,
  `text` text COLLATE latin1_german2_ci NOT NULL,
  `date` int(11) NOT NULL,
  `changed` int(3) NOT NULL,
  `last_changed` int(11) NOT NULL,
  `changed_uid` int(255) NOT NULL,
  `changed_nick` varchar(20) COLLATE latin1_german2_ci NOT NULL,
  PRIMARY KEY (`nid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_points`
--

CREATE TABLE IF NOT EXISTS `dw_points` (
  `uid` int(255) NOT NULL AUTO_INCREMENT,
  `unit_points` int(255) NOT NULL DEFAULT '0',
  `building_points` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=47 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_res`
--

CREATE TABLE IF NOT EXISTS `dw_res` (
  `uid` int(255) NOT NULL,
  `map_x` int(3) NOT NULL,
  `map_y` int(3) NOT NULL,
  `food` float NOT NULL DEFAULT '1000',
  `wood` float NOT NULL DEFAULT '1000',
  `rock` float NOT NULL DEFAULT '1000',
  `iron` float NOT NULL DEFAULT '250',
  `paper` float NOT NULL DEFAULT '0',
  `koku` float NOT NULL DEFAULT '0',
  `last_time` int(11) NOT NULL,
  `paper_prod` int(3) NOT NULL DEFAULT '100',
  PRIMARY KEY (`map_x`,`map_y`,`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_research`
--

CREATE TABLE IF NOT EXISTS `dw_research` (
  `rid` int(255) NOT NULL AUTO_INCREMENT,
  `map_x` int(3) NOT NULL,
  `map_y` int(3) NOT NULL,
  `uid` int(255) NOT NULL,
  `type` int(2) NOT NULL,
  `class` int(2) NOT NULL,
  `lvl` int(255) NOT NULL,
  `starttime` int(11) NOT NULL,
  PRIMARY KEY (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_tribunal`
--

CREATE TABLE IF NOT EXISTS `dw_tribunal` (
  `tid` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `suitor` int(255) NOT NULL,
  `accused` int(255) NOT NULL,
  `cause` int(255) NOT NULL,
  `description` text COLLATE latin1_german2_ci NOT NULL,
  `date` int(11) unsigned NOT NULL,
  `judge` int(255) NOT NULL,
  `decision` varchar(10) COLLATE latin1_german2_ci NOT NULL,
  `reason` text COLLATE latin1_german2_ci NOT NULL,
  `decision_date` int(11) unsigned NOT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_tribunal_arguments`
--

CREATE TABLE IF NOT EXISTS `dw_tribunal_arguments` (
  `aid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(10) unsigned NOT NULL,
  `msgid` int(10) unsigned NOT NULL,
  `from` int(10) unsigned NOT NULL,
  `date_added` int(11) NOT NULL,
  `approved` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_tribunal_causes`
--

CREATE TABLE IF NOT EXISTS `dw_tribunal_causes` (
  `tcid` int(255) NOT NULL,
  `language` int(1) NOT NULL,
  `cause` varchar(30) COLLATE latin1_german2_ci NOT NULL,
  `sort` int(3) NOT NULL,
  PRIMARY KEY (`tcid`,`language`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_tribunal_comments`
--

CREATE TABLE IF NOT EXISTS `dw_tribunal_comments` (
  `tcoid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(10) unsigned NOT NULL,
  `writer` int(10) unsigned NOT NULL,
  `comment` text COLLATE latin1_german2_ci NOT NULL,
  `date_added` int(11) NOT NULL,
  `last_changed_from` int(10) unsigned NOT NULL,
  `date_last_changed` int(11) NOT NULL,
  `changed_count` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`tcoid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_tribunal_rules`
--

CREATE TABLE IF NOT EXISTS `dw_tribunal_rules` (
  `ruid` int(11) NOT NULL AUTO_INCREMENT,
  `lang` char(2) COLLATE latin1_german2_ci NOT NULL,
  `paragraph` int(3) NOT NULL,
  `title` varchar(30) COLLATE latin1_german2_ci NOT NULL,
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ruid`,`lang`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_tribunal_rules_texts`
--

CREATE TABLE IF NOT EXISTS `dw_tribunal_rules_texts` (
  `rutid` int(11) NOT NULL AUTO_INCREMENT,
  `ruid` int(11) NOT NULL,
  `lang` char(2) COLLATE latin1_german2_ci NOT NULL,
  `clause` tinyint(2) NOT NULL,
  `subclause` tinyint(2) NOT NULL,
  `description` text COLLATE latin1_german2_ci NOT NULL,
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`rutid`,`lang`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=74 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_troops`
--

CREATE TABLE IF NOT EXISTS `dw_troops` (
  `tid` int(255) NOT NULL AUTO_INCREMENT,
  `uid` int(255) NOT NULL,
  `pos_x` int(3) NOT NULL,
  `pos_y` int(3) NOT NULL,
  `name` varchar(20) COLLATE latin1_german2_ci NOT NULL,
  `res` varchar(5) COLLATE latin1_german2_ci NOT NULL,
  `amount` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_troops_move`
--

CREATE TABLE IF NOT EXISTS `dw_troops_move` (
  `tmid` int(255) NOT NULL AUTO_INCREMENT,
  `tid` int(255) NOT NULL,
  `tx` int(3) NOT NULL,
  `ty` int(3) NOT NULL,
  `type` int(1) NOT NULL,
  `endtime` int(11) NOT NULL,
  PRIMARY KEY (`tmid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_units`
--

CREATE TABLE IF NOT EXISTS `dw_units` (
  `unid` int(255) NOT NULL AUTO_INCREMENT,
  `uid` int(255) NOT NULL,
  `kind` int(2) NOT NULL,
  `count` int(255) NOT NULL,
  `pos_x` int(3) NOT NULL,
  `pos_y` int(3) NOT NULL,
  `tid` int(255) NOT NULL,
  PRIMARY KEY (`unid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=80 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dw_user`
--

CREATE TABLE IF NOT EXISTS `dw_user` (
  `uid` int(255) NOT NULL AUTO_INCREMENT,
  `nick` varchar(20) COLLATE latin1_german2_ci NOT NULL,
  `password` varchar(32) COLLATE latin1_german2_ci NOT NULL,
  `email` varchar(50) COLLATE latin1_german2_ci NOT NULL,
  `blocked` int(1) NOT NULL DEFAULT '0',
  `regdate` int(11) NOT NULL,
  `game_rank` int(1) NOT NULL,
  `rankid` int(2) NOT NULL,
  `cid` int(255) NOT NULL,
  `description` text COLLATE latin1_german2_ci NOT NULL,
  `last_login` int(11) NOT NULL,
  `status` varchar(15) COLLATE latin1_german2_ci NOT NULL,
  `language` varchar(2) COLLATE latin1_german2_ci NOT NULL,
  `religion` int(1) NOT NULL DEFAULT '1',
  `deactivated` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=47 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
