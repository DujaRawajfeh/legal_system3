
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>صفحة الطابعة</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
<style>
@import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap');

body {
  font-family: "Cairo", sans-serif;
  background-color: #f8f9fa;
  margin: 0;
  padding: 0;
}

.court-bar {
  background-color: #717172;
  color: #fff;
  text-align: right;
  font-size: 1rem;
  padding: 12px 20px;
}

.navbar {
  background-color: #000;
  padding: 12px 20px;
  display: flex;
  align-items: center;
  font-weight: bold;
  font-size: small;
  gap: 40px;
}

.navbar .user-info { 
  color: white; 
  white-space: nowrap; 
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
  right: 0;
  top: 100%;
  background: #fff;
  border: 1px solid #ccc;
  min-width: 180px;
  z-index: 100;
  padding: 0;
  list-style: none;
}

.navbar ul li:hover > ul { 
  display: block; 
}

.navbar ul li ul li a {
  color: #000;
  display: block;
  padding: 6px 10px;
  text-decoration: none;
  white-space: nowrap;
}

.navbar ul li ul li a:hover { 
  background: #e7f1ff; 
}

.secondary-navbar {
  background-color: #f8f9fa;
  border-bottom: 1px solid #ddd;
  padding: 10px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.secondary-navbar form {
  display: flex;
  align-items: center;
  gap: 10px;
}

.secondary-navbar label { 
  margin: 0 5px; 
}

.secondary-navbar input[type="radio"] {
  margin: 0 5px;
}

.container-custom {
   width: 90%;
   max-width: 1200px;
   margin: 20px auto;
   padding: 25px;
   direction: rtl;
   text-align: right;
}

.cases-grid {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 40px;
}

.case-strip {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background-color: #fff;
  border: 1px solid #ccc;
  direction: rtl;
  border-radius: 8px;
  padding: 10px 15px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.08);
}

.case-info {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  text-align: right;
}

.case-info h3 {
  margin: 3px 0;
  color: #333;
  font-size: 15px;
}

.case-info p {
  margin: 3px 0;
  color: #555;
  font-size: 12px;
}

.case-actions {
  display: flex;
  flex-direction: column;
  gap: 6px;
  direction: ltr;
}

.action-btn {
  font-family: "Cairo", sans-serif;
  font-weight: bold;
  background-color: #37678e;
  border: none;
  color: white;
  cursor: pointer;
  transition: 0.2s;
  font-size: 10px;
  padding: 6px 10px;
  border-radius: 5px;
  white-space: nowrap;
}

.action-btn:hover {
  background-color: #2f5574;
}

#main-title {
  margin-top: 1px;
  margin-bottom: 0;
}

.title-line {
  border: none;
  height: 2px;
  background-color: #000;
  margin: 4px 0 15px 0;
  width: 100%;
}

.modal-header {
  background-color: #000;
  color: #fff;
}

.btn-close-white {
  filter: invert(1);
}
</style>
</head>
<body>

<div class="court-bar">محكمة بداية عمان</div>

<nav class="navbar">
  <div class="user-info">الطابعة / {{ Auth::user()->full_name ?? 'مستخدم' }}</div>
  <ul>
    <li><a href="#">الدعوى ▾</a>
      <ul>
        <li><a onclick="$('#judgmentModal').modal('show')">أحكام الدعوى</a></li>
        <li><a onclick="$('#setCaseSessionModal').modal('show')">تحديد جلسات الدعوى</a></li>
        <li><a onclick="$('#rescheduleSessionModal').modal('show')">إعادة تحديد جلسات الدعوى</a></li>
        <li><a onclick="$('#cancelSessionModal').modal('show')">إلغاء جلسات الدعوى</a></li>
      </ul>
    </li>
    <li><a href="#">الطلب ▾</a>
      <ul>
        <li><a onclick="openRequestSetSessionModal()">تحديد جلسات الطلبات</a></li>
        <li><a onclick="openRequestRescheduleModal()">إعادة تحديد جلسات الطلبات</a></li>
        <li><a onclick="openCancelRequestModal()">إلغاء جلسات الطلبات</a></li>
        <li><a onclick="openRequestJudgmentModal()">أحكام الطلبات</a></li>
      </ul>
    </li>
    <li><a href="#">الجلسات ▾</a>
      <ul>
        <li><a onclick="openCourtScheduleModal()">جدول أعمال المحكمة</a></li>
        <li><a onclick="$('#judgeScheduleModal').modal('show')">جدول أعمال القاضي</a></li>
        <li><a onclick="$('#caseScheduleModal').modal('show')">جدول الدعوى</a></li>
        <li><a onclick="$('#requestScheduleModal').modal('show')">جدول الطلبات</a></li>
      </ul>
    </li>
    <li><a href="{{ route('2fa.setup') }}" target="_blank">إعدادات الحماية</a></li>
  </ul>
</nav>

<div class="secondary-navbar">
  <form>
    <div>
      <input type="radio" id="request" name="entry_type" value="request" checked>
      <label for="request">طلب</label>
      <input type="radio" id="case" name="entry_type" value="case">
      <label for="case">دعوى</label>
    </div>
  </form>
</div>



<div class="container-custom">
  <section>
    <h2 id="main-title">القضايا التي يمكن متابعتها</h2>
    <hr class="title-line">
    
    {{-- عرض أسماء القضاة المرتبطين --}}
    @if(!empty($judgeNames))
        <p style="margin-bottom: 20px; font-weight: bold;">
            القاضي: {{ implode(' ، ', $judgeNames) }}
        </p>
    @else
        <p style="color: #999; text-align: center;">لا يوجد قضاة مرتبطون بهذه الطابعة.</p>
    @endif
    
    <div class="cases-grid">
        @forelse($cases as $case)
            @php 
                $session = $case->sessions->first(); 
            @endphp
            
            <div class="case-strip">
                <div class="case-info">
                    <h3>القضية رقم: {{ $case->number }}</h3>
                    <p><strong>عنوان الدعوى:</strong> {{ $case->type }}</p>
                    
                    @if($session)
                        <p><strong>تاريخ الجلسة:</strong> {{ $session->session_date }}</p>
                        <p><strong>حالة الجلسة:</strong> {{ $session->status }}</p>
                    @else
                        <p style="color: #999;">لا توجد جلسة محددة</p>
                    @endif
                </div>
                
                @if($session)
                    <div class="case-actions">
                        @if($session->status === 'محددة')
                            <a href="{{ route('trial.report', $session->id) }}" class="action-btn">محضر المحاكمة</a>
                        @elseif(in_array($session->status, ['مستمرة','مكتملة']))
                            <a href="{{ route('trial.report', $session->id) }}" class="action-btn">محضر المحاكمة</a>
                            <a href="{{ route('after.trial.report', $session->id) }}" class="action-btn">ما بعد</a>
                        @endif
                    </div>
                @endif
            </div>
        @empty
            <p style="color: #999; text-align: center; padding: 20px;">لا يوجد قضايا مرتبطة بأي قاضي.</p>
        @endforelse
    </div>
  </section>
</div>


<!-- ✅ هذا الكود يظهر قائمة الجلسات فقط إذا كان النوع المختار هو "دعوى" -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const trigger = document.getElementById('sessions-trigger');
    const menu = document.getElementById('sessions-menu-typist');

    // ✅ تحقق من وجود العناصر قبل إضافة المستمعات
    if (!trigger || !menu) {
        return;
    }

    function getCurrentType() {
        const selected = document.querySelector('input[name="entry_type"]:checked');
        return selected ? selected.value : null;
    }

    let isOverTrigger = false;
    let isOverMenu = false;

    trigger.addEventListener('mouseenter', function () {
        isOverTrigger = true;
        if (getCurrentType() === 'case') {
            menu.style.display = 'block';
        }
    });

    trigger.addEventListener('mouseleave', function () {
        isOverTrigger = false;
        setTimeout(() => {
            if (!isOverMenu) menu.style.display = 'none';
        }, 200);
    });

    menu.addEventListener('mouseenter', function () {
        isOverMenu = true;
    });

    menu.addEventListener('mouseleave', function () {
        isOverMenu = false;
        setTimeout(() => {
            if (!isOverTrigger) menu.style.display = 'none';
        }, 200);
    });

    const radios = document.querySelectorAll('input[name="entry_type"]');
    radios.forEach(radio => {
        radio.addEventListener('change', () => {
            menu.style.display = 'none';
        });
    });
});
</script>
<!-- 🔶 مودال جدول أعمال المحكمة -->
<div class="modal fade" id="courtScheduleModal" tabindex="-1" aria-labelledby="courtScheduleLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">جدول أعمال المحكمة</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <!-- 🔹 خيارات الفلترة -->
        <div class="row mb-3">
          
          <div class="col-md-6">
            <label class="form-label">تاريخ الجلسة:</label>
            <input type="date" id="courtScheduleDate" class="form-control">
          </div>

          <div class="col-md-6">
            <label class="form-label">حالة الجلسة:</label>
            <select id="courtScheduleStatus" class="form-select">
              <option value="">كل الحالات</option>
            </select>
          </div>

        </div>

        <div class="text-center mb-3">
          <button class="btn btn-primary" onclick="loadCourtSchedule()">بحث</button>
        </div>

        <!-- 🔹 جدول النتائج -->
        <div class="table-responsive">
          <table class="table table-bordered text-center">
            <thead class="table-light">
              <tr>
                <th>رقم الدعوى</th>
                <th>التاريخ</th>
                <th>الوقت</th>
                <th>نوع الجلسة</th>
                <th>حالة الجلسة</th>
                <th>اسم المحكمة</th>
                <th>اسم القاضي</th>
              </tr>
            </thead>
            <tbody id="courtScheduleTable">
              <tr><td colspan="7">لا توجد بيانات</td></tr>
            </tbody>
          </table>
        </div>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
      </div>

    </div>
  </div>
</div>

<script>

// فتح المودال
function openCourtScheduleModal() {
    const modal = new bootstrap.Modal(document.getElementById('courtScheduleModal'));
    modal.show();

    // تحميل الحالات فورًا
    loadSessionStatuses();
}


// ===========================================
// تحميل الحالات من المسار الصحيح
// ===========================================
function loadSessionStatuses() {
    fetch('/session-statuses-court')
        .then(res => res.json())
        .then(statuses => {
            const select = document.getElementById('courtScheduleStatus');
            select.innerHTML = '<option value="">كل الحالات</option>';

            statuses.forEach(s => {
                select.innerHTML += `<option value="${s}">${s}</option>`;
            });
        })
        .catch(() => {
            alert("تعذر تحميل حالات الجلسات");
        });
}


