<?php 

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/HandleCsv.php';

$opts = getopt("u:p:h:", ["file:", "create_table", "dry_run", "help"]);

if (isset($opts['help'])){
 echo <<<EOD
Usage: php user_upload.php [options]

Options:
    --file [csv file name]     this is the name of the CSV to be parsed
    --create_table             this will cause the MySQL users table to be built (and no further action will be taken)
    --dry_run                  this will be used with the --file directive in case we want to run the script but not insert
                               into the DB. All other functions will be executed, but the database won't be altered"
    -u [username]              MySQL username
    -p [password]              MySQL password
    -h [host]                  MySQL host
    --help                     Which will output the above list of directives with details

EOD;
    exit;
}

$db = Database::getInstance()->getConnection();
$handle = new HandleCsv($db, isset($opts['dry_run']));

if(isset($opts['create_table'])) {
    $handle->createTable();
    exit;
}

if (isset($opts['file'])) {
     $csv = $handle->readCsv($opts['file']);
     $handle->insert($csv);
} else {
      echo "Error: --file option requires a filename. Example: php user_upload.php --file [filename] \n";
      exit;
}










