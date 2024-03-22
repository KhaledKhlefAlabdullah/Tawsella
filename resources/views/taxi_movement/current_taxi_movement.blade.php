<!-- current_taxi_movement.blade.php -->

@extends('layouts.app')

@section('content')
    <main class="main" id="main">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Current Taxi Movement</h1>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>My Address</th>
                                <th>Destination Address</th>
                                <th>Gender</th>
                                <th>Start Latitude</th>
                                <th>Start Longitude</th>
                                <th>Driver Email</th>
                                <th>Customer Email</th>
                                <th>Driver Name</th>
                                <th>Driver Phone</th>
                                <th>Customer Name</th>
                                <th>Customer Phone</th>
                                <th>Car Name</th>
                                <th>Lamp Number</th>
                                <th>Plate Number</th>
                                <th>Movement Type</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($taxiMovement as $movement)
                                <tr>
                                    <td>{{ $movement->my_address }}</td>
                                    <td>{{ $movement->destnation_address }}</td>
                                    <td>{{ $movement->gender }}</td>
                                    <td>{{ $movement->start_latitude }}</td>
                                    <td>{{ $movement->start_longitude }}</td>
                                    <td>{{ $movement->driver_email }}</td>
                                    <td>{{ $movement->customer_email }}</td>
                                    <td>{{ $movement->driver_name }}</td>
                                    <td>{{ $movement->driver_phone }}</td>
                                    <td>{{ $movement->customer_name }}</td>
                                    <td>{{ $movement->customer_phone }}</td>
                                    <td>{{ $movement->car_car_name }}</td>
                                    <td>{{ $movement->car_lamp_number }}</td>
                                    <td>{{ $movement->car_plate_number }}</td>
                                    <td>{{ $movement->type }}</td>
                                    <td>{{ $movement->price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection
