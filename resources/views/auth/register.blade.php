@extends('layouts.app')

@section('content')
    <div class="main" id="main">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">إنشاء حساب سائق جديد</div>

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

                            <form action="{{ route('store-driver') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name">الاسم:</label>
                                    <input type="text" name="name" class="form-control" placeholder="أدخل اسمك"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="gender">جنس السائق:</label>
                                    <select name="gender" class="form-input" required>
                                        <option value="">الجنس</option>
                                        <option value="male">ذكر</option>
                                        <option value="female">انثى</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="email">البريد الإلكتروني:</label>
                                    <input type="email" name="email" class="form-control"
                                        placeholder="أدخل بريدك الإلكتروني" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone_number">ادخل رقم الجوال:</label>
                                    <input type="phone_number" name="phone_number" class="form-control"
                                        placeholder="ادخل رقم الجوال" required>
                                </div>

                                <div class="form-group">
                                    <label for="password">كلمة المرور:</label>
                                    <input type="password" name="password" class="form-control"
                                        placeholder="أدخل كلمة المرور" required>
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation">تأكيد كلمة المرور:</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        placeholder="أعد إدخال كلمة المرور" required>
                                </div>

                                <button type="submit" class="btn btn-primary">إنشاء الحساب</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
