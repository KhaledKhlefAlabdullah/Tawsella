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
    <link href="{{ asset('img/logo.png') }}" rel="icon">
    <link href="{{ asset('img/logo.png') }}" rel="apple-touch-icon">
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

    <!-- Vendor CSS Files -->
    <!-- Template Main CSS File -->
    <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Nov 17 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  <style>
    .container {
        max-width: 50vw;
        margin: 50px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        justify-content: center;

    }

    .form-title {
        text-align: center;
        color: #333;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-label {
        font-weight: bold;
    }

    .form-input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        resize: vertical;
    }

    .form-submit {
        background-color: #4CAF50;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
    }

    .form-submit:hover {
        background-color: #45a049;
    }
</style>
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
    {{-- <script>
           setTimeout(() => {
            var userId = <?php echo json_encode(auth()->id()); ?>;
            Echo.private(`Taxi-movement.${userId}`).
            listen('.App\\Events\\CreateTaxiMovementEvent', (e) => {

            });}, 200);
    </script> --}}
    <script>
        setTimeout(() => {
            var userId = <?php echo json_encode(auth()->id()); ?>;
            Echo.private(`Taxi-movement.${userId}`)
                .listen('.App\\Events\\CreateTaxiMovementEvent', (event) => {
                    // Extract data from the event
                    var customer = event.customer;
                    var locationLat = event.location_lat;
                    var locationLong = event.location_long;
                    var gender = event.gender;
                    var customer_address = event.customer_address;
                    var destnation_address = event.destnation_address;


                    // Update the user interface with the received data
                    var requestHtml = `
                    <li>
                      <div class="card">
                          <h2>اشعار طلب جديد</h2>
                          <hr>
                          <div class="row">
                              <div class="col-lg-6 mb-6">
                                  <div class="text-center card-content" style="margin: 10px;">
                                      <h4>اسم العميل: </h4><h4>${customer.name}</h4>
                                  </div>
                              </div>
                              <div class="col-lg-6 mb-6">
                                  <div class="text-center card-content" style="margin: 10px;">
                                    <h4>صورة العميل: </h4><h4><img src="${customer.user_avatar}" alt="صورة العميل"/></h4>
                                  </div>
                              </div>
                              <div class="col-lg-6 mb-6">
                                  <div class="text-center card-content" style="margin: 10px;">
                                    <h4>رقم العميل: </h4><h4>${customer.phoneNumber}</h4>
                                  </div>
                              </div>
                              <div class="col-lg-6 mb-6">
                                  <div class="text-center card-content" style="margin: 10px;">
                                    <h4>جنس العميل: </h4><h4>${gender}</h4>
                                  </div>
                              </div>
                              <div class="col-lg-6 mb-6">
                                  <div class="text-center card-content" style="margin: 10px;">
                                    <h4>عنوان العميل: </h4><h4>${customer_address}</h4>
                                  </div>
                              </div>
    // لعرض موقع الزبون على الخريطة
    //                           <iframe
    // src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13035.963879898062!2d${start_longitude}!3d${start_latitude}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c2598f267cb08b%3A0xae4b9b4fc4d9dc07!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sin!4v1608576658584!5m2!1sen!2sin"
    // width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>

                              <div class="col-lg-6 mb-6" >
                                  <div class="text-center card-content" style="margin: 10px;">
                                    <h4>وجهة العميل: </h4><h4>${destnation_address}</h4>
                                  </div>
                              </div>
                          </div>
                          <hr>
                          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13035.963879898062!2d${locationLong}!3d${locationLat}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c2598f267cb08b%3A0xae4b9b4fc4d9dc07!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sin!4v1608576658584!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                          <hr>
                       
                      </div>
                    </li>`;

                    // Append the request HTML to a container
                    document.getElementById('requests-container').innerHTML += requestHtml;
                });
        }, 200);
         
    </script>
    

</body>

</html>
