DROP TABLE IF EXISTS represente;
DROP TABLE IF EXISTS affecte;
DROP TABLE IF EXISTS demijournee;
DROP TABLE IF EXISTS journee;
DROP TABLE IF EXISTS creneau;
DROP TABLE IF EXISTS periode;
DROP TABLE IF EXISTS bdl;
DROP TABLE IF EXISTS composante;
DROP TABLE IF EXISTS adresse;
DROP TABLE IF EXISTS gestionnaire;
DROP TABLE IF EXISTS type;
DROP TABLE IF EXISTS commercial;
DROP TABLE IF EXISTS type_voie;
DROP TABLE IF EXISTS localite;
DROP TABLE IF EXISTS client;
DROP TABLE IF EXISTS interlocuteur;
DROP TABLE IF EXISTS prestataire;
DROP TABLE IF EXISTS personne;

CREATE TABLE personne (
   id_personne SERIAL PRIMARY KEY,
   nom VARCHAR(50) NOT NULL,
   prenom VARCHAR(50) NOT NULL,
   email VARCHAR(80),
   mot_de_passe VARCHAR(20),
   telephone VARCHAR(20)
);

CREATE TABLE prestataire (
   id_prestataire SERIAL PRIMARY KEY,
   FOREIGN KEY (id_prestataire) REFERENCES personne(id_personne)
);

CREATE TABLE interlocuteur (
   id_interlocuteur SERIAL PRIMARY KEY,
   FOREIGN KEY (id_interlocuteur) REFERENCES personne(id_personne)
);

CREATE TABLE client (
   id_client SERIAL PRIMARY KEY,
   nom_client VARCHAR(50) NOT NULL,
   tel_client VARCHAR(20)
);

CREATE TABLE localite (
   id_localite SERIAL PRIMARY KEY,     
   cp VARCHAR(5) NOT NULL,
   ville VARCHAR(50) NOT NULL
);

CREATE TABLE type_voie (
   id_typevoie SERIAL PRIMARY KEY,
   libelle VARCHAR(20)
);

CREATE TABLE commercial (
   id_commercial SERIAL PRIMARY KEY,
   FOREIGN KEY (id_commercial) REFERENCES personne(id_personne)
);

CREATE TABLE type (
   id_type SERIAL PRIMARY KEY,
   libelle VARCHAR(50)
);

CREATE TABLE gestionnaire (
   id_gestionnaire SERIAL PRIMARY KEY,
   FOREIGN KEY (id_gestionnaire) REFERENCES personne(id_personne)
);

CREATE TABLE adresse (
   id_adresse SERIAL PRIMARY KEY,
   numero int,
   nom_voie VARCHAR(50),
   id_typevoie SERIAL NOT NULL,
   id_localite SERIAL NOT NULL,
   FOREIGN KEY (id_typevoie) REFERENCES type_voie(id_typevoie),
   FOREIGN KEY (id_localite) REFERENCES localite(id_localite)
);

CREATE TABLE composante (
   id_composante SERIAL PRIMARY KEY,
   nom_composante VARCHAR(50),
   id_client SERIAL NOT NULL,
   id_adresse SERIAL NOT NULL,
   FOREIGN KEY (id_client) REFERENCES client(id_client),
   FOREIGN KEY (id_adresse) REFERENCES adresse(id_adresse)
);

CREATE TABLE bdl (
   id_composante SERIAL,
   id_gestionnaire SERIAL,
   annee SMALLINT,
   mois SMALLINT,
   signature_interlocuteur Varchar(50),
   signature_prestataire VARCHAR(50),
   commentaire VARCHAR(255),
   id_prestataire SERIAL,
   id_interlocuteur SERIAL NOT NULL,
   PRIMARY KEY (id_composante, id_prestataire, annee, mois),
   FOREIGN KEY (id_composante) REFERENCES composante(id_composante),
   FOREIGN KEY (id_prestataire) REFERENCES prestataire(id_prestataire),
   FOREIGN KEY (id_gestionnaire) REFERENCES gestionnaire(id_gestionnaire),
   FOREIGN KEY (id_interlocuteur) REFERENCES interlocuteur(id_interlocuteur)
);

