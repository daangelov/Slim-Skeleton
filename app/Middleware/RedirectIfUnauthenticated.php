<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Interfaces\RouterInterface;

/**
 * We can use the logic from the controllers and create an abstract
 * class Middleware that takes $container. But this is less heavy
 * because we are passing just the router.
 *
 */
class RedirectIfUnauthenticated
{
    protected $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        if (false) {//!isset($_SESSION['user_id'])) {
            return $response->withRedirect($this->router->pathFor('login'), 302);
        }

        return $next($request, $response);
    }
}