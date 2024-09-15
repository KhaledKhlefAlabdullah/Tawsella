<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <main>

            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <a href="/" class="logo d-flex align-items-center w-auto">
                                    <img src="img/logoo.png" alt="">
                                </a>
                            </div><!-- End Logo -->

                            <div class="card mb-3">

                                <div class="card-body">

                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4"> مرحبا بكم في لوحة التحكم </h5>
                                        <p class="text-center small"> ادخل الايميل و كلمة المرور للدخول الى لوحة التحكم
                                        </p>
                                    </div>

                                    <form method="POST" action="{{ route('login') }}" class="row g-3 needs-validation"
                                        novalidate id="login-form">
                                        @csrf

                                        <!-- Email Address -->
                                        <div class="col-12">
                                            <x-input-label for="email" class="form-label" :value="__('الايميل')" />
                                            <x-text-input id="email" class="block mt-1 w-full form-control"
                                                type="email" name="email" :value="old('email')" required autofocus
                                                autocomplete="username" />
                                            <div class="invalid-feedback">رجاءا ادخل الايميل </div>
                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                        </div>
                                        <!-- Password -->
                                        <div class="mt-4 col-12">
                                            <x-input-label for="password" class="form-label" :value="__('كلمة المرور ')" />

                                            <x-text-input id="password" class="block mt-1 w-full form-control" type="password"
                                                name="password" required autocomplete="current-password" />
                                                <div class="invalid-feedback"> رجاء ادخل كلمة المرور  </div>
                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>

                                        <!-- Remember Me -->
                                        <div class="block mt-4">
                                            <label for="remember_me" class="inline-flex items-center form-check-label">
                                                <input id="remember_me" type="checkbox"
                                                    class="form-check-input rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                                    name="remember">
                                                <span
                                                    class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('تذكرني') }}</span>
                                            </label>
                                        </div>

                                        <div class="flex items-center justify-end mt-4">
                                            @if (Route::has('password.request'))
                                                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                                                    href="{{ route('password.request') }}">
                                                    {{ __('هل نسيت كلمة المرور?') }}
                                                </a>
                                            @endif

                                            <div class="col-4">
                                                <button class="btn btn-primary w-100" type="submit"> تسجيل الدخول  </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

    </main><!-- End #main -->

</x-guest-layout>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#login-form').submit(function (event) {
            event.preventDefault(); // Prevent form submission

            // Simulate loading animation
            $('.btn-primary').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...').attr('disabled', 'disabled');

            // Simulate AJAX request
            setTimeout(function () {
                // Perform form submission after 1.5 seconds (for demonstration)
                $('#login-form').unbind('submit').submit();
            }, 1500);
        });
    });
</script>