// ===========================================
// تحميل جدول المحكمة
// ===========================================
function loadCourtSchedule() {

    const params = {
        date: document.getElementById('courtScheduleDate').value,
        status: document.getElementById('courtScheduleStatus').value,
    };

    fetch('/court-schedule?' + new URLSearchParams(params))
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById("courtScheduleTable");
            tbody.innerHTML = "";

            if (!data.length) {
                tbody.innerHTML = `<tr><td colspan="7">لا يوجد جلسات مطابقة</td></tr>`;
                return;
            }

            data.forEach(item => {
                tbody.innerHTML += `
                    <tr>
                        <td>${item.case_number ?? '-'}</td>
                        <td>${item.date}</td>
                        <td>${item.time}</td>
                        <td>${item.session_type ?? '-'}</td>
                        <td>${item.status ?? '-'}</td>
                        <td>${item.tribunal_name ?? '-'}</td>
                        <td>${item.judge_name ?? '-'}</td>
                    </tr>
                `;
            });
        })
        .catch(err => {
            console.error(err);
            alert("حدث خطأ أثناء تحميل جدول المحكمة");
        });
}

</script>

<!-- 🔶 مودال جدول أعمال القاضي -->
<div class="modal fade" id="judgeScheduleModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">جدول أعمال القاضي</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <!-- 🔹 فلاتر -->
        <div class="row mb-4">

          <!-- اختيار القاضي -->
          <div class="col-md-6">
            <label class="form-label">اختر القاضي:</label>
            <select id="judgeSelect" class="form-select">
              <option value="">اختر قاضٍ</option>
            </select>
          </div>

          <!-- حالة الجلسة -->
          <div class="col-md-6">
            <label class="form-label">حالة الجلسة:</label>
            <select id="judgeSessionStatus" class="form-select">
              <option value="">كل الحالات</option>
              <option value="محددة">محددة</option>
              <option value="مستمرة">مستمرة</option>
              <option value="مكتملة">مكتملة</option>
              <option value="مؤجلة">مؤجلة</option>
            </select>
          </div>

        </div>

        <div class="text-center mb-3">
          <button class="btn btn-primary" onclick="loadJudgeSchedule()">عرض الجدول</button>
        </div>

        <!-- 🔹 جدول النتائج -->
        <div class="table-responsive">
          <table class="table table-bordered text-center">
            <thead class="table-light">
              <tr>
                <th>رقم الدعوى</th>
                <th>تاريخ الجلسة</th>
                <th>وقت الجلسة</th>
                <th>المحكمة</th>
                <th>نوع الجلسة</th>
                <th>حالة الجلسة</th>
                <th>السبب</th>
                <th>التاريخ الأصلي</th>
              </tr>
            </thead>
            <tbody id="judgeScheduleTable">
              <tr><td colspan="8">لا توجد بيانات</td></tr>
            </tbody>
          </table>
        </div>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
      </div>

    </div>
  </div>
</div>
<script>

/* ============================
   🔹 تحميل القضاة من السيرفر
============================ */
function loadJudges() {
    fetch('/judges')
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById("judgeSelect");
            select.innerHTML = '<option value="">اختر قاضٍ</option>';

            data.forEach(j => {
                select.innerHTML += `<option value="${j.id}">${j.full_name}</option>`;
            });
        })
        .catch(() => alert("تعذر تحميل قائمة القضاة"));
}


/* ====================================================
   🔹 تحميل القضاة تلقائيًا عند فتح مودال جدول القاضي
==================================================== */
document.getElementById("judgeScheduleModal")
    .addEventListener("shown.bs.modal", function () {
        loadJudges();
    });




/* ============================
   🔹 تحميل جدول أعمال القاضي
============================ */
function loadJudgeSchedule() {

    const params = {
        judge_id: document.getElementById("judgeSelect").value,
        status: document.getElementById("judgeSessionStatus").value,
    };

    fetch('/judge-schedule?' + new URLSearchParams(params))
        .then(res => res.json())
        .then(data => {

            const tbody = document.getElementById("judgeScheduleTable");
            tbody.innerHTML = "";

            if (!data.length) {
                tbody.innerHTML = `<tr><td colspan="8">لا توجد جلسات مطابقة</td></tr>`;
                return;
            }

            data.forEach(item => {
                tbody.innerHTML += `
                    <tr>
                        <td>${item.case_number ?? '-'}</td>
                        <td>${item.date}</td>
                        <td>${item.time}</td>
                        <td>${item.tribunal_name ?? '-'}</td>
                        <td>${item.session_type ?? '-'}</td>
                        <td>${item.status ?? '-'}</td>
                        <td>${item.reason ?? '-'}</td>
                        <td>${item.original_date ?? '-'}</td>
                    </tr>
                `;
            });

        })
        .catch(err => {
            console.error(err);
            alert("حدث خطأ أثناء تحميل جدول أعمال القاضي");
        });
}

</script>

<!--  مودال تحديد جلسات الدعوى -->
<style>
  #setCaseSessionModal .modal-body {
    background-color: #f4f4f4;
    padding: 25px;
  }
  
  #setCaseSessionModal .session-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    padding: 25px;
    margin-bottom: 20px;
  }
  
  #setCaseSessionModal h3 {
    text-align: center;
    margin-bottom: 20px;
    font-weight: bold;
  }
  
  #setCaseSessionModal label {
    font-weight: bold;
    margin-top: 10px;
    display: block;
    font-size: 14px;
    color: #333;
  }
  
  #setCaseSessionModal input, 
  #setCaseSessionModal textarea, 
  #setCaseSessionModal select {
    padding: 8px;
    border-radius: 8px;
    border: 1px solid #ccc;
    margin-top: 5px;
    width: 100%;
    transition: border-color 0.3s;
  }
  
  #setCaseSessionModal input:focus, 
  #setCaseSessionModal select:focus, 
  #setCaseSessionModal textarea:focus {
    outline: none;
    border-color: #37678e;
    box-shadow: 0 0 5px rgba(55,103,142,0.3);
  }
  
  #setCaseSessionModal input:disabled, 
  #setCaseSessionModal textarea:disabled, 
  #setCaseSessionModal select:disabled {
    background-color: #e9ecef;
  }
  
  #setCaseSessionModal .case-number-row {
    display: flex;
    gap: 10px;
    margin-top: 5px;
  }
  
  #setCaseSessionModal .case-number-row input {
    flex: 1;
  }
  
  #setCaseSessionModal table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }
  
  #setCaseSessionModal th, 
  #setCaseSessionModal td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: center;
  }
  
  #setCaseSessionModal th {
    background: #1e1e1e;
    color: white;
  }
  
  #setCaseSessionModal .session-block {
    margin-top: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 15px;
    background: #eef7ff;
  }
  
  #setCaseSessionModal .form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 12px;
  }
  
  #setCaseSessionModal .button-group {
    display: flex;
    gap: 10px;
    margin-top: 15px;
    justify-content: flex-start;
  }
  
  #setCaseSessionModal button {
    font-family: "Cairo", sans-serif;
    font-size: 13px;
    padding: 6px 14px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    background-color: #37678e;
    color: white;
    transition: background-color 0.3s;
  }
  
  #setCaseSessionModal button:hover:not(:disabled) {
    background-color: #28527a;
  }
  
  #setCaseSessionModal button:disabled {
    background-color: #999;
    cursor: not-allowed;
  }
  
  #setCaseSessionModal .search-btn {
    margin-top: 10px;
  }
</style>

<div class="modal fade" id="setCaseSessionModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">تحديد جلسات الدعوى</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="session-container">

          <!-- رقم الدعوى -->
          <label>رقم الدعوى</label>
          <div class="case-number-row">
            <input type="text" id="caseNumberInput" placeholder="أدخل رقم الدعوى" required>
            <input type="text" id="tribunalNumber" placeholder="رقم المحكمة" readonly>
            <input type="text" id="departmentNumber" placeholder="رقم القلم" readonly>
            <input type="text" id="caseYear" placeholder="السنة" readonly>
          </div>

          <button class="search-btn" onclick="loadCaseDetails()">عرض الدعوى</button>

          <!-- جدول تفاصيل الدعوى -->
          <h3 style="margin-top:25px;">تفاصيل الدعوى</h3>
          <table>
            <thead>
              <tr>
                <th>رقم الدعوى</th>
                <th>نوع الدعوى</th>
                <th>اسم القاضي</th>
                <th>الأطراف</th>
                <th>التاريخ الأصلي</th>
              </tr>
            </thead>
            <tbody id="caseDetailsTable">
              <tr><td colspan="5">لا يوجد دعوى بعد.</td></tr>
            </tbody>
          </table>

          <!-- تحديد جلسة جديدة -->
          <h3 style="margin-top:25px;">تحديد جلسة جديدة</h3>
          <div class="session-block">

            <div class="form-group">
              <label for="sessionDate">تاريخ الجلسة</label>
              <input type="date" id="sessionDate" disabled>
            </div>

            <div class="form-group">
              <label for="sessionTime">وقت الجلسة</label>
              <input type="time" id="sessionTime" disabled>
            </div>

            <div class="form-group">
              <label for="sessionGoal">سبب الجلسة</label>
              <textarea id="sessionGoal" placeholder="اكتب سبب الجلسة..." disabled></textarea>
            </div>

            <div class="form-group">
              <label for="sessionStatus">حالة الجلسة</label>
              <select id="sessionStatus" required disabled>
                <option value="مفصولة">مفصولة</option>
                <option value="مستمرة">مستمرة</option>
                <option value="مكتملة">مكتملة</option>
                <option value="مؤجلة">مؤجلة</option>
              </select>
            </div>

            <div class="form-group">
              <label for="judgmentType">نوع الحكم</label>
              <select id="judgmentType" required disabled>
                <option value="تدقيقيا">تدقيقيا</option>
                <option value="ابتدائي">ابتدائي</option>
                <option value="غيابي">غيابي</option>
                <option value="وجاهي">وجاهي</option>
              </select>
            </div>

            <div class="button-group">
              <button id="saveCaseSessionBtn" onclick="saveCaseSession()" disabled>حفظ الجلسة</button>
              <button type="button" data-bs-dismiss="modal">إغلاق</button>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>
</div>
@push('scripts')
<script>

