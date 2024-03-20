@extends('layouts.app')

@section('content')
    <main class="main" id="main">
        <center>
            <h1>خطأ في قاعدة البيانات</h1>
            <p style="color: red">{{ $message }}</p>
            <a href="{{url('/dashboard')}}">Back</a>
        </center>
    </main>
@endsection
