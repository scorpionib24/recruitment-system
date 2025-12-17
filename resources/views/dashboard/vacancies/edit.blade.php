@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">تعديل الوظيفة: {{ $vacancy->title }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('dashboard.vacancies.update', $vacancy->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- المسمى الوظيفي --}}
                        <div class="mb-3">
                            <label for="title" class="form-label">المسمى الوظيفي</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $vacancy->title) }}" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- الفرع (قائمة منسدلة) --}}
                        <div class="mb-3">
                            <label for="branch_id" class="form-label">الفرع</label>
                            <select class="form-select @error('branch_id') is-invalid @enderror" id="branch_id" name="branch_id" required>
                                <option value="" disabled>-- اختر الفرع --</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id', $vacancy->branch_id) == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('branch_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- الوصف --}}
                        <div class="mb-3">
                            <label for="description" class="form-label">الوصف الوظيفي</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $vacancy->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- المتطلبات --}}
                        <div class="mb-3">
                            <label for="requirements" class="form-label">المتطلبات</label>
                            <textarea class="form-control @error('requirements') is-invalid @enderror" id="requirements" name="requirements" rows="4" required>{{ old('requirements', $vacancy->requirements) }}</textarea>
                            @error('requirements')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- آخر موعد للتقديم --}}
                        <div class="mb-3">
                            <label for="deadline" class="form-label">آخر موعد للتقديم (اختياري)</label>
                            <input type="date" class="form-control @error('deadline') is-invalid @enderror" id="deadline" name="deadline" value="{{ old('deadline', $vacancy->deadline->format('Y-m-d')) }}">
                            @error('deadline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary">تحديث</button>
                        <a href="{{ route('dashboard.vacancies.index') }}" class="btn btn-secondary">إلغاء</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
