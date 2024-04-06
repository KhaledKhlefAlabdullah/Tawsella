@extends('layouts.app')

@section('content')
    <main id="main" class="main">
        @if (session('success'))
            <div class="alert alert-success">
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
        <div class="pagetitle">
            <h1>Profile</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="profile.html"></a></li>
                    <li class="breadcrumb-item">Users</li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section profile">
            <div class="row">
                <div class="col-xl-4">

                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                            <div class="social-links mt-2">
                                <img src="{{ asset('assets/' . $driver->avatar) }}"
                                    style="width: 150px;height: 130px;border-radius: 50%" alt="">
                            </div>

                            <h2>{{ $driver->name }} <a href="#"><i class="bi bi-award"></i></a></h2>
                            <h3>({{ $driver->role->name ?? 'driver' }})</h3>

                        </div>
                    </div>

                </div>

                <div class="col-xl-8">

                    <div class="card">
                        <div class="card-body pt-3">
                            <!-- Bordered Tabs -->
                            <ul class="nav nav-tabs nav-tabs-bordered">

                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#profile-overview">ملخص بيانات</button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">
                                        تعديل الملف الشخصي</button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#profile-settings">الاعدادات</button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#profile-change-password">تغيير كلمة السر</button>
                                </li>
                                @can('is-admin')
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-delete">
                                            حذف الحساب</button>
                                    </li>
                                @endcan

                            </ul>
                            <div class="tab-content pt-2">

                                <div class="tab-pane fade show active profile-overview" id="profile-overview">

                                    <h5 class="card-title">تفاصيل الملف </h5>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">الاسم الكامل</div>
                                        <div class="col-lg-9 col-md-8">{{ $driver->name }}</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">الدور</div>
                                        <div class="col-lg-9 col-md-8">({{ $driver->role->name ?? 'driver' }})</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">الهاتف</div>
                                        <div class="col-lg-9 col-md-8"><a
                                                href="https://wa.me/{{ $driver->phoneNumber }}">{{ $driver->phoneNumber }}</a>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">الايميل</div>
                                        <div class="col-lg-9 col-md-8">{{ $driver->email }}</div>
                                    </div>

                                </div>

                                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                                    <header>
                                        <h2 class="text-lg font-medium text-gray-900">
                                            {{ __('معلومات الحساب') }}
                                        </h2>

                                        <p class="mt-1 text-sm text-gray-600">
                                            {{ __("قم بتحديث معلومات الملف الشخصي لحسابك وعنوان البريد الإلكتروني.") }}
                                        </p>
                                    </header>

                                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                                        @csrf
                                    </form>

                                    <form method="post" action="{{ route('drivers.update', $driver->id) }}"
                                        class="mt-6 space-y-6">
                                        @csrf
                                        @method('put')

                                        <div class="mb-3">
                                            <x-input-label for="name" :value="__('الاسم')" />
                                            <x-text-input id="name" name="name" type="text" class="form-control"
                                                :value="old('name', $driver->name)" required autofocus autocomplete="name" />
                                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                        </div>

                                        <div class="mb-3">
                                            <x-input-label for="email" :value="__('البريد الالكتروني')" />
                                            <x-text-input id="email" name="email" type="email" class="form-control"
                                                :value="old('email', $driver->email)" required autocomplete="username" />
                                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                        </div>
                                        {{-- <div class="mb-3">
                                            <x-input-label for="avatar" :value="__('صور الملف الشخصي')" />
                                            <input id="avatar" name="avatar" type="file" class="form-control" accept="image/*" />
                                            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                                        </div> --}}
                                        <div class="mb-3">
                                            <x-input-label for="phoneNumber" :value="__('رقم الجوال')" />
                                            <x-text-input id="phoneNumber" name="phoneNumber" type="tel"
                                                class="form-control" :value="old('phoneNumber', $driver->phoneNumber)" required
                                                autocomplete="phoneNumber" />
                                            <x-input-error class="mt-2" :messages="$errors->get('phoneNumber')" />
                                        </div>
                                        <div class="d-flex align-items-center gap-4">
                                            <x-primary-button class="btn btn-success">
                                                {{ __('حفظ') }}
                                            </x-primary-button>

                                            @if (session('status') === 'profile-updated')
                                                <p x-data="{ show: true }" x-show="show" x-transition
                                                    x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">
                                                    {{ __('Saved.') }}
                                                </p>
                                            @endif
                                        </div>
                                    </form>


                                </div>


                                <div class="tab-pane fade setting pt-3" id="profile-settings">

                                </div>

                                <div class="tab-pane fade pt-3" id="profile-change-password">
                                    <section>
                                        <header>
                                            <h2 class="text-lg font-medium text-gray-900">
                                                {{ __('تحديث كلمة السر') }}
                                            </h2>

                                            <p class="mt-1 text-sm text-gray-600">
                                                {{ __('تأكد من أن حسابك يستخدم كلمة مرور طويلة وعشوائية ليظل آمنًا.') }}
                                            </p>
                                        </header>

                                        <form method="post" action="{{ route('password.update') }}"
                                            class="mt-6 space-y-6">
                                            @csrf
                                            @method('put')

                                            <div class="mb-3">
                                                <label for="update_password_current_password"
                                                    class="form-label">{{ __('Current Password') }}</label>
                                                <input id="update_password_current_password" name="current_password"
                                                    type="password" class="form-control" autocomplete="current-password">
                                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                                            </div>

                                            <div class="mb-3">
                                                <label for="update_password_password"
                                                    class="form-label">{{ __('New Password') }}</label>
                                                <input id="update_password_password" name="password" type="password"
                                                    class="form-control" autocomplete="new-password">
                                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                                            </div>

                                            <div class="mb-3">
                                                <label for="update_password_password_confirmation"
                                                    class="form-label">{{ __('Confirm Password') }}</label>
                                                <input id="update_password_password_confirmation"
                                                    name="password_confirmation" type="password" class="form-control"
                                                    autocomplete="new-password">
                                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                                            </div>

                                            <div class="d-flex align-items-center gap-4">
                                                <button type="submit"
                                                    class="btn btn-primary">{{ __('Save') }}</button>

                                                @if (session('status') === 'password-updated')
                                                    <p x-data="{ show: true }" x-show="show" x-transition
                                                        x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">
                                                        {{ __('Saved.') }}</p>
                                                @endif
                                            </div>
                                        </form>
                                    </section>
                                </div>
                                <div class="tab-pane fade pt-3" id="profile-delete">
                                    <section class="space-y-6">
                                        <header>
                                            <h2 class="text-lg font-medium text-gray-900">
                                                {{ __('Delete Account') }}
                                            </h2>

                                            <p class="mt-1 text-sm text-gray-600">
                                                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                                            </p>
                                        </header>

                                        <x-danger-button x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">{{ __('Delete Account') }}</x-danger-button>

                                        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                                            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                                                @csrf
                                                @method('delete')

                                                <h2 class="text-lg font-medium text-gray-900">
                                                    {{ __('Are you sure you want to delete your account?') }}
                                                </h2>

                                                <p class="mt-1 text-sm text-gray-600">
                                                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                                                </p>

                                                <div class="mt-6">
                                                    <x-input-label for="password" value="{{ __('Password') }}"
                                                        class="sr-only" />

                                                    <x-text-input id="password" name="password" type="password"
                                                        class="mt-1 block w-3/4" placeholder="{{ __('Password') }}" />

                                                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                                                </div>

                                                <div class="mt-6 flex justify-end">
                                                    <x-secondary-button x-on:click="$dispatch('close')">
                                                        {{ __('Cancel') }}
                                                    </x-secondary-button>

                                                    <x-danger-button class="ms-3">
                                                        {{ __('Delete Account') }}
                                                    </x-danger-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    </section>
                                </div>
                                <!-- Add other tabs content here -->

                            </div><!-- End Bordered Tabs -->

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main><!-- End #main -->
@endsection
