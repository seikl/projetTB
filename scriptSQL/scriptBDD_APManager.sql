/* 
 * Script de suppression/création automatique de la BDD pour APmanageer
 */

drop user 'apmanager'@'localhost';
create user 'apmanager'@'localhost' identified by 'apmanager01';

drop database if exists apmanagerdb;
create database apmanagerdb;

grant usage on *.* to apmanager@localhost identified by 'apmanager01';
grant all privileges on apmanagerdb.* to apmanager@localhost ;

use apmanagerdb;

-- Création des tables
CREATE TABLE IF NOT EXISTS  modeles (
        noModeleAP INT NOT NULL AUTO_INCREMENT,
        nomModele VARCHAR(20) NOT NULL,
        versionFirmware VARCHAR(8) NOT NULL,
        nomFabricant VARCHAR(20) NOT NULL,
        adrMACFabricant VARCHAR(8) NOT NULL,
     PRIMARY KEY (noModeleAP)
);

CREATE TABLE IF NOT EXISTS accessPoints(
        noAP INT NOT NULL AUTO_INCREMENT,
        nomAP VARCHAR(20) NOT NULL,
        adresseIPv4 VARCHAR(15) NOT NULL,
        username VARCHAR(20),
        password VARCHAR(20) NOT NULL,
        noModeleAP INT NOT NULL REFERENCES modeles(noModeleAP),
     PRIMARY KEY (noAP)
);

CREATE TABLE IF NOT EXISTS  lignesCommande (
        noCli INT NOT NULL AUTO_INCREMENT,
        ligneCommande TEXT NOT NULL,
        portProtocole  SMALLINT NOT NULL,
        noModeleAP INT NOT NULL,
        noTypesCommande INT NOT NULL,
     PRIMARY KEY (noCli,noModeleAP,noTypesCommande)
);

CREATE TABLE IF NOT EXISTS  typesCommandes (
        noTypesCommande INT NOT NULL AUTO_INCREMENT,
        typesCommande VARCHAR(255) NOT NULL,
        description VARCHAR(255),
     PRIMARY KEY (noTypesCommande)
);

ALTER TABLE lignesCommande ADD FOREIGN KEY (noModeleAP)
REFERENCES modeles(noModeleAP);
ALTER TABLE lignesCommande ADD FOREIGN KEY (noTypesCommande)
REFERENCES typesCommandes (noTypesCommande);


--Insertion de données pour les tests
insert into modeles (nomModele,versionFirmware,nomFabricant,adrMACFabricant) values('AP-6','2.4.11','Avaya','00:20:a6');
insert into accessPoints (nomAP,adresseIPv4,password,noModeleAP) values('APADSSOL01','10.0.0.10','repuis',1);
insert into accessPoints (nomAP,adresseIPv4,password,noModeleAP) values('APADSSOL02','172.16.0.30','repuis',1);
insert into typesCommandes (typesCommande,description) values('Afficher infos système','Sert à afficher les informations systèmes de base');
insert into lignesCommande (ligneCommande,portProtocole,noModeleAP,noTypesCommande) values('show system\r\nquit\r\n',23,1,1);