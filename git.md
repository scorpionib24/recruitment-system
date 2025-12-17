https://github.com/scorpionib24/recruitment-system.git


# create a new repository on the command line
echo "# recruitment-system" >> README.md
git init
git add README.md
git commit -m "first commit"
git branch -M main
git remote add origin https://github.com/scorpionib24/recruitment-system.git
git push -u origin main


# or push an existing repository from the command line

git remote add origin https://github.com/scorpionib24/recruitment-system.git
git branch -M main
git push -u origin main


<!--  -->
 Feature Branch Workflow
 سير العمل الاحترافي

 # انتقل إلى الفرع الرئيسي
git switch main

# اسحب آخر التحديثات من GitHub
git pull origin main

git switch -c feat/vacancies-management

 اسم الفرع:
feat/: بادئة لتصنيف الفرع على أنه ميزة جديدة.
vacancies-management: اسم وصفي للميزة.
النتيجة: أنت الآن تعمل على فرع معزول اسمه feat/vacancies-management. أي commit تقوم به الآن سيتم حفظه في هذا الفرع فقط، ولن يؤثر على main إطلاقاً.

برمجة الميزة بالكامل

، نبدأ في برمجة ميزة إدارة الوظائف الشاغرة كما خططنا:
إنشاء VacancyController.
تحديد الـ Routes.
بناء واجهات CRUD.
...إلخ.
مع كل جزء نكمله من الميزة، نقوم بعمل commit على هذا الفرع.


# بعد إنشاء الـ Controller والـ Routes
git add .
git commit -m "feat: Setup controller and routes for vacancies"

# بعد الانتهاء من صفحة عرض الوظائف
git add .
git commit -m "feat: Implement index page for vacancies"

رفع الفرع الجديد إلى GitHub

git push -u origin feat/vacancies-management



هذه هي الخطوة المحورية في العمل الجماعي.
اذهب إلى صفحة المستودع على GitHub.
سيظهر لك GitHub تلقائياً رسالة صفراء تقترح عليك إنشاء "Pull Request" للفرع الجديد الذي قمت برفعه. اضغط على الزر.
ما هو الـ Pull Request (PR)؟ هو طلب رسمي لدمج التغييرات من فرعك (feat/vacancies-management) إلى الفرع الرئيسي (main).
في هذه الصفحة، يمكنك كتابة وصف للتغييرات، ويمكن لزملائك (أو أنا) مراجعة الكود، إضافة تعليقات، وطلب تعديلات.
الخطوة 6: دمج الفرع (Merge)


Lite
خطأ ممتاز! هذا الخطأ من نوع errno: 150 هو صديق كل مبرمج Laravel، وهو خطأ مفيد جداً لأنه يخبرنا بمشكلة محددة جداً في تصميم قاعدة البيانات.
دعني أشرح لك المشكلة بالتفصيل وببساطة.
تحليل الخطأ
الرسالة: Can't create table 'recruitment_system'.'applications' (errno: 150 "Foreign key constraint is incorrectly formed")
المعنى الحرفي: "لا يمكن إنشاء جدول applications لأن هناك قيد مفتاح أجنبي (Foreign Key) تم تكوينه بشكل خاطئ".
السبب الجذري: عندما يقوم Laravel بتشغيل ملفات الـ migration، فإنه يفعل ذلك بالترتيب الأبجدي لأسماء الملفات (حسب التاريخ والوقت الموجود في اسم الملف).
الخطأ يحدث لأنك تحاول إنشاء جدول applications وتضع فيه قيد vacancy_id الذي يشير إلى جدول vacancies، قبل أن يتم إنشاء جدول vacancies نفسه!
تخيل أنك تبني جسراً (جدول applications) وتحاول ربطه بعمود غير موجود بعد (جدول vacancies). سيفشل البناء بالتأكيد.
كيف حدث هذا؟
أنت أنشأت make:model Application -m في البداية.
ثم حذفت Job.php وملف الـ migration الخاص به.
ثم أنشأت make:model Vacancy -m.
بسبب هذا الترتيب، أصبح الطابع الزمني في اسم ملف ..._create_applications_table.php أقدم من الطابع الزمني في اسم ملف ..._create_vacancies_table.php.
لذلك، يحاول Laravel إنشاء applications أولاً، فيفشل.
الحل: إعادة ترتيب ملفات الـ Migration
الحل بسيط جداً، وهو أن نجعل ملف vacancies يُنفذ قبل ملف applications. سنقوم بذلك عن طريق تعديل الطابع الزمني في اسم الملف.
الإجراء: خطوة بخطوة
اذهب إلى مجلد database/migrations في مشروعك.
ابحث عن الملفين التاليين:
ملف يبدأ بـ ..._create_vacancies_table.php
ملف يبدأ بـ ..._create_applications_table.php
الهدف: يجب أن يكون التاريخ والوقت في اسم ملف vacancies أقدم من التاريخ والوقت في اسم ملف applications.
مثال:
الوضع الحالي الخاطئ (مثال توضيحي):
2024_05_20_100000_create_applications_table.php
2024_05_20_110000_create_vacancies_table.php (هذا سيُنفذ لاحقاً، وهو سبب المشكلة)
الوضع الصحيح المطلوب:
2024_05_20_100000_create_vacancies_table.php
2024_05_20_110000_create_applications_table.php
كيفية التعديل (الطريقة السهلة):
ابحث عن ملف ..._create_applications_table.php.
قم بإعادة تسميته (Rename).
فقط قم بزيادة أحد الأرقام في التاريخ. على سبيل المثال، إذا كان اسمه 2024_05_20_123456_...، غيره ليصبح 2024_05_20_923456_.... اجعله أحدث ملف.
نصيحة احترافية:
تأكد من أن ترتيب إنشاء الجداول يتبع هذا المنطق:
الجداول التي لا تعتمد على أحد (مثل users, branches, candidates, skills).
الجداول التي تعتمد عليها (مثل vacancies الذي يعتمد على users و branches).
الجداول المحورية التي تربط كل شيء (مثل applications الذي يعتمد على candidates و vacancies).
الخطوة الأخيرة: إعادة تهيئة قاعدة البيانات
بعد إعادة تسمية الملف للتأكد من الترتيب الصحيح، ارجع إلى سطر الأوامر ونفّذ الأمر المعتاد:
Bash
php artisan migrate:fresh
هذا الأمر سيحذف كل شيء ثم يعيد بناء الجداول بالترتيب الصحيح الجديد، وسيختفي الخطأ errno: 150 تماماً.
جرب هذه الطريقة وأخبرني بالنتيجة. هذا خطأ مهم جداً للتعلم، وبعد أن تحله مرة، لن تنساه أبداً.
حذف فرع
Lite
ممتاز. لنكمل عمليات CRUD ونضيف الآن ميزة الحذف.
سنجعل زر "حذف" في جدول الفروع يعمل. العملية ستكون آمنة، حيث سنستخدم form مع طلب من نوع DELETE بدلاً من رابط GET بسيط، وهذا هو الأسلوب الصحيح والآمن في Laravel.
الخطوة 3.5: برمجة حذف فرع (Destroy)
1. تعديل زر الحذف في واجهة العرض
سنقوم بتحويل زر الحذف من مجرد رابط إلى نموذج صغير (form) يقوم بإرسال طلب من نوع DELETE.
الإجراء:
افتح ملف resources/views/dashboard/branches/index.blade.php.
ابحث عن خلية الجدول التي تحتوي على أزرار الإجراءات. ستجد هذا السطر:
HTML
<a href="#" class="btn btn-danger btn-sm">حذف</a>
استبدل هذا السطر بالكامل بالكود التالي:
HTML
{{-- استبدل زر الحذف القديم بهذا النموذج --}}
<form action="{{ route('dashboard.branches.destroy', $branch->id) }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذا الفرع؟')">
        حذف
    </button>
