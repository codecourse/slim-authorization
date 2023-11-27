<?php

namespace App\Models;

class Post
{
    public $id;

    public $user_id;

    public function __construct($id, $userId)
    {
        $this->id = $id;
        $this->user_id = $userId;
    }
}
