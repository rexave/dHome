-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 25 Décembre 2012 à 11:52
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

--
-- Contenu de la table 'actions'
--

INSERT INTO actions (id_action, lib_action) VALUES
(7, 'Entrer à la maison'),
(2, 'Allumer'),
(3, 'Eteindre'),
(4, 'Sonner l''alarme'),
(5, 'Arreter la sonnerie de l''alarme'),
(6, 'Bip de confirmation Alarme'),
(8, 'Sortir de la maison'),
(10, 'Faire Sombre'),
(11, 'Faire Clair'),
(0, 'Envoyer un mail prédéfini'),
(1, 'Envoyer une notification Lapin prédéfinie'),
(-1, 'Envoyer un push prédéfini'),
(-2, 'Action Système');

--
-- Contenu de la table 'actions_definies'
--

INSERT INTO actions_definies (id_action, id_objet, id_eventGhost) VALUES
(3, 9, 'LAMPE1_OFF'),
(2, 9, 'LAMPE1_ON'),
(3, 11, 'LAMPE2_OFF'),
(2, 11, 'LAMPE2_ON'),
(3, 13, 'LAMPE3_OFF'),
(2, 13, 'LAMPE3_ON'),
(5, 10, 'ALARME_DISARM'),
(6, 10, 'ALARME_DISARM'),
(4, 10, 'ALARME_PANIC'),
(10, 17, ''),
(11, 17, ''),
(3, 18, 'CAMSEJOUR_OFF'),
(2, 18, 'CAMSEJOUR_ON'),
(7, 20, ''),
(8, 20, ''),
(2, 16, ''),
(3, 16, ''),
(2, 14, 'LAMPE4_ON'),
(3, 14, 'LAMPE4_OFF'),
(3, 21, 'KAROTZ_OFF'),
(2, 21, 'KAROTZ_ON'),
(3, 22, 'NABAZTAG1_OFF'),
(2, 22, 'NABAZTAG1_ON'),
(2, 12, 'ORDI_ON'),
(3, 12, 'ORDI_OFF'),
(2, 24, 'HP_ON'),
(3, 24, 'HP_OFF');

--
-- Contenu de la table 'actions_notification'
--

INSERT INTO actions_notification (id_notif, type_notif, objet_notif, message_notif, destinataire_mail, ids_nab) VALUES
(1, 'mail', 'Porte Ouverte', 'La porte d''entrée s''est ouverte.', 'machin@truc.com', NULL),
(2, 'karotz', 'Alerte', 'attention ! la porte d''entrée est ouverte !', '', NULL),
(5, 'push', 'porte ouverte', 'Alerte ! Porte ouverte. Alarme déclenchée !', '', NULL),
(9, 'nabaztag', 'test', 'J''aime le chocolat!', '', '3,1,');

--
-- Contenu de la table 'actions_possibles'
--

INSERT INTO actions_possibles (id_type_objet, id_action, id_etat_cible) VALUES
(10, 2, 3),
(10, 3, 4),
(8, 6, 2),
(8, 5, 2),
(8, 4, 1),
(13, 3, 4),
(13, 2, 3),
(15, 8, 19),
(15, 7, 18),
(11, 2, 3),
(11, 3, 4),
(12, 11, 12),
(12, 10, 11),
(17, 2, 3),
(17, 3, 4);

--
-- Contenu de la table 'actions_systeme'
--

INSERT INTO actions_systeme (id_action, type_action, param1, param2) VALUES
(1, 'wait', '50', ''),
(2, 'wait', '42', ''),
(3, 'script', '', ''),
(4, 'wait', '56', ''),
(5, 'script', '', ''),
(6, 'script', '', 'alarme_cameras.php'),
(8, 'wait', '23', '0'),
(9, 'script', '', 'alarme_cameras.php'),
(10, 'wait', '10', '0'),
(11, 'script', '', 'alarme_cameras_alerte.php'),
(12, 'wait', '20', '0'),
(13, 'script', '', 'alarme_cameras_normal.php'),
(14, 'script', '', 'alarme_cameras_alerte.php'),
(15, 'wait', '10', '0'),
(16, 'script', '', 'alarme_cameras_normal.php'),
(17, 'script', '', 'alarme_cameras_alerte.php'),
(18, 'wait', '1800', '0'),
(19, 'script', '', 'alarme_cameras_normal.php'),
(20, 'script', '', 'alarme_cameras_normal.php'),
(21, 'script', '', 'alarme_cameras_normal.php'),
(22, 'script', '', 'alarme_cameras_alerte.php'),
(23, 'wait', '600', '0'),
(24, 'wait', '1200', '0'),
(25, 'script', '', 'alarme_cameras_normal.php'),
(26, 'wait', '20', '0'),
(27, 'wait', '20', '0');

