@extends('layouts.app')

@section('content')
<main class="main" id="main">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">إضافة نوع حركة تاكسي جديد</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('movement-types.store') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="type" class="col-md-4 col-form-label text-md-right">النوع</label>

                                <div class="col-md-6">
                                    <input id="type" type="text" class="form-control @error('type') is-invalid @enderror" name="type" value="{{ old('type') }}" required>
                                    @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                    @error('type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="price" class="col-md-4 col-form-label text-md-right">السعر</label>

                                <div class="col-md-6">
                                    <input id="price" type="number" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" required>

                                    @error('price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label text-md-right">الوصف</label>

                                <div class="col-md-6">
                                    <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description') }}</textarea>

                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="is_onKM" class="col-md-4 col-form-label text-md-right">هل السعر على الكيلومتر؟</label>

                                <div class="col-md-6">
                                    <select id="is_onKM" class="form-control @error('is_onKM') is-invalid @enderror" name="is_onKM" required>
                                        <option value="1" {{ old('is_onKM') == '1' ? 'selected' : '' }}>نعم</option>
                                        <option value="0" {{ old('is_onKM') == '0' ? 'selected' : '' }}>لا</option>
                                    </select>

                                    @error('is_onKM')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="payment" class="col-md-4 col-form-label text-md-right">عملة الدفع</label>

                                <div class="col-md-6">
                                    <select id="is_onKM" class="form-control @error('payment') is-invalid @enderror" name="payment" required>
                                        <option value="$" {{ old('$') ? 'selected' : '' }}>دولار $</option>
                                        <option value="TL" {{ old('TL') ? 'selected' : '' }}>ليرة تركي TL</option>
                                    </select>

                                    @error('payment')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        إضافة
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
