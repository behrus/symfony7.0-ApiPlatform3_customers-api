
# CCA - Customer Contact Api

A prototype for managing customer contacts implemented with Symfony7.0, PlatformApi3 and PHP8.3 with PHPUnit testing.


# Installation/Configuration

Please just run 'composer install' in the terminal. (Composer Version2)
In addition to Symfony7.0 (no webapp installation), the ApiPlatform3 is installed, with which the API was implemented.

LAMP, WAMP or MAMP are ideal for local installation. (Nginx can also be used instead of Apache2) Or Docker.

PHP version during development was 8.3.
MySQL as a database in version 8.0.36-0ubuntu0.22.04.1.
PHPUnit v9.6 for testing as a Symfony pack installation. 
PHP Version bei der Entwicklung war 8.3.  

After installing Composer, no additional settings should be required other than creating the .env files for the local environment and test environment.


## Calling the project or the endpoints
If Symfony CLI is installed, it is recommended to start the server with “symfony servo -d”.

Without setting up a virtual host, the project is then available in the browser at “http://127.0.0.1:8000/?api”.
Here you have an overview of the endpoints and information about them.

The endpoints can be executed via the website. However, authentication on the startpage in browser is not available.

**The API client “Postman” was used for development and interaction with the endpoints. The set up authentication was also tested with Postman.**


## CRUD on the endpoints

The specifications for the prototype led to the creation of the endpoints below with the following functionalities:

- /api/customers       => Customer Entity - CRUD - Role User - *Search- and  
  order Filters für Datensätze* der Customers
- /api/addresses       => Address Entity   -  CRUD - Role User
- /api/city            => City Entity          -  CRUD - Role User
- /api/country         => Country Entity    -  CRUD - Role User
- /api/api_tokens      => ApiToken Entity  -  CRUD authorized
  only for the role admin
- /api/users           => User Entity          -  CRUD though
  authorized for the role admin
  
All entities are connected to other entities.
For example, deleting a country results in an OrphanRemoval action against the city and further deletion of the addresses.


## Auth

Auth ultimately became its own development.
The focus was on Auth, which is why login functionality is currently not available.

The workflow for Auth is as follows:

- Create user in the user table with mandatory fields: email,
  password and the role ['ROLE_USER']
- Then call the generate-token.php file in the project directory in the terminal
  apitoken is generated and output in the terminal. You can build this as a Symfony command.
- Enter the created token of user into the field
  stored in the apitoken table in the database

*There is a specially developed "ApiTokenAuthenticator" class in the "src/Security" folder that determines/controls the workflow.*

## Authorisierung - Access Control

All endpoints can only be reached by users with the role “ROLE_USER”.

Additionally: The user and api_tokens endpoints can only be reached by users with the “ROLE_ADMIN”.
These settings are of course very general and had to be further refined by assigning rights for the endpoint methods and with “voters” (ownership) in Symfony.

## HTTP header settings for interacting with the endpoints

When using a Rest client (Postman), the following settings must be made for the Http header:
- Accept          => application/ld+json
- api                 => <API_TOKE>  (See Authentication and generation of ApiToken section)
- Content-Type => application/ld+json

**If PATCH is used as a method, the value for the “Content-Type” key must be swapped with “application/merge-patch+json”..

# Logging - Monolog
All requests and responses with monologue are logged in the system.
For this purpose, an EventListener “src/EventListener/LogRequestAndResponseEventListener” was developed, which responds to requests and responses and writes the data to the log file in the “var/log/request” folder.

# PHPUnit Tests

To illustrate this, ApiTests were created for the Cities and Countries endpoints.
Please enter DATABASE_URL in .env.test.

There are city and country data fixtures for the entity that are used in the tests.


-----Test Umgebung ---------

- symfony console --env=test doctrin:migrations:migrate //Migration
  ausführen
- php bin/phpunit tests //Tests ausführen

