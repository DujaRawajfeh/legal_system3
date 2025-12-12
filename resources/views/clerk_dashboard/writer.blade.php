@extends('layouts.app')

@section('title', 'لوحة الكاتب')

@section('content')

<style>
/* شبكة القضايا */
.cases-grid {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 40px;
}

/* البوكس الرئيسي لكل قضية */
.case-strip {
  display: flex;
  flex-direction: row;       /* نص يمين + أزرار يسار */
  justify-content: space-between;
  align-items: center;
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: 8px;
  padding: 12px 15px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.08);
  direction: rtl;            /* النقاط داخل النص على اليمين */
}

/* بيانات القضية */
.case-info {
  flex: 1;
  direction: rtl;
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

/* حاوية الأزرار على اليسار */
.case-actions {
  display: flex;
  flex-direction: column; /* تحت بعض */
  gap: 6px;
  direction: ltr;         /* حتى يبقوا يسار بدون انقلاب */
}

/* الزر الصغير */
.case-actions .action-btn {
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
  white-space: nowrap;    /* ما ينزل سطر */
}

.case-actions .action-btn:hover {
  background-color: #2f5574;
}

.container-writer {
  width: 90%;
  max-width: 1200px;
  margin: 20px auto;
  padding: 25px;
  display: flex;
  flex-direction: column;
  gap: 20px;
  direction: rtl;
  text-align: right;
}

#main-title {
  margin-top: 5px;
  margin-bottom: 0;
}

.title-line {
  border: none;
  height: 2px;
  background-color: #000;
  margin: 4px 0 15px 0;
  width: 100%;
}
</style>

<!-- Main Content Area -->
<div class="container-writer">
  <section>
    <h2 id="main-title">القضايا التي يمكن متابعتها</h2>
    <hr class="title-line">
    <div class="cases-grid" id="casesGrid">
      <!-- Cases will be loaded here dynamically -->
      <div class="case-strip" data-key="2025/0012" data-type="case">
        <div class="case-info" id="case-box" data-status="مستمرة">
          <h3>القضية رقم: 2025/0012</h3>
          <p><strong>عنوان القضية:</strong> القتل العمد</p>
        </div>
        <div class="case-actions" id="actions-2025-0012">
          <button class="action-btn" id="btnRecord" onclick="openRecordprint()">محضر المحاكمة</button>
          <button class="action-btn" id="btnAfter" onclick="openAfterprint()">ما بعد</button>
        </div>
      </div>
    </div>
  </section>
</div>

<!--  قائمة الدعوى / الطلب الخاصة بالكاتب -->
<div id="writer-case-options"
     style="
        display:none;
        position:absolute;
        background:#fff;
        border:1px solid #ccc;
        width:250px;
        z-index:999999999;
        text-align:right;
        box-shadow:0 4px 8px rgba(0,0,0,0.18);
     ">
    <ul style="list-style:none; margin:0; padding:0;">
        <li id="open-register-case"
    style="padding:10px; border-bottom:1px solid #ddd; cursor:pointer;"
    data-bs-toggle="modal" data-bs-target="#registerCaseModal">
    تسجيل دعوى
</li>


<!-- <li id="open-register-request"
    style="padding:10px; border-bottom:1px solid #ddd; cursor:pointer;">
    تسجيل طلب
</li> -->
        <li style="padding:10px; border-bottom:1px solid #ddd; cursor:pointer;"
    data-bs-toggle="modal"
    data-bs-target="#withdrawCaseModal">
    سحب دعوى / المدعي العام
</li>

        <li style="padding:10px; border-bottom:1px solid #ddd; cursor:pointer;"
            data-bs-toggle="modal" data-bs-target="#pullPoliceCaseModal">
            سحب قضية من الشرطة
        </li>

        <li style="padding:10px; cursor:pointer;"
            data-bs-toggle="modal" data-bs-target="#participantsModal">
            المشاركين
        </li>
    </ul>
</div>



<style>
.floating-menu a {
    display:block;
    padding:5px 0;
    text-decoration:none;
    color:#333;
}
.floating-menu a:hover {
    color:#0d6efd;
}
.submenu-title {
    font-weight:bold;
    cursor:pointer;
}
</style>
<!-- قائمة التباليغ الخاصة بالكاتب -->
<div id="notifications-menu" class="floating-menu" style="display:none; position:absolute; top:120px; right:50px; background:white; border:1px solid #ccc; border-radius:6px; padding:10px; width:250px; z-index:9999;">
    <!-- تباليغ الدعوى -->
    <div class="submenu">
        <div class="submenu-title">تباليغ الدعوى ▾</div>
        <div class="submenu-items" style="display:none; margin-right:10px;">
            <a class="submenu-item" href="#" data-bs-toggle="modal" data-bs-target="#notif-complainant-modal"> مذكرة تبليغ مشتكى عليه</a>
            <a class="submenu-item open-modal"
   href="#"
   data-bs-toggle="modal"
   data-bs-target="#notif-session-complainant-modal">
    مذكرة تبليغ مشتكي موعد جلسة
</a>
            <a class="submenu-item" href="#">مذكرة حضور خاصة بالشهود</a>
            <a class="submenu-item" href="#">مذكرة تبليغ حكم</a>
            <a class="submenu-item" href="#" 
   data-bs-toggle="modal" 
   data-bs-target="#manage-notifications-modal">
   إدارة تباليغ
</a>
        </div>
    </div>

    <hr>

    <!-- كتب مخاطبات الأمن العام -->
    <div class="submenu">
        <div class="submenu-title">كتب مخاطبات الأمن العام ▾</div>
        <div class="submenu-items" style="display:none; margin-right:10px;">
            <a class="submenu-item"
   href="#"
   data-bs-toggle="modal"
   data-bs-target="#arrest-memo-modal">
    مذكرة توقيف
</a>
            <a class="submenu-item" href="#">مذكرة تمديد توقيف</a>
            <a class="submenu-item" href="#">مذكرة الإفراج عن الموقوفين</a>
            <a class="submenu-item"
   href="#"
   data-bs-toggle="modal"
   data-bs-target="#arrest-memos-modal">
    المذكرات
</a>
        </div>
    </div>

</div>













<!--نافذه تسجيل دعوى -->
<style>
#registerCaseModal .modal-dialog {
    max-width: 1000px;
}
#registerCaseModal .modal-content {
    background: #f5f5f5;
    border-radius: 8px;
}
#registerCaseModal .form-container {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
#registerCaseModal .section-title {
    background: black;
    color: white;
    padding: 12px 20px;
    border-radius: 6px;
    font-size: 18px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
}
#registerCaseModal .form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}
#registerCaseModal .form-control,
#registerCaseModal .form-select {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 8px 12px;
}
#registerCaseModal .party-block {
    background: #f9f9f9;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 15px;
    position: relative;
}
#registerCaseModal .party-block .remove-party {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    font-size: 18px;
    cursor: pointer;
}
#registerCaseModal .btn-add-party {
    background: black;
    color: white;
    border: none;
    padding: 10px 30px;
    border-radius: 6px;
    font-weight: bold;
    margin-top: 10px;
}
#registerCaseModal .btn-add-party:hover {
    background: #333;
}
#registerCaseModal .action-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
}
#registerCaseModal .action-buttons button {
    background: black;
    color: white;
    border: none;
    padding: 12px 40px;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
}
#registerCaseModal .action-buttons button:hover {
    background: #333;
}
</style>

<div class="modal fade" id="registerCaseModal" tabindex="-1" aria-labelledby="registerCaseLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" style="margin-top:80px;">
    <div class="modal-content">

      <div class="modal-header" style="background: black; color: white;">
        <h5 class="modal-title">تسجيل دعوى جديدة</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="form-container">
          <form id="caseForm">

            <!-- نوع الدعوى -->
            <div class="section-title">نوع الدعوى</div>
            <div class="row g-3 mb-4">
              <div class="col-md-12">
                <label class="form-label">نوع الدعوى</label>
                <select class="form-select" id="caseType">
                  <option value="">اختر نوع القضية...</option>
                  <option value="القتل العمد">القتل العمد</option>
                  <option value="القتل العمد مع سبق الإصرار">القتل العمد مع سبق الإصرار</option>
                  <option value="القتل الخطأ">القتل الخطأ</option>
                  <option value="السرقة">السرقة</option>
                  <option value="الاغتصاب">الاغتصاب</option>
                  <option value="الاعتداء الجسدي">الاعتداء الجسدي</option>
                  <option value="المخدرات - تعاطي">المخدرات - تعاطي</option>
                  <option value="المخدرات - اتجار">المخدرات - اتجار</option>
                  <option value="المخدرات - ترويج">المخدرات - ترويج</option>
                  <option value="الخطف">الخطف</option>
                  <option value="الجرائم الإلكترونية">الجرائم الإلكترونية</option>
                  <option value="الجرائم ضد أمن الدولة">الجرائم ضد أمن الدولة</option>
                </select>
              </div>
            </div>

            <!-- رقم الدعوى -->
            <div class="section-title">رقم الدعوى</div>
            <div class="row g-3 mb-4">
              <div class="col-md-3">
                <label class="form-label">رقم الدعوى</label>
                <input type="text" class="form-control" id="caseNumber" placeholder="اضغط Enter">
              </div>
              <div class="col-md-3">
                <label class="form-label">رقم المحكمة</label>
                <input type="text" class="form-control" id="courtNumber" value="{{ auth()->user()->tribunal->number }}" readonly>
              </div>
              <div class="col-md-3">
                <label class="form-label">رقم القلم</label>
                <input type="text" class="form-control" id="departmentNumber" value="{{ auth()->user()->department->number }}" readonly>
              </div>
              <div class="col-md-3">
                <label class="form-label">السنة</label>
                <input type="text" class="form-control" id="caseYear" value="{{ date('Y') }}" readonly>
              </div>
            </div>

            <!-- القاضي -->
            <div class="section-title">القاضي</div>
            <div class="row g-3 mb-4">
              <div class="col-md-12">
                <label class="form-label">القاضي المعيّن تلقائيًا</label>
                <input type="text" id="judge_name" class="form-control" readonly>
                <input type="hidden" name="judge_id" id="judge_id">
              </div>
            </div>

            <!-- أطراف الدعوى -->
            <div class="section-title">أطراف الدعوى</div>
            <div id="partiesContainerCase">
              <div class="party-block case-party">
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label">نوع الطرف</label>
                    <select class="form-select case-party-type">
                      <option value="">اختر...</option>
                      <option value="مشتكي">مشتكي</option>
                      <option value="مشتكى عليه">مشتكى عليه</option>
                      <option value="شاهد">شاهد</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">اسم الطرف</label>
                    <input type="text" class="form-control case-party-name">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">الرقم الوطني</label>
                    <input type="text" class="form-control case-party-nid">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">رقم الهاتف</label>
                    <input type="text" class="form-control case-party-phone">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">مكان السكن</label>
                    <input type="text" class="form-control case-party-residence">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">الوظيفة / مكان العمل</label>
                    <input type="text" class="form-control case-party-job">
                  </div>
                  <div class="col-md-12">
                    <label class="form-label">التهمة</label>
                    <input type="text" class="form-control case-party-charge">
                  </div>
                </div>
              </div>
            </div>

            <template id="casePartyTemplate">
              <div class="party-block case-party">
                <button type="button" class="remove-party">×</button>
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label">نوع الطرف</label>
                    <select class="form-select case-party-type">
                      <option value="">اختر...</option>
                      <option value="مشتكي">مشتكي</option>
                      <option value="مشتكى عليه">مشتكى عليه</option>
                      <option value="شاهد">شاهد</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">اسم الطرف</label>
                    <input type="text" class="form-control case-party-name">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">الرقم الوطني</label>
                    <input type="text" class="form-control case-party-nid">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">رقم الهاتف</label>
                    <input type="text" class="form-control case-party-phone">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">مكان السكن</label>
                    <input type="text" class="form-control case-party-residence">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">الوظيفة / مكان العمل</label>
                    <input type="text" class="form-control case-party-job">
                  </div>
                  <div class="col-md-12">
                    <label class="form-label">التهمة</label>
                    <input type="text" class="form-control case-party-charge">
                  </div>
                </div>
              </div>
            </template>

            <div class="text-center">
              <button type="button" id="addCaseParty" class="btn-add-party">➕ إضافة طرف آخر</button>
            </div>

            <!-- موعد الجلسة -->
            <div class="row g-3 mt-3">
              <div class="col-md-12">
                <label class="form-label">موعد الجلسة</label>
                <input type="text" id="session_date" class="form-control" readonly>
              </div>
            </div>

          </form>
        </div>
      </div>

      <div class="modal-footer" style="background: #f5f5f5;">
        <div class="action-buttons">
          <button type="button" id="saveAndFinishCase">حفظ وإنهاء</button>
          <button type="button" id="clearCase">مسح الكل</button>
        </div>
      </div>

    </div>
  </div>
</div>




<!--  نافذة تسجيل طلب -->
<style>
#registerRequestModal .modal-dialog {
    max-width: 900px;
}
#registerRequestModal .modal-content {
    background: #f5f5f5;
    border-radius: 8px;
}
#registerRequestModal .form-container {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
#registerRequestModal .section-title {
    background: black;
    color: white;
    padding: 12px 20px;
    border-radius: 6px;
    font-size: 18px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
}
#registerRequestModal .form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}
#registerRequestModal .form-control,
#registerRequestModal .form-select {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 8px 12px;
}
#registerRequestModal .party-block {
    background: #f9f9f9;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 15px;
    position: relative;
}
#registerRequestModal .party-block .remove-party {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    font-size: 18px;
    cursor: pointer;
}
#registerRequestModal .btn-add-party {
    background: black;
    color: white;
    border: none;
    padding: 10px 30px;
    border-radius: 6px;
    font-weight: bold;
    margin-top: 10px;
}
#registerRequestModal .btn-add-party:hover {
    background: #333;
}
#registerRequestModal .evidence-block {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 15px;
    margin-bottom: 10px;
    position: relative;
}
#registerRequestModal .action-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
}
#registerRequestModal .action-buttons button {
    background: black;
    color: white;
    border: none;
    padding: 12px 40px;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
}
#registerRequestModal .action-buttons button:hover {
    background: #333;
}
</style>

<div class="modal fade" id="registerRequestModal" tabindex="-1" aria-labelledby="registerRequestLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" style="margin-top:80px;">
    <div class="modal-content">

      <div class="modal-header" style="background: black; color: white;">
        <h5 class="modal-title">تسجيل طلب جديد</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="form-container">
          <form id="requestForm">

            <!-- رقم الطلب -->
            <!-- <div class="section-title">رقم الطلب</div> -->
            <div class="row g-3 mb-4">
              <div class="col-md-3">
                <label class="form-label">رقم المحكمة</label>
                <input type="text" class="form-control" id="reqCourtNumber" readonly>
              </div>
              <div class="col-md-3">
                <label class="form-label">رقم القلم</label>
                <input type="text" class="form-control" id="reqDepartmentNumber" readonly>
              </div>
              <div class="col-md-3">
                <label class="form-label">رقم الطلب</label>
                <input type="text" class="form-control" id="requestNumber" placeholder="اضغط Enter">
              </div>
              <div class="col-md-3">
                <label class="form-label">السنة</label>
                <input type="text" class="form-control" id="reqYear" readonly>
              </div>
            </div>

            <!-- نوع الطلب والقاضي -->
            <div class="section-title">معلومات الطلب</div>
            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label class="form-label">نوع الطلب</label>
                <select class="form-select" id="requestType">
                  <option value="">اختر نوع الطلب...</option>
                  <option value="طلب تنفيذ">طلب تنفيذ</option>
                  <option value="طلب إثبات حالة">طلب إثبات حالة</option>
                  <option value="طلب مستعجل">طلب مستعجل</option>
                  <option value="طلب تعليق">طلب تعليق</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">القاضي المعيّن تلقائيًا</label>
                <input type="text" id="reqJudgeName" class="form-control" readonly>
                <input type="hidden" id="reqJudgeId">
              </div>
            </div>

            <!-- الأطراف -->
            <div class="section-title">الأطراف</div>
            <div id="partiesContainer">
              <!-- الطرف الأول -->
              <div class="party-block request-party">
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label">نوع الطرف</label>
                    <select class="form-select request-party-type">
                      <option value="">اختر...</option>
                      <option value="مشتكي">مشتكي</option>
                      <option value="مشتكى عليه">مشتكى عليه</option>
                      <option value="شاهد">شاهد</option>
                      <option value="محامي">محامي</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">اسم الطرف</label>
                    <input type="text" class="form-control request-party-name">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">الرقم الوطني</label>
                    <input type="text" class="form-control request-party-nid">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">مكان السكن</label>
                    <input type="text" class="form-control request-party-residence">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">الوظيفة / مكان العمل</label>
                    <input type="text" class="form-control request-party-job">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">رقم الهاتف</label>
                    <input type="text" class="form-control request-party-phone">
                  </div>
                  <div class="col-md-12">
                    <label class="form-label">العنوان</label>
                    <input type="text" class="form-control request-party-address">
                  </div>
                </div>
              </div>
            </div>

            <!-- قالب طرف مخفي -->
            <template id="partyTemplate">
              <div class="party-block request-party">
                <button type="button" class="remove-party">×</button>
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label">نوع الطرف</label>
                    <select class="form-select request-party-type">
                      <option value="">اختر...</option>
                      <option value="مشتكي">مشتكي</option>
                      <option value="مشتكى عليه">مشتكى عليه</option>
                      <option value="شاهد">شاهد</option>
                      <option value="محامي">محامي</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">اسم الطرف</label>
                    <input type="text" class="form-control request-party-name">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">الرقم الوطني</label>
                    <input type="text" class="form-control request-party-nid">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">مكان السكن</label>
                    <input type="text" class="form-control request-party-residence">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">الوظيفة / مكان العمل</label>
                    <input type="text" class="form-control request-party-job">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">رقم الهاتف</label>
                    <input type="text" class="form-control request-party-phone">
                  </div>
                 
                </div>
              </div>
            </template>

            <div class="text-center">
              <button type="button" id="addRequestParty" class="btn-add-party">➕ إضافة طرف آخر</button>
            </div>

         

            <!-- الوصف -->
       

            <!-- موعد الجلسة -->
            <div class="row g-3">
              <div class="col-md-12">
                <label class="form-label">موعد الجلسة</label>
                <input type="text" id="reqSessionDate" class="form-control" readonly>
              </div>
            </div>

          </form>
        </div>
      </div>

      <div class="modal-footer" style="background: #f5f5f5;">
        <div class="action-buttons">
          <button type="button" id="saveAndFinishRequest">حفظ وإنهاء</button>
          <button type="button" id="clearRequest">مسح الكل</button>
        </div>
      </div>

    </div>
  </div>
</div>


<!-- قائمة محاضر الجلسات الخاصة بالكاتب -->
<ul id="writer-sessions-submenu"
    style="display:none; position:absolute;
           background:white; border:1px solid #ccc;
           padding:10px; min-width:180px;
           z-index:999999;">
    
    <li class="dropdown-item text-primary" onclick="openReportsListModal()">
         محاضر الجلسات
    </li>

    <!-- ✅ خيار جديد لفتح نافذة جدول الطلبات -->
    <li class="dropdown-item text-success" data-bs-toggle="modal" data-bs-target="#requestScheduleModal">
         جدول الطلبات
    </li>

</ul>



</ul>
<div class="modal fade" id="reportsListModal" tabindex="-1">
  <div class="modal-dialog modal-lg" style="margin-top:80px;">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">محاضر الجلسات</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div id="reportsContainer">
            <p class="text-center text-secondary">جاري التحميل...</p>
        </div>
      </div>

    </div>
  </div>
</div>
<!-- نافذة المشاركين / البحث في الأحوال المدنية -->
<style>
#participantsModal .modal-content {
    background: #f3f5f8;
}
#participantsModal .modal-body {
    background: #fff;
    border-radius: 10px;
    padding: 18px;
    box-shadow: 0 6px 18px rgba(0,0,0,.08);
}
#participantsModal h2 {
    text-align: center;
    margin: 0 0 14px;
}
#participantsModal .grid {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}
#participantsModal .field {
    flex: 1;
    min-width: 170px;
    display: flex;
    flex-direction: column;
}
#participantsModal label {
    font-weight: 700;
    margin-bottom: 6px;
}
#participantsModal input,
#participantsModal select {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
}
#participantsModal .controls {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-bottom: 8px;
}
#participantsModal .search-btn {
    background: #2d9f6f;
    color: #fff;
    padding: 9px 14px;
    border-radius: 6px;
    border: 0;
    cursor: pointer;
    font-weight: 700;
}
#participantsModal .exit-btn {
    background: #e74c3c;
    color: #fff;
}
#participantsModal table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 12px;
}
#participantsModal th,
#participantsModal td {
    border: 1px solid #e1e4e8;
    padding: 8px;
    text-align: center;
    font-size: 14px;
}
#participantsModal th {
    background: #f6f8fa;
    font-weight: 700;
}
#participantsModal tbody tr:hover {
    background: #f0fbf8;
}
#participantsModal .empty {
    color: #777;
    padding: 18px;
    text-align: center;
}
</style>

<div class="modal fade" id="participantsModal" tabindex="-1" aria-labelledby="participantsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">صفحة المشاركين</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- مدخلات البحث -->
                <div class="grid">
                    <div class="field">
                        <label for="first_name">الاسم الأول</label>
                        <input type="text" id="first_name">
                    </div>
                    <div class="field">
                        <label for="father_name">اسم الأب</label>
                        <input type="text" id="father_name">
                    </div>
                    <div class="field">
                        <label for="grandfather_name">اسم الجد </label>
                        <input type="text" id="grandfather_name">
                    </div>
                    <div class="field">
                        <label for="family_name">اسم العائلة</label>
                        <input type="text" id="family_name" >
                    </div>
                    <div class="field">
                        <label for="mother_name">اسم الأم</label>
                        <input type="text" id="mother_name">
                    </div>
                    <div class="field">
                        <label for="occupation">المهنة</label>
                        <input type="text" id="occupation">
                    </div>
                    <div class="field">
                        <label for="gender">الجنس</label>
                        <select id="gender">
                            <option value="">-- الكل --</option>
                            <option>ذكر</option>
                            <option>أنثى</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="nationality">الجنسية</label>
                        <input type="text" id="nationality">
                    </div>
                </div>

                <div class="controls">
                    <button class="search-btn" onclick="searchCivilRegistry()">بحث الأحول المدينة</button>
                </div>

                <!-- جدول النتائج -->
                <table aria-label="نتائج البحث">
                    <thead>
                        <tr>
                            <th>الرقم الوطني</th>
                            <th>الاسم الأول</th>
                            <th>اسم الأب</th>
                            <th>اسم الأم</th>
                            <th>اسم الجد</th>
                            <th>اسم العائلة</th>
                            <th>تاريخ الميلاد</th>
                            <th>العمر</th>
                            <th>الجنس</th>
                            <th>الديانة</th>
                            <th>الجنسية</th>
                            <th>مكان الولادة</th>
                            <th>المهنة</th>
                            <th>المستوى التعليمي</th>
                            <th>رقم الهاتف</th>
                            <th>مكان السجل</th>
                        </tr>
                    </thead>
                    <tbody id="civilResults">
                        <tr id="emptyRow"><td class="empty" colspan="16">لا توجد نتائج — اضغط "بحث" بعد إدخال شروط البحث</td></tr>
                    </tbody>
                </table>

            </div> <!-- /modal-body -->

        </div>
    </div>
</div>


<!--  نافذة جدول الطلبات -->
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


<!-- نافذة سحب دعوى من المدعي العام -->
<style>
#withdrawCaseModal .modal-content {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

#withdrawCaseModal .modal-header {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

#withdrawCaseModal .modal-body {
    padding: 30px;
}

#withdrawCaseModal .form-label {
    font-weight: bold;
    margin-bottom: 8px;
    display: block;
}

#withdrawCaseModal .form-control,
#withdrawCaseModal .form-select {
    padding: 8px 12px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

#withdrawCaseModal .withdraw-btn {
    background-color: #000;
    color: white;
    padding: 10px 30px;
    border: none;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
}

#withdrawCaseModal .withdraw-btn:hover {
    background-color: #333;
}

#withdrawCaseModal .exit-btn {
    background-color: #e74c3c;
    color: white;
    padding: 10px 30px;
    border: none;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
}

#withdrawCaseModal .exit-btn:hover {
    background-color: #c0392b;
}
</style>

<div class="modal fade" id="withdrawCaseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">سحب الدعوى</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- رقم الدعوى -->
                <div class="mb-3">
                    <label class="form-label">رقم الدعوى:</label>
                    <input type="text" id="case_number" class="form-control" placeholder="أدخل رقم الدعوى">
                </div>

                <!-- المحكمة والسجل العام -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">المحكمة:</label>
                        <select id="court_location" class="form-select">
                            <option value="محكمة بداية عمان" selected>محكمة بداية عمان</option>
                            <!-- <option value="محكمة بداية الزرقاء">محكمة بداية الزرقاء</option> -->
                            <!-- <option value="محكمة بداية إربد">محكمة بداية إربد</option> -->
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">السجل العام:</label>
                        <select id="prosecutor_office" class="form-select">
                            <option value="">-- اختر السجل العام --</option>
                            @foreach ($records as $rec)
                                <option value="{{ $rec->records }}">{{ $rec->records }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- صندوق عرض الرسالة -->
                <div id="pullResult" class="alert d-none mt-3"></div>

            </div>

            <div class="modal-footer">
                <button class="withdraw-btn" onclick="pullCase()">سحب الدعوى</button>
                <button class="exit-btn" data-bs-dismiss="modal">خروج</button>
            </div>

        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- نافذة سحب قضية من الشرطة -->
<style>
#pullPoliceCaseModal .modal-content {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

#pullPoliceCaseModal .modal-body {
    padding: 20px 30px;
}

#pullPoliceCaseModal .form-row {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    align-items: flex-end;
}

#pullPoliceCaseModal select,
#pullPoliceCaseModal button {
    padding: 8px 12px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 14px;
}

#pullPoliceCaseModal button {
    cursor: pointer;
    font-weight: bold;
}

#pullPoliceCaseModal table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

#pullPoliceCaseModal table,
#pullPoliceCaseModal th,
#pullPoliceCaseModal td {
    border: 1px solid #ccc;
}

#pullPoliceCaseModal th,
#pullPoliceCaseModal td {
    padding: 8px;
    text-align: center;
}

#pullPoliceCaseModal th {
    background-color: #f2f2f2;
    font-weight: bold;
}

#pullPoliceCaseModal tr.selected {
    background-color: #a8d5e2;
}

#pullPoliceCaseModal tbody tr:hover {
    background-color: #f0f8ff;
    cursor: pointer;
}

#pullPoliceCaseModal .withdraw-btn {
    background-color: #27ae60;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
}

#pullPoliceCaseModal .withdraw-btn:hover {
    background-color: #229954;
}

#pullPoliceCaseModal .withdraw-btn:disabled {
    background-color: #95a5a6;
    cursor: not-allowed;
}

#pullPoliceCaseModal .exit-btn {
    background-color: #e74c3c;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
}

#pullPoliceCaseModal .exit-btn:hover {
    background-color: #c0392b;
}
</style>

<div class="modal fade" id="pullPoliceCaseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">سحب دعوى من الشرطة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- اختيار المركز الأمني + بحث بجانبه -->
                <div class="form-row">
                    <div style="flex:1; display:flex; gap:10px; align-items:flex-end;">
                        <select id="police_center" style="flex:1;">
                        <option value="">-- اختر المركز الأمني --</option>
                      <option value="شرطة جنوب عمان">شرطة جنوب عمان</option>
                      <option value="شرطة شمال عمان">شرطة شمال عمان</option>
                      <option value="شرطة غرب عمان">شرطة غرب عمان</option>                        </select>
                        <button id="searchPoliceCases">بحث</button>
                    </div>
                </div>

                <!-- جدول القضايا -->
                <table id="policeCasesTable">
                    <thead>
                        <tr>
                            <th>المركز الأمني</th>
                            <th>رقم القضية لدى الأمن العام</th>
                            <th>تاريخ تسجيل القضية لدى الشرطة</th>
                            <th>تاريخ الجريمة</th>
                            <th>حالة القضية لدى الشرطة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- البيانات ستتعبأ هنا -->
                    </tbody>
                </table>

                <!-- صندوق رسائل -->
                <div id="policeAlert" class="mt-3"></div>

            </div>

            <div class="modal-footer" style="justify-content: center; gap: 20px;">
                <button id="pullSelectedCaseBtn" class="withdraw-btn" disabled>سحب الدعوى</button>
                <button class="exit-btn" data-bs-dismiss="modal">خروج</button>
            </div>

        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">



<!-- مذكرة تبليغ مشتكي عليه -->
<div class="modal fade" id="notif-complainant-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">مذكرة تبليغ مشتكى عليه</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <!-- رسالة/alert -->
        <div id="notif-complainant-alert"></div>

        <!-- سطر إدخال رقم الدعوى + زر بحث -->
        <div class="row g-2 mb-3">
          <div class="col-md-9">
            <input type="text" id="notif-complainant-case-number" class="form-control" placeholder="أدخل رقم الدعوى">
          </div>
          <div class="col-md-3 d-flex align-items-end">
            <button id="notif-complainant-search" class="btn btn-primary w-100">بحث</button>
          </div>
        </div>

        <!-- مساحة لعرض بيانات القضية (بعد البحث) -->
        <div id="notif-complainant-case-info" class="mb-3" style="display:none;">
          <div class="row">
            <div class="col-md-3"><small class="text-muted">رقم المحكمة</small><div id="notif-complainant-tribunal">-</div></div>
            <div class="col-md-2"><small class="text-muted">رقم القلم</small><div id="notif-complainant-department">-</div></div>
            <div class="col-md-2"><small class="text-muted">السنة</small><div id="notif-complainant-year">-</div></div>
            <div class="col-md-5"><small class="text-muted">عنوان القضية</small><div id="notif-complainant-title">-</div></div>
          </div>
        </div>

        <!-- جدول الأطراف (فلترة على الخادم بحسب نوع المذكرة) -->
        <div id="notif-complainant-participants-area" style="display:none;">
          <h6>قائمة الأطراف (مشتكى عليه)</h6>
          <div class="table-responsive">
            <table class="table table-sm table-hover" id="notif-complainant-participants-table">
              <thead class="table-light">
                <tr>
                  <th style="width:60px">اختيار</th>
                  <th>الاسم</th>
                  <th>الرقم الوطني</th>
                  <th>نوع الطرف</th>
                  <th>مكان الإقامة</th>
                  <th>الوظيفة</th>
                  <th>الهاتف</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>

        <!-- خيارات طريقة التبليغ -->
        <div id="notif-complainant-method-area" class="mt-3" style="display:none;">
          <div class="row g-2 align-items-end">
            <div class="col-md-6">
              <label class="form-label">طريقة التبليغ</label>
              <select id="notif-complainant-method" class="form-control">
                <option value="">اختر طريقة</option>
                <option value="sms">SMS</option>
                <option value="email">Email</option>
                <option value="قسم التباليغ">قسم التباليغ</option>
              </select>
            </div>
            

      </div> <!-- modal-body -->

      <div class="modal-footer">
        <button id="notif-complainant-save" class="btn btn-success" disabled>حفظ التبليغ</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
      </div>

    </div>
  </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- إدارة تباليغ الدعوى -->
