<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-xl-none" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="https://demos.creative-tim.com/material-dashboard/pages/dashboard" target="_blank">
            <img src="{{ asset('img/logo-ct.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold text-white">Material Dashboard 2</span>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            
            <!-- لوحة التحكم -->
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ url('/dashboard') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1">لوحة التحكم</span>
                </a>
            </li>

            <!-- السائقين -->
            <li class="nav-item">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#driversMenu" aria-expanded="false">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">directions_car</i>
                    </div>
                    <span class="nav-link-text ms-1">السائقين</span>
                </a>
                <div class="collapse" id="driversMenu">
                    <ul class="nav ms-4 ps-3">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('drivers.index') }}">
                                <i class="material-icons opacity-10">visibility</i>
                                <span class="nav-link-text ms-1">عرض السائقين</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('drivers.create') }}">
                                <i class="material-icons opacity-10">person_add</i>
                                <span class="nav-link-text ms-1">اضافة سائق</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- السيارات -->
            <li class="nav-item">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#taxisMenu" aria-expanded="false">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">taxi_alert</i>
                    </div>
                    <span class="nav-link-text ms-1">السيارات</span>
                </a>
                <div class="collapse" id="taxisMenu">
                    <ul class="nav ms-4 ps-3">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('taxis.index') }}">
                                <i class="material-icons opacity-10">visibility</i>
                                <span class="nav-link-text ms-1">عرض السيارات</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('taxis.create') }}">
                                <i class="material-icons opacity-10">add_circle_outline</i>
                                <span class="nav-link-text ms-1">اضافة سيارة</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- الطلبات -->
            <li class="nav-item">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#ordersMenu" aria-expanded="false">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">hourglass_empty</i>
                    </div>
                    <span class="nav-link-text ms-1">الطلبات</span>
                </a>
                <div class="collapse" id="ordersMenu">
                    <ul class="nav ms-4 ps-3">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('current.taxi.movement') }}">
                                <i class="material-icons opacity-10">schedule</i>
                                <span class="nav-link-text ms-1">الطلبات الحالية</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('completed.requests') }}">
                                <i class="material-icons opacity-10">check_circle_outline</i>
                                <span class="nav-link-text ms-1">الطلبات المكتملة</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- الاضافات -->
            <li class="nav-item">
                <a class="nav-link text-white" data-bs-toggle="collapse" href="#extrasMenu" aria-expanded="false">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">add_box</i>
                    </div>
                    <span class="nav-link-text ms-1">الاضافات</span>
                </a>
                <div class="collapse" id="extrasMenu">
                    <ul class="nav ms-4 ps-3">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ url('/services') }}">
                                <i class="material-icons opacity-10">build</i>
                                <span class="nav-link-text ms-1">الخدمات</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('offers.index') }}">
                                <i class="material-icons opacity-10">local_offer</i>
                                <span class="nav-link-text ms-1">العروض</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- الرصيد والحسابات -->
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('calculations.index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">account_balance_wallet</i>
                    </div>
                    <span class="nav-link-text ms-1">رصيد السائقين</span>
                </a>
            </li>

            <!-- الملف الشخصي -->
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ url('/profiles') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">person</i>
                    </div>
                    <span class="nav-link-text ms-1">الملف الشخصي</span>
                </a>
            </li>

            <!-- الاعدادات -->
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ url('/profiles') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">settings</i>
                    </div>
                    <span class="nav-link-text ms-1">الاعدادات</span>
                </a>
            </li>

            <!-- تسجيل الخروج -->
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="nav-link text-white" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">logout</i>
                        </div>
                        <span class="nav-link-text ms-1">تسجيل الخروج</span>
                    </a>
                </form>
            </li>
        </ul>
    </div>

</aside>
