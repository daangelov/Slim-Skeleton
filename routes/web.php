<?php

use App\Controllers\HomeController;
use App\Controllers\ProjectController;
use App\Middleware\RedirectIfAuthenticated;
use App\Middleware\RedirectIfUnauthenticated;

// $app->add(new Middleware(...)) // adding a global middleware

// Routes access from anyone
$app->get('/', HomeController::class . ':index')->setName('home');

// Routes accessed only if logged
$app->group('', function () {
    $this->group('/projects', function () {
        $this->get('', ProjectController::class . ':index')->setName('projects');
        $this->get('/{id}', ProjectController::class . ':showWithJson')->setName('project.show');

        // GET     -> Read
        // POST    -> Create
        // PUT     -> Update whole record
        // PATCH   -> Update part of the record
        // DELETE  -> Delete

        // OPTIONS -> Returns Allow header with the available methods
        // HEAD    -> Returns only the header of the request // Slim doesn't provide function
        // ANY     -> Any of the above
    });

})->add(new RedirectIfUnauthenticated($container->get('router')));

// Routes accessed only if NOT logged
$app->group('', function () {

    $this->get('/login', function () {

        return 'Login page';
    })->setName('login');

})->add(new RedirectIfAuthenticated($container->get('router')));