--
-- Contenu de la table 'core_cron'
--

INSERT INTO core_cron (id_cron, id_scenario, id_cron_system, valeur_cron, skip_condition, lib_cron) VALUES
(1, 17, 1, '* * * * *', 1, 'test'),
(2, 19, 1, '* * * * *', 1, 'test2'),
(3, 11, 1, '* * * * *', 1, 'test3');

--
-- Contenu de la table 'etats'
--

INSERT INTO etats (id_etat, lib_etat) VALUES
(1, 'Alarme'),
(2, 'Normal'),
(3, 'Allumé'),
(4, 'Eteind'),
(5, 'Bouton1_ON'),
(6, 'Bouton2_ON'),
(7, 'Bouton3_ON'),
(8, 'Bouton1_OFF'),
(9, 'Bouton2_OFF'),
(10, 'Bouton3_OFF'),
(11, 'Sombre'),
(12, 'Clair'),
(13, 'ARM'),
(14, 'DISARM'),
(15, 'Allumer les lumières'),
(16, 'Éteindre les lumières'),
(17, 'Panic'),
(18, 'à la maison'),
(19, 'dehors'),
(21, 'Remote_reboot'),
(22, 'remote_stop_alarme');

--
-- Contenu de la table 'etats_possibles'
--

INSERT INTO etats_possibles (id_type_objet, id_etat, valeur1, valeur2, valeur3, valeur4, nom_icone) VALUES
(4, 8, '01', 'off', '', '', ''),
(8, 1, '', '', '', '', 'alarme.png'),
(2, 1, 'alarm', '', '', '', 'alarme.png'),
(4, 7, '03', 'on', '', '', ''),
(4, 10, '03', 'off', '', '', ''),
(4, 9, '02', 'off', '', '', ''),
(4, 5, '01', 'on', '', '', ''),
(10, 3, '', '', '', '', 'lampe_ON.png'),
(4, 6, '02', 'on', '', '', ''),
(12, 12, '10', 'off', '', '', 'sun.png'),
(13, 3, '', '', '', '', 'connected.png'),
(14, 13, 'arm', '', '', '', ''),
(14, 15, 'light1', 'on', '', '', ''),
(14, 14, 'disarm', '', '', '', ''),
(14, 17, 'panic', '', '', '', ''),
(14, 16, 'light1', 'off', '', '', ''),
(15, 19, '', '', '', '', 'home_out.png'),
(11, 4, 'a1', 'b2', 'c3', 'd4', 'tick_ok.png'),
(11, 3, 'v1', 'v2', 'val3', 'quatre', 'camera.png'),
(10, 4, '', '', '', '', 'lampe_OFF.png'),
(3, 2, 'normal', '', '', '', 'detecteur_monoxyde_ok.png'),
(3, 1, 'alarm', '', '', '', 'danger.png'),
(16, 2, 'normal', '', '', '', 'detecteur_fumee_ok.png'),
(16, 1, 'alarm', '', '', '', 'danger.png'),
(8, 2, '', '', '', '', 'tick_ok.png'),
(12, 11, '10', 'on', '', '', 'moon.png'),
(15, 18, '', '', '', '', 'home_in.png'),
(2, 2, 'normal', '', '', '', 'tick_ok.png'),
(13, 4, '', '', '', '', 'disconnect.png'),
(17, 3, '', '', '', '', 'lapin_up.gif'),
(17, 4, '', '', '', '', 'lapin_dors.gif'),
(18, 22, 'redemarre_modem', '', '', '', 'tick_ok.png');

--
-- Contenu de la table 'nabaztag'
--

INSERT INTO nabaztag (id_nab, nom_nab, emplacement_nab, serial_nab) VALUES
(1, 'Nestor', 'Chambre', '0019dbxxxxxx'),
(3, 'Irma', 'Entrée', '0019dbxxxxxx');

--
-- Contenu de la table 'objets'
--

