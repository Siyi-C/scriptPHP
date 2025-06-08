<?php 

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/HandleCsv.php';

$options = require __DIR__ . "/options.php";

$shortOptions = "";
$longOptions = [];

foreach ($options as $opt) {
    if(!empty($opt['short'])) {
        $shortOptions .= $opt['short'];
    }
     if(!empty($opt['long'])) {
        $longOptions[] = $opt['long'];
    }
}

$opts = getopt($shortOptions, $longOptions);

if (isset($opts['help'])){
    echo "\nUsage: php user_upload.php [options]\n\n";
    echo "Options:\n";
    
    foreach ($options as $opt) {
        echo $opt['description'];
    }
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









