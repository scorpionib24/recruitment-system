@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12"> {{-- جعلناه أعرض قليلاً --}}
            <div class="card">
                <div class="card-header">
                    إدارة الوظائف الشاغرة
                    <a href="{{ route('dashboard.vacancies.create') }}" class="btn btn-success btn-sm float-end">إضافة وظيفة جديدة</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($vacancies->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>المسمى الوظيفي</th>
                                    <th>الفرع</th>
                                    <th>الحالة</th>
                                    <th>آخر موعد للتقديم</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vacancies as $vacancy)
                                    <tr>
                                        <td>
                                       <a href="{{ route('dashboard.vacancies.show', $vacancy->id) }}" target="_blank">
                                            {{ $vacancy->title }}
                                        </a
                                    </td>
                                        {{-- نعرض اسم الفرع من خلال العلاقة التي حملناها --}}
                                        <td>{{ $vacancy->branch->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($vacancy->status == 'open')
                                                <span class="badge bg-success">مفتوحة</span>
                                            @else
                                                <span class="badge bg-danger">مغلقة</span>
                                            @endif
                                        </td>
                                        <td>{{ $vacancy->deadline ? $vacancy->deadline->format('Y-m-d') : 'غير محدد' }}</td>
                                        <td>
                                           <td>
                                        <a href="{{ route('dashboard.vacancies.applications.index', $vacancy->id) }}" class="btn btn-secondary btn-sm">
                                            المتقدمون ({{ $vacancy->candidates_count }})
                                        </a>


                                        {{-- رابط التعديل --}}
                                        <a href="{{ route('dashboard.vacancies.edit', $vacancy->id) }}" class="btn btn-primary btn-sm">تعديل</a>

                                        {{-- نموذج الحذف --}}
                                        <form action="{{ route('dashboard.vacancies.destroy', $vacancy->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذه الوظيفة؟')">
                                                حذف
                                            </button>
                                        </form>
                                    </td>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $vacancies->links() }}
                    @else
                        <div class="alert alert-info text-center">
                            لا توجد أي وظائف شاغرة حالياً.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
