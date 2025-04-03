```
---------------------------------------------------
|Faire CTRL+SHIFT+V pour une lecture plus agréable.|
---------------------------------------------------
```

# Documentation du projet StageLinkV1 🚀

## Aperçu général 🌐

StageLinkV1 est une application web conçue pour faciliter la gestion des stages et la mise en relation entre étudiants, établissements d'enseignement et entreprises. Le projet est structuré comme une application PHP moderne, utilisant une architecture MVC (Modèle-Vue-Contrôleur).

## Structure du projet 📁

### Dossiers principaux 📂
- `.git/` - Dossier de gestion de version Git 🔄
- `vendor/` - Dépendances externes gérées par Composer 📦
- `src/` - Code source de l'application 💻
  - Organisé en modules (Controllers, Models, Services)
  - Implémentation des fonctionnalités métier
- `templates/` - Fichiers de templates pour l'interface utilisateur 🎨
  - Organisés par section ou fonctionnalité
- `static/` - Ressources statiques 🖼️
  - CSS, JavaScript, images et autres fichiers statiques

### Configuration ⚙️
- `.env` - Fichier de configuration des variables d'environnement 🔑

### Branches Git 🌿
- `main` - Branche principale 🏠
- `dev` - Branche de développement 🛠️
- `features/PDO` - Fonctionnalité liée à PDO (PHP Data Objects) 💾
- `features/pilotes-étudiants` - Fonctionnalité concernant les pilotes et étudiants 👨‍🎓👩‍🎓
- Autres branches de fonctionnalités : `english` 🇬🇧, `update` 🔄, `upgrade` ⬆️

## Modèle Logique de Données (MLD) 📊

Le MLD de StageLinkV1 révèle une structure de base de données relationnelle complète avec les entités suivantes :

### Entités principales :
- **Utilisateurs** : Stocke les informations des utilisateurs (nom, prénom, email, mot de passe)
- **Roles** : Définit les différents rôles dans le système
- **Permissions** : Gère les droits d'accès aux fonctionnalités
- **Role_Permissions** : Table de jonction entre rôles et permissions
- **Entreprises** : Informations sur les entreprises partenaires
- **Offres** : Détails des offres de stage proposées
- **Candidatures** : Suivi des candidatures des étudiants
- **Evaluations** : Évaluations des stages complétés
- **Competences** : Référentiel des compétences requises/acquises
- **Offres_Competences** : Liaison entre offres et compétences requises
- **WishList** : Système de favoris pour les offres

### Relations clés :
- Les utilisateurs ont un rôle spécifique
- Les entreprises publient des offres
- Les utilisateurs postulent aux offres via des candidatures
- Les offres nécessitent des compétences spécifiques
- Les utilisateurs peuvent ajouter des offres à leur liste de souhaits

Cette structure permet une gestion complète du cycle de vie des stages, depuis la publication des offres jusqu'à l'évaluation post-stage.

## Diagrammes UML 📈

### Diagrammes de classes
Le diagramme de classes illustre l'architecture orientée objet de l'application, montrant les classes principales, leurs attributs, méthodes et relations. Il met en évidence la structure de l'application avec une séparation claire entre :
- Les entités métier (User, Role, Offer, Application, etc.)
- Les services de gestion
- Les contrôleurs
- L'infrastructure technique

### Diagrammes d'activité
Les diagrammes d'activité modélisent les flux de processus pour les principales fonctionnalités :
- **Authentification** : Processus de connexion et gestion des sessions
- **Candidature** : Flux de soumission et traitement des candidatures
- **Entreprise** : Gestion des profils et offres d'entreprises
- **Offres** : Cycle de vie des offres de stage
- **Wishlist** : Gestion des favoris
- **Flux Total** : Vue d'ensemble des interactions utilisateur dans le système

### Diagrammes de séquence
Les diagrammes de séquence détaillent les interactions entre composants pour :
- **Authentification** : Échanges lors de la connexion
- **Candidature** : Communication entre acteurs lors d'une candidature
- **Entreprises** : Interactions pour la gestion des entreprises
- **Offres** : Séquence de création et gestion des offres
- **Wishlist** : Interactions pour la gestion des favoris

Ces diagrammes UML fournissent une documentation visuelle complète de l'architecture et du comportement du système, facilitant la compréhension et la maintenance du code.

## Fonctionnalités ✨

1. **Gestion des stages** 📝 - Création, suivi et évaluation des stages
2. **Gestion des utilisateurs** 👥 - Différents rôles (étudiants, pilotes, entreprises)
3. **Internationalisation** 🌍 - Support multilingue (français, anglais)
4. **Base de données** 🗃️ - Utilisation de PDO pour l'accès aux données
5. **Interface utilisateur responsive** 📱 - Implémentée dans les templates et avec des ressources statiques
6. **Système de candidatures** 📄 - Permet aux étudiants de postuler aux offres
7. **Wishlist** ⭐ - Permet aux utilisateurs de sauvegarder des offres favorites
8. **Gestion des compétences** 🧠 - Association de compétences aux offres et utilisateurs

## Architecture technique 🏗️

### Backend 🖥️
- **Langage** : PHP 🐘
- **Accès aux données** : PDO (PHP Data Objects) 🔌
- **Structure** : Architecture MVC 🏛️

### Frontend 🎨
- **Templates** : Système de templates pour la présentation 📄
- **CSS** : Styles pour l'interface utilisateur 🎭
- **JavaScript** : Interactions dynamiques côté client ⚡

### Outils de développement 🛠️
- **Gestion de version** : Git 📊
- **Gestion des dépendances** : Composer 📦

## Environnement de développement 🌱

Le fichier `.env` contient les configurations spécifiques à l'environnement :
- Paramètres de connexion à la base de données 🔐
- Configurations spécifiques à l'environnement (développement, test, production) ⚙️

## Processus de développement 🔄

Le projet suit un workflow Git avec :
- Branche principale (`main`) pour les versions stables ✅
- Branche de développement (`dev`) pour l'intégration des fonctionnalités 🔄
- Branches de fonctionnalités (`features/*`) pour le développement de nouvelles fonctionnalités 🌱

## Recommandations pour le développement futur 🚀

2. **Intégration continue** 🔄 : Mettre en place un pipeline CI/CD pour automatiser les tests et le déploiement
4. **Performance** ⚡ : Optimiser les requêtes de base de données et le chargement des pages
5. **Accessibilité** ♿ : S'assurer que l'interface utilisateur est accessible à tous les utilisateurs
6. **Extension des fonctionnalités** 🌱 : Développer des fonctionnalités supplémentaires comme des roles entreprises pour gérer les candidatures reçues.

## Conclusion 🏁

StageLinkV1 est une application web robuste pour la gestion des stages, avec une architecture bien structurée et documentée par des diagrammes UML complets. Le projet utilise des technologies modernes et suit les bonnes pratiques de développement, offrant une base solide pour les évolutions futures.