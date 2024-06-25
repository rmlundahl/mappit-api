<?php

namespace App\Services\User;

use App\Models\User;


class DeleteUser {
    
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }


    public function delete()
    {
        $this->user->status_id = 99;
        
        return $this->user->save();

    }
}
