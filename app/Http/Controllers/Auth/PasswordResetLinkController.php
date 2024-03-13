<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOTP;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function reset(Request $request, $token)
    {
        $user = User::where('token', $token)->firstOrFail();
        return view('auth.reset-password-otp', ['request' => $request, 'token' => $token]);
    }

    public function resetApi(Request $request)
    {
        $token = $request->token;
        $otp = $request->otp;
        $password = $request->password;
        $password_confirmation = $request->password_confirmation;
        $user = User::where('token', $token)->first();
        if($token != $user->token){
            return response()->json(['message' => 'Page expired, please refresh.'], 500);
        }

        if($otp != $user->otp){
            return response()->json(['message' => 'Invalid OTP, try again.', 'otp' => false], 302);
        }

        if(empty($password)){
            return response()->json(['message' => 'Enter new password', 'hidden' => true, 'otp' => true], 302);
        }

        if(empty($password) || empty($password_confirmation)){
            return response()->json(['message' => 'Passwords are required', 'otp' => true], 302);
        }

        if($password != $password_confirmation){
            return response()->json(['message' => 'Passwords do not match', 'otp' => true], 302);
        }

        try {
            $user->forceFill([
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ])->save();
        } catch(Exception $e){
            return response()->json(['message' => 'An error occurred, try later', 'otp' => true, 'log' => $e->getMessage()], 500);
        }
        Auth::attempt(['email' => $user->email, 'password' => $password], true);
        event(new PasswordReset($user));
        return response()->json(['message' => 'Password updated, redirecting...', 'redirect' => route('login'), 'otp' => true]);
    }

    public function storeApi(Request $request)
    {
        $request->validate([
            'email' => 'required',
        ]);
        $username = $request->email;
        $login_type = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $user = null;
        if($login_type == 'phone'){
            $user = User::where('phone', $username)->first();
        } else {
            $user = User::where('email', $username)->first();
        }

        if( empty($user) ){
            return response()->json(['message' => 'User not found with given '. $login_type], 302);
        }

        $otp = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $otp_expire_at = now()->addMinutes(30);
        if( !empty($user->otp) && !empty($user->otp_expire_at) && now()->lt($user->otp_expire_at) ){
            $otp = $user->otp;
            $otp_expire_at = $user->otp_expire_at;
        }

        try {
            $token = Str::uuid()->toString();
            $user->otp = $otp;
            $user->token = $token;
            $user->otp_expire_at = $otp_expire_at;
            $user->save();
            $sms_sent = $user->sendOTP($otp);
            Mail::to($user)->send(new SendOTP($user));
        } catch (Exception $e){
            return response()->json(['message' => 'An error occurred, try again later.', 'log' => $e->getMessage()], 500);
        }
        return response()->json(['message' => 'Redirecting...', 'redirect' => route('password.reset.otp', $token)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}
