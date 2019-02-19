<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Interfaces\RouterInterface;

class RedirectIfAuthenticated
{
    protected $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        if (true) { //isset($_SESSION['user_id'])) {
            return $response->withRedirect($this->router->pathFor('login'), 302);
        }

        return $next($request, $response);
    }
}