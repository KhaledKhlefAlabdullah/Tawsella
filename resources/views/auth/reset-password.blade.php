<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="page-header align-items-start min-vh-100"
        style="background-image: url('https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1950&q=80');">
        <span class="mask bg-gradient-dark opacity-6"></span>
        <div class="container my-auto">
            <div class="row">
                <div class="col-lg-4 col-md-8 col-12 mx-auto">
                    <div class="card z-index-0 fadeIn3 fadeInBottom">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                                <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">إعادة تعيين كلمة المرور</h4>
                                <div class="row mt-3">
                                    <div class="col-12 text-center ms-auto">
                                        <h2 style="color: #ffffff;font-size: 18px">Tawsella</h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('password.store') }}" class="text-start" id="reset-password-form">
                                @csrf

                                <!-- Password Reset Token -->
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                <!-- Email Address -->
                                <div class="input-group input-group-outline my-3">
                                    <x-input-label for="email" class="form-label" :value="__('الايميل')" />
                                    <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                                    <div class="invalid-feedback">رجاءً أدخل الإيميل</div>
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <!-- Password -->
                                <div class="input-group input-group-outline my-3">
                                    <x-input-label for="password" class="form-label" :value="__('كلمة المرور الجديدة')" />
                                    <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
                                    <div class="invalid-feedback">رجاءً أدخل كلمة المرور الجديدة</div>
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>

                                <!-- Confirm Password -->
                                <div class="input-group input-group-outline mb-3">
                                    <x-input-label for="password_confirmation" class="form-label" :value="__('تأكيد كلمة المرور')" />
                                    <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
                                    <div class="invalid-feedback">رجاءً أكد كلمة المرور</div>
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">إعادة تعيين كلمة المرور</button>
                                </div>
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
        $('#reset-password-form').submit(function(event) {
            event.preventDefault(); // Prevent form submission

            // Simulate loading animation
            var submitButton = $('.btn-primary');
            submitButton.html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> جاري التحميل...'
            );
            submitButton.prop('disabled', true);

            // Simulate AJAX request
            setTimeout(function() {
                $('#reset-password-form').unbind('submit').submit(); // Submit the form
            }, 1500);
        });
    });
</script>
