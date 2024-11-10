-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : dim. 10 nov. 2024 à 10:34
-- Version du serveur : 10.11.6-MariaDB-0+deb12u1
-- Version de PHP : 8.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `selmak_v2xproaxive`
--

-- --------------------------------------------------------

--
-- Structure de la table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
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
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `logo_link` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `logo_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `brands`
--

INSERT INTO `brands` (`id`, `name`, `logo_link`, `slug`, `logo_file`) VALUES
(1, 'Acer', NULL, 'acer', 'pe190873615-acer.png'),
(2, 'Asus', NULL, 'asus', 'pe59894369-asus.png'),
(3, 'Apple', NULL, 'apple', 'pe938389281-apple.png'),
(4, 'Brother', NULL, 'brother', 'pe847625144-brother.png'),
(5, 'Canon', NULL, 'canon', 'pe303630207-canon.png'),
(6, 'Compaq', NULL, 'compaq', 'pe762190526-compaq.png'),
(7, 'Cisco', NULL, 'cisco', 'pe705228956-Cisco.png'),
(8, 'Dell', NULL, 'dell', 'pe279357682-dell.png'),
(9, 'Epson', NULL, 'epson', 'pe47222901-epson.png'),
(10, 'HP', NULL, 'hp', 'pe826297485-hp.png'),
(11, 'Lenovo', NULL, 'lenovo', 'pe718666919-lenovo.png'),
(12, 'Packard Bell', NULL, 'packard-bell', 'pe381036515-packard-bell.png'),
(13, 'Samsung', NULL, 'samsung', 'pe658144462-samsung.png'),
(14, 'Sony', NULL, 'sony', 'pe441648698-sony.png'),
(15, 'Xiaomi', NULL, 'xiaomi', 'pe197696821-xiaomi.png');

-- --------------------------------------------------------

--
-- Structure de la table `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
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
  `open_days` varchar(255) DEFAULT 'Du lundi au vendredi',
  `open_hours` varchar(255) DEFAULT '9H - 12H / 14H - 18H'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `company`
--