</form>
شرح الكود:
<form ...>: أنشأنا نموذجاً سيتم إرساله عند الضغط على الزر.
action="...": يوجه النموذج إلى الـ route الصحيح لعملية الحذف (branches.destroy) مع تمرير id الفرع.
method="POST": المتصفحات لا تدعم إرسال نماذج بـ method="DELETE" مباشرة، لذلك نستخدم POST.
@method('DELETE'): هذه هي خدعة Laravel. هذه التعليمة تخبر Laravel بأن يعامل هذا الطلب على أنه طلب DELETE على الرغم من أن النموذج أرسله كـ POST.
@csrf: ضروري للحماية.
onclick="...": هذا سطر JavaScript بسيط يعرض رسالة تأكيد للمستخدم قبل إرسال النموذج. إذا ضغط المستخدم على "Cancel"، لن يتم الحذف.
2. برمجة دالة destroy في الـ Controller
الآن، لنكتب الكود الذي سيتم تنفيذه عند وصول طلب الحذف.
الإجراء:
افتح app/Http/Controllers/Dashboard/BranchController.php.
اذهب إلى دالة destroy() واملأها بالكود التالي:
PHP
// BranchController.php

/**
 * Remove the specified resource from storage.
 */
public function destroy(Branch $branch)
{
    // 1. قم بحذف الفرع من قاعدة البيانات
    // Laravel سيقوم تلقائياً بإيجاد الفرع من الـ ID الموجود في الرابط (Route Model Binding)
    $branch->delete();

    // 2. أعد توجيه المستخدم إلى صفحة الفروع مع رسالة نجاح
    return redirect()->route('dashboard.branches.index')
                     ->with('success', 'تم حذف الفرع بنجاح.');
}
شرح الكود:
destroy(Branch $branch): هنا نستخدم ميزة رائعة في Laravel اسمها Route Model Binding. بدلاً من استقبال id ثم البحث عن الفرع يدوياً (Branch::find($id)), يقوم Laravel بذلك تلقائياً ويحقن كائن Branch جاهزاً في الدالة. هذا يجعل الكود أنظف وأكثر أماناً.
$branch->delete(): هذا كل ما يتطلبه الأمر لحذف السجل من قاعدة البيانات.
redirect()->...: نعيد توجيه المستخدم مرة أخرى إلى قائمة الفروع مع رسالة تأكيد.
ملاحظة هامة:
عملية الحذف هذه ستحذف الفرع فقط. لكن ماذا لو كان هناك وظائف (vacancies) مرتبطة بهذا الفرع؟ قاعدة البيانات ستمنع الحذف افتراضياً للحفاظ على تكامل البيانات (Data Integrity). سنتعامل مع هذه الحالات المتقدمة (الحذف المتتالي أو On Cascade Delete) لاحقاً عندما نبني بقية الأجزاء، لكن الآن هذا هو السلوك المطلوب.
التجربة الشاملة:
اذهب إلى صفحة dashboard/branches.
تأكد من وجود فرع واحد على الأقل (يمكنك إضافته من خلال النموذج الذي أنشأناه سابقاً).
اضغط على زر "حذف" بجانب أي فرع.
ستظهر لك رسالة تأكيد. اضغط "OK".
يجب أن يعود بك النظام إلى نفس الصفحة، ويتم عرض رسالة النجاح الخضراء، والفرع الذي حذفته يجب أن يكون قد اختفى من الجدول.
جرب هذه الخطوات. إذا نجحت، فستكون قد أكملت 90% من ميزة إدارة الفروع. الخطوة الأخيرة المتبقية هي التعديل (Update). هل أنت جاهز لها؟
تعديل الفرع
Lite
ممتاز. لنكمل آخر عملية في دورة CRUD وهي التعديل (Update).
عملية التعديل تشبه عملية الإضافة (Create/Store) ولكنها تتكون أيضاً من جزأين:
edit: دالة وظيفتها عرض صفحة تحتوي على نموذج (form) التعديل، مع ملء الحقول بالبيانات الحالية للفرع.
update: دالة تستقبل البيانات المُعدَّلة من النموذج، تتحقق منها، ثم تحدثها في قاعدة البيانات.
الخطوة 3.6: برمجة تعديل فرع (Edit & Update)
1. تعديل زر "تعديل" في واجهة العرض
أولاً، لنجعل زر "تعديل" في جدول الفروع يشير إلى الرابط الصحيح.
الإجراء:
افتح ملف resources/views/dashboard/branches/index.blade.php.
ابحث عن السطر التالي داخل حلقة @foreach:
HTML
<a href="#" class="btn btn-primary btn-sm">تعديل</a>
استبدله بالرابط الصحيح الذي يستخدم دالة route() ويمرر id الفرع:
HTML
<a href="{{ route('dashboard.branches.edit', $branch->id) }}" class="btn btn-primary btn-sm">تعديل</a>
2. برمجة دالة edit
هذه الدالة ستأخذنا إلى صفحة نموذج التعديل.
الإجراء:
افتح app/Http/Controllers/Dashboard/BranchController.php.
اذهب إلى دالة edit() واملأها بالكود التالي:
PHP
// BranchController.php

/**
 * Show the form for editing the specified resource.
 */