<div class="modal fade" id="manage-notifications-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">إدارة تباليغ الدعوى</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <div id="manage-notifications-alert"></div>

        <!-- إدخال رقم الدعوى -->
        <div class="row g-2 mb-3">
          <div class="col-md-12">
            <input type="text" id="manage-notifications-case-number" class="form-control"
                   placeholder="أدخل رقم الدعوى ثم اضغط Enter">
          </div>
        </div>

        <!-- جدول التباليغ -->
        <div id="manage-notifications-table-area" style="display:none;">
          <h6>سجل التبليغات</h6>

          <div class="table-responsive">
            <table class="table table-sm table-hover" id="manage-notifications-table">
              <thead class="table-light">
                <tr>
                  <th>رقم الدعوى</th>
                  <th>نوع الطرف</th>
                  <th>اسم الطرف</th>
                  <th>طريقة التبليغ</th>
                  <th>تاريخ التبليغ</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
      </div>

    </div>
  </div>
</div>


<!--  مذكرة توقيف -->
<div class="modal fade" id="arrest-memo-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">مذكرة توقيف</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <!-- Alert -->
        <div id="arrest-alert"></div>

        <!--  معلومات المحكمة -->
        <div class="row text-center mb-3">
          <div class="col-md-4">
            <label class="text-muted">رقم المحكمة</label>
            <div id="arrest-tribunal">-</div>
          </div>

          <div class="col-md-4">
            <label class="text-muted">رقم القلم</label>
            <div id="arrest-department">-</div>
          </div>

          <div class="col-md-4">
            <label class="text-muted">السنة</label>
            <div id="arrest-year">-</div>
          </div>
        </div>

        <!-- رقم الدعوى -->
        <div class="row mb-3">
          <div class="col-md-9">
            <input type="text" id="arrest-case-number" class="form-control" placeholder="أدخل رقم الدعوى">
          </div>

          <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary w-100" id="arrest-search-btn">بحث</button>
          </div>
        </div>

        <!-- نوع الدعوى -->
        <div id="arrest-case-type-area" style="display:none;">
          <label class="text-muted">نوع الدعوى:</label>
          <div id="arrest-case-title" class="fw-bold"></div>
        </div>

        <!-- جدول الأطراف -->
        <div id="arrest-participants-area" style="display:none;" class="mt-3">
          <h6>الأطراف</h6>

          <div class="table-responsive">
            <table class="table table-sm table-hover" id="arrest-participants-table">
              <thead class="table-light">
                <tr>
                  <th style="width:60px">اختيار</th>
                  <th>الاسم</th>
                  <th>نوع الطرف</th>
                  <th>الوظيفة</th>
                  <th>مكان الإقامة</th>
                  <th>رقم الهاتف</th>
                  <th>التبليغ بواسطة</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>

        <!-- بيانات إضافية -->
        <div id="arrest-extra-area" style="display:none;" class="mt-4">

          <!-- اسم القاضي -->
          <div class="mb-3">
            <label>اسم القاضي</label>
            <input type="text" id="arrest-judge-name" class="form-control" readonly>
          </div>

          <!-- مدة التوقيف -->
          <div class="mb-3">
            <label>مدة التوقيف (بالأيام)</label>
            <input type="number" id="arrest-duration" class="form-control" min="1">
          </div>

          <!-- سبب التوقيف -->
          <div class="mb-3">
            <label>سبب التوقيف</label>
            <select id="arrest-reason" class="form-control">
              <option value="">اختر سبب التوقيف</option>
              <option value="خشية الفرار">خشية الفرار</option>
              <option value="منع التأثير على الشهود">منع التأثير على الشهود</option>
              <option value="منع العبث بالأدلة">منع العبث بالأدلة</option>
              <option value="لحماية المشتكي من الخطر">لحماية المشتكي من الخطر</option>
            </select>
          </div>

          <!-- مركز الإصلاح والتأهيل -->
          <div class="mb-3">
            <label>مركز الإصلاح والتأهيل</label>
            <select id="arrest-center" class="form-control">
              <option value="">اختر المركز</option>
              <option value="مركز إصلاح وتأهيل ماركا">مركز إصلاح وتأهيل ماركا</option>
              <option value="مركز إصلاح وتأهيل إربد">مركز إصلاح وتأهيل إربد</option>
              <option value="مركز إصلاح وتأهيل الكرك">مركز إصلاح وتأهيل الكرك</option>
            </select>
          </div>

        </div>

      </div>

      <!-- أزرار -->
      <div class="modal-footer">
        <button class="btn btn-success" id="arrest-save-btn" disabled>حفظ</button>
        <button class="btn btn-primary" id="arrest-save-close-btn" disabled>حفظ وإنهاء</button>
        <button class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
      </div>

    </div>
  </div>
</div>
<!-- المذكرات -->
<div class="modal fade" id="arrest-memos-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title">المذكرات</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <!-- Alerts -->
        <div id="arrest-memos-alert"></div>

        <!-- إدخال رقم الدعوى -->
        <div class="row g-2 mb-3">
          <div class="col-md-9">
            <input type="text" id="arrest-memos-case-number" class="form-control" placeholder="أدخل رقم الدعوى">
          </div>

          <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary w-100" id="arrest-memos-search-btn">بحث</button>
          </div>
        </div>

        <!-- عنوان قبل الجدول -->
        <h6 id="arrest-memos-title" class="mt-3" style="display:none;">
          مذكرات التوقيف المرتبطة بالدعوى
        </h6>

        <!-- جدول المذكرات -->
        <div class="table-responsive mt-2" id="arrest-memos-table-area" style="display:none;">
          <table class="table table-sm table-hover" id="arrest-memos-table">
            <thead class="table-light">
              <tr>
                <th>رقم الدعوى</th>
                <th>اسم الطرف</th>
                <th>الإفراج</th>
                <th>مدة التوقيف</th>
                <th>سبب التوقيف</th>
                <th>مركز الإصلاح والتأهيل</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
      </div>

    </div>
  </div>
</div>










@yield('chief-extra')
@endsection
<script>
  document.addEventListener("DOMContentLoaded", function () {

  const modalId = "arrest-memos-modal";

  const $ = id => document.getElementById(id);

  const caseNumberInput = $("arrest-memos-case-number");
  const searchBtn = $("arrest-memos-search-btn");
  const alertBox = $("arrest-memos-alert");

  const tableArea = $("arrest-memos-table-area");
  const tableBody = document.querySelector("#arrest-memos-table tbody");
  const titleLabel = $("arrest-memos-title");

  function showAlert(msg, type = "warning") {
    alertBox.innerHTML = `<div class="alert alert-${type}">${msg}</div>`;
  }

  function clearAlert() {
    alertBox.innerHTML = "";
  }

  function resetUI() {
    clearAlert();
    tableArea.style.display = "none";
    titleLabel.style.display = "none";
    tableBody.innerHTML = "";
  }

  // 🔎 زر البحث
  searchBtn.addEventListener("click", function () {

    resetUI();

    const number = caseNumberInput.value.trim();
    if (!number) {
      showAlert("⚠️ يرجى إدخال رقم الدعوى");
      return;
    }

    showAlert("⏳ جاري تحميل بيانات المذكرات ...", "info");

    fetch(`/writer/arrest-memos/${encodeURIComponent(number)}`)
      .then(res => res.json().then(j => ({ ok: res.ok, json: j })))
      .then(({ ok, json }) => {

        if (!ok) throw json;

        clearAlert();

        const memos = json.memos ?? [];

        if (!memos.length) {
          showAlert("⚠️ لا توجد مذكرات توقيف لهذه الدعوى");
          return;
        }

        // عرض العنوان
        titleLabel.style.display = "block";

        // بناء الجدول
        tableBody.innerHTML = "";

        memos.forEach(m => {
          const tr = document.createElement("tr");

          tr.innerHTML = `
            <td>${m.case_number}</td>
            <td>${m.participant_name}</td>
            <td>${m.released}</td>
            <td>${m.detention_duration}</td>
            <td>${m.detention_reason}</td>
            <td>${m.detention_center}</td>
          `;

          tableBody.appendChild(tr);
        });

        tableArea.style.display = "block";

      })
      .catch(err => {
        console.error(err);
        showAlert(err.error ?? "❌ حدث خطأ أثناء تحميل البيانات", "danger");
      });

  });

  // إعادة ضبط النافذة عند الإغلاق
  document.getElementById(modalId).addEventListener("hidden.bs.modal", resetUI);

});








</script>

<script>
    //مذكرة توقيف
