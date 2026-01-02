<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title> صفحة القاضي </title>

    <style>
@import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap');

body {
  font-family: "Cairo", sans-serif;
  background-color: #f8f9fa;
  margin: 0;
  padding: 0;
  font-size: 13px;
}

/* الشريط العلوي للمحكمة */
.court-bar {
  background-color: #717172;
  color: #fff;
  text-align: right;
  font-size: 1rem;
  padding: 8px 20px;
}

/* الشريط الأسود للقاضي */
.judge-bar {
  padding: 6px 20px;
  font-weight: 600;
  font-size: 12px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #111;
  color: #fff;
  border-bottom: 2px solid #333;
}

/* القسم الأيسر للشريط */
.judge-bar .left-section {
  display: flex;
  align-items: center;
  gap: 15px;
}

.judge-bar .judge-name {
  font-weight: 700;
  font-size: 13px;
  white-space: nowrap;
  color: #fff;
}

/* روابط الحماية */
.judge-bar .nav-links {
  list-style: none;
  display: flex;
  margin: 0;
  padding: 0;
  gap: 10px;
}

.judge-bar .nav-links li {
  display: inline-block;
}

.judge-bar .security-link {
  color: #fff;
  text-decoration: none;
  font-weight: 600;
  padding: 4px 8px;
  border-radius: 5px;
  background-color: #222;
  transition: background 0.3s, color 0.3s, text-decoration 0.3s;
}

.judge-bar .security-link:hover {
  text-decoration: underline;
}

/* تبويبات الدعاوى والطلبات */
.judge-bar .nav-tabs {
  display: flex;
  gap: 6px;
  align-items: center;
  margin: 0;
  padding: 0;
  list-style: none;
}

.judge-bar .nav-tabs li {
  display: inline-block;
  margin: 0;
  padding: 0;
}

.judge-bar .nav-tabs li a {
  padding: 5px 12px;
  font-size: 11px;
  font-weight: 600;
  border-radius: 5px;
  background-color: #222;
  color: #fff;
  text-decoration: none;
  transition: 0.3s;
  border: 1px solid transparent;
}

.judge-bar .nav-tabs li a:hover {
  background-color: #37678e;
  border-color: #37678e;
}

.judge-bar .nav-tabs li a.active {
  background-color: #005f9e;
  border-color: #005f9e;
  color: #fff;
  font-weight: 700;
}

/* العناوين قبل الجداول */
h3 {
  margin: 10px 0 5px 0;
  padding: 5px 10px;
  font-size: 1.2rem;
  font-weight: 700;
  color: #000;
  border-bottom: 1px solid #000;
}

/* الجداول */
table {
  width: 98%;
  margin: 0 auto 20px auto;
  border-collapse: collapse;
  font-size: 15px;
  background-color: white;
  border-radius: 6px;
  overflow: hidden;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

th, td {
  padding: 6px 8px;
  text-align: right;
  border-bottom: 1px solid #ddd;
}

th {
  font-size: 12px;
  background-color: #000;
  color: white;
}

tr:hover {
  background-color: #f1f1f1;
}

button, .btn {
  font-size: 10px;
  font-family: "Cairo", sans-serif;
  padding: 3px 6px;
  background-color: #37678e;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: 0.2s;
  text-decoration: none;
  display: inline-block;
}

button:hover, .btn:hover {
  background-color: #61a7e0;
}

.container {
  max-width: 100%;
  padding: 0 10px;
}

.sessions {
  margin-top: 20px;
}
    </style>
</head>
<body>

<div class="court-bar">{{ $judge->tribunal->name ?? '-' }} / {{ $judge->department->name ?? '-' }}</div>

<nav class="judge-bar">
  <div class="left-section">
    <span class="judge-name">القاضي/ {{ $judge->full_name }}</span>
    <ul class="nav-links">
      <li><a href="{{ route('2fa.setup') }}" class="security-link" target="_self">اعدادات الحماية</a></li>
    </ul>
    <ul class="nav-tabs">
      <li><a href="#" class="active" onclick="showTab('casesTab', this)">الدعاوى</a></li>
      <li><a href="#" onclick="showTab('requestsTab', this)">الطلبات</a></li>
    </ul>
  </div>


  <form method="POST" action="{{ route('logout') }}" class="logout-form">
    @csrf
    <button type="submit" class="logout-btn">
        تسجيل الخروج
    </button>
</form>
</nav>



<!-- تبويبات المحتوى -->
<div class="container">
  <div id="casesTab">
    <section class="sessions">
      <h3>جلسات اليوم (<span id="todayDate">{{ date('Y-m-d') }}</span>)</h3>

      <table border="1" cellspacing="0" cellpadding="5">
        <thead>
          <tr>
            <th>رقم الدعوى</th>
            <th>عنوان الدعوى</th>
            <th>التاريخ الأصلي</th>
            <th>وقت الجلسة</th>
            <th>نوع الحكم</th>
            <th>الحالة</th>
            <th>سبب الجلسة</th>
          </tr>
        </thead>

        <tbody id="todaySessionsTable">
          @forelse ($sessions as $session)
            <tr>
              <td>{{ $session->courtCase->number ?? '-' }}</td>
              <td>{{ $session->courtCase->type ?? '-' }}</td>
              <td>{{ $session->courtCase->created_at->format('Y-m-d') }}</td>
              <td>{{ \Carbon\Carbon::parse($session->session_date)->format('H:i') }}</td>

              {{-- نوع الحكم --}}
              <td>{{ $session->judgment_type ?: 'لم يصدر حكم' }}</td>

              {{-- الحالة --}}
              <td>{{ $session->status }}</td>

              {{-- سبب الجلسة --}}
              <td>{{ $session->session_goal ?: 'لم يتم تحديد سبب الجلسة' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="7" style="text-align: center;">
                لا توجد جلسات اليوم
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
   </section>
  </div>
</div>
    

    <h3>القضايا المرتبطة بالقاضي</h3>
    <div style="margin: 10px auto; width: 98%;">
      <input type="text" id="searchCases" placeholder="بحث في القضايا (رقم الدعوى، نوع الدعوى، اسم الطرف، التهمة...)" 
             style="width: 100%; padding: 8px; font-family: Cairo; border: 1px solid #ddd; border-radius: 4px; font-size: 13px;">
    </div>
    <table border="1" cellspacing="0" cellpadding="5">
      <thead>
        <tr>
          <th>رقم الدعوى</th>
          <th>عنوان الدعوى</th>
          <th>نوع الطرف</th>
          <th>اسم الطرف</th>
          <th>التهمة</th>
          <th>مدة التوقيف</th>
          <th>سبب التوقيف</th>
          <th>تم الإفراج عنه</th>
          <th>مركز الإصلاح</th>
          <th>طريقة التبليغ</th>
          <th>تاريخ التبليغ</th>
          <th>محضر المحاكمة</th>
          <th>التاريخ الأصلي</th>
          <th>تاريخ / وقت الجلسة</th>
        </tr>
      </thead>
      <tbody id="casesTable">
        @php
          $previousCaseNumber = null;
          $caseRowCount = [];
          
          // First pass: count rows per case
          foreach ($cases as $case) {
            $caseRowCount[$case->number] = count($case->participants);
          }
        @endphp
        
        @forelse ($cases as $case)
          @foreach ($case->participants as $index => $participant)
            @php
              $memo = $case->arrestMemos->firstWhere('participant_name', $participant->name);
              $notification = $case->notifications->firstWhere('participant_name', $participant->name);
              $firstSession = $case->sessions->first();
              $isFirstParticipant = ($index === 0);
              $rowspan = $caseRowCount[$case->number];
            @endphp
            <tr>
              @if($isFirstParticipant)
                <td rowspan="{{ $rowspan }}">{{ $case->number }}</td>
                <td rowspan="{{ $rowspan }}">{{ $case->type }}</td>
              @endif
              <td>طرف {{ $index + 1 }} - {{ $participant->type }}</td>
              <td>{{ $participant->name }}</td>
              <td>{{ $participant->charge }}</td>
              <td>{{ $memo->detention_duration ?? '-' }}</td>
              <td>{{ $memo->detention_reason ?? '-' }}</td>
              <td>{{ $memo->released ?? '-' }}</td>
              <td>{{ $memo->detention_center ?? '-' }}</td>
              <td>{{ $notification->method ?? '-' }}</td>
              <td>{{ $notification && $notification->notified_at ? \Carbon\Carbon::parse($notification->notified_at)->format('Y-m-d') : '-' }}</td>
              @if($isFirstParticipant)
                <td rowspan="{{ $rowspan }}">
                  <div class="case-actions">
                    @if($firstSession)
                      @if(\App\Models\CourtSessionReport::where('case_session_id', $firstSession->id)->where('report_mode','trial')->exists())
                        <a href="{{ route('judge.trial.report', $firstSession->id) }}" class="btn action-btn">محضر المحاكمة</a>
                      @endif
                      @if(\App\Models\CourtSessionReport::where('case_session_id', $firstSession->id)->where('report_mode','after')->exists())
                        <a href="{{ route('judge.after.report', $firstSession->id) }}" class="btn action-btn">ما بعد</a>
                      @endif
                      @if(!\App\Models\CourtSessionReport::where('case_session_id',$firstSession->id)->exists())
                        <span style="color: #777;">لا يوجد محضر</span>
                      @endif
                    @else
                      <span style="color: #777;">لا يوجد جلسة</span>
                    @endif
                  </div>
                </td>
                <td rowspan="{{ $rowspan }}">{{ $case->created_at ? $case->created_at->format('Y-m-d') : '-' }}</td>
                <td rowspan="{{ $rowspan }}">{{ $firstSession ? \Carbon\Carbon::parse($firstSession->session_date)->format('Y-m-d H:i') : '-' }}</td>
              @endif
            </tr>
          @endforeach
        @empty
          <tr><td colspan="14" style="text-align: center;">لا توجد قضايا مرتبطة بهذا القاضي</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div id="requestsTab" style="display:none;">
    <section class="sessions">
      <h3>جلسات الطلبات</h3>
      <table border="1" cellspacing="0" cellpadding="5">
        <thead>
          <tr>
            <th>رقم الطلب</th>
            <th>عنوان الطلب</th>
            <th>التاريخ الأصلي</th>
            <th>وقت الجلسة</th>
            <th>نوع الجلسة</th>
            <th>حالة الجلسة</th>
            <th>سبب التأجيل</th>
          </tr>
        </thead>
        <tbody id="requestsSessionsTable">
          <tr><td colspan="7" style="text-align: center;">جاري التحميل...</td></tr>
        </tbody>
      </table>
    </section>

    <h3>الطلبات المرتبطة بالقاضي</h3>
    <div style="margin: 10px auto; width: 98%;">
      <input type="text" id="searchRequests" placeholder="بحث في الطلبات (رقم الطلب، العنوان، اسم الطرف، الحكم...)" 
             style="width: 100%; padding: 8px; font-family: Cairo; border: 1px solid #ddd; border-radius: 4px; font-size: 13px;">
    </div>
    <table border="1" cellspacing="0" cellpadding="5">
      <thead>
        <tr>
          <th>رقم الطلب</th>
          <th>عنوان الطلب</th>
          <th>نوع الطرف</th>
          <th>اسم الطرف</th>
          <th>تاريخ/وقت الجلسة</th>
          <th>تاريخ الحكم</th>
          <th>تاريخ الإغلاق</th>
          <th>الحكم ضد الأطراف</th>
          <th>الحكم الفاصل</th>
          <th>إسقاط الحق الشخصي</th>
        </tr>
      </thead>
      <tbody id="requestsTable">
        <tr><td colspan="10" style="text-align: center;">جاري التحميل...</td></tr>
      </tbody>
    </table>
  </div>

</div>

<!-- تحميل axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log("📌 Judge page JS loaded");
    loadTodayRequests();
    loadAllRequests();
});

// -------- جدول جلسات الطلبات اليوم --------
async function loadTodayRequests() {
    const body = document.getElementById("requestsSessionsTable");

    try {
        console.log("🔹 Calling: {{ route('judge.requests.today') }}");

        const response = await axios.get("{{ route('judge.requests.today') }}");
        console.log("✅ Today Requests Response:", response);

        const data = response.data.requests || [];

        if (!Array.isArray(data)) {
            body.innerHTML = `<tr><td colspan="7" style="text-align: center; color: #dc3545;">تنسيق البيانات غير متوقع من السيرفر</td></tr>`;
            return;
        }

        let html = "";
        data.forEach(r => {
            html += `
                <tr>
                    <td>${r.request_number || '-'}</td>
                    <td>${r.title || '-'}</td>
                    <td>${(r.created_at || '').toString().substring(0,10) || '-'}</td>
                    <td>${r.session_time || '-'}</td>
                    <td>${r.session_type || '-'}</td>
                    <td>${r.session_status || '-'}</td>
                    <td>${r.session_reason || '-'}</td>
                </tr>
            `;
        });

        body.innerHTML = html || `<tr><td colspan="7" style="text-align: center;">لا يوجد طلبات اليوم</td></tr>`;

    } catch (err) {
        console.error("❌ ERROR in loadTodayRequests:", err);
        const status  = err.response ? err.response.status : '؟';
        const message = err.message || 'خطأ غير معروف';
        body.innerHTML = `<tr><td colspan="7" style="text-align: center; color: #dc3545;">خطأ أثناء تحميل البيانات (status: ${status}) - ${message}</td></tr>`;
    }
}

// -------- جدول كل الطلبات + الأطراف + الأحكام --------
async function loadAllRequests() {
    const body = document.getElementById("requestsTable");

    try {
        console.log("🔹 Calling: {{ route('judge.requests.all') }}");

        const response = await axios.get("{{ route('judge.requests.all') }}");
        console.log("✅ All Requests Response:", response);

        const data = response.data.requests || [];

        if (!Array.isArray(data)) {
            body.innerHTML = `<tr><td colspan="10" style="text-align: center; color: #dc3545;">تنسيق البيانات غير متوقع من السيرفر</td></tr>`;
            return;
        }

        let html = "";
        let previousRequestNumber = null;
        let requestRowCounts = {};
        
        // First pass: count rows per request
        data.forEach(r => {
            const parties = [
                {label: 'مشتكي', name: r.plaintiff_name},
                {label: 'مشتكى عليه', name: r.defendant_name},
                {label: 'طرف ثالث', name: r.third_party_name},
                {label: 'محامي', name: r.lawyer_name},
            ].filter(p => p.name); // Only count parties with names
            
            requestRowCounts[r.request_number] = parties.length || 1;
        });

        data.forEach(r => {
            const parties = [
                {label: 'مشتكي',       name: r.plaintiff_name,   text: r.judgment_text_plaintiff},
                {label: 'مشتكى عليه',  name: r.defendant_name,   text: r.judgment_text_defendant},
                {label: 'طرف ثالث',    name: r.third_party_name, text: r.judgment_text_third_party},
                {label: 'محامي',        name: r.lawyer_name,      text: r.judgment_text_lawyer},
            ];

            const rowspan = requestRowCounts[r.request_number];
            let isFirstRow = (previousRequestNumber !== r.request_number);
            
            parties.forEach((p, index) => {
                const isFirstParty = (index === 0);
                
                html += '<tr>';
                
                if (isFirstRow && isFirstParty) {
                    html += `
                        <td rowspan="${rowspan}">${r.request_number || '-'}</td>
                        <td rowspan="${rowspan}">${r.title || '-'}</td>
                    `;
                }
                
                html += `
                    <td>${p.label || '-'}</td>
                    <td>${p.name || '-'}</td>
                `;
                
                if (isFirstRow && isFirstParty) {
                    html += `
                        <td rowspan="${rowspan}">${r.session_date && r.session_time ? r.session_date + ' / ' + r.session_time : '-'}</td>
                        <td rowspan="${rowspan}">${r.judgment_date || '-'}</td>
                        <td rowspan="${rowspan}">${r.closure_date || '-'}</td>
                    `;
                }
                
                html += `
                    <td>${p.text || '-'}</td>
                `;
                
                if (isFirstRow && isFirstParty) {
                    html += `
                        <td rowspan="${rowspan}">${r.judgment_text_final || '-'}</td>
                        <td rowspan="${rowspan}">${r.judgment_text_waiver || '-'}</td>
                    `;
                }
                
                html += '</tr>';
            });
            
            previousRequestNumber = r.request_number;
        });

        body.innerHTML = html || `<tr><td colspan="10" style="text-align: center;">لا توجد طلبات</td></tr>`;

    } catch (err) {
        console.error("❌ ERROR in loadAllRequests:", err);
        const status  = err.response ? err.response.status : '؟';
        const message = err.message || 'خطأ غير معروف';
        body.innerHTML = `<tr><td colspan="10" style="text-align: center; color: #dc3545;">خطأ أثناء تحميل البيانات (status: ${status}) - ${message}</td></tr>`;
    }
}

// التبديل بين التبويبات
function showTab(tabId, link) {
  document.getElementById('casesTab').style.display = 'none';
  document.getElementById('requestsTab').style.display = 'none';
  document.getElementById(tabId).style.display = 'block';
  document.querySelectorAll('.nav-tabs a').forEach(a => a.classList.remove('active'));
  link.classList.add('active');
}

// فتح نافذة الأمان
function openWindow(page){
  let width = 1000;
  let height = 600;
  let left = (screen.width - width) / 2;
  let top = (screen.height - height) / 2;
  window.open(page + '.html', '_blank', `width=${width},height=${height},top=${top},left=${left}`);
}

// البحث في جدول القضايا
document.getElementById('searchCases')?.addEventListener('input', function(e) {
    const searchText = e.target.value.toLowerCase().trim();
    const table = document.getElementById('casesTable');
    const rows = table.getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const text = row.textContent.toLowerCase();
        
        if (text.includes(searchText)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
});

// البحث في جدول الطلبات
document.getElementById('searchRequests')?.addEventListener('input', function(e) {
    const searchText = e.target.value.toLowerCase().trim();
    const table = document.getElementById('requestsTable');
    const rows = table.getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const text = row.textContent.toLowerCase();
        
        if (text.includes(searchText)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
});
</script>

</body>
</html>