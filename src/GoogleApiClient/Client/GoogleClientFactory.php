<?php

namespace GoogleApiClient\Client;

use Google_Client;

class GoogleClientFactory
{
    /**
     * @param  string $applicationName
     * @param  string $scopes
     * @param  string $clientSecretJsonFile
     * @param  string $credentialsPath
     * @return Google_Client
     */
    public static function getClient($applicationName, $scopes, $clientSecretJsonFile, $credentialsPath)
    {
        $client = new Google_Client();
        $client->setApplicationName($applicationName);
        $client->setScopes($scopes);
        $client->setAuthConfig($clientSecretJsonFile);
        $client->setAccessType('offline');

        // Load previously authorized credentials from a file.
        if (file_exists($credentialsPath)) {
            $accessToken = json_decode(file_get_contents($credentialsPath), true);
        } else {
            if (php_sapi_name() != 'cli') {
                throw new \Exception('You need authorization to run this app. Please run index.php with command line to authorize');
            }

            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

            // Store the credentials to disk.
            if(!file_exists(dirname($credentialsPath))) {
                mkdir(dirname($credentialsPath), 0700, true);
            }
            file_put_contents($credentialsPath, json_encode($accessToken));
            printf("Credentials saved to %s\n", $credentialsPath);
        }
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $resfreshToken = $client->getRefreshToken();
            $client->fetchAccessTokenWithRefreshToken($resfreshToken);
            $newAccessToken = $client->getAccessToken();
            $newAccessToken['refresh_token'] = $resfreshToken;
            file_put_contents($credentialsPath, json_encode($newAccessToken));
        }
        return $client;
    }
}