document.addEventListener("DOMContentLoaded", function () {

  const modalId = "arrest-memo-modal";
  const modalEl = document.getElementById(modalId);

  const $ = id => document.getElementById(id);

  // عناصر الإدخال
  const caseNumberInput = $("arrest-case-number");
  const searchBtn = $("arrest-search-btn");

  const tribunalEl = $("arrest-tribunal");
  const departmentEl = $("arrest-department");
  const yearEl = $("arrest-year");
  const caseTitleEl = $("arrest-case-title");

  const caseTypeArea = $("arrest-case-type-area");
  const participantsArea = $("arrest-participants-area");
  const extraArea = $("arrest-extra-area");

  const participantsTableBody = document.querySelector("#arrest-participants-table tbody");

  const judgeNameInput = $("arrest-judge-name");
  const durationInput = $("arrest-duration");
  const reasonSelect = $("arrest-reason");
  const centerSelect = $("arrest-center");

  const saveBtn = $("arrest-save-btn");
  const saveCloseBtn = $("arrest-save-close-btn");

  const alertBox = $("arrest-alert");

  let selectedParticipant = null;
  let currentCaseNumber = null;

  function showAlert(msg, type = "warning") {
    alertBox.innerHTML = `<div class="alert alert-${type}">${msg}</div>`;
  }

  function clearAlert() { alertBox.innerHTML = ""; }

  function resetUI() {
    clearAlert();
    participantsTableBody.innerHTML = "";
    caseTypeArea.style.display = "none";
    participantsArea.style.display = "none";
    extraArea.style.display = "none";
    judgeNameInput.value = "";

    saveBtn.disabled = true;
    saveCloseBtn.disabled = true;
  }

  // 🔍 زر البحث
  searchBtn.addEventListener("click", function () {

    resetUI();

    const caseNumber = caseNumberInput.value.trim();
    if (!caseNumber) {
      showAlert("⚠️ يرجى إدخال رقم الدعوى");
      return;
    }

    showAlert("⏳ جاري جلب بيانات الدعوى ...", "info");

    fetch("/writer/arrest-memo", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        case_number: caseNumber
      })
    })
    .then(res => res.json().then(j => ({ ok: res.ok, json: j })))
    .then(({ ok, json }) => {

      if (!ok) throw json;

      clearAlert();

      // حفظ رقم الدعوى
      currentCaseNumber = caseNumber;

      // تعبئة بيانات المحكمة
      tribunalEl.textContent = json.case?.tribunal?.number ?? "-";
      departmentEl.textContent = json.case?.department?.number ?? "-";
      yearEl.textContent = json.case?.year ?? "-";

      // نوع الدعوى
      caseTitleEl.textContent = json.case?.title ?? "-";
      caseTypeArea.style.display = "block";

      // اسم القاضي
      judgeNameInput.value = json.judge_name ?? "";

      // عرض الأطراف
      const parts = json.participants ?? [];
      if (!parts.length) {
        showAlert("⚠️ لا يوجد أطراف لهذه الدعوى");
        return;
      }

      participantsTableBody.innerHTML = "";

      parts.forEach(p => {
        const tr = document.createElement("tr");

        tr.innerHTML = `
          <td><input type="radio" name="arrest_participant" value="${p.name}"></td>
          <td>${p.name}</td>
          <td>${p.type}</td>
          <td>${p.job ?? ""}</td>
          <td>${p.residence ?? ""}</td>
          <td>${p.phone ?? ""}</td>
          <td>الأمن العام</td>
        `;

        tr.querySelector("input").addEventListener("change", () => {
          selectedParticipant = p.name;
          extraArea.style.display = "block";
          validateForm();
        });

        participantsTableBody.appendChild(tr);
      });

      participantsArea.style.display = "block";
    })
    .catch(err => {
      console.error(err);
      showAlert(err.error ?? "❌ حدث خطأ أثناء جلب بيانات الدعوى", "danger");
    });

  });

  // ➕ التحقق من جاهزية الحفظ
  function validateForm() {

    const valid =
      selectedParticipant &&
      durationInput.value &&
      reasonSelect.value &&
      centerSelect.value;

    saveBtn.disabled = !valid;
    saveCloseBtn.disabled = !valid;
  }

  durationInput.addEventListener("input", validateForm);
  reasonSelect.addEventListener("change", validateForm);
  centerSelect.addEventListener("change", validateForm);

  // 💾 زر الحفظ
  function submitArrestMemo(closeAfter) {
    clearAlert();

    fetch("/writer/arrest-memo", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        case_number: currentCaseNumber,
        participant_name: selectedParticipant,
        detention_duration: durationInput.value,
        detention_reason: reasonSelect.value,
        detention_center: centerSelect.value,
        save: true
      })
    })
    .then(res => res.json().then(j => ({ ok: res.ok, json: j })))
    .then(({ ok, json }) => {
      if (!ok) throw json;

      showAlert("✅ تم حفظ مذكرة التوقيف بنجاح", "success");

      if (closeAfter) {
        setTimeout(() => {
          const modal = bootstrap.Modal.getInstance(modalEl);
          modal.hide();
        }, 700);
      }

    })
    .catch(err => {
      console.error(err);
      showAlert(err.error ?? "❌ خطأ أثناء حفظ مذكرة التوقيف", "danger");
    });
  }

  saveBtn.addEventListener("click", () => submitArrestMemo(false));
  saveCloseBtn.addEventListener("click", () => submitArrestMemo(true));

  // إعادة ضبط عند إغلاق النافذة
  modalEl.addEventListener("hidden.bs.modal", resetUI);

});
</script>










<script>
    //إدارة تباليغ الدعوى
document.addEventListener("DOMContentLoaded", function () {

  const modalId = "manage-notifications-modal";
  const $ = id => document.getElementById(id);

  const caseInput = $("manage-notifications-case-number");
  const alertBox = $("manage-notifications-alert");
  const tableArea = $("manage-notifications-table-area");
  const tableBody = document.querySelector("#manage-notifications-table tbody");

  function showAlert(msg, type="info") {
    alertBox.innerHTML = `<div class="alert alert-${type}">${msg}</div>`;
  }

  function clearAlert() { alertBox.innerHTML = ""; }

  function resetTable() {
    tableArea.style.display = "none";
    tableBody.innerHTML = "";
  }

  // ⬅ البحث عند الضغط على Enter
  caseInput.addEventListener("keyup", function (e) {
    if (e.key === "Enter") {
      fetchNotifications();
    }
  });

  function fetchNotifications() {
    clearAlert();
    resetTable();

    const caseNumber = caseInput.value.trim();
    if (!caseNumber) {
      showAlert("⚠️ أدخل رقم الدعوى", "warning");
      return;
    }

    showAlert("⏳ جاري تحميل البيانات...");

    fetch(`/writer/case-notifications/${encodeURIComponent(caseNumber)}`)
      .then(res => res.json().then(j => ({ ok: res.ok, json: j })))
      .then(({ ok, json }) => {

        if (!ok) throw json;

        clearAlert();

        const notifications = json.notifications || [];

        if (!notifications.length) {
          showAlert("⚠️ لا يوجد أي تبليغات لهذه الدعوى", "warning");
          return;
        }

        tableBody.innerHTML = "";
        notifications.forEach(n => {
          const tr = document.createElement("tr");

          tr.innerHTML = `
            <td>${n.case_number}</td>
            <td>${n.participant_type}</td>
            <td>${n.participant_name}</td>
            <td>${n.method}</td>
            <td>${n.notified_at ?? "-"}</td>
          `;

          tableBody.appendChild(tr);
        });

        tableArea.style.display = "block";

      })
      .catch(err => {
        console.error(err);
        resetTable();
        showAlert(err.error ?? "❌ حدث خطأ أثناء تحميل التبليغات", "danger");
      });
  }

  // مسح البيانات عند إغلاق النافذة
  document.getElementById(modalId).addEventListener("hidden.bs.modal", function () {
    clearAlert();
    resetTable();
    caseInput.value = "";
  });

});
</script>






<script>
    //تبليغ مشتكي عليه
document.addEventListener("DOMContentLoaded", function () {

  const modalId = "notif-complainant-modal";
  const $alert = id => document.getElementById(id);

  const caseInput = $alert("notif-complainant-case-number");
  const searchBtn = $alert("notif-complainant-search");
  const caseInfoDiv = $alert("notif-complainant-case-info");
  const tribunalEl = $alert("notif-complainant-tribunal");
  const departmentEl = $alert("notif-complainant-department");
  const yearEl = $alert("notif-complainant-year");
  const titleEl = $alert("notif-complainant-title");

  const participantsArea = $alert("notif-complainant-participants-area");
  const participantsTableBody = document.querySelector("#notif-complainant-participants-table tbody");

  const methodArea = $alert("notif-complainant-method-area");
  const methodSelect = $alert("notif-complainant-method");
  const notesInput = $alert("notif-complainant-notes");

  const saveBtn = $alert("notif-complainant-save");
  const alertBox = $alert("notif-complainant-alert");

  let selectedParticipantName = null;
  let currentCaseId = null; // ← ID القضية الحقيقي

  function showAlertHTML(html, type = "info") {
    alertBox.innerHTML = `<div class="alert alert-${type}">${html}</div>`;
  }

  function clearAlert() { alertBox.innerHTML = ""; }

  function clearCaseDisplay() {
    caseInfoDiv.style.display = "none";
    participantsArea.style.display = "none";
    methodArea.style.display = "none";
    participantsTableBody.innerHTML = "";
    saveBtn.disabled = true;
    selectedParticipantName = null;
    currentCaseId = null;
  }

  // عند الضغط على بحث
  searchBtn.addEventListener("click", function () {
    clearAlert();
    participantsTableBody.innerHTML = "";
    methodArea.style.display = "none";
    participantsArea.style.display = "none";
    caseInfoDiv.style.display = "none";
    saveBtn.disabled = true;

    const number = caseInput.value.trim();
    if (!number) {
      showAlertHTML("⚠️ أدخل رقم الدعوى للبحث", "warning");
      return;
    }

    showAlertHTML("جاري جلب بيانات القضية...", "info");

    const notificationType = "مذكرة تبليغ مشتكى عليه";

    fetch(`/court-cases/${encodeURIComponent(number)}?notification_type=${encodeURIComponent(notificationType)}`)
      .then(async res => {
        const json = await res.json();
        if (!res.ok) throw json;
        return json;
      })
      .then(data => {
        clearAlert();

        // ← حفظ ID الحقيقي للقضية
        currentCaseId = data.id;

        tribunalEl.textContent = data.tribunal?.name ?? "-";
        departmentEl.textContent = data.department?.name ?? "-";
        yearEl.textContent = data.year ?? "-";
        titleEl.textContent = data.title ?? "-";

        caseInfoDiv.style.display = "block";

        const parts = data.participants ?? [];
        if (!parts.length) {
          showAlertHTML("⚠️ لا يوجد أطراف من نوع 'مشتكى عليه' في هذه القضية.", "warning");
          return;
        }

        participantsTableBody.innerHTML = "";
        parts.forEach(p => {
          const tr = document.createElement("tr");

          tr.innerHTML = `
            <td><input type="radio" name="notif_complaint_participant" value="${escapeHtml(p.name)}"></td>
            <td>${escapeHtml(p.name)}</td>
            <td>${escapeHtml(p.national_id ?? "")}</td>
            <td>${escapeHtml(p.type ?? "")}</td>
            <td>${escapeHtml(p.residence ?? "")}</td>
            <td>${escapeHtml(p.job ?? "")}</td>
            <td>${escapeHtml(p.phone ?? "")}</td>
          `;

          tr.querySelector('input[type="radio"]').addEventListener('change', function () {
            selectedParticipantName = this.value;
            methodArea.style.display = "block";
            saveBtn.disabled = !methodSelect.value;
            clearAlert();
          });

          participantsTableBody.appendChild(tr);
        });

        participantsArea.style.display = "block";
      })
      .catch(err => {
        console.error(err);
        clearCaseDisplay();
        if (err && err.error) showAlertHTML(err.error, "warning");
        else showAlertHTML("❌ حدث خطأ أثناء جلب بيانات القضية", "danger");
      });
  });

  // عندما يختار طريقة التبليغ
  methodSelect.addEventListener("change", function () {
    saveBtn.disabled = !this.value || !selectedParticipantName;
  });

  // حفظ التبليغ
  saveBtn.addEventListener("click", function () {
    clearAlert();

    if (!currentCaseId || !selectedParticipantName) {
      showAlertHTML("⚠️ اختر قضية وطرف وطريقة التبليغ أولاً", "warning");
      return;
    }

    const payload = {
      case_id: currentCaseId, // ← ID الصحيح
      participant_name: selectedParticipantName,
      method: methodSelect.value,
      notes: notesInput?.value || null
    };

    saveBtn.disabled = true;
    saveBtn.textContent = "جارٍ الحفظ...";

    fetch("{{ route('notifications.save') }}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').content
      },
      body: JSON.stringify(payload)
    })
    .then(async res => {
      const json = await res.json();
      if (!res.ok) throw json;
      return json;
    })
    .then(ret => {
      showAlertHTML("✅ تم حفظ التبليغ بنجاح", "success");
    })
    .catch(err => {
      console.error(err);
      if (err && err.error) showAlertHTML(err.error, "danger");
      else showAlertHTML("❌ حدث خطأ أثناء حفظ التبليغ", "danger");
    })
    .finally(() => {
      saveBtn.disabled = false;
      saveBtn.textContent = "حفظ التبليغ";
    });
  });

  function escapeHtml(text) {
    if (text === null || text === undefined) return "";
    return String(text)
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  document.getElementById(modalId).addEventListener('hidden.bs.modal', function () {
    clearCaseDisplay();
    clearAlert();
  });

});
</script>


