<?php

// $config = [
//     "db" => [
//         "path" => "/var/www/db/db.sqlite"
//     ]
// ];

$config['db']['host'] = getenv('MYSQL_HOST');
$config['db']['database'] = getenv('MYSQL_DATABASE');
$config['db']['username'] = getenv('MYSQL_USER');
$config['db']['password'] = getenv('MYSQL_PASSWORD');
