<!-- resources/views/calculations/index.blade.php -->
@extends('layouts.app')

@section('content')
    <main class="main" id="main">
        <div class="container">
            <h1>Calculations</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">السائق </th>
                        <th scope="col">معرف حركة سيارات الأجرة</th>
                        <th scope="col">حساب </th>
                        <th scope="col">الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($calculations as $calculation)
                        <tr>
                            <th scope="row">{{ $calculation->id }}</th>
                            <td>{{ $calculation->driver_id }}</td>
                            <td>{{ $calculation->taxi_movement_id }}</td>
                            <td>{{ $calculation->calculate }}</td>
                            <td>
                                <a href="{{ route('calculations.show', $calculation->id) }}"
                                    class="btn btn-primary">عرض</a>
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
