<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>StarTaxi</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- Favicons -->
    <link href="{{ asset('img/logoo.png') }}" rel="icon">
    <link href="{{ asset('img/logoo.png') }}" rel="apple-touch-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/simple-datatables/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Template Main CSS File -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    {{-- for view maps for every customer --}}
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <!-- Vendor CSS Files -->
    <!-- Template Main CSS File -->
    <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Nov 17 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->

</head>

<body class="font-sans antialiased">

    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="py-10">
            @yield('content')
        </main>
    </div>


    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; <span>شركة</span> <strong><span>StarTaxi</span></strong>.
        </div>
        <div class="credits">

            <h6><b> الشركة المصممة : <a href="https://abdalrhmanal.github.io/CV-MY">Smart Code Enginer Company</a></b>
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


    <!-- Template Main JS File -->
    <script src="{{ asset('js/main.js') }}"></script>

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
                    console.log(driver);
                    console.log(customer);
                    console.log(message);

                    alert(driver);
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

                    alert("لقد وصل طلب جديد");

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
                                    <h4>جنس العميل: ${gender}</h4>
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
                                        ${drivers.map(driver => `
                                            @if ($driver->gender == $lifeTaxiMovement->gender)
                                                <option value="${driver.id}">${driver.name}</option>
                                            @endif
                                        `).join('')}
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
                    console.log(55);

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

</body>

</html>