<script>
    //خيارات التباليغ
document.addEventListener("DOMContentLoaded", function () {

    const mainTrigger = document.getElementById("trigger-notifications");
    const menu = document.getElementById("notifications-menu");

    // عند الضغط على كلمة التباليغ
    mainTrigger.addEventListener("click", function () {
        menu.style.display = (menu.style.display === "none") ? "block" : "none";
    });

    // تفعيل القوائم الفرعية
    document.querySelectorAll(".submenu-title").forEach(title => {
        title.addEventListener("click", function () {
            const items = this.nextElementSibling;
            items.style.display = (items.style.display === "none") ? "block" : "none";
        });
    });

    // إغلاق القائمة عند الضغط خارجها
    document.addEventListener("click", function (e) {
        if (!menu.contains(e.target) && e.target !== mainTrigger) {
            menu.style.display = "none";
        }
    });

});
</script>












<script>
// نافذة سحب قضية من الشرطة 
document.addEventListener("DOMContentLoaded", function () {

    const searchBtn = document.getElementById("searchPoliceCases");
    const centerSelect = document.getElementById("police_center");
    const tbody = document.querySelector('#policeCasesTable tbody');
    const alertBox = document.getElementById("policeAlert");
    const pullBtn = document.getElementById("pullSelectedCaseBtn");

    let selectedRow = null;
    let selectedCaseId = null;

    // ----------------------
    // دالة عرض الرسائل
    // ----------------------
    function showAlert(message, type = "info") {
        alertBox.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
    }

    function clearAlert() {
        alertBox.innerHTML = "";
    }

    function format(date) {
        return date ? new Date(date).toLocaleDateString() : "-";
    }

    // ----------------------
    // اختيار الصف من الجدول
    // ----------------------
    function selectRow(row, caseId) {
        if (selectedRow) {
            selectedRow.classList.remove('selected');
        }
        selectedRow = row;
        selectedCaseId = caseId;
        row.classList.add('selected');
        pullBtn.disabled = false;
    }

    // ----------------------
    // عند الضغط على زر البحث
    // ----------------------
    searchBtn.addEventListener("click", function () {
        clearAlert();
        tbody.innerHTML = '<tr><td colspan="5">جارٍ البحث...</td></tr>';

        const center = centerSelect.value.trim();
        if (!center) {
            showAlert('⚠️ اختر المركز الأمني', 'warning');
            tbody.innerHTML = '';
            return;
        }

        fetch(`/police-cases/by-center/${encodeURIComponent(center)}`)
            .then(res => res.json())
            .then(data => {

                tbody.innerHTML = '';

                if (data.message || data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="5">لا توجد بيانات</td></tr>`;
                    return;
                }

                data.forEach(c => {
                    const row = tbody.insertRow();
                    row.innerHTML = `
                        <td>${c.center_name || c.police_station || '-'}</td>
                        <td>${c.police_case_number || '-'}</td>
                        <td>${format(c.police_registration_date || c.registration_date)}</td>
                        <td>${format(c.crime_date)}</td>
                        <td>${c.status || '-'}</td>
                    `;
                    row.onclick = () => selectRow(row, c.id);
                });

                selectedRow = null;
                selectedCaseId = null;
                pullBtn.disabled = true;
            })
            .catch(err => {
                console.error(err);
                showAlert("❌ حدث خطأ أثناء جلب القضايا", "danger");
                tbody.innerHTML = '';
            });
    });

    // ----------------------
    // زر السحب
    // ----------------------
    pullBtn.addEventListener("click", function () {

        if (!selectedCaseId) {
            showAlert('⚠️ اختر صف من الجدول لسحب الدعوى', 'warning');
            return;
        }

        pullBtn.disabled = true;
        pullBtn.innerText = "جاري السحب...";

        fetch(`/writer/pull-police-case/${selectedCaseId}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            }
        })
            .then(res => res.json())
            .then(response => {
                showAlert(response.message ?? "✅ تم سحب الدعوى بنجاح", "success");

                // حذف الصف بعد السحب
                if (selectedRow) {
                    selectedRow.remove();
                    selectedRow = null;
                }

                selectedCaseId = null;
                pullBtn.disabled = true;
            })
            .catch(err => {
                console.error(err);
                showAlert("❌ حدث خطأ أثناء سحب القضية", "danger");
            })
            .finally(() => {
                pullBtn.disabled = true;
                pullBtn.innerText = "سحب الدعوى";
            });
    });

});
</script>







<script>
    //نافذة سحب دعوى من المدعي العام 
    document.addEventListener("DOMContentLoaded", function () {

    window.pullCase = function () {

        let case_number = document.getElementById("case_number").value;
        let court_location = document.getElementById("court_location").value;
        let prosecutor_office = document.getElementById("prosecutor_office").value;

        let resultBox = document.getElementById("pullResult");
        resultBox.classList.add("d-none");

        fetch("/cases/pull", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                case_number: case_number,
                court_location: court_location,
                prosecutor_office: prosecutor_office
            })
        })
        .then(res => res.json())
        .then(response => {

            resultBox.classList.remove("d-none");

            if (response.error) {
                resultBox.classList.add("alert-danger");
                resultBox.classList.remove("alert-success");
                resultBox.innerHTML = response.error;
            } else {
                resultBox.classList.add("alert-success");
                resultBox.classList.remove("alert-danger");
                resultBox.innerHTML = response.message;

                // تفريغ رقم الدعوى بعد السحب
                document.getElementById("case_number").value = "";
            }

        })
        .catch(error => {
            resultBox.classList.remove("d-none");
            resultBox.classList.add("alert-danger");
            resultBox.innerHTML = "حدث خطأ غير متوقع";
            console.error(error);
        });
    };

});


</script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    function fetchRequestSchedule() {
        const requestNumber = document.getElementById('requestNumberInput').value;

        if (!requestNumber) {
            alert('يرجى إدخال رقم الطلب');
            return;
        }

        // ✅ تعديل المسار ليكون خاص بالكاتب
        fetch('/writer/request-schedule', {
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

                    // ✅ عرض معلومات المحكمة
                    console.log("📥 Full record:", first);
                    console.log("🔑 Keys:", Object.keys(first));
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

    // ✅ ربط الدالة بزر الإدخال إذا ضغط Enter
    const input = document.getElementById('requestNumberInput');
    if (input) {
        input.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                fetchRequestSchedule();
            }
        });
    }

});
</script>





















<script>
    //نافذه المشاركين
document.addEventListener("DOMContentLoaded", function () {

    console.log("📌 participants JS Loaded");

    window.searchCivilRegistry = function () {

        console.log("📌 Starting Civil Registry Search...");

        const params = {
            first_name: document.getElementById("first_name").value.trim(),
            father_name: document.getElementById("father_name").value.trim(),
            mother_name: document.getElementById("mother_name").value.trim(),
            grandfather_name: document.getElementById("grandfather_name").value.trim(),
            family_name: document.getElementById("family_name").value.trim(),
            occupation: document.getElementById("occupation").value.trim(),
            nationality: document.getElementById("nationality").value.trim(),
            gender: document.getElementById("gender").value.trim(),
        };

        fetch("/civil-registry/search", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(params)
        })
        .then(res => res.json())
        .then(data => {
            console.log("📥 Civil Registry Results:", data);

            // ✅ خزّن البيانات في متغير عام للوصول من الـ Console
            window.civilData = data;

            // اطبع المفاتيح والقيمة للتأكد
            if (Array.isArray(data) && data.length > 0) {
                console.log(" Keys:", Object.keys(data[0]));
                console.log("x First Name Value:", data[0].first_name);
            }

            const tbody = document.getElementById("civilResults");
            tbody.innerHTML = "";

            if (!Array.isArray(data) || data.length === 0) {
                tbody.innerHTML = `<tr id="emptyRow"><td class="empty" colspan="16">لا توجد نتائج مطابقة</td></tr>`;
                return;
            }

            data.forEach(item => {
                const tr = document.createElement('tr');
                
                // Format birth_date to show only date part (YYYY-MM-DD)
                let birthDate = '-';
                if (item.birth_date) {
                    birthDate = item.birth_date.substring(0, 10);
                }

                tr.innerHTML = `
                    <td>${item.national_id ?? '-'}</td>
                    <td>${item.first_name ?? '-'}</td>
                    <td>${item.father_name ?? '-'}</td>
                    <td>${item.mother_name ?? '-'}</td>
                    <td>${item.grandfather_name ?? '-'}</td>
                    <td>${item.family_name ?? '-'}</td>
                    <td>${birthDate}</td>
                    <td>${item.age ?? '-'}</td>
                    <td>${item.gender ?? '-'}</td>
                    <td>${item.religion ?? '-'}</td>
                    <td>${item.nationality ?? '-'}</td>
                    <td>${item.place_of_birth ?? '-'}</td>
                    <td>${item.occupation ?? '-'}</td>
                    <td>${item.education_level ?? '-'}</td>
                    <td>${item.phone_number ?? '-'}</td>
                    <td>${item.record_location ?? '-'}</td>
                `;

                tbody.appendChild(tr);
            });

        })
        .catch(err => {
            console.error("❌ Error:", err);
            alert("حدث خطأ أثناء البحث");
        });
    };

});
</script>




