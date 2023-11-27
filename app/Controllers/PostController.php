<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PostController extends Controller
{
    /**
     * Render the home page
     *
     * @param Request $request
     * @param Response $response
     * @param [type] $args
     * @return void
     */
    public function show(Request $request, Response $response, $args)
    {
        $post = new Post(1, 2);

        $this->authorise('view-post', $post);

        return $response;
    }
}
