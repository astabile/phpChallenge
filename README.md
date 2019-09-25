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
composer require symfony/asset
composer require annotations
composer require validator
composer require template
composer require security-bundle
composer require abraham/twitteroauth
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

Create Admin controller (Use entity manager and repositories for the entities to retrieve data):
```
php bin/console make:controller  
> AdminController
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

Create Twitter entity:
```
php bin/console make:entity  
> Twitter
```

Create tables:
```
php bin/console doctrine:schema:update --force  
```

Install doctrine fixtures for populate tables (Optional):
```
composer require --dev doctrine/doctrine-fixtures-bundle
```

Run the fixture with some dummy data (DataFixtures):
```
php bin/console doctrine:fixtures:load 	
```

Install HWIOAuth Bundle and several third-party libraries:
```
composer require hwi/oauth-bundle:"^0.6.3" 
composer require php-http/guzzle6-adapter:"^1.1" 
composer require php-http/httplug-bundle:"^1.13"
composer update
```

Adding required on composer.json:
```
"hwi/oauth-bundle": "^0.6.3",
"php-http/guzzle6-adapter": "^1.1",
"php-http/httplug-bundle": "^1.13",
```

Install Symfony's Webpack Encore:
```
composer require webpack-encore
```

Install sass-loader and node-sass dependencies:
```
yarn add sass-loader node-sass --dev
```

Compile .css and .js files into assets:
```
yarn run encore dev --watch
```

Install jQuery:
```
yarn add jquery --dev
```

Install bootstrap-sass:
```
yarn add bootstrap-sass --dev
```

Install TwitterOAuth library:
```
composer require abraham/twitteroauth
```

Clear the caché and run the application:
```
php bin/console cache:clear
php bin/console server:run
```