<?php
$viewJsonFile = file_get_contents(dirname(__DIR__) . '/public_html/src/Database/db.json');
$viewJson = json_decode($viewJsonFile, false);
// Get PDO object
$pdo = new PDO(
    'mysql:host='.$viewJson->db_host.';dbname='.$viewJson->db_name.';charset=utf8', $viewJson->db_user, $viewJson->db_passwd,
    array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8 COLLATE utf8_unicode_ci',
    )
);

return [
    'paths' => [
        'migrations' => __DIR__ . '/db/migrations',
        'seeds' => __DIR__ . '/db/seeds'
    ],

    'environments' => [
        'default_migration_table' => 'pl15x_phinxlog',
        'default_database' => 'production',
        'production' => [
            // Database name
            'name' => $viewJson->db_name,
            'table_prefix' => 'pl15x_',
            'connection' => $pdo,
        ]
    ]
];