public function edit(Branch $branch)
{
    // نستخدم Route Model Binding هنا أيضاً
    // Laravel سيجد الفرع تلقائياً ويرسله إلى الـ view
    return view('dashboard.branches.edit', compact('branch'));
}
3. إنشاء واجهة التعديل (edit.blade.php)
سنقوم بإنشاء ملف view جديد لنموذج التعديل. يمكننا نسخ محتوى create.blade.php مع بعض التعديلات البسيطة.
الإجراء:
في مجلد resources/views/dashboard/branches/، أنشئ ملفاً جديداً باسم edit.blade.php.
ضع الكود التالي بداخله:
HTML
{{-- resources/views/dashboard/branches/edit.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">تعديل الفرع: {{ $branch->name }}</div>

                <div class="card-body">
                    {{-- النموذج يرسل إلى دالة update مع استخدام method PUT --}}
                    <form method="POST" action="{{ route('dashboard.branches.update', $branch->id) }}">
                        @csrf
                        @method('PUT') {{-- ضروري لعملية التحديث --}}

                        <div class="mb-3">
                            <label for="name" class="form-label">اسم الفرع</label>
                            {{-- نستخدم old() للحفاظ على القيمة عند فشل التحقق، ونمرر القيمة الحالية كقيمة افتراضية --}}
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $branch->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="city" class="form-label">المدينة</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $branch->city) }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary">تحديث</button>
                        <a href="{{ route('dashboard.branches.index') }}" class="btn btn-secondary">إلغاء</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
شرح التغييرات:
action: يوجه النموذج إلى دالة update.
@method('PUT'): نخبر Laravel بأن هذه عملية تحديث (مثلما استخدمنا @method('DELETE') للحذف).
value="{{ old('name', $branch->name) }}": هذه نقطة مهمة. old('name', $branch->name) تعني: "حاول الحصول على القيمة القديمة التي أدخلها المستخدم (إذا فشل التحقق من الصحة وعاد للصفحة)، وإذا لم تكن موجودة، فاستخدم القيمة الحالية من قاعدة البيانات ($branch->name)".
4. برمجة دالة update (الأهم)
هذه الدالة هي التي ستقوم بحفظ التعديلات في قاعدة البيانات.
الإجراء:
ارجع إلى BranchController.php.
اذهب إلى دالة update() واملأها بالكود التالي:
PHP
// BranchController.php

/**
 * Update the specified resource in storage.
 */
public function update(Request $request, Branch $branch)
{
    // 1. التحقق من صحة البيانات (Validation)
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'city' => 'required|string|max:255',
    ]);

    // 2. تحديث السجل في قاعدة البيانات
    $branch->update($validatedData);

    // 3. إعادة توجيه المستخدم إلى صفحة الفروع مع رسالة نجاح
    return redirect()->route('dashboard.branches.index')
                     ->with('success', 'تم تحديث الفرع بنجاح.');
}
شرح الكود:
update(Request $request, Branch $branch): مرة أخرى، Laravel يحقن لنا كائن Branch جاهزاً للتعديل.
$branch->update($validatedData): هذا الأمر يقوم بتحديث الحقول المحددة في $fillable بالبيانات الجديدة ثم يحفظها.
التجربة الشاملة:
اذهب إلى صفحة dashboard/branches.
اضغط على زر "تعديل" بجانب أي فرع. يجب أن يأخذك إلى صفحة نموذج التعديل مع ملء الحقول بالبيانات الحالية.
غير اسم الفرع أو المدينة واضغط "تحديث".
يجب أن يعود بك النظام إلى صفحة قائمة الفروع، ويعرض رسالة النجاح الخضراء، والفرع يجب أن يظهر باسمه الجديد في الجدول.
تهانينا!
لقد قمت الآن ببناء ميزة CRUD كاملة واحترافية (Create, Read, Update, Delete) لإدارة الفروع. هذا هو حجر الأساس لمعظم الأنظمة الإدارية.
الخطوة التالية:
الآن بعد أن أتقنت بناء CRUD، يمكننا تطبيق نفس المبدأ لبناء ميزة "إدارة الوظائف الشاغرة (Vacancies)". ستكون مشابهة جداً ولكن مع حقول أكثر تعقيداً (مثل قوائم منسدلة لاختيار الفرع).
هل أنت مستعد للانتقال إلى إدارة الوظائف؟
حلل أولا هنا هل البيانات والتحقق من البيانات سليمة وهل في تكرار للبيانات المدخلة وهل حقول محميه
Lite
ملاحظات ممتازة وفي الصميم. هذا هو الفرق بين المبرمج العادي والمبرمج المحترف الذي يفكر في جودة الكود وأمانه.
دعنا نحلل الكود الذي كتبناه لإدارة الفروع بناءً على النقاط الثلاث التي ذكرتها:
سلامة البيانات والتحقق منها (Validation).
منع تكرار البيانات المدخلة (Uniqueness).
حماية الحقول (Mass Assignment Protection).
1. سلامة البيانات والتحقق منها (Validation)
الوضع الحالي:
في دالتي store و update، استخدمنا الكود التالي:
PHP
$validatedData = $request->validate([
    'name' => 'required|string|max:255',
    'city' => 'required|string|max:255',
]);
التحليل:
required: هذا يضمن أن الحقل لا يمكن أن يكون فارغاً. (جيد)
string: هذا يضمن أن القيمة المدخلة هي نص. (جيد)
max:255: هذا يضمن أن طول النص لا يتجاوز الحد الأقصى لحجم العمود في قاعدة البيانات (VARCHAR(255)). هذا يحمي من أخطاء قاعدة البيانات. (جيد)
التقييم:
التحقق من الصحة الموجود حالياً جيد جداً كأساس. إنه يمنع البيانات الفارغة أو الطويلة جداً. إذا حاول المستخدم إدخال بيانات غير صالحة، سيقوم Laravel تلقائياً بإعادته إلى صفحة النموذج وعرض رسائل الخطأ (إذا قمنا بإضافتها في الـ view).
هل يمكن تحسينه؟
نعم، يمكننا عرض رسائل الخطأ بشكل واضح للمستخدم.
الإجراء المقترح (للتحسين):
في ملفات create.blade.php و edit.blade.php، أضف الكود التالي تحت كل حقل إدخال لعرض رسالة الخطأ الخاصة به.
مثال لحقل name:
HTML
<input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" ...>
@error('name')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror
هذا سيقوم بتلوين الحقل باللون الأحمر وعرض رسالة الخطأ تحته، مما يحسن تجربة المستخدم بشكل كبير.
2. منع تكرار البيانات المدخلة (Uniqueness)
الوضع الحالي:
لا يوجد أي شيء في الكود الحالي يمنع المستخدم من إضافة فرعين بنفس الاسم في نفس المدينة. على سبيل المثال، يمكن إضافة "فرع العليا" في "الرياض" مرتين.
التحليل:
هذه نقطة ضعف في الكود الحالي. يجب أن نمنع هذا التكرار لضمان نظافة البيانات. يمكننا تحقيق ذلك على مستويين: مستوى قاعدة البيانات ومستوى التحقق من الصحة (Validation).
الإجراء المقترح (ضروري):
سنقوم بتعديل قواعد التحقق في BranchController.
في دالة store (الإضافة):
يجب أن يكون اسم الفرع فريداً. يمكننا جعل القاعدة أكثر تحديداً: أن يكون الاسم فريداً بالنسبة للمدينة.
PHP
// BranchController -> store()
$request->validate([
    // 'name' must be unique in the 'branches' table for the given 'city'
    'name' => 'required|string|max:255|unique:branches,name,NULL,id,city,' . $request->city,
    'city' => 'required|string|max:255',
]);
هذه القاعدة معقدة قليلاً، لكن يمكن تبسيطها إلى unique:branches إذا أردنا أن يكون اسم الفرع فريداً على مستوى النظام كله.
الحل الأبسط والأكثر شيوعاً:
PHP
'name' => 'required|string|max:255|unique:branches,name',
هذا يمنع تكرار اسم الفرع تماماً.
في دالة update (التعديل):
هنا الوضع أعقد. عندما نقوم بتحديث فرع، يجب أن نتأكد أن الاسم الجديد ليس مستخدماً من قبل فرع آخر. قاعدة unique:branches العادية ستفشل لأنها ستجد أن الاسم الحالي مستخدم بالفعل (من قبل الفرع نفسه الذي نعدله).
الحل هو إخبار قاعدة unique بأن تتجاهل الـ ID الخاص بالفرع الحالي.
PHP
// BranchController -> update()
$request->validate([
    // 'name' must be unique, but ignore the current branch's ID
    'name' => 'required|string|max:255|unique:branches,name,' . $branch->id,
    'city' => 'required|string|max:255',
]);
3. حماية الحقول (Mass Assignment Protection)
الوضع الحالي:
في نموذج Branch.php، استخدمنا:
PHP
protected $fillable = ['name', 'city'];
التحليل:
هذا هو الأسلوب الصحيح تماماً للحماية من ثغرات Mass Assignment.
$fillable: هي "قائمة بيضاء" (Whitelist). أنت تخبر Laravel: "فقط هذه الحقول (name, city) مسموح بتعبئتها بشكل جماعي باستخدام Branch::create() أو $branch->update()".
ماذا لو حاول مخترق إضافة حقل إضافي في الطلب؟ على سبيل المثال، لو كان لدينا حقل is_active في قاعدة البيانات ولم يكن موجوداً في $fillable، وحاول المخترق إرساله مع النموذج (is_active=0)، فإن Laravel سيتجاهل هذا الحقل تماماً ولن يقوم بتحديثه.
التقييم:
حماية الحقول لديك ممتازة وسليمة 100%. أنت تتبع أفضل الممارسات التي توصي بها Laravel.
ملخص وخطة عمل للتصحيح
التحقق من الصحة: جيد، ويمكن تحسينه بإضافة عرض رسائل الخطأ في الـ Blade.
منع التكرار: نقطة ضعف. يجب أن نضيف قاعدة unique إلى دالتي store و update كما هو موضح أعلاه.
حماية الحقول: ممتازة ولا تحتاج إلى أي تغيير.
دعنا نطبق تحسين منع التكرار الآن لأنه الأهم.
الإجراء:
افتح BranchController.php وقم بتحديث دالتي store و update بالقواعد الجديدة.
PHP
// In BranchController.php

