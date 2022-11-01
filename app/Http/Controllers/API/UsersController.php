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

use Auth, Log;

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
        if ( Auth::user()->can('index', [User::class]) ) {
            
            $users = $this->getUser->all( $request->all() );
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
        if( Auth::user()->can('store', [User::class]) ) {
            
            // authors can only create other authors
            if( Auth::user()->role=='author' &&  $request['role']!='author' ) {
                abort(403);
            }

            $createUser = new CreateUser( $request->all() );
            $newUser = $createUser->save();
            return response()->json( $newUser, 201 );
        }
        abort(403);
    }

    /**
     * Find the specified resource by primary key.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function find($id, Request $request)
    {
        if ( !is_numeric($id) ) {
            return response()->json( [], 404 );
        }

        $user = User::where('id', $id)->first();
       
        if (empty($user)) {
            return response()->json( [], 404 ); 
        }
        return response()->json( $user, 200 );
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\API\User\UpdateUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request)
    {
        if( Auth::user()->can('update', [User::class]) ) {
            
            $user = User::where('id', $request->id)->first();
       
            if (empty($user)) {
                return response()->json( [], 404 ); 
            }

            // authors can not update roles
            if( Auth::user()->role=='author' &&  $request['role']!='author' ) {
                abort(403);
            }

            $updateUser = new UpdateUser( $request->all(), $user );
            
            $user = $updateUser->update($updateUser, $user);
            return response()->json( $user, 200 );
        }
        abort(403);
    }
}
