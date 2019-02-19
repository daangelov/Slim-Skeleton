<?php

namespace App\Handlers;

use Slim\Handlers\AbstractHandler;
use Slim\Http\Body;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;
use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface;
use UnexpectedValueException;


/**
 * Custom Not Found handler.
 *
 * It outputs a simple message in either JSON, XML or HTML based on the
 * Accept header. Override function determineContentType to change this
 */
class NotFoundHandler extends AbstractHandler
{
    protected $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    /**
     * Invoke Not Found handler
     *
     * @param Request $request The most recent Request object
     * @param Response $response The most recent Response object
     * @return Response
     */
    public function __invoke(Request $request, Response $response)
    {
        if ($request->getMethod() === 'OPTIONS') {
            $output = $this->renderPlainNotFoundOutput($response);
        } else {
            $contentType = $this->determineContentType($request);
            switch ($contentType) {
                case 'application/json':
                    $output = $this->renderJsonNotFoundOutput($response);
                    break;

                case 'text/xml':
                case 'application/xml':
                    $output = $this->renderXmlNotFoundOutput($response, $contentType);
                    break;

                case 'text/html':
                    $output = $this->renderHtmlNotFoundOutput($response);
                    break;

                default:
                    throw new UnexpectedValueException('Cannot render unknown content type ' . $contentType);
            }
        }

        return $output->withStatus(StatusCode::HTTP_NOT_FOUND);
    }

    /**
     * Render plain not found message
     *
     * @param Response $response
     * @return Response
     */
    protected function renderPlainNotFoundOutput($response)
    {
        $body = new Body(fopen('php://temp', 'r+'));
        $body->write('Not Found');
        return $response->withHeader('Content-Type', 'text/plain')->withBody($body);
    }

    /**
     * Return a response for application/json content not found
     *
     * @param Response $response
     * @return Response
     */
    protected function renderJsonNotFoundOutput($response)
    {
        return $response->withJson(['message' => 'Not found']);
    }

    /**
     * Return a response for xml content not found
     *
     * @param Response $response
     * @param string $contentType
     * @return Response
     */
    protected function renderXmlNotFoundOutput($response, $contentType)
    {
        $body = new Body(fopen('php://temp', 'r+'));
        $body->write('<root><message>Not found</message></root>');
        return $response->withHeader('Content-type', $contentType)->withBody($body);
    }

    /**
     * Return a response for text/html content not found
     *
     * @param $response
     * @return ResponseInterface
     *
     */
    protected function renderHtmlNotFoundOutput($response)
    {
        return $this->view->render($response, 'errors/404.twig');
    }
}
