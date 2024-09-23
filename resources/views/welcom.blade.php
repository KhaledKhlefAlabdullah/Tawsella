<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Star Taxi</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <link href="{{ asset('assets/img/logoo.png') }}" rel="icon">
    <link href="{{ asset('assets/img/logoo.png') }}" rel="apple-touch-icon">

    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top d-flex align-items-center header-transparent">
        <div class="container d-flex align-items-center justify-content-between">

            <div class="logo">
                <h1><a href="/"><span>Tawsella</span></a></h1>
                <!-- Uncomment below if you prefer to use an image logo -->
                <!-- <a href="index.html"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->
            </div>

            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto active" href="#hero">الصفحة الرئيسية</a></li>
                    <li><a class="nav-link scrollto" href="#about">عنا</a></li>
                    <li><a class="nav-link scrollto" href="#features">خدماتنا</a></li>
                    <li><a class="nav-link scrollto" href="#team">السائقين</a></li>
                    <li><a class="nav-link scrollto" href="#pricing">الاسعار و الرحلات</a></li>
                    <li><a class="nav-link scrollto" href="#contact">التواصل</a></li>
                    <li><a class="nav-link scrollto" href="/login">تسجيل الدخول</a></li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->

        </div>
    </header><!-- End Header -->

    <!-- ======= Hero Section ======= -->
    <section id="hero">

        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-7 pt-5 pt-lg-0 order-2 order-lg-1 d-flex align-items-center">
                    <div data-aos="zoom-out">
                        <h1>اكبر شركة للنقل الداخلي الخاص هي شركة <span>Tawsella</span></h1><br><br><br>
                        <h2> نحن في شركة ستارتكسي نقدم خدمات التنقل الخاص داخل المناطق المحررة على مدار الساعة بلا توقف
                        </h2>
                        <div class="text-center text-lg-start">
                            <a href="#about" class="btn-get-started scrollto"> ابدأ الان </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 order-1 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="300">
                    <img src="{{ asset('assets/img/hero-img.png') }}" class="img-fluid animated" alt="">
                </div>
            </div>
        </div>

        <svg class="hero-waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
            viewBox="0 24 150 28 " preserveAspectRatio="none">
            <defs>
                <path id="wave-path" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z">
            </defs>
            <g class="wave1">
                <use xlink:href="#wave-path" x="50" y="3" fill="rgba(255,255,255, .1)">
            </g>
            <g class="wave2">
                <use xlink:href="#wave-path" x="50" y="0" fill="rgba(255,255,255, .2)">
            </g>
            <g class="wave3">
                <use xlink:href="#wave-path" x="50" y="9" fill="#fff">
            </g>
        </svg>

    </section><!-- End Hero -->

    <main id="main">

        <!-- ======= About Section ======= -->
        <section id="about" class="about">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-xl-5 col-lg-6 video-box d-flex justify-content-center align-items-stretch"
                        data-aos="fade-right">
                        <a href="https://youtu.be/4Zp3XNicmZ8?si=kgTORZYOugXM-RE9" class="glightbox play-btn mb-4"></a>
                    </div>

                    <div class="col-xl-7 col-lg-6 icon-boxes d-flex flex-column align-items-stretch justify-content-center py-5 px-lg-5"
                        data-aos="fade-left">
                        <h3>فقط اطلب السيارة من الشركة باستخدام التطبيق وخلال دقائق يصل اليك السائق </h3>
                        <p> سنتلقى من ضمن الطلب الذي قدمته للشركة موقعك الحالي ويتم وجيه سائق اليك خلال دقائق </p>

                        <div class="icon-box" data-aos="zoom-in" data-aos-delay="100">
                            <div class="icon"><i class="bx bx-fingerprint"></i></div>
                            <h4 class="title"><a href="">الامان والسلامة </a></h4>
                            <p class="description"> يلتزم سائقينا بجميع القواعد والتعليمات المرورية التي تتناسب مع جميع
                                الاعمار و
                                البيئة المحيطة اثناء القيادة </p>
                        </div>

                        <div class="icon-box" data-aos="zoom-in" data-aos-delay="200">
                            <div class="icon"><i class="bx bx-gift"></i></div>
                            <h4 class="title"><a href=""> العروض </a></h4>
                            <p class="description">نحن نقدم عدد من العروض و الهدايا خلال كل فترة من الزمن ليكون عملاءنا
                                سعداء</p>
                        </div>

                        <div class="icon-box" data-aos="zoom-in" data-aos-delay="300">
                            <div class="icon"><i class="bx bx-atom"></i></div>
                            <h4 class="title"><a href="">أوقات الخدمة</a></h4>
                            <p class="description"> نحن متوفرون دائما ليلا و نهارا على مدار الساعة فقط اطلب سيارتك
                                وسنصل اليك في اي
                                وقت كان </p>
                        </div>

                    </div>
                </div>

            </div>
        </section><!-- End About Section -->

        <!-- ======= Features Section ======= -->
        <section id="features" class="features">
            <div class="container">

                <div class="section-title" data-aos="fade-up">
                    <h2>الميزات والخدمات </h2>
                    <p>الميزات والخدمات خلال الرحلة</p>
                </div>

                <div class="row" data-aos="fade-left">
                    <div class="col-lg-3 col-md-4 mt-4">
                        <div class="icon-box" data-aos="zoom-in" data-aos-delay="450">
                            <i class="ri-anchor-line" style="color: #b2904f;"></i>
                            <h3><a href="">بامكانك ان تطلب من السائق التوقف لدقائق</a></h3>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 mt-4">
                        <div class="icon-box" data-aos="zoom-in" data-aos-delay="500">
                            <i class="ri-disc-line" style="color: #b20969;"></i>
                            <h3><a href="">استمتع خلال الرحلة بما تريد سماعه انت</a></h3>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 mt-4">
                        <div class="icon-box" data-aos="zoom-in" data-aos-delay="550">
                            <i class="ri-base-station-line" style="color: #ff5828;"></i>
                            <h3><a href="">يوجد لدينا انترنت طوال الرحلة </a></h3>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 mt-4">
                        <div class="icon-box" data-aos="zoom-in" data-aos-delay="600">
                            <i class="ri-fingerprint-line" style="color: #29cc61;"></i>
                            <h3><a href="">السيارة مراقبة من قبل المركز بالشركة</a></h3>
                        </div>
                    </div>
                </div>

            </div>
        </section><!-- End Features Section -->

        <!-- ======= Counts Section ======= -->
        <section id="counts" class="counts">
            <div class="container">

                <div class="row" data-aos="fade-up">

                    <div class="col-lg-6 col-md-6">
                        <div class="count-box">
                            <i class="bi bi-emoji-smile"></i>
                            <span data-purecounter-start="0" data-purecounter-end="232" data-purecounter-duration="1"
                                class="purecounter"></span>
                            <p>العملاء السعداء </p>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 mt-5 mt-lg-0">
                        <div class="count-box">
                            <i class="bi bi-people"></i>
                            <span data-purecounter-start="0" data-purecounter-end="15" data-purecounter-duration="1"
                                class="purecounter"></span>
                            <p>العاملين في الشركة</p>
                        </div>
                    </div>

                </div>

            </div>
        </section><!-- End Counts Section -->

        <!-- ======= Details Section ======= -->
        <section id="details" class="details">
            <div class="container">

                <div class="row content">
                    <div class="col-md-4" data-aos="fade-right">
                        <img src="{{ asset('assets/img/details-11.png') }}" class="img-fluid" alt="">
                    </div>
                    <div class="col-md-8 pt-4" data-aos="fade-up">
                        <h3>عندما تقوم بطلب سيارة</h3>
                        <p class="fst-italic">
                            يصلنا طلبك من خلال التطبيق يتضمن مجموعة من البيانات التي تساعدنا في معالجة طلبك وارسال سائق
                            اليك
                        </p>
                        <ul>
                            <li><i class="bi bi-check"></i> اختر عنوان انطلاق واضح لسرعة وصول السائق اليك .</li>
                            <li><i class="bi bi-check"></i> اختر الوجهة التي تريد الانطلاق اليها من اجل ان يتم اريال
                                اليك السيارة
                                الاكثر راحة.</li>
                            <li><i class="bi bi-check"></i> اختر الجنس بشكل صحيح لضمان ارسال اليك سائق او سائقة.</li>
                            <li><i class="bi bi-check"></i> اختر ارسال الموقع الدقيق ليصل اليك السائق بسرعة و سهولة.
                            </li>
                        </ul>
                        <p>
                            هذه البيانات تساعدنا على تلبية طلبك بشكل سريع و امن
                        </p>
                    </div>
                </div>

                <div class="row content">
                    <div class="col-md-4 order-1 order-md-2" data-aos="fade-left">
                        <img src="{{ asset('assets/img/details-22.png') }}" class="img-fluid" alt="">
                    </div>
                    <div class="col-md-8 pt-5 order-2 order-md-1" data-aos="fade-up">
                        <h3>معالجة طلبك</h3>
                        <p class="fst-italic">
                            بناء على البيانات التي تصلنا من الزبائن يتم توجيه السائق المناسب لتلبية الطلبية
                        </p>
                        <p>
                            معالجة الطلب تكون في مقر الشركة حيث يتم التحقق من مكان تواجدك و معالجة البيانات التي قد
                            ادخلتها وارسال
                            السائق اليك
                        </p>
                        <p>
                            فترة معالجة طلبك تأخذ عدد قليل من الدقائف فقط
                        </p>
                    </div>
                </div>
            </div>
        </section><!-- End Details Section -->

        <!-- ======= Team Section ======= -->
        <section id="team" class="team">
            <div class="container">

                <div class="section-title" data-aos="fade-up">
                    <h2>السائقين</h2>
                    <p>السائقين لدينا في الشركة</p>
                </div>

                <div class="row" data-aos="fade-left">

                    <div class="col-lg-3 col-md-6"></div>
                    <div class="col-lg-3 col-md-6">
                        <div class="member" data-aos="zoom-in" data-aos-delay="100">
                            <div class="pic"><img src="{{ asset('assets/img/driver.png') }}" class="img-fluid"
                                    alt="">
                            </div>
                            <div class="member-info">
                                <h4>سائقين رجال</h4>
                                <span> يتم ارسالهم عندما يكون الطلب للرجال </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mt-5 mt-md-0">
                        <div class="member" data-aos="zoom-in" data-aos-delay="200">
                            <div class="pic"><img src="{{ asset('assets/img/driver.png') }}" class="img-fluid"
                                    alt="">
                            </div>
                            <div class="member-info">
                                <h4>سائقات نساء</h4>
                                <span>يتم ارسالهم فقط في حال كان الطلب لنساء فقط</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6"></div>

            </div>
        </section><!-- End Team Section -->

        <!-- ======= Pricing Section ======= -->
        <section id="pricing" class="pricing">
            <div class="container">

                <div class="section-title" data-aos="fade-up">
                    <h2>الاسعار</h2>
                    <p>الاسعار و الرحلات</p>
                </div>

                <div class="row" data-aos="fade-left">

                    <div class="col-lg-4 col-md-6 mt-4 mt-md-0">
                        <div class="box featured" data-aos="zoom-in" data-aos-delay="200">
                            <h3>داخل المدينة</h3>
                            <h4><sup>$</sup>1.75<span> / الذهاب</span></h4>
                            <ul>
                                <li>
                                    <p>
                                        يتم الطلب داخل مدينة اعزاز ولا يكون الى خارج المدينة
                                    </p>
                                </li>

                            </ul>
                            <div class="btn-wrap">
                                <a href="#" class="btn-buy">اطلب الان</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mt-4 mt-lg-0">
                        <div class="box" data-aos="zoom-in" data-aos-delay="300">
                            <h3>الطلب خارج مدينة اعزاز</h3>
                            <h4><sup>$</sup>0.5<span> /1 KM</span></h4>
                            <ul>
                                <li>يتم حساب المسافة من
                                    مكان الانطلاق الى </li>
                                <li>مكان الوصول ويتم بعدها </li>
                                <li>حساب مبلغ الرحلة</li>
                            </ul>
                            <div class="btn-wrap">
                                <a href="#" class="btn-buy">أطلب الان</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mt-4 mt-lg-0">
                        <div class="box" data-aos="zoom-in" data-aos-delay="400">
                            <span class="advanced"> سيارات مميزة</span>
                            <h3>طلب سيارة لمدة زمنية محددة</h3>
                            <h4><sup>$</sup>50<span> / لليوم الواحد</span></h4>
                            <ul>
                                <li>يوجد لدينا عدد من السيارات بامكانك استأجار السيارة لمدة زمنية</li>
                                <li>يتم الاتفاق عليها مع شخص مختص من الشركة</li>
                                <li>وذلك بعد تقديم الكفالة المطلوبة</li>
                            </ul>
                            <div class="btn-wrap">
                                <a href="#" class="btn-buy">اطلب الان</a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </section><!-- End Pricing Section -->

        <!-- Gallery Section -->
        <section id="gallery" class="gallery section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Gallery</h2>
                <div><span>Check Our</span> <span class="description-title">Gallery</span></div>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row g-0">

                    <div class="col-lg-3 col-md-4">
                        <div class="gallery-item">
                            <a href="{{asset('assets/img/gallery/gallery-1.jpg')}}" class="glightbox"
                                data-gallery="images-gallery">
                                <img src="{{asset('assets/img/gallery/gallery-1.jpg')}}" alt="" class="img-fluid">
                            </a>
                        </div>
                    </div><!-- End Gallery Item -->

                    <div class="col-lg-3 col-md-4">
                        <div class="gallery-item">
                            <a href="{{asset('assets/img/gallery/gallery-2.jpg')}}" class="glightbox"
                                data-gallery="images-gallery">
                                <img src="{{asset('assets/img/gallery/gallery-2.jpg')}}" alt="" class="img-fluid">
                            </a>
                        </div>
                    </div><!-- End Gallery Item -->

                    <div class="col-lg-3 col-md-4">
                        <div class="gallery-item">
                            <a href="{{asset('assets/img/gallery/gallery-3.jpg')}}" class="glightbox"
                                data-gallery="images-gallery">
                                <img src="{{asset('assets/img/gallery/gallery-3.jpg')}}" alt="" class="img-fluid">
                            </a>
                        </div>
                    </div><!-- End Gallery Item -->

                    <div class="col-lg-3 col-md-4">
                        <div class="gallery-item">
                            <a href="{{asset('assets/img/gallery/gallery-4.jpg')}}" class="glightbox"
                                data-gallery="images-gallery">
                                <img src="{{asset('assets/img/gallery/gallery-4.jpg')}}" alt="" class="img-fluid">
                            </a>
                        </div>
                    </div><!-- End Gallery Item -->

                    <div class="col-lg-3 col-md-4">
                        <div class="gallery-item">
                            <a href="{{asset('assets/img/gallery/gallery-5.jpg')}}" class="glightbox"
                                data-gallery="images-gallery">
                                <img src="{{asset('assets/img/gallery/gallery-5.jpg')}}" alt="" class="img-fluid">
                            </a>
                        </div>
                    </div><!-- End Gallery Item -->

                    <div class="col-lg-3 col-md-4">
                        <div class="gallery-item">
                            <a href="{{asset('assets/img/gallery/gallery-6.jpg')}}" class="glightbox"
                                data-gallery="images-gallery">
                                <img src="{{asset('assets/img/gallery/gallery-6.jpg')}}" alt="" class="img-fluid">
                            </a>
                        </div>
                    </div><!-- End Gallery Item -->

                    <div class="col-lg-3 col-md-4">
                        <div class="gallery-item">
                            <a href="{{asset('assets/img/gallery/gallery-7.jpg')}}" class="glightbox"
                                data-gallery="images-gallery">
                                <img src="{{asset('assets/img/gallery/gallery-7.jpg')}}" alt="" class="img-fluid">
                            </a>
                        </div>
                    </div><!-- End Gallery Item -->

                    <div class="col-lg-3 col-md-4">
                        <div class="gallery-item">
                            <a href="{{asset('assets/img/gallery/gallery-8.jpg')}}" class="glightbox"
                                data-gallery="images-gallery">
                                <img src="{{asset('assets/img/gallery/gallery-8.jpg')}}" alt="" class="img-fluid">
                            </a>
                        </div>
                    </div><!-- End Gallery Item -->

                </div>

            </div>

        </section><!-- /Gallery Section -->

        <!-- Testimonials Section -->
        <section id="testimonials" class="testimonials section dark-background">

            <img src="{{asset('assets/img/testimonials-bg.jpg')}}" class="testimonials-bg" alt="">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="swiper init-swiper">
                    <script type="application/json" class="swiper-config">
              {
                "loop": true,
                "speed": 600,
                "autoplay": {
                  "delay": 5000
                },
                "slidesPerView": "auto",
                "pagination": {
                  "el": ".swiper-pagination",
                  "type": "bullets",
                  "clickable": true
                }
              }
            </script>
                    <div class="swiper-wrapper">

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <img src="{{asset('assets/img/testimonials/testimonials-1.jpg')}}" alt="">
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <img src="{{asset('assets/img/testimonials/testimonials-1.jpg')}}" alt="">
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <img src="{{asset('assets/img/testimonials/testimonials-1.jpg')}}" alt="">
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <img src="{{asset('assets/img/testimonials/testimonials-1.jpg')}}" alt="">
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <img src="{{asset('assets/img/testimonials/testimonials-1.jpg')}}" alt="">
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <img src="{{asset('assets/img/testimonials/testimonials-1.jpg')}}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>

            </div>

        </section><!-- /Testimonials Section -->
        
        <!-- ======= F.A.Q Section ======= -->
        <section id="faq" class="faq section-bg">
            <div class="container">

                <div class="section-title" data-aos="fade-up">
                    <h2>F.A.Q</h2>
                    <p>بعض اسئلة العملاء</p>
                </div>

                <div class="faq-list">
                    <ul>
                        <li data-aos="fade-up">
                            <i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse" class="collapse"
                                data-bs-target="#faq-list-1"> هل يوجد رحلات ليلاً الى خارج مدينة اعزاز؟ <i
                                    class="bx bx-chevron-down icon-show"></i><i
                                    class="bx bx-chevron-up icon-close"></i></a>
                            <div id="faq-list-1" class="collapse show" data-bs-parent=".faq-list">
                                <p>
                                    نعم يوجد رحلات خارج المدينة الى الارياف المحيطة بها لاكن السائقين يكونون رجال فقط
                                </p>
                            </div>
                        </li>

                        <li data-aos="fade-up" data-aos-delay="100">
                            <i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse"
                                data-bs-target="#faq-list-2" class="collapsed"> هل لديكم تطبيق من اجل طلب السيارة عن
                                طريق الجوال؟ <i class="bx bx-chevron-down icon-show"></i><i
                                    class="bx bx-chevron-up icon-close"></i></a>
                            <div id="faq-list-2" class="collapse" data-bs-parent=".faq-list">
                                <p>
                                    نعم لدينا تطبيق من اجل طلب السيارات من الشركة عن طريق الجوال وهذا رابط المنصة لتحميل
                                    التطبيق
                                    https://star-taxi.net/Applatform
                                </p>
                            </div>
                        </li>

                        <li data-aos="fade-up" data-aos-delay="200">
                            <i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse"
                                data-bs-target="#faq-list-3" class="collapsed"> هل لديكم سيارات من اجل استأجارها لمدة
                                زمنية محددة؟ <i class="bx bx-chevron-down icon-show"></i><i
                                    class="bx bx-chevron-up icon-close"></i></a>
                            <div id="faq-list-3" class="collapse" data-bs-parent=".faq-list">
                                <p>
                                    نعم لدينا ثلاث انواع من السيارات المستعدة من اجل الاجار لمدة زمنية محدد يمكنك
                                    التواصل معنا من اجل
                                    الاستفسار اكثر
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
        </section><!-- End F.A.Q Section -->

        <!-- ======= Contact Section ======= -->
        <section id="contact" class="contact">
            <div class="container">

                <div class="section-title" data-aos="fade-up">
                    <h2>التواصل</h2>
                    <p>تواصل معنا </p>
                </div>

                <div class="row">

                    <div class="col-lg-4" data-aos="fade-right" data-aos-delay="100">
                        <div class="info">
                            <div class="address">
                                <i class="bi bi-geo-alt"></i>
                                <h4>موقع الشركة:</h4>
                                <p>اعزاز بالقرب من دوار الكفين </p>
                            </div>

                            <div class="email">
                                <i class="bi bi-envelope"></i>
                                <h4>Email:</h4>
                                <p>Tawsella1@gmail.com</p>
                            </div>

                            <div class="phone">
                                <i class="bi bi-phone"></i>
                                <h4>جوال :</h4>
                                <p><a href="https://wa.me/+14784044439">+1 478 4044 439</a></p>
                            </div>

                            <div class="phone">
                                <i class="bi bi-phone"></i>
                                <h4>جوال :</h4>
                                <p><a href="https://wa.me/+905365683172">+90 536 568 3172</a></p>
                            </div>
                        </div>

                    </div>


                    <div class="col-lg-4 mt-5 mt-lg-0" data-aos="fade-left" data-aos-delay="200">

                        <form action="" method="post" role="form" class="php-email-form">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <input type="text" name="name" class="form-control" id="name"
                                        placeholder="Your Name" required>
                                </div>
                                <div class="col-md-6 form-group mt-3 mt-md-0">
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="Your Email" required>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <input type="text" class="form-control" name="subject" id="subject"
                                    placeholder="Subject" required>
                            </div>
                            <div class="form-group mt-3">
                                <textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
                            </div>
                            <div class="my-3">
                                <div class="loading">Loading</div>
                                <div class="error-message"></div>
                                <div class="sent-message">Your message has been sent. Thank you!</div>
                            </div>
                            <div class="text-center"><button type="submit">Send Message</button></div>
                        </form>

                    </div>
                    <div class="col-lg-4 mt-5 mt-lg-0" data-aos="fade-left" data-aos-delay="200">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d1601.8880902546045!2d37.039787461292725!3d36.58361109307745!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMzbCsDM1JzAxLjAiTiAzN8KwMDInMTguNiJF!5e0!3m2!1sar!2str!4v1713553076279!5m2!1sar!2str"
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>

            </div>
        </section><!-- End Contact Section -->

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer">

        <div class="container">
            <div class="copyright">
                &copy; شركة <strong><span> Tawsella </span></strong>بخدمتكم
            </div>
            <div class="credits">
                مصمم الموقع <a href="https://www.facebook.com/qadoor98/">شركة Smart Code</a>
            </div>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

</body>

</html>
