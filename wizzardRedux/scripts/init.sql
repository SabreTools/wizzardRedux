SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `roms`
--

-- --------------------------------------------------------

--
-- Table Structure for Table `checksums`
--

CREATE TABLE IF NOT EXISTS `checksums` (
  `file` int(11) NOT NULL,
  `size` bigint(12) NOT NULL,
  `crc` char(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `md5` char(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sha1` char(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`file`,`size`,`crc`,`md5`,`sha1`),
  KEY `file` (`file`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table Structure for Table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `set` int(11) NOT NULL,
  `name` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` char(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `set` (`set`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=DYNAMIC AUTO_INCREMENT=49781487 ;

-- --------------------------------------------------------

--
-- Table Structure for Table `games`
--

CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `system` int(11) NOT NULL,
  `name` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `source` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `system` (`system`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=DYNAMIC AUTO_INCREMENT=22416052 ;

-- --------------------------------------------------------

--
-- Table Structure for Table `systems`
--

CREATE TABLE IF NOT EXISTS `systems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manufactor` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `system` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT AUTO_INCREMENT=480 ;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `checksums`
--
ALTER TABLE `checksums`
  ADD CONSTRAINT `checksums_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `systems` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `games` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `datfiles` (`id`) ON DELETE CASCADE;
