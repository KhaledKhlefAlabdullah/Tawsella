<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="page-header align-items-start min-vh-100"
        style="background-image: url('https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1950&q=80');">
        <span class="mask bg-gradient-dark opacity-6"></span>
        <div class="container my-auto">
            <div class="row">
                <div class="col-lg-4 col-md-8 col-12 mx-auto">
                    <div class="card z-index-0 fadeIn3 fadeInBottom" style="background-color: #22272B">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="shadow-primary border-radius-lg py-3 pe-1"  style="background-color: #FFBC07">
                                <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">استعادة كلمة المرور</h4>
                                <div class="row mt-3">
                                    <div class="col-12 text-center ms-auto">
                                        <h2 style="color: #ffffff;font-size: 18px">Tawsella</h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body-auth">
                            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('هل نسيت كلمة المرور؟ لا مشكلة. فقط قم بإدخال بريدك الإلكتروني وسنرسل لك رابطاً لإعادة تعيين كلمة المرور.') }}
                            </div>

                            <form method="POST" action="{{ route('password.email') }}" class="text-start" id="reset-password-form">
                                @csrf

                                <!-- Email Address -->
                                <div class="input-group input-group-outline my-3">
                                    <x-input-label for="email" class="form-label" :value="__('الايميل')" />
                                    <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus />
                                    <div class="invalid-feedback">رجاءً أدخل الإيميل</div>
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">إرسال رابط إعادة تعيين كلمة المرور</button>
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