@push('scripts')
<!-- قائمة محاضر الجلسات الخاصة بالكاتب -->
<script>
function openRecordprint() {
    window.open('/recordprint', '_blank');
}
function openAfterprint() {
    window.open('/afterprint', '_blank');
}

document.addEventListener("DOMContentLoaded", () => {
    const status = document.getElementById("case-box")?.dataset.status;

    const btnRecord = document.getElementById("btnRecord");
    const btnAfter  = document.getElementById("btnAfter");

    if (status === "مستمرة" || status === "مكتملة") {
        if (btnRecord) btnRecord.style.display = "inline-block";
        if (btnAfter) btnAfter.style.display = "inline-block";

    } else if (status === "محددة") {
        if (btnRecord) btnRecord.style.display = "inline-block";
        if (btnAfter) btnAfter.style.display = "none";

    } else if (status === "مؤجلة" || status === "مفصولة") {
        if (btnRecord) btnRecord.style.display = "none";
        if (btnAfter) btnAfter.style.display = "none";
    }
});
</script>
<script>
// إظهار قائمة الجلسات
document.addEventListener('DOMContentLoaded', function () {

    const trigger = document.getElementById('sessions-trigger'); // من layouts.app
    const menu = document.getElementById('writer-sessions-submenu');

    if (!trigger || !menu) {
        console.warn('⚠️ sessions-trigger or writer-sessions-submenu not found');
        return;
    }

    // ⭐ إظهار القائمة تحت كلمة "الجلسات"
    trigger.addEventListener('mouseenter', () => {

        // موقع كلمة الجلسات على الشاشة
        const rect = trigger.getBoundingClientRect();

        // حساب المكان بدقة تحت الكلمة
        menu.style.top  = (rect.bottom + window.scrollY) + "px";     // تحت الكلمة مباشرة
        menu.style.right = (window.innerWidth - rect.right) + "px";  // محاذاة يمين الكلمة

        menu.style.display = "block";
    });

    // ⭐ إخفاء القائمة عندما يخرج الماوس
    trigger.addEventListener('mouseleave', () => {
        setTimeout(() => {
            if (!menu.matches(':hover')) {
                menu.style.display = "none";
            }
        }, 150);
    });

    menu.addEventListener('mouseleave', () => {
        menu.style.display = "none";
    });

});
</script>
<script>
window.openReportsListModal = function () {
    const modal = new bootstrap.Modal(document.getElementById('reportsListModal'));
    modal.show();
    loadReportsList();
}

