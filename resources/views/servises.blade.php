@extends('layouts.app')

@section('content')
    <main id="main" class="main">
        <div class="container">
            <div class="row">
                @foreach ($movementTypes as $movementType)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $movementType->type }}</h5>
                                <p class="card-text">{{ $movementType->description }}</p>
                                <p class="card-text"><small class="text-muted">السعر: {{ $movementType->price }} ريال</small></p>
                            </div>
                            <div class="card-footer">
                                @if ($movementType->is_onKM)
                                    <span class="badge bg-primary">معتمد على كيلومترات المشوار</span>
                                @else
                                    <span class="badge bg-secondary">غير معتمد على كيلومترات المشوار</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection
