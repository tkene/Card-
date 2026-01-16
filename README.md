# 🃏 Jeu de Cartes - Application Symfony

Application web interactive permettant de générer et trier des mains de cartes selon des règles personnalisables. Le projet utilise Symfony 7.4 et Tailwind CSS pour offrir une expérience utilisateur moderne et intuitive.

## 📋 Table des matières

- [Description](#-description)
- [Fonctionnalités](#-fonctionnalités)
- [Prérequis](#-prérequis)
- [Installation](#-installation)
- [Utilisation](#-utilisation)
- [Structure du projet](#-structure-du-projet)
- [Technologies utilisées](#-technologies-utilisées)
- [Routes disponibles](#-routes-disponibles)
- [Tests](#-tests)
- [Architecture](#-architecture)

## 🎯 Description

Cette application permet aux utilisateurs de :
1. **Définir un ordre personnalisé pour les couleurs** (Carreaux, Cœurs, Piques, Trèfles)
2. **Définir un ordre personnalisé pour les valeurs** (As, 2, 3, ..., Roi)
3. **Générer une main aléatoire** de cartes (de 1 à 52 cartes)
4. **Visualiser les cartes triées** selon les règles personnalisées

Le tri s'effectue d'abord par couleur (selon l'ordre choisi), puis par valeur (selon l'ordre choisi).

## ✨ Fonctionnalités

### Fonctionnalités principales
- ✅ Configuration personnalisée de l'ordre des couleurs
- ✅ Configuration personnalisée de l'ordre des valeurs
- ✅ Réorganisation interactive des couleurs et valeurs (déplacement haut/bas)
- ✅ Génération de mains aléatoires (1 à 52 cartes)
- ✅ Tri automatique des cartes selon les règles définies
- ✅ Interface utilisateur moderne et responsive avec Tailwind CSS
- ✅ Gestion d'état via sessions Symfony

### Fonctionnalités bonus
- 🔄 Réorganisation dynamique de l'ordre des couleurs/valeurs
- 🎨 Interface utilisateur soignée avec animations
- 📱 Design responsive

## 🔧 Prérequis

Avant de commencer, assurez-vous d'avoir installé :

- **PHP** >= 8.2
- **Composer** (gestionnaire de dépendances PHP)
- **Node.js** et **npm** (pour Tailwind CSS)
- **Git** (optionnel, pour le versioning)

## 📦 Installation

### 1. Cloner le projet (si applicable)

```bash
git clone <url-du-repo>
cd card-game
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Installer les dépendances Node.js

```bash
npm install
```

### 4. Compiler les assets CSS

```bash
npm run build-css
```

## 🚀 Utilisation

### Démarrer le serveur de développement

```bash
# Option 1 : Utiliser le script npm
npm start

# Option 2 : Utiliser le serveur PHP intégré
php -S localhost:8000 -t public

# Option 3 : Utiliser Symfony CLI (si installé)
symfony server:start
```

L'application sera accessible à l'adresse : **http://localhost:8000**

### Workflow de l'application

1. **Page d'accueil** : Cliquez sur "Commencer"
2. **Étape 1 - Choix des couleurs** : 
   - Un ordre aléatoire des couleurs est généré
   - Vous pouvez réorganiser l'ordre en cliquant sur les flèches haut/bas
   - Cliquez sur "Confirmer" une fois satisfait
3. **Étape 2 - Choix des valeurs** :
   - Un ordre aléatoire des valeurs est généré
   - Réorganisez l'ordre si nécessaire
   - Cliquez sur "Confirmer"
4. **Étape 3 - Nombre de cartes** :
   - Entrez le nombre de cartes souhaité (entre 1 et 52)
   - Cliquez sur "Générer la main"
5. **Étape 4 - Visualisation** :
   - Visualisez votre main non triée
   - Cliquez sur "Trier les cartes" pour voir le résultat
6. **Étape 5 - Résultat** :
   - Visualisez les cartes triées selon vos règles
   - Option de retour ou de réinitialisation

## 📁 Structure du projet

```
card-game/
├── assets/
│   └── styles/
│       └── app.css              # Styles Tailwind CSS
├── config/                      # Configuration Symfony
│   ├── packages/
│   └── routes.yaml
├── public/                      # Point d'entrée web
│   ├── build/
│   │   └── app.css              # CSS compilé
│   └── index.php
├── src/
│   ├── Controller/
│   │   └── GameController.php   # Contrôleur principal
│   ├── Service/
│   │   ├── CardService.php      # Service de gestion des cartes
│   │   ├── GameService.php      # Service de logique métier
│   │   └── GameStateService.php # Service de gestion d'état (session)
│   └── Kernel.php
├── templates/
│   ├── base.html.twig           # Template de base
│   └── game/
│       ├── index.html.twig
│       ├── choose_colors.html.twig
│       ├── choose_values.html.twig
│       ├── choose_game_mode.html.twig
│       ├── show_cards.html.twig
│       └── show_sorted_cards.html.twig
├── tests/
│   └── Service/
│       └── CardServiceTest.php  # Tests unitaires
├── composer.json                # Dépendances PHP
├── package.json                 # Dépendances Node.js
└── tailwind.config.js           # Configuration Tailwind
```

## 🛠 Technologies utilisées

### Backend
- **Symfony 7.4** : Framework PHP moderne
- **PHP 8.2+** : Langage de programmation
- **Twig** : Moteur de templates

### Frontend
- **Tailwind CSS 3.4** : Framework CSS utility-first
- **HTML5** : Structure
- **JavaScript** : Interactivité (vanilla)

### Outils de développement
- **Composer** : Gestionnaire de dépendances PHP
- **npm** : Gestionnaire de paquets Node.js
- **Git** : Contrôle de version

## 🗺 Routes disponibles

| Route | Méthode | Description |
|-------|---------|-------------|
| `/` | GET | Page d'accueil |
| `/choose-colors` | GET | Étape 1 : Choix de l'ordre des couleurs |
| `/confirm-colors` | GET | Confirmation de l'ordre des couleurs |
| `/choose-values` | GET | Étape 2 : Choix de l'ordre des valeurs |
| `/confirm-values` | GET | Confirmation de l'ordre des valeurs |
| `/choose-game-mode` | GET | Étape 3 : Choix du nombre de cartes |
| `/confirm-cards-number` | POST | Confirmation du nombre de cartes |
| `/show-cards-with-values` | GET | Étape 4 : Affichage de la main non triée |
| `/show-sorted-cards` | GET | Étape 5 : Affichage de la main triée |
| `/reset-game` | GET | Réinitialisation de la partie |

### Paramètres de requête

- `?new=true` : Génère un nouvel ordre aléatoire
- `?move=up&index=0` : Déplace un élément vers le haut
- `?move=down&index=0` : Déplace un élément vers le bas

## 🧪 Tests

### Exécuter les tests

```bash
# Si PHPUnit est installé
vendor/bin/phpunit

# Ou avec Symfony
php bin/phpunit
```

### Tests disponibles

- `CardServiceTest::testSortHand()` : Test du tri des cartes

### Installation de PHPUnit (si nécessaire)

```bash
composer require --dev phpunit/phpunit
```

## 🏗 Architecture

### Pattern MVC

Le projet suit l'architecture Model-View-Controller :

- **Model** : Services (`CardService`, `GameService`, `GameStateService`)
- **View** : Templates Twig dans `templates/`
- **Controller** : `GameController`

### Services

#### CardService
Gère la logique liée aux cartes :
- Génération d'ordres aléatoires (couleurs, valeurs)
- Tri des mains selon les règles personnalisées

#### GameService
Contient la logique métier du jeu :
- Validation du nombre de cartes
- Génération de mains aléatoires
- Réorganisation d'éléments dans un tableau

#### GameStateService
Gère l'état de la partie via les sessions :
- Stockage des ordres de couleurs/valeurs
- Gestion des confirmations d'étapes
- Persistance de la main générée

### Injection de dépendances

Tous les services sont injectés via le conteneur Symfony (autowiring) :

```php
public function __construct(
    GameService $gameService,
    CardService $cardService,
    GameStateService $gameStateService
) {
    // ...
}
```

## 📝 Notes de développement

### Compilation des assets

Lors de la modification des fichiers CSS, recompiler les assets :

```bash
npm run build-css
```

### Cache Symfony

En cas de problème, vider le cache :

```bash
php bin/console cache:clear
```

## 🔒 Sécurité

- Validation des entrées utilisateur (nombre de cartes)
- Utilisation des sessions Symfony sécurisées
- Protection contre les injections (type casting explicite)

## 📄 Licence

Ce projet est un exercice de développement. Tous droits réservés.

## 👤 Auteur

Développé dans le cadre d'un exercice technique.

## 🤝 Contribution

Ce projet est un exercice individuel. Pour toute question ou suggestion, n'hésitez pas à ouvrir une issue.

---

**Bon jeu ! 🃏**

