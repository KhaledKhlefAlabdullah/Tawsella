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
            <h1 class="my-4">إضافة عرض جديد</h1>
            <div class="card mb-3" style="max-width: 540px;">
                <div class="row g-0">
                    <div class="col-md-8">
                        <div class="card-body">
                            <form method="post" action="{{ route('offers.store') }}">
                                @csrf

                                <div class="form-group">
                                    <label for="movement_type_id">نوع الحركة</label>
                                    <select name="movement_type_id" id="movement_type_id" class="form-control">
                                        @foreach($movementTypes as $movementType)
                                            <option value="{{ $movementType->id }}">{{ $movementType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="offer">العرض</label>
                                    <textarea name="offer" id="offer" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="value_of_discount">قيمة الخصم</label>
                                    <input type="text" name="value_of_discount" id="value_of_discount" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="valide_date">تاريخ الصلاحية</label>
                                    <input type="date" name="valide_date" id="valide_date" class="form-control">
                                </div>

                                <button type="submit" class="btn btn-primary">حفظ</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
