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
            <!-- Main Content -->
            <h1 class="my-4">عروض اليوم</h1>
                <a class="btn btn-success" href="{{ route('offers.create') }}"><i class="bi bi-plus"></i> إضافة
                    عرض
                    جديد</a>

            <div class="row">
                @foreach ($offers as $index => $offer)
                    <div class="col-12">
                        <div class="card mb-3">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="{{ asset('images/' . $offer->image) }}" class="w-100 rounded-start"
                                        style="width: 200px;height: 200px;padding: 10px" alt="{{ $offer->type }}">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $index + 1 }}
                                            <hr>{{ $offer->title }}
                                        </h5>
                                        <p class="card-text">{{ $offer->value_of_discount }}%</p>
                                        <p class="card-text"><small class="text-body-secondary">تاريخ انتهاء العرض:
                                                {{ $offer->valide_date }}</small></p>

                                        <!-- زر تعديل العرض -->
                                        <div class="row">
                                            <div class="col-12">
                                                <a class="btn btn-primary" href="{{ route('offers.edit', $offer->id) }}"><i
                                                        class="bi bi-pencil"></i> تعديل</a>
                                                <!-- زر حذف العرض -->
                                                <form action="{{ route('offers.destroy', $offer->id) }}" method="POST"
                                                    style="display: inline; width: 100%;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger"
                                                        onclick="return confirm('هل أنت متأكد من حذف العرض؟')">
                                                        <i class="bi bi-trash"></i> حذف
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </main>
@endsection
