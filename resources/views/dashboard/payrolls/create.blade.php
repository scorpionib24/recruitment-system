@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5>إنشاء مسير رواتب جديد</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.payrolls.store') }}" method="POST">
                @csrf

                <div class="alert alert-info">
                    <p class="mb-0">سيقوم النظام بإنشاء مسير رواتب لكل الموظفين النشطين للشهر والسنة المحددين. سيتم حساب الراتب بناءً على عدد أيام الحضور المسجلة في نظام الحضور والانصراف.</p>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="month" class="form-label">اختر الشهر</label>
                        <select class="form-select" id="month" name="month" required>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ old('month', date('m')) == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="year" class="form-label">اختر السنة</label>
                        <select class="form-select" id="year" name="year" required>
                            @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                                <option value="{{ $y }}" {{ old('year', date('Y')) == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">إنشاء ومعالجة المسير</button>
                <a href="{{ route('dashboard.payrolls.index') }}" class="btn btn-secondary">إلغاء</a>
            </form>
        </div>
    </div>
</div>
@endsection
