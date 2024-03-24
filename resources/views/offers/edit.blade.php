@extends('layouts.app')

@section('content')
    <main class="main" id="main">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Main Content -->
                    <h1 class="my-4">تعديل العرض</h1>

                    <div class="card mb-3" style="max-width: 540px;">
                        <div class="row g-0">
                            <div class="col-md-8">
                                <div class="card-body">
                                    <form method="post" action="{{ route('offers.update', $offer->id) }}">
                                        @csrf
                                        @method('put')

                                        <div class="form-group">
                                            <label for="movement_type_id">نوع الحركة</label>
                                            <select name="movement_type_id" id="movement_type_id" class="form-control">
                                                @foreach($movementTypes as $movementType)
                                                    <option value="{{ $movementType->id }}" {{ $offer->movement_type_id == $movementType->id ? 'selected' : '' }}>
                                                        {{ $movementType->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="admin_id">المشرف</label>
                                            <select name="admin_id" id="admin_id" class="form-control">
                                                @foreach($admins as $admin)
                                                    <option value="{{ $admin->id }}" {{ $offer->admin_id == $admin->id ? 'selected' : '' }}>
                                                        {{ $admin->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="offer">العرض</label>
                                            <textarea name="offer" id="offer" class="form-control">{{ $offer->offer }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="value_of_discount">قيمة الخصم</label>
                                            <input type="text" name="value_of_discount" id="value_of_discount" class="form-control" value="{{ $offer->value_of_discount }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="valide_date">تاريخ الصلاحية</label>
                                            <input type="date" name="valide_date" id="valide_date" class="form-control" value="{{ $offer->valide_date }}">
                                        </div>

                                        <button type="submit" class="btn btn-primary">تحديث العرض</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
