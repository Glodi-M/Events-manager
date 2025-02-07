# Events Manager

## Prérequis

Avant de commencer, assurez-vous d'avoir les éléments suivants installés sur votre machine :

- [PHP](https://www.php.net/) (version 7.4 ou supérieure)
- [Composer](https://getcomposer.org/)
- [Symfony CLI](https://symfony.com/download)
- [XAMPP](https://www.apachefriends.org/index.html) (pour démarrer le serveur)

## Installation

1. Clonez le dépôt du projet sur votre machine locale :

    ```bash
    git clone https://github.com/Glodi-M/Events-manager.git
    ```

2. Accédez au répertoire du projet :

    ```bash
    cd Events-manager
    ```

3. Installez les dépendances nécessaires :

    ```bash
    composer install
    ```

## Démarrage du projet

Pour démarrer le projet en mode développement, assurez-vous que XAMPP est en cours d'exécution, puis exécutez la commande suivante :

```bash
symfony server:start
```

Cette commande lancera le serveur de développement et vous pourrez accéder à l'application via [http://localhost:8080](http://localhost:8080).

## Scripts disponibles

- `symfony server:start` : Démarre le serveur de développement.
- `composer run-script build` : Compile l'application pour la production.
- `composer test` : Lance les tests unitaires.

## Structure du projet

Voici un aperçu de la structure du projet :

```
Events-manager/
├── bin/
├── config/
├── public/
├── src/
│   ├── Controller/
│   ├── Entity/
│   ├── Repository/
│   ├── ...
├── templates/
├── tests/
├── translations/
├── var/
├── vendor/
├── .env
├── composer.json
├── README.md
```

## Contribution

Les contributions sont les bienvenues ! Veuillez consulter le fichier `CONTRIBUTING.md` pour plus d'informations.

## Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.