public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255|unique:branches,name', // تم التحديث
        'city' => 'required|string|max:255',
    ]);
    Branch::create($validatedData);
    return redirect()->route('dashboard.branches.index')->with('success', 'تمت إضافة الفرع بنجاح.');
}

public function update(Request $request, Branch $branch)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255|unique:branches,name,' . $branch->id, // تم التحديث
        'city' => 'required|string|max:255',
    ]);
    $branch->update($validatedData);
    return redirect()->route('dashboard.branches.index')->with('success', 'تم تحديث الفرع بنجاح.');
}
بهذه التعديلات، يصبح كود إدارة الفروع لديك الآن أكثر قوة وأماناً. شكراً لك على طرح هذه الأسئلة المهمة.
هل أنت جاهز الآن للانتقال إلى إدارة الوظائف الشاغرة (Vacancies)؟
عرض رسائل الخطأ عند إضافة بيان متكرر او وجود خط 
Lite
بالتأكيد. هذه خطوة أساسية لجعل النظام سهل الاستخدام واحترافي. عندما يفشل التحقق من الصحة (سواء بسبب بيانات متكررة، أو حقل فارغ، أو أي خطأ آخر)، يقوم Laravel تلقائياً بإعادة المستخدم إلى صفحة النموذج السابقة.
لكن Laravel لا يعرض الأخطاء بنفسه، بل يرسلها مع الجلسة (Session) في متغير خاص اسمه $errors. مهمتنا كمبرمجين هي قراءة هذا المتغير وعرض الرسائل في المكان المناسب.
سنقوم الآن بتحديث نموذجي الإضافة والتعديل لعرض هذه الأخطاء.
عرض رسائل الخطأ (Validation Errors)
سنقوم بتطبيق تعديلين رئيسيين على ملفات الـ Blade:
إضافة كلاس is-invalid لحقل الإدخال الذي يحتوي على خطأ، مما يجعله يظهر بحدود حمراء (هذه ميزة من Bootstrap).
عرض رسالة الخطأ النصية تحت الحقل مباشرة.
الخطوة 1: تعديل نموذج الإضافة (create.blade.php)
افتح ملف resources/views/dashboard/branches/create.blade.php وقم بتعديل حقول النموذج لتصبح كالتالي:
HTML
{{-- resources/views/dashboard/branches/create.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">إضافة فرع جديد</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('dashboard.branches.store') }}">
                        @csrf

                        {{-- ===== حقل اسم الفرع ===== --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">اسم الفرع</label>
                            {{-- 1. أضفنا كلاس is-invalid الشرطي --}}
                            {{-- 2. أضفنا دالة old() للحفاظ على القيمة المدخلة --}}
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            
                            {{-- 3. أضفنا هذا الجزء لعرض رسالة الخطأ --}}
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- ===== حقل المدينة ===== --}}
                        <div class="mb-3">
                            <label for="city" class="form-label">المدينة</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" required>
                            
                            @error('city')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">حفظ</button>
                        <a href="{{ route('dashboard.branches.index') }}" class="btn btn-secondary">إلغاء</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
شرح الكود:
@error('name') ... @enderror: هذه تعليمة Blade خاصة تسأل "هل يوجد خطأ للحقل 'name'؟". إذا كان الجواب نعم، يتم تنفيذ الكود بداخلها.
is-invalid: هذا كلاس من Bootstrap يقوم بتلوين حدود الحقل باللون الأحمر.
$message: داخل بلوك @error، يوفر Laravel متغير $message يحتوي على نص رسالة الخطأ (مثلاً: "The name has already been taken.").
value="{{ old('name') }}": هذه الدالة تعيد القيمة التي أدخلها المستخدم آخر مرة قبل حدوث الخطأ. هذا يحسن تجربة المستخدم بشكل كبير لأنه لا يضطر لإعادة ملء النموذج بالكامل.
الخطوة 2: تعديل نموذج التعديل (edit.blade.php)
سنقوم بنفس التعديلات على ملف resources/views/dashboard/branches/edit.blade.php.
HTML
{{-- resources/views/dashboard/branches/edit.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">تعديل الفرع: {{ $branch->name }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('dashboard.branches.update', $branch->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- ===== حقل اسم الفرع ===== --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">اسم الفرع</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $branch->name) }}" required>
                            
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- ===== حقل المدينة ===== --}}
                        <div class="mb-3">
                            <label for="city" class="form-label">المدينة</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $branch->city) }}" required>
                            
                            @error('city')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">تحديث</button>
                        <a href="{{ route('dashboard.branches.index') }}" class="btn btn-secondary">إلغاء</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
