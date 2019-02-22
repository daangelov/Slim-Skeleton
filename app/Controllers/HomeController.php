<?php

namespace App\Controllers;

use App\Models\User;
use PDO;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

/**
 * @property Twig view
 * @property PDO db
 */
class HomeController extends Controller
{
    public function index(Request $request, Response $response)
    {
        $user = User::where($this->db, ['email' => 'drago@test.com']);
        if (!$user) {
            // Error
            return $this->view->render($response, 'home.twig', compact('user'));
        }

        $user = $user->fetch(PDO::FETCH_OBJ);
        return $this->view->render($response, 'home.twig', compact('user'));
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