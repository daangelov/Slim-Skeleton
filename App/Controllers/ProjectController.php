<?php

namespace App\Controllers;

use App\Models\Project;
use PDO;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;
use Slim\Views\Twig;

/**
 * @property Twig view
 * @property PDO db
 */
class ProjectController extends Controller
{

    /**
     * @param Request $request
     * @param Response $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(Request $request, Response $response)
    {
        $projects = $this->db
            ->query("SELECT * FROM projects")
            ->fetchAll(PDO::FETCH_ASSOC);

        return $this->view->render($response, 'home.twig', compact("projects"));
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function show(Request $request, Response $response, array $args)
    {
        $project = $this->db->prepare("SELECT * FROM projects WHERE id = :id");

        $project->execute(['id' => $args['id']]);

        $project = $project->fetch(PDO::FETCH_OBJ);

        if (!$project) {
            return $this->view->render($response->withStatus(StatusCode::HTTP_NOT_FOUND), 'errors/404.twig');
        }

        return $this->view->render($response, 'home.twig', compact("project"));
    }

    public function showWithJson(Request $request, Response $response, array $args)
    {
        $project = $this->db->prepare("SELECT * FROM projects WHERE id = :id");
        $project->execute(['id' => $args['id']]);

        $project = $project->fetch(PDO::FETCH_OBJ);

        if (!$project) {
            return $response->withJson(['message' => 'This record does not exist!'], StatusCode::HTTP_NOT_FOUND);
        }

        return $response->withJson($project, StatusCode::HTTP_OK);
    }

    public function test2(Request $request, Response $response)
    {
        $projects = $this->db->query("SELECT * FROM projects")->fetchAll(PDO::FETCH_CLASS, Project::class);

        return $this->view->render($response, 'home.twig', $projects);
    }
}