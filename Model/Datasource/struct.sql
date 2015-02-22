# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Hôte: 127.0.0.1 (MySQL 5.6.21)
# Base de données: opencomp
# Temps de génération: 2015-02-22 20:22:46 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Affichage de la table academies
# ------------------------------------------------------------

CREATE TABLE `academies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL COMMENT 'Academie-Sous rectorat',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table academies_users
# ------------------------------------------------------------

CREATE TABLE `academies_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `academy_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `academy_id` (`academy_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `academies_users_ibfk_3` FOREIGN KEY (`academy_id`) REFERENCES `academies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `academies_users_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table classrooms
# ------------------------------------------------------------

CREATE TABLE `classrooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `year_id` int(10) unsigned NOT NULL,
  `establishment_id` int(10) unsigned NOT NULL,
  `created` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `year_id` (`year_id`),
  KEY `establishment_id` (`establishment_id`),
  CONSTRAINT `classrooms_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `classrooms_ibfk_2` FOREIGN KEY (`year_id`) REFERENCES `years` (`id`) ON UPDATE NO ACTION,
  CONSTRAINT `classrooms_ibfk_3` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`) ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table classrooms_pupils
# ------------------------------------------------------------

CREATE TABLE `classrooms_pupils` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `classroom_id` int(10) unsigned NOT NULL,
  `pupil_id` int(10) unsigned NOT NULL,
  `level_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `classroom_id` (`classroom_id`),
  KEY `pupil_id` (`pupil_id`),
  KEY `level_id` (`level_id`),
  CONSTRAINT `classrooms_pupils_ibfk_4` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `classrooms_pupils_ibfk_5` FOREIGN KEY (`pupil_id`) REFERENCES `pupils` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `classrooms_pupils_ibfk_6` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table classrooms_users
# ------------------------------------------------------------

CREATE TABLE `classrooms_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `classroom_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table competences
# ------------------------------------------------------------

CREATE TABLE `competences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `depth` int(1) unsigned DEFAULT NULL,
  `lft` int(10) unsigned NOT NULL,
  `rght` int(10) unsigned NOT NULL,
  `title` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table competences_users
# ------------------------------------------------------------

CREATE TABLE `competences_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `competence_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `classroom_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table cycles
# ------------------------------------------------------------

CREATE TABLE `cycles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table establishments
# ------------------------------------------------------------

CREATE TABLE `establishments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `postcode` int(5) unsigned NOT NULL,
  `town` varchar(45) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `academy_id` int(10) unsigned NOT NULL,
  `current_period_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `academy_id` (`academy_id`),
  KEY `current_period_id` (`current_period_id`),
  CONSTRAINT `establishments_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `establishments_ibfk_4` FOREIGN KEY (`academy_id`) REFERENCES `academies` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table establishments_users
# ------------------------------------------------------------

CREATE TABLE `establishments_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `establishment_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table evaluations
# ------------------------------------------------------------

CREATE TABLE `evaluations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `classroom_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `period_id` int(10) unsigned NOT NULL,
  `unrated` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Booléen indiquant si cette évaluation est une évaluation factice servant uniquement à contenir des items travaillés mais non notés',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `period_id` (`period_id`),
  KEY `classroom_id` (`classroom_id`),
  CONSTRAINT `evaluations_ibfk_4` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `evaluations_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `evaluations_ibfk_6` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table evaluations_items
# ------------------------------------------------------------

CREATE TABLE `evaluations_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `evaluation_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `position` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `evaluation_id` (`evaluation_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `evaluations_items_ibfk_3` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `evaluations_items_ibfk_4` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table evaluations_pupils
# ------------------------------------------------------------