INSERT INTO objets (id_objet_logique, nom_objet, commentaire_objet, id_objet_physique, id_type_objet_logique, id_etat) VALUES
(0, 'dHome', '', '', 0, 0),
(1, 'Sonde Chambre', '', '41729', 1, 0),
(2, 'Consommation électrique', 'OWL160', '20866', 5, 0),
(3, 'Sonde extèrieure', '', '36356', 6, 0),
(4, 'Sonde Séjour', '', '54016', 7, 0),
(7, 'Telecommande DI.O Globale', '', '006fa7d6', 4, 8),
(9, 'Lampe Couloir', '', '03c0de01', 10, 4),
(10, 'Sirène', '', 'c0de', 8, 2),
(11, 'Lampe Entrée', '', '03c0de02', 10, 4),
(12, 'Interrupteur Ordinateur', '', '03c0d307', 13, 4),
(13, 'Lampe Salon', '', '03c0de03', 10, 4),
(14, 'Lampe Chambre', '', '03c0de04', 10, 4),
(15, 'Telecommande X10', '', '818e79', 14, 0),
(16, 'Alarme Virtuelle', '', '', 11, 3),
(17, 'Capteur Crépusculaire', 'Actions virtuelle', '003e2f66', 12, 11),
(18, 'Interrupteur Guirelande', '', '03c0de21', 13, 4),
(19, 'Porte Entrée', '', 'c0cf77', 2, 2),
(20, 'Xavier', '', '', 15, 19),
(21, 'Karotz', '', '03c0d331', 17, 4),
(22, 'Nabaztag Salon', '', '03c0d332', 17, 3),
(23, 'Telecommande DI.O Chambre', '', '', 4, 0),
(24, 'Haut-Parleur Salon', '', '00 00 00 01', 13, 4),
(25, 'Nokia 3220', '', '123456789123456', 18, 0);

--
-- Contenu de la table 'parametres'
--

INSERT INTO parametres (id_param, val_param) VALUES
('karotz_deviceID', 'vabcdef123456789'),
('openjabnab_url', '192.168.0.11'),
('openjabnab_login', 'admini'),
('openjabnab_pass', 'strate');

--
-- Contenu de la table 'scenario'
--

INSERT INTO scenario (id_scenario, id_objet_source, id_etat_source, lib_scenario) VALUES
(2, 7, 5, 'Lampe Couloir Allumer'),
(3, 7, 5, 'Lampe Couloir Eteindre'),
(4, 7, 8, 'Lampe Entrée Allumer'),
(5, 7, 8, 'Lampe Entrée Eteindre'),
(6, 15, 16, 'X10 Eteindre les lumieres'),
(7, 15, 13, 'X10 Armer'),
(8, 15, 14, 'X10 Desarmer'),
(9, 19, 1, 'Ouverture Porte d entrée'),
(10, 17, 11, 'Il fait sombre'),
(11, 17, 12, 'Il fait clair'),
(12, 7, 6, 'Lampe Salon Allumer'),
(13, 7, 6, 'Lampe Salon Eteindre'),
(16, 15, 15, 'X10 Allumer les lumières'),
(17, 7, 10, 'test karotz'),
(18, 7, 7, 'Lapins - Allumer'),
(19, 7, 7, 'Lapins - Eteindre'),
(20, 17, 11, 'Il fait sombre absent'),
(21, 0, 0, 'Quitter Home'),
(22, 0, 0, 'Entrer Home'),
(23, 0, 0, 'Dodo'),
(24, 25, 22, 'Redemarre modem');

--
-- Contenu de la table 'scenario_actions'
--

INSERT INTO scenario_actions (id_scenario, id_ordre, id_action, id_objet, id_FK) VALUES
(5, 2, 3, 11, NULL),
(4, 2, 2, 11, NULL),
(3, 2, 3, 9, NULL),
(2, 2, 2, 9, NULL),
(6, 6, 3, 12, 0),
(6, 5, 3, 13, 0),
(6, 4, 3, 11, 0),
(6, 3, 3, 9, 0),
(6, 2, 3, 14, 0),
(21, 3, 6, 10, 0),
(7, 2, 2, 16, 0),
(8, 5, 7, 20, 0),
(7, 6, -2, 0, 21),
(21, 4, 3, 9, 0),
(11, 2, 11, 17, 0),
(10, 4, 2, 13, 0),
(17, 8, -2, 0, 16),
(10, 3, 2, 11, 0),
(9, 6, -2, 0, 22),
(8, 4, 6, 10, 0),
(12, 2, 2, 13, NULL),
(13, 2, 3, 13, NULL),
(10, 5, 10, 17, 0),
(9, 7, 0, 0, 1),
(9, 9, -2, 0, 23),
(16, 2, 2, 11, 0),
(17, 7, -2, 0, 15),
(17, 6, -2, 0, 14),
(9, 13, 5, 10, 23),
(7, 3, 6, 10, 0),
(7, 5, 8, 20, 0),
(20, 2, 10, 17, 0),
(21, 2, 8, 20, 0),
(18, 2, 2, 21, 0),
(18, 3, 2, 22, 0),
(19, 2, 3, 21, 0),
(19, 3, 3, 22, 0),
(9, 5, 0, 0, 5),
(9, 2, 4, 10, 0),
(8, 3, 3, 16, 0),
(21, 5, 3, 11, 0),
(21, 6, 3, 13, 0),
(21, 7, 3, 14, 0),
(21, 8, 2, 16, 0),
(22, 2, 3, 16, 0),
(22, 3, 6, 10, 0),
(22, 4, 2, 18, 0),
(22, 5, 2, 14, 0),
(23, 5, 3, 9, 0),
(23, 4, 3, 14, 0),
(23, 6, 3, 11, 0),
(23, 7, 3, 13, 0),
(23, 8, 2, 16, 0),
(23, 9, 3, 18, 0),
(24, 6, -2, 0, 27),
(9, 14, -2, 0, 24),
(9, 15, -2, 0, 25),
(24, 5, 3, 22, 0),
(24, 2, 2, 9, 0),
(24, 7, 2, 22, 27),
(24, 8, 3, 9, 27);

