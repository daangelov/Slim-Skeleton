<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

/**
 * @property Twig view
 */
class HomeController extends Controller
{
    public function index(Request $request, Response $response)
    {
        return $this->view->render($response, 'home.twig');
    }

    // Basic methods

    // index -> get all resources
    // show  -> get one resource

    // create -> get create page for resource
    // store  -> create resource

    // edit  -> get edit page for resource
    // update -> update resource

    // destroy -> delete resource

    // Table with all status codes https://www.restapitutorial.com/httpstatuscodes.html
}