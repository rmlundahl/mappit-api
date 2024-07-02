<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;

use \Illuminate\Http\JsonResponse;
use Auth;

class GroupController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');

    }

    public function groups_from_user(): JsonResponse
    {
        $user = Auth::user();
        if( $user instanceof User) {
            $groups = Group::find($user->group_id)->descendantsAndSelf()->get();
            return response()->json( $groups, 200 );
        }
        return response()->json( [], 404 );
    }

}
