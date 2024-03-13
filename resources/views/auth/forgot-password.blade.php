<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" id="forget-password-form" action="{{ route('api.password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email or 10 digit contact number')" />
            <x-text-input id="email" class="block mt-1 w-full" type="text" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div data-js="submit-status" class="hidden my-2 fw-bold"></div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button data-js="submit-btn">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
    <script type="text/javascript">
        (function(){
            var forget_password_form = document.getElementById('forget-password-form');
            if(forget_password_form){
                var url = forget_password_form.getAttribute('action');
                var submit_status = forget_password_form.querySelector('[data-js="submit-status"]');
                var submit_btn = forget_password_form.querySelector('[data-js="submit-btn"]');
                forget_password_form.addEventListener('submit', function(e){
                    e.preventDefault();
                    var data = new FormData(forget_password_form);
                    submit_status.classList.remove('hidden');
                    submit_status.textContent = 'Connecting...';
                    submit_btn.disabled = true;
                    axios.post(url, data).then(function(res){
                        if(res.data.redirect){
                            window.location.href = res.data.redirect;
                        } else {
                            submit_btn.disabled = false;
                        }
                        if(res.data?.message){
                            submit_status.textContent = res.data.message;
                        }
                    }).catch(function(err){
                        var msg = 'An error occurred, try later.';
                        if (err?.response?.data?.message) {
                            msg = err.response.data.message;
                        } else if (err?.message) {
                            msg = err.message;
                        }
                        submit_status.textContent = msg;
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
