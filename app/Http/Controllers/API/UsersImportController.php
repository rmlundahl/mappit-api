<?php

namespace App\Http\Controllers\API;

use App\Models\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\File\FileUploadRequest;

use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

use Auth, Log;

class UsersImportController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Import a file with users.
     *
     * @param  App\Http\Requests\API\File\FileUploadRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function import(FileUploadRequest $request)
    {
        
        if( Auth::user()->can('import', [User::class]) ) {

            Excel::import(new UsersImport, request()->file('file'));

            return response()->json( [], 201 );
        }
        abort(403);
    }

}
