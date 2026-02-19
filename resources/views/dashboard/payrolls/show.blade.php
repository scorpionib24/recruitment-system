@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                تفاصيل مسير رواتب شهر: 
                <strong>{{ \Carbon\Carbon::create()->month($payroll->month)->format('F') }} {{ $payroll->year }}</strong>
            </h5>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- يمكننا إضافة بطاقات إحصائية هنا لاحقاً --}}

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>الرقم الوظيفي</th>
                            <th>اسم الموظف</th>
                            <th>الراتب الأساسي</th>
                            <th>أيام العمل</th>
                            <th>الراتب الإجمالي</th>
                            <th>الخصومات</th>
                            <th>الحوافز</th>
                            <th>صافي الراتب</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payroll->details as $detail)
                            <tr>
                                <td>{{ $detail->employee->employee_number }}</td>
                                <td>{{ $detail->employee->first_name }} {{ $detail->employee->last_name }}</td>
                                <td>{{ number_format($detail->base_salary, 2) }}</td>
                                <td>{{ $detail->days_worked }}</td>
                                <td>{{ number_format($detail->gross_salary, 2) }}</td>
                                <td class="text-danger">{{ number_format($detail->deductions, 2) }}</td>
                                <td class="text-success">{{ number_format($detail->bonuses, 2) }}</td>
                                <td class="fw-bold">{{ number_format($detail->net_salary, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">لا توجد تفاصيل لعرضها في هذا المسير.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="7" class="text-end">الإجمالي النهائي:</th>
                            <th class="fw-bold">{{ number_format($payroll->details->sum('net_salary'), 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-4">
                <a href="{{ route('dashboard.payrolls.index') }}" class="btn btn-secondary">العودة إلى قائمة المسيرات</a>
                {{-- يمكننا إضافة زر "طباعة" أو "تصدير Excel" هنا لاحقاً --}}
            </div>
        </div>
    </div>
</div>
@endsection
