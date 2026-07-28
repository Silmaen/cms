-- Schéma MINIMAL de la base de TEST (comitefetes_test).
-- Reprend les colonnes réelles (cf. docs/base-de-donnees.md) des tables utilisées
-- par les fonctions testées. Rechargé avant CHAQUE test (isolation) : DROP + CREATE.
-- Aucune donnée personnelle : les tests insèrent eux-mêmes leurs données synthétiques.

DROP TABLE IF EXISTS admin_menu;
CREATE TABLE admin_menu (
  id_admin_menu INT NOT NULL,
  titre_fr TEXT, titre_page_fr TEXT, url TEXT,
  niveau INT, id_parent INT, ordre INT,
  nom_table TEXT, id_table TEXT,
  PRIMARY KEY (id_admin_menu)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

DROP TABLE IF EXISTS admin_menus_groupes;
CREATE TABLE admin_menus_groupes (
  id_droit MEDIUMINT NOT NULL AUTO_INCREMENT,
  id_admin_menu MEDIUMINT, id_utilisateur_groupe MEDIUMINT,
  droit MEDIUMINT, id_membre_auteur MEDIUMINT,
  PRIMARY KEY (id_droit)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

DROP TABLE IF EXISTS admin_utilisateurs;
CREATE TABLE admin_utilisateurs (
  id_utilisateur MEDIUMINT NOT NULL AUTO_INCREMENT,
  nom_utilisateur VARCHAR(200), prenom_utilisateur VARCHAR(200),
  telephone_utilisateur VARCHAR(30), email_utilisateur VARCHAR(200),
  mdp_utilisateur VARCHAR(200), id_utilisateur_groupe MEDIUMINT,
  items_par_page MEDIUMINT DEFAULT 10000, id_etat MEDIUMINT,
  cle_utilisateur VARCHAR(200), date_creation DATE, date_modification DATE,
  id_membre_auteur MEDIUMINT,
  PRIMARY KEY (id_utilisateur)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

DROP TABLE IF EXISTS admin_utilisateurs_session;
CREATE TABLE admin_utilisateurs_session (
  id_info_session MEDIUMINT NOT NULL AUTO_INCREMENT,
  id_utilisateur MEDIUMINT, id_admin_menu MEDIUMINT,
  colonne TEXT, colonne_titre_fr TEXT, largeur MEDIUMINT, ordre MEDIUMINT,
  sens_tri TEXT, items_par_page MEDIUMINT DEFAULT 10000,
  tri_utilisateur MEDIUMINT NOT NULL DEFAULT 0,
  PRIMARY KEY (id_info_session)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

DROP TABLE IF EXISTS articles;
CREATE TABLE articles (
  id_article MEDIUMINT NOT NULL AUTO_INCREMENT,
  designation VARCHAR(200) DEFAULT '', commentaire TEXT,
  id_etat MEDIUMINT DEFAULT 0, date_creation DATE, date_modification DATE,
  ordre_article MEDIUMINT NOT NULL DEFAULT 0, id_membre_auteur MEDIUMINT,
  PRIMARY KEY (id_article)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS clients;
CREATE TABLE clients (
  id_client MEDIUMINT NOT NULL AUTO_INCREMENT,
  nom VARCHAR(200) DEFAULT '', id_etat MEDIUMINT DEFAULT 0,
  PRIMARY KEY (id_client)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