CREATE TABLE `evaluations_pupils` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `evaluation_id` int(10) unsigned NOT NULL,
  `pupil_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `evaluation_id` (`evaluation_id`),
  KEY `pupil_id` (`pupil_id`),
  CONSTRAINT `evaluations_pupils_ibfk_3` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `evaluations_pupils_ibfk_4` FOREIGN KEY (`pupil_id`) REFERENCES `pupils` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table items
# ------------------------------------------------------------

CREATE TABLE `items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `competence_id` int(10) unsigned NOT NULL,
  `type` tinyint(4) unsigned NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `classroom_id` int(10) unsigned DEFAULT NULL,
  `lpcnode_id` int(11) DEFAULT NULL,
  `establishment_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `competence_id` (`competence_id`),
  KEY `user_id` (`user_id`),
  KEY `classroom_id` (`classroom_id`),
  CONSTRAINT `items_ibfk_4` FOREIGN KEY (`competence_id`) REFERENCES `competences` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `items_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `items_ibfk_6` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table items_levels
# ------------------------------------------------------------

CREATE TABLE `items_levels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned NOT NULL,
  `level_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `level_id` (`level_id`),
  CONSTRAINT `items_levels_ibfk_3` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `items_levels_ibfk_4` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table levels
# ------------------------------------------------------------

CREATE TABLE `levels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(15) NOT NULL,
  `cycle_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cycle_id` (`cycle_id`),
  CONSTRAINT `levels_ibfk_2` FOREIGN KEY (`cycle_id`) REFERENCES `cycles` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table lpcnodes
# ------------------------------------------------------------

CREATE TABLE `lpcnodes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `lft` int(10) unsigned NOT NULL,
  `rght` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table periods
# ------------------------------------------------------------

CREATE TABLE `periods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `year_id` int(10) unsigned NOT NULL,
  `establishment_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table pupils
# ------------------------------------------------------------

CREATE TABLE `pupils` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `sex` varchar(1) NOT NULL,
  `birthday` date NOT NULL,
  `state` tinyint(1) unsigned DEFAULT NULL,
  `tutor_id` int(10) unsigned DEFAULT NULL,
  `created` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table reports
# ------------------------------------------------------------

CREATE TABLE `reports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `header` varchar(255) NOT NULL DEFAULT '',
  `footer` varchar(255) NOT NULL DEFAULT '',
  `page_break` varchar(255) DEFAULT NULL,
  `classroom_id` int(10) unsigned NOT NULL,
  `period_id` varchar(255) NOT NULL DEFAULT '',
  `duplex_printing` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `classroom_id` (`classroom_id`),
  CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table results
# ------------------------------------------------------------

CREATE TABLE `results` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `evaluation_id` int(10) unsigned NOT NULL,
  `pupil_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `result` enum('A','B','C','D','ABS','X','NE') NOT NULL DEFAULT 'NE',
  `grade_a` tinyint(11) unsigned DEFAULT '0',
  `grade_b` tinyint(11) unsigned DEFAULT '0',
  `grade_c` tinyint(11) unsigned DEFAULT '0',
  `grade_d` tinyint(11) unsigned DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `evaluation_id` (`evaluation_id`),
  KEY `pupil_id` (`pupil_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `results_ibfk_5` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `results_ibfk_6` FOREIGN KEY (`pupil_id`) REFERENCES `pupils` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `results_ibfk_7` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table settings
# ------------------------------------------------------------

CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table tutors
# ------------------------------------------------------------

CREATE TABLE `tutors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `postcode` int(5) unsigned NOT NULL,
  `town` varchar(50) NOT NULL,
  `tel` int(10) unsigned DEFAULT NULL,
  `tel2` int(10) unsigned DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `notes` text,
  `updated` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table users
# ------------------------------------------------------------

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(80) DEFAULT NULL,
  `role` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `yubikeyID` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Affichage de la table years
# ------------------------------------------------------------

CREATE TABLE `years` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




--
-- Dumping routines (FUNCTION) for database 'opencomp'
--
DELIMITER ;;

# Dump of FUNCTION DEPTH
# ------------------------------------------------------------

/*!50003 SET SESSION SQL_MODE="NO_ENGINE_SUBSTITUTION"*/;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `DEPTH`(bg INT(11), bd INT(11)) RETURNS int(11)
  BEGIN
    DECLARE depth INT(11);
    SET depth = 0;
    SELECT COUNT(id)+1 INTO depth FROM competences WHERE (lft < bg AND rght > bd);
    RETURN depth;
  END */;;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;;
DELIMITER ;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
