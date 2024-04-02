<!-- resources/views/calculations/index.blade.php -->
@extends('layouts.app')

@section('content')
    <main class="main" id="main">
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
        <div class="container">
            <h1 class="my-4">عرض الحساب</h1>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">بيانات الحساب</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Driver ID: {{ $calculations->driver_id }}</li>
                        <li class="list-group-item">Taxi Movement ID: {{ $calculations->taxi_movement_id }}</li>
                        <li class="list-group-item">Calculate: {{ $calculations->calculate }}</li>
                        <li class="list-group-item">Created At: {{ $calculations->created_at }}</li>
                        <li class="list-group-item">Updated At: {{ $calculations->updated_at }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
@endsection
