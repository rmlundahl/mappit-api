<?php declare(strict_types = 1);

namespace App\Services\User;

use App\Models\User;


class DeleteUser {
    
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }


    public function delete(): bool
    {
        $this->user->status_id = 99;
        
        return $this->user->save();

    }
}