--
-- Contenu de la table 'scenario_conditions'
--

INSERT INTO scenario_conditions (id_scenario, id_objet, id_etat) VALUES
(2, 9, 4),
(3, 9, 3),
(4, 11, 4),
(5, 11, 3),
(8, 16, 3),
(9, 16, 3),
(10, 20, 18),
(12, 13, 4),
(13, 13, 3),
(18, 21, 4),
(19, 21, 3),
(20, 20, 19);

--
-- Contenu de la table 'telephone_log'
--


--
-- Contenu de la table 'type_objet'
--

INSERT INTO type_objet (id_type_objet_logique, nom_type_objet, id_physique_type_objet, lib_valeur1, lib_valeur2, lib_valeur3, lib_valeur4) VALUES
(1, 'Oregon Température/Hygrométrie', 0, 'temperature', 'hygrometrie', '', ''),
(2, 'Capteur de porte', 0, 'etat', '', '', ''),
(3, 'Détecteur de monoxyde carbone', 0, 'etat', '', '', ''),
(4, 'Telecommande DI.O', 0, 'id_telecommande', 'numero_bouton', 'action', ''),
(5, 'Consommation électrique', 0, 'consommation instantanée', 'consommation cumulée', '', ''),
(6, 'Oregon Température', 0, 'Temperature', '', '', ''),
(7, 'Oregon Tempo/Hygro/Baro', 0, 'Temperature', 'Hygrometrie', 'Barometrie', 'Prevision'),
(8, 'Sirène alarme', 0, '', '', '', ''),
(10, 'Lampe', 0, '', '', '', ''),
(11, 'Alarme virtuelle', 0, '', '', '', ''),
(12, 'Capteur Crépusculaire', 0, '', '', '', ''),
(13, 'Interrupteur distant', 0, '', '', '', ''),
(14, 'Télécommande X10', 0, '', '', '', ''),
(15, 'Humain', 0, '', '', '', ''),
(16, 'Detecteur de fumée', 0, '', '', '', ''),
(17, 'Nabaztag', 0, '', '', '', ''),
(18, 'Telephone de secours', 0, '', '', '', '');

--
-- Contenu de la table 'ui_scenario'
--

INSERT INTO ui_scenario (id_scenario, check_condition, displayed) VALUES
(22, 0, 1),
(21, 0, 1),
(10, 0, 0),
(11, 0, 0),
(12, 0, 0),
(2, 0, 0),
(13, 0, 0),
(3, 0, 0),
(16, 0, 0),
(4, 0, 0),
(17, 0, 0),
(5, 0, 0),
(18, 0, 0),
(6, 0, 0),
(19, 0, 0),
(7, 0, 0),
(20, 0, 0),
(8, 0, 0),
(9, 0, 0),
(23, 0, 1);

--
-- Contenu de la table 'ui_type_objet'
--

INSERT INTO ui_type_objet (id_type_objet, displayed, ordre) VALUES
(10, 1, 10),
(13, 1, 20),
(12, 1, 55),
(11, 1, 30),
(8, 1, 40),
(3, 1, 60),
(16, 1, 62),
(2, 0, 70),
(15, 1, 80),
(7, 0, NULL),
(1, 0, NULL),
(14, 0, NULL),
(4, 0, NULL),
(5, 0, NULL),
(17, 1, 50),
(6, 0, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
