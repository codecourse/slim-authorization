<?php

namespace App\Models;

class User
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function isAdmin()
    {
        return true;
    }
}