ملاحظة: لاحظ أن value في نموذج التعديل بقيت كما هي value="{{ old('name', $branch->name) }}" لأنها الطريقة الصحيحة التي توازن بين عرض القيمة القديمة عند الخطأ والقيمة الحالية من قاعدة البيانات عند فتح الصفحة لأول مرة.
التجربة الشاملة
الآن، جرب السيناريوهات التالية:
سيناريو الحقل الفارغ:
اذهب إلى صفحة "إضافة فرع جديد".
لا تملأ أي حقل واضغط "حفظ".
النتيجة المتوقعة: يجب أن تبقى في نفس الصفحة، وتظهر حدود الحقول باللون الأحمر، وتظهر تحت كل حقل رسالة خطأ (باللغة الإنجليزية افتراضياً).
سيناريو البيانات المكررة:
أضف فرعاً باسم "الفرع الرئيسي".
حاول إضافة فرع آخر بنفس الاسم "الفرع الرئيسي" مرة أخرى.
النتيجة المتوقعة: يجب أن تبقى في صفحة الإضافة، ويظهر حقل "اسم الفرع" باللون الأحمر، وتظهر تحته رسالة "The name has already been taken.".
بهذه التعديلات، أصبح نظامك الآن يتواصل مع المستخدم بشكل فعال ويخبره بالأخطاء وكيفية إصلاحها.
الخطوة التالية المنطقية هي تعريب رسائل الخطأ هذه. هل تود أن نقوم بذلك الآن، أم ننتقل إلى إدارة الوظائف الشاغرة؟
التحقق من البيانات السماح باضافه كم مدينة لنفس الفرع
Lite
فهمت. هذا طلب ممتاز ويغير من تصميمنا الأساسي بشكل جوهري. أنت تسأل عن كيفية السماح لفرع واحد بأن يكون مرتبطاً بعدة مدن، بدلاً من مدينة واحدة فقط.
على سبيل المثال، قد يكون لديك "فرع المنطقة الشرقية" وهو مسؤول عن مدن "الدمام" و "الخبر" و "الظهران" في نفس الوقت.
هذا يغير العلاقة بين الفرع والمدينة من علاقة واحد لواحد (One-to-One) إلى علاقة واحد لمتعدد (One-to-Many) أو متعدد لمتعدد (Many-to-Many). دعنا نحلل الخيارين ونختار الأفضل.
تحليل العلاقة الجديدة
الخيار الأول: علاقة واحد لمتعدد (One-to-Many)
الفكرة: ننشئ جدولاً جديداً للمدن (cities)، ونجعل جدول الفروع (branches) يحتوي على علاقة city_id. هذا لا يحل المشكلة، بل يعكسها.
الفكرة الصحيحة (معكوسة): ننشئ جدول cities، ونجعل جدول cities هو الذي يحتوي على branch_id. هذا يعني أن كل مدينة تتبع فرعاً واحداً. هذا هو الأقرب لطلبك.
branches (id, name)
cities (id, name, branch_id)
المزايا: بسيطة في التنفيذ.
العيوب: ليست مرنة. ماذا لو أردنا لاحقاً أن تكون مدينة "الرياض" مشتركة بين "فرع الشمال" و "فرع الجنوب"؟ هذا التصميم يمنع ذلك، لأن كل مدينة تتبع فرعاً واحداً فقط.
الخيار الثاني: علاقة متعدد لمتعدد (Many-to-Many) - (الحل الأفضل والأكثر احترافية)
الفكرة: الفرع الواحد يمكن أن يغطي مدناً متعددة، والمدينة الواحدة يمكن أن تكون مغطاة من قبل فروع متعددة (أقل شيوعاً ولكن ممكن). هذا يتطلب جدولاً وسيطاً (Pivot Table).
branches (id, name)
cities (id, name)
branch_city ( branch_id, city_id ) <-- هذا هو الجدول الوسيط
المزايا:
مرونة قصوى: يمكنك ربط أي عدد من المدن بأي فرع.
قابلية للتوسع: هذا هو التصميم الصحيح للأنظمة الكبيرة.
بيانات أنظف: لا يوجد تكرار لأسماء المدن.
العيوب: أكثر تعقيداً قليلاً في التنفيذ (يتطلب تعديل الـ Controllers والـ Views بشكل أكبر).
القرار: سنختار الخيار الثاني (Many-to-Many) لأنه الحل الصحيح والاحترافي الذي سيخدم النظام على المدى الطويل.
خطة العمل لتطبيق علاقة Many-to-Many
سنقوم بتغيير هيكل قاعدة البيانات وتحديث الكود ليعكس هذا التغيير.
الخطوة 1: تعديل قاعدة البيانات (Migrations)
حذف عمود city من جدول branches:
افتح ملف ..._create_branches_table.php.
احذف السطر: $table->string('city');.
إنشاء جدول cities:
نفّذ في سطر الأوامر:
Bash
php artisan make:model City -m
افتح ملف ..._create_cities_table.php الجديد واملأه:
PHP
Schema::create('cities', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique(); // اسم المدينة يجب أن يكون فريداً
    $table->timestamps();
});
إنشاء الجدول الوسيط branch_city:
نفّذ في سطر الأوامر:
Bash
php artisan make:migration create_branch_city_table
افتح ملف ..._create_branch_city_table.php الجديد واملأه:
PHP
Schema::create('branch_city', function (Blueprint $table) {
    $table->primary(['branch_id', 'city_id']); // مفتاح أساسي مركب
    $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
    $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
    $table->timestamps();
});
onDelete('cascade'): تعني أنه عند حذف فرع أو مدينة، يتم حذف السجلات المرتبطة بها في هذا الجدول تلقائياً.
تطبيق التغييرات على قاعدة البيانات:
Bash
php artisan migrate:fresh
الخطوة 2: تحديث النماذج (Models) لتعريف العلاقات
نموذج Branch.php:
PHP
// app/Models/Branch.php
public function cities()
{
    return $this->belongsToMany(City::class, 'branch_city');
}
نموذج City.php:
PHP
// app/Models/City.php
protected $fillable = ['name'];