/* ============================================================
   🔹 تحميل تفاصيل الدعوى
============================================================ */
function loadCaseDetails() {

    console.log("🔥 loadCaseDetails() called!");

    const caseNumber = document.getElementById("caseNumberInput").value;

        if (!caseNumber) {
            alert("يرجى إدخال رقم الدعوى");
            return;
        }

        console.log("📌 Fetching:", `/typist/case-details/${caseNumber}`);

        fetch(`/typist/case-details/${caseNumber}`)
            .then(res => {
                console.log("📌 Raw Response:", res);
                return res.json();
            })
            .then(data => {

                console.log("📌 Parsed JSON:", data);

                if (data.error) {
                    alert(data.error);
                    return;
                }

                if (!data.id) {
                    alert("⚠️ السيرفر لم يرجع ID — مشكلة في الكنترولر");
                    return;
                }

                // 🔥 تخزين المعرّفات
                window.selectedCaseId  = Number(data.id);
                window.selectedJudgeId = Number(data.judge_id);

                console.log("🔥 Stored selectedCaseId =", window.selectedCaseId);
                console.log("🔥 Stored selectedJudgeId =", window.selectedJudgeId);

                let participants = data.participants?.length
                    ? data.participants.map(p => `${p.type}: ${p.name}`).join("<br>")
                    : "-";

                // ✅ تعبئة الجدول
                document.getElementById("caseDetailsTable").innerHTML = `
                    <tr>
                        <td>${data.case_number}</td>
                        <td>${data.case_type ?? '-'}</td>
                        <td>${data.judge_name ?? '-'}</td>
                        <td>${participants}</td>
                        <td>${data.created_at}</td>
                    </tr>
                `;

                // ✅ تعبئة الحقول المقروءة فقط
                document.getElementById("tribunalNumber").value = data.tribunal_number ?? '-';
                document.getElementById("departmentNumber").value = data.department_number ?? '-';
                document.getElementById("caseYear").value = data.year ?? '-';

                // ✅ تفعيل حقول الجلسة
                document.getElementById("sessionDate").disabled = false;
                document.getElementById("sessionTime").disabled = false;
                document.getElementById("sessionGoal").disabled = false;
                document.getElementById("sessionStatus").disabled = false;
                document.getElementById("judgmentType").disabled = false;
                document.getElementById("saveCaseSessionBtn").disabled = false;

            })
        .catch(err => {
            console.error("❌ Fetch Error:", err);
            alert("حدث خطأ أثناء تحميل تفاصيل الدعوى");
        });
}


/* ============================================================
   🔹 حفظ الجلسة
============================================================ */
function saveCaseSession() {

    console.log("🔥 saveCaseSession() called!");

    // 🔥 فحص وصول المعرّفات
    if (!window.selectedCaseId) {
        alert("❌ لم يتم تحميل بيانات الدعوى بعد");
        return;
    }

    if (!window.selectedJudgeId) {
        alert("❌ لا يوجد قاضي مربوط بهذه الدعوى");
        return;
    }

    const sessionDate   = document.getElementById("sessionDate").value;
    const sessionTime   = document.getElementById("sessionTime").value;
    const sessionGoal   = document.getElementById("sessionGoal").value;
    const judgmentType  = document.getElementById("judgmentType").value;
    const sessionStatus = document.getElementById("sessionStatus").value;

    if (!sessionDate || !sessionTime || !sessionGoal) {
        alert("يرجى تعبئة جميع الحقول");
        return;
    }

    const payload = {
        court_case_id: window.selectedCaseId,
        judge_id: window.selectedJudgeId,
        session_date: `${sessionDate} ${sessionTime}:00`,
        session_time: sessionTime,
        session_goal: sessionGoal,
        judgment_type: judgmentType,
        status: sessionStatus
    };

    console.log("📤 Sending payload:", payload);

    fetch('/typist/set-session', {
        method: 'POST',
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(payload)
    })
    .then(res => {
        console.log("📥 Raw Response from save:", res);
        return res.json();
    })
    .then(data => {

        console.log("📥 Parsed JSON from save:", data);

        if (data.errors) {
            alert("هناك أخطاء في البيانات");
            console.log(data.errors);
            return;
        }

        alert(data.message);
    })
    .catch(err => {
        console.error("❌ Save Error:", err);
        alert("حدث خطأ أثناء حفظ الجلسة");
    });
}

</script>
@endpush


{{-- ✅ نافذة جدول الدعوى --}}
<div class="modal fade" id="caseScheduleModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">جدول الدعوى</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <div class="row mb-3">

          <div class="col-md-3">
            <label class="form-label">رقم المحكمة</label>
            <input type="text" id="cs_tribunal" class="form-control form-control-sm" value="---" readonly>
          </div>

          <div class="col-md-3">
            <label class="form-label">رقم القلم</label>
            <input type="text" id="cs_department" class="form-control form-control-sm" value="---" readonly>
          </div>

          <div class="col-md-3">
            <label class="form-label">السنة</label>
            <input type="text" class="form-control form-control-sm" value="{{ date('Y') }}" readonly>
          </div>

          <div class="col-md-3">
            <label class="form-label">رقم الدعوى</label>
            <input type="text" id="cs_case_number" class="form-control form-control-sm"
                   placeholder="أدخل رقم الدعوى">
          </div>

        </div>

        <div class="table-responsive mt-3">
          <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
              <tr>
                <th>تاريخ الجلسة</th>
                <th>وقت الجلسة</th>
                <th>نوع الحكم</th>
                <th>نوع الجلسة</th>
                <th>حالة الجلسة</th>
                <th>القاضي</th>
              </tr>
            </thead>

            <tbody id="cs_sessions_body">
              <tr><td colspan="6">يرجى إدخال رقم الدعوى لعرض الجلسات</td></tr>
            </tbody>

          </table>
        </div>

      </div>

      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-danger" onclick="closeCaseSchedule()">خروج</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
      </div>

    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('cs_case_number');
    const tbody = document.getElementById('cs_sessions_body');

    const tribunalInput = document.getElementById('cs_tribunal');
    const departmentInput = document.getElementById('cs_department');

    const caseScheduleUrlTemplate = @json(route('case.schedule', ['caseNumber' => 'CASE_NUMBER_PLACEHOLDER']));

    input.addEventListener('keydown', function (e) {
        if (e.key !== 'Enter') return;

        const caseNumber = input.value.trim();
        if (!caseNumber) {
            alert('يرجى إدخال رقم الدعوى');
            return;
        }

        const url = caseScheduleUrlTemplate.replace('CASE_NUMBER_PLACEHOLDER', encodeURIComponent(caseNumber));

        fetch(url)
            .then(response => response.json())
            .then(data => {

                tbody.innerHTML = '';

                if (data.error) {
                    tbody.innerHTML = `<tr><td colspan="6">${data.error}</td></tr>`;
                    tribunalInput.value = '---';
                    departmentInput.value = '---';
                    return;
                }

                tribunalInput.value = data.tribunal_number ?? '---';
                departmentInput.value = data.department_number ?? '---';

                if (!data.sessions || data.sessions.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6">لا توجد جلسات لهذه الدعوى</td></tr>';
                    return;
                }

                data.sessions.forEach(s => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${s.session_date ?? '---'}</td>
                            <td>${s.session_time ?? '---'}</td>
                            <td>${s.judgment_type ?? '---'}</td>
                            <td>${s.session_type ?? '---'}</td>
                            <td>${s.status ?? '---'}</td>
                            <td>${s.judge_name ?? '---'}</td>
                        </tr>
                    `;
                });

            })
            .catch(err => {
                console.error('❌ خطأ:', err);
                alert('حدث خطأ أثناء تحميل الجلسات');
            });

    });
});

function closeCaseSchedule() {
  const modalEl = document.getElementById('caseScheduleModal');
  const modal = bootstrap.Modal.getInstance(modalEl);
  if (modal) modal.hide();
}
</script>














































<style>
  /* 🔹 تحسين الترتيب */
  #caseScheduleModal .modal-body {
    max-height: 70vh;
    overflow-y: auto;
  }
  #caseScheduleModal .table th {
    white-space: nowrap;
  }
</style>


<!-- نافذة الأحكام -->
<!-- نافذة أحكام الدعوى -->

<!-- =========================== -->
<!-- 🔶 نافذة أحكام الدعوى -->
<!-- =========================== -->
<style>
  .judgment-modal .modal-body {
    background-color: #f4f6f8;
  }
  
  .judgment-modal .judgment-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    padding: 25px;
  }
  
  .judgment-modal .tabs, .judgment-modal .sub-tabs {
    display: flex;
    margin-bottom: 10px;
    border-bottom: 2px solid #ccc;
  }
  
  .judgment-modal .tab, .judgment-modal .sub-tab {
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 6px 6px 0 0;
    background-color: #eee;
    margin-left: 5px;
    font-weight: bold;
    transition: 0.3s;
    border: none;
  }
  
  .judgment-modal .tab.active, .judgment-modal .sub-tab.active {
    background-color: #0078d7;
    color: white;
  }
  
  .judgment-modal .tab-content, .judgment-modal .sub-tab-content {
    background-color: #fafafa;
    border: 1px solid #ccc;
    border-radius: 0 0 8px 8px;
    padding: 20px;
  }
  
  .judgment-modal .box {
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    background-color: #fdfdfd;
  }
  
  .judgment-modal textarea {
    width: 100%;
    height: 120px;
    border-radius: 6px;
    border: 1px solid #aaa;
    resize: none;
    padding: 10px;
    font-family: inherit;
  }
  
  .judgment-modal .form-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 20px;
    flex-wrap: wrap;
  }
  
  .judgment-modal .form-group {
    display: flex;
    flex-direction: column;
    margin: 5px;
  }
  
  .judgment-modal label {
    font-weight: bold;
    margin-bottom: 5px;
    color: #444;
  }
  
  .judgment-modal input, .judgment-modal select {
    padding: 8px;
    border: 1px solid #aaa;
    border-radius: 6px;
    box-sizing: border-box;
  }
  
  .judgment-modal .modal-footer button {
    font-family: "Cairo", sans-serif;
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: 0.2s;
    background-color: #000;
    color: white;
  }
  
  .judgment-modal .modal-footer button:hover {
    background-color: #333;
  }
</style>

<div class="modal fade judgment-modal" id="judgmentModal" tabindex="-1" aria-labelledby="judgmentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header" style="background-color: #f4f6f8; border-bottom: none;">
        <h5 class="modal-title" style="color: #333;">أحكام الدعوى</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="judgment-container">
          
          <!-- الفورم الأول -->
          <div class="form-row">
            <div class="form-group" style="flex-direction: row; align-items: center; gap: 10px;">
              <label style="margin-bottom: 0;">رقم الدعوى:</label>
              <input type="text" id="caseNumberInputJudgment" class="form-control" placeholder="أدخل الرقم واضغط Enter" style="width: 180px;">
              <button class="btn btn-primary" onclick="fetchCaseDataFromInput()" style="background-color: #0078d7; color: white;">بحث</button>
            </div>

            <div class="form-group">
              <label>تاريخ الحكم:</label>
              <input type="date" id="judgmentDate" class="form-control" style="width: 220px;">
            </div>

            <div class="form-group">
              <label>تاريخ الإغلاق:</label>
              <input type="date" id="closureDate" class="form-control" style="width: 220px;">
            </div>
          </div>

          <!-- التبويبات الرئيسية -->
          <div class="tabs">
            <div class="tab active" data-tab="tab1">الحكم ضد الأطراف</div>
            <div class="tab" data-tab="tab2">الحكم الفاصل</div>
            <div class="tab" data-tab="tab3">إسقاط الحق الشخصي</div>
          </div>

          <!-- التبويب الأول -->
          <div class="tab-content" id="tab1">
            <div class="box">
              <h3 style="font-size: 16px; margin-bottom: 15px;">أطراف الدعوى</h3>
              <div class="form-row">
                <div class="form-group">
                  <label>اسم الطرف:</label>
                  <select id="participantAgainst" class="form-select" style="width: 220px;">
                    <option value="">-- اختر الطرف --</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="sub-tabs">
              <div class="sub-tab active" data-sub="sub1">فصل التهمة</div>
              <div class="sub-tab" data-sub="sub2">الحكم</div>
              <div class="sub-tab" data-sub="sub3">تفاصيل التنفيذ</div>
            </div>

            <div class="sub-tab-content" id="sub1">
              <div class="box">
                <p>التهمة: <strong id="chargeText">—</strong></p>
                
                <br>
                <label>فصل التهمة:</label>
                <select id="chargeSplitType" class="form-select">
                  <option value="">اختر</option>
                  <option>إحالة الدعوى الجزائية</option>
                  <option>إدانة</option>
                  <option>إدانة - إعفاء من العقوبة</option>
                  <option>إدانة - وقف التنفيذ</option>
                  <option>إدانة و الحكم بالادعاء بالحق الشخصي</option>
                  <option>إسقاط بالعفو</option>
                  <option>إسقاط دعوى الحق العام</option>
                  <option>إسقاط للغياب</option>
                  <option>إعلان براءة</option>
                  <option>إعلان عدم مسؤولية</option>
                  <option>إيداع - المتهم في المركز الوطني للصحة النفسية</option>
                  <option>احالة</option>
                  <option>اسقاط الغرامة بالعفو العام والحكم بإزالة اسباب</option>
                  <option>اسقاط الغرامة بالعفو العام والحكم بالاغلاق</option>
                  <option>اسقاط بالتقادم</option>
                  <option>اسقاط بالعفو و الحكم بالادعاء بالحق الشخصي</option>
                  <option>اسقاط بالعفو و رد الادعاء بالحق الشخصي</option>
                  <option>افراج - الحدث</option>
                  <option>ايداع - الحدث</option>
                  <option>تعديل وصف التهمة</option>
                  <option>ضم قضية الى اخرى</option>
                  <option>وقف سير قضائي</option>
                  <option>وقف الملاحقة</option>
                </select>
              </div>
            </div>

            <div class="sub-tab-content" id="sub2" style="display:none;">
              <div class="box">
                <label>نص الحكم:</label>
                <textarea id="judgmentTextInput" placeholder="أدخل تفاصيل الحكم..."></textarea>
              </div>
            </div>

            <div class="sub-tab-content" id="sub3" style="display:none;">
              <div class="box">
                <label>تفاصيل التنفيذ:</label>
                <textarea id="executionDetailsInput" placeholder="أدخل تفاصيل التنفيذ..."></textarea>
              </div>
            </div>
          </div>

          <!-- التبويب الثاني - الحكم الفاصل -->
          <div class="tab-content" id="tab2" style="display:none;">
            <!-- كيفية انتهاء الدعوى -->
            <div class="box">
              <label>كيفية انتهاء الدعوى:</label>
              <select id="terminationType" class="form-select">
                <option value="">اختر</option>
                <option>احالة الى محكمة اخرى</option>
                <option>الفصل بالموضوع</option>
                <option>عدم اختصاص</option>
                <option>إسقاط الدعوى</option>
                <option>انسحاب الطرف</option>
                <option>رفض المحكمة</option>
              </select>
            </div>

            <!-- اختيار الطرف -->
            <div class="box">
              <label>اختر الطرف:</label>
              <select id="participantFinal" class="form-select">
                <option value="">-- اختر الطرف --</option>
              </select>
            </div>

            <!-- نوع الحكم -->
            <div class="box">
              <label>نوع الحكم:</label>
              <select id="judgmentType" class="form-select">
                <option value="">اختر</option>
                <option>وجاهي</option>
                <option>تدقيقا</option>
                <option>غيابي</option>
              </select>
            </div>

            <!-- خلاصة الحكم -->
            <div class="box">
              <label>خلاصة الحكم:</label>
              <textarea id="judgmentSummary" placeholder="أدخل خلاصة الحكم هنا..."></textarea>
            </div>
          </div>

          <!-- التبويب الثالث -->
          <div class="tab-content" id="tab3" style="display:none;">
            <div class="box">
              <label>نص اسقاط الحق الشخصي:</label>
              <textarea id="personalDropText" placeholder="أدخل نص إسقاط الحق الشخصي..."></textarea>
            </div>
          </div>

        </div>
      </div>

      <div class="modal-footer" style="background-color: #f4f6f8;">
        <button class="btn btn-secondary" onclick="saveJudgment()">حفظ الحكم</button>
        <button class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
      </div>

    </div>
  </div>
</div>

<input type="hidden" id="courtCaseId">
<script>
// ===========================
// 🔥 تهيئة سلوك التبويبات الرئيسية
// ===========================
const tabs = document.querySelectorAll('.judgment-modal .tab');
const contents = document.querySelectorAll('.judgment-modal .tab-content');
tabs.forEach(tab => {
  tab.addEventListener('click', () => {
    tabs.forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    contents.forEach(c => c.style.display = 'none');
    document.getElementById(tab.dataset.tab).style.display = 'block';
  });
});

// ===========================
// 🔥 تهيئة التبويبات الفرعية
// ===========================
const subTabs = document.querySelectorAll('.judgment-modal .sub-tab');
const subContents = document.querySelectorAll('.judgment-modal .sub-tab-content');
subTabs.forEach(tab => {
  tab.addEventListener('click', () => {
    const parent = tab.parentElement;
    const container = parent.parentElement;
    const tabsInContainer = container.querySelectorAll('.sub-tab');
    const contentsInContainer = container.querySelectorAll('.sub-tab-content');
    tabsInContainer.forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    contentsInContainer.forEach(c => c.style.display = 'none');
    const targetId = tab.dataset.sub;
    const target = container.querySelector('#' + targetId);
    if (target) target.style.display = 'block';
  });
});

// ===========================
// 🔥 جلب بيانات الدعوى
// ===========================
function fetchCaseData(caseNumber) {
    fetch(`/judgment/${caseNumber}`)
        .then(res => res.json())
        .then(data => {
            if (data.error) return alert(data.error);

            window.loadedParticipants = data.participants || [];
            document.getElementById('courtCaseId').value = data.case.id;

            const selects = [document.getElementById('participantAgainst'), document.getElementById('participantFinal')];

            selects.forEach(sel => {
                sel.innerHTML = '<option value="">-- اختر الطرف --</option>';
                window.loadedParticipants.forEach(p => {
                    sel.innerHTML += `<option value="${p.id}">${p.type} - ${p.name}</option>`;
                });
            });
        });
}

// ===========================
// 🔥 زر البحث
// ===========================
function fetchCaseDataFromInput() {
    const caseNumber = document.getElementById('caseNumberInputJudgment').value.trim();
    if (!caseNumber) {
        alert('الرجاء إدخال رقم الدعوى');
        return;
    }
    fetchCaseData(caseNumber);
}

// ===========================
// 🔥 اختيار طرف → التهمة
// ===========================
document.addEventListener("change", function(e) {
    if (e.target.id === "participantAgainst") {
        const id = e.target.value;
        const p = window.loadedParticipants?.find(x => x.id == id);
        document.getElementById('chargeText').textContent = p ? (p.charge || "—") : "—";
    }
});

// ===========================
// 🔥 زر الحفظ النهائي
// ===========================
function saveJudgment() {
    const payload = {
        court_case_id: document.getElementById('courtCaseId').value,
        participant_id:
            document.getElementById('participantAgainst').value ||
            document.getElementById('participantFinal').value ||
            null,
        judgment_date: document.getElementById('judgmentDate').value,
        closure_date: document.getElementById('closureDate').value,
        charge_split_type: document.getElementById('chargeSplitType')?.value,
        charge_text: document.getElementById('judgmentTextInput')?.value,
        execution_details: document.getElementById('executionDetailsInput')?.value,
        termination_type: document.getElementById('terminationType')?.value,
        judgment_type: document.getElementById('judgmentType')?.value,
        judgment_summary: document.getElementById('judgmentSummary')?.value,
        personal_drop_text: document.getElementById('personalDropText')?.value,
    };

    fetch("/typist/judgment/save", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            alert("خطأ: " + data.error);
        } else {
            alert(data.message || "تم الحفظ بنجاح");
        }
    })
    .catch(() => alert("❌ فشل الحفظ"));
}

// ===========================
// Enter لتحميل الدعوى
// ===========================
document.getElementById('caseNumberInputJudgment').addEventListener("keydown", function(e) {
    if (e.key === "Enter") fetchCaseDataFromInput();
});
</script>



















<!-- نافذه إعادة تحديد الجلسات-->
<!-- نافذة إعادة التحديد -->
<div class="modal fade" id="rescheduleSessionModal" tabindex="-1" aria-labelledby="rescheduleSessionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <div class="w-100 d-flex justify-content-between align-items-center">
          <h5 class="modal-title">إعادة تحديد جلسات الدعوى</h5>
          <!-- ✅ إضافة معلومات رأس الصفحة -->
          <div class="text-end">
            <span class="me-3 fw-bold">رقم المحكمة: <span id="rescheduleTribunalNumber">-</span></span>
            <span class="me-3 fw-bold">رقم القلم: <span id="rescheduleDepartmentNumber">-</span></span>
            <span class="fw-bold">السنة: <span id="rescheduleCaseYear">-</span></span>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>

      <div class="modal-body">

        <!-- إدخال رقم الدعوى -->
        <div class="mb-3">
          <label>رقم الدعوى:</label>
          <input type="text" id="caseNumberInputReschedule" class="form-control" placeholder="أدخل رقم الدعوى واضغط Enter">
        </div>

        <!-- جدول تفاصيل الدعوى -->
        <div id="caseDetailsTableReschedule" class="mb-4">
          <table class="table table-bordered table-sm text-center">
            <thead class="table-light">
              <tr>
                <th>رقم الدعوى</th>
                <th>نوع الدعوى</th>
                <th>القاضي</th>
                <th>الأطراف</th>
                <th>التاريخ الأصلي</th>
              </tr>
            </thead>
            <tbody id="caseDetailsBodyReschedule">
              <tr><td colspan="5">لا توجد بيانات</td></tr>
            </tbody>
          </table>
        </div>

        <!-- تفاصيل الجلسة القديمة -->
        <div id="oldSessionDetails" class="mb-4">
          <h6 class="fw-bold">الجلسة القديمة</h6>
          <table class="table table-bordered table-sm text-center">
            <thead class="table-light">
              <tr>
                <th>التاريخ</th>
                <th>الوقت</th>
                <th>السبب</th>
                <th>إجراء</th>
              </tr>
            </thead>
            <tbody id="oldSessionBody">
              <tr><td colspan="4">لا توجد بيانات</td></tr>
            </tbody>
          </table>
        </div>

        <!-- نموذج الجلسة الجديدة -->
        <div id="newSessionForm">
          <h6 class="fw-bold">إدخال الجلسة الجديدة</h6>
          <div class="row g-3">
            <div class="col-md-4">
              <label>تاريخ الجلسة:</label>
              <input type="date" id="newSessionDate" class="form-control">
            </div>
            <div class="col-md-4">
              <label>وقت الجلسة:</label>
              <input type="time" id="newSessionTime" class="form-control">
            </div>
            <div class="col-md-4">
              <label>سبب الجلسة:</label>
              <input type="text" id="newSessionGoal" class="form-control" placeholder="سبب الجلسة">
            </div>
          </div>

          <div class="row g-3 mt-2">
            <div class="col-md-4">
              <label>نوع الحكم:</label>
              <select id="newJudgmentType" class="form-control">
                <option value="تدقيقيا">تدقيقيا</option>
                <option value="ابتدائي">ابتدائي</option>
                <option value="غيابي">غيابي</option>
                <option value="وجاهي">وجاهي</option>
              </select>
            </div>

            <div class="col-md-4">
              <label>حالة الجلسة:</label>
              <select id="newSessionStatus" class="form-control">
                <option value="مفصولة">مفصولة</option>
                <option value="مستمرة">مستمرة</option>
                <option value="مكتملة">مكتملة</option>
                <option value="مؤجلة">مؤجلة</option>
              </select>
            </div>
          </div>

          <div class="mt-3 text-center">
            <button class="btn btn-primary" onclick="rescheduleSession()">إعادة التحديد</button>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<script>
let currentCaseId = null;
let currentJudgeId = null;
let currentSessionId = null;

/* ===============================
   🔹 عند إدخال رقم الدعوى والضغط Enter
================================= */
document.getElementById('caseNumberInputReschedule').addEventListener('keypress', function (e) {
  if (e.key === 'Enter') {
    e.preventDefault();
    const caseNumber = this.value.trim();
    if (caseNumber) {
      fetchCaseDetailsAndSession(caseNumber);
    }
  }
});

/* ===============================
   🔹 جلب تفاصيل الدعوى + الجلسة القديمة
================================= */
function fetchCaseDetailsAndSession(caseNumber) {
  fetch(`/typist/case-details/${caseNumber}`)
    .then(res => res.json())
    .then(caseData => {
      currentCaseId = caseData.id;
      currentJudgeId = caseData.judge_id;
      renderCaseDetails(caseData);

      // ✅ تعبئة رأس النافذة
      document.getElementById("rescheduleTribunalNumber").textContent   = caseData.tribunal_number ?? '-';
      document.getElementById("rescheduleDepartmentNumber").textContent = caseData.department_number ?? '-';
      document.getElementById("rescheduleCaseYear").textContent         = caseData.year ?? '-';

      fetchOldSession(caseNumber);
    })
    .catch(() => alert('❌ رقم الدعوى غير موجود'));
}

/* ===============================
   🔹 عرض تفاصيل الدعوى في الجدول
================================= */
function renderCaseDetails(caseData) {
  const tbody = document.getElementById('caseDetailsBodyReschedule');
  const participants = caseData.participants?.length
    ? caseData.participants.map(p => `${p.type}: ${p.name}`).join('<br>')
    : '-';

  tbody.innerHTML = `
    <tr>
      <td>${caseData.case_number}</td>
      <td>${caseData.case_type ?? '-'}</td>
      <td>${caseData.judge_name ?? '-'}</td>
      <td>${participants}</td>
      <td>${caseData.created_at ?? '-'}</td>
    </tr>
  `;
}

/* ===============================
   🔹 جلب الجلسة القديمة
================================= */
function fetchOldSession(caseNumber) {
  fetch(`/typist/get-session/${caseNumber}`)
    .then(res => res.json())
    .then(session => {
      currentSessionId = session.id;
      renderOldSession(session);
    })
    .catch(() => {
      document.getElementById('oldSessionBody').innerHTML = `
        <tr><td colspan="4" class="text-center text-muted">لا توجد جلسة محددة</td></tr>
      `;
    });
}

/* ===============================
   🔹 عرض الجلسة القديمة
================================= */
function renderOldSession(session) {
  const tbody = document.getElementById('oldSessionBody');
  tbody.innerHTML = `
    <tr>
      <td>${session.session_date}</td>
      <td>${session.session_time}</td>
      <td>${session.session_goal}</td>
      <td><button class="btn btn-danger btn-sm" onclick="deleteOldSession()">حذف</button></td>
    </tr>
  `;
}

/* ===============================
   🔹 حذف الجلسة القديمة
================================= */
function deleteOldSession() {
  fetch(`/typist/delete-case-session/${currentSessionId}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
  })
    .then(res => res.json())
    .then(() => {
      alert('✅ تم حذف الجلسة');
      document.getElementById('oldSessionBody').innerHTML = `
        <tr><td colspan="4" class="text-center text-success">تم حذف الجلسة</td></tr>
      `;
    })
    .catch(() => alert('❌ فشل حذف الجلسة'));
}

/* ===============================
   🔹 حفظ الجلسة الجديدة (مع نوع الحكم + حالة الجلسة)
================================= */
function rescheduleSession() {
  const date = document.getElementById('newSessionDate').value;
  const time = document.getElementById('newSessionTime').value;
  const goal = document.getElementById('newSessionGoal').value;

  const judgmentType = document.getElementById('newJudgmentType').value;
  const sessionStatus = document.getElementById('newSessionStatus').value;

  if (!date || !time || !goal) {
    alert('❌ يرجى تعبئة جميع الحقول');
    return;
  }

  const payload = {
    court_case_id: currentCaseId,
    judge_id: currentJudgeId,
    session_date: `${date} ${time}:00`,
    session_time: time,
    session_goal: goal,
    judgment_type: judgmentType,
    status: sessionStatus,
    end: false
  };

  fetch('/typist/set-session', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify(payload)
  })
    .then(res => res.json())
    .then(data => {
      alert(data.message || '✅ تم إعادة تحديد الجلسة بنجاح');
    })
    .catch(() => alert('❌ فشل حفظ الجلسة الجديدة'));
}
</script>
























<!-- نافذة إلغاء الجلسة -->
<div class="modal fade" id="cancelSessionModal" tabindex="-1" aria-labelledby="cancelSessionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div class="w-100">
          <h5 class="modal-title mb-3">إلغاء جلسات الدعوى</h5>
          <div class="row g-3">
            <div class="col-md-3">
              <label>رقم المحكمة:</label>
              <input type="text" id="tribunalNumberCancel" class="form-control" disabled>
            </div>
            <div class="col-md-3">
              <label>رقم القلم:</label>
              <input type="text" id="departmentNumberCancel" class="form-control" disabled>
            </div>
            <div class="col-md-3">
              <label>السنة:</label>
              <input type="text" id="caseYearCancel" class="form-control" disabled>
            </div>
            <div class="col-md-3">
              <label>رقم الدعوى:</label>
              <input type="text" id="caseNumberInputCancel" class="form-control" placeholder="أدخل رقم الدعوى واضغط Enter">
            </div>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>

      <div class="modal-body">

        <!-- جدول تفاصيل الدعوى -->
        <div id="caseDetailsTableCancel" class="mb-4">
          <table class="table table-bordered table-sm">
            <thead class="table-light">
              <tr>
                <th>رقم الدعوى</th>
                <th>نوع الدعوى</th>
                <th>القاضي</th>
                <th>الأطراف</th>
                <th>التاريخ الأصلي</th>
              </tr>
            </thead>
            <tbody id="caseDetailsBodyCancel">
              <!-- يتم تعبئته من JavaScript -->
            </tbody>
          </table>
        </div>

        <!-- تفاصيل الجلسة الحالية -->
        <div id="cancelSessionDetails">
          <h6>موعد الجلسة</h6>
          <table class="table table-bordered table-sm">
            <thead class="table-light">
              <tr>
                <th>التاريخ</th>
                <th>الوقت</th>
                <th>السبب</th>
                <th>إجراء</th>
              </tr>
            </thead>
            <tbody id="cancelSessionBody">
              <!-- يتم تعبئته من JavaScript -->
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>
<script>
  let cancelCaseId = null;
let cancelSessionId = null;

// إدخال رقم الدعوى
document.getElementById('caseNumberInputCancel').addEventListener('keypress', function (e) {
  if (e.key === 'Enter') {
    e.preventDefault();
    const caseNumber = this.value.trim();
    if (caseNumber) {
      fetchCancelCaseDetails(caseNumber);
    }
  }
});

// ✅ جلب تفاصيل الدعوى
function fetchCancelCaseDetails(caseNumber) {
  fetch(`/typist/cancel-case-details/${caseNumber}`)
    .then(res => res.json())
    .then(caseData => {
      cancelCaseId = caseData.id;
      document.getElementById('tribunalNumberCancel').value = caseData.tribunal_number || '';
      document.getElementById('departmentNumberCancel').value = caseData.department_number || '';
      document.getElementById('caseYearCancel').value = caseData.year || '';
      renderCancelCaseDetails(caseData);
      fetchCancelSession(caseNumber);
    })
    .catch(() => alert('❌ رقم الدعوى غير موجود'));
}

// ✅ عرض تفاصيل الدعوى
function renderCancelCaseDetails(caseData) {
  const tbody = document.getElementById('caseDetailsBodyCancel');
  const participants = caseData.participants.map(p => `${p.type}: ${p.name}`).join('<br>');
  tbody.innerHTML = `
    <tr>
      <td>${caseData.case_number}</td>
      <td>${caseData.case_type}</td>
      <td>${caseData.judge_name}</td>
      <td>${participants}</td>
      <td>${caseData.created_at}</td>
    </tr>
  `;
}

// ✅ جلب الجلسة الحالية
function fetchCancelSession(caseNumber) {
  fetch(`/typist/cancel-session/${caseNumber}`)
    .then(res => res.json())
    .then(session => {
      cancelSessionId = session.id;
      renderCancelSession(session);
    })
    .catch(() => {
      document.getElementById('cancelSessionBody').innerHTML = `
        <tr><td colspan="4" class="text-center text-muted">لا توجد جلسة حالية</td></tr>
      `;
    });
}

// ✅ عرض الجلسة مع زر إلغاء
function renderCancelSession(session) {
  const tbody = document.getElementById('cancelSessionBody');
  tbody.innerHTML = `
    <tr>
      <td>${session.session_date}</td>
      <td>${session.session_time}</td>
      <td>${session.session_goal}</td>
      <td><button class="btn btn-danger btn-sm" onclick="cancelSession()">إلغاء الجلسة</button></td>
    </tr>
  `;
}

// ✅ حذف الجلسة
function cancelSession() {
  fetch(`/typist/cancel-session-delete/${cancelSessionId}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
  })
    .then(res => res.json())
    .then(data => {
      alert(data.message || '✅ تم إلغاء الجلسة');
      document.getElementById('cancelSessionBody').innerHTML = `
        <tr><td colspan="4" class="text-center text-success">✅ تم إلغاء الجلسة</td></tr>
      `;
    })
    .catch(() => alert('❌ فشل إلغاء الجلسة'));
}
</script>


























































































{{-- ✅ قائمة الطلبات الخاصة بالطابعة (تظهر عند المرور على الكلمة الموجودة في layouts.app) --}}
<div id="sessions-menu-request" class="position-absolute bg-white border rounded shadow-sm px-2 py-1"
     style="display: none; top: 38px; right: 12px; z-index: 1000; min-width: 220px;">
    <div class="dropdown-item" role="button" data-bs-toggle="modal" data-bs-target="#requestScheduleModal">جدول الطلبات</div>
    <div class="dropdown-item" onclick="openRequestSetSessionModal()">تحديد جلسات الطلبات</div>
    <div class="dropdown-item" onclick="openRequestRescheduleModal()">إعادة تحديد جلسات الطلبات</div>
    <div class="dropdown-item" onclick="openCancelRequestModal()">إلغاء جلسات الطلبات</div>
    <div class="dropdown-item" onclick="openRequestJudgmentModal()">أحكام الطلبات</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const trigger = document.getElementById('sessions-trigger');
    const menu = document.getElementById('sessions-menu-request');

    // ✅ تحقق من وجود العناصر قبل إضافة المستمعات
    if (!trigger || !menu) {
        return;
    }

    function getCurrentType() {
        const selected = document.querySelector('input[name="entry_type"]:checked');
        return selected ? selected.value : null;
    }

    let isOverTrigger = false;
    let isOverMenu = false;

    trigger.addEventListener('mouseenter', function () {
        isOverTrigger = true;
        if (getCurrentType() === 'request') {
            menu.style.display = 'block';
        }
    });

    trigger.addEventListener('mouseleave', function () {
        isOverTrigger = false;
        setTimeout(() => {
            if (!isOverMenu) menu.style.display = 'none';
        }, 200);
    });

    menu.addEventListener('mouseenter', function () {
        isOverMenu = true;
    });

    menu.addEventListener('mouseleave', function () {
        isOverMenu = false;
        setTimeout(() => {
            if (!isOverTrigger) menu.style.display = 'none';
        }, 200);
    });

    const radios = document.querySelectorAll('input[name="entry_type"]');
    radios.forEach(radio => {
        radio.addEventListener('change', () => {
            menu.style.display = 'none';
        });
    });
});
</script>
<!-- ✅ نافذة جدول الطلبات -->
<div class="modal fade" id="requestScheduleModal" tabindex="-1" aria-labelledby="requestScheduleLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <!-- رأس النافذة -->
      <div class="modal-header">
        <h5 class="modal-title" id="requestScheduleLabel">جدول الطلبات</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>

      <!-- جسم النافذة -->
      <div class="modal-body">

        <!-- معلومات المحكمة -->
        <div class="mb-3">
          <label class="form-label">رقم المحكمة:</label>
          <span id="courtNumber">-</span>
        </div>
        <div class="mb-3">
          <label class="form-label">القلم:</label>
          <span id="courtDesk">-</span>
        </div>
        <div class="mb-3">
          <label class="form-label">السنة:</label>
          <span id="courtYear">-</span>
        </div>

        <!-- إدخال رقم الطلب -->
        <div class="mb-4">
          <label for="requestNumberInput" class="form-label">رقم الطلب:</label>
          <input type="text" class="form-control" id="requestNumberInput" placeholder="أدخل رقم الطلب" onkeydown="if(event.key === 'Enter') fetchRequestSchedule()">
        </div>

        <!-- جدول الجلسات -->
        <div class="table-responsive">
          <table class="table table-bordered text-center">
            <thead class="table-light">
              <tr>
                <th>تاريخ الجلسة</th>
                <th>وقت الجلسة</th>
                <th>حالة الجلسة</th>
                <th>السبب</th>
                <th>التاريخ الأصلي</th>
                <th>القاضي</th>
              </tr>
            </thead>
            <tbody id="requestSessionsBody">
              <tr>
                <td colspan="6">-</td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>

      <!-- زر الإغلاق -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
      </div>

    </div>
  </div>
</div>

<script>
function fetchRequestSchedule() {
    const requestNumber = document.getElementById('requestNumberInput').value;

    if (!requestNumber) {
        alert('يرجى إدخال رقم الطلب');
        return;
    }

    fetch('/typist/request-schedule', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ request_number: requestNumber })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateScheduleTable(data.data);

            if (data.data.length > 0) {
                const first = data.data[0];
                document.getElementById('courtNumber').textContent = first.tribunal_number || '-';
                document.getElementById('courtDesk').textContent = first.department_number || '-';
                document.getElementById('courtYear').textContent = first.court_year || '-';
            }
        } else {
            alert('لم يتم العثور على بيانات');
        }
    })
    .catch(error => {
        console.error('خطأ في الجلب:', error);
        alert('حدث خطأ أثناء جلب البيانات');
    });
}

function updateScheduleTable(sessions) {
    const tbody = document.getElementById('requestSessionsBody');
    tbody.innerHTML = ''; // مسح المحتوى السابق

    if (sessions.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6">لا توجد جلسات لهذا الطلب</td></tr>`;
        return;
    }

    sessions.forEach(session => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${session.session_date || '-'}</td>
            <td>${session.session_time || '-'}</td>
            <td>${session.session_status || '-'}</td>
            <td>${session.session_reason || '-'}</td>
            <td>${session.original_date || '-'}</td>
            <td>${session.judge_name || '-'}</td>
        `;
        tbody.appendChild(row);
    });
}
</script>





<!-- ✅ نافذة تحديد جلسة الطلب -->
<!-- ✅ نافذة تحديد جلسة الطلب -->
<div class="modal fade" id="requestSetSessionModal" tabindex="-1" aria-labelledby="requestSetSessionLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="margin-top: 80px;">
    <div class="modal-content">

      <!-- رأس النافذة -->
      <div class="modal-header">
        <h5 class="modal-title" id="requestSetSessionLabel">تحديد جلسة الطلب</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>

      <!-- جسم النافذة -->
      <div class="modal-body">
        <!-- إدخال رقم الطلب -->
        <div class="mb-3">
          <label class="form-label">رقم الطلب:</label>
          <input type="text" class="form-control form-control-sm" id="request_session_number_input" placeholder="أدخل رقم الطلب واضغط Enter">
        </div>

        <form id="request-set-session-form" class="row g-3" method="POST" action="{{ route('typist.request.store-session') }}">
          @csrf
          <input type="hidden" name="id">

          <!-- جدول المحكمة -->
          <div class="col-12">
            <table class="table table-sm table-bordered">
              <tr>
                <th>رقم المحكمة</th>
                <td class="tribunal-number"></td>
                <th>رقم القلم</th>
                <td class="department-number"></td>
                <th>السنة</th>
                <td class="court-year"></td>
              </tr>
            </table>
          </div>

          <!-- جدول تفاصيل الطلب -->
          <div class="col-12">
            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th>رقم الدعوى</th>
                  <th>عنوان الطلب</th>
                  <th>المدعي</th>
                  <th>المدعى عليه</th>
                  <th>الطرف الثالث</th>
                  <th>التاريخ الأصلي</th>
                  <th>اسم القاضي</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="request-number"></td>
                  <td class="title"></td>
                  <td class="plaintiff"></td>
                  <td class="defendant"></td>
                  <td class="third-party"></td>
                  <td class="original-date"></td>
                  <td class="judge-name"></td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- نموذج تحديد الجلسة -->
          <div class="session-form-fields row g-3">
            <div class="col-md-6">
              <label class="form-label">تاريخ الجلسة:</label>
              <input type="date" class="form-control form-control-sm" name="session_date" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">وقت الجلسة:</label>
              <input type="time" class="form-control form-control-sm" name="session_time" required>
            </div>
            <div class="col-12">
              <label class="form-label">سبب الجلسة:</label>
              <textarea class="form-control form-control-sm" name="session_reason" rows="2" required></textarea>
            </div>
            <!-- ✅ إضافة حالة الجلسة -->
            <div class="col-md-6">
              <label class="form-label">حالة الجلسة:</label>
              <select class="form-control form-control-sm" name="session_status" required>
                  <option value="">-- اختر الحالة --</option>
                  <option value="مستمرة">مستمرة</option>
                  <option value="مفصولة">مفصولة</option>
                  <option value="مكتملة">مكتملة</option>
                  <option value="مؤجلة">مؤجلة</option>
              </select>
            </div>
          </div>

          <!-- إذا الجلسة محددة مسبقًا -->
          <div class="session-warning d-none col-12">
            <div class="alert alert-warning">
              تم تحديد جلسة مسبقًا لهذا الطلب:
              <span class="session-date"></span> - <span class="session-time"></span>
              <br>
              <strong>الحالة:</strong> <span class="session-status"></span>
            </div>
          </div>
        </form>
      </div>

      <!-- أزرار -->
      <div class="modal-footer d-flex justify-content-between">
        <div></div>
        <div class="session-buttons">
          <button type="submit" form="request-set-session-form" class="btn btn-primary btn-sm">حفظ الجلسة</button>
          <button type="submit" form="request-set-session-form" name="finish" value="1" class="btn btn-success btn-sm">حفظ وإنهاء</button>
        </div>
        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">خروج</button>
      </div>

    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

  const modalElement = document.getElementById('requestSetSessionModal');
  const modal = new bootstrap.Modal(modalElement);

  const form = modalElement.querySelector('#request-set-session-form');
  const sessionFields = modalElement.querySelector('.session-form-fields');
  const sessionWarning = modalElement.querySelector('.session-warning');
  const sessionButtons = modalElement.querySelector('.session-buttons');
  const requestInput = modalElement.querySelector('#request_session_number_input');

  // جلب التفاصيل
  function fetchAndFillRequestModal(requestNumber) {
    if (!requestNumber) return;

    fetch(`/typist/request/${requestNumber}/details`)
      .then(response => response.json())
      .then(data => {
        form.querySelector('[name="id"]').value = data.id;

        modalElement.querySelector('.tribunal-number').textContent = data.tribunal_number || '';
        modalElement.querySelector('.department-number').textContent = data.department_number || '';
        modalElement.querySelector('.court-year').textContent = data.court_year || '';

        modalElement.querySelector('.request-number').textContent = data.request_number || '';
        modalElement.querySelector('.title').textContent = data.title || '';
        modalElement.querySelector('.plaintiff').textContent = data.plaintiff || '';
        modalElement.querySelector('.defendant').textContent = data.defendant || '';
        modalElement.querySelector('.third-party').textContent = data.third_party || '';
        modalElement.querySelector('.original-date').textContent = data.original_date || '';
        modalElement.querySelector('.judge-name').textContent = data.judge_name || '';

        if (!data.session_date && !data.session_time) {
          sessionFields.classList.remove('d-none');
          sessionButtons.classList.remove('d-none');
          sessionWarning.classList.add('d-none');
        } else {
          sessionFields.classList.add('d-none');
          sessionButtons.classList.add('d-none');
          sessionWarning.classList.remove('d-none');

          modalElement.querySelector('.session-date').textContent = data.session_date;
          modalElement.querySelector('.session-time').textContent = data.session_time;
          modalElement.querySelector('.session-status').textContent = data.session_status || '';
        }

        // ✅ إذا الحالة موجودة مسبقًا، نملأ الـ select
        if (data.session_status) {
          form.querySelector('[name="session_status"]').value = data.session_status;
        }
      })
      .catch(err => console.error('Error:', err));
  }

  // enter key
  requestInput.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      fetchAndFillRequestModal(requestInput.value.trim());
    }
  });

  // فتح النافذة من القائمة
  window.openRequestSetSessionModal = function () {
    modal.show();
  };

});
</script>










<!-- نافذه إعادة تحديد جلسات الطلبات-->
 <div class="modal fade" id="requestRescheduleModal" tabindex="-1" aria-labelledby="requestRescheduleLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="margin-top: 80px;">
    <div class="modal-content">

      <!-- رأس -->
      <div class="modal-header">
        <h5 class="modal-title" id="requestRescheduleLabel">إعادة تحديد جلسة الطلب</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- جسم -->
      <div class="modal-body">
        <!-- إدخال رقم الطلب -->
        <div class="mb-3">
          <label class="form-label">رقم الطلب:</label>
          <input type="text" class="form-control form-control-sm" id="reschedule_request_number_input" placeholder="أدخل رقم الطلب واضغط Enter">
        </div>

        <form id="request-reschedule-session-form" class="row g-3" method="POST" action="{{ route('typist.request.reschedule-session') }}">
          @csrf
          <input type="hidden" name="id">

          <!-- جدول المحكمة -->
          <div class="col-12">
            <table class="table table-sm table-bordered">
              <tr>
                <th>رقم المحكمة</th>
                <td class="tribunal-number-res"></td>
                <th>رقم القلم</th>
                <td class="department-number-res"></td>
                <th>السنة</th>
                <td class="court-year-res"></td>
              </tr>
            </table>
          </div>

          <!-- جدول تفاصيل الطلب -->
          <div class="col-12">
            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th>رقم الدعوى</th>
                  <th>عنوان الطلب</th>
                  <th>المدعي</th>
                  <th>المدعى عليه</th>
                  <th>الطرف الثالث</th>
                  <th>التاريخ الأصلي</th>
                  <th>اسم القاضي</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="request-number-res"></td>
                  <td class="title-res"></td>
                  <td class="plaintiff-res"></td>
                  <td class="defendant-res"></td>
                  <td class="third-party-res"></td>
                  <td class="original-date-res"></td>
                  <td class="judge-name-res"></td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- الجلسة القديمة -->
          <div class="col-12">
            <div class="alert alert-secondary d-flex justify-content-between align-items-center">
              <div>
                <strong>الجلسة الحالية:</strong>
                <span class="session-date-res"></span> - <span class="session-time-res"></span>
                <br>
                <strong>الحالة:</strong> <span class="session-status-res"></span>
              </div>
              <button type="button" class="btn btn-danger btn-sm" id="delete_reschedule_session_button">حذف الجلسة القديمة</button>
            </div>
          </div>

          <!-- نموذج إعادة التحديد -->
          <div class="reschedule-fields row g-3">
            <div class="col-md-6">
              <label class="form-label">تاريخ جديد للجلسة:</label>
              <input type="date" class="form-control form-control-sm" name="session_date" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">وقت جديد للجلسة:</label>
              <input type="time" class="form-control form-control-sm" name="session_time" required>
            </div>
            <div class="col-12">
              <label class="form-label">سبب إعادة التحديد:</label>
              <textarea class="form-control form-control-sm" name="session_reason" rows="2" required></textarea>
            </div>
            <!-- ✅ إضافة حالة الجلسة -->
            <div class="col-md-6">
              <label class="form-label">حالة الجلسة:</label>
              <select class="form-control form-control-sm" name="session_status" required>
                  <option value="">-- اختر الحالة --</option>
                  <option value="مستمرة">مستمرة</option>
                  <option value="مفصولة">مفصولة</option>
                  <option value="مكتملة">مكتملة</option>
                  <option value="مؤجلة">مؤجلة</option>
              </select>
            </div>
          </div>

        </form>
      </div>

      <!-- أزرار -->
      <div class="modal-footer d-flex justify-content-between">
        <div></div>
        <div>
          <button type="button" id="save_reschedule_session_button" class="btn btn-primary btn-sm"> إعادة تحديد الجلسة</button>
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">خروج</button>
        </div>
      </div>

    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

  const modalElement = document.getElementById('requestRescheduleModal');
  const modal = new bootstrap.Modal(modalElement);

  const form = modalElement.querySelector('#request-reschedule-session-form');
  const requestInput = modalElement.querySelector('#reschedule_request_number_input');

  // فتح النافذة
  window.openRequestRescheduleModal = function () {
    modal.show();
    form.reset();
    modalElement.querySelectorAll('span').forEach(span => span.textContent = '');
  };

  // جلب التفاصيل
  function loadRescheduleDetails(requestNumber) {
    fetch(`/typist/reschedule/${requestNumber}/details`)
      .then(res => res.json())
      .then(data => {
        form.querySelector('[name="id"]').value = data.id;

        modalElement.querySelector('.tribunal-number-res').textContent = data.tribunal_number || '';
        modalElement.querySelector('.department-number-res').textContent = data.department_number || '';
        modalElement.querySelector('.court-year-res').textContent = data.court_year || '';

        modalElement.querySelector('.request-number-res').textContent = data.request_number || '';
        modalElement.querySelector('.title-res').textContent = data.title || '';
        modalElement.querySelector('.plaintiff-res').textContent = data.plaintiff || '';
        modalElement.querySelector('.defendant-res').textContent = data.defendant || '';
        modalElement.querySelector('.third-party-res').textContent = data.third_party || '';
        modalElement.querySelector('.original-date-res').textContent = data.original_date || '';
        modalElement.querySelector('.judge-name-res').textContent = data.judge_name || '';

        modalElement.querySelector('.session-date-res').textContent = data.session_date || 'غير محدد';
        modalElement.querySelector('.session-time-res').textContent = data.session_time || 'غير محدد';
        modalElement.querySelector('.session-status-res').textContent = data.session_status || 'غير محدد';

        // ✅ إذا الحالة موجودة مسبقًا، نملأ الـ select
        if (data.session_status) {
          form.querySelector('[name="session_status"]').value = data.session_status;
        }
      })
      .catch(err => console.error("خطأ:", err));
  }

  // عند Enter
  requestInput.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      if (requestInput.value.trim()) {
        loadRescheduleDetails(requestInput.value.trim());
      }
    }
  });

  // زر حذف الجلسة القديمة
  document.getElementById('delete_reschedule_session_button').addEventListener('click', function () {
    const requestId = form.querySelector('[name="id"]').value;
    if (!requestId) return;
    if (!confirm("هل أنت متأكد من حذف الجلسة؟")) return;

    fetch(`{{ route('typist.request.delete-session') }}`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ id: requestId })
    })
    .then(res => res.json())
    .then(data => {
      alert(data.success || "تم حذف الجلسة");
      modalElement.querySelector('.session-date-res').textContent = "";
      modalElement.querySelector('.session-time-res').textContent = "";
      modalElement.querySelector('.session-status-res').textContent = "";
    });
  });

  // ⭐ زر حفظ إعادة التحديد (AJAX)
  document.getElementById('save_reschedule_session_button').addEventListener('click', function () {
      const requestId = form.querySelector('[name="id"]').value;
      const sessionDate = form.querySelector('[name="session_date"]').value;
      const sessionTime = form.querySelector('[name="session_time"]').value;
      const sessionReason = form.querySelector('[name="session_reason"]').value;
      const sessionStatus = form.querySelector('[name="session_status"]').value;

      if (!requestId) {
          alert("رقم الطلب غير موجود");
          return;
      }

      fetch(`{{ route('typist.request.reschedule-session') }}`, {
          method: "POST",
          headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
              id: requestId,
              session_date: sessionDate,
              session_time: sessionTime,
              session_reason: sessionReason,
              session_status: sessionStatus   // ✅ إرسال الحالة الجديدة
          })
      })
      .then(res => res.json())
      .then(data => {
          alert(data.success || "تم حفظ موعد الجلسة الجديد");
          // ❗ إذا بدك النافذة تسكّر بعد الحفظ شغّلي هذا السطر:
          // modal.hide();
      })
      .catch(err => console.error("Error:", err));
  });

});
</script>


<!-- نافذة إلغاء جلسات الطلبات -->
<div class="modal fade" id="cancelRequestSessionModal" tabindex="-1" aria-labelledby="cancelRequestSessionLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="margin-top: 80px;">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="cancelRequestSessionLabel">إلغاء جلسة الطلب</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <!-- رقم الطلب -->
        <div class="mb-3">
          <label class="form-label">رقم الطلب:</label>
          <input type="text" class="form-control form-control-sm" id="cancelRequestNumberInput" placeholder="أدخل رقم الطلب واضغط Enter">
        </div>

        <form id="cancel-request-session-form" method="POST">
          @csrf
          <input type="hidden" name="id">

          <!-- بيانات المحكمة -->
          <table class="table table-sm table-bordered mb-3">
            <tr>
              <th>رقم المحكمة</th>
              <td class="tribunal-number-request"></td>

              <th>رقم القلم</th>
              <td class="department-number-request"></td>

              <th>السنة</th>
              <td class="court-year-request"></td>
            </tr>
          </table>

          <!-- تفاصيل الطلب -->
          <table class="table table-bordered table-sm mb-3">
            <thead>
              <tr>
                <th>رقم الطلب</th>
                <th>عنوان الطلب</th>
                <th>المدعي</th>
                <th>المدعى عليه</th>
                <th>الطرف الثالث</th>
                <th>التاريخ الأصلي</th>
                <th>اسم القاضي</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="request-number-request"></td>
                <td class="title-request"></td>
                <td class="plaintiff-request"></td>
                <td class="defendant-request"></td>
                <td class="third-party-request"></td>
                <td class="original-date-request"></td>
                <td class="judge-name-request"></td>
              </tr>
            </tbody>
          </table>

          <!-- الجلسة الحالية -->
          <div class="alert alert-warning d-flex justify-content-between align-items-center">
            <div>
              <strong>الجلسة الحالية:</strong>
              <span class="session-date-request"></span> -
              <span class="session-time-request"></span>
            </div>

            <button type="button" id="cancel-session-request-button" class="btn btn-danger btn-sm">
              إلغاء الجلسة
            </button>
          </div>

        </form>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">خروج</button>
      </div>

    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ⛔ أهم نقطة: نختار النافذة الصحيحة ID الجديد
    const modalElement = document.getElementById('cancelRequestSessionModal');
    const modal = new bootstrap.Modal(modalElement);

    const form = modalElement.querySelector('#cancel-request-session-form');
    const requestInput = modalElement.querySelector('#cancelRequestNumberInput');

    // ⭐ فتح النافذة من القائمة
    window.openCancelRequestModal = function () {
        modal.show();
        form.reset();

        // نفرغ جميع الحقول الخاصة بالعرض
        modalElement.querySelectorAll('td, span').forEach(el => el.textContent = '');
    };

    // ⭐ جلب التفاصيل عند كتابة رقم الطلب والضغط Enter
    requestInput.addEventListener('keypress', function (e) {
        if (e.key !== 'Enter') return;

        e.preventDefault();
        const requestNumber = requestInput.value.trim();
        if (!requestNumber) return;

        fetch(`/typist/cancel/${requestNumber}/details`)
            .then(response => {
                if (!response.ok) throw new Error('الطلب غير موجود');
                return response.json();
            })
            .then(data => {

                // تعبئة الحقول
                form.querySelector('[name="id"]').value = data.id ?? '';

                modalElement.querySelector('.tribunal-number-request').textContent   = data.tribunal_number ?? '';
                modalElement.querySelector('.department-number-request').textContent = data.department_number ?? '';
                modalElement.querySelector('.court-year-request').textContent        = data.court_year ?? '';

                modalElement.querySelector('.request-number-request').textContent = data.request_number ?? '';
                modalElement.querySelector('.title-request').textContent          = data.title ?? '';
                modalElement.querySelector('.plaintiff-request').textContent      = data.plaintiff ?? '';
                modalElement.querySelector('.defendant-request').textContent      = data.defendant ?? '';
                modalElement.querySelector('.third-party-request').textContent    = data.third_party ?? '';

                // ⭐ التاريخ الأصلي نعرضه من Created_at — backend لازم يرجعه الآن
                modalElement.querySelector('.original-date-request').textContent = data.original_date ?? '';

                modalElement.querySelector('.judge-name-request').textContent   = data.judge_name ?? '';

                modalElement.querySelector('.session-date-request').textContent = data.session_date ?? 'غير محدد';
                modalElement.querySelector('.session-time-request').textContent = data.session_time ?? 'غير محدد';

            })
            .catch(err => {
                console.error('فشل تحميل تفاصيل الطلب:', err);
                alert("❌ الطلب غير موجود");
            });
    });


    // ⭐ زر إلغاء الجلسة
    document.getElementById('cancel-session-request-button').addEventListener('click', function () {

        const requestId = form.querySelector('[name="id"]').value;
        if (!requestId) {
            alert("⚠️ الرجاء إدخال رقم الطلب أولاً");
            return;
        }

        if (!confirm("هل أنت متأكد من أنك تريد إلغاء الجلسة؟")) return;

        fetch(`{{ route('typist.request.cancel-session') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ id: requestId })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.success || "تم إلغاء الجلسة بنجاح");

            // إفراغ بيانات الجلسة فقط
            modalElement.querySelector('.session-date-request').textContent = '';
            modalElement.querySelector('.session-time-request').textContent = '';
        })
        .catch(error => {
            console.error('خطأ في إلغاء الجلسة:', error);
            alert("⚠️ حدث خطأ أثناء الإلغاء");
        });

    });

});
</script>


