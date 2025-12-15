{{-- resources/views/dashboard/branches/index.blade.php --}}

@extends('layouts.app') {{-- استخدام القالب الرئيسي الذي جاء مع laravel/ui --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    إدارة الفروع
                   <a href="{{ route('dashboard.branches.create') }}" class="btn btn-success btn-sm float-end">إضافة فرع جديد</a>
                </div>

                <div class="card-body">
                  
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($branches->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">اسم الفرع</th>
                                    <th scope="col">المدينة</th>
                                    <th scope="col">تاريخ الإضافة</th>
                                    <th scope="col">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($branches as $branch)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $branch->name }}</td>
                                        <td>{{ $branch->city }}</td>
                                        <td>{{ $branch->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('dashboard.branches.edit', $branch->id) }}" class="btn btn-primary btn-sm">تعديل</a>

                                            {{-- استبدل زر الحذف القديم بهذا النموذج --}}
                                        <form action="{{ route('dashboard.branches.destroy', $branch->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذا الفرع؟')">
                                                حذف
                                            </button>
                                        </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- لعرض أزرار التنقل بين الصفحات --}}
                        {{ $branches->links() }} 
                    @else
                        <div class="alert alert-info text-center">
                            لا توجد أي فروع مضافة حالياً.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
