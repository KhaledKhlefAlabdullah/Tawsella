@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Completed Requests</div>

                    <div class="card-body">
                        @if($completedRequests->isEmpty())
                            <p>No completed requests found.</p>
                        @else
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Request ID</th>
                                    <th>Driver</th>
                                    <th>Customer</th>
                                    <th>Start Address</th>
                                    <th>End Address</th>
                                    <th>Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($completedRequests as $request)
                                    <tr>
                                        <td>{{ $request->id }}</td>
                                        <td>{{ $request->driver->name }}</td>
                                        <td>{{ $request->customer->name }}</td>
                                        <td>{{ $request->my_address }}</td>
                                        <td>{{ $request->destnation_address }}</td>
                                        <td>{{ $request->total_price_for_this_movement }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
