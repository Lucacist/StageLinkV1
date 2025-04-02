# 🎓 StageLink - Plateforme de gestion des stages
![HTML5](https://img.shields.io/badge/html5-%23E34F26.svg?style=for-the-badge&logo=html5&logoColor=white)
![JavaScript](https://img.shields.io/badge/javascript-%23323330.svg?style=for-the-badge&logo=javascript&logoColor=%23F7DF1E)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![CSS3](https://img.shields.io/badge/css3-%231572B6.svg?style=for-the-badge&logo=css3&logoColor=white)
![Apache](https://img.shields.io/badge/apache-%23D42029.svg?style=for-the-badge&logo=apache&logoColor=white)

## 📋 Description du projet

StageLink est une application web conçue pour faciliter la recherche et la gestion des stages pour les étudiants du CESI. Elle centralise les offres de stage, permet de gérer les candidatures et offre une interface adaptée à chaque type d'utilisateur (étudiant, pilote de promotion, administrateur).

## ✨ Fonctionnalités principales

### 👤 Gestion d'accès
- Authentification sécurisée
- Système de droits d'accès différenciés selon les rôles

### 🏢 Gestion des entreprises
- Recherche et affichage des fiches entreprises
- Création, modification et suppression d'entreprises
- Système d'évaluation des entreprises

### 📝 Gestion des offres de stage
- Recherche multicritères d'offres
- Création, modification et suppression d'offres
- Statistiques sur les offres (répartition par compétence, durée, etc.)

### 👨‍🏫 Gestion des pilotes de promotion
- Gestion complète des comptes pilotes

### 👨‍🎓 Gestion des étudiants
- Gestion complète des comptes étudiants
- Suivi de la recherche de stage

### 📄 Gestion des candidatures
- Système de wish-list pour les offres
- Interface de candidature (CV + lettre de motivation)
- Suivi des candidatures

## 🛠️ Technologies utilisées

- **Frontend** : HTML5, CSS3, JavaScript
- **Backend** : PHP (Architecture MVC)
- **Base de données** : MySQL
- **Moteur de template** : Twig
- **Serveur** : Apache

## 🚀 Installation

1. Cloner le dépôt
   ```bash
   git clone https://github.com/votre-username/StageLinkV1.git
   ```

2. Importer la base de données
   créez une base de données sur PhpMyAdmin nommée "stagelink" et insérez-y le code suivant : 
```-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 02 avr. 2025 à 09:34
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `stagelink`
--

-- --------------------------------------------------------

--
-- Structure de la table `candidatures`
--

DROP TABLE IF EXISTS `candidatures`;
CREATE TABLE IF NOT EXISTS `candidatures` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int NOT NULL,
  `offre_id` int NOT NULL,
  `date_candidature` date NOT NULL,
  `cv` varchar(100) NOT NULL,
  `lettre_motivation` text,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  KEY `offre_id` (`offre_id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `candidatures`
--

INSERT INTO `candidatures` (`id`, `utilisateur_id`, `offre_id`, `date_candidature`, `cv`, `lettre_motivation`) VALUES
(1, 20, 1, '2025-03-03', 'cv_20_1.pdf', 'Je suis particulièrement intéressé par les technologies que vous utilisez.'),
(2, 16, 1, '2025-03-03', 'cv_16_1.pdf', 'Je suis particulièrement intéressé par les technologies que vous utilisez.'),
(3, 15, 1, '2025-03-16', 'cv_15_1.pdf', 'Je suis très motivé pour rejoindre votre équipe et participer à vos projets innovants.'),
(4, 18, 2, '2025-03-11', 'cv_18_2.pdf', 'Je suis particulièrement intéressé par les technologies que vous utilisez.'),
(5, 16, 2, '2025-03-30', 'cv_16_2.pdf', 'Je suis particulièrement intéressé par les technologies que vous utilisez.'),
(6, 15, 2, '2025-04-01', 'cv_15_2.pdf', 'Je suis très motivé pour rejoindre votre équipe et participer à vos projets innovants.'),
(7, 2, 2, '2025-03-19', 'cv_2_2.pdf', 'Je suis particulièrement intéressé par les technologies que vous utilisez.'),
(8, 20, 3, '2025-03-08', 'cv_20_3.pdf', 'Je suis particulièrement intéressé par les technologies que vous utilisez.'),
(9, 17, 3, '2025-03-25', 'cv_17_3.pdf', 'Je suis très motivé pour rejoindre votre équipe et participer à vos projets innovants.'),
(10, 15, 3, '2025-03-09', 'cv_15_3.pdf', 'Passionné par votre domaine d\'activité, je souhaite apporter ma contribution.'),
(11, 2, 3, '2025-03-16', 'cv_2_3.pdf', 'Mon profil correspond parfaitement aux compétences recherchées pour ce stage.'),
(12, 22, 4, '2025-03-28', 'cv_22_4.pdf', 'Passionné par votre domaine d\'activité, je souhaite apporter ma contribution.'),
(13, 19, 4, '2025-03-09', 'cv_19_4.pdf', 'Mon profil correspond parfaitement aux compétences recherchées pour ce stage.'),
(14, 18, 4, '2025-03-25', 'cv_18_4.pdf', 'Passionné par votre domaine d\'activité, je souhaite apporter ma contribution.'),
(15, 16, 4, '2025-03-21', 'cv_16_4.pdf', 'Je suis particulièrement intéressé par les technologies que vous utilisez.'),
(16, 15, 4, '2025-03-27', 'cv_15_4.pdf', 'Je suis particulièrement intéressé par les technologies que vous utilisez.'),
(17, 20, 5, '2025-03-29', 'cv_20_5.pdf', 'Passionné par votre domaine d\'activité, je souhaite apporter ma contribution.'),
(18, 15, 5, '2025-03-14', 'cv_15_5.pdf', 'Mon profil correspond parfaitement aux compétences recherchées pour ce stage.'),
(19, 21, 6, '2025-03-18', 'cv_21_6.pdf', 'Je suis très motivé pour rejoindre votre équipe et participer à vos projets innovants.'),
(20, 18, 6, '2025-03-22', 'cv_18_6.pdf', 'Passionné par votre domaine d\'activité, je souhaite apporter ma contribution.'),
(21, 16, 6, '2025-03-29', 'cv_16_6.pdf', 'Passionné par votre domaine d\'activité, je souhaite apporter ma contribution.'),
(22, 15, 6, '2025-03-27', 'cv_15_6.pdf', 'Je suis particulièrement intéressé par les technologies que vous utilisez.'),
(23, 2, 6, '2025-03-20', 'cv_2_6.pdf', 'Je suis particulièrement intéressé par les technologies que vous utilisez.'),
(24, 20, 7, '2025-03-30', 'cv_20_7.pdf', 'Je suis très motivé pour rejoindre votre équipe et participer à vos projets innovants.'),
(25, 22, 8, '2025-03-10', 'cv_22_8.pdf', 'Passionné par votre domaine d\'activité, je souhaite apporter ma contribution.'),
(26, 21, 8, '2025-03-08', 'cv_21_8.pdf', 'Je suis particulièrement intéressé par les technologies que vous utilisez.'),
(27, 19, 8, '2025-03-12', 'cv_19_8.pdf', 'Je suis particulièrement intéressé par les technologies que vous utilisez.'),
(28, 15, 8, '2025-03-26', 'cv_15_8.pdf', 'Passionné par votre domaine d\'activité, je souhaite apporter ma contribution.'),
(29, 21, 9, '2025-03-06', 'cv_21_9.pdf', 'Mon profil correspond parfaitement aux compétences recherchées pour ce stage.'),
(30, 18, 9, '2025-03-06', 'cv_18_9.pdf', 'Mon profil correspond parfaitement aux compétences recherchées pour ce stage.'),
(31, 1, 3, '2025-04-01', 'uploads/cv/cv_67ebceea60d9c.pdf', 'je suis motivé');

-- --------------------------------------------------------

--
-- Structure de la table `competences`
--

DROP TABLE IF EXISTS `competences`;
CREATE TABLE IF NOT EXISTS `competences` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `competences`
--

INSERT INTO `competences` (`id`, `nom`) VALUES
(1, 'JavaScript'),
(2, 'Python'),
(3, 'Java'),
(4, 'PHP'),
(5, 'React'),
(6, 'Angular'),
(7, 'Vue.js'),
(8, 'Node.js'),
(9, 'SQL'),
(10, 'NoSQL'),
(11, 'AWS'),
(12, 'Azure'),
(13, 'Docker'),
(14, 'Kubernetes'),
(15, 'Git'),
(16, 'CI/CD'),
(17, 'Machine Learning'),
(18, 'DevOps'),
(19, 'Agile'),
(20, 'Scrum'),
(21, 'TypeScript'),
(22, 'C++'),
(23, 'Ruby'),
(24, 'Swift'),
(25, 'Kotlin'),
(26, 'Flutter'),
(27, 'Unity'),
(28, 'Unreal Engine'),
(29, 'TensorFlow'),
(30, 'PyTorch');

-- --------------------------------------------------------

--
-- Structure de la table `entreprises`
--

DROP TABLE IF EXISTS `entreprises`;
CREATE TABLE IF NOT EXISTS `entreprises` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `description` text,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `entreprises`
--

INSERT INTO `entreprises` (`id`, `nom`, `description`, `email`, `telephone`) VALUES
(1, 'TechInno Solutions', 'Entreprise spécialisée dans le développement de solutions innovantes', 'contact@techinnov.fr', '0123456789'),
(2, 'DataViz Corp', 'Leader en visualisation de données et analytics', 'info@dataviz.fr', '0234567890'),
(3, 'WebDev Express', 'Agence de développement web full-stack', 'contact@webdev.fr', '0345678901'),
(4, 'AI Future', 'Spécialiste en intelligence artificielle et machine learning', 'contact@aifuture.fr', '0456789012'),
(5, 'CyberSec Pro', 'Expert en cybersécurité et protection des données', 'security@cybersec.fr', '0567890123'),
(6, 'Cloud Masters', 'Solutions cloud et infrastructure IT', 'info@cloudmasters.fr', '0678901234'),
(7, 'Mobile First', 'Développement d\'applications mobiles innovantes', 'dev@mobilefirst.fr', '0789012345'),
(8, 'Green IT Solutions', 'Solutions informatiques écologiques', 'contact@greenit.fr', '0890123456'),
(9, 'UX Design Lab', 'Studio de design d\'expérience utilisateur', 'design@uxlab.fr', '0901234567'),
(10, 'DevOps Elite', 'Experts en pratiques DevOps et CI/CD', 'team@devops.fr', '0912345678'),
(11, 'Smart IoT', 'Spécialiste de l\'Internet des Objets et systèmes embarqués', 'contact@smartiots.fr', '0123789456'),
(12, 'BlockTech', 'Innovation en technologie blockchain et cryptomonnaies', 'info@blocktechs.fr', '0234890567'),
(13, '3D Virtual', 'Studio de réalité virtuelle et augmentée', 'studio@3dvirtual.fr', '0345901678'),
(14, 'BioTech Solutions', 'Applications pour le secteur médical et biotechnologique', 'contact@biotech.fr', '0456012789'),
(15, 'EduTech Pro', 'Solutions e-learning innovantes', 'edu@edutechpro.fr', '0567123890');

-- --------------------------------------------------------

--
-- Structure de la table `evaluations`
--

DROP TABLE IF EXISTS `evaluations`;
CREATE TABLE IF NOT EXISTS `evaluations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `entreprise_id` int NOT NULL,
  `utilisateur_id` int NOT NULL,
  `note` int DEFAULT NULL,
  `commentaire` text,
  PRIMARY KEY (`id`),
  KEY `entreprise_id` (`entreprise_id`),
  KEY `utilisateur_id` (`utilisateur_id`)
) ;

--
-- Déchargement des données de la table `evaluations`
--

INSERT INTO `evaluations` (`id`, `entreprise_id`, `utilisateur_id`, `note`, `commentaire`) VALUES
(1, 1, 17, 5, 'Bonne expérience globale, j\'ai beaucoup appris mais la charge de travail est importante'),
(2, 1, 16, 4, 'Excellente ambiance de travail, projets stimulants et équipe très compétente'),
(3, 1, 15, 2, 'Bonne expérience globale, j\'ai beaucoup appris mais la charge de travail est importante'),
(4, 1, 2, 5, 'Très bonne première expérience professionnelle, recommande vivement'),
(5, 2, 17, 3, 'Bonne expérience globale, j\'ai beaucoup appris mais la charge de travail est importante'),
(6, 2, 16, 2, 'Très bonne première expérience professionnelle, recommande vivement'),
(7, 2, 2, 3, 'Très bonne première expérience professionnelle, recommande vivement'),
(8, 3, 17, 3, 'Bonne expérience globale, j\'ai beaucoup appris mais la charge de travail est importante'),
(9, 3, 16, 3, 'Super environnement pour apprendre, tuteur très disponible'),
(10, 3, 15, 3, 'Projets intéressants mais manque parfois d\'encadrement'),
(11, 3, 2, 4, 'Très bonne première expérience professionnelle, recommande vivement'),
(12, 4, 17, 3, 'Super environnement pour apprendre, tuteur très disponible'),
(13, 4, 16, 2, 'Très bonne première expérience professionnelle, recommande vivement'),
(14, 4, 15, 2, 'Projets intéressants mais manque parfois d\'encadrement'),
(15, 5, 18, 4, 'Projets intéressants mais manque parfois d\'encadrement'),
(16, 5, 17, 4, 'Projets intéressants mais manque parfois d\'encadrement'),
(17, 5, 16, 5, 'Excellente ambiance de travail, projets stimulants et équipe très compétente'),
(18, 5, 15, 5, 'Bonne expérience globale, j\'ai beaucoup appris mais la charge de travail est importante'),
(19, 5, 2, 5, 'Super environnement pour apprendre, tuteur très disponible'),
(20, 6, 18, 3, 'Très bonne première expérience professionnelle, recommande vivement'),
(21, 6, 17, 5, 'Bonne expérience globale, j\'ai beaucoup appris mais la charge de travail est importante'),
(22, 6, 16, 5, 'Projets intéressants mais manque parfois d\'encadrement'),
(23, 6, 15, 3, 'Excellente ambiance de travail, projets stimulants et équipe très compétente'),
(24, 6, 2, 4, 'Projets intéressants mais manque parfois d\'encadrement'),
(25, 7, 18, 3, 'Bonne expérience globale, j\'ai beaucoup appris mais la charge de travail est importante'),
(26, 7, 16, 5, 'Très bonne première expérience professionnelle, recommande vivement'),
(27, 7, 15, 5, 'Projets intéressants mais manque parfois d\'encadrement'),
(28, 7, 2, 3, 'Projets intéressants mais manque parfois d\'encadrement'),
(29, 8, 17, 2, 'Projets intéressants mais manque parfois d\'encadrement'),
(30, 8, 16, 3, 'Très bonne première expérience professionnelle, recommande vivement'),
(31, 8, 15, 2, 'Bonne expérience globale, j\'ai beaucoup appris mais la charge de travail est importante'),
(32, 8, 2, 3, 'Très bonne première expérience professionnelle, recommande vivement'),
(33, 9, 18, 2, 'Excellente ambiance de travail, projets stimulants et équipe très compétente'),
(34, 9, 17, 5, 'Très bonne première expérience professionnelle, recommande vivement'),
(35, 9, 16, 4, 'Bonne expérience globale, j\'ai beaucoup appris mais la charge de travail est importante'),
(36, 9, 2, 3, 'Bonne expérience globale, j\'ai beaucoup appris mais la charge de travail est importante'),
(37, 10, 17, 4, 'Très bonne première expérience professionnelle, recommande vivement'),
(38, 10, 16, 4, 'Très bonne première expérience professionnelle, recommande vivement'),
(39, 10, 15, 4, 'Projets intéressants mais manque parfois d\'encadrement'),
(40, 10, 2, 4, 'Bonne expérience globale, j\'ai beaucoup appris mais la charge de travail est importante'),
(41, 11, 18, 2, 'Bonne expérience globale, j\'ai beaucoup appris mais la charge de travail est importante'),
(42, 11, 2, 3, 'Excellente ambiance de travail, projets stimulants et équipe très compétente'),
(43, 12, 18, 2, 'Excellente ambiance de travail, projets stimulants et équipe très compétente'),
(44, 12, 17, 2, 'Bonne expérience globale, j\'ai beaucoup appris mais la charge de travail est importante'),
(45, 12, 16, 4, 'Excellente ambiance de travail, projets stimulants et équipe très compétente'),
(46, 12, 15, 5, 'Super environnement pour apprendre, tuteur très disponible'),
(47, 12, 2, 5, 'Projets intéressants mais manque parfois d\'encadrement'),
(48, 13, 18, 2, 'Projets intéressants mais manque parfois d\'encadrement'),
(49, 13, 16, 5, 'Bonne expérience globale, j\'ai beaucoup appris mais la charge de travail est importante'),
(50, 13, 15, 5, 'Projets intéressants mais manque parfois d\'encadrement'),
(51, 14, 18, 3, 'Projets intéressants mais manque parfois d\'encadrement'),
(52, 14, 16, 5, 'Super environnement pour apprendre, tuteur très disponible'),
(53, 14, 15, 3, 'Très bonne première expérience professionnelle, recommande vivement'),
(54, 15, 18, 3, 'Très bonne première expérience professionnelle, recommande vivement'),
(55, 15, 17, 5, 'Super environnement pour apprendre, tuteur très disponible'),
(56, 15, 15, 4, 'Projets intéressants mais manque parfois d\'encadrement'),
(57, 15, 2, 2, 'Bonne expérience globale, j\'ai beaucoup appris mais la charge de travail est importante'),
(58, 13, 1, 1, 'nul');

-- --------------------------------------------------------

--
-- Structure de la table `offres`
--

DROP TABLE IF EXISTS `offres`;
CREATE TABLE IF NOT EXISTS `offres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `entreprise_id` int NOT NULL,
  `titre` varchar(100) NOT NULL,
  `description` text,
  `base_remuneration` decimal(10,2) DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entreprise_id` (`entreprise_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `offres`
--

INSERT INTO `offres` (`id`, `entreprise_id`, `titre`, `description`, `base_remuneration`, `date_debut`, `date_fin`) VALUES
(1, 1, 'Stage Développeur Full-Stack', 'Développement d\'applications web modernes avec React et Node.js', 1000.00, '2024-07-01', '2024-12-31'),
(2, 2, 'Data Scientist Junior', 'Analyse de données et création de visualisations interactives', 1200.00, '2024-06-01', '2024-11-30'),
(3, 3, 'Stage Développeur Front-End', 'Création d\'interfaces utilisateur avec Vue.js', 900.00, '2024-07-15', '2024-12-15'),
(4, 4, 'Stage en IA', 'Développement d\'algorithmes de machine learning', 1300.00, '2024-06-15', '2024-12-15'),
(5, 5, 'Stage en Cybersécurité', 'Audit de sécurité et pentesting', 1100.00, '2024-07-01', '2024-12-31'),
(6, 6, 'DevOps Engineer Junior', 'Mise en place de pipelines CI/CD', 1000.00, '2024-06-01', '2024-11-30'),
(7, 7, 'Développeur Mobile', 'Développement d\'applications Android/iOS', 950.00, '2024-07-01', '2024-12-31'),
(8, 8, 'Stage Green IT', 'Optimisation de la consommation énergétique des applications', 900.00, '2024-06-15', '2024-12-15'),
(9, 9, 'UX/UI Designer', 'Design d\'interfaces utilisateur innovantes', 1000.00, '2024-07-01', '2024-12-31'),
(10, 10, 'Stage Infrastructure Cloud', 'Déploiement d\'applications sur AWS', 1100.00, '2024-06-01', '2024-11-30'),
(11, 11, 'Stage Développeur Blockchain', 'Développement de smart contracts et applications décentralisées', 1200.00, '2024-07-01', '2024-12-31'),
(12, 12, 'Stage Data Engineer', 'Construction de pipelines de données temps réel', 1100.00, '2024-06-15', '2024-12-15'),
(13, 13, 'Stage Développeur VR', 'Création d\'expériences en réalité virtuelle', 1000.00, '2024-07-01', '2024-12-31'),
(14, 14, 'Stage en Robotique', 'Programmation de robots industriels', 1300.00, '2024-06-01', '2024-11-30'),
(15, 15, 'Stage IoT Developer', 'Développement de solutions IoT connectées', 1100.00, '2024-07-15', '2024-12-15');

-- --------------------------------------------------------

--
-- Structure de la table `offres_competences`
--

DROP TABLE IF EXISTS `offres_competences`;
CREATE TABLE IF NOT EXISTS `offres_competences` (
  `offre_id` int NOT NULL,
  `competence_id` int NOT NULL,
  PRIMARY KEY (`offre_id`,`competence_id`),
  KEY `competence_id` (`competence_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `offres_competences`
--

INSERT INTO `offres_competences` (`offre_id`, `competence_id`) VALUES
(1, 12),
(1, 25),
(1, 30),
(2, 1),
(2, 2),
(2, 6),
(2, 8),
(2, 15),
(2, 17),
(2, 30),
(3, 6),
(3, 7),
(3, 8),
(3, 15),
(3, 19),
(3, 22),
(3, 28),
(4, 1),
(4, 4),
(4, 28),
(5, 16),
(5, 22),
(6, 18),
(6, 22),
(6, 27),
(6, 29),
(7, 11),
(7, 13),
(7, 16),
(7, 20),
(7, 21),
(7, 23),
(7, 26),
(8, 7),
(8, 10),
(8, 19),
(8, 25),
(9, 6),
(9, 13),
(9, 25),
(10, 21),
(10, 22),
(10, 24),
(10, 25),
(10, 27),
(11, 3),
(11, 7),
(11, 22),
(11, 23),
(11, 28),
(11, 30),
(12, 2),
(12, 5),
(12, 13),
(12, 15),
(12, 19),
(12, 22),
(13, 3),
(13, 15),
(13, 22),
(13, 26),
(13, 27),
(14, 2),
(14, 17),
(14, 22),
(14, 28),
(15, 5),
(15, 6),
(15, 11),
(15, 14),
(15, 16),
(15, 17);

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ;

--
-- Déchargement des données de la table `permissions`
--

INSERT INTO `permissions` (`id`, `code`, `nom`, `description`) VALUES
(1, 'VOIR_OFFRE', 'Voir les offres', 'Permet de voir les offres de stage'),
(2, 'CREER_OFFRE', 'Créer une offre', 'Permet de créer une offre de stage'),
(3, 'MODIFIER_OFFRE', 'Modifier une offre', 'Permet de modifier une offre de stage'),
(4, 'SUPPRIMER_OFFRE', 'Supprimer une offre', 'Permet de supprimer une offre de stage'),
(5, 'VOIR_ENTREPRISE', 'Voir les entreprises', 'Permet de voir les entreprises'),
(6, 'GERER_ENTREPRISES', 'Gérer les entreprises', 'Permet de gérer les entreprises'),
(7, 'EVALUER_ENTREPRISE', 'Évaluer une entreprise', 'Permet d\'évaluer une entreprise'),
(8, 'GERER_UTILISATEURS', 'Gérer les utilisateurs', 'Permet de gérer les utilisateurs'),
(9, 'VOIR_CANDIDATURES', 'Voir les candidatures', 'Permet de voir les candidatures'),
(10, 'GERER_CANDIDATURES', 'Gérer les candidatures', 'Permet de gérer les candidatures'),
(11, 'ACCES_ADMIN', 'Accès admin', 'Permet d\'accéder à l\'interface d\'administration');

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `code`, `nom`, `description`) VALUES
(1, 'ADMIN', 'Administrateur', 'Accès complet au système'),
(2, 'PILOTE', 'Pilote', 'Gestion des offres et des entreprises'),
(3, 'ETUDIANT', 'Étudiant', 'Accès aux offres et entreprises');

-- --------------------------------------------------------

--
-- Structure de la table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `permission_id` (`permission_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 5),
(2, 6),
(2, 8),
(2, 9),
(2, 10),
(3, 1),
(3, 5),
(3, 7),
(3, 9);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(100) NOT NULL,
  `role_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `role_id`) VALUES
(1, 'admin', 'admin', 'admin@gmail.com', '$2y$10$R9L3k0c3nN4U7iZjrdvE0ugIaXjbznTNtdKbIMNxzj3OsCeJXbr3e', 1),
(2, 'etudiant', 'etudiant', 'etudiant@gmail.com', '$2y$10$YMcmD7NI0zhNHm7r2.lEOeRgwbaaKcCMv/13XXZq2MJjFQXsOHlsi', 3),
(3, 'pilote', 'pilote', 'pilote@gmail.com', '$2y$10$i/M3FwldHovKLtnq7HuCzefWeg0hlqftG9bZWBjLbAdpZZc3Jr89a', 2),
(13, 'Dupont', 'Jean', 'jean.dupont@example.com', 'mdp123', 1),
(14, 'Martin', 'Sophie', 'sophie.martin@example.com', 'mdp456', 2),
(15, 'Durand', 'Luc', 'luc.durand@example.com', 'mdp789', 3),
(16, 'Lemoine', 'Alice', 'alice.lemoine@example.com', 'mdpabc', 3),
(17, 'Bernard', 'Thomas', 'thomas.bernard@example.com', 'mdpdef', 3),
(18, 'Moreau', 'Julie', 'julie.moreau@example.com', 'mdpghi', 3),
(19, 'Robert', 'Emma', 'emma.robert@example.com', 'mdpjkl', 3),
(20, 'Lefevre', 'Maxime', 'maxime.lefevre@example.com', 'mdpmno', 3),
(21, 'Garcia', 'Nina', 'nina.garcia@example.com', 'mdpqrs', 3),
(22, 'Lemoine', 'Paul', 'paul.lemoine@example.com', 'mdptuv', 3);

-- --------------------------------------------------------

--
-- Structure de la table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE IF NOT EXISTS `wishlist` (
  `utilisateur_id` int NOT NULL,
  `offre_id` int NOT NULL,
  PRIMARY KEY (`utilisateur_id`,`offre_id`),
  KEY `offre_id` (`offre_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `wishlist`
--

INSERT INTO `wishlist` (`utilisateur_id`, `offre_id`) VALUES
(2, 3),
(2, 4),
(2, 6),
(2, 8),
(15, 4),
(15, 9),
(16, 4),
(16, 5),
(17, 1),
(17, 2),
(17, 3),
(17, 4),
(17, 9),
(18, 2),
(18, 3),
(18, 6),
(19, 2),
(19, 4),
(19, 5),
(20, 10),
(21, 8),
(21, 10),
(22, 2),
(22, 6),
(22, 9);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
```

   ```bash
   mysql -u username -p database_name < database/stagelink.sql
   ```

4. Installer les dépendances
   ```bash
   composer install
   ```

5. Configurer les paramètres de connexion à la base de données dans `config/database.php`

## 📁 Structure du projet

```
StageLinkV1/
├── assets/           # Fichiers statiques (CSS, JS, images)
├── config/           # Fichiers de configuration
├── controllers/      # Contrôleurs MVC
├── models/           # Modèles MVC
├── templates/        # Templates Twig
├── vendor/           # Dépendances (générées par Composer)
├── .htaccess         # Configuration Apache
├── composer.json     # Configuration Composer
├── index.php         # Point d'entrée de l'application
└── README.md         # Ce fichier
```

## 👥 Équipe de développement

- Enzo - Chef de Projet / développeur fullstack
- Luca - développeur fullstack
- Alexandre - développeur fullstack
- Léo - développeur fullstack

## 🔒 Sécurité

- Protection contre les injections SQL
- Hachage sécurisé des mots de passe
- Authentification par cookies sécurisés
- Validation des données côté client et serveur

## 📱 Responsive Design

L'application est entièrement responsive et s'adapte à tous les appareils, des téléphones mobiles aux grands écrans.

## 📜 Licence

© 2025 Équipe StageLinkV1. Tous droits réservés.

💡 Note pour les développeurs: N'oubliez pas de consulter la documentation complète dans le dossier docs/ pour plus de détails sur l'architecture et les conventions de codage.

---
