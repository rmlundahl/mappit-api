<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

use App\Services\User\ListUser;
use App\Services\User\CreateUser;

use App\Http\Requests\API\User\CreateUserRequest;

use Auth;

class UsersController extends Controller
{
    private $user;
    private $listUser;
    
    public function __construct(User $user, ListUser $listUser)
    {
        $this->middleware('auth:sanctum');
        $this->user = $user;
        $this->listUser = $listUser;
    }

    public function show(Request $request)
    {
        return response()->json( $request->user() );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( Auth::user()->can('index', [User::class]) ) {
            
            $users = $this->listUser->all();
            return response()->json( $users, 200 );

        }
        abort(403);
    }

    public function users_from_group($group_id)
    {
        if( Auth::user()->can('users_from_group', [User::class]) ) {
            
            $users = $this->listUser->users_from_group( $group_id );
            return response()->json( $users, 200 );

        }
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\API\User\CreateUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $createUser = new CreateUser( $request->all() );
        $newUser = $createUser->save();
        return response()->json( $newUser, 201 );
    }
}