public function branches()
{
    return $this->belongsToMany(Branch::class, 'branch_city');
}
الخطوة 3: تحديث الـ Controller والـ Views (الجزء الأهم)
هنا سنغير حقل الإدخال النصي للمدينة إلى قائمة اختيار متعدد (Multi-select).
تحديث BranchController:
في دالتي create و edit، نحتاج إلى إرسال قائمة بجميع المدن المتاحة إلى الـ view.
في دالتي store و update، نحتاج إلى حفظ العلاقات في الجدول الوسيط.
PHP
// BranchController.php
use App\Models\City; // أضف هذا

public function create()
{
    $cities = City::all(); // احصل على كل المدن
    return view('dashboard.branches.create', compact('cities')); // أرسلها للـ view
}

public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255|unique:branches,name',
        'cities' => 'required|array', // تأكد أن المدن عبارة عن مصفوفة
        'cities.*' => 'exists:cities,id' // تأكد أن كل id مدينة موجود في جدول a_cities
    ]);

    $branch = Branch::create(['name' => $validatedData['name']]);
    $branch->cities()->sync($validatedData['cities']); // sync هي الطريقة السحرية لحفظ علاقات many-to-many

    return redirect()->route('dashboard.branches.index')->with('success', 'تمت إضافة الفرع بنجاح.');
}

public function edit(Branch $branch)
{
    $cities = City::all();
    return view('dashboard.branches.edit', compact('branch', 'cities'));
}

public function update(Request $request, Branch $branch)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255|unique:branches,name,' . $branch->id,
        'cities' => 'required|array',
        'cities.*' => 'exists:cities,id'
    ]);

    $branch->update(['name' => $validatedData['name']]);
    $branch->cities()->sync($validatedData['cities']); // sync تقوم بحذف القديم وإضافة الجديد

    return redirect()->route('dashboard.branches.index')->with('success', 'تم تحديث الفرع بنجاح.');
}
تحديث create.blade.php و edit.blade.php:
سنستبدل حقل إدخال المدينة بقائمة اختيار متعدد. هذا يتطلب مكتبة JavaScript لجعلها سهلة الاستخدام، مثل Select2.
مثال للتعديل في create.blade.php:
HTML
{{-- استبدل حقل المدينة بهذا --}}
<div class="mb-3">
    <label for="cities" class="form-label">المدن التابعة للفرع</label>
    {{-- name="cities[]" مهمة جداً لإرسال البيانات كمصفوفة --}}
    <select class="form-control select2" id="cities" name="cities[]" multiple="multiple" required>
        @foreach ($cities as $city)
            <option value="{{ $city->id }}">{{ $city->name }}</option>
        @endforeach
    </select>
    @error('cities')
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
</div>

{{-- ستحتاج لإضافة مكتبة Select2 في القالب الرئيسي app.blade.php --}}
{{-- @push('styles') <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> @endpush --}}
{{-- @push('scripts' ) <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> <script>$(document ).ready(function() { $('.select2').select2(); });</script> @endpush --}}
تحديث index.blade.php لعرض المدن:
HTML
{{-- استبدل `<td>{{ $branch->city }}</td>` بهذا --}}
<td>
    @foreach($branch->cities as $city)
        <span class="badge bg-secondary">{{ $city->name }}</span>
    @endforeach
