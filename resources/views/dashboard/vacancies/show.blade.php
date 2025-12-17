{{-- resources/views/public/vacancies/show.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ $vacancy->title }}</h4>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <p class="fs-5">
                            <i class="bi bi-geo-alt-fill text-secondary"></i> 
                            <strong>الفرع:</strong> {{ $vacancy->branch->name }} - {{ $vacancy->branch->city }}
                        </p>
                        @if($vacancy->deadline)
                            <p class="fs-6 text-danger">
                                <i class="bi bi-calendar-x-fill"></i>
                                <strong>آخر موعد للتقديم:</strong> {{ $vacancy->deadline->format('d M, Y') }}
                            </p>
                        @endif
                    </div>

                    <hr>

                    <div class="mb-4">
                        <h5><strong>الوصف الوظيفي</strong></h5>
                        {{-- استخدام {!! !!} يسمح بعرض التنسيق إذا كان النص يحتوي على HTML --}}
                        {{-- nl2br() تحول أسطر النص الجديدة إلى   
 --}}
                        <p>{!! nl2br(e($vacancy->description)) !!}</p>
                    </div>

                    <div class="mb-4">
                        <h5><strong>المتطلبات</strong></h5>
                        <p>{!! nl2br(e($vacancy->requirements)) !!}</p>
                    </div>

                    <hr>

                    <div class="text-center mt-4">
                        {{-- هذا الزر سيقود إلى صفحة التقديم التي سنبنيها لاحقاً --}}
                        <a href="#" class="btn btn-success btn-lg">التقديم الآن</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
