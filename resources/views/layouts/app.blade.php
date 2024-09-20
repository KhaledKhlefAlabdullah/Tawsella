<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <title>
        Tawsella
    </title>
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link href="{{ asset('css/nucleo-icons.css" rel="stylesheet') }}" />
    <link href="{{ asset('css/nucleo-svg.css" rel="stylesheet') }}" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link id="pagestyle" href="{{ asset('css/material-dashboard.css?v=3.1.0') }}" rel="stylesheet" />
    <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>

</head>

<body class="g-sidenav-show  bg-gray-200">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')
        <main class="main-content position-relative border-radius-lg">
            <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
                data-scroll="true">
                <div class="container-fluid py-1 px-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark"
                                    href="javascript:;">Pages</a></li>
                            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
                        </ol>
                        <h6 class="font-weight-bolder mb-0">Dashboard</h6>
                    </nav>
                    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                        <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                            <div class="input-group input-group-outline">
                                <label class="form-label">Type here...</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <ul class="navbar-nav  justify-content-end">
                            <li class="nav-item d-flex align-items-center">
                                <a class="btn btn-outline-primary btn-sm mb-0 me-3" target="_blank"
                                    href="https://www.creative-tim.com/builder?ref=navbar-material-dashboard">Online
                                    Builder</a>
                            </li>
                            <li class="mt-2">
                                <a class="github-button"
                                    href="https://github.com/creativetimofficial/material-dashboard"
                                    data-icon="octicon-star" data-size="large" data-show-count="true"
                                    aria-label="Star creativetimofficial/material-dashboard on GitHub">Star</a>
                            </li>
                            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                                <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                    <div class="sidenav-toggler-inner">
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item px-3 d-flex align-items-center">
                                <a href="javascript:;" class="nav-link text-body p-0">
                                    <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                                </a>
                            </li>
                            <li class="nav-item dropdown pe-2 d-flex align-items-center">
                                <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-bell cursor-pointer"></i>
                                </a>
                                <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4"
                                    aria-labelledby="dropdownMenuButton">
                                    <li class="mb-2">
                                        <a class="dropdown-item border-radius-md" href="javascript:;">
                                            <div class="d-flex py-1">
                                                <div class="my-auto">
                                                    <img src="../assets/img/team-2.jpg" class="avatar avatar-sm  me-3 ">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="text-sm font-weight-normal mb-1">
                                                        <span class="font-weight-bold">New message</span> from Laur
                                                    </h6>
                                                    <p class="text-xs text-secondary mb-0">
                                                        <i class="fa fa-clock me-1"></i>
                                                        13 minutes ago
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a class="dropdown-item border-radius-md" href="javascript:;">
                                            <div class="d-flex py-1">
                                                <div class="my-auto">
                                                    <img src="../assets/img/small-logos/logo-spotify.svg"
                                                        class="avatar avatar-sm bg-gradient-dark  me-3 ">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="text-sm font-weight-normal mb-1">
                                                        <span class="font-weight-bold">New album</span> by Travis Scott
                                                    </h6>
                                                    <p class="text-xs text-secondary mb-0">
                                                        <i class="fa fa-clock me-1"></i>
                                                        1 day
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item border-radius-md" href="javascript:;">
                                            <div class="d-flex py-1">
                                                <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                                                    <svg width="12px" height="12px" viewBox="0 0 43 36"
                                                        version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink">
                                                        <title>credit-card</title>
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <g transform="translate(-2169.000000, -745.000000)"
                                                                fill="#FFFFFF" fill-rule="nonzero">
                                                                <g transform="translate(1716.000000, 291.000000)">
                                                                    <g transform="translate(453.000000, 454.000000)">
                                                                        <path class="color-background"
                                                                            d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z"
                                                                            opacity="0.593633743"></path>
                                                                        <path class="color-background"
                                                                            d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z">
                                                                        </path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="text-sm font-weight-normal mb-1">
                                                        Payment successfully completed
                                                    </h6>
                                                    <p class="text-xs text-secondary mb-0">
                                                        <i class="fa fa-clock me-1"></i>
                                                        2 days
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item d-flex align-items-center">
                                <a href="../pages/sign-in.html" class="nav-link text-body font-weight-bold px-0">
                                    <i class="fa fa-user me-sm-1"></i>
                                    <span class="d-sm-inline d-none">Sign In</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif
            @yield('content')
            <footer class="footer py-4  ">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-lg-6 mb-lg-0 mb-4">
                            <div class="copyright text-center text-sm text-muted text-lg-start">
                                ©
                                <script>
                                    document.write(new Date().getFullYear())
                                </script>,
                                made with <i class="fa fa-heart"></i> by
                                <a href="https://www.creative-tim.com" class="font-weight-bold"
                                    target="_blank">Creative
                                    Tim</a>
                                for a better web.
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com" class="nav-link text-muted"
                                        target="_blank">Creative Tim</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted"
                                        target="_blank">About Us</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com/blog" class="nav-link text-muted"
                                        target="_blank">Blog</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted"
                                        target="_blank">License</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </main>

    </div>

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; <span>شركة</span> <strong><span>StarTaxi</span></strong>.
        </div>
        <div class="credits">

            <h6><b> الشركة المصممة : <a href="">Smart Code Enginer Company</a></b>
            </h6>
        </div>
    </footer><!-- End Footer -->


    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('vendor/php-email-form/validate.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="{{ asset('js/darkmode.js') }}"></script>
    <script src="{{ asset('js/darkmode-config.js') }}"></script>
    <script src="path/to/darkmode.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(deletedroute) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "لن يمكنك التراجع عن هذا الإجراء!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذفه!',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`deleteForm${deletedroute}`).submit();
                }
            });
            return false; // Prevent default form submission
        }
    </script>

    <!-- Template Main JS File -->
    <script src="{{ asset('js/main.js') }}"></script>

    <!-- Reale time Scripts -->
    @vite('resources/js/app.js')
    <script>
        setTimeout(() => {
            var userId = <?php echo json_encode(auth()->id()); ?>;
            Echo.private(`found-customer.${userId}`)
                .listen('.App\\Events\\DriverChangeMovementStateEvent', (event) => {
                    var driver = event.driver;
                    var customer = event.customer;
                    var message = event.message;

                    Swal.fire({
                        position: "top-end",
                        title: message,
                        showConfirmButton: false,
                        timer: 2500
                    });

                });
        }, 200);
    </script>
    <script>
        setTimeout(() => {

            var userId = <?php echo json_encode(auth()->id()); ?>;
            Echo.private(`Taxi-movement.${userId}`)
                .listen('.App\\Events\\CreateTaxiMovementEvent', (event) => {
                    var index = event.index;
                    var drivers = event.drivers;
                    var request_id = event.request_id;
                    var customer = event.customer;
                    var locationLat = event.lat;
                    var locationLong = event.long;
                    var gender = event.gender;
                    var customer_address = event.customer_address;
                    var destination_address = event.destination_address;
                    var time = event.time;

                    Swal.fire({
                        position: "top-end",
                        title: "لقد وصل طلب جديد",
                        showConfirmButton: false,
                        timer: 2000
                    });
                    var newItem = document.createElement('li');
                    newItem.innerHTML = `

            <li id='item${index}'>
                <div class="card">
                    <h2>طلب جديد</h2>
                    <hr>
                    <div class="col">
                        <div class="row align-items-center">
                            <div class="col-lg-6 mb-6">
                                <div class="text-center card-content" style="margin: 10px;">
                                    <h4>اسم العميل: ${customer.name}</h4>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-6">
                                <div class="text-center card-content" style="margin: 10px;">
                                    <h4>صورة العميل: <img class="img" src="{{ asset('assets') }}${customer.avatar}" alt="صورة العميل" /></h4>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center ">
                            <div class="col-lg-6 mb-6">
                                <div class="text-center card-content" style="margin: 10px;">
                                    <h4>رقم العميل: ${customer.phoneNumber}</h4>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-6">
                                <div class="text-center card-content" style="margin: 10px;">
                                    <h4>الجنس: <span
                                                        style="color: ${ gender == 'male' ? '#4154f1' : 'pink' }">${ gender == 'male' ? 'ذكر' : 'انثى' }</span>
                                                </h4>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-lg-6 mb-6">
                                <div class="text-center card-content" style="margin: 10px;">
                                    <h4>عنوان العميل: ${customer_address}</h4>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-6">
                                <div class="text-center card-content" style="margin: 10px;">
                                    <h4>وجهة العميل: ${destination_address}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                        <div class="col-lg-6 mb-6">

                            <div class="text-center card-content" style="margin: 10px;">
                                <h4>التوقيت: ${time}</h4>
                            </div>
                        </div>
                    <hr>
                    <div id="map${index}" class="map"></div>
                    <hr>
                    <div id="buttons${index}" class="p-4 w-100" style="display: block;">
                                    <button id='accept${index}' class="btn rounded btn-success"
                                        onclick="showAcceptForm(${index})">قبول</button>
                                    <button id='reject${index}' class="btn rounded btn-danger"
                                        onclick="showRejectForm(${index})">رفض</button>
                                </div>
                                <button id="cancel${index}" class="btn" style="display: none;"
                                    onclick="showButtons(${index})"><i class="fa-solid fa-x"></i></button>

                                <form id="accept-form${index}" method="POST"
                                    action="{{ url('/accept-reject-request/${request_id}') }}"
                                    style="display: none;">
                                    @csrf <!-- Add CSRF token for Laravel form submission -->
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <div id="driver-field${index}" class="form-group">
                                        <label for="driver_id" class="form-label">اسم السائق:</label><br>

                                                <select id="driver_id" name="driver_id" class="form-input" required>
                                        <option value="">اختر السائق</option>
                                        ${drivers.map(driver => driver.gender == gender ? `<option value="${driver.id}">${driver.name}</option> ` : '').join('')}
                                        </select>
                                    </div>
                                    <!-- Hidden input field for static state -->
                                    <input type="hidden" name="state" value="accepted">
                                    <input type="submit" class="form-submit">
                                </form>

                                <form id="reject-form${index}" method="POST"
                                    action="{{ url('/accept-reject-request/${request_id}') }}"
                                    style="display: none;">
                                    @csrf <!-- Add CSRF token for Laravel form submission -->
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <div id="reason-field${index}" class="form-group">
                                        <label for="reason" class="form-label">السبب (اختياري):</label><br>
                                        <textarea id="reason${index}" name="message" class="form-input" rows="4" cols="50" required>{{ old('message') }}</textarea>
                                    </div>
                                    <!-- Hidden input field for static state -->
                                    <input type="hidden" name="state" value="rejected">
                                    <input type="submit" class="form-submit">
                                </form>
                    </div>

                    </li>
                    `;

                    document.getElementById('requests-container').appendChild(newItem);

                    var script = document.createElement('script');

                    // Set the text content of the script
                    script.textContent = `
                    var map${index} = L.map('map${index}').setView([${locationLat}, ${locationLong}], 10); // Set the map view with latitude and longitude of the current request
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                                }).addTo(map${index});
                                var marker${index} = L.marker([${locationLat}, ${locationLong}]).addTo(
                                    map${index});
                                marker${index}.bindPopup(JSON.stringify('${customer.name}')).openPopup();
                                // You can customize the popup content as needed

                                function showAcceptForm(index) {
                                    document.getElementById('accept-form' + index).style.display = 'block';
                                    document.getElementById('reject-form' + index).style.display = 'none';
                                    document.getElementById('buttons' + index).style.display = 'none';
                                    document.getElementById('cancel' + index).style.display = 'block';

                                }

                                function showRejectForm(index) {
                                    document.getElementById('accept-form' + index).style.display = 'none';
                                    document.getElementById('reject-form' + index).style.display = 'block';
                                    document.getElementById('buttons' + index).style.display = 'none';
                                    document.getElementById('cancel' + index).style.display = 'block';

                                }

                                function showButtons(index){
                                    document.getElementById('accept-form' + index).style.display = 'none';
                                    document.getElementById('reject-form' + index).style.display = 'none';
                                    document.getElementById('buttons' + index).style.display = 'block';
                                    document.getElementById('cancel' + index).style.display = 'none';
                                }
                                `;


                    // Get the HTML element to append the script to
                    var item = document.getElementById(`item${index}`);

                    // Append the script element to the HTML element
                    item.appendChild(script);

                });
        }, 1000); // Set a delay of 1 second (1000 milliseconds) to ensure proper rendering after the page load
    </script>
    <script src="{{ asset('js/core/popper.min.js') }}"></script>
    <script src="{{ asset('js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/plugins/chartjs.min.js') }}"></script>
    <script>
        function confirmDelete(deletedroute) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "لن يمكنك التراجع عن هذا الإجراء!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذفه!',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`deleteForm${deletedroute}`).submit();
                }
            });
            return false; // Prevent default form submission
        }
    </script>
    <!-- Reale time Scripts -->
    @vite('resources/js/app.js')
    <script>
        setTimeout(() => {
            var userId = <?php echo json_encode(auth()->id()); ?>;
            Echo.private(`movemnt.${userId}`)
                .listen('.App\\Events\\MovementFindUnFindEvent', (event) => {
                    var driver = event.driver;
                    var customer = event.customer;
                    var message = event.message;

                    Swal.fire({
                        position: "top-end",
                        title: message,
                        showConfirmButton: false,
                        timer: 2500
                    });

                });
        }, 200);
    </script>

    <script>
        setTimeout(() => {

            var userId = <?php echo json_encode(auth()->id()); ?>;
            Echo.private(`Taxi-movement.${userId}`)
                .listen('.App\\Events\\CreateTaxiMovementEvent', (event) => {
                    var index = event.index;
                    var drivers = event.drivers;
                    var request_id = event.request_id;
                    var customer = event.customer;
                    var locationLat = event.lat;
                    var locationLong = event.long;
                    var gender = event.gender;
                    var customer_address = event.customer_address;
                    var destnation_address = event.destnation_address;
                    var time = event.time;

                    Swal.fire({
                        position: "top-end",
                        title: "لقد وصل طلب جديد",
                        showConfirmButton: false,
                        timer: 2000
                    });
                    var newItem = document.createElement('li');
                    newItem.innerHTML = `

            <li id='item${index}'>
                <div class="card">
                    <h2>طلب جديد</h2>
                    <hr>
                    <div class="col">
                        <div class="row align-items-center">
                            <div class="col-lg-6 mb-6">
                                <div class="text-center card-content" style="margin: 10px;">
                                    <h4>اسم العميل: ${customer.name}</h4>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-6">
                                <div class="text-center card-content" style="margin: 10px;">
                                    <h4>صورة العميل: <img class="img" src="{{ asset('assets') }}${customer.avatar}" alt="صورة العميل" /></h4>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center ">
                            <div class="col-lg-6 mb-6">
                                <div class="text-center card-content" style="margin: 10px;">
                                    <h4>رقم العميل: ${customer.phoneNumber}</h4>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-6">
                                <div class="text-center card-content" style="margin: 10px;">
                                    <h4>الجنس: <span
                                                        style="color: ${ gender == 'male' ? '#4154f1' : 'pink' }">${ gender == 'male' ? 'ذكر' : 'انثى' }</span>
                                                </h4>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-lg-6 mb-6">
                                <div class="text-center card-content" style="margin: 10px;">
                                    <h4>عنوان العميل: ${customer_address}</h4>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-6">
                                <div class="text-center card-content" style="margin: 10px;">
                                    <h4>وجهة العميل: ${destnation_address}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                        <div class="col-lg-6 mb-6">

                            <div class="text-center card-content" style="margin: 10px;">
                                <h4>التوقيت: ${time}</h4>
                            </div>
                        </div>
                    <hr>
                    <div id="map${index}" class="map"></div>
                    <hr>
                    <div id="buttons${index}" class="p-4 w-100" style="display: block;">
                                    <button id='accept${index}' class="btn rounded btn-success"
                                        onclick="showAcceptForm(${index})">قبول</button>
                                    <button id='reject${index}' class="btn rounded btn-danger"
                                        onclick="showRejectForm(${index})">رفض</button>
                                </div>
                                <button id="cancel${index}" class="btn" style="display: none;"
                                    onclick="showButtons(${index})"><i class="fa-solid fa-x"></i></button>

                                <form id="accept-form${index}" method="POST"
                                    action="{{ url('/accept-reject-request/${request_id}') }}"
                                    style="display: none;">
                                    @csrf <!-- Add CSRF token for Laravel form submission -->
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <div id="driver-field${index}" class="form-group">
                                        <label for="driver_id" class="form-label">اسم السائق:</label><br>

                                                <select id="driver_id" name="driver_id" class="form-input" required>
                                        <option value="">اختر السائق</option>
                                        ${drivers.map(driver => driver.gender == gender ? `<option value="${driver.id}">${driver.name}</option> ` : '').join('')}
                                        </select>
                                    </div>
                                    <!-- Hidden input field for static state -->
                                    <input type="hidden" name="state" value="accepted">
                                    <input type="submit" class="form-submit">
                                </form>

                                <form id="reject-form${index}" method="POST"
                                    action="{{ url('/accept-reject-request/${request_id}') }}"
                                    style="display: none;">
                                    @csrf <!-- Add CSRF token for Laravel form submission -->
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <div id="reason-field${index}" class="form-group">
                                        <label for="reason" class="form-label">السبب (اختياري):</label><br>
                                        <textarea id="reason${index}" name="message" class="form-input" rows="4" cols="50" required>{{ old('message') }}</textarea>
                                    </div>
                                    <!-- Hidden input field for static state -->
                                    <input type="hidden" name="state" value="rejected">
                                    <input type="submit" class="form-submit">
                                </form>
                    </div>

                    </li>
                    `;

                    document.getElementById('requests-container').appendChild(newItem);

                    var script = document.createElement('script');

                    // Set the text content of the script
                    script.textContent = `
                    var map${index} = L.map('map${index}').setView([${locationLat}, ${locationLong}], 10); // Set the map view with latitude and longitude of the current request
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                                }).addTo(map${index});
                                var marker${index} = L.marker([${locationLat}, ${locationLong}]).addTo(
                                    map${index});
                                marker${index}.bindPopup(JSON.stringify('${customer.name}')).openPopup();
                                // You can customize the popup content as needed

                                function showAcceptForm(index) {
                                    document.getElementById('accept-form' + index).style.display = 'block';
                                    document.getElementById('reject-form' + index).style.display = 'none';
                                    document.getElementById('buttons' + index).style.display = 'none';
                                    document.getElementById('cancel' + index).style.display = 'block';

                                }

                                function showRejectForm(index) {
                                    document.getElementById('accept-form' + index).style.display = 'none';
                                    document.getElementById('reject-form' + index).style.display = 'block';
                                    document.getElementById('buttons' + index).style.display = 'none';
                                    document.getElementById('cancel' + index).style.display = 'block';

                                }

                                function showButtons(index){
                                    document.getElementById('accept-form' + index).style.display = 'none';
                                    document.getElementById('reject-form' + index).style.display = 'none';
                                    document.getElementById('buttons' + index).style.display = 'block';
                                    document.getElementById('cancel' + index).style.display = 'none';
                                }
                                `;


                    // Get the HTML element to append the script to
                    var item = document.getElementById(`item${index}`);

                    // Append the script element to the HTML element
                    item.appendChild(script);

                });
        }, 1000); // Set a delay of 1 second (1000 milliseconds) to ensure proper rendering after the page load
    </script>
    <script>
        var ctx = document.getElementById("chart-bars").getContext("2d");

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["M", "T", "W", "T", "F", "S", "S"],
                datasets: [{
                    label: "Sales",
                    tension: 0.4,
                    borderWidth: 0,
                    borderRadius: 4,
                    borderSkipped: false,
                    backgroundColor: "rgba(255, 255, 255, .8)",
                    data: [50, 20, 10, 22, 50, 10, 40],
                    maxBarThickness: 6
                }, ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5],
                            color: 'rgba(255, 255, 255, .2)'
                        },
                        ticks: {
                            suggestedMin: 0,
                            suggestedMax: 500,
                            beginAtZero: true,
                            padding: 10,
                            font: {
                                size: 14,
                                weight: 300,
                                family: "Roboto",
                                style: 'normal',
                                lineHeight: 2
                            },
                            color: "#fff"
                        },
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5],
                            color: 'rgba(255, 255, 255, .2)'
                        },
                        ticks: {
                            display: true,
                            color: '#f8f9fa',
                            padding: 10,
                            font: {
                                size: 14,
                                weight: 300,
                                family: "Roboto",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });


        var ctx2 = document.getElementById("chart-line").getContext("2d");

        new Chart(ctx2, {
            type: "line",
            data: {
                labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Mobile apps",
                    tension: 0,
                    borderWidth: 0,
                    pointRadius: 5,
                    pointBackgroundColor: "rgba(255, 255, 255, .8)",
                    pointBorderColor: "transparent",
                    borderColor: "rgba(255, 255, 255, .8)",
                    borderColor: "rgba(255, 255, 255, .8)",
                    borderWidth: 4,
                    backgroundColor: "transparent",
                    fill: true,
                    data: [50, 40, 300, 320, 500, 350, 200, 230, 500],
                    maxBarThickness: 6

                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5],
                            color: 'rgba(255, 255, 255, .2)'
                        },
                        ticks: {
                            display: true,
                            color: '#f8f9fa',
                            padding: 10,
                            font: {
                                size: 14,
                                weight: 300,
                                family: "Roboto",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#f8f9fa',
                            padding: 10,
                            font: {
                                size: 14,
                                weight: 300,
                                family: "Roboto",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });

        var ctx3 = document.getElementById("chart-line-tasks").getContext("2d");

        new Chart(ctx3, {
            type: "line",
            data: {
                labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Mobile apps",
                    tension: 0,
                    borderWidth: 0,
                    pointRadius: 5,
                    pointBackgroundColor: "rgba(255, 255, 255, .8)",
                    pointBorderColor: "transparent",
                    borderColor: "rgba(255, 255, 255, .8)",
                    borderWidth: 4,
                    backgroundColor: "transparent",
                    fill: true,
                    data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
                    maxBarThickness: 6

                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5],
                            color: 'rgba(255, 255, 255, .2)'
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#f8f9fa',
                            font: {
                                size: 14,
                                weight: 300,
                                family: "Roboto",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#f8f9fa',
                            padding: 10,
                            font: {
                                size: 14,
                                weight: 300,
                                family: "Roboto",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });
    </script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('js/material-dashboard.min.js?v=3.1.0') }}"></script>
</body>

</html>