CREATE TABLE periode (
   id_composante SERIAL,
   id_prestataire SERIAL,
   annee SMALLINT,
   mois SMALLINT,
   jour_du_mois SMALLINT,
   heures_sup smallint,
   PRIMARY KEY (id_composante, id_prestataire, annee, mois, jour_du_mois),
   FOREIGN KEY (id_composante, id_prestataire, annee, mois) REFERENCES bdl(id_composante, id_prestataire, annee, mois)
);

CREATE TABLE creneau (
   numero SERIAL PRIMARY KEY,
   heure_arrivee TIME,
   heure_depart TIME,
   id_composante SERIAL NOT NULL,
   id_prestataire SERIAL NOT NULL,
   annee SMALLINT NOT NULL,
   mois SMALLINT NOT NULL,
   jour_du_mois SMALLINT NOT NULL,
   heures_sup smallint,
   FOREIGN KEY (id_composante, id_prestataire, annee, mois, jour_du_mois) REFERENCES periode(id_composante, id_prestataire, annee, mois, jour_du_mois)
);

CREATE TABLE journee (
   id_composante SERIAL,
   id_prestataire SERIAL,
   annee SMALLINT,
   mois SMALLINT,
   jour_du_mois SMALLINT,
   heures_sup smallint,
   PRIMARY KEY (id_composante, id_prestataire, annee, mois, jour_du_mois),
   FOREIGN KEY (id_composante, id_prestataire, annee, mois, jour_du_mois) REFERENCES periode(id_composante, id_prestataire, annee, mois, jour_du_mois)
);

CREATE TABLE demijournee (
   id_type SERIAL,
   id_composante SERIAL NOT NULL,
   id_prestataire SERIAL NOT NULL,
   annee SMALLINT NOT NULL,
   mois SMALLINT NOT NULL,
   jour_du_mois SMALLINT NOT NULL,
   heures_sup smallint,
   PRIMARY KEY (id_type, id_composante, id_prestataire, annee, mois, jour_du_mois),
   FOREIGN KEY (id_type) REFERENCES type(id_type),
   FOREIGN KEY (id_composante, id_prestataire, annee, mois, jour_du_mois) REFERENCES periode(id_composante, id_prestataire, annee, mois, jour_du_mois)
);

CREATE TABLE represente (
   id_interlocuteur SERIAL,
   id_composante SERIAL,
   PRIMARY KEY (id_interlocuteur, id_composante),
   FOREIGN KEY (id_interlocuteur) REFERENCES interlocuteur(id_interlocuteur),
   FOREIGN KEY (id_composante) REFERENCES composante(id_composante)
);

CREATE TABLE affecte (
   id_composante SERIAL,
   id_commercial SERIAL,
   PRIMARY KEY (id_composante, id_commercial),
   FOREIGN KEY (id_composante) REFERENCES composante(id_composante),
   FOREIGN KEY (id_commercial) REFERENCES commercial(id_commercial)
);

      
-- Création de la fonction insert_into_represente_function
CREATE OR REPLACE FUNCTION insert_into_represente_function()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO represente (id_interlocuteur, id_composante)
    VALUES (NEW.id_interlocuteur, NEW.id_composante);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Création de la fonction insert_into_affecte_from_commercial
CREATE OR REPLACE FUNCTION insert_into_affecte_from_commercial()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO affecte (id_composante, id_commercial)
    VALUES (NEW.id_composante, NEW.id_commercial);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

/*
Creating triggers after population
*/

/* 
Creation of the 'insert_into_represente' trigger
*/
CREATE TRIGGER insert_into_represente
AFTER INSERT ON composante
FOR EACH ROW
EXECUTE FUNCTION insert_into_represente_function();

/* 
Creation of the 'insert_into_affecte_commercial' trigger
*/
CREATE TRIGGER insert_into_affecte_commercial
AFTER INSERT ON commercial
FOR EACH ROW
EXECUTE FUNCTION insert_into_affecte_from_commercial();
