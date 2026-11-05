# Marché Local

Une marketplace simple et fonctionnelle développée en PHP, conçue pour permettre à des utilisateurs de parcourir des produits, de s'inscrire, de se connecter, d'ajouter des articles au panier et de passer des commandes.

Le projet met en œuvre une logique d'authentification basique, une gestion des rôles acheteur/vendeur, un catalogue de produits avec images, ainsi qu'un flux de commande adapté à une boutique en ligne légère.

## Sommaire

- [Présentation](#présentation)
- [Fonctionnalités](#fonctionnalités)
- [Stack technique](#stack-technique)
- [Prérequis](#prérequis)
- [Installation](#installation)
- [Configuration de la base de données](#configuration-de-la-base-de-données)
- [Utilisation](#utilisation)
- [Structure du projet](#structure-du-projet)
- [Points d'attention](#points-dattention)

## Présentation

Marché Local est une application web de type marketplace locale avec un parcours utilisateur simple :

- découverte des produits dans un catalogue,
- inscription / connexion,
- gestion des rôles acheteur et vendeur,
- ajout au panier et mise à jour de quantités,
- validation d'une commande et suivi de statut,
- publication de nouveaux produits par les vendeurs.

L'application est pensée pour un usage pédagogique ou un démarrage de boutique en ligne légère, sans framework, en PHP procédural avec MySQL.

## Fonctionnalités

### Pour les visiteurs

- Accès à la page d'accueil avec présentation du site,
- parcourir le catalogue de produits,
- consulter les détails d'un article,
- créer un compte,
- se connecter à son espace.

### Pour les acheteurs

- ajouter des produits au panier,
- modifier la quantité,
- supprimer un article,
- consulter le total du panier,
- passer une commande,
- suivre l'état de ses commandes.

### Pour les vendeurs

- créer un compte avec le rôle vendeur,
- publier un produit avec titre, description, prix et image,
- gérer les commandes reçues,
- mettre à jour le statut d'une commande (en attente, confirmée, expédiée, livrée, annulée).

## Stack technique

- PHP 8+
- MySQL / MariaDB
- PDO pour les accès à la base de données
- HTML / CSS / JavaScript léger
- XAMPP pour le lancement local

## Prérequis

Avant de lancer le projet, assurez-vous d'avoir installé :

- XAMPP (Apache + MySQL)
- PHP 8+
- un navigateur web moderne
- un accès à la base MySQL locale

## Installation

1. Clonez ou téléchargez le projet dans le dossier web de XAMPP :

   ```bash
   C:\xampp\htdocs\marketplace
   ```

2. Démarrez Apache et MySQL depuis le panneau de contrôle XAMPP.

3. Vérifiez que votre projet est bien accessible via :

   ```text
   http://localhost/marketplace
   ```

## Configuration de la base de données

1. Ouvrez phpMyAdmin.
2. Créez une base de données nommée :

   ```sql
   marketplace
   ```

3. Importez le fichier SQL suivant :

   ```text
   config/marketplace.sql
   ```

4. La configuration de connexion se trouve dans :

   ```text
   config/database_connexion.php
   ```

   Elle est configurée pour un environnement local par défaut :

   ```php
   $host = "localhost";
   $dbname = "marketplace";
   $user = "root";
   $pass = "";
   ```

   Si votre installation MySQL utilise un mot de passe différent, modifiez ces valeurs.

## Utilisation

### Accès public

- Page d'accueil : `index.php`
- Catalogue : `products/catalog_html.php`
- Inscription : `auth/register_html.php`
- Connexion : `auth/login_html.php`

### Espace vendeur

Un utilisateur avec le rôle `seller` peut ajouter un produit depuis l'interface de gestion, puis consulter les commandes associées.

### Espace acheteur

Un utilisateur avec le rôle `buyer` peut ajouter des produits au panier, modifier quantités et passer une commande.

## Structure du projet

```text
marketplace/
├── auth/
│   ├── dashboard_html.php
│   ├── dashboard_php.php
│   ├── login_html.php
│   ├── login_php.php
│   ├── logout.php
│   ├── register_html.php
│   └── register_php.php
├── config/
│   ├── database_connexion.php
│   └── marketplace.sql
├── orders/
│   ├── checkout_php.php
│   ├── orders_html.php
│   └── orders_php.php
├── products/
│   ├── add_product_html.php
│   ├── add_product_php.php
│   ├── cart_html.php
│   ├── cart_php.php
│   ├── catalog_html.php
│   ├── catalog_php.php
│   ├── products_html.php
│   ├── products_php.php
│   └── uploads/
├── style/
│   └── style.css
├── index.php
├── auth-cookies.txt
├── README.md
└── LICENSE (si présent dans votre environnement)
```

## Points d'attention

- Le projet est en PHP natif et ne suit pas un pattern MVC complet.
- La gestion des sessions et des rôles est réalisée à partir de `$_SESSION`.
- Les images des produits sont stockées dans `products/uploads/`.
- Les fichiers sont répartis par domaine fonctionnel, ce qui facilite la compréhension et l'évolution du projet.
- Pour un usage en production, il serait conseillé d'ajouter :
  - validation plus stricte côté serveur,
  - protection CSRF,
  - gestion des permissions plus robuste,
  - tests automatisés,
  - chiffrement / sécurisation de la configuration.

## Licence

Ce projet est fourni à titre pédagogique. Vérifiez les droits applicables avant toute utilisation commerciale ou redistribution.

## Auteur

Projet réalisé dans le cadre d'un développement web local de marketplace simple et fonctionnelle.
