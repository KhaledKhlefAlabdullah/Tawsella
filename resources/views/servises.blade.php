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
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <a href="{{ route('taxi_movement_types.create') }}" class="btn btn-primary mb-3">إضافة خدمة جديدة</a>
                </div>
            </div>
            <div class="row">
                @foreach ($movementTypes as $movementType)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h3 class="card-title">{{ $movementType->type }}</h3>
                                <p class="card-text">{{ $movementType->description }}</p>
                                <p class="card-text"><small class="text-muted">السعر: {{ $movementType->price }} LT</small></p>
                            </div>
                            <p>{{$movementType->id}}</p>
                            <div class="card-footer">
                                @if ($movementType->is_onKM)
                                    <span class="badge bg-primary">معتمد على كيلومترات</span>
                                @else
                                    <span class="badge bg-secondary">غير معتمد على كيلومترات</span>
                                @endif
                                <a href="{{ route('taxi_movement_types.edit', '01932a72-fcf9-4c70-918c-99b6dbc4ec99') }}" class="btn btn-success btn-sm mx-1">تعديل</a>
                                <form method="POST" action="{{ route('taxi_movement_types.destroy', $movementType->id) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من حذف هذا النوع؟')">حذف</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection
