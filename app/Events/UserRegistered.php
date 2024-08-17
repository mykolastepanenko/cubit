<?php

namespace App\Events;

use App\Models\User;

class UserRegistered
{
    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
