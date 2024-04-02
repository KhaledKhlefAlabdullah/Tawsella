@extends('layouts.app')

@section('content')
    <main id="main" class="main">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="pagetitle">
            <h1>Contact</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/public/dashboard">Home</a></li>
                    <li class="breadcrumb-item">Pages</li>
                    <li class="breadcrumb-item active">Contact</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section contact">

            <div class="row gy-4">

                <div class="col-xl-6">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="info-box card">
                                <i class="bi bi-geo-alt"></i>
                                <h3>Address</h3>
                                <p>A108 Adam Street,<br>New York, NY 535022</p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="info-box card">
                                <i class="bi bi-telephone"></i>
                                <h3>Call Us</h3>
                                <p><a href="https://wa.me/+905072415450">+90 507 241 54 50</a><br><a
                                        href="https://wa.me/+963992819597">+963 992 819 597</a></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="info-box card">
                                <i class="bi bi-envelope"></i>
                                <h3>Email Us</h3>
                                <p>info@example.com<br>contact@example.com</p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="info-box card">
                                <i class="bi bi-clock"></i>
                                <h3>Open Hours</h3>
                                <p>Monday - Friday<br>9:00AM - 05:00PM</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-xl-6">
                    <div class="card p-4">
                        <form action="forms/contact.php" method="post" class="php-email-form">
                            <div class="row gy-4">

                                <div class="col-md-6">
                                    <input type="text" name="name" class="form-control" placeholder="Your Name"
                                        required>
                                </div>

                                <div class="col-md-6 ">
                                    <input type="email" class="form-control" name="email" placeholder="Your Email"
                                        required>
                                </div>

                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="subject" placeholder="Subject"
                                        required>
                                </div>

                                <div class="col-md-12">
                                    <textarea class="form-control" name="message" rows="6" placeholder="Message" required></textarea>
                                </div>

                                <div class="col-md-12 text-center">
                                    <div class="loading">Loading</div>
                                    <div class="error-message"></div>
                                    <div class="sent-message">Your message has been sent. Thank you!</div>

                                    <button type="submit">Send Message</button>
                                </div>

                            </div>
                        </form>
                    </div>

                </div>

            </div>

        </section>

    </main><!-- End #main -->
@endsection
