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

DROP TABLE IF EXISTS admin_utilisateurs_groupes;
CREATE TABLE admin_utilisateurs_groupes (
  id_utilisateur_groupe MEDIUMINT NOT NULL AUTO_INCREMENT,
  libelle_utilisateur_groupe VARCHAR(200),
  id_membre_auteur MEDIUMINT,
  PRIMARY KEY (id_utilisateur_groupe)
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

DROP TABLE IF EXISTS etats;
CREATE TABLE etats (
  id_etat MEDIUMINT NOT NULL,
  libelle_etat VARCHAR(200), date_modification DATE, id_membre_createur MEDIUMINT,
  PRIMARY KEY (id_etat)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

DROP TABLE IF EXISTS fichiers;
CREATE TABLE fichiers (
  id_fichier INT NOT NULL AUTO_INCREMENT,
  id_menu INT, id_parent INT, nom_fichier VARCHAR(200),
  poids INT, largeur INT, hauteur INT, extension VARCHAR(10),
  type_mime VARCHAR(50), date_creation DATE, id_membre_auteur INT,
  PRIMARY KEY (id_fichier)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

DROP TABLE IF EXISTS clients;
CREATE TABLE clients (
  id_client MEDIUMINT NOT NULL AUTO_INCREMENT,
  association VARCHAR(200) DEFAULT '',
  nom VARCHAR(200) DEFAULT '',
  prenom VARCHAR(200) DEFAULT '',
  adresse1 VARCHAR(200), adresse2 VARCHAR(200), adresse3 VARCHAR(200),
  cp VARCHAR(10), ville VARCHAR(200), telephone VARCHAR(200), email VARCHAR(200),
  commentaire TEXT, cle_client VARCHAR(200),
  date_creation DATE, date_modification DATE,
  id_statut_client MEDIUMINT DEFAULT 0,
  id_etat MEDIUMINT DEFAULT 0,
  id_membre_auteur MEDIUMINT,
  PRIMARY KEY (id_client)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS clients_statuts;
CREATE TABLE clients_statuts (
  id_statut_client MEDIUMINT NOT NULL AUTO_INCREMENT,
  libelle_statut VARCHAR(200), date_modification DATE, id_membre_createur MEDIUMINT,
  PRIMARY KEY (id_statut_client)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS clients_adhesions;
CREATE TABLE clients_adhesions (
  id_adhesion MEDIUMINT NOT NULL AUTO_INCREMENT,
  id_client MEDIUMINT, annee MEDIUMINT, montant FLOAT,
  date_creation DATE, date_modification DATE, id_membre_auteur MEDIUMINT,
  PRIMARY KEY (id_adhesion)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS inventaires;
CREATE TABLE inventaires (
  id_inventaire MEDIUMINT NOT NULL AUTO_INCREMENT,
  date_inventaire DATE, commentaire TEXT,
  date_creation DATE, date_modification DATE, id_etat MEDIUMINT,
  id_statut_inventaire MEDIUMINT, id_type_inventaire MEDIUMINT,
  id_membre_auteur MEDIUMINT,
  PRIMARY KEY (id_inventaire)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

DROP TABLE IF EXISTS inventaires_statuts;
CREATE TABLE inventaires_statuts (
  id_statut_inventaire MEDIUMINT NOT NULL AUTO_INCREMENT,
  libelle_statut VARCHAR(200),
  PRIMARY KEY (id_statut_inventaire)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

DROP TABLE IF EXISTS inventaires_types;
CREATE TABLE inventaires_types (
  id_type_inventaire MEDIUMINT NOT NULL AUTO_INCREMENT,
  libelle_type VARCHAR(200),
  PRIMARY KEY (id_type_inventaire)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

DROP TABLE IF EXISTS inventaires_articles;
CREATE TABLE inventaires_articles (
  id_inventaire MEDIUMINT, id_article MEDIUMINT,
  quantite_precedent MEDIUMINT, quantite_totale MEDIUMINT,
  commentaire TEXT, id_membre_auteur MEDIUMINT
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

DROP TABLE IF EXISTS reservations;
CREATE TABLE reservations (
  id_reservation MEDIUMINT NOT NULL AUTO_INCREMENT,
  id_client MEDIUMINT, date_creation DATE, date_depart DATE, date_retour DATE,
  annee_reservation MEDIUMINT, commentaire TEXT, don MEDIUMINT, date_don DATE,
  id_etat MEDIUMINT, id_statut_reservation MEDIUMINT,
  date_modification DATE, heure_modification TIME, id_membre_auteur MEDIUMINT,
  PRIMARY KEY (id_reservation)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

DROP TABLE IF EXISTS reservations_articles;
CREATE TABLE reservations_articles (
  id_reservation MEDIUMINT, id_article MEDIUMINT,
  quantite_reservee MEDIUMINT, id_membre_auteur MEDIUMINT
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

DROP TABLE IF EXISTS reservations_statuts;
CREATE TABLE reservations_statuts (
  id_statut_reservation MEDIUMINT NOT NULL AUTO_INCREMENT,
  libelle_statut VARCHAR(200), date_modification DATE, id_membre_createur MEDIUMINT,
  PRIMARY KEY (id_statut_reservation)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
