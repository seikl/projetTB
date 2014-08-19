/* 
 * Script de suppression/création automatique de la BDD pour APmanageer
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
        nomAP VARCHAR(20) NOT NULL,
        adresseIPv4 VARCHAR(15) NOT NULL,
        snmpCommunity VARCHAR(20) NOT NULL DEFAULT 'public',
        username VARCHAR(20),
        password VARCHAR(20),
        noModeleAP INT NOT NULL REFERENCES modeles(noModeleAP),
     PRIMARY KEY (noAP)
);

CREATE TABLE IF NOT EXISTS  lignesCommande (
        noCLI INT NOT NULL AUTO_INCREMENT,
        ligneCommande TEXT NOT NULL,
        protocole VARCHAR(10) NOT NULL,
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


/*Insertion de données pour les tests*/
insert into modeles (nomModele,versionFirmware,nomFabricant,adrMACFabricant) values('AP-6','2.4.11','Avaya','00:20:a6');
insert into modeles (nomModele,versionFirmware,nomFabricant,adrMACFabricant) values('SS-439','4.1.0','Qnap','00:08:9b');
insert into modeles (nomModele,versionFirmware,nomFabricant,adrMACFabricant) values('HL-6050D/DN serie','1.03','Brother','00:80:77');
insert into modeles (nomModele,versionFirmware,nomFabricant,adrMACFabricant) values('Localhost','CentOS 6','VMware','00:50:56');
insert into accessPoints (nomAP,adresseIPv4,password,noModeleAP) values('APADSSOL01','172.16.1.29','repuis',1);
insert into accessPoints (nomAP,adresseIPv4,password,snmpCommunity,noModeleAP) values('APADSSOL02','172.16.1.30','repuis','repuis',1);
insert into accessPoints (nomAP,adresseIPv4,password,snmpCommunity,noModeleAP) values('testAPMaison','10.0.0.62','public','public',1);
insert into accessPoints (nomAP,adresseIPv4,noModeleAP) values('NASMaison','10.0.0.60',2);
insert into accessPoints (nomAP,adresseIPv4,noModeleAP) values('LNB-0123','172.16.6.63',3);
insert into accessPoints (nomAP,adresseIPv4,noModeleAP) values('LNB-0068','172.16.6.40',3);
insert into accessPoints (nomAP,adresseIPv4,noModeleAP) values('LNB-0069 ','172.16.6.44',3);
insert into accessPoints (nomAP,adresseIPv4,noModeleAP) values('LNB-0138','192.168.18.41',3);
insert into accessPoints (nomAP,adresseIPv4,password,snmpCommunity,noModeleAP) values('APTOOL','127.0.0.1','aptool','public',4);
insert into typesCommandes (typesCommande,description) values('Afficher infos système','Sert à afficher les informations systèmes via une commande TELNET');
insert into typesCommandes (typesCommande,description) values('Afficher la page d\'accueil','Envoi d\'une requête GET / en HTTP');
insert into typesCommandes (typesCommande,description) values('Afficher la page d\'informations','Envoi d\'une requête GET / en HTTP pour obtenir la page d\'informations d\'un AP');
insert into typesCommandes (typesCommande,description) values('Afficher le nombre d\'impressions','Afficher le nombre d\'impressions via un OID SNMP');
insert into typesCommandes (typesCommande,description) values('Parcourir toutes les OID SNMP','Effectue un snmpwalk à la racine');
insert into lignesCommande (ligneCommande,protocole, portProtocole,noModeleAP,noTypesCommande) values('show system\r\nquit\r\n','telnet',23,1,1);
insert into lignesCommande (ligneCommande,protocole, portProtocole,noModeleAP,noTypesCommande) values('uname -a\r\nquit\r\n','telnet',23,2,1);
insert into lignesCommande (ligneCommande,protocole, portProtocole,noModeleAP,noTypesCommande) values('.1.3.6.1.2.1.43.10.2.1.4','snmp',161,3,4);
insert into lignesCommande (ligneCommande,protocole, portProtocole,noModeleAP,noTypesCommande) values('.','snmp',161,4,5);
insert into lignesCommande (ligneCommande,protocole, portProtocole,noModeleAP,noTypesCommande) values('GET / HTTP/1.1
Host: 0.0.0.0
User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Referer: http://0.0.0.0/index.html
Authorization: Basic OnJlcHVpcw==
Connection: keep-alive
','http',80,1,2);
insert into lignesCommande (ligneCommande,protocole, portProtocole,noModeleAP,noTypesCommande) values('GET /printer/maininfo.html HTTP/1.1
Host: 172.16.6.63
User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Cookie: AutoRefresh=off
Connection: keep-alive
','http',80,3,3);

