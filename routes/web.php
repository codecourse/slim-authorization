<?php

use App\Controllers\PostController;

$app->get('/posts/1', PostController::class . ':show');
