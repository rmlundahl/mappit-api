<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

use App\Services\User\GetUser;
use App\Services\User\CreateUser;
use App\Services\User\UpdateUser;

use App\Http\Requests\API\User\CreateUserRequest;
use App\Http\Requests\API\User\UpdateUserRequest;

use Auth;

class UsersController extends Controller
{
    private $user;
    private $getUser;
    
    public function __construct(User $user, GetUser $getUser)
    {
        $this->middleware('auth:sanctum');
        $this->user = $user;
        $this->getUser = $getUser;
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
            
            $users = $this->getUser->all();
            return response()->json( $users, 200 );

        }
        abort(403);
    }

    public function users_from_group($group_id)
    {
        if( Auth::user()->can('users_from_group', [User::class]) ) {
            
            $users = $this->getUser->users_from_group( $group_id );
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

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\API\User\UpdateUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request)
    {
        $user = User::where('id', $request->id)->first();
       
        if (empty($user)) {
            return response()->json( [], 404 ); 
        }

        $updateUser = new UpdateUser( $request->all(), $user );
        
        $user = $updateUser->update($updateUser, $user);
        return response()->json( $user, 200 );
    }
}
