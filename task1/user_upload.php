<?php 

require_once __DIR__ . '/Database.php';
use Script\Database;

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
// var_dump($opts);

if (isset($opts['help'])){
    echo "\nUsage: php user_upload.php [options]\n\n";
    echo "Options:\n";
    
    foreach ($options as $opt) {
        echo $opt['description'];
    }
    exit;
}

// if (!isset($opts['file'])) {
//      echo "Error: --file option requires a filename. Example: php user_upload.php --file [filename] \n";
// }

$db = Database::getInstance()->getConnection();


