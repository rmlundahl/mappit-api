<?php

namespace App\Services\User;

use App\Models\User;


class DeleteUser {
    
    private $data;

    public function __construct(array $deleteUserData, User $user)
    {
        $this->data = $deleteUserData;
        $this->user = $user;
    }


    public function delete()
    {
        $this->user->status_id = 99;
        
        return $this->user->save();

    }
}
