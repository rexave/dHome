-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 25 Décembre 2012 à 11:50
-- Version du serveur: 5.1.49
-- Version de PHP: 5.3.15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: 'dhome'
--

-- --------------------------------------------------------

--
-- Structure de la table 'actions'
--

CREATE TABLE actions (
  id_action int(11) NOT NULL AUTO_INCREMENT,
  lib_action varchar(255) NOT NULL,
  PRIMARY KEY (id_action)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Structure de la table 'actions_definies'
--

CREATE TABLE actions_definies (
  id_action int(11) NOT NULL,
  id_objet int(11) NOT NULL,
  id_eventGhost varchar(50) NOT NULL,
  PRIMARY KEY (id_action,id_objet)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table 'actions_notification'
--

CREATE TABLE actions_notification (
  id_notif int(11) NOT NULL AUTO_INCREMENT,
  type_notif enum('push','mail','karotz','nabaztag') NOT NULL,
  objet_notif varchar(255) NOT NULL,
  message_notif text NOT NULL,
  destinataire_mail varchar(255) NOT NULL,
  ids_nab varchar(200) DEFAULT NULL,
  PRIMARY KEY (id_notif)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Structure de la table 'actions_possibles'
--

CREATE TABLE actions_possibles (
  id_type_objet int(11) NOT NULL COMMENT 'objet cible',
  id_action int(11) NOT NULL,
  id_etat_cible int(11) NOT NULL,
  PRIMARY KEY (id_type_objet,id_action)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table 'actions_systeme'
--

CREATE TABLE actions_systeme (
  id_action int(11) NOT NULL AUTO_INCREMENT,
  type_action enum('wait','script') NOT NULL,
  param1 varchar(255) DEFAULT NULL,
  param2 varchar(255) DEFAULT NULL,
  PRIMARY KEY (id_action)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Structure de la table 'core_cron'
--

CREATE TABLE core_cron (
  id_cron int(11) NOT NULL AUTO_INCREMENT,
  id_scenario int(11) NOT NULL,
  id_cron_system int(11) NOT NULL,
  valeur_cron varchar(255) NOT NULL,
  skip_condition int(11) NOT NULL,
  lib_cron varchar(255) NOT NULL,
  PRIMARY KEY (id_cron)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table 'etats'
--

CREATE TABLE etats (
  id_etat int(11) NOT NULL AUTO_INCREMENT,
  lib_etat varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (id_etat)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Structure de la table 'etats_definis'
--

CREATE TABLE etats_definis (
  id_etat int(11) NOT NULL,
  id_objet int(11) NOT NULL,
  PRIMARY KEY (id_etat,id_objet)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table 'etats_possibles'
--

CREATE TABLE etats_possibles (
  id_type_objet int(11) NOT NULL COMMENT 'objet source',
  id_etat int(11) NOT NULL,
  valeur1 varchar(255) NOT NULL,
  valeur2 varchar(255) NOT NULL,
  valeur3 varchar(255) NOT NULL,
  valeur4 varchar(255) NOT NULL,
  nom_icone varchar(255) NOT NULL,
  PRIMARY KEY (id_type_objet,id_etat)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table 'historique_donnees'
--

CREATE TABLE historique_donnees (
  id_objet int(11) NOT NULL,
  date_histo timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  signal int(11) NOT NULL,
  batterie int(11) NOT NULL,
  valeur1 float NOT NULL,
  valeur2 float NOT NULL,
  valeur3 float NOT NULL,
  valeur4 varchar(255) NOT NULL,
  PRIMARY KEY (id_objet,date_histo)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table 'nabaztag'
--

CREATE TABLE nabaztag (
  id_nab int(11) NOT NULL AUTO_INCREMENT,
  nom_nab varchar(255) NOT NULL,
  emplacement_nab varchar(255) NOT NULL,
  serial_nab varchar(20) NOT NULL,
  PRIMARY KEY (id_nab)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table 'objets'
--

CREATE TABLE objets (
  id_objet_logique int(11) NOT NULL AUTO_INCREMENT,
  nom_objet varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  commentaire_objet text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  id_objet_physique varchar(30) NOT NULL,
  id_type_objet_logique int(11) NOT NULL,
  id_etat int(11) NOT NULL,
  PRIMARY KEY (id_objet_logique)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Structure de la table 'parametres'
--

CREATE TABLE parametres (
  id_param varchar(20) NOT NULL,
  val_param varchar(255) NOT NULL,
  PRIMARY KEY (id_param)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table 'scenario'
--

CREATE TABLE scenario (
  id_scenario int(11) NOT NULL AUTO_INCREMENT,
  id_objet_source int(11) NOT NULL,
  id_etat_source int(11) NOT NULL,
  lib_scenario varchar(255) NOT NULL,
  PRIMARY KEY (id_scenario)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Structure de la table 'scenario_actions'
--

CREATE TABLE scenario_actions (
  id_scenario int(11) NOT NULL,
  id_ordre int(11) NOT NULL,
  id_action int(11) NOT NULL,
  id_objet int(11) NOT NULL,
  id_FK int(11) DEFAULT NULL,
  PRIMARY KEY (id_scenario,id_ordre)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table 'scenario_conditions'
--

CREATE TABLE scenario_conditions (
  id_scenario int(11) NOT NULL,
  id_objet int(11) NOT NULL,
  id_etat int(11) NOT NULL,
  PRIMARY KEY (id_scenario,id_objet,id_etat)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table 'telephone_log'
--

CREATE TABLE telephone_log (
  id_log int(11) NOT NULL AUTO_INCREMENT,
  id_phone varchar(20) NOT NULL,
  from_phonelog varchar(50) NOT NULL,
  date_phonelog date NOT NULL,
  type_phonelog enum('sms','call') NOT NULL,
  message_phonelog varchar(500) NOT NULL,
  PRIMARY KEY (id_log)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Structure de la table 'type_objet'
--

CREATE TABLE type_objet (
  id_type_objet_logique int(11) NOT NULL AUTO_INCREMENT,
  nom_type_objet varchar(255) NOT NULL,
  id_physique_type_objet int(11) NOT NULL,
  lib_valeur1 varchar(255) NOT NULL,
  lib_valeur2 varchar(255) NOT NULL,
  lib_valeur3 varchar(255) NOT NULL,
  lib_valeur4 varchar(255) NOT NULL,
  PRIMARY KEY (id_type_objet_logique)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Structure de la table 'ui_scenario'
--

CREATE TABLE ui_scenario (
  id_scenario int(11) NOT NULL,
  check_condition int(11) NOT NULL,
  displayed int(11) NOT NULL,
  PRIMARY KEY (id_scenario)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table 'ui_type_objet'
--

CREATE TABLE ui_type_objet (
  id_type_objet int(11) NOT NULL,
  displayed int(11) NOT NULL,
  ordre int(11) DEFAULT NULL,
  PRIMARY KEY (id_type_objet)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
