<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Group;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index( User $user )
    {
        if( $user->role == 'administrator' ) {
            return true;
        } else if($user->role == 'editor') {
            return true;
        } else if( $user->role == 'author' && $user->is_group_admin==1 ) {
            return true;
        }
        return false;
    }

    public function users_from_group(User $user)
    {
        if( $user->role == 'administrator' ) {
            return true;
        } else if($user->role == 'editor') {
            return true;
        } else if( $user->role == 'author' && $user->is_group_admin==1 ) {
            return true;
        }
        return false;
    }


    public function store( User $user )
    {
        if( $user->role == 'administrator' ) {
            return true;
        } else if($user->role == 'editor') {
            return true;
        } else if( $user->role == 'author' && $user->is_group_admin==1 ) {
            return true;
        }
        return false;
    }

    public function update( User $user )
    {
        if( $user->role == 'administrator' ) {
            return true;
        } else if($user->role == 'editor') {
            return true;
        } else if( $user->role == 'author' && $user->is_group_admin==1 ) {
            return true;
        }
        return false;
    }
}
