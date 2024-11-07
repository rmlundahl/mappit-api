<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Auth;

class AuthController extends Controller
{
    
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make( $request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|same:password'
        ]);

        if( !$validator->fails() ){
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => bcrypt( $request->string('password') ),
                // 'avatar' => $gravatar->get()
            ]);
            
            return response()->json('', 204);
        }

        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);

    }

    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt([
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ])) {
            return response()->json('', 204);
        } else {
            return response()->json([
                'error' => 'invalid_credentials'
            ], 403);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        return response()->json('', 204);
    }
}
