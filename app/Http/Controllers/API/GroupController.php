<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;

use Auth, Log;

class GroupController extends Controller
{
    private $group;
    private $getGroup;
    
    public function __construct(User $user)
    {
        $this->middleware('auth:sanctum');
        $this->user = $user;

    }


    public function groups_from_user()
    {
        $groups = Group::find(Auth::user()->group_id)->descendantsAndSelf()->get();
        return response()->json( $groups, 200 );
    }

}
