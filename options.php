<?php

return [
   [
        'short' => "",
        'long' => "file:",
        'description' => "  --file\t\t <csv file name>\t\t this is the name of the CSV to be parsed\n"
    ],
    [
        'short' => "",
        'long' => "create_table",
        'description' => "  --create_table \t\t\t\t\t this will cause the MySQL users table to be built (and no further action will be taken)\n"
    ],
    [
        'short' => "",
        'long' => "dry_run",
        'description' => "  --dry_run \t\t\t\t\t\t this will be used with the --file directive in case we want to run the script but not insert
                                 \t\t\t into the DB. All other functions will be executed, but the database won't be altered\n"
    ],
    [
        'short' => "u:",
        'long' => "",
        'description' => "  -u \t\t\t\t\t\t\t MySQL username\n"
    ],
    [
        'short' => "p:",
        'long' => "",
        'description' => "  -p \t\t\t\t\t\t\t MySQL password\n"
    ],
    [
        'short' => "h:",
        'long' => "",
        'description' => "  -h \t\t\t\t\t\t\t MySQL host\n"
    ],
    [
        'short' => "",
        'long' => "help",
        'description' => "  --help \t\t\t\t\t\t which will output the above list of directives with details\n"
    ]
];