
# Projet 6 - Openclassrooms

Ce projet est basé sur Symfony 6 et vise à développer un site communautaire au tour des figures de snowboard.


## Prérequis

Avant de commencer, assurez-vous d'avoir installé les éléments suivants :

- PHP 8.1 ou supérieur
- Composer
- Node.js et npm

## Installation

Suivez les étapes ci-dessous pour installer et configurer le projet sur votre machine locale.

### 1. Clonez le dépôt Git

```bash
git clone https://github.com/cactuseure/SnowTricks.git
```

### 2. Installez les dépendances via Composer


```bash
composer install
```

### 3. Configurez le fichier .env

Créer votre fichier `.env.local` pour y ajouter les informations de connexion à votre base de données et de connexion au serveur SMTP

```
DATABASE_URL =
MAILER_DSN =
```

Assurez-vous de remplir les valeurs appropriées pour votre configuration. Des expliquations sont fournit dans le fichier `.env`

### 4. Créez la base de données

```bash
php bin/console doctrine:database:create
```

Exécutez les migrations pour créer les tables dans votre base de données

```bash
php bin/console doctrine:migrations:migrate
```

### 5. Compiler les assets

```bash
yarn install
```

```bash
yarn build
```

### 6. Lancez le serveur Symfony
```bash
symfony server:start
```
## Contribution

Si vous souhaitez contribuer à ce projet, veuillez suivre les étapes suivantes :

Fork du dépôt sur GitHub.
Clonez votre fork sur votre machine locale.
Créez une branche pour votre contribution :
- git checkout -b ma-contribution.
- Faites vos modifications et committez-les : git commit -m "Ajout de fonctionnalité X".
- Poussez votre branche vers votre fork : git push origin ma-contribution.
- Créez une pull request depuis votre fork vers ce dépôt principal.

## Licence

Ce projet est sous licence GNU GPLv3. Pour plus de détails, veuillez consulter le fichier `LICENSE.md`.

[![GNU License](https://img.shields.io/badge/License-GNU%20GPL-blue)](https://choosealicense.com/licenses/gpl-3.0/)