<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request){
        $validated = $request->validate([
            'name'=> ['required', 'string', 'min:2', 'max:50'],
            'username' => ['required', 'string', 'min:2', 'max:50', 'unique:'.User::class],
            'email'=> ['required', 'email', 'max:250', 'unique:'.User::class],
            'password' => ['required', 'string', 'min:4', 'max:50', 'confirmed'],
            'device_name' => ['nullable', 'string', 'max:50'],
        ]);

        $input = $request->only('name', 'username', 'email', 'password');
        $input['password'] = Hash::make($request['password']);
        $device_name = $request['device_name'] ?? 'web';
        $user = User::create($input);
        $data = [
            'token'=> $user->createToken($device_name)->plainTextToken,
            'user'=> $user,
        ];

        return response()->json($data, 200);
    }

    public function login(Request $request) {
        $validated = $request->validate([
            'username' => 'required',
            'password' => 'required|min:6',
            'deviceId' => 'required',
        ],[
            'username.required'=> 'Please enter username',
            'deviceId.required'=> 'We can not identify your device',
        ]);
    
        $field = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($field, $request->username)->first();

        if ( !$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }
    
        /* delete old tokens of current device */
        $user->tokens()->where('name', $request->deviceId)->delete();
        return response()->json([
            'token'=> $user->createToken($request->deviceId)->plainTextToken,
            'user'=> $user,
        ], 200);
    }

    public function logout(Request $request) {
        // $user->tokens()->where('name', $request->device_name)->delete();
        $user = auth('sanctum')->user();
        dd($user);
    }

    public function google(Request $request){
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function googleCallback(Request $request) {
        try {
            $user = Socialite::driver('google')->stateless()->user();
        } catch (ClientException $exception) {
            return response()->json([
                'errors'=> ['Invalid credentials provided.'],
                'message'=> 'Invalid credentials provided.',
            ], 422);
        }

        $userCreated = User::firstOrCreate(
            [ 'email'=> $user->getEmail(), ],
            [
                'email_verified_at'=> now(),
                'name'=> $user->getName(),
                'avatar'=> $user->getAvatar(),
                'status'=> true,
            ]
        );
        $token = $userCreated->createToken('socialite')->plainTextToken;
        return response()->json([
            'token'=> $token,
            'user'=> $userCreated,
        ], 200);
    }
}
