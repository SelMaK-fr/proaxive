<?php
declare(strict_types=1);
// Settings
// Error reporting

use PHPMailer\PHPMailer\PHPMailer;

error_reporting(0);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
// Timezone
date_default_timezone_set('Europe/Paris');
$settings = [
    'app' => [
        'name'   => 'Proaxive', // Sitename or your business name
        'version' => '2.0.10',
        'env'    => env('APP_ENV', 'production'),
        'debug'  => true,
        'urlPath' => '/',
        'domainUrl' => env('DOMAIN_PATH', 'http://localhost:8080'),
        'locale' => 'fr',
        'timezone' => 'UTC',
        'secretKey' => env('APP_SECRET', 'secret'),
        'calendar_url' => '/admin/booking/get/all', // Return API Proaxive or other API
    ],
    'settings' => [
        'rootPath' => dirname(__DIR__),
        'publicPath' => dirname(__DIR__) . '/public/',
        'licenceKey' => '',
        'custom_css' => false
    ],
    'view' => [
        'path'  => dirname(__DIR__) . '/templates',
        'cache' => false,
    ],
    'db' => [
        'host' => env('DB_HOST', 'localhost'),
        'dbname' => env('DB_NAME'),
        'user' => env('DB_USER'),
        'password' => env('DB_PASSWORD'),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'options' => [
            // Turn off persistent connections
            PDO::ATTR_PERSISTENT => false,
            // Enable exceptions
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // Emulate prepared statements
            PDO::ATTR_EMULATE_PREPARES => true,
            // Set default fetch mode to array
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Set character set
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci'
        ],
    ],
    'session' => [
        'name' => 'app',
        'lifetime' => 7200,
        'path' => null,
        'domain' => null,
        'secure' => false,
        'httponly' => true,
        'cache_limiter' => 'nocache',
    ],
    'mailer' => [
        'dsn' => env('MAIL_DSN', 'smtp://127.0.0.1:1025'),
        'debug' => false,
        'host' => env('MAIL_HOST', 'localhost'),
        'SMTPAuth' => env('MAIL_SMTPAUTH', false), // false if local server
        'from' => env('MAIL_FROM', 'admin@proaxive.in'),
        'username' => env('MAIL_USERNAME', 'user'),
        'password' => env('MAIL_PASSWORD', 'password'),
        'SMTPSecure' => env('MAIL_SMTPSECURE', false), // PHPMailer::ENCRYPTION_SMTPS or PHPMailer::ENCRYPTION_STARTTLS // false if local server
        'port' => env('MAIL_PORT', 1025)
    ],
    'logger' => [
        'name' => 'proaxive-app',
        'path' => dirname(__DIR__) . '/var/logs',
        'level' => \Monolog\Level::Info,
    ],
    'storage' => [
        'bao' => dirname(__DIR__) . '/storage/app/bao',
        'backups' => dirname(__DIR__) . '/storage/backups/',
        'documents' => dirname(__DIR__) . '/storage/documents/',
        'gallery' => dirname(__DIR__) . '/storage/gallery/',
        'temp' => dirname(__DIR__) . '/storage/tmp'
    ]
];

// ...

return $settings;