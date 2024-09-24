<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="page-header align-items-start min-vh-100"
        style="background-image: url('https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1950&q=80');">
        <span class="mask bg-gradient-dark opacity-6"></span>
        <div class="container my-auto">
            <div class="row">
                <div class="col-lg-4 col-md-8 col-12 mx-auto">
                    <div class="card z-index-0 fadeIn3 fadeInBottom" style="background-color: #1e2023">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class=" shadow-primary border-radius-lg py-3 pe-1" style="background-color: #ffa023">
                                <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">تسجيل الدخول</h4>
                                <div class="row mt-3">
                                    <div class="col-12 text-center ms-auto">
                                        <h2 style="color: #ffffff;font-size: 18px">Tawsella</h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body-auth">
                            <form method="POST" action="{{ route('login') }}" class="text-start" id="login-form">
                                @csrf

                                <!-- Email Address -->
                                <div class="input-group input-group-outline my-3">
                                    <x-input-label for="email" class="form-label" :value="__('الايميل')" />
                                    <x-text-input id="email" class="form-control" type="email" name="email"
                                        :value="old('email')" required autofocus autocomplete="username"
                                        style="color:#ffffff" />
                                    <div class="invalid-feedback">رجاءً أدخل الإيميل</div>
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <!-- Password -->
                                <div class="input-group input-group-outline mb-3">
                                    <x-input-label for="password" class="form-label" :value="__('كلمة المرور')" />
                                    <x-text-input id="password" class="form-control" type="password" name="password"
                                        required autocomplete="current-password" />
                                    <div class="invalid-feedback">رجاءً أدخل كلمة المرور</div>
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>

                                <!-- Remember Me -->
                                <div class="form-check form-switch d-flex align-items-center mb-3">
                                    <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                                    <label class="form-check-label mb-0 ms-3" for="rememberMe">تذكرني</label>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">تسجيل
                                        الدخول</button>
                                </div>
                                @if (Route::has('password.request'))
                                    <div class="text-center">
                                        <a class="text-sm text-gray-600 dark:text-gray-400"
                                            href="{{ route('password.request') }}">
                                            {{ __('هل نسيت كلمة المرور؟') }}
                                        </a>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

<!-- jQuery and Loading Spinner Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#login-form').submit(function(event) {
            event.preventDefault(); // Prevent form submission

            // Simulate loading animation
            var submitButton = $('.btn-primary');
            submitButton.html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> جاري التحميل...'
            );
            submitButton.prop('disabled', true);

            // Simulate AJAX request
            setTimeout(function() {
                $('#login-form').unbind('submit').submit(); // Submit the form
            }, 1500);
        });
    });
</script>
