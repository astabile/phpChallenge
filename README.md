# About the project
Create a web app that will have two parts. The first part involves entries (similar to blog posts)
from registered users (functionality to view, create, and edit entries). The second part is to be
able to see a single user's entries and tweets (from Twitter).

# Tools previously installed:
- XAMPP: https://www.apachefriends.org/es/download.html
- Composer: https://getcomposer.org
- Yarn: https://yarnpkg.com/lang/en/docs/install
- Git bash: https://git-scm.com/downloads

# Code steps
Clonate project and access to the repo:
```
git clone https://github.com/astabile/phpChallenge.git blog
cd blog
```

Update libraries
```
composer update
```

Install sass-loader and node-sass dependencies:
```
yarn add sass-loader node-sass --dev
```

Compile .css and .js files into assets:
```
yarn run encore dev --watch
```

Clear the caché and run the application:
```
php bin/console cache:clear
php bin/console server:run
```

# Database steps
- Import the database script (create database, tables and structure): **/database/blog.sql**
- Import the user populate script: **/database/user.sql**
- Import the entry populate script: **/database/entry.sql**
- Import the twitter populate script: **/database/twitter.sql**
 
