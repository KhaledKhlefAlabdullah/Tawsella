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
                    <a href="{{ route('service.create') }}" class="btn btn-primary mb-3">إضافة خدمة جديدة</a>
                </div>
            </div>
            <div class="row">
                @foreach ($movementTypes as $movementType)
                    <div class="col-md-6 mb-4 ">
                        <div class="card h-100">
                            <div class="card-body">
                                <h3 class="card-title">{{ $movementType->type }}</h3>
                                <p class="card-text">{{ $movementType->description }}</p>
                                <p class="card-text"><small class="text-muted">السعر: {{ $movementType->price.' '.$movementType->payment  }}</small></p>
                            </div>
                            <div class="card-footer">
                                @if ($movementType->is_onKM)
                                    <span class="badge bg-primary">معتمد على كيلومترات</span>
                                @else
                                    <span class="badge bg-secondary">غير معتمد على كيلومترات</span>
                                @endif
                                <x-buttons 
                                :delete-route="route('service.destroy', $movementType->id) " 
                                :edit-route="route('service.edit', $movementType->id)" 
                                :showDeleteButton="false"
                                :showEditButton="true"
                                :showDetailsButton="false" />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row">
                @foreach ($movements as $movement)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h3 class="card-title">{{ $movement->type }}</h3>
                                <p class="card-text">{{ $movement->description }}</p>
                                <p class="card-text"><small class="text-muted">السعر: {{ $movement->price.' '.$movement->payment}}</small></p>
                            </div>
                            <div class="card-footer">
                                <span class="badge bg-primary"> سعر ثابت </span>
                                <x-buttons 
                                :delete-route="route('service.destroy', $movement->id)" 
                                :edit-route="route('service.edit', $movement->id)" 
                                :showDeleteButton="true"
                                :showEditButton="true"
                                :showDetailsButton="false" />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection
