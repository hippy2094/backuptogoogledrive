<?php
/* Backup to GoogleDrive example script
   Copyright (C) 2013 Matthew Hipkin <http://www.matthewhipkin.co.uk>

   getauthcode.php
   Provides URL to authenticate application */
   
  require_once("google-api-php-client/src/Google_Client.php");
  require_once("google-api-php-client/src/contrib/Google_DriveService.php");
  include("settings.inc.php");  

  $client = new Google_Client();
  // Get your credentials from the APIs Console
  $client->setClientId($clientId);
  $client->setClientSecret($clientSecret);
  $client->setRedirectUri($requestURI);
  $client->setScopes(array('https://www.googleapis.com/auth/drive'));

  $service = new Google_DriveService($client);

  $authUrl = $client->createAuthUrl();

  echo "Authorisation URL:\n$authUrl\n\n";
  echo "Please visit the URL above and then save the given value into settings.inc.php\n";
?>