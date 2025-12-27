@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">لوحة التقارير</h1>

    {{-- ================================================== --}}
    {{-- 1. تقرير قمع التوظيف (Recruitment Funnel Report) --}}
    {{-- ================================================== --}}
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-bar me-1"></i>
            تقرير  التوظيف (لكل وظيفة)
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>الوظيفة</th>
                            <th>الفرع</th>
                            <th class="text-center">إجمالي المتقدمين</th>
                            <th class="text-center">جدد</th>
                            <th class="text-center">فرز</th>
                            <th class="text-center">مقابلة</th>
                            <th class="text-center">عرض</th>
                            <th class="text-center">تم التوظيف</th>
                            <th class="text-center">مرفوض</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vacanciesReport as $vacancy)
                            <tr>
                                <td>
                                    <a href="{{ route('dashboard.vacancies.applications.index', $vacancy->id) }}">
                                        {{ $vacancy->title }}
                                    </a>
                                </td>
                                <td>{{ $vacancy->branch->name ?? 'N/A' }}</td>
                                <td class="text-center fw-bold">{{ $vacancy->total_applicants }}</td>
                                <td class="text-center">{{ $vacancy->new_applicants }}</td>
                                <td class="text-center">{{ $vacancy->screening_applicants }}</td>
                                <td class="text-center">{{ $vacancy->interview_applicants }}</td>
                                <td class="text-center">{{ $vacancy->offer_applicants }}</td>
                                <td class="text-center bg-success text-white fw-bold">{{ $vacancy->hired_applicants }}</td>
                                <td class="text-center">{{ $vacancy->rejected_applicants }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">لا توجد بيانات لعرضها.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ================================================== --}}
    {{-- 2. تقرير أداء الفروع (Branches Performance Report) --}}
    {{-- ================================================== --}}
    <div class="card">
        <div class="card-header">
            <i class="fas fa-sitemap me-1"></i>
            تقرير أداء الفروع
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>الفرع</th>
                            <th class="text-center">إجمالي الوظائف المفتوحة</th>
                            <th class="text-center">إجمالي من تم توظيفهم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($branchesReport as $branch)
                            <tr>
                                <td>{{ $branch->name }}</td>
                                <td class="text-center fw-bold">{{ $branch->total_vacancies }}</td>
                                <td class="text-center bg-success text-white">{{ $branch->total_hired ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">لا توجد فروع لعرضها.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
