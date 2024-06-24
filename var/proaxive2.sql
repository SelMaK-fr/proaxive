-- MariaDB dump 10.19  Distrib 10.11.6-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: proaxive_slim
-- ------------------------------------------------------
-- Server version	10.11.6-MariaDB-0+deb12u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `booking`
--

DROP TABLE IF EXISTS `booking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `backgroundColor` varchar(255) DEFAULT NULL,
  `textColor` varchar(255) DEFAULT NULL,
  `allDay` tinyint(1) DEFAULT NULL,
  `component` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking`
--

LOCK TABLES `booking` WRITE;
/*!40000 ALTER TABLE `booking` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `logo_link` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `logo_file` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES
(1,'Acer',NULL,'acer','pe190873615-acer.png'),
(2,'Asus',NULL,'asus','pe59894369-asus.png'),
(3,'Apple',NULL,'apple','pe938389281-apple.png'),
(4,'Brother',NULL,'brother','pe847625144-brother.png'),
(5,'Canon',NULL,'canon','pe303630207-canon.png'),
(6,'Compaq',NULL,'compaq','pe762190526-compaq.png'),
(7,'Cisco',NULL,'cisco','pe705228956-Cisco.png'),
(8,'Dell',NULL,'dell','pe279357682-dell.png'),
(9,'Epson',NULL,'epson','pe47222901-epson.png'),
(10,'HP',NULL,'hp','pe826297485-hp.png'),
(11,'Lenovo',NULL,'lenovo','pe718666919-lenovo.png'),
(12,'Packard Bell',NULL,'packard-bell','pe381036515-packard-bell.png'),
(13,'Samsung',NULL,'samsung','pe658144462-samsung.png'),
(14,'Sony',NULL,'sony','pe441648698-sony.png'),
(15,'Xiaomi',NULL,'xiaomi','pe197696821-xiaomi.png');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `about` text DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `zipcode` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `addr_longitude` varchar(255) DEFAULT NULL,
  `addr_latitude` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `phone_indicatif` varchar(255) DEFAULT NULL,
  `director` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `mail` varchar(255) NOT NULL,
  `siret` varchar(255) DEFAULT NULL,
  `ape` varchar(255) DEFAULT NULL,
  `aprm` varchar(255) DEFAULT NULL,
  `rm` varchar(255) DEFAULT NULL,
  `rcs` varchar(255) DEFAULT NULL,
  `rc_pro` varchar(255) DEFAULT NULL,
  `tva` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `isdefault` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company`
--

LOCK TABLES `company` WRITE;
/*!40000 ALTER TABLE `company` DISABLE KEYS */;
INSERT INTO `company` VALUES
(1,'Mon entreprise',NULL,'EURL','Mon adresse postale',NULL,'France',NULL,NULL,NULL,NULL,'0102030405',NULL,NULL,'+33','John Doe',NULL,'myname@myenterprise.fr',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2024-06-23 11:27:35','2024-06-23 11:27:35');
/*!40000 ALTER TABLE `company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(255) DEFAULT NULL,
  `passwd` longtext DEFAULT NULL,
  `activated` int(11) NOT NULL,
  `login_id` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `on_sale` varchar(255) DEFAULT NULL,
  `addr_longitude` varchar(255) DEFAULT NULL,
  `addr_latitude` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `phone_indicatif` varchar(255) DEFAULT NULL,
  `profil_type` varchar(255) DEFAULT NULL,
  `favorite_contact` varchar(255) DEFAULT NULL,
  `address` mediumtext DEFAULT NULL,
  `zipcode` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `type_housing` varchar(255) DEFAULT NULL,
  `h_floor` varchar(255) DEFAULT NULL,
  `h_digicode` varchar(255) DEFAULT NULL,
  `h_about` longtext DEFAULT NULL,
  `about` longtext DEFAULT NULL,
  `tva_number` varchar(255) DEFAULT NULL,
  `type_status` varchar(255) DEFAULT NULL,
  `siret_number` varchar(255) DEFAULT NULL,
  `naf_number` varchar(255) DEFAULT NULL,
  `phone_2` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `contact_status` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(255) DEFAULT NULL,
  `contact_fullname` varchar(255) DEFAULT NULL,
  `contact_mail` varchar(255) DEFAULT NULL,
  `contact_address` text DEFAULT NULL,
  `token_access` text DEFAULT NULL,
  `is_society` tinyint(1) DEFAULT NULL,
  `is_blacklisted` tinyint(1) DEFAULT NULL,
  `is_draft` tinyint(1) DEFAULT NULL,
  `enable_portal` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deposit`
--

DROP TABLE IF EXISTS `deposit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deposit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) NOT NULL,
  `deposit_date` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `equipment_name` varchar(255) NOT NULL,
  `intervention_number` varchar(255) NOT NULL,
  `equipment_state` int(11) NOT NULL,
  `about_state` text DEFAULT NULL,
  `equipment_accessories` text DEFAULT NULL,
  `is_signed` tinyint(1) DEFAULT NULL,
  `interventions_id` int(11) DEFAULT NULL,
  `customers_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `equipments_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `interventions_id` (`interventions_id`),
  KEY `customers_id` (`customers_id`),
  KEY `company_id` (`company_id`),
  KEY `equipments_id` (`equipments_id`),
  CONSTRAINT `deposit_ibfk_1` FOREIGN KEY (`interventions_id`) REFERENCES `interventions` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `deposit_ibfk_2` FOREIGN KEY (`customers_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `deposit_ibfk_3` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `deposit_ibfk_4` FOREIGN KEY (`equipments_id`) REFERENCES `equipments` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deposit`
--

LOCK TABLES `deposit` WRITE;
/*!40000 ALTER TABLE `deposit` DISABLE KEYS */;
/*!40000 ALTER TABLE `deposit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `edocuments`
--

DROP TABLE IF EXISTS `edocuments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edocuments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `size` varchar(255) DEFAULT NULL,
  `extension` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `is_online` tinyint(1) DEFAULT NULL,
  `interventions_id` int(11) DEFAULT NULL,
  `customers_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `interventions_id` (`interventions_id`),
  KEY `customers_id` (`customers_id`),
  CONSTRAINT `edocuments_ibfk_1` FOREIGN KEY (`interventions_id`) REFERENCES `interventions` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `edocuments_ibfk_2` FOREIGN KEY (`customers_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `edocuments`
--

LOCK TABLES `edocuments` WRITE;
/*!40000 ALTER TABLE `edocuments` DISABLE KEYS */;
/*!40000 ALTER TABLE `edocuments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipments`
--

DROP TABLE IF EXISTS `equipments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `about` longtext DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `e_serial` varchar(255) DEFAULT NULL,
  `e_year` varchar(255) DEFAULT NULL,
  `e_model` varchar(255) DEFAULT NULL,
  `e_licence` varchar(255) DEFAULT NULL,
  `end_guarantee` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `type_name` varchar(255) DEFAULT NULL,
  `brand_name` varchar(255) DEFAULT NULL,
  `os_name` varchar(255) DEFAULT NULL,
  `is_outofservice` tinyint(1) DEFAULT NULL,
  `c_install_date` varchar(255) DEFAULT NULL,
  `c_processor` varchar(255) DEFAULT NULL,
  `c_motherboard` varchar(255) DEFAULT NULL,
  `c_socket` varchar(255) DEFAULT NULL,
  `c_bios` varchar(255) DEFAULT NULL,
  `c_gpu` varchar(255) DEFAULT NULL,
  `c_memory` varchar(255) DEFAULT NULL,
  `c_hdd0` longtext DEFAULT NULL,
  `c_hdd1` longtext DEFAULT NULL,
  `c_hdd2` longtext DEFAULT NULL,
  `c_hdd3` longtext DEFAULT NULL,
  `c_software` longtext DEFAULT NULL,
  `n_ipaddress` varchar(255) DEFAULT NULL,
  `n_gateway` varchar(255) DEFAULT NULL,
  `n_masknetwork` varchar(255) DEFAULT NULL,
  `n_dns` varchar(255) DEFAULT NULL,
  `n_ssid` varchar(255) DEFAULT NULL,
  `n_wifi_key` varchar(255) DEFAULT NULL,
  `u_username` varchar(255) DEFAULT NULL,
  `u_account_mail` varchar(255) DEFAULT NULL,
  `u_password` varchar(255) DEFAULT NULL,
  `u_domain` varchar(255) DEFAULT NULL,
  `bao_temp_file` varchar(255) DEFAULT NULL,
  `customers_id` int(11) DEFAULT NULL,
  `brands_id` int(11) DEFAULT NULL,
  `types_equipments_id` int(11) DEFAULT NULL,
  `operating_systems_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `localization_site` mediumtext DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customers_id` (`customers_id`),
  KEY `brands_id` (`brands_id`),
  KEY `types_equipments_id` (`types_equipments_id`),
  KEY `operating_systems_id` (`operating_systems_id`),
  CONSTRAINT `equipments_ibfk_1` FOREIGN KEY (`customers_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `equipments_ibfk_2` FOREIGN KEY (`brands_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `equipments_ibfk_3` FOREIGN KEY (`types_equipments_id`) REFERENCES `types_equipments` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `equipments_ibfk_4` FOREIGN KEY (`operating_systems_id`) REFERENCES `operating_systems` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipments`
--

LOCK TABLES `equipments` WRITE;
/*!40000 ALTER TABLE `equipments` DISABLE KEYS */;
/*!40000 ALTER TABLE `equipments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `intervention_pictures`
--

DROP TABLE IF EXISTS `intervention_pictures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `intervention_pictures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `filesize` int(11) DEFAULT NULL,
  `picture_order` int(11) DEFAULT NULL,
  `interventions_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `interventions_id` (`interventions_id`),
  CONSTRAINT `intervention_pictures_ibfk_1` FOREIGN KEY (`interventions_id`) REFERENCES `interventions` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intervention_pictures`
--

LOCK TABLES `intervention_pictures` WRITE;
/*!40000 ALTER TABLE `intervention_pictures` DISABLE KEYS */;
/*!40000 ALTER TABLE `intervention_pictures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `interventions`
--

DROP TABLE IF EXISTS `interventions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `interventions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `sort` varchar(255) NOT NULL,
  `with_deposit` tinyint(1) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `before_breakdown` longtext DEFAULT NULL,
  `observation` longtext DEFAULT NULL,
  `note_technician` longtext DEFAULT NULL,
  `note_customer` longtext DEFAULT NULL,
  `handling_customer` longtext DEFAULT NULL,
  `message_report` longtext DEFAULT NULL,
  `actions_list` longtext DEFAULT NULL,
  `bao_report_file` text DEFAULT NULL,
  `way_type` varchar(255) DEFAULT NULL,
  `way_steps` int(11) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `equipment_name` varchar(255) DEFAULT NULL,
  `a_priority` varchar(255) DEFAULT NULL,
  `is_remote` tinyint(1) DEFAULT NULL,
  `ref_number` varchar(255) NOT NULL,
  `ref_for_link` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `package_price_name` varchar(255) DEFAULT NULL,
  `package_price` varchar(255) DEFAULT NULL,
  `total_time` int(11) DEFAULT NULL,
  `is_closed` tinyint(1) DEFAULT NULL,
  `diag_cpu` varchar(255) DEFAULT NULL,
  `diag_gpu` varchar(255) DEFAULT NULL,
  `diag_memory` varchar(255) DEFAULT NULL,
  `diag_storage` varchar(255) DEFAULT NULL,
  `customers_id` int(11) DEFAULT NULL,
  `equipments_id` int(11) DEFAULT NULL,
  `users_id` int(11) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `deposit_date` date DEFAULT NULL,
  `pull_date` datetime DEFAULT NULL,
  `cancel_date` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customers_id` (`customers_id`),
  KEY `equipments_id` (`equipments_id`),
  KEY `users_id` (`users_id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `interventions_ibfk_1` FOREIGN KEY (`customers_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `interventions_ibfk_2` FOREIGN KEY (`equipments_id`) REFERENCES `equipments` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `interventions_ibfk_3` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `interventions_ibfk_4` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `interventions`
--

LOCK TABLES `interventions` WRITE;
/*!40000 ALTER TABLE `interventions` DISABLE KEYS */;
/*!40000 ALTER TABLE `interventions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operating_systems`
--

DROP TABLE IF EXISTS `operating_systems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `operating_systems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `os_name` varchar(255) NOT NULL,
  `os_release` varchar(255) DEFAULT NULL,
  `os_architecture` varchar(255) DEFAULT NULL,
  `os_about` text DEFAULT NULL,
  `os_full` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operating_systems`
--

LOCK TABLES `operating_systems` WRITE;
/*!40000 ALTER TABLE `operating_systems` DISABLE KEYS */;
INSERT INTO `operating_systems` VALUES
(1,'Windows 11 Home','22H2','x64','N/C','Windows 11 Home - 22H2 - x64'),
(2,'Windows 11 Pro','22H2','x64','N/C','Windows 11 Home - 22H2 - x64'),
(3,'Windows 10 Home','1909','x64','N/C','Windows 10 Home - 1909 - x64'),
(4,'Windows 10 Pro','1909','x64','N/C','Windows 10 Pro - 1909 - x64'),
(5,'Windows 10 Home','2004','x64','N/C','Windows 10 Home - 2004 - x64'),
(6,'Windows 10 Pro','2004','x64','N/C','Windows 10 Pro - 2004 - x64'),
(7,'Windows 8.1','8.1','x64','N/C','Windows 8.1 - 8.1 - x64'),
(8,'Windows 7 Pro','SP1','x64','N/C','Windows 7 Pro - SP1 - x64'),
(9,'Windows 7 Édition Familiale Basique','SP1','x64','N/C','Windows 7 Édition Familiale Basique - SP1 - x64'),
(10,'Windows 7 Édition Starter','SP1','x64','N/C','Windows 7 Édition Starter - SP1 - x64'),
(11,'Windows 7 Édition Intégrale','SP1','x64','N/C','Windows 7 Édition Intégrale - SP1 - x64'),
(12,'Windows XP Professionnel','SP3','x64','N/C','Windows XP Professionnel - SP3 - x64'),
(13,'Windows XP Professionnel','SP3','x86','N/C','Windows XP Professionnel - SP3 - x86'),
(14,'Windows XP Familiale','SP3','x64','N/C','Windows XP Familiale - SP3 - x64'),
(15,'Windows XP Familiale','SP3','x86','N/C','Windows XP Familiale - SP3 - x86'),
(16,'Linux Mint','20 (Ulyana)','x64','N/C','Linux Mint - 20 (Ulyana) - x64'),
(17,'Elementary OS','5.1.6 (Hera)','x64','N/C','Elementary OS - 5.1.6 (Hera) - x64'),
(18,'Ubuntu','20.04 (Focal)','x64','N/C','Ubuntu - 20.04 (Focal) - x64'),
(19,'Xubuntu','20.04 (Focal Fossa)','x64','N/C','Xubuntu - 20.04 (Focal Fossa) - x64'),
(20,'Xubuntu Eoan','19.10 (Eoan Ermine)','x64','N/C','Xubuntu - 19.10 (Eoan Ermine) - x64'),
(21,'Pop!_OS','20.04','x64','N/C','Pop!_OS - 20.04 - x64'),
(22,'Manjaro','20.0 (Lysia)','x64','N/C','Manjaro - 20.0 (Lysia) - x64'),
(23,'Arch Linux','2020.08.01','x64','N/C','Arch Linux - 2020.08.01 - x64'),
(24,'Debian','10.5 (Buster)','x64','N/C','Debian - 10.5 (Buster) - x64'),
(25,'Fedora','33','x64','N/C','Fedora - 33 - x64'),
(26,'macOS','10.15.6','x64','N/C','macOS - 10.15.6 - x64'),
(27,'Android','5.0.x (Lollipop)','x64','N/C','Android - 5.0.x (Lollipop) - x64'),
(28,'Android 9','9.0.x (Pie)','x64','N/C','Android 9 - 9.0.x (Pie) - x64'),
(29,'Android 10','10 (Android 10)','x64','N/C','Android 10 - 10 (Android 10) - x64');
/*!40000 ALTER TABLE `operating_systems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `outlay`
--

DROP TABLE IF EXISTS `outlay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `outlay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `refund` date DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `reference_propal` varchar(255) DEFAULT NULL,
  `reference_intervention` varchar(255) DEFAULT NULL,
  `is_closed` tinyint(1) DEFAULT NULL,
  `seller` varchar(255) DEFAULT NULL,
  `products` longtext DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `customers_id` int(11) DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT NULL,
  `users_id` int(11) DEFAULT NULL,
  `code_sign` longtext DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `customers_id` (`customers_id`),
  KEY `users_id` (`users_id`),
  CONSTRAINT `outlay_ibfk_1` FOREIGN KEY (`customers_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `outlay_ibfk_2` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `outlay`
--

LOCK TABLES `outlay` WRITE;
/*!40000 ALTER TABLE `outlay` DISABLE KEYS */;
/*!40000 ALTER TABLE `outlay` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `phinxlog`
--

DROP TABLE IF EXISTS `phinxlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phinxlog` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `breakpoint` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `phinxlog`
--

LOCK TABLES `phinxlog` WRITE;
/*!40000 ALTER TABLE `phinxlog` DISABLE KEYS */;
INSERT INTO `phinxlog` VALUES
(20220828175939,'UserCreateModel','2024-06-23 09:22:50','2024-06-23 09:22:50',0),
(20220903180456,'CustomerCreateModel','2024-06-23 09:22:50','2024-06-23 09:22:50',0),
(20220921105859,'BrandCreateModel','2024-06-23 09:22:50','2024-06-23 09:22:50',0),
(20220921110736,'TypeEquipmentCreateModel','2024-06-23 09:22:50','2024-06-23 09:22:50',0),
(20220921170532,'OperatingSystemCreateModel','2024-06-23 09:22:50','2024-06-23 09:22:50',0),
(20220921170726,'EquipmentCreateModel','2024-06-23 09:22:50','2024-06-23 09:22:50',0),
(20221004090154,'InterventionsCreateModel','2024-06-23 09:22:50','2024-06-23 09:22:50',0),
(20231110182254,'TasksCreateModel','2024-06-23 09:22:50','2024-06-23 09:22:50',0),
(20231117111036,'CompanyCreateModel','2024-06-23 09:22:50','2024-06-23 09:22:50',0),
(20231126125720,'UsersToCompanyModel','2024-06-23 09:22:50','2024-06-23 09:22:50',0),
(20231128145825,'InterventionsToCompanyModel','2024-06-23 09:22:50','2024-06-23 09:22:50',0),
(20231128175938,'StatusModel','2024-06-23 09:22:50','2024-06-23 09:22:50',0),
(20240105180816,'DepositModel','2024-06-23 09:22:50','2024-06-23 09:22:50',0),
(20240414170620,'StatsModel','2024-06-23 09:22:50','2024-06-23 09:22:50',0),
(20240423143222,'EquipmentUpdateModel','2024-06-23 09:22:50','2024-06-23 09:22:50',0),
(20240426172736,'OutlayCreateModel','2024-06-23 09:22:50','2024-06-23 09:22:50',0),
(20240520150151,'BookingCreateModel','2024-06-23 09:22:50','2024-06-23 09:22:50',0),
(20240613161220,'EdocumentCreateModel','2024-06-23 09:22:50','2024-06-23 09:22:50',0),
(20240621104647,'InterventionPicturesCreateModel','2024-06-23 09:22:50','2024-06-23 09:22:50',0);
/*!40000 ALTER TABLE `phinxlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `statistics`
--

DROP TABLE IF EXISTS `statistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inter_not_started` int(11) DEFAULT NULL,
  `inter_in_workshop` int(11) DEFAULT NULL,
  `inter_final_test` int(11) DEFAULT NULL,
  `inter_exit_waiting` int(11) DEFAULT NULL,
  `inter_total` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statistics`
--

LOCK TABLES `statistics` WRITE;
/*!40000 ALTER TABLE `statistics` DISABLE KEYS */;
/*!40000 ALTER TABLE `statistics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `fixed` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status`
--

LOCK TABLES `status` WRITE;
/*!40000 ALTER TABLE `status` DISABLE KEYS */;
INSERT INTO `status` VALUES
(1,'En traitement',NULL,1),
(2,'En attente de récupération',NULL,1),
(3,'Réparé',NULL,1),
(4,'Devis approuvé',NULL,1),
(5,'Devis refusé',NULL,1);
/*!40000 ALTER TABLE `status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` float DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` VALUES
(1,'Tentative de réinitialisation',NULL,NULL),
(2,'Mises à jour Windows',NULL,NULL),
(3,'Installation des logiciels basiques',NULL,NULL),
(4,'Mise en place SSD',NULL,NULL),
(5,'Installation Windows 10',NULL,NULL),
(6,'Activation de Windows 10',NULL,NULL),
(7,'Récupération de données',NULL,NULL),
(8,'Formatage HDD',NULL,NULL),
(9,'Installation/Activation Office 2021 Pro Plus',NULL,NULL),
(10,'Nettoyage du boitier',NULL,NULL),
(11,'Restauration sauvegarde HDD vers SSD',NULL,NULL),
(12,'Nettoyage ventirad CPU',NULL,NULL),
(13,'Sauvegarde des données utilisateur',NULL,NULL),
(14,'Premier test de démarrage',NULL,NULL),
(15,'Installation des mises à jour et pilotes',NULL,NULL);
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks_assoc`
--

DROP TABLE IF EXISTS `tasks_assoc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tasks_assoc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tasks_id` int(11) NOT NULL,
  `interventions_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tasks_id` (`tasks_id`),
  KEY `interventions_id` (`interventions_id`),
  CONSTRAINT `tasks_assoc_ibfk_1` FOREIGN KEY (`tasks_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `tasks_assoc_ibfk_2` FOREIGN KEY (`interventions_id`) REFERENCES `interventions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks_assoc`
--

LOCK TABLES `tasks_assoc` WRITE;
/*!40000 ALTER TABLE `tasks_assoc` DISABLE KEYS */;
/*!40000 ALTER TABLE `tasks_assoc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `types_equipments`
--

DROP TABLE IF EXISTS `types_equipments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `types_equipments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `is_peripheral` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `types_equipments`
--

LOCK TABLES `types_equipments` WRITE;
/*!40000 ALTER TABLE `types_equipments` DISABLE KEYS */;
INSERT INTO `types_equipments` VALUES
(1,'Ordinateur de bureau','desktop',NULL),
(2,'Ordinateur portable','laptop',NULL),
(3,'Serveur','server',NULL),
(4,'Smartphone','smartphone',NULL),
(5,'Tablette','tablet',NULL),
(6,'Imprimante','printer',1),
(7,'Scanner','scan',1),
(8,'SSD Externe','external-ssd',1),
(9,'HDD Externe','external-hdd',1);
/*!40000 ALTER TABLE `types_equipments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `password` longtext DEFAULT NULL,
  `key_totp` varchar(255) DEFAULT NULL,
  `lastvisite` date DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `resetpassword` varchar(255) DEFAULT NULL,
  `confirm_at` varchar(255) DEFAULT NULL,
  `address_ip` varchar(255) DEFAULT NULL,
  `token` text DEFAULT NULL,
  `auth_token` text DEFAULT NULL,
  `reset_at` datetime DEFAULT NULL,
  `reset_code` longtext DEFAULT NULL,
  `roles` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mail` (`mail`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'admin','admin@admin.tld','John Doe','$2y$10$ZebWxA7.cq4GV1YhGtWFSOmcDKaLh8UtXVKGSEFqvrnjPU6l/dKxy',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'SUPER_ADMIN','2024-03-25 04:21:52','2024-03-25 04:21:52',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-06-23 13:28:05
