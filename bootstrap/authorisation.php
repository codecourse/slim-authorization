<?php

$container->get('gate')->define('view-post', function ($user, $post) {
    return $user->id === $post->user_id;
});

$container->get('gate')->before(function ($user, $ability) { 
    return $user->isAdmin();
});
