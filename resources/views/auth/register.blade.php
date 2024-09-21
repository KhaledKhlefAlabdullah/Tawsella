@extends('layouts.app')

@section('content')
    <div class="main" id="main">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-12 mx-auto">
                    <div class="card z-index-0 fadeIn3 fadeInBottom">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                                <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">إنشاء حساب سائق جديد</h4>
                            </div>
                        </div>

                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('drivers.store') }}" method="POST">
                                @csrf

                                <!-- Name -->
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label" for="name">الاسم</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>

                                <!-- Gender -->
                                <div class="input-group input-group-outline my-3">
                                    <select name="gender" class="form-control" required>
                                        <option value="">اختر الجنس</option>
                                        <option value="male">ذكر</option>
                                        <option value="female">أنثى</option>
                                    </select>
                                </div>

                                <!-- Email -->
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label" for="email">البريد الإلكتروني</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>

                                <!-- Phone Number -->
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label" for="phone_number">رقم الجوال</label>
                                    <input type="text" name="phone_number" class="form-control" required>
                                </div>

                                <!-- Password -->
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label" for="password">كلمة المرور</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>

                                <!-- Confirm Password -->
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label" for="password_confirmation">تأكيد كلمة المرور</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">إنشاء الحساب</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