INSERT INTO `company` (`id`, `name`, `about`, `type`, `address`, `city`, `country`, `zipcode`, `department`, `addr_longitude`, `addr_latitude`, `phone`, `mobile`, `fax`, `phone_indicatif`, `director`, `website`, `mail`, `siret`, `ape`, `aprm`, `rm`, `rcs`, `rc_pro`, `tva`, `facebook`, `twitter`, `instagram`, `linkedin`, `logo`, `signature`, `isdefault`, `created_at`, `updated_at`, `open_days`, `open_hours`) VALUES
(1, 'Mon entreprise', NULL, 'EURL', 'Mon adresse postale', NULL, 'France', NULL, NULL, NULL, NULL, '0102030405', NULL, NULL, '+33', 'John Doe', NULL, 'myname@myenterprise.fr', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2024-11-10 09:37:54', '2024-11-10 09:37:54', 'Du lundi au vendredi', '9H - 12H / 14H - 18H');

-- --------------------------------------------------------

--
-- Structure de la table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
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
  `lastname` varchar(100) DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `civility` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `deposit`
--

CREATE TABLE `deposit` (
  `id` int(11) NOT NULL,
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
  `equipments_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `edocuments`
--

CREATE TABLE `edocuments` (
  `id` int(11) NOT NULL,
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
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `equipments`
--

CREATE TABLE `equipments` (
  `id` int(11) NOT NULL,
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
  `picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `interventions`
--

CREATE TABLE `interventions` (
  `id` int(11) NOT NULL,
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
  `deposit_reference` varchar(100) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `interventions_history`
--

CREATE TABLE `interventions_history` (
  `id` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `type` varchar(255) NOT NULL,
  `interventions_id` int(11) DEFAULT NULL,
  `users_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `intervention_pictures`
--

CREATE TABLE `intervention_pictures` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filesize` int(11) DEFAULT NULL,
  `picture_order` int(11) DEFAULT NULL,
  `interventions_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `is_online` tinyint(1) DEFAULT 0,
  `name` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inventory_materials_mod`
--

CREATE TABLE `inventory_materials_mod` (
  `id` int(11) NOT NULL,
  `code_parc` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `model` varchar(255) DEFAULT NULL,
  `serial_number` longtext DEFAULT NULL,
  `uc_price` varchar(255) NOT NULL,
  `code_snid` varchar(255) DEFAULT NULL,
  `anydesk` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `accessories` longtext DEFAULT NULL,
  `has_antivirus` tinyint(1) DEFAULT NULL,
  `has_moffice` tinyint(1) DEFAULT NULL,
  `available` tinyint(1) DEFAULT NULL,
  `comment` longtext DEFAULT NULL,
  `rental_type` varchar(255) DEFAULT NULL,
  `instock` tinyint(1) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `restitution_date` date DEFAULT NULL,
  `customers_id` int(11) DEFAULT NULL,
  `brands_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inventory_product_item_mod`
--

CREATE TABLE `inventory_product_item_mod` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `attachment_file` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `code_product` varchar(255) DEFAULT NULL,
  `inventory_materials_mod_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `stick` int(11) DEFAULT NULL,
  `high` int(11) DEFAULT NULL,
  `archived` int(11) DEFAULT NULL,
  `bgcolor` varchar(255) DEFAULT NULL,
  `txtcolor` varchar(255) DEFAULT NULL,
  `users_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `operating_systems`
--

CREATE TABLE `operating_systems` (
  `id` int(11) NOT NULL,
  `os_name` varchar(255) NOT NULL,
  `os_release` varchar(255) DEFAULT NULL,
  `os_architecture` varchar(255) DEFAULT NULL,
  `os_about` text DEFAULT NULL,
  `os_full` varchar(255) DEFAULT NULL,
  `os_order` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `operating_systems`
--

INSERT INTO `operating_systems` (`id`, `os_name`, `os_release`, `os_architecture`, `os_about`, `os_full`, `os_order`) VALUES
(1, 'Windows 11 Home', '22H2', 'x64', 'N/C', 'Windows 11 Home - 22H2 - x64', 1),
(2, 'Windows 11 Pro', '22H2', 'x64', 'N/C', 'Windows 11 Home - 22H2 - x64', 1),
(3, 'Windows 10 Home', '1909', 'x64', 'N/C', 'Windows 10 Home - 1909 - x64', 1),
(4, 'Windows 10 Pro', '1909', 'x64', 'N/C', 'Windows 10 Pro - 1909 - x64', 1),
(5, 'Windows 10 Home', '2004', 'x64', 'N/C', 'Windows 10 Home - 2004 - x64', 1),
(6, 'Windows 10 Pro', '2004', 'x64', 'N/C', 'Windows 10 Pro - 2004 - x64', 1),
(7, 'Windows 8.1', '8.1', 'x64', 'N/C', 'Windows 8.1 - 8.1 - x64', 1),
(8, 'Windows 7 Pro', 'SP1', 'x64', 'N/C', 'Windows 7 Pro - SP1 - x64', 1),
(9, 'Windows 7 Édition Familiale Basique', 'SP1', 'x64', 'N/C', 'Windows 7 Édition Familiale Basique - SP1 - x64', 1),
(10, 'Windows 7 Édition Starter', 'SP1', 'x64', 'N/C', 'Windows 7 Édition Starter - SP1 - x64', 1),
(11, 'Windows 7 Édition Intégrale', 'SP1', 'x64', 'N/C', 'Windows 7 Édition Intégrale - SP1 - x64', 1),
(12, 'Windows XP Professionnel', 'SP3', 'x64', 'N/C', 'Windows XP Professionnel - SP3 - x64', 1),
(13, 'Windows XP Professionnel', 'SP3', 'x86', 'N/C', 'Windows XP Professionnel - SP3 - x86', 1),
(14, 'Windows XP Familiale', 'SP3', 'x64', 'N/C', 'Windows XP Familiale - SP3 - x64', 1),
(15, 'Windows XP Familiale', 'SP3', 'x86', 'N/C', 'Windows XP Familiale - SP3 - x86', 1),
(16, 'Linux Mint', '20 (Ulyana)', 'x64', 'N/C', 'Linux Mint - 20 (Ulyana) - x64', 1),
(17, 'Elementary OS', '5.1.6 (Hera)', 'x64', 'N/C', 'Elementary OS - 5.1.6 (Hera) - x64', 1),
(18, 'Ubuntu', '20.04 (Focal)', 'x64', 'N/C', 'Ubuntu - 20.04 (Focal) - x64', 1),
(19, 'Xubuntu', '20.04 (Focal Fossa)', 'x64', 'N/C', 'Xubuntu - 20.04 (Focal Fossa) - x64', 1),
(20, 'Xubuntu Eoan', '19.10 (Eoan Ermine)', 'x64', 'N/C', 'Xubuntu - 19.10 (Eoan Ermine) - x64', 1),
(21, 'Pop!_OS', '20.04', 'x64', 'N/C', 'Pop!_OS - 20.04 - x64', 1),
(22, 'Manjaro', '20.0 (Lysia)', 'x64', 'N/C', 'Manjaro - 20.0 (Lysia) - x64', 1),
(23, 'Arch Linux', '2020.08.01', 'x64', 'N/C', 'Arch Linux - 2020.08.01 - x64', 1),
(24, 'Debian', '10.5 (Buster)', 'x64', 'N/C', 'Debian - 10.5 (Buster) - x64', 1),
(25, 'Fedora', '33', 'x64', 'N/C', 'Fedora - 33 - x64', 1),
(26, 'macOS', '10.15.6', 'x64', 'N/C', 'macOS - 10.15.6 - x64', 1),
(27, 'Android', '5.0.x (Lollipop)', 'x64', 'N/C', 'Android - 5.0.x (Lollipop) - x64', 1),
(28, 'Android 9', '9.0.x (Pie)', 'x64', 'N/C', 'Android 9 - 9.0.x (Pie) - x64', 1),
(29, 'Android 10', '10 (Android 10)', 'x64', 'N/C', 'Android 10 - 10 (Android 10) - x64', 1);

-- --------------------------------------------------------

--
-- Structure de la table `outlay`
--

CREATE TABLE `outlay` (
  `id` int(11) NOT NULL,
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
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `phinxlog`
--

CREATE TABLE `phinxlog` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `breakpoint` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `phinxlog`
--

INSERT INTO `phinxlog` (`version`, `migration_name`, `start_time`, `end_time`, `breakpoint`) VALUES
(20220828175939, 'UserCreateModel', '2024-11-10 08:37:43', '2024-11-10 08:37:43', 0),
(20220903180456, 'CustomerCreateModel', '2024-11-10 08:37:43', '2024-11-10 08:37:43', 0),
(20220921105859, 'BrandCreateModel', '2024-11-10 08:37:43', '2024-11-10 08:37:43', 0),
(20220921110736, 'TypeEquipmentCreateModel', '2024-11-10 08:37:43', '2024-11-10 08:37:43', 0),
(20220921170532, 'OperatingSystemCreateModel', '2024-11-10 08:37:43', '2024-11-10 08:37:43', 0),
(20220921170726, 'EquipmentCreateModel', '2024-11-10 08:37:43', '2024-11-10 08:37:43', 0),
(20221004090154, 'InterventionsCreateModel', '2024-11-10 08:37:43', '2024-11-10 08:37:43', 0),
(20231110182254, 'TasksCreateModel', '2024-11-10 08:37:43', '2024-11-10 08:37:43', 0),
(20231117111036, 'CompanyCreateModel', '2024-11-10 08:37:43', '2024-11-10 08:37:43', 0),
(20231126125720, 'UsersToCompanyModel', '2024-11-10 08:37:43', '2024-11-10 08:37:43', 0),
(20231128145825, 'InterventionsToCompanyModel', '2024-11-10 08:37:43', '2024-11-10 08:37:43', 0),
(20231128175938, 'StatusModel', '2024-11-10 08:37:43', '2024-11-10 08:37:43', 0),
(20240105180816, 'DepositModel', '2024-11-10 08:37:43', '2024-11-10 08:37:43', 0),
(20240414170620, 'StatsModel', '2024-11-10 08:37:43', '2024-11-10 08:37:43', 0),
(20240423143222, 'EquipmentUpdateModel', '2024-11-10 08:37:43', '2024-11-10 08:37:43', 0),
(20240426172736, 'OutlayCreateModel', '2024-11-10 08:37:43', '2024-11-10 08:37:43', 0),
(20240520150151, 'BookingCreateModel', '2024-11-10 08:37:43', '2024-11-10 08:37:43', 0),
(20240613161220, 'EdocumentCreateModel', '2024-11-10 08:37:43', '2024-11-10 08:37:43', 0),
(20240621104647, 'InterventionPicturesCreateModel', '2024-11-10 08:37:43', '2024-11-10 08:37:43', 0),
(20240625101624, 'Update204Model', '2024-11-10 08:37:43', '2024-11-10 08:37:43', 0),
(20240630122946, 'Update205Model', '2024-11-10 08:37:43', '2024-11-10 08:37:44', 0),
(20240717081001, 'Update206Model', '2024-11-10 08:37:44', '2024-11-10 08:37:44', 0),
(20240908153847, 'TypeInterventionUpdate207Model', '2024-11-10 08:37:44', '2024-11-10 08:37:44', 0),
(20240909160712, 'Update207Model', '2024-11-10 08:37:44', '2024-11-10 08:37:44', 0),
(20240919082354, 'NoteUpdate208Model', '2024-11-10 08:37:44', '2024-11-10 08:37:44', 0),
(20240919082759, 'Update208Model', '2024-11-10 08:37:44', '2024-11-10 08:37:44', 0),
(20241003105406, 'InventoryMaterialsModCreateModel', '2024-11-10 08:37:44', '2024-11-10 08:37:44', 0);

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `app_version` varchar(20) DEFAULT NULL,
  `is_beta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `settings`
--

INSERT INTO `settings` (`id`, `app_version`, `is_beta`) VALUES
(1, '2.0.8', 1);

-- --------------------------------------------------------

--
-- Structure de la table `statistics`
--

CREATE TABLE `statistics` (
  `id` int(11) NOT NULL,
  `inter_not_started` int(11) DEFAULT NULL,
  `inter_in_workshop` int(11) DEFAULT NULL,
  `inter_final_test` int(11) DEFAULT NULL,
  `inter_exit_waiting` int(11) DEFAULT NULL,
  `inter_total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `fixed` tinyint(1) NOT NULL,
  `description` longtext DEFAULT NULL,
  `color_txt` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `status`
--

INSERT INTO `status` (`id`, `name`, `color`, `fixed`, `description`, `color_txt`) VALUES
(1, 'En traitement', NULL, 1, NULL, NULL),
(2, 'En attente de récupération', NULL, 1, NULL, NULL),
(3, 'En attente', NULL, 1, NULL, NULL),
(4, 'Complété(e)', NULL, 1, NULL, NULL),
(5, 'Annulé(e)', NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` float DEFAULT NULL,
  `description` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `tasks`
--

INSERT INTO `tasks` (`id`, `name`, `price`, `description`) VALUES
(1, 'Tentative de réinitialisation', NULL, NULL),
(2, 'Mises à jour Windows', NULL, NULL),
(3, 'Installation des logiciels basiques', NULL, NULL),
(4, 'Mise en place SSD', NULL, NULL),
(5, 'Installation Windows 10', NULL, NULL),
(6, 'Activation de Windows 10', NULL, NULL),
(7, 'Récupération de données', NULL, NULL),
(8, 'Formatage HDD', NULL, NULL),
(9, 'Installation/Activation Office 2021 Pro Plus', NULL, NULL),
(10, 'Nettoyage du boitier', NULL, NULL),
(11, 'Restauration sauvegarde HDD vers SSD', NULL, NULL),
(12, 'Nettoyage ventirad CPU', NULL, NULL),
(13, 'Sauvegarde des données utilisateur', NULL, NULL),
(14, 'Premier test de démarrage', NULL, NULL),
(15, 'Installation des mises à jour et pilotes', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `tasks_assoc`
--

CREATE TABLE `tasks_assoc` (
  `id` int(11) NOT NULL,
  `tasks_id` int(11) NOT NULL,
  `interventions_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `types_equipments`
--

CREATE TABLE `types_equipments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `is_peripheral` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `types_equipments`
--

INSERT INTO `types_equipments` (`id`, `name`, `slug`, `is_peripheral`) VALUES
(1, 'Ordinateur de bureau', 'desktop', NULL),
(2, 'Ordinateur portable', 'laptop', NULL),
(3, 'Serveur', 'server', NULL),
(4, 'Smartphone', 'smartphone', NULL),
(5, 'Tablette', 'tablet', NULL),
(6, 'Imprimante', 'printer', 1),
(7, 'Scanner', 'scan', 1),
(8, 'SSD Externe', 'external-ssd', 1),
(9, 'HDD Externe', 'external-hdd', 1);

-- --------------------------------------------------------

--
-- Structure de la table `types_interventions`
--

CREATE TABLE `types_interventions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `types_interventions`
--

INSERT INTO `types_interventions` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Dépannage', NULL, '2024-09-08 18:21:52', '2024-09-08 18:21:52'),
(2, 'Réparation', NULL, '2024-09-08 18:21:52', '2024-09-08 18:21:52'),
(3, 'Visite périodique', NULL, '2024-09-08 18:21:52', '2024-09-08 18:21:52'),
(4, 'Préparation de poste', NULL, '2024-09-08 18:21:52', '2024-09-08 18:21:52'),
(5, 'Assemblage', NULL, '2024-09-08 18:21:52', '2024-09-08 18:21:52'),
(6, 'Expertise', NULL, '2024-09-08 18:21:52', '2024-09-08 18:21:52'),
(7, 'Devis', NULL, '2024-09-08 18:21:52', '2024-09-08 18:21:52');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
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
  `company_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `pseudo`, `mail`, `fullname`, `password`, `key_totp`, `lastvisite`, `avatar`, `resetpassword`, `confirm_at`, `address_ip`, `token`, `auth_token`, `reset_at`, `reset_code`, `roles`, `created_at`, `updated_at`, `company_id`) VALUES
(1, 'admin', 'admin@proaxive.in', 'John Doe', '$2y$10$YUGO1sTrg5b1hLdni9/Ur.dwvYG8JC7cGm0Qsgs/gWUKEMgykM6IO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SUPER_ADMIN', '2024-11-10 10:45:19', '2024-11-10 10:58:50', 1),
(2, 'Tech', 'tech@proaxive.in', 'Technicien', '$2y$10$Ynn2FloS9HuUozQX88pm5e4.2yq3SClmLDjoj2P6XiqR26buTBZ3C', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'USER_TECH', '2024-11-10 10:50:27', '2024-11-10 10:53:57', 1),
(3, 'Manager', 'manager@proaxive.in', 'Manager', '$2y$10$TcWHI7EO9Hvcsr4G3BRcw.QFwqirl5N42RCC3c5cye.HSki/6POFm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'USER_MANAGER', '2024-11-10 10:52:36', '2024-11-10 10:54:22', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `deposit`
--
ALTER TABLE `deposit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `customers_id` (`customers_id`),
  ADD KEY `interventions_id` (`interventions_id`),
  ADD KEY `equipments_id` (`equipments_id`);

--
-- Index pour la table `edocuments`
--
ALTER TABLE `edocuments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_id` (`customers_id`),
  ADD KEY `interventions_id` (`interventions_id`);

--
-- Index pour la table `equipments`
--
ALTER TABLE `equipments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brands_id` (`brands_id`),
  ADD KEY `types_equipments_id` (`types_equipments_id`),
  ADD KEY `operating_systems_id` (`operating_systems_id`),
  ADD KEY `customers_id` (`customers_id`);

--
-- Index pour la table `interventions`
--
ALTER TABLE `interventions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`users_id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `customers_id` (`customers_id`),
  ADD KEY `equipments_id` (`equipments_id`);

--
-- Index pour la table `interventions_history`
--
ALTER TABLE `interventions_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `interventions_id` (`interventions_id`),
  ADD KEY `users_id` (`users_id`);

--
-- Index pour la table `intervention_pictures`
--
ALTER TABLE `intervention_pictures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `interventions_id` (`interventions_id`);

--
-- Index pour la table `inventory_materials_mod`
--
ALTER TABLE `inventory_materials_mod`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_id` (`customers_id`),
  ADD KEY `brands_id` (`brands_id`);

--
-- Index pour la table `inventory_product_item_mod`
--
ALTER TABLE `inventory_product_item_mod`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_materials_mod_id` (`inventory_materials_mod_id`);

--
-- Index pour la table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`users_id`),
  ADD KEY `company_id` (`company_id`);

--
-- Index pour la table `operating_systems`
--
ALTER TABLE `operating_systems`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `outlay`
--
ALTER TABLE `outlay`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`users_id`),
  ADD KEY `customers_id` (`customers_id`);

--
-- Index pour la table `phinxlog`
--
ALTER TABLE `phinxlog`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `statistics`
--
ALTER TABLE `statistics`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tasks_assoc`
--
ALTER TABLE `tasks_assoc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_id` (`tasks_id`),
  ADD KEY `interventions_id` (`interventions_id`);

--
-- Index pour la table `types_equipments`
--
ALTER TABLE `types_equipments`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `types_interventions`
--
ALTER TABLE `types_interventions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mail` (`mail`),
  ADD KEY `company_id` (`company_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `deposit`
--
ALTER TABLE `deposit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `edocuments`
--
ALTER TABLE `edocuments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `equipments`
--
ALTER TABLE `equipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `interventions`
--
ALTER TABLE `interventions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `interventions_history`
--
ALTER TABLE `interventions_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `intervention_pictures`
--
ALTER TABLE `intervention_pictures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `inventory_materials_mod`
--
ALTER TABLE `inventory_materials_mod`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `inventory_product_item_mod`
--
ALTER TABLE `inventory_product_item_mod`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `operating_systems`
--
ALTER TABLE `operating_systems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `outlay`
--
ALTER TABLE `outlay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `statistics`
--
ALTER TABLE `statistics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `tasks_assoc`
--
ALTER TABLE `tasks_assoc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `types_equipments`
--
ALTER TABLE `types_equipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `types_interventions`
--
ALTER TABLE `types_interventions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `deposit`
--
ALTER TABLE `deposit`
  ADD CONSTRAINT `deposit_ibfk_3` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `deposit_ibfk_4` FOREIGN KEY (`customers_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `deposit_ibfk_5` FOREIGN KEY (`interventions_id`) REFERENCES `interventions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `deposit_ibfk_6` FOREIGN KEY (`equipments_id`) REFERENCES `equipments` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `edocuments`
--
ALTER TABLE `edocuments`
  ADD CONSTRAINT `edocuments_ibfk_1` FOREIGN KEY (`customers_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `edocuments_ibfk_2` FOREIGN KEY (`interventions_id`) REFERENCES `interventions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `equipments`
--
ALTER TABLE `equipments`
  ADD CONSTRAINT `equipments_ibfk_2` FOREIGN KEY (`brands_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `equipments_ibfk_3` FOREIGN KEY (`types_equipments_id`) REFERENCES `types_equipments` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `equipments_ibfk_4` FOREIGN KEY (`operating_systems_id`) REFERENCES `operating_systems` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `equipments_ibfk_5` FOREIGN KEY (`customers_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `interventions`
--
ALTER TABLE `interventions`
  ADD CONSTRAINT `interventions_ibfk_3` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `interventions_ibfk_4` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `interventions_ibfk_5` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `interventions_ibfk_6` FOREIGN KEY (`customers_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `interventions_ibfk_7` FOREIGN KEY (`equipments_id`) REFERENCES `equipments` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `interventions_history`
--
ALTER TABLE `interventions_history`
  ADD CONSTRAINT `interventions_history_ibfk_1` FOREIGN KEY (`interventions_id`) REFERENCES `interventions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `interventions_history_ibfk_2` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `intervention_pictures`
--
ALTER TABLE `intervention_pictures`
  ADD CONSTRAINT `intervention_pictures_ibfk_1` FOREIGN KEY (`interventions_id`) REFERENCES `interventions` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Contraintes pour la table `inventory_materials_mod`
--
ALTER TABLE `inventory_materials_mod`
  ADD CONSTRAINT `inventory_materials_mod_ibfk_1` FOREIGN KEY (`customers_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `inventory_materials_mod_ibfk_2` FOREIGN KEY (`brands_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Contraintes pour la table `inventory_product_item_mod`
--
ALTER TABLE `inventory_product_item_mod`
  ADD CONSTRAINT `inventory_product_item_mod_ibfk_1` FOREIGN KEY (`inventory_materials_mod_id`) REFERENCES `inventory_materials_mod` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Contraintes pour la table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `notes_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `outlay`
--
ALTER TABLE `outlay`
  ADD CONSTRAINT `outlay_ibfk_2` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `outlay_ibfk_3` FOREIGN KEY (`customers_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `tasks_assoc`
--
ALTER TABLE `tasks_assoc`
  ADD CONSTRAINT `tasks_assoc_ibfk_1` FOREIGN KEY (`tasks_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `tasks_assoc_ibfk_2` FOREIGN KEY (`interventions_id`) REFERENCES `interventions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
