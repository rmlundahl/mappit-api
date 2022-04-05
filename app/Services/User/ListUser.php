<?php

namespace App\Services\User;

use App\Models\User;


class ListUser {
    
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }


    public function all()
    {
        return $this->user->all();
    }

    public function users_from_group($group_id=null)
    {
        if( !$group_id ) return;

        return $this->user->where('group_id', $group_id)->get();
    }
}
