<x-mail::message>
# OTP Code

Here is your verification code:

<x-mail::button :url="route('password.reset.otp', ['user' => $user->token, 'otp' => $user->otp])">
{{ $user->otp }}
</x-mail::button>

Please make sure you never share this code with anyone.<br>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>