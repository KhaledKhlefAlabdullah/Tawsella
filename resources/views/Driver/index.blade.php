@extends('layouts.app')

@section('content')
    <main id="main" class="main">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">
                <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start">
                            <h6>{{ __('فلترة') }}</h6>
                        </li>
                        <li><a class="dropdown-item" href="#">{{ __('كل السائقين') }}</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ __('السائقين') }} <span>| {{ __('سجل') }}</span></h5>
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <table class="table table-borderless datatable">
                        <thead>
                            <tr style="margin-left: 15px">
                                <th scope="col">#</th>
                                <th scope="col">{{ __('السائق') }}</th>
                                <th scope="col">{{ __('الايميل') }}</th>
                                <th scope="col">{{ __('الرصيد') }}</th>
                                <th scope="col">{{ __('رقم السيارة') }}</th>
                                <th scope="col">{{ __('حالة الحساب') }}</th>
                                <th scope="col">{{ __('ادارة') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                                    <tr>
                                        <th scope="row"><a href="#">1</a></th>
                                        <td>name</td>
                                        <td><a href="#" class="text-primary">email </a></td>
                                        <td>
                                            balances
                                            {{-- @if ($user->padding)
                                                <a href="{{ route('balances.show', $user->id) }}">$
                                                    {{ $user->balance->credit_balance }}</a>
                                            @else
                                                {{ __('No balance available') }}
                                            @endif --}}
                                        </td>
                                        <td>
                                            <a href="#">{{ __('Edit') }}</a>
                                        </td>
                                        <td>
                                            <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $user->is_active ? __('Active') : __('Inactive') }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('users.toggleStatus', $user->id) }}" method="POST"
                                                onsubmit="return confirmStatusChange(event, {{ $user->is_active }})">
                                                @csrf
                                                @method('POST')
                                                <button type="submit">
                                                    <i class="bi bi-power" style="margin-left: 30px;"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    {{-- <script>
        function confirmStatusChange(event, isActive) {
            if (!confirm(isActive ? '{{ __('The account will be deactivated. Are you sure?') }}' :
                    '{{ __('The account will be reactivated. Are you sure?') }}')) {
                event.preventDefault();
            }
        }
    </script> --}}
@endsection
