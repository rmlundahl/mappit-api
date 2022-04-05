<?php

namespace App\Services\User;

use App\Models\User;
use \Str;

class CreateUser {
    
    private $data;

    public function __construct($newUserData)
    {
        $this->data = $newUserData;
    }

    public function save()
    {
        $user = new User;
        $user->name           = $this->data['name'];
        $user->email          = $this->data['email'];
        $user->password       = $this->data['password'];
        $user->group_id       = $this->data['group_id'] ?? null;
        $user->is_group_admin = $this->data['is_group_admin'] ?? 0;
        $user->role           = $this->data['role'] ?? 'author';
        $user->status_id      = $this->data['status_id'] ?? 1;
        $user->save();

        return $user;
    }
    
}
