@extends('layouts.app')

@section('content')
    <main class="main" id="main">
        <div class="container">
            <div class="row justify-content-center mt-5">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="text-center mb-4">تنزيل تطبيق السائق</h1>
                            <div class="app-info text-center">
                                <img src="https://arabitechnomedia.com/wp-content/uploads/2015/06/2015-06-24_144521.jpg"
                                    alt="صورة التطبيق" class="app-image mb-3 img-fluid rounded">
                                <div>Uptodown App Store 5.86</div>
                                <div>Uptodown.com</div>
                            </div>
                            <div class="store-info text-center mt-4">
                                <p>قم بتنزيل التطبيق من متجر Uptodown للاستمتاع بالمزيد من الميزات والخدمات!</p>
                                <button type="button" class="btn btn-primary download-btn">
                                    تنزيل التطبيق <i class="bi bi-download"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="text-center mb-4">تنزيل التطبيق</h1>
                            <div class="app-info text-center">
                                <img src="https://arabitechnomedia.com/wp-content/uploads/2015/06/2015-06-24_144521.jpg"
                                    alt="صورة التطبيق" class="app-image mb-3 img-fluid rounded">
                                <div>Uptodown App Store 5.86</div>
                                <div>Uptodown.com</div>
                            </div>
                            <div class="store-info text-center mt-4">
                                <p>قم بتنزيل التطبيق من متجر Uptodown للاستمتاع بالمزيد من الميزات والخدمات!</p>
                                <a href="App-APK/app-release.apk" download="app-release.apk"
                                    class="btn btn-primary download-btn" data-bs-toggle="modal"
                                    data-bs-target="#downloadModal">
                                    تنزيل التطبيق <i class="bi bi-download"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Modal -->
                <!-- Modal -->
                <div class="modal fade" id="downloadModal" tabindex="-1" aria-labelledby="downloadModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="downloadModalLabel">تنزيل التطبيق</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                يتم تحميل التطبيق. الرجاء الانتظار...
                            </div>
                            <div class="modal-footer">
                                <a href="App-APK/app-release.apk" download="app-release.apk" class="btn btn-primary">
                                    موافق
                                </a>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
            <div class="row justify-content-center mt-5">
                <div class="col-md-8">
                    <h1 class="text-center mb-4">عرض التطبيق</h1>
                    <div class="row">
                        <div class="col-md-4 mb-3 col-4">
                            <img src="https://arabitechnomedia.com/wp-content/uploads/2015/06/2015-06-24_144521.jpg"
                                alt="صورة التطبيق" class="app-imageph img-fluid rounded">
                        </div>
                        <div class="col-md-4 mb-3 col-4">
                            <img src="https://arabitechnomedia.com/wp-content/uploads/2015/06/2015-06-24_144521.jpg"
                                alt="صورة التطبيق" class="app-imageph img-fluid rounded">
                        </div>
                        <div class="col-md-4 mb-3 col-4">
                            <img src="https://arabitechnomedia.com/wp-content/uploads/2015/06/2015-06-24_144521.jpg"
                                alt="صورة التطبيق" class="app-imageph img-fluid rounded">
                        </div>
                        <div class="col-md-4 mb-3 col-4">
                            <img src="https://arabitechnomedia.com/wp-content/uploads/2015/06/2015-06-24_144521.jpg"
                                alt="صورة التطبيق" class="app-imageph img-fluid rounded">
                        </div>
                        <div class="col-md-4 mb-3 col-4">
                            <img src="https://arabitechnomedia.com/wp-content/uploads/2015/06/2015-06-24_144521.jpg"
                                alt="صورة التطبيق" class="app-imageph img-fluid rounded">
                        </div>
                    </div>
                    <div class="app-description mt-4">
                        <h3 class="text-center">وصف التطبيق</h3>
                        <p>هذا التطبيق يتيح للمستخدمين فعل شيء رائع. ويوفر مجموعة من الميزات المذهلة التي تسهل حياتهم
                            اليومية.</p>
                        <p>يتميز التطبيق بواجهة مستخدم سهلة الاستخدام وتجربة مستخدم ممتعة. بالإضافة إلى ذلك، يتيح
                            التطبيق تخصيص كبير لتلبية احتياجات كل مستخدم.</p>
                        <p>باختصار، هذا التطبيق هو ما تحتاجه لتحسين حياتك الرقمية!</p>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection
