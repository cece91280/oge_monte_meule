# OGE Monte Meuble - Plateforme de devis pour déménagement

## Présentation

**OGE Monte Meuble** est une plateforme web permettant aux futurs clients d’une société de déménagement de :
- Réaliser des devis en ligne pour leur déménagement, incluant des services additionnels selon les besoins (emballage, aide au portage, etc.)
- Consulter en temps réel les disponibilités sur un calendrier dynamique basé sur les données de la société
- **Contacter la société via une page dédiée** pour toute question ou demande spécifique
- **Déposer un avis à la fin de la prestation**, pour partager leur expérience

L’objectif est de simplifier la création de devis personnalisés, de centraliser les demandes, et de donner de la visibilité à la qualité des services proposés grâce aux avis clients.

## Fonctionnalités principales

- Création rapide de devis en ligne
- Ajout de services complémentaires lors de la demande de devis
- Affichage du calendrier des disponibilités (données issues de la BDD)
- Suivi de l’état des demandes (statuts : devis en attente, validé, etc.)
- Gestion et historique des demandes pour les utilisateurs
- **Page de contact** permettant aux clients de poser leurs questions ou d’entrer en relation avec la société
- **Système d’avis clients :**
    - Les clients peuvent laisser un avis à la fin de leur prestation
    - Les avis sont affichés sur le site pour donner de la visibilité et rassurer les futurs clients

## Public visé

Le projet s’adresse principalement aux futurs clients de la société de déménagement souhaitant obtenir un devis personnalisé en ligne, contacter facilement l’équipe, et consulter les avis d’autres clients.

## Technologies utilisées

- **Symfony** (framework PHP)
- **MySQL** (base de données)
- **Docker** (environnement de développement)
- **Composer** (gestion des dépendances PHP)
- **Twig** (moteur de templates)
- **Javascript**, **HTML**, **CSS** (frontend)

## Installation et pré-requis

### Prérequis

- [Docker](https://www.docker.com/) installé sur votre machine
- [Composer](https://getcomposer.org/) pour la gestion des dépendances PHP

### Installation rapide

1. **Cloner le projet**

   ```bash
   git clone <https://github.com/cece91280/oge_monte_meule.git>
   cd oge_monte_meuble

2. **Lancer les conteneurs Docker**

   ```bash
   Copier
   Modifier
   docker compose up -d

3. **Installer les dépendances PHP (si besoin)**

   ```bash
   Copier
   Modifier
   docker compose exec php composer install

4. **Configurer la base de données**

Modifier le fichier .env ou .env.local avec les paramètres MySQL fournis par Docker.

5. **Effectuer les migrations**

    ```bash
    Copier
    Modifier
    docker compose exec php bin/console doctrine:migrations:migrate

6. **Accéder à l’application**

Ouvrez votre navigateur à l’adresse : 127.0.0.1:8080