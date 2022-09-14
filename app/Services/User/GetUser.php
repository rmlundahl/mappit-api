<?php

namespace App\Services\User;

use App\Models\User;


class GetUser {
    
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

    public function users_from_groups($group_ids=[])
    {
        if( empty($group_ids) ) return;

        return $this->user->whereIn('group_id', $group_ids)->get();
    }
}
