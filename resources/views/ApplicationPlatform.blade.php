@extends('layouts.app')

@section('content')
    <main class="main" id="main">
        <div class="container">
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <h1 class="text-center mb-4">تنزيل التطبيق</h1>
                        <div class="app-info">
                            <!-- رابط الصورة لتطبيقك -->
                            <img src="https://arabitechnomedia.com/wp-content/uploads/2015/06/2015-06-24_144521.jpg"
                                alt="صورة التطبيق" class="app-image mb-3">
                            <div>Uptodown App Store 5.86</div>
                            <div>Uptodown.com</div>
                        </div>
                        <div class="store-info">
                            <p>قم بتنزيل التطبيق من متجر Uptodown للاستمتاع بالمزيد من الميزات والخدمات!</p>
                            <button type="button" class="btn btn-primary download-btn">
                                تنزيل التطبيق <i class="bi bi-download"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <h1 class="text-center mb-4">عرض التطبيق</h1>
                        <!-- عرض الصور -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <img src="https://arabitechnomedia.com/wp-content/uploads/2015/06/2015-06-24_144521.jpg" style="width: 100%;height: 100%;"
                                    alt="صورة التطبيق" class="app-imageph">
                            </div>
                            <div class="col-md-4 mb-3">
                                <img src="https://arabitechnomedia.com/wp-content/uploads/2015/06/2015-06-24_144521.jpg" style="width: 100%;height: 100%;"
                                    alt="صورة التطبيق" class="app-imageph">
                            </div>
                            <div class="col-md-4 mb-3">
                                <img src="https://arabitechnomedia.com/wp-content/uploads/2015/06/2015-06-24_144521.jpg" style="width: 100%;height: 100%;"
                                    alt="صورة التطبيق" class="app-imageph">
                            </div>
                            <div class="col-md-4 mb-3">
                                <img src="https://arabitechnomedia.com/wp-content/uploads/2015/06/2015-06-24_144521.jpg" style="width: 100%;height: 100%;"
                                    alt="صورة التطبيق" class="app-imageph">
                            </div>
                            <div class="col-md-4 mb-3">
                                <img src="https://arabitechnomedia.com/wp-content/uploads/2015/06/2015-06-24_144521.jpg" style="width: 100%;height: 100%;"
                                    alt="صورة التطبيق" class="app-imageph">
                            </div>
                        </div>
                        <!-- وصف التطبيق -->
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
        </div>

    </main>
    <style>
        body {
          background-color: #f8f9fa;
        }
        .app-info {
          text-align: center;
          margin-top: 20px;
        }
        .app-image {
          max-width: 30%;
          height: auto;
          border-radius: 10px;
          box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
          transition: transform 0.3s ease-in-out;
        }
        .app-image:hover {
          transform: scale(1.05);
        }
        .store-info {
          margin-top: 20px;
          text-align: center;
        }
        .download-btn {
          margin-top: 10px;
        }


        .app-info {
          text-align: center;
          margin-top: 20px;
        }
        .app-imageph {
      width: 100%;
      max-width: 100%;
      height: auto;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease-in-out;
      padding-bottom: 75%; /* يمثل نسبة 4:3 */
    }
        .app-image:hover {
          transform: scale(1.05);
        }
        .app-description {
          margin-top: 20px;
        }
      </style>
@endsection
