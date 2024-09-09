## PROJET BLOG SYMFONY

#### Création des entités
- User
- Article
- Comments

#### Mise en place des fixtures
1. Installation du bundle

`composer require --dev doctrine/doctrine-fixtures-bundle`

2. Utilisation de faker
composer require --dev fakerphp/faker

3. Execution des fixtures : 
php bin/console doctrine:fixtures:load

4. Création des controllers
php bin/console make:controller BlogController