async function loadReportsList() {

    const container = document.getElementById("reportsContainer");
    container.innerHTML = `<p class="text-center text-secondary">جاري التحميل...</p>`;

    try {
        // ✅ نستخدم الراوت بالاسم
        const response = await axios.get("{{ route('writer.reports.list') }}");
        const data = response.data.reports || [];

        if (data.length === 0) {
            container.innerHTML = `<p class="text-danger text-center">لا يوجد محاضر</p>`;
            return;
        }

        // ✅ قوالب الروابط مع PLACEHOLDER
        const trialUrlTemplate = `{{ route('writer.trial.report.show', ['session' => 'SESSION_ID']) }}`;
        const afterUrlTemplate = `{{ route('writer.after-trial.report.show', ['session' => 'SESSION_ID']) }}`;

        let html = "";

        data.forEach(row => {

            const c = row.case;          // { id, number, type }
            const modes = row.modes || [];
            const sessionId = row.session_id;

            // ✅ نبدل SESSION_ID بالـ sessionId الحقيقي
            const trialUrl = trialUrlTemplate.replace('SESSION_ID', sessionId);
            const afterUrl = afterUrlTemplate.replace('SESSION_ID', sessionId);

            html += `
                <div class="border rounded p-3 mb-2">
                    <h6>قضية: ${c.number} — ${c.type}</h6>

                    <div class="mt-2 d-flex gap-2">
                        ${modes.includes('trial')
                            ? `<a class="btn btn-primary btn-sm" href="${trialUrl}" target="_blank">محضر المحاكمة</a>`
                            : ''}

                        ${modes.includes('after')
                            ? `<a class="btn btn-secondary btn-sm" href="${afterUrl}" target="_blank">محضر ما بعد</a>`
                            : ''}
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;

    } catch (e) {
        console.error('❌ خطأ أثناء تحميل المحاضر:', e);
        container.innerHTML = `<p class="text-danger text-center">فشل التحميل</p>`;
    }
}
</script>
















<script>
document.addEventListener("DOMContentLoaded", () => {

    console.log("📌 JS تسجيل الطلب يعمل...");

    let currentRequestId = null;

    // فتح نافذة تسجيل الطلب
    const openBtn = document.getElementById("open-register-request");
    const modalEl = document.getElementById("registerRequestModal");

    if (openBtn && modalEl) {
        openBtn.addEventListener("click", () => {

            const modal = new bootstrap.Modal(modalEl);
            modal.show();

            document.getElementById("reqCourtNumber").value = "{{ auth()->user()->tribunal->number }}";
            document.getElementById("reqDepartmentNumber").value = "{{ auth()->user()->department->number }}";
            document.getElementById("reqYear").value = new Date().getFullYear();

            currentRequestId = null;
        });
    }

    // ⭐ توليد رقم الطلب
    const requestNumberInput = document.getElementById("requestNumber");
    if (requestNumberInput) {
        requestNumberInput.addEventListener("keydown", async (e) => {

            if (e.key !== "Enter") return;
            e.preventDefault();

            const type = document.getElementById("requestType").value;

            if (!type) {
                alert("❌ اختر نوع الطلب أولاً");
                return;
            }

            try {
                const response = await axios.post("/writer/request/store-number", { type });
                const d = response.data;

                currentRequestId = d.id;

                document.getElementById("requestNumber").value = d.request_number;
                document.getElementById("reqJudgeName").value  = d.judge_name;
                document.getElementById("reqJudgeId").value    = d.judge_id;
                document.getElementById("reqSessionDate").value = d.session_date;

            } catch (err) {
                console.error(err);
            }
        });
    }

    // ⭐ إضافة طرف
    const addPartyBtn = document.getElementById("addRequestParty");
    
    if (addPartyBtn) {
        addPartyBtn.addEventListener("click", () => {
            
            const partyTemplate = document.getElementById("partyTemplate");
            const partiesContainer = document.getElementById("partiesContainer");
            
            if (!partyTemplate || !partiesContainer) {
                console.error("❌ Template or container not found");
                return;
            }

            // استخدام template.content للحصول على المحتوى
            let clone = partyTemplate.content.cloneNode(true);

            // البحث عن زر الحذف في النسخة المستنسخة
            const removeBtn = clone.querySelector(".remove-party");
            if (removeBtn) {
                removeBtn.addEventListener("click", function() {
                    // حذف العنصر الأب (party-block)
                    this.closest('.party-block').remove();
                });
            }

            partiesContainer.appendChild(clone);
        });
    }

    // ⭐ مسح الكل
    const clearBtn = document.getElementById("clearRequest");
    if (clearBtn) {
        clearBtn.addEventListener("click", () => {
            if (confirm("هل أنت متأكد من مسح جميع البيانات؟")) {
                document.getElementById("requestForm").reset();
                
                // حذف الأطراف المضافة (ماعدا الأول)
                const allParties = partiesContainer.querySelectorAll(".party-block");
                allParties.forEach((party, index) => {
                    if (index > 0) party.remove();
                });

                currentRequestId = null;
            }
        });
    }

    // ⭐ حفظ ومتابعة
    const saveBtn = document.getElementById("saveRequest");
    if (saveBtn) {
        saveBtn.addEventListener("click", async () => {
            await saveRequestData(false);
        });
    }

    // ⭐ حفظ وإنهاء
    const saveAndFinishBtn = document.getElementById("saveAndFinishRequest");
    if (saveAndFinishBtn) {
        saveAndFinishBtn.addEventListener("click", async () => {
            await saveRequestData(true);
        });
    }

    // ⭐ دالة حفظ الطلب
    async function saveRequestData(closeModal = false) {

        if (!currentRequestId) {
            alert("❌ اضغط Enter لتوليد رقم الطلب أولاً");
            return;
        }

        let parties = [];

        // جمع بيانات جميع الأطراف
        document.querySelectorAll("#partiesContainer .request-party").forEach(block => {

            const p = {
                type:       block.querySelector(".request-party-type").value,
                name:       block.querySelector(".request-party-name").value,
                national_id:block.querySelector(".request-party-nid").value,
                residence:  block.querySelector(".request-party-residence").value,
                job:        block.querySelector(".request-party-job").value,
                phone:      block.querySelector(".request-party-phone").value,
            };

            if (p.type && p.name) {
                parties.push(p);
            }
        });

        if (parties.length === 0) {
            alert("❌ يجب إدخال طرف واحد على الأقل");
            return;
        }

        try {
            const res = await axios.post("/requests/store-parties", {
                request_id: currentRequestId,
                parties: parties,
            });

            alert("✔ تم حفظ الطلب بنجاح");

            if (closeModal) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }

        } catch (err) {
            console.error(err);
            alert("❌ خطأ أثناء حفظ الأطراف");
        }
    }

});
</script>












<script>
document.addEventListener('DOMContentLoaded', function () {

    console.log("🔥 participants JS Loaded");

    /* ============================================================
       🔍 وظيفة البحث بالأحوال المدنية
    ============================================================ */
    window.searchCivilRegistry = function () {

        const payload = {
            first_name:     document.getElementById("first_name").value,
            father_name:    document.getElementById("father_name").value,
            mother_name:    document.getElementById("mother_name").value,
            grandfather_name: document.getElementById("grandfather_name").value,
            family_name:    document.getElementById("family_name").value,
            gender:         document.getElementById("gender").value,
            occupation:     document.getElementById("occupation").value,
            nationality:    document.getElementById("nationality").value,
            // ✅ حذفنا birth_date من الـ payload
        };

        console.log("📤 Sending request → /civil-registry/search", payload);

        fetch("/civil-registry/search", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {

            console.log("📥 Civil Registry Results:", data);

            const tbody = document.getElementById("civilResults");
            tbody.innerHTML = "";

            if (!data || data.length === 0) {
                // ✅ عدد الأعمدة = 16 الآن (بدون عمود اختيار)
                tbody.innerHTML = `<tr><td colspan="16" class="text-danger text-center">لا توجد نتائج</td></tr>`;
                return;
            }

            data.forEach(item => {

                // ✅ تنسيق تاريخ الميلاد (قص أول 10 خانات فقط)
                let birthDate = '-';
                if (item.birth_date) {
                    birthDate = item.birth_date.toString().substring(0, 10);
                }

                tbody.innerHTML += `
                    <tr>
                        <td>${item.national_id       ?? '-'}</td>
                        <td>${item.first_name         ?? '-'}</td>
                        <td>${item.father_name       ?? '-'}</td>
                        <td>${item.mother_name       ?? '-'}</td>
                        <td>${item.grandfather_name  ?? '-'}</td>
                        <td>${item.family_name       ?? '-'}</td>
                        <td>${birthDate}</td>
                        <td>${item.age               ?? '-'}</td>
                        <td>${item.gender            ?? '-'}</td>
                        <td>${item.religion          ?? '-'}</td>
                        <td>${item.nationality       ?? '-'}</td>
                        <td>${item.place_of_birth    ?? '-'}</td>
                        <td>${item.occupation        ?? '-'}</td>
                        <td>${item.education_level   ?? '-'}</td>
                        <td>${item.phone_number      ?? '-'}</td>
                        <td>${item.record_location   ?? '-'}</td>
                        <!-- ✅ حذفنا عمود اختيار -->
                    </tr>
                `;
            });

        })
        .catch(err => {
            console.error("❌ Civil Registry Error:", err);
            alert("حدث خطأ أثناء البحث في السجل المدني");
        });

    }; // END searchCivilRegistry



    /* ============================================================
       🟦 دالة selectCivil (موجودة احتياطًا لو احتجتيها لاحقًا)
       — حاليًا ما في زر اختيار في الجدول
    ============================================================ */
    window.selectCivil = function (item) {

        console.log("✔ Selected Civil Person:", item);

        if (document.getElementById("partyName")) {
            document.getElementById("partyName").value =
                `${item.first_name ?? ''} ${item.father_name ?? ''} ${item.grandfather_name ?? ''} ${item.family_name ?? ''}`;
        }

        if (document.getElementById("nationalId"))
            document.getElementById("nationalId").value = item.national_id ?? '';

        if (document.getElementById("residence"))
            document.getElementById("residence").value = item.current_address ?? '';

        if (document.getElementById("job"))
            document.getElementById("job").value = item.occupation ?? '';

        if (document.getElementById("phone"))
            document.getElementById("phone").value = item.phone_number ?? '';

        alert("✔ تم اختيار الشخص من السجل المدني");

        const modalEl = document.getElementById('participantsModal');
        if (modalEl) {
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        }
    };

}); // END DOMContentLoaded
</script>
<script>
  //إظهار القائمة الدعوى/الطلب
document.addEventListener('DOMContentLoaded', function () {

    const menu = document.getElementById('writer-case-options');
    const trigger = document.getElementById('trigger-cases'); // من layouts.app

    console.log("📌 Writer page loaded");

    if (!menu) console.warn("⚠️ القائمة غير موجودة!");
    if (!trigger) console.warn("⚠️ trigger-cases غير موجود في الصفحة!");

    if (!menu || !trigger) return;

    /* 📌 إظهار القائمة عندما يرسل layouts.event الإشارة */
    document.addEventListener('showWriterCasesMenu', () => {

        const rect = trigger.getBoundingClientRect();

        // ⭐ وضع القائمة تحت الكلمة في RTL
        menu.style.top = rect.bottom + window.scrollY + "px";
        menu.style.right = (window.innerWidth - rect.right) + "px";

        menu.style.display = "block";
        console.log("📌 القائمة ظهرت الآن");
    });

    /* 📌 إخفاء القائمة */
    // document.addEventListener('hideWriterCasesMenu', () => {

    //     setTimeout(() => {
    //         if (!menu.matches(':hover') && !trigger.matches(':hover')) {
    //             menu.style.display = "none";
    //             console.log("📌 القائمة اختفت");
    //         }
    //     }, 150);
    // });

    /* 📌 إخفاء عند خروج الماوس */
    menu.addEventListener('mouseleave', () => {
        if (!trigger.matches(':hover')) {
            menu.style.display = "none";
        }
    });

});
</script>

<script>
document.addEventListener("DOMContentLoaded", () => {

    console.log("📌 JS تسجيل الدعوى يعمل...");

    let currentCaseId = null;

    // فتح نافذة تسجيل الدعوى
    const openCaseBtn = document.getElementById("open-register-case");
    const caseModalEl = document.getElementById("registerCaseModal");

    if (openCaseBtn && caseModalEl) {
        openCaseBtn.addEventListener("click", () => {
            const modal = new bootstrap.Modal(caseModalEl);
            modal.show();
            currentCaseId = null;
        });

        // ✅ تنظيف الـ backdrop عند إغلاق النافذة
        caseModalEl.addEventListener('hidden.bs.modal', () => {
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        });
    }

    // ⭐ توليد رقم الدعوى
    const caseNumberInput = document.getElementById("caseNumber");
    if (caseNumberInput) {
        caseNumberInput.addEventListener("keydown", async (e) => {
            if (e.key !== "Enter") return;
            e.preventDefault();

            const type = document.getElementById("caseType").value;

            if (!type) {
                alert("❌ اختر نوع الدعوى أولاً");
                return;
            }

            try {
                const response = await axios.post("/court-cases/store", { 
                    type: type,
                    tribunal_number: document.getElementById("courtNumber").value,
                    department_number: document.getElementById("departmentNumber").value,
                    year: document.getElementById("caseYear").value
                });
                const d = response.data;

                console.log("📥 Generated Case Data:", d);
                currentCaseId = d.id;

                document.getElementById("caseNumber").value = d.number;
                document.getElementById("judge_name").value = d.judge_name;
                document.getElementById("judge_id").value = d.judge_id;
                document.getElementById("session_date").value = d.session_date;

            } catch (err) {
                console.error("❌ خطأ:", err);
                alert("❌ خطأ أثناء توليد رقم الدعوى");
            }
        });
    }

    // ⭐ إضافة طرف للدعوى
    const addCasePartyBtn = document.getElementById("addCaseParty");
    
    if (addCasePartyBtn) {
        addCasePartyBtn.addEventListener("click", () => {
            
            const casePartyTemplate = document.getElementById("casePartyTemplate");
            const partiesContainerCase = document.getElementById("partiesContainerCase");
            
            if (!casePartyTemplate || !partiesContainerCase) {
                console.error("❌ Template or container not found");
                return;
            }

            // استخدام template.content للحصول على المحتوى
            let clone = casePartyTemplate.content.cloneNode(true);

            // البحث عن زر الحذف في النسخة المستنسخة
            const removeBtn = clone.querySelector(".remove-party");
            if (removeBtn) {
                removeBtn.addEventListener("click", function() {
                    this.closest('.party-block').remove();
                });
            }

            partiesContainerCase.appendChild(clone);
        });
    }

    // ⭐ مسح الكل
    const clearCaseBtn = document.getElementById("clearCase");
    if (clearCaseBtn) {
        clearCaseBtn.addEventListener("click", () => {
            if (confirm("هل أنت متأكد من مسح جميع البيانات؟")) {
                document.getElementById("caseForm").reset();
                
                const partiesContainerCase = document.getElementById("partiesContainerCase");
                // حذف الأطراف المضافة (ماعدا الأول)
                const allParties = partiesContainerCase.querySelectorAll(".party-block");
                allParties.forEach((party, index) => {
                    if (index > 0) party.remove();
                });

                currentCaseId = null;
            }
        });
    }

    // ⭐ حفظ وإنهاء
    const saveAndFinishCaseBtn = document.getElementById("saveAndFinishCase");
    if (saveAndFinishCaseBtn) {
        saveAndFinishCaseBtn.addEventListener("click", async () => {
            await saveCaseData(true);
        });
    }

    // ⭐ دالة حفظ الدعوى
    async function saveCaseData(closeModal = false) {

        if (!currentCaseId) {
            alert("❌ اضغط Enter لتوليد رقم الدعوى أولاً");
            return;
        }

        let parties = [];

        // جمع بيانات جميع الأطراف
        document.querySelectorAll("#partiesContainerCase .case-party").forEach(block => {

            const p = {
                type:       block.querySelector(".case-party-type").value,
                name:       block.querySelector(".case-party-name").value,
                national_id:block.querySelector(".case-party-nid").value,
                phone:      block.querySelector(".case-party-phone").value,
                residence:  block.querySelector(".case-party-residence").value,
                job:        block.querySelector(".case-party-job").value,
                charge:     block.querySelector(".case-party-charge").value
            };

            if (p.type && p.name) {
                parties.push(p);
            }
        });

        if (parties.length === 0) {
            alert("❌ يجب إدخال طرف واحد على الأقل");
            return;
        }

        try {
            // ⭐ حفظ كل طرف على حدة
            for (const party of parties) {
                await axios.post("/participants/store", {
                    court_case_id: currentCaseId,
                    type:          party.type,
                    name:          party.name,
                    national_id:   party.national_id,
                    phone:         party.phone,
                    residence:     party.residence,
                    job:           party.job,
                    charge:        party.charge
                });
            }

            alert("✔ تم حفظ الدعوى بنجاح");

            if (closeModal) {
                const modal = bootstrap.Modal.getInstance(caseModalEl);
                if (modal) {
                    modal.hide();
                    
                    // ✅ تنظيف الـ backdrop مباشرة بعد الإغلاق
                    setTimeout(() => {
                        const backdrops = document.querySelectorAll('.modal-backdrop');
                        backdrops.forEach(backdrop => backdrop.remove());
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                    }, 300);
                }
            }

        } catch (err) {
            console.error("❌ خطأ:", err);
            alert("❌ خطأ أثناء حفظ الدعوى");
        }
    }

});
</script>





@endpush