# Steps
Tools previously installed:
- XAMPP: https://www.apachefriends.org/es/download.html
- Composer: https://getcomposer.org
- Yarn: https://yarnpkg.com/lang/en/docs/install
- Git bash: https://git-scm.com/downloads

Install Symfony and create project:
```
composer create-project -s beta symfony/skeleton blog
cd blog
```

Install Doctrine and libraries:
```
composer require symfony/orm-pack
composer require symfony /maker-bundle --dev
composer require annotations
composer require validator
composer require template
composer require security-bundle
```

Install Symfony’s web server:
```
composer require server --dev
```

Config .env file. Replace the DATABASE_URL value: 
> DATABASE_URL=mysql://root:@127.0.0.1:3306/blog

Create the database:
```
php bin/console doctrine:database:create
```

Create Blog controller:
```
php bin/console make:controller  
> BlogController
```

Create User entity:
```
php bin/console make:entity  
> User
```

Create Entry entity:
```
php bin/console make:entity  
Entry
```

Create tables:
```
php bin/console doctrine:schema:update --force  
```

Install doctrine fixtures for populate tables:
```
composer require --dev doctrine/doctrine-fixtures-bundle
```

Run the fixture with some dummy data (DataFixtures):
```
php bin/console doctrine:fixtures:load 	
```
