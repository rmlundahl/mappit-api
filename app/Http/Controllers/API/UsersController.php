<?php declare(strict_types = 1);

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\UserCreated;

use App\Services\User\GetUser;
use App\Services\User\CreateUser;
use App\Services\User\UpdateUser;
use App\Services\User\DeleteUser;

use App\Http\Requests\API\User\CreateUserRequest;
use App\Http\Requests\API\User\UpdateUserRequest;
use App\Http\Requests\API\User\DeleteUserRequest;

use Auth, Log;

class UsersController extends Controller
{
    private GetUser $getUser;
    
    public function __construct(GetUser $getUser)
    {
        $this->middleware('auth:sanctum');
        $this->getUser = $getUser;
    }

    /**
     * Display a resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        return response()->json( $request->user() );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ( Auth::user()->can('index', [User::class]) ) {
            
            $users = $this->getUser->all();
            return response()->json( $users, 200 );

        }
        abort(403);
    }

    /**
     * Display all users from a group.
     *
     * @param  int  $group_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function users_from_group(int $group_id)
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
     * @param  \App\Http\Requests\API\User\CreateUserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateUserRequest $request)
    {
        $user = Auth::user();
        
        if( !$user instanceof User) {
            abort(403);
        }
            
        if( $user->can('store', [User::class]) ) {
            
            // authors can only create other authors
            if( $user->role=='author' &&  $request['role']!='author' ) {
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function find($id)
    {
        if ( !is_numeric($id) ) {
            return response()->json( [], 404 );
        }

        $user = User::where('id', $id)->with('user_preferences')->first();
       
        if (empty($user)) {
            return response()->json( [], 404 ); 
        }
        return response()->json( $user, 200 );
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\API\User\UpdateUserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request)
    {
        $user = Auth::user();
        
        if( !$user instanceof User) {
            abort(403);
        }
        
        if( $user->can('update', [User::class]) ) {
            
            $userToUpdate = User::where('id', $request->id)->first();
       
            if (empty($userToUpdate)) {
                return response()->json( [], 404 ); 
            }

            // authors can not update roles
            if( $user->role=='author' && isset($request['role']) && $request['role']!='author' ) {
                abort(403);
            }

            // authors can not update other authors, unless they are group admin. And they can update their own profile
            if( $user->role=='author' && !$user->is_group_admin && $user->id != $request['id']) {
                abort(403);
            }

            $updateUser = new UpdateUser( $request->all(), $userToUpdate );
            
            $updatedUser = $updateUser->update();

            // send email to new user?
            if($request['form_action']=='new') {
                $updatedUser->notify(new UserCreated($request['name'], $request['email'], $request['password']??'', $user->email));
            }

            return response()->json( $updatedUser, 200 );
        }
        abort(403);
    }

    /**
     * Soft remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\API\User\DeleteUserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(DeleteUserRequest $request)
    {
        $user = User::find($request->integer('id'));
        
        if (empty($user)) {
            return response()->json( [], 404 ); 
        }

        $deleteUser = new DeleteUser($user);
        
        $user = $deleteUser->delete();
        return response()->json( [], 204 );
    }
}
