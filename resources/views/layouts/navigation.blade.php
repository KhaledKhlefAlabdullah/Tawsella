<header id="header" class="header fixed-top  align-items-center">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-4">
                <nav class="header-nav ms-auto">
                    <ul class="d-flex align-items-center">

                        <li class="nav-item dropdown pe-3">

                            <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                                data-bs-toggle="dropdown">
                                <span
                                    class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->user_type }}</span>
                            </a><!-- End Profile Image Icon -->

                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                                <li class="dropdown-header">
                                    @auth
                                        <h6>{{ Auth::user()->user_type }}</h6>
                                        <span>({{ Auth::user()->user_type ?? 'Default Role' }})</span>
                                    @endauth
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="/profiles">
                                        <i class="bi bi-person"></i>
                                        <span>{{ __('ملفي الشخصي') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="/profiles">
                                        <i class="bi bi-gear"></i>
                                        <span>{{ __('اعدادات الحساب') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ url('/Contact') }}">
                                        <i class="bi bi-question-circle"></i>
                                        <span>{{ __('مساعدة') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf

                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); this.closest('form').submit();"
                                            class="dropdown-item d-flex align-items-center">
                                            <i class="bi bi-box-arrow-right"></i>
                                            <span>{{ __('تسجيل الخروج') }}</span>
                                        </a>
                                    </form>
                                </li>

                            </ul><!-- End Profile Dropdown Items -->
                        </li><!-- End Profile Nav -->


                    </ul>
                </nav><!-- End Icons Navigation -->
            </div>
            <div class="col-5"></div>
            <div class="col-3">
                <div class="row">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="col-4" style="justify-content:end;margin-right: 17px">
                            <i class="bi bi-list toggle-sidebar-btn"></i>
                        </div>
                        <a class="logo d-flex align-items-center">
                            <img src="{{ asset('/img/logoo.png') }}" style="width: 60px;height: 40px;margin: 10px"
                                alt="">
                            <span class="d-none d-lg-block" style="font-family: 'Cairo', sans-serif ;color: white; ">{{ __('الشهباء') }}</span>
                        </a>
                    </div><!-- End Logo -->
                </div>
            </div>

        </div>
    </div>
</header><!-- End Header -->

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar ">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link " href="{{ url('/dashboard') }}">
                <span class="ms-auto">لوحة التحكم</span>
                <i class="bi bi-grid " style="margin-left: 10px"></i>

            </a>
        </li><!-- End Dashboard Nav -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-chevron-down"></i><span class="ms-auto">السائقين</span><i
                    class="bi bi-menu-button-wide " style="margin-left: 10px"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ url('/drivers/') }}">
                        <div class="ms-auto" style="margin-right: 30px">
                            <span>عرض السائقين</span><i class="bi bi-circle" style="margin-left: 10px"></i>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('create.driver') }}">
                        <div class="ms-auto" style="margin-right: 30px">
                            <span>اضافة سائق</span><i class="bi bi-circle" style="margin-left: 10px"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-chevron-down "></i><span class="ms-auto">السيارات</span><i class="bi bi-taxi-front-fill" style="margin-left: 10px"></i>
            </a>
            <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('taxis.index') }}">
                        <div class="ms-auto" style="margin-right: 30px">
                            <span> عرض السيارات </span><i class="bi bi-circle" style="margin-left: 10px"></i>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('taxis.create') }}">
                        <div class="ms-auto" style="margin-right: 30px">
                            <span> اضافة سيارة </span><i class="bi bi-circle" style="margin-left: 10px"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </li><!-- End Forms Nav -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#taxi-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-chevron-down "></i><span class="ms-auto">الطلبات</span><i class="bi bi-hourglass" style="margin-left: 10px"></i>
            </a>
            <ul id="taxi-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('current.taxi.movement') }}">
                        <div class="ms-auto" style="margin-right: 30px">
                            <span> الطلبات الحالية </span><i class="bi bi-circle" style="margin-left: 10px"></i>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('completed.requests') }}">
                        <div class="ms-auto" style="margin-right: 30px">
                            <span> الطلبات المكتملة </span><i class="bi bi-circle" style="margin-left: 10px"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </li><!-- End Forms Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-chevron-down "></i><span class=" ms-auto">الاضافات</span>
                <i class="bi bi-layout-text-window-reverse" style="margin-left: 10px"></i>
            </a>
            <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ url('/serve') }}">
                        <div class="ms-auto" style="margin-right: 30px">
                            <span>الخدمات</span><i class="bi bi-circle" style="margin-left: 10px"></i>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('offers.index') }}">
                        <div class="ms-auto" style="margin-right: 30px">
                            <span>العروض</span><i class="bi bi-circle" style="margin-left: 10px"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </li><!-- End Tables Nav -->

        <li class="nav-item">
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-chevron-down "></i> <span class="ms-auto">الرصيد والحسابات</span><i
                    class="bi bi-gem "style="margin-left: 10px"></i>
            </a>
            <ul id="icons-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('calculations.index') }}">
                        <div class="ms-auto" style="margin-right: 30px">
                            <span> رصيد السائقين </span><i class="bi bi-circle" style="margin-left: 10px"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </li><!-- End Icons Nav -->

        <center>
            <li
                style="font-size: 11px;
                            text-transform: uppercase;
                            color: #899bbd;
                            font-weight: 600;">
                صفحات</li>
        </center>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ url('/profiles') }}">
                <span class="ms-auto">الملف الشخصي</span><i class="bi bi-person " style="margin-left: 10px"></i>

            </a>
        </li><!-- End Profile Page Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ url('/Contact') }}">
                <span class="ms-auto">التواصل</span>
                <i class="bi bi-envelope " style="margin-left: 10px"></i>
            </a>
        </li><!-- End Contact Page Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ url('/profiles') }}">
                <span class="ms-auto">الاعدادات</span>
                <i class="bi bi-gear " style="margin-left: 10px"></i>
            </a>
        </li><!-- End Blank Page Nav -->
        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a class="nav-link collapsed" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    <span class="ms-auto">تسجيل الخروج</span>
                    <i class="bi bi-box-arrow-right" style="margin-left: 10px"></i>
                </a>
            </form>
        </li><!-- End Blank Page Nav -->
    </ul>

</aside><!-- End Sidebar-->
