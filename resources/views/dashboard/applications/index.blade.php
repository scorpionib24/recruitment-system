{{-- resources/views/dashboard/applications/index.blade.php --}}
@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            المتقدمون لوظيفة: <strong>{{ $vacancy->title }}</strong>
        </div>
        
        @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
        @endif
        
        <div class="card-body">

            {{-- نموذج الفلترة --}}

            <div class="accordion mb-3" id="filterAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
                            <i class="fas fa-filter me-2"></i> {{-- يمكنك استخدام أي أيقونة فلتر هنا --}}
                            <span>فلترة المتقدمين</span>
                            
                            {{-- إضافة شارة صغيرة إذا كانت هناك فلاتر مطبقة حالياً --}}
                            @if(request('stage') || request('rating'))
                                <span class="badge rounded-pill bg-primary ms-2">مطبقة</span>
                            @endif
                        </button>
                    </h2>

                    {{-- هذا الشرط يفتح الأكورديون تلقائياً إذا كانت الصفحة محملة مع فلاتر --}}
                    <div id="collapseFilter" class="accordion-collapse collapse {{ (request('stage') || request('rating')) ? 'show' : '' }}" aria-labelledby="headingOne" data-bs-parent="#filterAccordion">
                        <div class="accordion-body bg-light">
                            
                            {{-- هنا نضع نفس نموذج الفلترة الذي لدينا بالفعل --}}
                            <form action="{{ route('dashboard.vacancies.applications.index', $vacancy->id) }}" method="GET" class="row g-3 align-items-center">
                        
                                {{-- فلتر الحالة --}}
                                <div class="col-md-4">
                                    <label for="filter_stage" class="form-label">الحالة</label>
                                    <select name="stage" id="filter_stage" class="form-select">
                                        <option value="">عرض الكل</option>
                                        @foreach (['new', 'screening', 'interview', 'offer', 'rejected', 'hired'] as $stage)
                                            <option value="{{ $stage }}" {{ request('stage') == $stage ? 'selected' : '' }}>
                                                {{ ucfirst($stage) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                
                                {{-- فلتر التقييم --}}
                                <div class="col-md-4">
                                    <label for="filter_rating" class="form-label">التقييم</label>
                                    <select name="rating" id="filter_rating" class="form-select">
                                        <option value="">عرض الكل</option>
                                        @foreach (['Qualified', 'Not Qualified', 'Qualified with Training'] as $rating)
                                            <option value="{{ $rating }}" {{ request('rating') == $rating ? 'selected' : '' }}>
                                                {{ $rating }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                
                                {{-- أزرار التحكم --}}
                                <div class="col-md-4 d-flex align-items-end pt-3">
                                    <button type="submit" class="btn btn-primary me-2">تطبيق الفلترة</button>
                                    <a href="{{ route('dashboard.vacancies.applications.index', $vacancy->id) }}" class="btn btn-secondary">إعادة تعيين</a>
                                </div>
                
                            </form>

                        </div>
                    </div>
                </div>
            </div>


            {{-- رسالة النجاح (إذا كانت موجودة) --}}
            @if (session('success'))
                {{-- ... (لا تغيير هنا) ... --}}
            @endif

            <table class="table">
                <thead>
                    <tr>
                        <th>اسم المتقدم</th>
                        <th>البريد الإلكتروني</th>
                        <th>تاريخ التقديم</th>
                        <th>الحالة</th>
                        <th>التقييم</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($candidates  as $candidate)
                        <tr>
                            <td>{{ $candidate->first_name }} {{ $candidate->last_name }}</td>
                            <td>{{ $candidate->email }}</td>
                            <td>{{ $candidate->pivot->applied_at }}</td>
                            <td>
                                <form class="update-stage-form" data-application-id="{{ $candidate->pivot->id }}">
                                    @csrf
                                    @method('PATCH')
                                   
                                    <select name="stage" class="form-select form-select-sm stage-select {{ 
                                        ($candidate->pivot->stage == 'rejected' || $candidate->pivot->stage == 'offer') ? 'bg-danger text-white' : 
                                        (($candidate->pivot->stage == 'hired' || $candidate->pivot->stage == 'screening') ? 'bg-success text-white' : 
                                        'bg-light') 
                                    }}">
                                        @foreach (['new', 'screening', 'interview', 'offer', 'rejected', 'hired'] as $stage)
                                            <option value="{{ $stage }}" @if($candidate->pivot->stage == $stage) selected @endif>
                                                {{ ucfirst($stage) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td>
                                <form class="update-rating-form" data-application-id="{{ $candidate->pivot->id }}">
                                    @csrf
                                    @method('PATCH')
                                    <select name="rating" class="form-select form-select-sm rating-select"
                                                @if($candidate->pivot->stage == 'new') disabled @endif 
                                                title="@if($candidate->pivot->stage == 'new') يجب تغيير حالة المتقدم أولاً @endif">

                                        {{-- الخيار الافتراضي إذا لم يتم التقييم بعد --}}
                                        <option value="" @if(is_null($candidate->pivot->rating)) selected @endif>-- اختر تقييم --</option>
                                        
                                        {{-- قائمة التقييمات --}}
                                        @foreach (['Qualified', 'Not Qualified', 'Qualified with Training'] as $rating)
                                            <option value="{{ $rating }}" @if($candidate->pivot->rating == $rating) selected @endif>
                                                {{ $rating }}
                                            </option>
                                        @endforeach
                                </select>
                                </form>
                            </td>
                            <td>
                                <a href="{{ asset('storage/' . $candidate->resume_path) }}" target="_blank" class="btn btn-info btn-sm">
                                    عرض السيرة الذاتية
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">لا يوجد أي متقدمين لهذه الوظيفة حتى الآن.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>


            <div class="mt-3">
                {{ $candidates->withQueryString()->links() }}
            </div>

        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
$(document).ready(function() {
    // دالة لتحديد لون الخلفية بناءً على الحالة
    function getBackgroundColorClass(stage) {
        switch (stage) {
            case 'rejected':
            case 'offer':
                return 'bg-danger text-white';
            case 'hired':
            case 'screening':
                return 'bg-success text-white';
            case 'interview':
                return 'bg-warning text-dark';
            default: // 'new'
                return 'bg-light text-dark';
        }
    }

    $('.stage-select').on('change', function() {
        var selectElement = $(this);
        var form = selectElement.closest('form');
        var applicationId = form.data('application-id');
        var newStage = selectElement.val();
        var url = "{{ url('dashboard/applications') }}/" + applicationId;

        // إزالة ألوان الخلفية القديمة
        selectElement.removeClass('bg-light bg-success bg-warning bg-danger text-white text-dark');

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'PATCH',
                stage: newStage
            },
            success: function(response) {
                // عرض إشعار نجاح
                toastr.success('تم تحديث حالة المتقدم بنجاح!');
                
                // تحديث لون الخلفية للقائمة المنسدلة
                selectElement.addClass(getBackgroundColorClass(newStage));

                // إذا لم تعد الحالة 'new'، قم بتمكين قائمة التقييم في نفس الصف
                if (newStage !== 'new') {
                    // ابحث عن قائمة التقييم في نفس صف الجدول (tr) وقم بإزالة خاصية 'disabled'
                    selectElement.closest('tr').find('.rating-select').prop('disabled', false).prop('title', '');
                }
                // ====================

            },
            error: function(xhr) {
                // عرض إشعار خطأ
                toastr.error('حدث خطأ أثناء التحديث. يرجى المحاولة مرة أخرى.');
                
                // يمكنك هنا إعادة القائمة إلى حالتها السابقة إذا أردت
            }
        });
    });

      // === الكود الجديد الخاص بتحديث التقييم ===
    $('.rating-select').on('change', function() {
        var selectElement = $(this);
        var form = selectElement.closest('form');
        var applicationId = form.data('application-id');
        var newRating = selectElement.val();
        var url = "{{ url('dashboard/applications') }}/" + applicationId + "/rating"; // الرابط الجديد

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'PATCH',
                rating: newRating
            },
            success: function(response) {
                // استخدام رسالة الرد من الخادم
                toastr.success(response.message); 
            },
            error: function(xhr) {
                toastr.error('حدث خطأ أثناء تحديث التقييم.');
            }
        });
    });
    // =================== Rating ====================



});
</script>
@endpush


