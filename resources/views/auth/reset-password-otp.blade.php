<x-guest-layout>
    <form method="POST" id="reset-password-form" action="{{ route('api.password.reset') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="step-otp">
            <x-input-label for="email" :value="__('OTP')" />
            <x-text-input id="email" class="block mt-1 w-full" type="number" name="otp" value="{{ $request->otp }}" autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="step-password hidden mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="step-password hidden mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        <input type="hidden" name="token" value="{{ $token }}" />
        <div data-js="submit-status" class="hidden my-2 fw-bold"></div>
        <div class="flex items-center justify-end mt-4">
            <x-primary-button data-js="submit-btn">
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
    <script type="text/javascript">
        (function(){
            var reset_password_form = document.getElementById('reset-password-form');
            if(reset_password_form){
                var step_otps = document.querySelectorAll('.step-otp');
                var step_passwords = document.querySelectorAll('.step-password');
                var submit_btn = reset_password_form.querySelector('[data-js="submit-btn"]');
                var url = reset_password_form.getAttribute('action');
                var submit_status = reset_password_form.querySelector('[data-js="submit-status"]');
                reset_password_form.addEventListener('submit', function(e){
                    e.preventDefault();
                    var data = new FormData(reset_password_form);
                    submit_status.classList.remove('hidden');
                    submit_status.textContent = 'Connecting...';
                    submit_btn.disabled = true;
                    axios.post(url, data).then(function(res){
                        if(res.data.redirect){
                            window.location.href = res.data.redirect;
                        }
                        if(res.data?.message){
                            submit_status.textContent = res.data.message;
                        }
                    }).catch(function(err){
                        var msg = 'An error occurred, try later.';
                        var res = err.response;
                        if (err?.response?.data?.message) {
                            msg = err.response.data.message;
                        } else if (err?.message) {
                            msg = err.message;
                        }
                        if(res.data?.hidden){
                            submit_status.classList.add('hidden');
                            submit_status.textContent = '';
                        } else {
                            submit_status.classList.remove('hidden');
                            submit_status.textContent = msg;
                        }
                        if(res.data?.otp){
                            for (var i = 0; i < step_otps.length; i++) {
                                step_otps[i].classList.add('hidden');
                            }
                            for (var i = 0; i < step_passwords.length; i++) {
                                step_passwords[i].classList.remove('hidden');
                            }
                        } else {
                            for (var i = 0; i < step_otps.length; i++) {
                                step_otps[i].classList.remove('hidden');
                            }
                            for (var i = 0; i < step_passwords.length; i++) {
                                step_passwords[i].classList.add('hidden');
                            }
                        }
                    }).finally(function(){
                        submit_btn.disabled = false;
                    });
                });
            }
            window.addEventListener('pageshow', function (event) {
                var historyTraversal = event.persisted ||
                    (typeof window.performance != 'undefined' &&
                        window.performance?.navigation?.type === 2);
                if (historyTraversal) {
                    window.location.reload();
                }
            });
        })();
    </script>
</x-guest-layout>
