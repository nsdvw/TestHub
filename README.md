# TestHub

The TestHub application allows to create and pass tests (school exams, quizzes, etc.).
Written on php using symfony framework.

## Requirements
1. debian based os
1. php ^5.5
1. mysql ^5.5
1. webserver apache/nginx with url rewrite tool

## How to install

Clone the repository
```
$ git clone https://github.com/nsdvw/TestHub.git destination_folder
```
Change permissions for logs, cache and sessions, as it shown in official symfony
documentation
```
$ HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx'\
| grep -v root | head -1 | cut -d\  -f1`
$ sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var
$ sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var
```
Install dependencies
```
$ composer install
```
Write connection settings in `app/config/parameters.yml`, may use
parameters.yml.dist as a sample.

Create database
```
$ php bin/console doctrine:database:create
```

Create database schema via migrations tool
```
$ php bin/console doctrine:migrations:migrate
```

## Testing
Create test database (change config in `app/config/config_test.yml` if needed)
```
$ php bin/console doctrine:database:create -e test
```

Create database schema via migrations tool
```
$ php bin/console doctrine:migrations:migrate -e test
```

Run tests
```
$ php phpunit.phar
```
