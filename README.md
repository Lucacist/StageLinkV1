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

### 1. Cloner le dépôt
   ```bash
   git clone https://github.com/votre-username/StageLinkV1.git
   ```

### 2. Importer la base de données
#### Configurer la base de données

##### Créez une base de données MySQL nommée "stagelink"
Importez le fichier SQL fourni :
Copier
mysql -u username -p stagelink < database/stagelink.sql

Note: Le fichier SQL se trouve dans le dossier src/config du projet

   ```bash
   mysql -u username -p database_name < database/stagelink.sql
   ```

### 4. Installer les dépendances
   ```bash
   composer install
   ```

### 5. Configurer les paramètres de connexion à la base de données dans `config/database.php`

## 📁 Structure du projet

```
StageLinkV1/
├── src/
    │   ├── config/           # Fichiers de configuration
    │   ├── Controllers/      # Contrôleurs MVC
    │   ├── Models/           # Modèles MVC
    │   └── Utils/            # Fichiers URL
    ├── static/               # Fichiers statiques (CSS, JS, images)
    ├── templates/            # Templates Twig
    ├── uploads/              # Documents uploads
    ├── vendor/               # Dépendances (générées par Composer)
    ├── .htaccess             # Configuration Apache
    ├── .env                  # Sécuritée connexion bdd
    ├── composer.json         # Configuration Composer
    ├── index.php             # Point d'entrée de l'application
    └── README.md             # Ce fichier
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

> [!NOTE]
>Note pour les développeurs: N'oubliez pas de consulter la documentation complète dans le dossier docs/ pour plus de détails sur l'architecture et les conventions de codage.

---