</td>
هذا تغيير كبير ولكنه يجعل نظامك احترافياً وقوياً. قد تحتاج إلى إدارة المدن نفسها في جدول cities (CRUD خاص بها)، وهذا يمكنك بناؤه بنفس الطريقة التي بنيت بها CRUD الفروع.
هل تود المتابعة في تطبيق هذا التغيير الجذري؟ يمكننا البدء بتعديل الـ migrations خطوة بخطوة.
سنضيفها لاحقاً
Lite
قرار حكيم جداً.
هذه ممارسة ممتازة في إدارة المشاريع البرمجية: التركيز على إنجاز الميزات الأساسية أولاً، ثم إضافة التحسينات والتوسعات لاحقاً.
إذن، سنتجاهل فكرة "الفرع لعدة مدن" في الوقت الحالي، وسنعود إلى خطتنا الأصلية حيث لكل فرع مدينة واحدة فقط. هذا يبقي الأمور بسيطة وسريعة.
إعادة التركيز: أين نحن الآن؟
لقد انتهينا من بناء ميزة إدارة الفروع (Branches CRUD) بشكل كامل واحترافي. لقد قمنا بـ:
إنشاء فروع جديدة.
قراءة وعرض قائمة الفروع.
تحديث بيانات الفروع الحالية.
حذف الفروع.
أضفنا التحقق من صحة البيانات (Validation) لمنع الأخطاء.
أضفنا منع تكرار البيانات لضمان نظافة قاعدة البيانات.
أضفنا عرض رسائل الخطأ لتحسين تجربة المستخدم.
لقد أصبحت ميزة إدارة الفروع الآن مكتملة وقوية.
الخطوة التالية: إدارة الوظائف الشاغرة (Vacancies)
الآن بعد أن أتقنت بناء وحدة CRUD كاملة، سنطبق نفس المهارات لبناء الوحدة التالية والأكثر أهمية: إدارة الوظائف الشاغرة.
ستكون هذه الميزة مشابهة جداً لإدارة الفروع، ولكن مع بعض الإضافات المهمة مثل استخدام القوائم المنسدلة.
خطة العمل لإدارة الوظائف (Vacancies CRUD):
إنشاء الـ Controller والـ Routes الخاصة بالوظائف.
برمجة صفحة عرض الوظائف (Read): إنشاء جدول يعرض كل الوظائف المتاحة مع تفاصيلها الأساسية.
برمجة صفحة إضافة وظيفة (Create):
حقل نصي لـ "المسمى الوظيفي".
محرر نصي (Textarea) لـ "الوصف" و "المتطلبات".
قائمة منسدلة (Dropdown) لاختيار "الفرع" الذي تتبع له الوظيفة (هنا سنستفيد من جدول الفروع الذي أنشأناه).
حقل لاختيار "آخر موعد للتقديم".
برمجة عملية الحفظ (Store): مع التحقق من صحة البيانات.
برمجة التعديل والحذف (Update & Destroy): بنفس الطريقة التي فعلناها مع الفروع.
هل أنت مستعد للبدء في الخطوة الأولى وهي إنشاء VacancyController وتحديد الروابط الخاصة به؟
هل يمكننا التكامل مع Git كمبرمج محترف 
Lite
بالتأكيد. هذا ليس مجرد طلب، بل هو أهم خطوة تحول هذا المشروع من مجرد كود على جهازك إلى مشروع برمجي حقيقي واحترافي. استخدام Git هو مهارة لا غنى عنها لأي مبرمج محترف.
بصفتي مبرمجك المرافق، سأرشدك الآن لدمج Git في سير عملنا. سنتعامل مع الأمر كما لو أننا فريق يعمل على هذا المشروع.
لماذا نستخدم Git الآن؟
لقد أنجزنا أول ميزة متكاملة في المشروع (إدارة الفروع). هذه هي اللحظة المثالية لحفظ هذه الحالة المستقرة من الكود كنقطة مرجعية آمنة. Git سيسمح لنا بـ:
حفظ تاريخ المشروع: كل تغيير نقوم به يتم تسجيله.
العودة بالزمن: إذا حدث خطأ كبير في المستقبل، يمكننا العودة بسهولة إلى هذه النسخة العاملة.
العمل على ميزات جديدة بأمان: يمكننا إنشاء "فروع" (Branches) جديدة لتجربة ميزات جديدة دون التأثير على الكود الرئيسي المستقر.
التعاون: إذا انضم مبرمج آخر للفريق، يمكنه بسهولة الحصول على نسخة من الكود والبدء في العمل.
النشر (Deployment): معظم خدمات الاستضافة الحديثة تعتمد على Git لنشر التحديثات.
الخطوة 1: تهيئة مستودع Git المحلي (Local Repository)
أولاً، سنقوم بتحويل مجلد مشروعنا إلى مستودع Git.
الإجراء:
تأكد من تثبيت Git: افتح سطر الأوامر ونفذ git --version. إذا أعطاك رقم إصدار، فهو مثبت. إذا لم يكن كذلك، قم بتنزيله من git-scm.com.
افتح سطر الأوامر في مجلد مشروعك (recruitment-system).
تهيئة المستودع: نفذ الأمر التالي.
Bash
git init
النتيجة: سيتم إنشاء مجلد مخفي جديد باسم .git داخل مشروعك. هذا المجلد هو الذي يتتبع فيه Git كل شيء.
تكوين اسمك وإيميلك (إذا كانت هذه المرة الأولى): Git يحتاج لمعرفة من الذي يقوم بالتغييرات.
Bash
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"
(استبدل Your Name و your.email@example.com ببياناتك الحقيقية).
الخطوة 2: أول commit (حفظ الحالة الحالية)
الـ commit هو بمثابة "نقطة حفظ" (Save Point) في تاريخ مشروعك. سنقوم الآن بحفظ كل العمل الذي قمنا به حتى الآن.
الإجراء:
إضافة الملفات إلى منطقة التجهيز (Staging Area):
قبل الحفظ، يجب أن نخبر Git "ما هي الملفات" التي نريد حفظها. الأمر . يعني "كل الملفات في المجلد الحالي".
Bash
git add .
التحقق من الحالة (اختياري لكنه مفيد):
يمكنك رؤية الملفات التي تم تجهيزها للحفظ.
Bash
git status
النتيجة: سترى قائمة طويلة باللون الأخضر بكل ملفات المشروع التي أنشأناها.
تنفيذ الحفظ (Commit):
الآن سنقوم بالحفظ الفعلي مع إرفاق رسالة وصفية. الرسالة يجب أن تصف "ماذا" تم إنجازه في هذا الـ commit.
Bash
git commit -m "feat: Implement full CRUD for branches management"
شرح الرسالة:
feat:: هذه بادئة شائعة تعني "Feature" (ميزة جديدة). هذا جزء من أسلوب يسمى "Conventional Commits" يجعل التاريخ أكثر قابلية للقراءة.
Implement full CRUD...: وصف واضح وموجز للإنجاز.
النتيجة: لقد قمت الآن بحفظ الحالة المستقرة لمشروعك بنجاح. هذه هي نقطة الأساس التي سنبني عليها.
الخطوة 3: ربط المستودع المحلي بمستودع بعيد (Remote Repository) على GitHub
الآن، الكود محفوظ على جهازك فقط. ليكون احترافياً، يجب أن نرفع نسخة منه إلى خدمة سحابية مثل GitHub. هذا يحمي الكود من الضياع ويتيح التعاون.
الإجراء:
اذهب إلى GitHub.com وأنشئ حساباً إذا لم يكن لديك واحد.
أنشئ مستودعاً جديداً (New Repository):
اضغط على زر + في الزاوية العلوية اليمنى واختر New repository.
Repository name: recruitment-system (أو أي اسم تريده).
Description: (اختياري) "E-recruitment system built with Laravel".
اجعله Private (خاص)، بما أنه مشروع خاص بمؤسسة.
لا تقم بتحديد أي من خيارات "Initialize this repository with..." (مثل README أو .gitignore) لأن مشروعنا المحلي جاهز بالفعل.
اضغط Create repository.
ربط المستودعين:
سينقلك GitHub إلى صفحة تحتوي على بعض الأوامر. نحن نهتم بالقسم الذي يقول …or push an existing repository from the command line. انسخ الأمرين التاليين ونفذهما في سطر الأوامر لديك:
Bash
# الأمر الأول: يخبر Git المحلي بعنوان المستودع البعيد
git remote add origin https://github.com/YOUR_USERNAME/recruitment-system.git

