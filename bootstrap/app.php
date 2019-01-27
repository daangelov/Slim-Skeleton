<?php

require dirname(__DIR__) . '/vendor/autoload.php';

// Load environment variables form .env
$dotenv = Dotenv\Dotenv::create(dirname(__DIR__));
$dotenv->load();

// Connect to database
$db = \App\Utils\Db::getConnection();

// Start session
$sessionHandler = new App\Utils\Session($db);
session_set_save_handler($sessionHandler);
session_name(getenv('SESSION_NAME'));
session_start();

// Create app
$app = new Slim\App([
    'settings' => [
        'displayErrorDetails' => getenv('APP_DEBUG') === "true",
        'determineRouteBeforeAppMiddleware' => true
    ]
]);

require __DIR__ . '/container.php';

require dirname(__DIR__) . '/routes/web.php';