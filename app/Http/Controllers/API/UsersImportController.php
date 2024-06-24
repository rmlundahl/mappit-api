<?php declare(strict_types = 1);

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\UserPreference;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\File\FileUploadRequest;

use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Notification\UserCreatedNotification;

use Auth;

class UsersImportController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Import a file with users.
     *
     * @param  \App\Http\Requests\API\File\FileUploadRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function import(FileUploadRequest $request)
    {
        $auth_user = Auth::user();

        if( !$auth_user instanceof User) {
            abort(403);
        }
        
        if( $auth_user->can('import', [User::class]) ) {

            $import = new UsersImport;
            Excel::import($import, request()->file('file'));
            
            // Should we notify new users?
            if( count($import->getNewUsers()) && $request->input('notify_user')==='true' ) {
                                
                foreach($import->getNewUsers() as $user) {
                    $userPreference = new UserPreference;
                    $userPreference->user_id = $user->id;
                    $userPreference->key = 'notify_when_created';
                    $userPreference->val = 'true';
                    $userPreference->save();

                    $userPreference = new UserPreference;
                    $userPreference->user_id = $user->id;
                    $userPreference->key = 'notify_when_created_cc_email';
                    $userPreference->val = $auth_user->email;
                    $userPreference->save();
                }

                $userCreatedNotification = new UserCreatedNotification;
                $userCreatedNotification->send();
            }

            return response()->json( [], 201 );
        }
        abort(403);
    }

}
