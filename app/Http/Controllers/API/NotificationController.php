<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests\API\Notification\UpdateNotificationRequest;
use App\Http\Requests\API\Notification\DeleteNotificationRequest;

use Auth, Log;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     *
     * * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        return response()->json( $user->notifications, 200 );
    }

    /**
     * Find the specified resource by primary key.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function find($id)
    {
        if ( strlen($id) != 36 ) {
            return response()->json( [], 404 );
        }
        $user = Auth::user();
        $notification = $user->notifications->where('id', $id)->first();
       
        if (empty($notification)) {
            return response()->json( [], 404 ); 
        }
        return response()->json( $notification, 200 );
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\API\Notification\UpdateNotificationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNotificationRequest $request)
    {
        $user = Auth::user();
        $notification = $user->notifications->where('id', $request->id)->first();
        $notification->read_at = $request->read_at;
        $notification->save();

        return response()->json( $notification, 200 );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Http\Requests\API\Notification\DeleteNotificationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(DeleteNotificationRequest $request)
    {
        $user = Auth::user();
        $notification = $user->notifications->where('id', $request->id)->first();
        
        if (empty($notification)) {
            return response()->json( [], 404 ); 
        }
        
        $notification->delete();

        return response()->json( [], 204 );
    }
}