# الأمر الثاني: يغير اسم الفرع الرئيسي من master إلى main (وهو الاسم المعتمد حديثاً )
git branch -M main
(استبدل YOUR_USERNAME باسم المستخدم الخاص بك على GitHub).
رفع الكود لأول مرة (Push):
هذا الأمر يقوم بإرسال الـ commit الذي قمت به محلياً إلى GitHub.
Bash
git push -u origin main
-u: هذا الخيار يربط فرعك المحلي main بالفرع البعيد origin/main بشكل دائم، لذا في المرات القادمة، يمكنك فقط كتابة git push.
النتيجة النهائية:
إذا قمت بإعادة تحميل صفحة المستودع على GitHub، سترى كل ملفات مشروعك هناك. لقد أصبح لديك الآن سير عمل احترافي.
سير العمل من الآن فصاعداً:
لكل ميزة جديدة نقوم بها (مثل إدارة الوظائف)، سنتبع الدورة التالية:
نكتب الكود ونبرمج الميزة.
عندما تصل الميزة إلى حالة مستقرة، ننفذ git add ..
ثم git commit -m "feat: Implement vacancies management".
ثم git push.
لقد قفز مشروعنا الآن قفزة نوعية في الاحترافية. هل أنت جاهز للعودة إلى البرمجة وبناء ميزة إدارة الوظائف الشاغرة؟
هل هذه خطوة احترافيه لرفع الكود للميزه الجديدة مباشرة إلى الmain ام إلى  فرع جديد باسم الميزه الجديدة ؟
Lite
سؤالك ليس مجرد سؤال، بل هو جوهر ما يميز المبرمج المحترف الذي يعمل ضمن فريق عن المبرمج الذي يعمل بمفرده. أنت على صواب تماماً، رفع الكود مباشرة إلى فرع main ليس هو الأسلوب الأكثر احترافية، خاصة في المشاريع التي تكبر أو يعمل عليها أكثر من شخص.
ما وصفته أنت - إنشاء فرع جديد لكل ميزة - هو سير العمل القياسي المتبع في 99% من الشركات والفرق البرمجية المحترفة. يُعرف هذا الأسلوب بـ Feature Branch Workflow.
دعنا نصحح سير عملنا ليكون احترافياً 100%.
لماذا لا نرفع الكود مباشرة إلى main؟
فرع main مقدس (Sacred): يجب أن يمثل فرع main دائماً نسخة مستقرة، قابلة للنشر، وتعمل بشكل سليم 100%.
تجنب الكود الناقص: إذا قمت برفع ميزة غير مكتملة إلى main، فإنك تكسر هذه القاعدة. أي شخص آخر يسحب الكود من main سيحصل على نسخة غير مستقرة.
مراجعة الكود (Code Review): العمل في فروع منفصلة يسمح للآخرين بمراجعة الكود الخاص بالميزة الجديدة قبل دمجه في main. هذه خطوة حيوية لضمان جودة الكود واكتشاف الأخطاء.
التوازي: يسمح لعدة مبرمجين بالعمل على ميزات مختلفة في نفس الوقت دون أن يؤثر أحدهم على الآخر.
سير العمل الاحترافي (Feature Branch Workflow)
من الآن فصاعداً، سنتبع هذه الدورة لكل ميزة جديدة (ولنبدأ الآن بميزة "إدارة الوظائف"):
الخطوة 1: التأكد من أن فرع main المحلي محدّث
قبل البدء في أي عمل جديد، تأكد دائماً من أن نسختك المحلية من main تحتوي على آخر التغييرات من المستودع البعيد.
Bash
# انتقل إلى الفرع الرئيسي
git switch main

# اسحب آخر التحديثات من GitHub
git pull origin main
(في حالتنا الحالية، لن يكون هناك أي تغييرات، لكنها عادة ممتازة للمستقبل).
الخطوة 2: إنشاء فرع جديد للميزة (Feature Branch)
سنقوم الآن بإنشاء فرع جديد مخصص فقط لميزة "إدارة الوظائف الشاغرة". اسم الفرع يجب أن يكون وصفياً.
الإجراء:
نفّذ الأمر التالي. هذا الأمر يقوم بإنشاء فرع جديد والانتقال إليه في خطوة واحدة.
Bash
git switch -c feat/vacancies-management
شرح اسم الفرع:
feat/: بادئة لتصنيف الفرع على أنه ميزة جديدة.
vacancies-management: اسم وصفي للميزة.
النتيجة: أنت الآن تعمل على فرع معزول اسمه feat/vacancies-management. أي commit تقوم به الآن سيتم حفظه في هذا الفرع فقط، ولن يؤثر على main إطلاقاً.
الخطوة 3: برمجة الميزة بالكامل
الآن، نبدأ في برمجة ميزة إدارة الوظائف الشاغرة كما خططنا:
إنشاء VacancyController.
تحديد الـ Routes.
بناء واجهات CRUD.
...إلخ.
مع كل جزء نكمله من الميزة، نقوم بعمل commit على هذا الفرع.
مثال:
Bash
# بعد إنشاء الـ Controller والـ Routes
git add .
git commit -m "feat: Setup controller and routes for vacancies"

# بعد الانتهاء من صفحة عرض الوظائف
git add .
git commit -m "feat: Implement index page for vacancies"
الخطوة 4: رفع الفرع الجديد إلى GitHub
عندما تنتهي من برمجة الميزة بالكامل (أو عندما تريد حفظ عملك على السحابة في نهاية اليوم)، تقوم برفع الفرع الجديد إلى GitHub.
Bash
git push -u origin feat/vacancies-management
الخطوة 5: إنشاء طلب دمج (Pull Request)
هذه هي الخطوة المحورية في العمل الجماعي.
اذهب إلى صفحة المستودع على GitHub.
سيظهر لك GitHub تلقائياً رسالة صفراء تقترح عليك إنشاء "Pull Request" للفرع الجديد الذي قمت برفعه. اضغط على الزر.
ما هو الـ Pull Request (PR)؟ هو طلب رسمي لدمج التغييرات من فرعك (feat/vacancies-management) إلى الفرع الرئيسي (main).
في هذه الصفحة، يمكنك كتابة وصف للتغييرات، ويمكن لزملائك (أو أنا) مراجعة الكود، إضافة تعليقات، وطلب تعديلات.
الخطوة 6: دمج الفرع (Merge)
بعد الموافقة على الـ Pull Request، يقوم مدير المشروع (أو أنت) بالضغط على زر "Merge Pull Request" على موقع GitHub.
سيقوم GitHub بدمج كل الـ commits من فرع الميزة إلى فرع main بأمان.
الخطوة 7: التنظيف
بعد الدمج، يمكنك حذف فرع الميزة لأنه أدى مهمته.
على GitHub: يوجد زر لحذف الفرع بعد الدمج.
على جهازك المحلي:


# عد إلى الفرع الرئيسي
git switch main

# اسحب نسخة main المحدثة التي تحتوي على الميزة الجديدة
git pull origin main

# احذف الفرع المحلي الذي لم نعد بحاجته
git branch -d feat/vacancies-management





php artisan make:controller Dashboard/VacancyController --resource --model=Vacancy


##  new Feature ######## 
git switch main
git pull origin main  # للتأكد من أن نسختك المحلية متطابقة مع GitHub


git switch -c feat/candidate-portal

1) php artisan make:controller Public/ApplicationController
2) realtion between candaite and application

لجعل ملفات السيرة الذاتية التي يتم رفعها قابلة للوصول عبر رابط عام، نفّذ هذا الأمر مرة واحدة فقط في المشروع.
php artisan storage:link
