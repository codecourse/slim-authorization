<?php

namespace App\Exceptions;

use Throwable;
use Slim\Views\Twig;
use Slim\Psr7\Factory\ResponseFactory;
use App\Authorisation\Exceptions\GateAuthorisationException;

class Handler
{
    protected $view;

    protected $responseFactory;

    public function __construct(Twig $view, ResponseFactory $responseFactory)
    {
        $this->view = $view;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke($request, Throwable $exception)
    {
        if ($exception instanceof GateAuthorisationException) {
            return $this->view->render(
                $this->responseFactory->createResponse(),
                'errors/401.twig'
            )
                ->withStatus(401);
        }
    
        throw $exception;
    }
}