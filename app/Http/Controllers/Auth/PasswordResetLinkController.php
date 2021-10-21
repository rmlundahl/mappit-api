<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * Handle an incoming password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make( $request->only('email'), [
            'email' => 'required|string|email|max:255|'
        ]);
        
        if( $validator->fails() ){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // return response()->json([
        //     'success' => true,
        //     'message' => $status
        // ], 201);
       

        return $status == Password::RESET_LINK_SENT
                    ? response()->json(['status', __($status)])
                    : response()->json(['email' => __($status)]);


    }
}
