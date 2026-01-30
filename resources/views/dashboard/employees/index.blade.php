@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إدارة الموظفين</h5>
            <a href="{{ route('dashboard.employees.create') }}" class="btn btn-success">إضافة موظف جديد</a>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>الرقم الوظيفي</th>
                        <th>الاسم الكامل</th>
                        <th>البريد الإلكتروني</th>
                        <th>الفرع</th>
                        <th>المسمى الوظيفي</th>
                        <th>تاريخ التعيين</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr>
                            <td>{{ $employee->employee_number }}</td>
                            <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->branch->name ?? 'N/A' }}</td>
                            <td>{{ $employee->position }}</td>
                            <td>{{ $employee->hire_date }}</td>
                            <td>
                                @if($employee->status == 'active')
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-secondary">{{ $employee->status }}</span>
                                @endif
                            </td>
                           <td>
                            <form action="{{ route('dashboard.employees.destroy', $employee->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا الموظف؟');">
                                <a href="{{ route('dashboard.employees.edit', $employee->id) }}" class="btn btn-primary btn-sm">تعديل</a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                            </form>
                        </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">لا يوجد أي موظفين لعرضهم.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- لعرض روابط التنقل بين الصفحات --}}
            <div class="mt-3">
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
