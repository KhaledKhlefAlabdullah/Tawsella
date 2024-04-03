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
            <a href="{{route('taxi_movement_types.create')}}">اضافة خدمة</a>
            <div class="row">
                @foreach ($movementTypes as $movementType)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h3 class="card-title">{{ $movementType->type }}</h3>
                                <p class="card-text">{{ $movementType->description }}</p>
                                <p class="card-text"><small class="text-muted">السعر: {{ $movementType->price }} LT</small>
                                </p>
                            </div>
                            <div class="card-footer">
                                @if ($movementType->is_onKM)
                                    <span class="badge bg-primary">معتمد على كيلومترات </span>
                                @else
                                    <span class="badge bg-secondary">غير معتمد على كيلومترات</span>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('taxi_movement_types.destroy', $movementType->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من حذف هذا النوع؟')">حذف</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection
