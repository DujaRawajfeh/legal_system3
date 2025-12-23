<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'نظام المحكمة'); ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
    body {
        margin: 0;
        font-family: 'Cairo', sans-serif;
        direction: rtl;
        background-color: #f8f9fa;
    }

    /* الشريط العلوي الرمادي */
    .court-bar {
        background-color: #717172;
        color: #fff;
        text-align: right;
        font-size: 1rem;
        padding: 8px 20px;
    }

    /* الشريط الأسود */
    .navbar {
        background-color: #111;
        padding: 6px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        font-size: 12px;
        border-bottom: 2px solid #333;
    }

    .navbar .left-section {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .navbar .user-info {
        color: white;
        white-space: nowrap;
        font-weight: 700;
        font-size: 13px;
    }

    .navbar .nav-links {
        list-style: none;
        display: flex;
        margin: 0;
        padding: 0;
        gap: 10px;
    }

    .navbar .nav-links li {
        display: inline-block;
    }

    .navbar .security-link {
        color: #fff;
        text-decoration: none;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 5px;
        background-color: #222;
        transition: background 0.3s, color 0.3s, text-decoration 0.3s;
    }

    .navbar .security-link:hover {
        text-decoration: underline;
    }

    .navbar ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        gap: 20px;
    }

    .navbar ul li {
        position: relative;
    }

    .navbar ul li a {
        color: white;
        text-decoration: none;
        cursor: pointer;
    }

    .navbar ul li a:hover {
        text-decoration: underline;
    }

    .navbar ul li ul {
        display: none;
        position: absolute;
        background-color: #fff;
        border: 1px solid #ccc;
        padding: 5px 0;
        right: 0;
        min-width: 180px;
        z-index: 100;
    }

    .navbar ul li ul li {
        padding: 5px 10px;
    }

    .navbar ul li ul li a {
        color: #000;
    }

    .navbar ul li:hover ul {
        display: block;
    }

    .content {
        padding: 0;
    }

    #case-options {
        display: none;
        position: absolute;
        background-color: #f8f9fa;
        border: 1px solid #ccc;
        width: 250px;
        z-index: 9999;
        text-align: right;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transform: translate(12px, 10px);
        transition: opacity 0.2s ease, transform 0.2s ease;
    }
    #case-options li:hover {
        background-color: #e9ecef;
    }



   .logout-btn {
    background-color: #2f6fae;   /*تسجيل الدخول*/
    border: none;
    color: white;
    padding: 5px 12px;
    font-size: 12px;
    border-radius: 6px;
    cursor: pointer;
    font-family: "Cairo", sans-serif;
    line-height: 1;
}

.logout-btn:hover {
    background-color: #255b8f;   /* أغمق شوي عند المرور */
}
    </style>
</head>

<body>

<!-- الشريط العلوي الرمادي -->
<div class="court-bar"><?php echo e(optional(auth()->user()->tribunal)->name ?? 'محكمة بداية عمان'); ?> / <?php echo e(optional(auth()->user()->department)->name ?? '-'); ?></div>

<!-- الشريط الأسود -->
<nav class="navbar">
  <div class="left-section">
    <div class="user-info">الكاتب / <?php echo e(auth()->user()->full_name ?? 'محمد احمد'); ?></div>
    
    <ul class="nav-links">
      <li><a href="<?php echo e(route('2fa.setup')); ?>" class="security-link" target="_self">اعدادات الحماية</a></li>
    </ul>

    <ul>
      <li><a href="#" id="trigger-cases">الدعوى▾</a>
        <ul>
          <li><a href="#" data-bs-toggle="modal" data-bs-target="#registerCaseModal">تسجيل دعوى</a></li>
          <li><a href="#" data-bs-toggle="modal" data-bs-target="#withdrawCaseModal">سحب دعوى/المدعي العام </a></li>
          <li><a href="#" data-bs-toggle="modal" data-bs-target="#pullPoliceCaseModal">سحب دعوى من الشرطة</a></li>
        </ul>
      </li>
      <li><a href="#">الطلب▾</a>
        <ul>
          <li><a href="#" id="open-register-request">تسجيل الطلبات </a></li>
        </ul>
      </li>
      <li><a href="#">مخاطبات الامن العام ▾</a>
        <ul>
          <li><a href="#" data-bs-toggle="modal" data-bs-target="#arrest-memo-modal">مذكرة توقيف</a></li>
          <li><a href="#"data-bs-toggle="modal"data-bs-target="#extend-arrest-memo-modal"> مذكرة تمديد توقيف</a></li>
         <li><a href="#"data-bs-toggle="modal"data-bs-target="#release-memo-modal">مذكرة افراج للموقوفين</a></li>
        </ul>
      </li>
      <li><a href="#" id="trigger-notifications">تباليغ ▾</a>
        <ul>
          <li><a href="#" data-bs-toggle="modal" data-bs-target="#notif-complainant-modal">مذكرة تبليغ مشتكى عليه</a></li>
          <li><a href="#" data-bs-toggle="modal" data-bs-target="#notif-session-complainant-modal">مذكرة تبليغ مشتكي موعد جلسة</a></li>
          <li><a class="submenu-item" href="#" 
     data-bs-toggle="modal" 
     data-bs-target="#notif-witness-modal">مذكرة حضور خاصة بالشهود</a></li>
         <li>
    <a href="#"
       data-bs-toggle="modal"
       data-bs-target="#notif-judgment-modal">
       مذكرة تبليغ حكم
    </a>
  </li>
          <li><a href="#" data-bs-toggle="modal" data-bs-target="#manage-notifications-modal">ادارة تباليغ الدعوى</a></li>
        </ul>
      </li>
      <li>
        <a href="#" id="sessions-trigger">الجلسات ▾</a>
        <ul>
          <li><a onclick="openCourtScheduleModal()">جدول أعمال المحكمة</a></li>
          <li><a onclick="openJudgeScheduleModal()">جدول أعمال القاضي</a></li>
          <li><a onclick="openCaseScheduleModal()">جدول الدعوى</a></li>
          <li><a onclick="openRequestScheduleModal()">جدول الطلبات</a></li>
          <li><a onclick="openReportsListModal()">محاضر الجلسات</a></li>
        </ul>
      </li>
      <li><a href="#" data-bs-toggle="modal" data-bs-target="#participantsModal">المشاركين</a></li>
    </ul>
  </div>

  <form method="POST" action="<?php echo e(route('logout')); ?>" style="margin:0;">
    <?php echo csrf_field(); ?>
    <button type="submit" class="logout-btn">
      تسجيل الخروج
    </button>
  </form>
