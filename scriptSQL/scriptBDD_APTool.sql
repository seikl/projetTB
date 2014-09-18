/* 
 * Script de suppression/création automatique de la BDD pour APTool
 */

drop user 'apmanager'@'localhost';
flush privileges;
create user 'apmanager'@'localhost' identified by 'apmanager01';

drop database if exists apmanagerdb;
create database apmanagerdb;

grant usage on *.* to apmanager@localhost identified by 'apmanager01';
grant all privileges on apmanagerdb.* to apmanager@localhost ;

use apmanagerdb;

/*Création des tables*/
CREATE TABLE IF NOT EXISTS  modeles (
        noModeleAP INT NOT NULL AUTO_INCREMENT,
        nomModele VARCHAR(25) NOT NULL,
        versionFirmware VARCHAR(8) NOT NULL,
        nomFabricant VARCHAR(20) NOT NULL,
        adrMACFabricant VARCHAR(8) NOT NULL,
     PRIMARY KEY (noModeleAP)
);

CREATE TABLE IF NOT EXISTS accessPoints(
        noAP INT NOT NULL AUTO_INCREMENT,
        nomAP VARCHAR(50),
        adresseIPv4 VARCHAR(15) NOT NULL,
        snmpCommunity VARCHAR(12) NOT NULL DEFAULT 'public',
        username VARCHAR(20),
        password VARCHAR(20) DEFAULT 'admin',
        noModeleAP INT NOT NULL REFERENCES modeles(noModeleAP),
     PRIMARY KEY (noAP)
);

CREATE TABLE IF NOT EXISTS  typeCommandes (
        notypeCommande INT NOT NULL AUTO_INCREMENT,
        typeCommande VARCHAR(100) NOT NULL,
        description VARCHAR(255),
     PRIMARY KEY (notypeCommande)
);

CREATE TABLE IF NOT EXISTS  lignesCommande (
        noCLI INT NOT NULL AUTO_INCREMENT,
        ligneCommande TEXT NOT NULL,
        protocole VARCHAR(10) NOT NULL,
        portProtocole  SMALLINT NOT NULL,
        noModeleAP INT NOT NULL,
        notypeCommande INT NOT NULL,
     PRIMARY KEY (noCli,noModeleAP)
);

ALTER TABLE lignesCommande ADD FOREIGN KEY (noModeleAP)
REFERENCES modeles(noModeleAP);
ALTER TABLE lignesCommande ADD FOREIGN KEY (notypeCommande)
REFERENCES typeCommandes (notypeCommande);


/*Insertion de données pour les tests*/
insert into modeles (nomModele,versionFirmware,nomFabricant,adrMACFabricant) values('Localhost','CentOS 6','VMware','00:50:56');
insert into accessPoints (nomAP,adresseIPv4,password,snmpCommunity,noModeleAP) values('APTOOL','127.0.0.1','admin','public',1);
insert into typeCommandes (typeCommande,description) values('Récupérer la page d\"accueil','Effectue un GET / sur le périphérique désigné');
insert into lignesCommande (ligneCommande,protocole, portProtocole,noModeleAP,notypeCommande) values('GET / HTTP/1.1','https',443,1,1);