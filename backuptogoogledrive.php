<?php
/* Backup to GoogleDrive example script
   Copyright (C) 2013 Matthew Hipkin <http://www.matthewhipkin.co.uk>

   backuptogoogledrive.php
   Main script file which creates gzip files and sends them to GoogleDrive */
   
  set_time_limit(0);
  require_once("google-api-php-client/src/Google_Client.php");
  require_once("google-api-php-client/src/contrib/Google_DriveService.php");
  include("settings.inc.php");
  
  if($authCode == "") die("You need to run getauthcode.php first!\n\n");
  
  /* PREPARE FILES FOR UPLOAD */
  
  // Use the current date/time as unique identifier
  $uid = date("YmdHis");
  // Create tar.gz file
  shell_exec("cd ".$homedir." && tar cf - ".$sitedir." -C ".$homedir." | gzip -9 > ".$homedir.$fprefix.$uid.".tar.gz");
  // Dump datamabase
  shell_exec("mysqldump -u".$dbuser." -p".$dbpass." ".$dbname." > ".$homedir.$dprefix.$uid.".sql");
  shell_exec("gzip ".$homedir.$dprefix.$uid.".sql");
  
  /* SEND FILES TO GOOGLEDRIVE */
  
  $client = new Google_Client();
  // Get your credentials from the APIs Console
  $client->setClientId($clientId);
  $client->setClientSecret($clientSecret);
  $client->setRedirectUri($requestURI);
  $client->setScopes(array("https://www.googleapis.com/auth/drive"));
  $service = new Google_DriveService($client);  
  // Exchange authorisation code for access token
  if(!file_exists("token.json")) {
    // Save token for future use
    $accessToken = $client->authenticate($authCode);      
    file_put_contents("token.json",$accessToken);  
  }
  else $accessToken = file_get_contents("token.json");
  $client->setAccessToken($accessToken);  
  // Upload file to Google Drive  
  $file = new Google_DriveFile();
  $file->setTitle($fprefix.$uid.".tar.gz");
  $file->setDescription("Server backup file");
  $file->setMimeType("application/gzip");
  $data = file_get_contents($homedir.$fprefix.$uid.".tar.gz");
  $createdFile = $service->files->insert($file, array('data' => $data, 'mimeType' => "application/gzip",));
  // Process response here....
  print_r($createdFile);      
  // Upload database to Google Drive
  $file = new Google_DriveFile();
  $file->setTitle($dprefix.$uid.".sql.gz");
  $file->setDescription("Database backup file");
  $file->setMimeType("application/gzip");
  $data = file_get_contents($homedir.$dprefix.$uid.".sql.gz");
  $createdFile = $service->files->insert($file, array('data' => $data, 'mimeType' => "application/gzip",));
  // Process response here....
  print_r($createdFile);  
  
  /* CLEANUP */
  
  // Delete created files
  unlink($homedir.$fprefix.$uid.".tar.gz");
  unlink($homedir.$dprefix.$uid.".sql.gz");
  
  /* References:
       https://developers.google.com/drive/quickstart-php */
?>