<!-- نافذة أحكام الطلب -->
<!-- نافذة أحكام الطلب -->
<div class="modal fade" id="requestJudgmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">أحكام الطلب</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- بيانات رأس الصفحة -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>رقم المحكمة</label>
                        <input type="text" id="tribunal_number_j" class="form-control" readonly>
                    </div>

                    <div class="col-md-3">
                        <label>رقم القلم</label>
                        <input type="text" id="department_number_j" class="form-control" readonly>
                    </div>

                    <div class="col-md-3">
                        <label>السنة</label>
                        <input type="text" id="court_year_j" class="form-control" readonly>
                    </div>

                    <div class="col-md-3">
                        <label>رقم الطلب</label>
                        <input type="text" id="request_number_j" class="form-control" placeholder="أدخل رقم الطلب">
                    </div>
                </div>

                <!-- تاريخ الحكم + الإغلاق -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>تاريخ الحكم</label>
                        <input type="date" id="judgment_date" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>تاريخ الإغلاق</label>
                        <input type="date" id="closure_date" class="form-control">
                    </div>
                </div>

                <hr>

                <!-- أزرار الاختيار -->
                <div class="d-flex gap-2 mb-3">
                    <button class="btn btn-outline-primary" id="btn_against_parties">الحكم ضد الأطراف</button>
                    <button class="btn btn-outline-secondary" id="btn_final_judgment">الحكم الفاصل</button>
                    <button class="btn btn-outline-danger" id="btn_waiver">إسقاط الحق الشخصي</button>
                </div>

                <!-- المنطقة الديناميكية -->
                <div id="dynamic_area"></div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-success" id="save_judgment">💾 حفظ الحكم</button>
                <button class="btn btn-danger" data-bs-dismiss="modal">إغلاق</button>
            </div>

        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // فتح نافذة أحكام الطلب
    window.openRequestJudgmentModal = function () {
        let modal = new bootstrap.Modal(document.getElementById('requestJudgmentModal'));
        modal.show();

        // تنظيف البيانات المخزنة مؤقتاً
        window.currentParties = null;
        window.textAgainst = {};
        window.textFinal = "";
        window.textWaiver = "";
        document.getElementById("dynamic_area").innerHTML = "";
    };



    // -------------------------------------------
    //   جلب بيانات الطلب والأطراف
    // -------------------------------------------
    function fetchRequestData(requestNumber) {
        axios.get("{{ route('typist.judgment.open') }}", {
            params: { request_number: requestNumber }
        })
        .then(response => {

            let data = response.data.request;

            document.getElementById('tribunal_number_j').value = data.tribunal.number;
            document.getElementById('department_number_j').value = data.department.number;
            document.getElementById('court_year_j').value = data.court_year;

            window.currentRequestId = data.id;

            // تخزين الأطراف
            window.currentParties = {
                plaintiff: data.plaintiff_name,
                defendant: data.defendant_name,
                third_party: data.third_party_name,
                lawyer: data.lawyer_name
            };

        })
        .catch(() => {
            alert("❌ لم يتم العثور على طلب بهذا الرقم");
        });
    }

    // عند الضغط Enter داخل خانة رقم الطلب
    document.getElementById('request_number_j').addEventListener('keydown', function(e){
        if (e.key === 'Enter') {
            fetchRequestData(this.value.trim());
        }
    });




    // -------------------------------------------
    //  🔵 الحكم ضد الأطراف
    // -------------------------------------------
    document.getElementById('btn_against_parties').addEventListener('click', function () {

        if (!window.currentParties) {
            alert("⚠ يرجى إدخال رقم الطلب والضغط Enter أولاً");
            return;
        }

        let p = window.currentParties;

        let dropdown = '';
        if (p.plaintiff)    dropdown += `<option value="plaintiff">${p.plaintiff}</option>`;
        if (p.defendant)    dropdown += `<option value="defendant">${p.defendant}</option>`;
        if (p.third_party)  dropdown += `<option value="third_party">${p.third_party}</option>`;
        if (p.lawyer)       dropdown += `<option value="lawyer">${p.lawyer}</option>`;

        // استرجاع النص المحفوظ للطرف المُختار إن وجد
        let savedText = "";
        const selectedParty = Object.keys(window.textAgainst)[0];
        if (selectedParty) savedText = window.textAgainst[selectedParty];

        document.getElementById('dynamic_area').innerHTML = `
            <label>اختر الطرف</label>
            <select id="selected_party" class="form-control mb-3">
                ${dropdown}
            </select>

            <label>نص الحكم</label>
            <textarea id="judgment_text" class="form-control" rows="4">${savedText || ''}</textarea>
        `;

        // عند تغيير الطرف — نرجّع النص المخزن
        setTimeout(() => {
            document.getElementById("selected_party").addEventListener("change", function () {
                let key = this.value;
                document.getElementById("judgment_text").value = window.textAgainst[key] || "";
            });

            document.getElementById("judgment_text").addEventListener("input", function () {
                let key = document.getElementById("selected_party").value;
                window.textAgainst[key] = this.value;
            });
        }, 100);

    });




    // -------------------------------------------
    // 🔵 الحكم الفاصل
    // -------------------------------------------
    document.getElementById('btn_final_judgment').addEventListener('click', function () {

        document.getElementById('dynamic_area').innerHTML = `
            <label>نص الحكم الفاصل</label>
            <textarea id="judgment_text_final" class="form-control" rows="4">${window.textFinal || ''}</textarea>
        `;

        setTimeout(() => {
            document.getElementById("judgment_text_final").addEventListener("input", function () {
                window.textFinal = this.value;
            });
        }, 100);

    });




    // -------------------------------------------
    // 🔵 إسقاط الحق الشخصي
    // -------------------------------------------
    document.getElementById('btn_waiver').addEventListener('click', function () {

        document.getElementById('dynamic_area').innerHTML = `
            <label>نص إسقاط الحق الشخصي</label>
            <textarea id="judgment_text_waiver" class="form-control" rows="4">${window.textWaiver || ''}</textarea>
        `;

        setTimeout(() => {
            document.getElementById("judgment_text_waiver").addEventListener("input", function () {
                window.textWaiver = this.value;
            });
        }, 100);

    });




    // -------------------------------------------
    // 🔵 زر الحفظ النهائي
    // -------------------------------------------
    document.getElementById('save_judgment').addEventListener('click', function () {

        axios.post("{{ route('typist.judgment.store') }}", {
            request_id: window.currentRequestId,
            judgment_date: document.getElementById('judgment_date').value,
            closure_date: document.getElementById('closure_date').value,

            text_against: window.textAgainst,
            text_final: window.textFinal,
            text_waiver: window.textWaiver,
        })
        .then(() => {
            alert("✔ تم حفظ الحكم بالكامل");
        })
        .catch(err => {
            console.error(err);
            alert("❌ حدث خطأ أثناء حفظ الحكم");
        });

    });

});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>








