# TestHub

The TestHub application allows to create and pass tests (school exams, quizzes, etc.).
Written on php using symfony framework.

## Requirements
1. debian based os
1. php ^5.4
1. mysql ^5.5
1. webserver apache/nginx with url rewrite tool

## How to install

1. Clone the repository
```
$ git clone https://github.com/nsdvw/TestHub.git destination_folder
```
2. Change permissions for logs, cache and sessions, as it shown in official symfony
documentation
```
$ HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx'\
| grep -v root | head -1 | cut -d\  -f1`
$ sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var
$ sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var
```
3. Install dependencies
```
$ composer install
```
4. Write connection settings in `app/config/parameters.yml`, may use
parameters.yml.dist as a sample
5. Create database schema via console using doctrine annotations
```
$ php bin/console doctrine:schema:update --dump-sql --force
```
