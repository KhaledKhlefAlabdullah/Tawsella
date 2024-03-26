@extends('layouts.app')

@section('content')
    <main class="main" id="main">
        <div class="container">
            <h1 class="my-4">تعديل الحساب</h1>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('calculations.update', $calculations->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="driver_id">Driver ID</label>
                            <input type="text" class="form-control" id="driver_id" name="driver_id"
                                value="{{ $calculations->driver_id }}">
                        </div>
                        <div class="form-group">
                            <label for="taxi_movement_id">Taxi Movement ID</label>
                            <input type="text" class="form-control" id="taxi_movement_id" name="taxi_movement_id"
                                value="{{ $calculations->taxi_movement_id }}">
                        </div>
                        <div class="form-group">
                            <label for="calculate">Calculate</label>
                            <input type="text" class="form-control" id="calculate" name="calculate"
                                value="{{ $calculations->calculate }}">
                        </div>
                        <button type="submit" class="btn btn-primary">تحديث الحساب</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
