{{-- 🔵 الشريط الثالث - بار البحث عن الدعوى/الطلب --}}
<div class="third-bar">

    <div class="d-flex align-items-center">
        <label class="me-2 mb-0">النوع:</label>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="entry_type" id="type_case" value="case" checked>
            <label class="form-check-label" for="type_case">دعوى</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="entry_type" id="type_request" value="request">
            <label class="form-check-label" for="type_request">طلب</label>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2">
        <input type="text" class="form-control form-control-sm" placeholder="المحكمة"
               readonly value="{{ optional(auth()->user()->tribunal)->number ?? '---' }}">

        <input type="text" class="form-control form-control-sm" placeholder="القلم"
               readonly value="{{ optional(auth()->user()->department)->number ?? '---' }}">

        {{-- ⭐ هذا هو الحقل الذي سنقرأ منه رقم الطلب --}}
        <input id="entryNumberInput" type="text" class="form-control form-control-sm" placeholder="الرقم">

        <input type="text" class="form-control form-control-sm" placeholder="السنة" readonly value="{{ date('Y') }}">
    </div>
</div>

{{-- ⭐⭐⭐ نافذة تفاصيل الطلب (Popup) ⭐⭐⭐ --}}
<div id="requestDetailsPopup" class="details-popup" style="display: none;">
    <div class="popup-content">
        <div class="popup-header">
            <h5>تفاصيل الطلب</h5>
            <button type="button" class="popup-close" onclick="closeRequestPopup()">&times;</button>
        </div>
        <div class="popup-body" id="requestDetailsBody">
            <p class="text-center text-secondary">جاري التحميل...</p>
        </div>
    </div>
</div>

{{-- ⭐⭐⭐ نافذة تفاصيل الدعوى (Popup) ⭐⭐⭐ --}}
<div id="caseDetailsPopup" class="details-popup" style="display: none;">
    <div class="popup-content">
        <div class="popup-header">
            <h5>تفاصيل الدعوى</h5>
            <button type="button" class="popup-close" onclick="closeCasePopup()">&times;</button>
        </div>
        <div class="popup-body" id="caseDetailsBody">
            <p class="text-center text-secondary">جاري التحميل...</p>
        </div>
    </div>
</div>

{{-- 🔵 سكربت فتح نافذة الطلب --}}
<script>
// =============================
//  الضغط على Enter لفتح نافذة الطلب
// =============================
document.addEventListener('DOMContentLoaded', function () {

    const entryTypeRequest = document.getElementById("type_request");
    const entryInput = document.getElementById("entryNumberInput");

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


// =============================
//   فتح popup تفاصيل الطلب
// =============================
function openRequestDetails(requestNumber) {
    const popup = document.getElementById("requestDetailsPopup");
    const body  = document.getElementById("requestDetailsBody");

    body.innerHTML = `<p class="text-center text-secondary">جاري التحميل...</p>`;
    popup.style.display = "flex";

    loadRequestDetails(requestNumber);
}

// =============================
//   إغلاق popup تفاصيل الطلب
// =============================
function closeRequestPopup() {
    document.getElementById("requestDetailsPopup").style.display = "none";
}


// =============================
//   جلب بيانات الطلب (POST)
// =============================
async function loadRequestDetails(requestNumber) {

    const body = document.getElementById("requestDetailsBody");

    try {
        const response = await axios.post("{{ route('chief.request.details') }}", {
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
// =============================
// استماع لزر Enter عند اختيار "دعوى"
// =============================
document.addEventListener("DOMContentLoaded", function () {

    const entryTypeCase = document.getElementById("type_case");
    const inputs = document.querySelectorAll('.third-bar input[type="text"]');
    const caseNumberInput = inputs[2]; // رقم الدعوى

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
//  فتح popup تفاصيل الدعوى
// =============================
function openCaseDetails(caseNumber) {
    const popup = document.getElementById("caseDetailsPopup");
    const body  = document.getElementById("caseDetailsBody");

    body.innerHTML = `<p class="text-center text-secondary">جاري التحميل...</p>`;
    popup.style.display = "flex";

    loadCaseDetails(caseNumber);
}

// =============================
//   إغلاق popup تفاصيل الدعوى
// =============================
function closeCasePopup() {
    document.getElementById("caseDetailsPopup").style.display = "none";
}


// =============================
//  جلب بيانات الدعوى من السيرفر
// =============================
async function loadCaseDetails(caseNumber) {

    const body = document.getElementById("caseDetailsBody");

    try {
        const response = await axios.post("{{ route('chief.case.details') }}", {
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

<style>
.third-bar {
    background-color: #f0f0f0;
    font-size: 13px;
    padding: 6px 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #ccc;
}

.third-bar input {
    width: 90px;
    font-size: 13px;
}

.third-bar .form-check-input {
    width: 12px !important;
    height: 12px !important;
    margin-top: 2px;
    cursor: pointer;
}

/* Popup Styles */
.details-popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.popup-content {
    background: white;
    border-radius: 8px;
    width: 90%;
    max-width: 800px;
    max-height: 90vh;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    animation: popupSlideIn 0.3s ease-out;
}

@keyframes popupSlideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.popup-header {
    background-color: #000000;
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 8px 8px 0 0;
}

.popup-header h5 {
    margin: 0;
    font-size: 18px;
    font-weight: bold;
}

.popup-close {
    background: none;
    border: none;
    color: white;
    font-size: 28px;
    cursor: pointer;
    line-height: 1;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s;
}

.popup-close:hover {
    transform: scale(1.2);
}

.popup-body {
    padding: 20px;
    max-height: calc(90vh - 70px);
    overflow-y: auto;
    font-family: 'Cairo', sans-serif;
    direction: rtl;
    text-align: right;
}

.popup-body table {
    width: 100%;
    margin-bottom: 15px;
}

.popup-body table th {
    background-color: #f0f0f0;
    padding: 10px;
    font-weight: bold;
}

.popup-body table td {
    padding: 10px;
}

.popup-body h6 {
    margin-top: 20px;
    margin-bottom: 10px;
    font-weight: bold;
    color: #004080;
}
</style>
