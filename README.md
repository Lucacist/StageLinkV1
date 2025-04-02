# 🎓 StageLink - Plateforme de gestion des stages

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

2. Configurer le serveur Apache avec les vhosts appropriés
   - Un vhost principal pour l'application
   - Un vhost spécifique pour le contenu statique

3. Importer la base de données
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

---

💡 **Note pour les développeurs**: N'oubliez pas de consulter la documentation complète dans le dossier `docs/` pour plus de détails sur l'architecture et les conventions de codage.
