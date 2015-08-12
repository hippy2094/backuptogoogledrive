<?php
/* Backup to GoogleDrive example script
   Copyright (C) 2013 Matthew Hipkin <http://www.matthewhipkin.co.uk>

   settings.inc.php
   Settings required for script execution */

  // User home directory (absolute)
  $homedir = trim(shell_exec("cd ~ && pwd"))."/"; // If this doesn't work, you can provide the full path yourself
  // Site directory (relative)
  $sitedir = "www/"; 
  // Base filename for backup file
  $fprefix = "sitebackup-";
  // Base filename for database file
  $dprefix = "dbbackup-";
  // MySQL username
  $dbuser = "root";
  // MySQL password
  $dbpass = "root";
  // MySQL database
  $dbname = "test";
  // Google Drive Client ID
  $clientId = ""; // Get this from the Google APIs Console https://code.google.com/apis/console/
  // Google Drive Client Secret
  $clientSecret = ""; // Get this from the Google APIs Console https://code.google.com/apis/console/
  // Google Drive authentication code
  $authCode = ""; // Needs to be set using getauthcode.php first!    
    
?>