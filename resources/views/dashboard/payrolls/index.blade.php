@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إدارة مسيرات الرواتب</h5>
            {{-- سنضيف زر إنشاء مسير جديد هنا لاحقاً --}}
            <a href="{{ route('dashboard.payrolls.create') }}" class="btn btn-success">إنشاء مسير رواتب جديد</a>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>الشهر / السنة</th>
                        <th>الحالة</th>
                        <th>تاريخ المعالجة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payrolls as $payroll)
                        <tr>
                            <td>{{ \Carbon\Carbon::create()->month($payroll->month)->format('F') }} {{ $payroll->year }}</td>
                            <td>
                                @if($payroll->status == 'draft')
                                    <span class="badge bg-secondary">مسودة</span>
                                @elseif($payroll->status == 'processed')
                                    <span class="badge bg-primary">تمت المعالجة</span>
                                @else
                                    <span class="badge bg-success">مدفوع</span>
                                @endif
                            </td>
                            <td>{{ $payroll->processed_at ? \Carbon\Carbon::parse($payroll->processed_at)->format('Y-m-d H:i') : 'N/A' }}</td>
                            <td>
                                <a href="{{ route('dashboard.payrolls.show', $payroll->id) }}" class="btn btn-info btn-sm">
                                    عرض التفاصيل
                                </a>
                                {{-- سنضيف أزرار أخرى هنا لاحقاً --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">لا يوجد أي مسيرات رواتب لعرضها.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $payrolls->links() }}
            </div>
        </div>
    </div>
</div>
</div>
@endsection
