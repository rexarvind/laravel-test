<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'username' => 'required|min:2|max:25|string',
            'email'=> 'required|email|unique:users.email',
            'password' => 'required|min:6|max:25|confirmed|string',
            'device_name' => 'required',
        ]);
    
    }

    public function login(Request $request) {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'device_name' => 'required',
        ]);
    
        $user = User::where('email', $request->username)->first();

        if ( !$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }
    
        // delete old tokens of current device
        $user->tokens()->where('name', $request->device_name)->delete();
        return response()->json(['token'=> $user->createToken($request->device_name)->plainTextToken ], 200);
    }

    public function logout(Request $request) {
        // $user->tokens()->where('name', $request->device_name)->delete();
        $user = auth('sanctum')->user();
        dd($user);
    }
}
