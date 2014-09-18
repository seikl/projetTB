-- MySQL dump 10.13  Distrib 5.1.73, for redhat-linux-gnu (x86_64)
--
-- Host: localhost    Database: apmanagerdb
-- ------------------------------------------------------
-- Server version	5.1.73

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `accessPoints`
--

DROP TABLE IF EXISTS `accessPoints`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accessPoints` (
  `noAP` int(11) NOT NULL AUTO_INCREMENT,
  `nomAP` varchar(50) DEFAULT NULL,
  `adresseIPv4` varchar(15) NOT NULL,
  `snmpCommunity` varchar(12) NOT NULL DEFAULT 'public',
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(20) DEFAULT 'admin',
  `noModeleAP` int(11) NOT NULL,
  PRIMARY KEY (`noAP`)
) ENGINE=MyISAM AUTO_INCREMENT=118 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accessPoints`
--

LOCK TABLES `accessPoints` WRITE;
/*!40000 ALTER TABLE `accessPoints` DISABLE KEYS */;
INSERT INTO `accessPoints` VALUES (110,'AP-3','172.16.1.30','repuis','','repuis',7),(109,'AP-2','172.16.1.35','repuis','','test',8),(108,'AP-1','172.16.1.29','repuis','','repuis',8),(117,'ns5gt-si01','192.168.98.1','public','admin','repuis',11),(100,' lnb-0155.lerepuis.ch.','172.16.6.86','public','admin','access',3),(99,' lnb-0069.lerepuis.ch.','172.16.6.44','public','admin','access',3),(9,'APTOOL','127.0.0.1','public',NULL,'aptool',4),(98,' lnb-0162.lerepuis.ch.','172.16.6.91','public','admin','access',3),(97,' lnb-0119.lerepuis.ch.','172.16.6.58','public','admin','access',3),(96,' lnb-0130.lerepuis.ch.','172.16.6.66','public','admin','access',3),(95,' lnb-0166.lerepuis.ch.','172.16.6.96','public','admin','access',3),(94,' lnb-0140.lerepuis.ch.','172.16.6.67','public','admin','access',3),(93,' lnb-0095.lerepuis.ch.','172.16.6.4','public','admin','access',3),(92,' lnb-0124.lerepuis.ch.','172.16.6.60','public','admin','access',3),(91,' lnb-0083.lerepuis.ch.','172.16.6.16','public','admin','access',3),(90,' lnb-0152.lerepuis.ch.','172.16.6.105','public','admin','access',3),(89,' lnbatpoly.lerepuis.ch.','172.16.6.23','public','admin','access',3),(88,' lnb-0092.lerepuis.ch.','172.16.6.31','public','admin','access',3),(87,' lnb-0146.lerepuis.ch.','172.16.6.73','public','admin','access',3),(86,' lnb-0159.lerepuis.ch.','172.16.6.90','public','admin','access',3),(85,' lnb-0158.lerepuis.ch.','172.16.6.88','public','admin','access',3),(84,' lnb-0102.lerepuis.ch.','172.16.6.51','public','admin','access',3),(83,' lnb-0098.lerepuis.ch.','172.16.6.7','public','admin','access',3),(82,' lnb-0075.lerepuis.ch.','172.16.6.17','public','admin','access',3),(81,' lnb-0089.lerepuis.ch.','172.16.6.22','public','admin','access',3),(80,' lnb-0174.lerepuis.ch.','172.16.6.19','public','admin','access',3),(79,' lnb-0091.lerepuis.ch.','172.16.6.24','public','admin','access',3),(78,' brn_3cc7e4.lerepuis.ch.','172.16.6.75','public','admin','access',3),(77,' lnbatsanit.lerepuis.ch.','172.16.6.25','public','admin','access',3),(76,' lnb-0103.lerepuis.ch.','172.16.6.41','public','admin','access',3),(75,' lnb-0081.lerepuis.ch.','172.16.6.15','public','admin','access',3),(74,' lnb-0127.lerepuis.ch.','172.16.6.37','public','admin','access',3),(73,' lnb-0167.lerepuis.ch.','172.16.6.94','public','admin','access',3),(72,' lnb-0154.lerepuis.ch.','172.16.6.83','public','admin','access',3),(71,' lnb-0168.lerepuis.ch.','172.16.6.98','public','admin','access',3),(70,' lnb-0115.lerepuis.ch.','172.16.6.53','public','admin','access',3),(69,' lnb-0173.lerepuis.ch.','172.16.6.104','public','admin','456',3),(68,' lnb-0104.lerepuis.ch.','172.16.6.42','public','admin','access',3),(67,' lnb-0145.lerepuis.ch.','172.16.6.71','public','admin','access',3),(66,' lnb-0112.lerepuis.ch.','172.16.6.109','public','admin','access',3),(65,' lnb-0121.lerepuis.ch.','172.16.6.57','public','admin','access',3),(64,' lnb-0157.lerepuis.ch.','172.16.6.87','public','admin','access',3),(63,' lnb-0101.lerepuis.ch.','172.16.6.64','public','admin','access',3),(62,' lnb-0099.lerepuis.ch.','172.16.6.6','public','admin','access',3),(61,' lnb-0093.lerepuis.ch.','172.16.6.36','public','admin','access',3),(60,' lnb-0078.lerepuis.ch.','172.16.6.8','public','admin','access',3),(59,' lnb-0123.lerepuis.ch.','172.16.6.63','public','admin','access',3),(58,' lnb-0068.lerepuis.ch.','172.16.6.40','public','admin','access',3),(57,' lnb-0100.lerepuis.ch.','172.16.6.11','public','admin','access',3),(56,' lnb-0087.lerepuis.ch.','172.16.6.32','public','admin','access',3),(101,' lnb-0082.lerepuis.ch.','172.16.6.14','public','admin','access',3),(102,' lnb-0165.lerepuis.ch.','172.16.6.97','public','admin','access',3),(103,' lnb-0142.lerepuis.ch.','172.16.6.68','public','admin','access',3),(104,' lnb-0084.lerepuis.ch.','172.16.6.20','public','admin','access',3),(105,' lnb-0070.lerepuis.ch.','172.16.6.74','public','admin','access',3),(106,' lnb-0125.lerepuis.ch.','172.16.6.125','public','admin','access',3),(115,'02-169-0301.lerepuis.ch.','172.16.2.217','public','','R3pu1s',6),(111,'AP-4','172.16.1.43','repuis','','repuis',7),(112,'AP-5','172.16.1.68','repuis','','â—â—â—â—â—â—',7),(113,'AP-6','172.16.1.60','repuis','','repuis',7),(114,'testAPMaison','10.0.0.62','public','','â—â—â—â—â—â—',7),(116,'routeur.asus.com','10.0.0.10','public','admin','test',9);
/*!40000 ALTER TABLE `accessPoints` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lignesCommande`
--

DROP TABLE IF EXISTS `lignesCommande`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lignesCommande` (
  `noCLI` int(11) NOT NULL AUTO_INCREMENT,
  `ligneCommande` text NOT NULL,
  `protocole` varchar(10) NOT NULL,
  `portProtocole` smallint(6) NOT NULL,
  `noModeleAP` int(11) NOT NULL,
  `notypeCommande` int(11) NOT NULL,
  PRIMARY KEY (`noCLI`,`noModeleAP`,`notypeCommande`),
  KEY `noModeleAP` (`noModeleAP`),
  KEY `notypeCommande` (`notypeCommande`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lignesCommande`
--

LOCK TABLES `lignesCommande` WRITE;
/*!40000 ALTER TABLE `lignesCommande` DISABLE KEYS */;
INSERT INTO `lignesCommande` VALUES (30,'get system','TELNET',23,11,1),(20,'.1.3.6.1.2.1','SNMP',161,4,5),(3,'.1.3.6.1.2.1.43.10.2.1.4','snmp',161,3,4),(10,'show system','TELNET',23,7,1),(25,'GET / HTTP/1.1','HTTPS',8443,9,1),(12,'GET / HTTP/1.1','HTTP',80,3,2),(13,'GET / HTTP/1.1','HTTPS',443,6,2),(6,'GET /printer/maininfo.html HTTP/1.1\nHost: 172.16.6.63\nUser-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\nAccept-Language: en-US,en;q=0.5\nAccept-Encoding: gzip, deflate\nCookie: AutoRefresh=off\nConnection: keep-alive\n','http',80,3,3),(31,'get arp','TELNET',23,11,15),(32,'POST /bio/post/netAdmin.html HTTP/1.1\r\n\r\nsvc_contact=sinfo@lerepuis.ch&svc_location=','HTTP',80,3,6),(29,'POST /cfg/system.html HTTP/1.1 \r\n \r\nEmWeb_ns%3Asnmp%3A233=APADSL01&EmWeb_ns%3Asnmp%3A234=pas+de+lieu&EmWeb_ns%3Asnmp%3A235=&EmWeb_ns%3Asnmp%3A236.0*s=sinfo%40lerepuis.ch&EmWeb_ns%3Asnmp%3A237=','HTTPS',443,8,6),(28,'set sysctemail sinfo@lerepuis.ch','TELNET',23,7,6),(18,'POST /printer/post/plock.html?autoref=0&weblang=0 HTTP/1.1\r\n\r\nSCROLLMSGSW=ON&SCROLLMSGENTTY=LOW+MAYONNAISE&PLOCKPASS=000&CPLOCK=OFF','HTTP',80,3,11),(19,'POST /printer/post/plock.html?autoref=0&weblang=0 HTTP/1.1\r\n\r\nSCROLLMSGENTTY=&SCROLLMSGSW=OFF&PLOCKPASS=000&CPLOCK=OFF','HTTP',80,3,12),(24,'POST /printer/post/plock.html?autoref=0&weblang=0 HTTP/1.1\r\n\r\nSCROLLMSGSW=ON&SCROLLMSGENTTY=Enjoy+your+day+%3A%3D%29&PLOCKPASS=000&CPLOCK=OFF','HTTP',80,3,14),(26,'GET / HTTP/1.1','HTTPS',443,8,1);
/*!40000 ALTER TABLE `lignesCommande` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modeles`
--

DROP TABLE IF EXISTS `modeles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modeles` (
  `noModeleAP` int(11) NOT NULL AUTO_INCREMENT,
  `nomModele` varchar(25) NOT NULL,
  `versionFirmware` varchar(8) NOT NULL,
  `nomFabricant` varchar(20) NOT NULL,
  `adrMACFabricant` varchar(8) NOT NULL,
  PRIMARY KEY (`noModeleAP`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modeles`
--

LOCK TABLES `modeles` WRITE;
/*!40000 ALTER TABLE `modeles` DISABLE KEYS */;
INSERT INTO `modeles` VALUES (7,'AP-6','2.4.11','Avaya','00:20:a6'),(11,'Netscreen NS5GT','5.3.0r3','Juniper','00:12:1e'),(3,'HL-6050D/DN serie','1.03','Brother','00:80:77'),(4,'Localhost','CentOS 6','VMware','00:50:56'),(6,'SyncMaster NC241','4.5','Teradici','C4:73:1e'),(8,'AP-6 SSL','2.7','Avaya','00:20:a6'),(9,'RT66CU','3.434','Asus','bc:ee:7b');
/*!40000 ALTER TABLE `modeles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `typeCommandes`
--

DROP TABLE IF EXISTS `typeCommandes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `typeCommandes` (
  `notypeCommande` int(11) NOT NULL AUTO_INCREMENT,
  `typeCommande` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`notypeCommande`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `typeCommandes`
--

LOCK TABLES `typeCommandes` WRITE;
/*!40000 ALTER TABLE `typeCommandes` DISABLE KEYS */;
INSERT INTO `typeCommandes` VALUES (1,'Afficher infos systÃ¨me','Sert Ã  afficher les informations systÃ¨mes via une commande TELNET'),(2,'Afficher la page d\'accueil','Envoi d\'une requÃªte GET / en HTTP'),(3,'Afficher la page d\'informations','Envoi d\'une requÃªte GET / en HTTP pour obtenir la page d\'informations d\'un AP'),(4,'Afficher le nombre d\'impressions','Afficher le nombre d\'impressions via un OID SNMP'),(5,'Parcourir toutes les OID SNMP','Effectue un snmpwalk Ã  la racine'),(6,'modfier l\'adresse de contact','SpÃ©cifie -sinfo@lerepuis.ch- comme adresse email de contact'),(15,'Afficher la table ARP','Utilise une commande TELNET pour afficher la table ARP du pÃ©riphÃ©rique distant'),(11,'Editer le message du panneau LCD','Affiche un message trÃ¨s important'),(12,'DÃ©sactiver le message du panneau LCD',''),(14,'Editer le message du panneau LCD','inscrit \"Enjoy your day\" sur le panneau LCD');
/*!40000 ALTER TABLE `typeCommandes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-09-18 15:20:16
