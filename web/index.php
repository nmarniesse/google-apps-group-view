<?php

require_once __DIR__.'/../vendor/autoload.php'; 

use GoogleApiClient\Client\GoogleClientFactory;
use GoogleGroupView\Action\GetGroupsAndMembers;
use GoogleGroupView\Domain\Service\GroupService;
use GoogleGroupView\Config\Config;

$app = new Silex\Application();

// Config
$configFile     = __dir__ . '/../config/parameters.yml';
$configFileDist = __dir__ . '/../config/parameters.yml.dist';
if (!file_exists($configFile)) {
    copy($configFileDist, $configFile);
}

// Register twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path'    => __DIR__ . '/../views',
    'twig.options' => [
        'debug' => true,
    ],
));

// Var
$applicationName     = 'Google Group View';
$scopes              = implode(' ', [
    Google_Service_Directory::ADMIN_DIRECTORY_GROUP_READONLY,
    Google_Service_Directory::ADMIN_DIRECTORY_GROUP_MEMBER_READONLY,
]);
$clientSecretJsonFile = __DIR__ . '/../client_secret.json';
$credentialsPath      = __DIR__ . '/../.credentials/credentials.json';

// Services
$app['app_config'] = function () use ($configFile) {
    return new Config($configFile);
};
$app['google_client'] = $app->factory(function() use ($applicationName, $scopes, $clientSecretJsonFile, $credentialsPath) {
    return GoogleClientFactory::getClient($applicationName, $scopes, $clientSecretJsonFile, $credentialsPath);
});
$app['group_service'] = $app->factory(function($app) {
    return new GroupService($app['google_client']);
});

// Routing
$app->get('/', function() use ($app) {
    $action = new GetGroupsAndMembers($app);
    return $action($app['app_config']->config['app']['restrict-domain']);
});


$app->run(); 
