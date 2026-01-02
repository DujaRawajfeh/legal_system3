
<div class="third-bar">

    <div class="d-flex align-items-center">
        <label class="me-2 mb-0">النوع:</label>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="entry_type" id="entrySearchTypeCase" value="case" checked>
            <label class="form-check-label" for="entrySearchTypeCase">دعوى</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="entry_type" id="entrySearchTypeRequest" value="request">
            <label class="form-check-label" for="entrySearchTypeRequest">طلب</label>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2">
        <input type="text" class="form-control form-control-sm" placeholder="المحكمة"
               readonly value="<?php echo e(optional(auth()->user()->tribunal)->number ?? '---'); ?>">

        <input type="text" class="form-control form-control-sm" placeholder="القلم"
               readonly value="<?php echo e(optional(auth()->user()->department)->number ?? '---'); ?>">

        
        <input id="entrySearchNumberInput" type="text" class="form-control form-control-sm" placeholder="الرقم">

        <input type="text" class="form-control form-control-sm" placeholder="السنة" readonly value="<?php echo e(date('Y')); ?>">
    </div>
</div>


<div id="entrySearchRequestPopup" class="details-popup" style="display: none;">
    <div class="popup-content">
        <div class="popup-header">
            <h5>تفاصيل الطلب</h5>
            <button type="button" class="popup-close" onclick="closeEntrySearchRequestPopup()">&times;</button>
        </div>
        <div class="popup-body" id="entrySearchRequestBody">
            <p class="text-center text-secondary">جاري التحميل...</p>
        </div>
    </div>
</div>


<div id="entrySearchCasePopup" class="details-popup" style="display: none;">
    <div class="popup-content">
        <div class="popup-header">
            <h5>تفاصيل الدعوى</h5>
            <button type="button" class="popup-close" onclick="closeEntrySearchCasePopup()">&times;</button>
        </div>
        <div class="popup-body" id="entrySearchCaseBody">
            <p class="text-center text-secondary">جاري التحميل...</p>
        </div>
    </div>
</div>


<script>
// =============================
//  الضغط على Enter لفتح النافذة المناسبة
// =============================
document.addEventListener('DOMContentLoaded', function () {

    const entryTypeCase = document.getElementById("entrySearchTypeCase");
    const entryTypeRequest = document.getElementById("entrySearchTypeRequest");
    const entryInput = document.getElementById("entrySearchNumberInput");

    entryInput.addEventListener("keydown", function (e) {

        if (e.key === "Enter") {
            e.preventDefault();
            
            const number = entryInput.value.trim();
            
            if (!number) {
                alert("الرجاء إدخال الرقم");
                return;
            }

            if (entryTypeRequest.checked) {
                openEntrySearchRequestDetails(number);
            } else if (entryTypeCase.checked) {
                openEntrySearchCaseDetails(number);
            }
        }
    });

});


// =============================
//   فتح popup تفاصيل الطلب
// =============================
function openEntrySearchRequestDetails(requestNumber) {
    const popup = document.getElementById("entrySearchRequestPopup");
    const body  = document.getElementById("entrySearchRequestBody");

    body.innerHTML = `<p class="text-center text-secondary">جاري التحميل...</p>`;
    popup.style.display = "flex";

    loadEntrySearchRequestDetails(requestNumber);
}

// =============================
//   إغلاق popup تفاصيل الطلب
// =============================
function closeEntrySearchRequestPopup() {
    document.getElementById("entrySearchRequestPopup").style.display = "none";
}


// =============================
//   جلب بيانات الطلب (POST)
// =============================
async function loadEntrySearchRequestDetails(requestNumber) {

    const body = document.getElementById("entrySearchRequestBody");

    try {
        const response = await axios.post("<?php echo e(route('chief.request.details')); ?>", {
            request_number: requestNumber
        });
        console.log(response);

        if (!response.data.success) {
            body.innerHTML = `<p class="text-danger text-center">⚠️ ${response.data.message}</p>`;
            return;
        }

        const info = response.data.info;
        const sessions = response.data.sessions || [];
        const parties = response.data.parties || [];


        // Build sessions table HTML
        let sessionsHTML = "";
        sessions.forEach(s => {
            sessionsHTML += `
                <tr>
                    <td>${s.date ?? '-'}</td>
                    <td>${s.time ?? '-'}</td>
                    <td>${s.session_status ?? '-'}</td>
                    <td>${s.reason ?? '-'}</td>
                </tr>`;
        });

        // Build parties table HTML
        let partiesHTML = "";
        parties.forEach(p => {
            partiesHTML += `
                <tr>
                    <td>${p.type ?? '-'}</td>
                    <td>${p.name ?? '-'}</td>
                </tr>`;
        });

        body.innerHTML = `
            <h6>معلومات الطلب</h6>
            <table class="table table-bordered">
                <tr><th>رقم الطلب</th><td>${info.request_number ?? '-'}</td></tr>
                <tr><th>عنوان الطلب</th><td>${info.title ?? '-'}</td></tr>
                <tr><th>التاريخ الأصلي</th><td>${info.original_date ?? '-'}</td></tr>
                <tr><th>القاضي</th><td>${info.judge_name ?? '-'}</td></tr>
            </table>

            <h6 class="mt-4">الجلسات</h6>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>تاريخ الجلسة</th>
                        <th>وقت الجلسة</th>
                        <th>حالة الجلسة</th>
                        <th>سبب الجلسة</th>
                    </tr>
                </thead>
                <tbody>${sessionsHTML || '<tr><td colspan="4" class="text-center">لا توجد جلسات</td></tr>'}</tbody>
            </table>

            <h6 class="mt-4">الأطراف</h6>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>الصفة</th>
                        <th>الاسم</th>
                    </tr>
                </thead>
                <tbody>${partiesHTML || '<tr><td colspan="2" class="text-center">لا توجد أطراف</td></tr>'}</tbody>
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
//  فتح popup تفاصيل الدعوى
// =============================
function openEntrySearchCaseDetails(caseNumber) {
    const popup = document.getElementById("entrySearchCasePopup");
    const body  = document.getElementById("entrySearchCaseBody");

    body.innerHTML = `<p class="text-center text-secondary">جاري التحميل...</p>`;
    popup.style.display = "flex";

    loadEntrySearchCaseDetails(caseNumber);
}

// =============================
//   إغلاق popup تفاصيل الدعوى
// =============================
function closeEntrySearchCasePopup() {
    document.getElementById("entrySearchCasePopup").style.display = "none";
}


// =============================
//  جلب بيانات الدعوى من السيرفر
// =============================
async function loadEntrySearchCaseDetails(caseNumber) {

    const body = document.getElementById("entrySearchCaseBody");

    try {
        console.log("Searching for case:", caseNumber);
        
        const response = await axios.post("<?php echo e(route('chief.case.details')); ?>", {
            case_number: caseNumber
        });

        console.log("Case response:", response);

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
                    <td>${s.session_status ?? '-'}</td>
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
<?php /**PATH C:\Users\LENOVO\legal_system\resources\views/components/entry-search-bar.blade.php ENDPATH**/ ?>