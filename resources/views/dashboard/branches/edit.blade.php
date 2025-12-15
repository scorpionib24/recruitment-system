@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">تعديل الفرع: {{ $branch->name }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('dashboard.branches.update', $branch->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- ===== حقل اسم الفرع ===== --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">اسم الفرع</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $branch->name) }}" required>
                            
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- ===== حقل المدينة ===== --}}
                        <div class="mb-3">
                            <label for="city" class="form-label">المدينة</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $branch->city) }}" required>
                            
                            @error('city')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">تحديث</button>
                        <a href="{{ route('dashboard.branches.index') }}" class="btn btn-secondary">إلغاء</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
