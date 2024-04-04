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
            <h1>Calculations</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">السائق </th>
                        <th scope="col">رقم السيارة</th>
                        <th scope="col">الرصيد اليومي </th>
                        <th scope="col">الرصيد الكلي </th>
                        <th scope="col">الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($calculations as $index=> $calculation)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>

                            <td>{{ $calculation->name }}</td>
                            <td>{{ $calculation->plate_number }}</td>
                            <td>
                                {{ $calculation->today_account }}
                            </td>
                            <td>
                                {{ $calculation->all_account }}
                            </td>
                            <td>
                                <x-buttons 
                                        :delete-route="route('drivers.destroy', ['id' => $driver->driver_id])" 
                                        :edit-route="route('drivers.edit', ['id' => $driver->driver_id])" 
                                        :show-route="route('calculations.show', $calculation->driver_id)" 
                                        :showDeleteButton="false" 
                                        :showEditButton="false" 
                                        :showDetailsButton="true" 
                                    />
                               
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
@endsection
