<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request){
        try {
            $credentials = $request->only(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return response()->json([
                  'status_code' => 500,
                  'message' => 'Unauthorized'
                ], 500);
              }
              $user = User::where('email', $request->email)->first();
              if ( ! Hash::check($request->password, $user->password)) {
                 throw new \Exception('Error in Login');
              }
              $tokenResult = $user->createToken('authToken')->plainTextToken;
              return response()->json([
                'status_code' => 200,
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
              ], 200);
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in Login',
                'error' => $error,
            ]);
        }
    }

    public function profile(){
        return response()->json(Auth::user());
    }

    public function logout(){
        $user = Auth::user();
        $user->tokens()->delete();
        return response()->json([
            'message' => 'Logout successfully'
        ]);
    }
}
