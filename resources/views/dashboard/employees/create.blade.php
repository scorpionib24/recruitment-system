@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5>إضافة موظف جديد</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.employees.store') }}" method="POST">
                @csrf

                {{-- عرض أخطاء التحقق من الصحة --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    {{-- معلومات أساسية --}}
                    <div class="col-md-4 mb-3">
                        <label for="first_name" class="form-label">الاسم الأول</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="last_name" class="form-label">الاسم الأخير</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>

                    {{-- معلومات وظيفية --}}
                    <div class="col-md-4 mb-3">
                        <label for="employee_number" class="form-label">الرقم الوظيفي</label>
                        <input type="text" class="form-control" id="employee_number" name="employee_number" value="{{ old('employee_number') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="position" class="form-label">المسمى الوظيفي</label>
                        <input type="text" class="form-control" id="position" name="position" value="{{ old('position') }}" required>
                    </div>

                     <div class="col-md-3 mb-3">
                        <label for="branch_id" class="form-label">الفرع</label>
                        <select class="form-select" id="branch_id" name="branch_id" required>
                            <option value="" disabled selected>اختر الفرع...</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                     <!-- <div class="col-md-3 mb-3">
                        <label for="department_id" class="form-label">القسم</label>
                        <select class="form-select" id="department_id" name="department_id" required>
                            <option value="" disabled selected>اختر القسم...</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div> -->

                    {{-- تواريخ ومعلومات أخرى --}}
                    <div class="col-md-3 mb-3">
                        <label for="hire_date" class="form-label">تاريخ التعيين</label>
                        <input type="date" class="form-control" id="hire_date" name="hire_date" value="{{ old('hire_date') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="salary" class="form-label">الراتب (اختياري)</label>
                        <input type="number" step="0.01" class="form-control" id="salary" name="salary" value="{{ old('salary') }}">
                    </div>
                     <div class="col-md-4 mb-3">
                        <label for="status" class="form-label">الحالة</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            <option value="on_leave" {{ old('status') == 'on_leave' ? 'selected' : '' }}>في إجازة</option>
                        </select>
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">حفظ الموظف</button>
                <a href="{{ route('dashboard.employees.index') }}" class="btn btn-secondary">إلغاء</a>
            </form>
        </div>
    </div>
</div>
@endsection