</nav>

<?php echo $__env->make('components.entry-search-bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>






<div class="modal fade" id="requestDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">تفاصيل الطلب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="requestDetailsBody">
                <p class="text-center text-secondary">جاري التحميل...</p>
            </div>

        </div>
    </div>
</div>
<!-- ⭐⭐⭐ نافذة عرض تفاصيل الدعوى ⭐⭐⭐ -->
<div class="modal fade" id="caseDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">تفاصيل الدعوى</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="caseDetailsBody">
                <p class="text-center text-secondary">جاري التحميل...</p>
            </div>

        </div>
    </div>
</div>





<div class="content">
    <?php echo $__env->yieldContent('content'); ?>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const triggerCases = document.getElementById('trigger-cases');

    triggerCases.addEventListener('mouseenter', () => {
        document.dispatchEvent(new Event('showWriterCasesMenu'));
    });

    triggerCases.addEventListener('mouseleave', () => {
        document.dispatchEvent(new Event('hideWriterCasesMenu'));
    });
});
</script>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const entryTypeRequest = document.getElementById("type_request");
    const entryInput = document.getElementById("entryNumberInput");

    if (!entryInput || !entryTypeRequest) return; // Exit if elements not found

    entryInput.addEventListener("keydown", function (e) {

        if (e.key === "Enter" && entryTypeRequest.checked) {

            const reqNumber = entryInput.value.trim();
            if (!reqNumber) {
                alert("الرجاء إدخال رقم الطلب");
                return;
            }

            openRequestDetails(reqNumber);
        }
    });

});

function openRequestDetails(requestNumber) {

    const modal = new bootstrap.Modal(document.getElementById("requestDetailsModal"));
    const body  = document.getElementById("requestDetailsBody");

    body.innerHTML = `<p class="text-center text-secondary">جاري التحميل...</p>`;
    modal.show();

    loadRequestDetails(requestNumber);
}



async function loadRequestDetails(requestNumber) {

    const body = document.getElementById("requestDetailsBody");

    try {
        const response = await axios.post("<?php echo e(route('chief.request.details')); ?>", {
            request_number: requestNumber
        });

        if (!response.data.success) {
            body.innerHTML = `<p class="text-danger text-center">⚠️ ${response.data.message}</p>`;
            return;
        }

        const r = response.data.request;

        body.innerHTML = `
            <table class="table table-bordered">

                <tr><th>رقم الطلب</th><td>${r.request_number}</td></tr>
                <tr><th>عنوان الطلب</th><td>${r.title ?? '-'}</td></tr>
                <tr><th>التاريخ الأصلي</th><td>${r.original_date ?? '-'}</td></tr>

                <tr><th>تاريخ الجلسة</th><td>${r.session_date ?? '-'}</td></tr>
                <tr><th>وقت الجلسة</th><td>${r.session_time ?? '-'}</td></tr>

                <tr><th>غرض الجلسة</th><td>${r.session_purpose ?? '-'}</td></tr>
                <tr><th>سبب الجلسة</th><td>${r.session_reason ?? '-'}</td></tr>

                <tr><th>القاضي</th><td>${r.judge_name ?? '-'}</td></tr>

            </table>

            <h6 class="mt-4">الأطراف</h6>

            <table class="table table-bordered">
                <tr><th>الصفة</th><th>الاسم</th></tr>

                ${r.plaintiff_name ? `<tr><td>مشتكي</td><td>${r.plaintiff_name}</td></tr>` : ''}
                ${r.defendant_name ? `<tr><td>مشتكى عليه</td><td>${r.defendant_name}</td></tr>` : ''}
                ${r.third_party_name ? `<tr><td>طرف ثالث</td><td>${r.third_party_name}</td></tr>` : ''}
                ${r.lawyer_name ? `<tr><td>محامي</td><td>${r.lawyer_name}</td></tr>` : ''}
            </table>
        `;

    } catch (error) {

        const msg = error.response?.data?.message ?? "خطأ غير معروف";

        body.innerHTML = `
            <p class="text-danger text-center">❌ خطأ أثناء تحميل البيانات — ${msg}</p>
        `;
    }
}
</script>













