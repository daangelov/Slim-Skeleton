<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Utils\Db;
use App\Utils\Session;
use Dotenv\Dotenv;
use Dotenv\Environment\Adapter\EnvConstAdapter;
use Dotenv\Environment\Adapter\ServerConstAdapter;
use Dotenv\Environment\DotenvFactory;
use Slim\App;

// Load environment variables form .env
$factory = new DotenvFactory([new EnvConstAdapter(), new ServerConstAdapter()]);
$dotenv = Dotenv::create(dirname(__DIR__), null, $factory);
$dotenv->load();

// Connect to database
$db = Db::getConnection();

// Start session
$sessionHandler = new Session($db);
session_set_save_handler($sessionHandler);
session_name($_ENV['SESSION_NAME']);
session_start();

// Create app
$app = new App([
    'settings' => [
        'displayErrorDetails' => $_ENV['APP_DEBUG'] === "true",
        'determineRouteBeforeAppMiddleware' => true
    ]
]);

require __DIR__ . '/container.php';

require dirname(__DIR__) . '/routes/web.php';