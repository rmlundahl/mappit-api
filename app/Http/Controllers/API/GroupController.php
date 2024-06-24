<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;

use Auth;

class GroupController extends Controller
{
    private $user;
    private $groups;
    
    public function __construct(User $user)
    {
        $this->middleware('auth:sanctum');
        $this->user = $user;

    }


    public function groups_from_user()
    {
        $user = Auth::user();
        if( $user instanceof User) {
            $groups = Group::find($user->group_id)->descendantsAndSelf()->get();
            return response()->json( $groups, 200 );
        }
    }

}
