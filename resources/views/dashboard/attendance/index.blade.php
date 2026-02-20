@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">سجل الحضور والانصراف لشهر: {{ $monthName }} {{ $year }}</h5>
        </div>
        <div class="card-body">

            {{-- نموذج التنقل بين الشهور --}}
            <form action="{{ route('dashboard.attendance.index') }}" method="GET" class="row g-3 align-items-center mb-4">
                <div class="col-auto">
                    <label for="month" class="col-form-label">اختر الشهر:</label>
                </div>
                <div class="col-auto">
                    <select name="month" id="month" class="form-select">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                                {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-auto">
                    <label for="year" class="col-form-label">السنة:</label>
                </div>
                <div class="col-auto">
                    <input type="number" name="year" id="year" class="form-control" value="{{ $year }}" min="2020" max="2030">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">عرض السجل</button>
                </div>
            </form>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- بداية نموذج حفظ البيانات --}}
            <form action="{{ route('dashboard.attendance.store') }}" method="POST">
                @csrf
                <input type="hidden" name="month" value="{{ $month }}">
                <input type="hidden" name="year" value="{{ $year }}">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center">
                        <thead class="table-light">
                            <tr>
                                <th style="min-width: 150px;">اسم الموظف</th>
                                @for ($day = 1; $day <= $daysInMonth; $day++)
                                    <th>{{ $day }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employees as $employee)
                                <tr>
                                    <td class="text-start">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                    @for ($day = 1; $day <= $daysInMonth; $day++)
                                        @php
                                            // إنشاء مفتاح فريد لكل موظف في كل يوم
                                            $key = $employee->id . '-' . $day;
                                            // التحقق من وجود سجل حضور لهذا المفتاح
                                            $attendanceRecord = $attendances->get($key);
                                        @endphp
                                        <td>
                                            <!-- <select name="status[{{ $employee->id }}][{{ $day }}]" class="form-select form-select-sm">
                                                <option value="present" {{ optional($attendanceRecord)->status == 'present' ? 'selected' : '' }}>حاضر</option>
                                                <option value="absent" {{ optional($attendanceRecord)->status == 'absent' ? 'selected' : '' }}>غائب</option>
                                                <option value="leave" {{ optional($attendanceRecord)->status == 'leave' ? 'selected' : '' }}>إجازة</option>
                                                <option value="holiday" {{ optional($attendanceRecord)->status == 'holiday' ? 'selected' : '' }}>عطلة</option>
                                            </select> -->
                                            <select name="employees[{{ $employee->id }}][{{ $day }}]" class="form-select form-select-sm">
    
                                                {{-- خيار "حاضر" --}}
                                                <option value="present" 
                                                    {{ optional($attendanceRecord)->status == 'present' ? 'selected' : '' }}>
                                                    حاضر
                                                </option>

                                                {{-- خيار "غائب" (مع المنطق الافتراضي الجديد) --}}
                                                <option value="absent" 
                                                    {{ (optional($attendanceRecord)->status == 'absent' || !$attendanceRecord) ? 'selected' : '' }}>
                                                    غائب
                                                </option>

                                                {{-- خيار "إجازة" --}}
                                                <option value="leave" 
                                                    {{ optional($attendanceRecord)->status == 'leave' ? 'selected' : '' }}>
                                                    إجازة
                                                </option>

                                                {{-- خيار "عطلة" --}}
                                                <option value="holiday" 
                                                    {{ optional($attendanceRecord)->status == 'holiday' ? 'selected' : '' }}>
                                                    عطلة
                                                </option>

                                            </select>

                                        </td>
                                    @endfor
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $daysInMonth + 1 }}" class="text-center">لا يوجد موظفون لعرضهم.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
