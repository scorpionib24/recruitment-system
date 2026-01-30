@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إدارة الأقسام</h5>
            <a href="{{ route('dashboard.departments.create') }}" class="btn btn-success">إضافة قسم جديد</a>
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
                        <th>#</th>
                        <th>اسم القسم</th>
                        <th>تاريخ الإنشاء</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($departments as $department)
                        <tr>
                            <td>{{ $department->id }}</td>
                            <td>{{ $department->name }}</td>
                            <td>{{ $department->created_at->format('Y-m-d') }}</td>
                            <td>
                                <form action="{{ route('dashboard.departments.destroy', $department->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟');">
                                    <a href="{{ route('dashboard.departments.edit', $department->id) }}" class="btn btn-primary btn-sm">تعديل</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">لا يوجد أي أقسام لعرضها.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $departments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
