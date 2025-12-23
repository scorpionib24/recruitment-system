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
