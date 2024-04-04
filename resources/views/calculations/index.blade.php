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
                        <th scope="col">معرف حركة سيارات الأجرة</th>
                        <th scope="col">الرصيد اليومي </th>
                        <th scope="col">الرصيد الكلي </th>
                        <th scope="col">الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($calculations as $index=> $calculation)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <?php
                                $total_today = $this->todayAccounts($calculation->driver_id);
                                $total_previous = $this->totalAccounts($calculation->driver_id);
                            ?>
                            <td>{{ $calculation->driver_id }}</td>
                            <td>{{ $calculation->taxi_movement_id }}</td>
                            <td>
                                {{$total_today}}
                            </td>
                            <td>
                                {{$total_previous}}
                            </td>
                            <td>
                                <a href="{{ route('calculations.show', $calculation->id) }}" class="btn btn-primary">عرض</a>
                                <a href="{{ route('calculations.edit', $calculation->id) }}"
                                    class="btn btn-success">تعديل</a>
                                <form action="{{ route('calculations.destroy', $calculation->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete?')">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
@endsection
