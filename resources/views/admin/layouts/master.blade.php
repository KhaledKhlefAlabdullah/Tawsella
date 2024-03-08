<!doctype html>
<html lang="en" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="dark">
<!-- Start head -->
@include('admin.layouts.head')
<!-- End head -->

<body class="app sidebar-mini">

    <!-- Start Switcher -->
    @include('admin.layouts.switcher')
    <!-- End Switcher -->



    <!-- GLOBAL-LOADER -->
    @include('admin.layouts.loader')
    <!-- /GLOBAL-LOADER -->

    <!-- PAGE -->
    <div class="page">
        <div class="page-main">

            <!--Start App Header -->
            @include('admin.layouts.header')
            <!-- End App Header -->


            <!-- Start::app-sidebar -->
            <aside class="app-sidebar sticky" id="sidebar">



                <!-- Start::main-sidebar -->
                @include('admin.layouts.main-sidebar')
                <!-- End::main-sidebar -->

            </aside>
            <!-- End::app-sidebar -->


            <!--app-content open-->
            <div class="main-content app-content mt-0">

                <!-- PAGE-HEADER -->
                @yield('page-header')
                <!-- PAGE-HEADER END -->

                <!-- CONTAINER -->
                <div class="main-container container-fluid">

                    <!-- Start::Row-1 -->
                    @yield('content')
                    <!-- End::Row-1 -->

                </div>
                <!-- CONTAINER END -->
            </div>
            <!--app-content close-->

        </div>

        <!--Start Search Modal -->
        @include('admin.layouts.search-modal')
        <!-- End Search Modal   -->

        <!-- FOOTER -->
        @include('admin.layouts.footer')
        <!-- FOOTER CLOSED -->


    </div>


    <!-- Scroll To Top -->
    <div class="scrollToTop">
        <span class="arrow"><i class="fa fa-angle-up fs-20"></i></span>
    </div>
    <!-- Scroll To Top -->

    <div id="responsive-overlay"></div>

    @include('admin.layouts.foot-script')


</body>


</html>