<script>
    //رقم الدعوى الشريط الثالث
// =============================
// استماع لزر Enter عند اختيار "دعوى"
// =============================
document.addEventListener("DOMContentLoaded", function () {

    const entryTypeCase = document.getElementById("type_case");
    const inputs = document.querySelectorAll('.third-bar input[type="text"]');
    const caseNumberInput = inputs[2]; // رقم الدعوى

    if (!caseNumberInput || !entryTypeCase) return; // Exit if elements not found

    caseNumberInput.addEventListener("keydown", function (e) {

        if (e.key === "Enter" && entryTypeCase.checked) {

            const caseNum = caseNumberInput.value.trim();

            if (!caseNum) {
                alert("الرجاء إدخال رقم الدعوى");
                return;
            }

            openCaseDetails(caseNum);
        }

    });

});


// =============================
//  فتح نافذة تفاصيل الدعوى
// =============================
function openCaseDetails(caseNumber) {

    const modal = new bootstrap.Modal(document.getElementById("caseDetailsModal"));
    const body  = document.getElementById("caseDetailsBody");

    body.innerHTML = `<p class="text-center text-secondary">جاري التحميل...</p>`;
    modal.show();

    loadCaseDetails(caseNumber);
}


// =============================
//  جلب بيانات الدعوى من السيرفر
// =============================
async function loadCaseDetails(caseNumber) {

    const body = document.getElementById("caseDetailsBody");

    try {
        const response = await axios.post("<?php echo e(route('chief.case.details')); ?>", {
            case_number: caseNumber
        });

        if (!response.data.success) {
            body.innerHTML = `<p class="text-danger text-center">${response.data.message}</p>`;
            return;
        }

        const c = response.data.case;

        let participantsHTML = "";
        c.participants.forEach(p => {
            participantsHTML += `
                <tr>
                    <td>${p.type}</td>
                    <td>${p.name}</td>
                    <td>${p.charge ?? '-'}</td>
                </tr>`;
        });

        let sessionsHTML = "";
        c.sessions.forEach(s => {
            sessionsHTML += `
                <tr>
                    <td>${s.id}</td>
                    <td>${s.time ?? '-'}</td>
                    <td>${s.date ?? '-'}</td>
                    <td>${s.reason ?? '-'}</td>
                    <td>${s.status ?? '-'}</td>
                </tr>`;
        });

        body.innerHTML = `
            <h6>معلومات الدعوى</h6>
            <table class="table table-bordered">
                <tr><th>رقم الدعوى</th><td>${c.number}</td></tr>
                <tr><th>عنوان الدعوى</th><td>${c.title}</td></tr>
                <tr><th>التاريخ الأصلي</th><td>${c.original_date}</td></tr>
            </table>

            <h6 class="mt-4">الجلسات</h6>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>رقم الجلسة</th>
                        <th>وقت الجلسة</th>
                        <th>تاريخ الجلسة</th>
                        <th>سبب الجلسة</th>
                        <th>حالة الجلسة</th>
                    </tr>
                </thead>
                <tbody>${sessionsHTML}</tbody>
            </table>

            <h6 class="mt-4">الأطراف</h6>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>الصفة</th>
                        <th>الاسم</th>
                        <th>التهمة</th>
                    </tr>
                </thead>
                <tbody>${participantsHTML}</tbody>
            </table>
        `;

    } catch (error) {

        console.error(error);

        body.innerHTML = `
            <p class="text-danger text-center">❌ خطأ أثناء تحميل البيانات</p>
        `;
    }
}
</script>




























<script>
const securityTrigger = document.getElementById('trigger-security');
const securityMenu = document.getElementById('security-menu');

if (securityTrigger && securityMenu) {
    securityTrigger.addEventListener('click', function() {
        if (securityMenu.style.display === 'none' || securityMenu.style.display === '') {
            securityMenu.style.display = 'block';
        } else {
            securityMenu.style.display = 'none';
        }
    });
}
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>

</body>
</html><?php /**PATH C:\Users\LENOVO\legal_system\resources\views/layouts/app.blade.php ENDPATH**/ ?>