# google-apps-group-view

Web interface to visualize groups and members from a Google Suite account

## Requirement

PHP 5.4.0 or higher, and [composer](https://getcomposer.org/)

## Install

Install all dependencies with composer:

```
$ composer install
```

## Google authentication

Turn on API on Google Developers Console : 
- Create a project name
- In the Credentials tab, create a OAuth clientID with the 'other' type
- Validate and download the json file

Initialize authorization on application
- copy the previous json file as *client_secret.json* in the root directory of the directory application
- run web/index.php in cli mode : 

```
$ php web/index.php
```

Copy/paste the url in your favorite browser and validate authorization to obtain a token. Copy this token into console. 

That's it. 

## Webserver configuration

Please follow [Silex documentation](http://silex.sensiolabs.org/doc/master/web_servers.html)  
Root path for the webserver is web/
