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

<!--  قائمة الدعوى / الطلب الخاصة بالكاتب 
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
-->

<!-- <li id="open-register-request"
    style="padding:10px; border-bottom:1px solid #ddd; cursor:pointer;">
    تسجيل طلب
</li> -->
     <!--   <li style="padding:10px; border-bottom:1px solid #ddd; cursor:pointer;"
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
</div> -->



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
<!-- قائمة التباليغ الخاصة بالكاتب 
<div id="notifications-menu" class="floating-menu" style="display:none; position:absolute; top:120px; right:50px; background:white; border:1px solid #ccc; border-radius:6px; padding:10px; width:250px; z-index:9999;">
 
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
            <a class="submenu-item" href="#" 
   data-bs-toggle="modal" 
   data-bs-target="#notif-witness-modal">
    مذكرة تبليغ شاهد موعد جلسة
</a>
            <a class="submenu-item" href="#">مذكرة تبليغ حكم</a>
            <a class="submenu-item" href="#" 
   data-bs-toggle="modal" 
   data-bs-target="#manage-notifications-modal">
   إدارة تباليغ
</a>
        </div>
    </div>

    <hr>

  
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


-->










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
<!-- <ul id="writer-sessions-submenu"
    style="display:none; position:absolute;
           background:white; border:1px solid #ccc;
           padding:10px; min-width:180px;
           z-index:999999;">
    


  
</ul> -->



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
<style>
#notif-complainant-modal .modal-content {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.08);
}

#notif-complainant-modal h2 {
    text-align: center;
    margin-bottom: 18px;
    font-size: 20px;
}

#notif-complainant-modal .case-number-wrapper {
    margin-bottom: 20px;
}

#notif-complainant-modal .case-number-label {
    font-weight: bold;
    margin-bottom: 8px;
    display: block;
}

#notif-complainant-modal .case-number-inputs {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}

#notif-complainant-modal .case-number-inputs input {
    flex: 1;
    min-width: 120px;
    padding: 6px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

#notif-complainant-modal .case-number-inputs button {
    padding: 8px 20px;
    border-radius: 6px;
    border: 0;
    cursor: pointer;
    font-family: 'Cairo', sans-serif;
    font-weight: bold;
    font-size: 11px;
    background: #000;
    color: #fff;
}

#notif-complainant-modal .row {
    display: flex;
    gap: 12px;
    margin-bottom: 14px;
    align-items: center;
}

#notif-complainant-modal .col {
    flex: 1;
}

#notif-complainant-modal label {
    display: block;
    margin-top: 10px;
    font-weight: bold;
}

#notif-complainant-modal input,
#notif-complainant-modal select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
}

#notif-complainant-modal #case-type,
#notif-complainant-modal #judge-name {
    width: 400px;
    padding: 8px;
    font-size: 16px;
}

#notif-complainant-modal table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

#notif-complainant-modal table,
#notif-complainant-modal th,
#notif-complainant-modal td {
    border: 1px solid #ccc;
}

#notif-complainant-modal th,
#notif-complainant-modal td {
    padding: 8px;
    text-align: center;
}

#notif-complainant-modal th {
    background: #000;
    color: #fff;
}

#notif-complainant-modal tr.selected {
    background: #d1e7fd;
}

#notif-complainant-modal tbody tr:hover {
    background: #f0f8ff;
    cursor: pointer;
}

#notif-complainant-modal .actions {
    display: flex;
    gap: 12px;
    margin-top: 18px;
    justify-content: center;
}

#notif-complainant-modal .btn-save {
    background: #1a7f24;
    color: #fff;
    padding: 10px 20px;
    border-radius: 6px;
    border: 0;
    cursor: pointer;
    font-family: 'Cairo', sans-serif;
    font-weight: bold;
    font-size: 11px;
}

#notif-complainant-modal .btn-notify {
    background: #0d6efd;
    color: #fff;
    padding: 10px 20px;
    border-radius: 6px;
    border: 0;
    cursor: pointer;
    font-family: 'Cairo', sans-serif;
    font-weight: bold;
    font-size: 11px;
}

#notif-complainant-modal .btn-exit {
    background: #c81e1e;
    color: #fff;
    padding: 10px 20px;
    border-radius: 6px;
    border: 0;
    cursor: pointer;
    font-family: 'Cairo', sans-serif;
    font-weight: bold;
    font-size: 11px;
}
</style>

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

        <!-- رقم الدعوى -->
        <div class="case-number-wrapper">
          <div class="case-number-label">رقم الدعوى</div>
          <div class="case-number-inputs">
            <input type="text" id="notif-complainant-case-serial" maxlength="4" placeholder="####">
            <input type="text" id="notif-complainant-court-number" readonly placeholder="##">
            <input type="text" id="notif-complainant-pen-number" readonly placeholder="x/y">
            <input type="text" id="notif-complainant-year-number" readonly placeholder="YYYY">
          </div>
          <div class="case-number-inputs">
            <button id="notif-complainant-search">بحث</button>
          </div>
        </div>

        <!-- نوع الدعوى واسم القاضي -->
        <div class="row">
          <div class="col">
            <label>نوع الدعوى</label>
            <input id="notif-complainant-case-type" disabled>
          </div>
          <div class="col">
            <label>اسم القاضي</label>
            <input id="notif-complainant-judge-name" disabled>
          </div>
        </div>

        <!-- جدول الأطراف -->
        <label>الأطراف</label>
        <table id="notif-complainant-parties-table">
          <thead>
            <tr>
              <th>الاسم</th>
              <th>الرقم الوطني</th>
              <th>نوع الطرف</th>
              <th>الوظيفة</th>
              <th>مكان الإقامة</th>
              <th>رقم الهاتف</th>
              <!-- <th>قسم التباليغ</th> -->
              <th>طريقة التبليغ</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>

      </div> <!-- modal-body -->

      <div class="modal-footer">
        <div class="actions">
          <button id="notif-complainant-save" class="btn-save">حفظ وانهاء</button>
          <button id="notif-complainant-notify" class="btn-notify">تنفيذ تبليغ</button>
          <button class="btn-exit" data-bs-dismiss="modal">خروج</button>
        </div>
      </div>

    </div>
  </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- مذكرة تبليغ مشتكي موعد جلسة -->
<style>
#notif-session-complainant-modal .modal-content {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.08);
}

#notif-session-complainant-modal h2 {
    text-align: center;
    margin-bottom: 18px;
    font-size: 20px;
}

#notif-session-complainant-modal .case-number-wrapper {
    margin-bottom: 20px;
}

#notif-session-complainant-modal .case-number-label {
    font-weight: bold;
    margin-bottom: 8px;
    display: block;
}

#notif-session-complainant-modal .case-number-inputs {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}

#notif-session-complainant-modal .case-number-inputs input {
    flex: 1;
    min-width: 120px;
    padding: 6px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

#notif-session-complainant-modal .case-number-inputs button {
    padding: 8px 20px;
    border-radius: 6px;
    border: 0;
    cursor: pointer;
    font-family: 'Cairo', sans-serif;
    font-weight: bold;
    font-size: 11px;
    background: #000;
    color: #fff;
}

#notif-session-complainant-modal .row {
    display: flex;
    gap: 12px;
    margin-bottom: 14px;
    align-items: center;
}

#notif-session-complainant-modal .col {
    flex: 1;
}

#notif-session-complainant-modal label {
    display: block;
    margin-top: 10px;
    font-weight: bold;
}

#notif-session-complainant-modal input,
#notif-session-complainant-modal select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
}

#notif-session-complainant-modal #case-type,
#notif-session-complainant-modal #judge-name {
    width: 400px;
    padding: 8px;
    font-size: 16px;
}

#notif-session-complainant-modal table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

#notif-session-complainant-modal table,
#notif-session-complainant-modal th,
#notif-session-complainant-modal td {
    border: 1px solid #ccc;
}

#notif-session-complainant-modal th,
#notif-session-complainant-modal td {
    padding: 8px;
    text-align: center;
}

#notif-session-complainant-modal th {
    background: #000;
    color: #fff;
}

#notif-session-complainant-modal tr.selected {
    background: #d1e7fd;
}

#notif-session-complainant-modal tbody tr:hover {
    background: #f0f8ff;
    cursor: pointer;
}

#notif-session-complainant-modal .actions {
    display: flex;
    gap: 12px;
    margin-top: 18px;
    justify-content: center;
}

#notif-session-complainant-modal .btn-save {
    background: #1a7f24;
    color: #fff;
    padding: 10px 20px;
    border-radius: 6px;
    border: 0;
    cursor: pointer;
    font-family: 'Cairo', sans-serif;
    font-weight: bold;
    font-size: 11px;
}

#notif-session-complainant-modal .btn-notify {
    background: #0d6efd;
    color: #fff;
    padding: 10px 20px;
    border-radius: 6px;
    border: 0;
    cursor: pointer;
    font-family: 'Cairo', sans-serif;
    font-weight: bold;
    font-size: 11px;
}

#notif-session-complainant-modal .btn-exit {
    background: #c81e1e;
    color: #fff;
    padding: 10px 20px;
    border-radius: 6px;
    border: 0;
    cursor: pointer;
    font-family: 'Cairo', sans-serif;
    font-weight: bold;
    font-size: 11px;
}
</style>

<div class="modal fade" id="notif-session-complainant-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">مذكرة تبليغ مشتكي موعد جلسة</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <!-- رسالة/alert -->
        <div id="notif-session-complainant-alert"></div>

        <!-- رقم الدعوى -->
        <div class="case-number-wrapper">
          <div class="case-number-label">رقم الدعوى</div>
          <div class="case-number-inputs">
            <input type="text" id="notif-session-complainant-case-serial" maxlength="4" placeholder="####">
            <input type="text" id="notif-session-complainant-court-number" readonly placeholder="##">
            <input type="text" id="notif-session-complainant-pen-number" readonly placeholder="x/y">
            <input type="text" id="notif-session-complainant-year-number" readonly placeholder="YYYY">
          </div>
          <div class="case-number-inputs">
            <button id="notif-session-complainant-search">بحث</button>
          </div>
        </div>

        <!-- نوع الدعوى واسم القاضي -->
        <div class="row">
          <div class="col">
            <label>نوع الدعوى</label>
            <input id="notif-session-complainant-case-type" disabled>
          </div>
          <div class="col">
            <label>اسم القاضي</label>
            <input id="notif-session-complainant-judge-name" disabled>
          </div>
        </div>

        <!-- جدول الأطراف -->
        <label>الأطراف</label>
        <table id="notif-session-complainant-parties-table">
          <thead>
            <tr>
              <th>الاسم</th>
              <th>الرقم الوطني</th>
              <th>نوع الطرف</th>
              <th>الوظيفة</th>
              <th>مكان الإقامة</th>
              <th>رقم الهاتف</th>
              <th>طريقة التبليغ</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>

      </div> <!-- modal-body -->

      <div class="modal-footer">
        <div class="actions">
          <button id="notif-session-complainant-save" class="btn-save">حفظ وانهاء</button>
          <button id="notif-session-complainant-notify" class="btn-notify">تنفيذ تبليغ</button>
          <button class="btn-exit" data-bs-dismiss="modal">خروج</button>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- مودال مذكرة تبليغ شاهد موعد جلسة -->
<div class="modal fade" id="notif-witness-modal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header" style="background-color: #000; color: white; font-family: 'Cairo', sans-serif;">
        <h5 class="modal-title">مذكرة تبليغ شاهد موعد جلسة</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body" style="font-family: 'Cairo', sans-serif;">

        <div id="notif-witness-alert"></div>

        <!-- إدخال رقم الدعوى (4 خانات) -->
        <div class="row g-2 mb-3">
          <div class="col-md-3">
            <label class="form-label" style="font-weight: bold;">رقم الدعوى</label>
            <input type="text" id="notif-witness-case-serial" class="form-control"
                   placeholder="رقم الدعوى (4 خانات)" maxlength="4">
          </div>
          <div class="col-md-3">
            <label class="form-label" style="font-weight: bold;">المحكمة</label>
            <input type="text" id="notif-witness-court-number" class="form-control"
                   value="{{ auth()->user()->tribunal->number }}" readonly>
          </div>
          <div class="col-md-3">
            <label class="form-label" style="font-weight: bold;">القلم</label>
            <input type="text" id="notif-witness-pen-number" class="form-control"
                   value="{{ auth()->user()->department->number }}" readonly>
          </div>
          <div class="col-md-3">
            <label class="form-label" style="font-weight: bold;">السنة</label>
            <input type="text" id="notif-witness-year-number" class="form-control"
                   value="{{ date('Y') }}" readonly>
          </div>
        </div>

        <!-- زر البحث -->
        <div class="row mb-3">
          <div class="col-md-12">
            <button type="button" id="notif-witness-search" class="btn btn-primary w-100"
                    style="background-color: #1a7f24; border:none; font-family: 'Cairo', sans-serif; font-weight: bold;">
              بحث
            </button>
          </div>
        </div>

        <!-- عرض نوع الدعوى واسم القاضي -->
        <div class="row g-2 mb-3">
          <div class="col-md-6">
            <label class="form-label" style="font-weight: bold;">نوع الدعوى</label>
            <input type="text" id="notif-witness-case-type" class="form-control" disabled>
          </div>
          <div class="col-md-6">
            <label class="form-label" style="font-weight: bold;">اسم القاضي</label>
            <input type="text" id="notif-witness-judge-name" class="form-control" disabled>
          </div>
        </div>

        <!-- جدول الأطراف -->
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="notif-witness-parties-table"
                 style="font-family: 'Cairo', sans-serif; font-size: 14px;">
            <thead style="background-color: #f8f9fa;">
              <tr>
                <th>اسم الطرف</th>
                <th>الرقم الوطني</th>
                <th>نوع الطرف</th>
                <th>المهنة</th>
                <th>السكن</th>
                <th>رقم الهاتف</th>
                <th>طريقة التبليغ</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>

      </div>

      <div class="modal-footer" style="font-family: 'Cairo', sans-serif;">
        <button type="button" id="notif-witness-save" class="btn"
                style="background-color: #1a7f24; color: white; border:none; font-weight: bold;">
          حفظ وانهاء
        </button>
        <button type="button" id="notif-witness-notify" class="btn"
                style="background-color: #27ae60; color: white; border:none; font-weight: bold;">
          تنفيذ تبليغ
        </button>
        <button type="button" class="btn" data-bs-dismiss="modal"
                style="background-color: #e74c3c; color: white; border:none; font-weight: bold;">
          خروج
        </button>
      </div>

    </div>
  </div>
</div>

<style>
  #notif-witness-parties-table tbody tr {
    cursor: pointer;
  }
  #notif-witness-parties-table tbody tr:hover {
    background-color: #f0f0f0;
  }
  #notif-witness-parties-table tbody tr.selected {
    background-color: #d1e7fd;
  }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- إدارة تباليغ الدعوى -->
<style>
  #manage-notifications-modal .modal-content {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }

  #manage-notifications-modal .modal-body {
    font-family: 'Cairo', sans-serif;
    background: #f8f9fa;
    padding: 20px;
  }

  #manage-notifications-modal h2 {
    text-align: center;
    margin-bottom: 18px;
    font-size: 20px;
  }

  #manage-notifications-modal .case-number-wrapper {
    margin-bottom: 20px;
    background: #fff;
    padding: 15px;
    border-radius: 8px;
  }

  #manage-notifications-modal .case-number-label {
    font-weight: bold;
    margin-bottom: 8px;
    display: block;
  }

  #manage-notifications-modal .case-number-inputs {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
    margin-bottom: 12px;
  }

  #manage-notifications-modal .case-number-inputs input {
    flex: 1;
    min-width: 120px;
    padding: 8px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
    text-align: center;
  }

  #manage-notifications-modal .case-number-inputs button {
    padding: 10px 20px;
    border-radius: 6px;
    border: 0;
    cursor: pointer;
    font-family: 'Cairo', sans-serif;
    font-weight: bold;
    font-size: 14px;
    background-color: #37678e;
    color: #fff;
    width: 100%;
  }

  #manage-notifications-modal table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    background: #fff;
  }

  #manage-notifications-modal table, 
  #manage-notifications-modal th, 
  #manage-notifications-modal td {
    border: 1px solid #ccc;
  }

  #manage-notifications-modal th, 
  #manage-notifications-modal td {
    padding: 8px;
    text-align: center;
  }

  #manage-notifications-modal th {
    background: #000;
    color: #fff;
  }

  #manage-notifications-modal .btn-area {
    margin-top: 20px;
    display: flex;
    justify-content: space-between;
    gap: 10px;
  }

  #manage-notifications-modal .btn-save {
    background-color: #37678e;
    color: #fff;
    padding: 10px 20px;
    border-radius: 6px;
    border: 0;
    cursor: pointer;
    font-family: 'Cairo', sans-serif;
    font-weight: bold;
  }

  #manage-notifications-modal .btn-end {
    background-color: #777;
    color: #fff;
    padding: 10px 20px;
    border-radius: 6px;
    border: 0;
    cursor: pointer;
    font-family: 'Cairo', sans-serif;
    font-weight: bold;
  }
</style>

<div class="modal fade" id="manage-notifications-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header" style="background: #000; color: #fff;">
        <h5 class="modal-title">إدارة تباليغ الدعوى</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <div id="manage-notifications-alert"></div>

        <!-- إدخال رقم الدعوى مقسم -->
        <div class="case-number-wrapper">
          <label class="case-number-label">رقم الدعوى:</label>

          <div class="case-number-inputs">
            <input type="text" id="manage-case-serial" maxlength="4" placeholder="####">
            <input type="text" id="manage-case-court" maxlength="2" placeholder="##" readonly>
            <input type="text" id="manage-case-pen" maxlength="3" placeholder="x/y" readonly>
            <input type="text" id="manage-case-year" maxlength="4" placeholder="yyyy" readonly>
          </div>

          <button id="manage-notifications-search">بحث</button>
        </div>

        <!-- جدول التباليغ - دائماً ظاهر -->
        <div class="table-responsive">
          <table id="manage-notifications-table">
            <thead>
              <tr>
                <th>رقم الدعوى</th>
                <th>نوع الطرف</th>
                <th>اسم الطرف</th>
                <th>طريقة التبليغ</th>
                <th>تاريخ التبليغ</th>
              </tr>
            </thead>
            <tbody id="manage-notifications-tbody">
              <!-- البيانات ستُملأ هنا -->
            </tbody>
          </table>
        </div>

        <!-- أزرار -->
        <div class="btn-area" style="justify-content: center;">
          <button class="btn-end" data-bs-dismiss="modal">إغلاق</button>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- مودال مذكرة تبليغ حكم -->
<div class="modal fade" id="notif-judgment-modal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header" style="background:#000;color:#fff;font-family:'Cairo',sans-serif;">
        <h5 class="modal-title">مذكرة تبليغ حكم</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body" style="font-family:'Cairo',sans-serif;">

        <div id="notif-judgment-alert"></div>

        <!-- رقم الدعوى -->
        <div class="row g-2 mb-3">
          <div class="col-md-3">
            <label class="form-label fw-bold">رقم الدعوى</label>
            <input type="text" id="notif-judgment-case-serial" class="form-control" maxlength="4" placeholder="####">
          </div>
          <div class="col-md-3">
            <label class="form-label fw-bold">المحكمة</label>
            <input type="text" id="notif-judgment-court-number" class="form-control" readonly>
          </div>
          <div class="col-md-3">
            <label class="form-label fw-bold">القلم</label>
            <input type="text" id="notif-judgment-pen-number" class="form-control" readonly>
          </div>
          <div class="col-md-3">
            <label class="form-label fw-bold">السنة</label>
            <input type="text" id="notif-judgment-year-number" class="form-control" readonly>
          </div>
        </div>

        <!-- زر البحث -->
        <div class="row mb-3">
          <div class="col-12">
            <button id="notif-judgment-search" class="btn w-100"
                    style="background:#1a7f24;color:#fff;border:none;font-weight:bold;">
              بحث
            </button>
          </div>
        </div>

        <!-- نوع الدعوى + اسم القاضي -->
        <div class="row g-2 mb-3">
          <div class="col-md-6">
            <label class="form-label fw-bold">نوع الدعوى</label>
            <input type="text" id="notif-judgment-case-type" class="form-control" disabled>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">اسم القاضي</label>
            <input type="text" id="notif-judgment-judge-name" class="form-control" disabled>
          </div>
        </div>

        <!-- نص الحكم -->
        <div class="row g-2 mb-3">
          <div class="col-12">
            <label class="form-label fw-bold">نص الحكم</label>
            <textarea id="notif-judgment-text" class="form-control" rows="4"
                      disabled style="resize:none;background:#f8f9fa;"
                      placeholder="سيتم عرض نص الحكم هنا بعد البحث"></textarea>
          </div>
        </div>

        <!-- جدول الأطراف -->
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="notif-judgment-parties-table"
                 style="font-size:14px;">
            <thead style="background:#f8f9fa;">
              <tr>
                <th>اختيار</th>
                <th>اسم الطرف</th>
                <th>الرقم الوطني</th>
                <th>نوع الطرف</th>
                <th>المهنة</th>
                <th>مكان الإقامة</th>
                <th>رقم الهاتف</th>
                <th>طريقة التبليغ</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>

      </div>

      <div class="modal-footer" style="font-family:'Cairo',sans-serif;">
        <!-- <button id="notif-judgment-save" class="btn"
                style="background:#1a7f24;color:#fff;border:none;font-weight:bold;">
          حفظ وانهاء
        </button> -->
        <button id="notif-judgment-notify" class="btn"
                style="background:#27ae60;color:#fff;border:none;font-weight:bold;">
          تنفيذ تبليغ
        </button>
        <button class="btn" data-bs-dismiss="modal"
                style="background:#e74c3c;color:#fff;border:none;font-weight:bold;">
          خروج
        </button>
      </div>

    </div>
  </div>
</div>















<!--  مذكرة توقيف -->
<style>
#arrest-memo-modal .modal-content {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 14px rgba(0,0,0,0.08);
}

#arrest-memo-modal .modal-body {
  font-family: 'Cairo', sans-serif;
  padding: 20px;
}

#arrest-memo-modal h2 {
  text-align: center;
  margin-bottom: 18px;
  font-size: 20px;
}

#arrest-memo-modal .case-number-wrapper {
  margin-bottom: 20px;
}

#arrest-memo-modal .case-number-label {
  font-weight: bold;
  margin-bottom: 8px;
  display: block;
}

#arrest-memo-modal .case-boxes {
  display: flex;
  gap: 8px;
  margin-bottom: 12px;
}

#arrest-memo-modal .case-boxes input {
  width: 70px;
  text-align: center;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 14px;
}

#arrest-memo-modal .row {
  display: flex;
  gap: 12px;
  margin-bottom: 14px;
  align-items: center;
}

#arrest-memo-modal .col {
  flex: 1;
}

#arrest-memo-modal label {
  display: block;
  margin-top: 10px;
  font-weight: bold;
}

#arrest-memo-modal input,
#arrest-memo-modal select {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 14px;
}

#arrest-memo-modal #arrest-case-type {
  width: 100%;
  padding: 8px;
  font-size: 16px;
}

#arrest-memo-modal #arrest-judge-name,
#arrest-memo-modal #arrest-duration {
  width: 420px;
  padding: 8px;
  font-size: 16px;
}

#arrest-memo-modal table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
}

#arrest-memo-modal table,
#arrest-memo-modal th,
#arrest-memo-modal td {
  border: 1px solid #ccc;
}

#arrest-memo-modal th,
#arrest-memo-modal td {
  padding: 8px;
  text-align: center;
}

#arrest-memo-modal th {
  background: #000;
  color: #fff;
}

#arrest-memo-modal tr.selected {
  background: #d1e7fd;
}

#arrest-memo-modal tbody tr:hover {
  background: #f0f8ff;
  cursor: pointer;
}

#arrest-memo-modal .actions {
  display: flex;
  gap: 12px;
  margin-top: 18px;
  justify-content: center;
}

#arrest-memo-modal .btn-save {
  background: #1a7f24;
  color: #fff;
  padding: 10px 14px;
  border-radius: 6px;
  border: 0;
  cursor: pointer;
  font-family: 'Cairo', sans-serif;
  font-weight: 600;
}

#arrest-memo-modal .btn-exit {
  background: #c81e1e;
  color: #fff;
  padding: 10px 14px;
  border-radius: 6px;
  border: 0;
  cursor: pointer;
  font-family: 'Cairo', sans-serif;
  font-weight: 600;
}
</style>

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

        <!-- رقم الدعوى -->
        <div class="case-number-wrapper">
          <label class="case-number-label">رقم الدعوى:</label>
          <div class="case-boxes">
            <input type="text" id="arrest-case-serial" maxlength="4" placeholder="####">
            <input type="text" id="arrest-court-number" placeholder="##" readonly>
            <input type="text" id="arrest-pen-number" placeholder="x/y" readonly>
            <input type="text" id="arrest-year-number" placeholder="YYYY" readonly>
          </div>
        </div>

        <!-- نوع الدعوى -->
        <div class="row">
          <div class="col">
            <label>نوع الدعوى:</label>
            <input id="arrest-case-type" disabled>
          </div>
        </div>

        <!-- جدول الأطراف -->
        <label>الأطراف</label>
        <table id="arrest-participants-table">
          <thead>
            <tr>
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

        <!-- اسم القاضي ومدة التوقيف -->
        <div class="row">
          <div class="col">
            <label>اسم القاضي:</label>
            <input id="arrest-judge-name" disabled>
          </div>
          <div class="col">
            <label>مدة التوقيف (أيام):</label>
            <input id="arrest-duration" type="number" min="0">
          </div>
        </div>

        <!-- سبب التوقيف ومركز الإصلاح -->
        <div class="row">
          <div class="col">
            <label>سبب التوقيف:</label>
            <select id="arrest-reason">
              <option value="">اختر</option>
              <option value="خطر الهروب">خطر الهروب</option>
              <option value="خطر العبث بالأدلة">خطر العبث بالأدلة</option>
              <option value="خطر الإتيان بجرائم جديدة">خطر الإتيان بجرائم جديدة</option>
              <option value="عدم ثبوت إقامة">عدم ثبوت إقامة</option>
              <option value="أمر أمني">أمر أمني</option>
            </select>
          </div>
          <div class="col">
            <label>مركز الإصلاح والتأهيل:</label>
            <select id="arrest-center">
              <option value="">اختر المركز</option>
              <option value="مركز الإصلاح المركزي">مركز الإصلاح المركزي</option>
              <option value="مركز الإصلاح الجنوبي">مركز الإصلاح الجنوبي</option>
              <option value="مركز تأهيل الشمال">مركز تأهيل الشمال</option>
            </select>
          </div>
        </div>

      </div>

      <!-- أزرار -->
      <div class="modal-footer">
        <div class="actions">
          <button class="btn-save" id="arrest-save-btn">حفظ وانهاء</button>
          <button class="btn-exit" data-bs-dismiss="modal">خروج</button>
        </div>
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

<!-- مذكرة تمديد توقيف -->
<style>
#extend-arrest-memo-modal .modal-content {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 14px rgba(0,0,0,0.08);
}

#extend-arrest-memo-modal .modal-body {
  font-family: 'Cairo', sans-serif;
  padding: 20px;
}

#extend-arrest-memo-modal h2 {
  text-align: center;
  margin-bottom: 18px;
  font-size: 20px;
}

#extend-arrest-memo-modal .case-number-wrapper {
  margin-bottom: 20px;
}

#extend-arrest-memo-modal .case-number-label {
  font-weight: bold;
  margin-bottom: 8px;
  display: block;
}

#extend-arrest-memo-modal .case-boxes {
  display: flex;
  gap: 8px;
  margin-bottom: 12px;
}

#extend-arrest-memo-modal .case-boxes input {
  width: 70px;
  text-align: center;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 14px;
}

#extend-arrest-memo-modal .row {
  display: flex;
  gap: 12px;
  margin-bottom: 14px;
  align-items: center;
}

#extend-arrest-memo-modal .col {
  flex: 1;
}

#extend-arrest-memo-modal label {
  display: block;
  margin-top: 10px;
  font-weight: bold;
}

#extend-arrest-memo-modal input,
#extend-arrest-memo-modal select {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 14px;
}

#extend-arrest-memo-modal #extend-arrest-case-type {
  width: 100%;
  padding: 8px;
  font-size: 16px;
}

#extend-arrest-memo-modal #extend-arrest-judge-name,
#extend-arrest-memo-modal #extend-arrest-extension-days {
  width: 420px;
  padding: 8px;
  font-size: 16px;
}

#extend-arrest-memo-modal table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
}

#extend-arrest-memo-modal table,
#extend-arrest-memo-modal th,
#extend-arrest-memo-modal td {
  border: 1px solid #ccc;
}

#extend-arrest-memo-modal th,
#extend-arrest-memo-modal td {
  padding: 8px;
  text-align: center;
}

#extend-arrest-memo-modal th {
  background: #000;
  color: #fff;
}

#extend-arrest-memo-modal tr.selected {
  background: #d1e7fd;
}

#extend-arrest-memo-modal tbody tr:hover {
  background: #f0f8ff;
  cursor: pointer;
}

#extend-arrest-memo-modal .actions {
  display: flex;
  gap: 12px;
  margin-top: 18px;
  justify-content: center;
}

#extend-arrest-memo-modal .btn-save {
  background: #1a7f24;
  color: #fff;
  padding: 10px 14px;
  border-radius: 6px;
  border: 0;
  cursor: pointer;
  font-family: 'Cairo', sans-serif;
  font-weight: 600;
}

#extend-arrest-memo-modal .btn-exit {
  background: #c81e1e;
  color: #fff;
  padding: 10px 14px;
  border-radius: 6px;
  border: 0;
  cursor: pointer;
  font-family: 'Cairo', sans-serif;
  font-weight: 600;
}
</style>

<div class="modal fade" id="extend-arrest-memo-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">مذكرة تمديد توقيف</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <!-- Alert -->
        <div id="extend-arrest-alert"></div>

        <!-- رقم الدعوى -->
        <div class="case-number-wrapper">
          <label class="case-number-label">رقم الدعوى</label>
          <div class="case-boxes">
            <input type="text" id="extend-arrest-case-serial" maxlength="4" placeholder="####">
            <input type="text" id="extend-arrest-court-number" placeholder="##" readonly>
            <input type="text" id="extend-arrest-pen-number" placeholder="x/y" readonly>
            <input type="text" id="extend-arrest-year-number" placeholder="YYYY" readonly>
          </div>
        </div>

        <!-- نوع الدعوى -->
        <div class="row">
          <div class="col">
            <label>نوع الدعوى:</label>
            <input id="extend-arrest-case-type" disabled>
          </div>
        </div>

        <!-- جدول الأطراف -->
        <label>الأطراف</label>
        <table id="extend-arrest-participants-table">
          <thead>
            <tr>
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

        <!-- اسم القاضي وتمديد التوقيف -->
        <div class="row">
          <div class="col">
            <label>اسم القاضي:</label>
            <input id="extend-arrest-judge-name" disabled>
          </div>
          <div class="col">
            <label>تمديد التوقيف (أيام):</label>
            <input id="extend-arrest-extension-days" type="number" min="0">
          </div>
        </div>

        <!-- سبب التوقيف ومركز الإصلاح -->
        <div class="row">
          <div class="col">
            <label>سبب التوقيف:</label>
            <select id="extend-arrest-reason">
              <option value="">اختر</option>
              <option value="خطر الهروب">خطر الهروب</option>
              <option value="خطر العبث بالأدلة">خطر العبث بالأدلة</option>
              <option value="خطر الإتيان بجرائم جديدة">خطر الإتيان بجرائم جديدة</option>
              <option value="عدم ثبوت إقامة">عدم ثبوت إقامة</option>
              <option value="أمر أمني">أمر أمني</option>
            </select>
          </div>
          <div class="col">
            <label>مركز الإصلاح والتأهيل:</label>
            <select id="extend-arrest-center">
              <option value="">اختر المركز</option>
              <option value="مركز الإصلاح المركزي">مركز الإصلاح المركزي</option>
              <option value="مركز الإصلاح الجنوبي">مركز الإصلاح الجنوبي</option>
              <option value="مركز تأهيل الشمال">مركز تأهيل الشمال</option>
            </select>
          </div>
        </div>

      </div>

      <!-- أزرار -->
      <div class="modal-footer">
        <div class="actions">
          <button class="btn-save" id="extend-arrest-save-btn">حفظ وانهاء</button>
          <button class="btn-exit" data-bs-dismiss="modal">خروج</button>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- مذكرة إفراج للموقوفين -->
<style>
#release-memo-modal .modal-content {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 14px rgba(0,0,0,0.08);
}

#release-memo-modal .modal-body {
  font-family: 'Cairo', sans-serif;
  padding: 20px;
}

#release-memo-modal h2 {
  text-align: center;
  margin-bottom: 18px;
  font-size: 20px;
}

#release-memo-modal .case-number-wrapper {
  margin-bottom: 20px;
}

#release-memo-modal .case-number-label {
  font-weight: bold;
  margin-bottom: 8px;
  display: block;
}

#release-memo-modal .case-boxes {
  display: flex;
  gap: 8px;
  margin-bottom: 12px;
}

#release-memo-modal .case-boxes input {
  width: 70px;
  text-align: center;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 14px;
}

#release-memo-modal .row {
  display: flex;
  gap: 12px;
  margin-bottom: 14px;
  align-items: center;
}

#release-memo-modal .col {
  flex: 1;
}

#release-memo-modal label {
  display: block;
  margin-top: 10px;
  font-weight: bold;
}

#release-memo-modal input,
#release-memo-modal select {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 14px;
}

#release-memo-modal #release-case-type,
#release-memo-modal #release-judge-name {
  width: 420px;
  padding: 8px;
  font-size: 16px;
}

#release-memo-modal table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
}

#release-memo-modal table,
#release-memo-modal th,
#release-memo-modal td {
  border: 1px solid #ccc;
}

#release-memo-modal th,
#release-memo-modal td {
  padding: 8px;
  text-align: center;
}

#release-memo-modal th {
  background: #000;
  color: #fff;
}

#release-memo-modal tr.selected {
  background: #d1e7fd;
}

#release-memo-modal tbody tr:hover {
  background: #f0f8ff;
  cursor: pointer;
}

#release-memo-modal .actions {
  display: flex;
  gap: 12px;
  margin-top: 18px;
  justify-content: center;
  flex-wrap: wrap;
}

#release-memo-modal .btn-release {
  background: #0d6efd;
  color: #fff;
  padding: 10px 14px;
  border-radius: 6px;
  border: 0;
  cursor: pointer;
  font-family: 'Cairo', sans-serif;
  font-weight: 600;
}

#release-memo-modal .btn-exit {
  background: #c81e1e;
  color: #fff;
  padding: 10px 14px;
  border-radius: 6px;
  border: 0;
  cursor: pointer;
  font-family: 'Cairo', sans-serif;
  font-weight: 600;
}
</style>

<div class="modal fade" id="release-memo-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">مذكرة إفراج للموقوفين</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <!-- Alert -->
        <div id="release-alert"></div>

        <!-- رقم الدعوى -->
        <div class="case-number-wrapper">
          <label class="case-number-label">رقم الدعوى</label>
          <div class="case-boxes">
            <input type="text" id="release-case-serial" maxlength="4" placeholder="####">
            <input type="text" id="release-court-number" placeholder="##" readonly>
            <input type="text" id="release-pen-number" placeholder="x/y" readonly>
            <input type="text" id="release-year-number" placeholder="YYYY" readonly>
          </div>
        </div>

        <!-- نوع الدعوى واسم القاضي -->
        <div class="row">
          <div class="col">
            <label>نوع الدعوى</label>
            <input id="release-case-type" disabled>
          </div>
          <div class="col">
            <label>اسم القاضي</label>
            <input id="release-judge-name" disabled>
          </div>
        </div>

        <!-- جدول الأطراف -->
        <label>الأطراف</label>
        <table id="release-participants-table">
          <thead>
            <tr>
              <th>الاسم</th>
              <th>نوع الطرف</th>
              <th>التهمة</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>

      </div>

      <!-- أزرار -->
      <div class="modal-footer">
        <div class="actions">
          <button class="btn-release" id="release-save-btn">إفراج عن الموقوف</button>
          <button class="btn-exit" data-bs-dismiss="modal">خروج</button>
        </div>
      </div>

    </div>
  </div>
</div>










@yield('chief-extra')
@endsection
<script>
  //مذكرة الإفراج عن الموقوفين
document.addEventListener('DOMContentLoaded', () => {

  const modalId = "release-memo-modal";
  const modalEl = document.getElementById(modalId);

  const $ = id => document.getElementById(id);

  // 4-box case number inputs
  const caseSerial = $("release-case-serial");
  const courtNumber = $("release-court-number");
  const penNumber = $("release-pen-number");
  const yearNumber = $("release-year-number");

  const caseTypeInput = $("release-case-type");
  const judgeNameInput = $("release-judge-name");
  const participantsTableBody = document.querySelector("#release-participants-table tbody");

  const saveBtn = $("release-save-btn");
  const alertBox = $("release-alert");

  let selectedRow = null;
  let selectedParticipant = null;
  let currentCaseId = null;

  function showAlert(msg, type = "warning") {
    if (!alertBox) return;
    const cls = type === "success" ? "alert-success" : type === "danger" ? "alert-danger" : "alert-warning";
    alertBox.innerHTML = `<div class="alert ${cls}">${msg}</div>`;
    setTimeout(() => { alertBox.innerHTML = ""; }, 5000);
  }

  function clearForm() {
    if (caseSerial) caseSerial.value = "";
    if (courtNumber) courtNumber.value = "";
    if (penNumber) penNumber.value = "";
    if (yearNumber) yearNumber.value = "";
    if (caseTypeInput) caseTypeInput.value = "";
    if (judgeNameInput) judgeNameInput.value = "";
    if (participantsTableBody) participantsTableBody.innerHTML = "";
    if (alertBox) alertBox.innerHTML = "";
    selectedRow = null;
    selectedParticipant = null;
    currentCaseId = null;
  }

  async function searchCase() {
    if (!caseSerial) return;
    const serial = caseSerial.value.trim();
    if (serial === "") {
      showAlert("الرجاء إدخال رقم الدعوى", "danger");
      return;
    }

    selectedRow = null;
    selectedParticipant = null;
    currentCaseId = null;

    if (participantsTableBody) participantsTableBody.innerHTML = "";
    if (alertBox) alertBox.innerHTML = "";

    try {
      // Get all data from one endpoint
      const res = await fetch(`/writer/case-notifications/${encodeURIComponent(serial)}`);
      const data = await res.json();

      if (!res.ok || data.error) {
        showAlert(data.error || "لا توجد قضية بهذا الرقم", "danger");
        return;
      }

      currentCaseId = serial;

      // Fill all fields from single endpoint
      if (courtNumber) courtNumber.value = data.case_court || "";
      if (penNumber) penNumber.value = data.case_pen || "";
      if (yearNumber) yearNumber.value = data.case_year || "";
      if (caseTypeInput) caseTypeInput.value = data.case_type || "";
      if (judgeNameInput) judgeNameInput.value = data.judge_name || "";

      // Fill participants table
      if (participantsTableBody) {
        participantsTableBody.innerHTML = "";
        if (data.participants && data.participants.length > 0) {
          data.participants.forEach(p => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
              <td>${p.name || ""}</td>
              <td>${p.type || ""}</td>
              <td>${p.charge || ""}</td>
            `;

            tr.addEventListener("click", () => {
              if (selectedRow) {
                selectedRow.classList.remove("selected");
              }
              selectedRow = tr;
              selectedRow.classList.add("selected");

              selectedParticipant = {
                name: p.name || "",
                type: p.type || "",
                charge: p.charge || ""
              };
            });

            participantsTableBody.appendChild(tr);
          });
        }
      }

    } catch (e) {
      console.error(e);
      showAlert("خطأ في الاتصال بالسيرفر", "danger");
    }
  }

  // Enter key search
  if (caseSerial) {
    caseSerial.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        searchCase();
      }
    });
  }

  // Save/Release button
  if (saveBtn) {
    saveBtn.addEventListener("click", async () => {
      if (!selectedParticipant) {
        showAlert("يرجى اختيار طرف من الجدول", "warning");
        return;
      }
      
      const serial = caseSerial ? caseSerial.value.trim() : "";
      const court = courtNumber ? courtNumber.value.trim() : "";
      const pen = penNumber ? penNumber.value.trim() : "";
      const year = yearNumber ? yearNumber.value.trim() : "";
      
      if (!serial) {
        showAlert("يرجى البحث عن القضية أولاً", "warning");
        return;
      }

      const caseNumber = `${serial}`;

      try {
        const res = await fetch("/release-memo/store", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
            case_number: caseNumber,
            released_participants: [selectedParticipant.name]
          })
        });

        if (!res.ok) throw new Error("فشل الحفظ");

        const data = await res.json();
        if (data.error) {
          showAlert(data.error, "danger");
        } else {
          showAlert("تم الإفراج عن الموقوف بنجاح", "success");
          clearForm();
          const modal = bootstrap.Modal.getInstance(modalEl);
          if (modal) {
            setTimeout(() => modal.hide(), 1500);
          }
        }

      } catch (e) {
        console.error(e);
        showAlert("خطأ أثناء الحفظ", "danger");
      }
    });
  }

  // Modal cleanup on hide
  if (modalEl) {
    modalEl.addEventListener("hidden.bs.modal", () => {
      clearForm();
    });
  }

});
</script>

<script>
  //مذكرة تمديد توقيف
document.addEventListener('DOMContentLoaded', () => {

  const modalId = "extend-arrest-memo-modal";
  const modalEl = document.getElementById(modalId);

  const $ = id => document.getElementById(id);

  // 4-box case number inputs
  const caseSerial = $("extend-arrest-case-serial");
  const courtNumber = $("extend-arrest-court-number");
  const penNumber = $("extend-arrest-pen-number");
  const yearNumber = $("extend-arrest-year-number");

  const caseTypeInput = $("extend-arrest-case-type");
  const participantsTableBody = document.querySelector("#extend-arrest-participants-table tbody");

  const judgeNameInput = $("extend-arrest-judge-name");
  const extensionDaysInput = $("extend-arrest-extension-days");
  const reasonSelect = $("extend-arrest-reason");
  const centerSelect = $("extend-arrest-center");

  const saveBtn = $("extend-arrest-save-btn");
  const alertBox = $("extend-arrest-alert");

  let selectedRow = null;
  let selectedParticipant = null;
  let currentCaseId = null;

  function showAlert(msg, type = "warning") {
    if (!alertBox) return;
    alertBox.innerHTML = `<div class="alert alert-${type}">${msg}</div>`;
  }

  function clearAlert() { 
    if (alertBox) alertBox.innerHTML = ""; 
  }

  function resetUI() {
    clearAlert();
    if (caseTypeInput) caseTypeInput.value = "";
    if (judgeNameInput) judgeNameInput.value = "";
    if (participantsTableBody) participantsTableBody.innerHTML = "";
    if (extensionDaysInput) extensionDaysInput.value = "";
    if (reasonSelect) reasonSelect.value = "";
    if (centerSelect) centerSelect.value = "";
    selectedRow = null;
    selectedParticipant = null;
  }

  // 🔍 البحث عند Enter في أول خانة
  if (caseSerial) {
    caseSerial.addEventListener("keypress", function(e) {
      if (e.key === "Enter") {
        searchCase();
      }
    });
  }

  function searchCase() {
    resetUI();

    const serial = caseSerial.value.trim();
    if (serial.length !== 4) {
      showAlert("⚠️ يرجى إدخال 4 أرقام في رقم الدعوى");
      return;
    }

    showAlert("⏳ جاري جلب بيانات الدعوى ...", "info");

    fetch("/writer/extend-arrest-memo", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        case_number: serial
      })
    })
    .then(res => res.json().then(j => ({ ok: res.ok, json: j })))
    .then(({ ok, json }) => {

      if (!ok) throw json;

      clearAlert();

      // حفظ معرف القضية
      currentCaseId = json.case?.id;

      // تعبئة الخانات الأخرى
      if (courtNumber) courtNumber.value = json.case?.tribunal?.number ?? "";
      if (penNumber) penNumber.value = json.case?.department?.number ?? "";
      if (yearNumber) yearNumber.value = json.case?.year ?? "";

      // نوع الدعوى
      if (caseTypeInput) caseTypeInput.value = json.case?.type ?? "";

      // اسم القاضي
      if (judgeNameInput) judgeNameInput.value = json.judge_name ?? "";

      // عرض الأطراف
      const parts = json.participants ?? [];
      if (!parts.length) {
        showAlert("⚠️ لا يوجد أطراف لهذه الدعوى");
        return;
      }

      if (participantsTableBody) {
        participantsTableBody.innerHTML = "";

        parts.forEach(p => {
          const tr = document.createElement("tr");

          tr.innerHTML = `
            <td>${p.name}</td>
            <td>${p.type}</td>
            <td>${p.job ?? ""}</td>
            <td>${p.residence ?? ""}</td>
            <td>${p.phone ?? ""}</td>
            <td>الأمن العام</td>
          `;

          // Click to select row
          tr.addEventListener("click", () => {
            if (selectedRow) {
              selectedRow.classList.remove("selected");
            }
            tr.classList.add("selected");
            selectedRow = tr;
            selectedParticipant = p.name;
          });

          participantsTableBody.appendChild(tr);
        });
      }

      showAlert("✅ تم تحميل بيانات الدعوى", "success");
    })
    .catch(err => {
      console.error(err);
      showAlert(err.error ?? "❌ حدث خطأ أثناء جلب بيانات الدعوى", "danger");
    });
  }

  // 💾 زر الحفظ
  if (saveBtn) {
    saveBtn.addEventListener("click", function() {
      clearAlert();

      if (!selectedParticipant) {
        showAlert("⚠️ يرجى اختيار طرف من الجدول");
        return;
      }

      if (!extensionDaysInput.value) {
        showAlert("⚠️ يرجى إدخال عدد أيام التمديد");
        return;
      }

      if (!reasonSelect.value) {
        showAlert("⚠️ يرجى اختيار سبب التوقيف");
        return;
      }

      if (!centerSelect.value) {
        showAlert("⚠️ يرجى اختيار مركز الإصلاح");
        return;
      }

      const caseNumber = caseSerial.value.trim();

      fetch("/writer/extend-arrest-memo", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          case_number: caseNumber,
          participant_name: selectedParticipant,
          extension_days: extensionDaysInput.value,
          detention_reason: reasonSelect.value,
          detention_center: centerSelect.value,
          save: true
        })
      })
      .then(res => res.json().then(j => ({ ok: res.ok, json: j })))
      .then(({ ok, json }) => {
        if (!ok) throw json;

        showAlert("✅ تم حفظ مذكرة تمديد التوقيف بنجاح", "success");

        setTimeout(() => {
          const modal = bootstrap.Modal.getInstance(modalEl);
          if (modal) modal.hide();
        }, 1000);
      })
      .catch(err => {
        console.error(err);
        showAlert(err.error ?? "❌ خطأ أثناء حفظ مذكرة التمديد", "danger");
      });
    });
  }

  // إعادة ضبط عند إغلاق النافذة
  if (modalEl) {
    modalEl.addEventListener("hidden.bs.modal", function() {
      resetUI();
      if (caseSerial) caseSerial.value = "";
      if (courtNumber) courtNumber.value = "";
      if (penNumber) penNumber.value = "";
      if (yearNumber) yearNumber.value = "";
    });
  }

});
</script>





<script>
//مذكرة تبليغ حكم
document.addEventListener('DOMContentLoaded', () => {
  console.log('🚀 مذكرة تبليغ حكم - Script loaded!');

  const searchBtn = document.getElementById('notif-judgment-search');
  const notifyBtn = document.getElementById('notif-judgment-notify');
  const alertBox = document.getElementById('notif-judgment-alert');

  let selectedRow = null;
  let selectedParticipant = null;
  let currentCaseId = null;

  if (!searchBtn) {
    console.error('❌ زر البحث غير موجود في الصفحة');
    return;
  }

  console.log('📦 Elements check:', {
    searchBtn: !!searchBtn,
    notifyBtn: !!notifyBtn,
    alertBox: !!alertBox
  });

  function showAlert(msg, type = 'info') {
    alertBox.innerHTML = `<div class="alert alert-${type}">${msg}</div>`;
  }

  searchBtn.addEventListener('click', async () => {
    console.log('✅ تم الضغط على زر البحث');

    const serial = document.getElementById('notif-judgment-case-serial').value.trim();
    alertBox.innerHTML = '';
    selectedRow = null;
    selectedParticipant = null;
    currentCaseId = null;

    if (serial.length !== 4) {
      showAlert('أدخل رقم دعوى من 4 خانات', 'danger');
      return;
    }

    try {
      const res = await fetch(`/court-cases/${serial}?notification_type=تبليغ حكم`);
      const data = await res.json();

      if (!res.ok || data.error) {
        showAlert(data.error ?? 'خطأ غير متوقع', 'danger');
        return;
      }

      console.log('📥 Data received:', data);

      // Store case ID
      currentCaseId = data.case_id || data.id;
      console.log('💾 Stored case ID:', currentCaseId);

      // نوع الدعوى + اسم القاضي + الحكم
      document.getElementById('notif-judgment-case-type').value = data.case_type ?? '';
      document.getElementById('notif-judgment-judge-name').value = data.judge_name ?? '';
      document.getElementById('notif-judgment-text').value = data.judgment ?? 'لا يوجد حكم نهائي لهذه الدعوى';
      document.getElementById('notif-judgment-court-number').value = data.tribunal?.number ?? '';
      document.getElementById('notif-judgment-pen-number').value = data.department?.number ?? '';
      document.getElementById('notif-judgment-year-number').value = new Date().getFullYear();

      const tbody = document.querySelector('#notif-judgment-parties-table tbody');
      tbody.innerHTML = '';

      (data.participants || []).forEach((p, i) => {
        const tr = document.createElement('tr');
        tr.dataset.index = i;
        tr.style.cursor = 'pointer';

        tr.innerHTML = `
          <td>
            <input type="radio" name="selected_party" class="form-check-input">
          </td>
          <td>${p.name ?? ''}</td>
          <td>${p.national_id ?? ''}</td>
          <td>${p.type ?? ''}</td>
          <td>${p.job ?? ''}</td>
          <td>${p.residence ?? ''}</td>
          <td>${p.phone ?? ''}</td>
          <td>
            <select class="notification-method-select form-select form-select-sm" data-index="${i}">
              <option value="">اختر</option>
              <option value="sms">رسالة قصيرة</option>
              <option value="email">بريد إلكتروني</option>
              <option value="قسم التباليغ">قسم التباليغ</option>
            </select>
          </td>
        `;

        // Stop propagation on select
        const select = tr.querySelector('.notification-method-select');
        select.addEventListener('click', (e) => e.stopPropagation());

        // Row click handler
        tr.addEventListener('click', () => {
          console.log('📋 Row clicked:', p.name);
          
          // Deselect previous
          if (selectedRow) {
            selectedRow.classList.remove('table-primary');
            selectedRow.querySelector('input[type="radio"]').checked = false;
          }
          
          // Select current
          tr.classList.add('table-primary');
          tr.querySelector('input[type="radio"]').checked = true;
          selectedRow = tr;
          selectedParticipant = p;
          
          console.log('✅ Participant selected:', selectedParticipant);
        });

        tbody.appendChild(tr);
      });

      console.log('✅ تم عرض جميع بيانات الدعوى بنجاح');

    } catch (e) {
      showAlert('فشل الاتصال بالسيرفر', 'danger');
      console.error('❌ Fetch error:', e);
    }
  });

  // Notify button handler
  if (notifyBtn) {
    console.log('✅ Adding click event listener to notify button');
    notifyBtn.addEventListener('click', async () => {
      console.log('🔔 Notify button clicked!');
      console.log('Selected participant:', selectedParticipant);
      console.log('Current case ID:', currentCaseId);

      if (!selectedRow || !selectedParticipant) {
        showAlert('⚠️ حدد طرفاً من الجدول', 'warning');
        return;
      }

      const methodSelect = selectedRow.querySelector('.notification-method-select');
      const method = methodSelect.value;

      console.log('Selected method:', method);

      if (!method) {
        showAlert('⚠️ اختر طريقة التبليغ من القائمة', 'warning');
        return;
      }

      if (!currentCaseId) {
        showAlert('⚠️ لا يوجد معرف للقضية', 'warning');
        return;
      }

      console.log('📤 Sending notification:', {
        case_id: currentCaseId,
        participant_name: selectedParticipant.name,
        method: method
      });

      notifyBtn.disabled = true;
      notifyBtn.textContent = 'جاري الإرسال...';

      try {
        const res = await fetch("{{ route('notifications.save') }}", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
            case_id: currentCaseId,
            participant_name: selectedParticipant.name,
            method: method
          })
        });

        const data = await res.json();
        console.log('📥 Response:', data);

        if (!res.ok) {
          throw data;
        }

        showAlert(`✅ تم حفظ التبليغ للطرف: ${selectedParticipant.name} بطريقة: ${method}`, 'success');

      } catch (err) {
        console.error('❌ Error:', err);
        const errorMsg = err.error || err.message || 'حدث خطأ أثناء حفظ التبليغ';
        showAlert(`❌ ${errorMsg}`, 'danger');
      } finally {
        notifyBtn.disabled = false;
        notifyBtn.textContent = 'تنفيذ تبليغ';
      }
    });
  }

});
</script>







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

  // عناصر الإدخال - 4 boxes
  const caseSerial = $("arrest-case-serial");
  const courtNumber = $("arrest-court-number");
  const penNumber = $("arrest-pen-number");
  const yearNumber = $("arrest-year-number");

  const caseTypeInput = $("arrest-case-type");
  const participantsTableBody = document.querySelector("#arrest-participants-table tbody");

  const judgeNameInput = $("arrest-judge-name");
  const durationInput = $("arrest-duration");
  const reasonSelect = $("arrest-reason");
  const centerSelect = $("arrest-center");

  const saveBtn = $("arrest-save-btn");
  const alertBox = $("arrest-alert");

  let selectedRow = null;
  let selectedParticipant = null;
  let currentCaseId = null;

  function showAlert(msg, type = "warning") {
    alertBox.innerHTML = `<div class="alert alert-${type}">${msg}</div>`;
  }

  function clearAlert() { 
    alertBox.innerHTML = ""; 
  }

  function resetUI() {
    clearAlert();
    caseTypeInput.value = "";
    judgeNameInput.value = "";
    participantsTableBody.innerHTML = "";
    durationInput.value = "";
    reasonSelect.value = "";
    centerSelect.value = "";
    selectedRow = null;
    selectedParticipant = null;
  }

  // 🔍 البحث عند Enter في أول خانة
  if (caseSerial) {
    caseSerial.addEventListener("keypress", function(e) {
      if (e.key === "Enter") {
        searchCase();
      }
    });
  }

  function searchCase() {
    resetUI();

    const serial = caseSerial.value.trim();
    if (serial.length !== 4) {
      showAlert("⚠️ يرجى إدخال 4 أرقام في رقم الدعوى");
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
        case_number: serial
      })
    })
    .then(res => res.json().then(j => ({ ok: res.ok, json: j })))
    .then(({ ok, json }) => {

      if (!ok) throw json;

      clearAlert();

      // حفظ معرف القضية
      currentCaseId = json.case?.id;

      // تعبئة الخانات الأخرى
      courtNumber.value = json.case?.tribunal?.number ?? "";
      penNumber.value = json.case?.department?.number ?? "";
      yearNumber.value = json.case?.year ?? "";

      // نوع الدعوى
      caseTypeInput.value = json.case?.type ?? "";

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
          <td>${p.name}</td>
          <td>${p.type}</td>
          <td>${p.job ?? ""}</td>
          <td>${p.residence ?? ""}</td>
          <td>${p.phone ?? ""}</td>
          <td>الأمن العام</td>
        `;

        // Click to select row
        tr.addEventListener("click", () => {
          if (selectedRow) {
            selectedRow.classList.remove("selected");
          }
          tr.classList.add("selected");
          selectedRow = tr;
          selectedParticipant = p.name;
        });

        participantsTableBody.appendChild(tr);
      });

      showAlert("✅ تم تحميل بيانات الدعوى", "success");
    })
    .catch(err => {
      console.error(err);
      showAlert(err.error ?? "❌ حدث خطأ أثناء جلب بيانات الدعوى", "danger");
    });
  }

  // 💾 زر الحفظ
  if (saveBtn) {
    saveBtn.addEventListener("click", function() {
      clearAlert();

      if (!selectedParticipant) {
        showAlert("⚠️ يرجى اختيار طرف من الجدول");
        return;
      }

      if (!durationInput.value) {
        showAlert("⚠️ يرجى إدخال مدة التوقيف");
        return;
      }

      if (!reasonSelect.value) {
        showAlert("⚠️ يرجى اختيار سبب التوقيف");
        return;
      }

      if (!centerSelect.value) {
        showAlert("⚠️ يرجى اختيار مركز الإصلاح");
        return;
      }

      const caseNumber = caseSerial.value.trim();

      fetch("/writer/arrest-memo", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          case_number: caseNumber,
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

        setTimeout(() => {
          const modal = bootstrap.Modal.getInstance(modalEl);
          if (modal) modal.hide();
        }, 1000);
      })
      .catch(err => {
        console.error(err);
        showAlert(err.error ?? "❌ خطأ أثناء حفظ مذكرة التوقيف", "danger");
      });
    });
  }

  // إعادة ضبط عند إغلاق النافذة
  if (modalEl) {
    modalEl.addEventListener("hidden.bs.modal", function() {
      resetUI();
      if (caseSerial) caseSerial.value = "";
      if (courtNumber) courtNumber.value = "";
      if (penNumber) penNumber.value = "";
      if (yearNumber) yearNumber.value = "";
    });
  }

});
</script>










<script>
    //إدارة تباليغ الدعوى
document.addEventListener("DOMContentLoaded", function () {

  const modalId = "manage-notifications-modal";
  const $ = id => document.getElementById(id);

  const serialInput = $("manage-case-serial");
  const courtInput = $("manage-case-court");
  const penInput = $("manage-case-pen");
  const yearInput = $("manage-case-year");
  const searchBtn = $("manage-notifications-search");
  const saveBtn = $("manage-notifications-save");
  const alertBox = $("manage-notifications-alert");
  const tableBody = $("manage-notifications-tbody");

  function showAlert(msg, type="info") {
    alertBox.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
      ${msg}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>`;
  }

  function clearAlert() { alertBox.innerHTML = ""; }

  function resetTable() {
    tableBody.innerHTML = "";
  }

  // Auto-focus next input on 4-digit entry
  if (serialInput) {
    serialInput.addEventListener("input", function() {
      if (this.value.length === 4) {
        courtInput.focus();
      }
    });
  }

  // Search button click
  if (searchBtn) {
    searchBtn.addEventListener("click", fetchNotifications);
  }

  // Enter key on serial input
  if (serialInput) {
    serialInput.addEventListener("keyup", function(e) {
      if (e.key === "Enter" && this.value.length === 4) {
        fetchNotifications();
      }
    });
  }

  function fetchNotifications() {
    clearAlert();
    resetTable();

    const serial = serialInput.value.trim();
    if (!serial || serial.length !== 4) {
      showAlert("⚠️ أدخل رقم الدعوى (4 أرقام)", "warning");
      return;
    }

    const caseNumber = serial; // For now just use serial, extend if needed

    showAlert("⏳ جاري تحميل البيانات...", "info");

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

        // Fill court, pen, year fields
        if (courtInput) courtInput.value = json.case_court || '';
        if (penInput) penInput.value = json.case_pen || '';
        if (yearInput) yearInput.value = json.case_year || '';

        tableBody.innerHTML = "";
        notifications.forEach(n => {
          const tr = document.createElement("tr");

          // Extract date only from datetime (2025-10-23 13:37 → 2025-10-23)
          let dateOnly = '-';
          if (n.notified_at) {
            dateOnly = n.notified_at.split(' ')[0];
          }

          // Create cells - all as plain text
          const tdCaseNumber = document.createElement('td');
          tdCaseNumber.textContent = n.case_number || serial;

          const tdParticipantType = document.createElement('td');
          tdParticipantType.textContent = n.participant_type || '-';

          const tdParticipantName = document.createElement('td');
          tdParticipantName.textContent = n.participant_name || '-';

          // Method as plain text
          const tdMethod = document.createElement('td');
          tdMethod.textContent = n.method || '-';

          // Date as plain text
          const tdDate = document.createElement('td');
          tdDate.textContent = dateOnly;

          // Append all cells to row
          tr.appendChild(tdCaseNumber);
          tr.appendChild(tdParticipantType);
          tr.appendChild(tdParticipantName);
          tr.appendChild(tdMethod);
          tr.appendChild(tdDate);

          tableBody.appendChild(tr);
        });

        showAlert(`✅ تم تحميل ${notifications.length} تبليغ`, "success");

      })
      .catch(err => {
        console.error(err);
        resetTable();
        showAlert(err.error ?? "❌ حدث خطأ أثناء تحميل التبليغات", "danger");
      });
  }

  // Save button removed - this is a read-only view
  // If you need to edit notifications, use the other notification modals

  // مسح البيانات عند إغلاق النافذة
  document.getElementById(modalId).addEventListener("hidden.bs.modal", function () {
    clearAlert();
    resetTable();
    if (serialInput) serialInput.value = "";
    if (courtInput) courtInput.value = "";
    if (penInput) penInput.value = "";
    if (yearInput) yearInput.value = "";
  });

});
</script>






<script>
    // تبليغ مشتكي عليه
document.addEventListener("DOMContentLoaded", function () {
  console.log('🚀 تبليغ مشتكي عليه - Script loaded!');

  const modalId = "notif-complainant-modal";
  const $ = id => document.getElementById(id);

  const caseSerial = $("notif-complainant-case-serial");
  const courtNumber = $("notif-complainant-court-number");
  const penNumber = $("notif-complainant-pen-number");
  const yearNumber = $("notif-complainant-year-number");
  const searchBtn = $("notif-complainant-search");
  
  const caseType = $("notif-complainant-case-type");
  const judgeName = $("notif-complainant-judge-name");
  const tableBody = document.querySelector("#notif-complainant-parties-table tbody");
  
  const saveBtn = $("notif-complainant-save");
  const notifyBtn = $("notif-complainant-notify");
  const alertBox = $("notif-complainant-alert");

  console.log('📦 Elements check:', {
    caseSerial: !!caseSerial,
    searchBtn: !!searchBtn,
    saveBtn: !!saveBtn,
    notifyBtn: !!notifyBtn,
    tableBody: !!tableBody
  });

  let selectedRow = null;
  let selectedParticipant = null;
  let currentCaseData = [];
  let currentCaseId = null; // Store case ID

  function showAlert(msg, type = "info") {
    alertBox.innerHTML = `<div class="alert alert-${type}">${msg}</div>`;
  }

  function clearAlert() {
    alertBox.innerHTML = "";
  }

  function clearCaseDisplay() {
    caseType.value = "";
    judgeName.value = "";
    tableBody.innerHTML = "";
    selectedRow = null;
    selectedParticipant = null;
    currentCaseData = [];
  }

  // البحث عند الضغط Enter على رقم الدعوى
  caseSerial.addEventListener('keypress', function(e) {
    if (e.key !== 'Enter') return;

    const value = caseSerial.value.trim();
    if (value.length !== 4) {
      showAlert('⚠️ يجب إدخال 4 خانات', 'warning');
      return;
    }

    // تعبئة الحقول التلقائية
    courtNumber.value = '{{ auth()->user()->tribunal->number }}';
    penNumber.value = '{{ auth()->user()->department->number }}';
    yearNumber.value = new Date().getFullYear();

    // جلب البيانات من الخادم
    const fullCaseNumber = `${value}`;
    
    showAlert("⏳ جاري جلب بيانات القضية...", "info");

    const notificationType = "مذكرة تبليغ مشتكى عليه";

    fetch(`/court-cases/${encodeURIComponent(fullCaseNumber)}?notification_type=${encodeURIComponent(notificationType)}`)
      .then(res => res.json().then(j => ({ ok: res.ok, json: j })))
      .then(({ ok, json }) => {
        if (!ok) throw json;

        clearAlert();

        currentCaseId = json.case_id || json.id; // Store the case ID
        caseType.value = json.case_type ?? "";
        judgeName.value = json.judge_name ?? "";
        currentCaseData = json.participants ?? [];

        populateTable(currentCaseData);
      })
      .catch(err => {
        console.error(err);
        clearCaseDisplay();
        showAlert(err.error ?? "❌ لا يوجد سجل", "danger");
      });
  });

  // زر البحث
  searchBtn.addEventListener("click", function() {
    const event = new KeyboardEvent('keypress', { key: 'Enter', bubbles: true });
    caseSerial.dispatchEvent(event);
  });

  function populateTable(data) {
    tableBody.innerHTML = '';
    
    if (!data || data.length === 0) {
      showAlert("⚠️ لا يوجد أطراف من نوع 'مشتكى عليه' في هذه القضية.", "warning");
      return;
    }

    data.forEach((p, i) => {
      const tr = document.createElement('tr');
      tr.dataset.index = i;
      
      tr.innerHTML = `
        <td>${p.name ?? ''}</td>
        <td>${p.national_id ?? ''}</td>
        <td>${p.type ?? ''}</td>
        <td>${p.job ?? ''}</td>
        <td>${p.residence ?? ''}</td>
        <td>${p.phone ?? ''}</td>
        <td>
          <select class="notification-method-select" data-index="${i}" style="width:100%; padding:4px; border:1px solid #ccc; border-radius:4px;">
            <option value="">-- اختر --</option>
            <option value="sms">رسالة قصيرة</option>
            <option value="email">بريد إلكتروني</option>
            <option value="قسم التباليغ">قسم التباليغ</option>
          </select>
        </td>
      `;
      
      // Stop propagation on select to avoid row selection when clicking dropdown
      const select = tr.querySelector('.notification-method-select');
      select.addEventListener('click', (e) => {
        e.stopPropagation();
      });
      
      tr.addEventListener('click', () => {
        console.log('📋 Row clicked:', p.name);
        if (selectedRow) selectedRow.classList.remove('selected');
        tr.classList.add('selected');
        selectedRow = tr;
        selectedParticipant = p;
        console.log('✅ Participant selected:', selectedParticipant);
      });
      
      tableBody.appendChild(tr);
    });
  }

  // تنفيذ تبليغ - send to database
  console.log('🔍 Notify button element:', notifyBtn);
  console.log('🔍 Notify button element:', notifyBtn);
  if (notifyBtn) {
    console.log('✅ Adding click event listener to notify button');
    notifyBtn.addEventListener('click', () => {
      console.log('🔔 Notify button clicked!');
      console.log('Selected row:', selectedRow);
      console.log('Selected participant:', selectedParticipant);
      console.log('Current case ID:', currentCaseId);

      if (!selectedRow || !selectedParticipant) {
        showAlert('⚠️ حدد طرفا من الجدول', 'warning');
        return;
      }

      // Get the method from the selected row's dropdown
      const rowIndex = selectedRow.dataset.index;
      const methodSelect = selectedRow.querySelector('.notification-method-select');
      const method = methodSelect.value;

      console.log('Selected method:', method);

      if (!method) {
        showAlert('⚠️ اختر طريقة التبليغ من القائمة', 'warning');
        return;
      }

      if (!currentCaseId) {
        showAlert('⚠️ لا يوجد معرف للقضية', 'warning');
        return;
      }

      // Send to database
      console.log('📤 Sending notification:', {
        case_id: currentCaseId,
        participant_name: selectedParticipant.name,
        method: method
      });

      notifyBtn.disabled = true;
      notifyBtn.textContent = "جاري الإرسال...";

      fetch("{{ route('notifications.save') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          case_id: currentCaseId,
          participant_name: selectedParticipant.name,
          method: method
        })
      })
      .then(res => {
        if (!res.ok) {
          return res.json().then(err => Promise.reject(err));
        }
        return res.json();
      })
      .then(data => {
        console.log(' Response:', data);
        showAlert(`✅ تم حفظ التبليغ للطرف: ${selectedParticipant.name} بطريقة: ${method}`, 'success');
      })
      .catch(err => {
        console.error(err);
        showAlert('❌ حدث خطأ أثناء حفظ التبليغ', 'danger');
      })
      .finally(() => {
        notifyBtn.disabled = false;
        notifyBtn.textContent = "تنفيذ تبليغ";
      });
    });
  }

  // حفظ وإنهاء
  if (saveBtn) {
    saveBtn.addEventListener('click', () => {
      if (!selectedParticipant) {
        showAlert('⚠️ اختر طرفاً أولاً', 'warning');
        return;
      }

      // Get the method from the selected row's dropdown
      const rowIndex = selectedRow.dataset.index;
      const methodSelect = selectedRow.querySelector('.notification-method-select');
      const method = methodSelect.value;

      if (!method) {
        showAlert('⚠️ اختر طريقة التبليغ من القائمة', 'warning');
        return;
      }

      if (!currentCaseId) {
        showAlert('⚠️ لا يوجد معرف للقضية', 'warning');
        return;
      }
      
      saveBtn.disabled = true;
      saveBtn.textContent = "جاري الحفظ...";

      fetch("{{ route('notifications.save') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          case_id: currentCaseId,
          participant_name: selectedParticipant.name,
          method: method
        })
      })
      .then(res => {
        if (!res.ok) {
          return res.json().then(err => Promise.reject(err));
        }
        return res.json();
      })
      .then(data => {
        console.log(' Response:', data);
        showAlert('✅ تم الحفظ بنجاح', 'success');
        setTimeout(() => {
          const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
          if (modal) modal.hide();
        }, 700);
      })
      .catch(err => {
        console.error(err);
        showAlert('❌ حدث خطأ أثناء الحفظ', 'danger');
      })
      .finally(() => {
        saveBtn.disabled = false;
        saveBtn.textContent = "حفظ وانهاء";
      });
    });
  }

  // إعادة ضبط عند إغلاق النافذة
  document.getElementById(modalId).addEventListener('hidden.bs.modal', function () {
    clearCaseDisplay();
    clearAlert();
    caseSerial.value = "";
    courtNumber.value = "";
    penNumber.value = "";
    yearNumber.value = "";
    currentCaseId = null;
  });

});
</script>

<script>
    // تبليغ مشتكي موعد جلسة
document.addEventListener("DOMContentLoaded", function () {

  const modalId = "notif-session-complainant-modal";
  const $ = id => document.getElementById(id);

  const caseSerial = $("notif-session-complainant-case-serial");
  const courtNumber = $("notif-session-complainant-court-number");
  const penNumber = $("notif-session-complainant-pen-number");
  const yearNumber = $("notif-session-complainant-year-number");
  const searchBtn = $("notif-session-complainant-search");
  
  const caseType = $("notif-session-complainant-case-type");
  const judgeName = $("notif-session-complainant-judge-name");
  const tableBody = document.querySelector("#notif-session-complainant-parties-table tbody");
  
  const saveBtn = $("notif-session-complainant-save");
  const notifyBtn = $("notif-session-complainant-notify");
  const alertBox = $("notif-session-complainant-alert");

  let selectedRow = null;
  let selectedParticipant = null;
  let currentCaseData = [];
  let currentCaseId = null;

  function showAlert(msg, type = "info") {
    alertBox.innerHTML = `<div class="alert alert-${type}">${msg}</div>`;
  }

  function clearAlert() {
    alertBox.innerHTML = "";
  }

  function clearCaseDisplay() {
    caseType.value = "";
    judgeName.value = "";
    tableBody.innerHTML = "";
    selectedRow = null;
    selectedParticipant = null;
    currentCaseData = [];
  }

  // البحث عند الضغط Enter على رقم الدعوى
  caseSerial.addEventListener('keypress', function(e) {
    if (e.key !== 'Enter') return;

    const value = caseSerial.value.trim();
    if (value.length !== 4) {
      showAlert('⚠️ يجب إدخال 4 خانات', 'warning');
      return;
    }

    courtNumber.value = '{{ auth()->user()->tribunal->number }}';
    penNumber.value = '{{ auth()->user()->department->number }}';
    yearNumber.value = new Date().getFullYear();

    const fullCaseNumber = `${value}`;
    
    showAlert("⏳ جاري جلب بيانات القضية...", "info");

    const notificationType = "مذكرة تبليغ مشتكي موعد جلسة";

    fetch(`/court-cases/${encodeURIComponent(fullCaseNumber)}?notification_type=${encodeURIComponent(notificationType)}`)
      .then(res => res.json().then(j => ({ ok: res.ok, json: j })))
      .then(({ ok, json }) => {
        if (!ok) throw json;

        clearAlert();

        currentCaseId = json.case_id || json.id;
        caseType.value = json.case_type ?? "";
        judgeName.value = json.judge_name ?? "";
        currentCaseData = json.participants ?? [];

        populateTable(currentCaseData);
      })
      .catch(err => {
        console.error(err);
        clearCaseDisplay();
        showAlert(err.error ?? "❌ لا يوجد سجل", "danger");
      });
  });

  searchBtn.addEventListener("click", function() {
    const event = new KeyboardEvent('keypress', { key: 'Enter', bubbles: true });
    caseSerial.dispatchEvent(event);
  });

  function populateTable(data) {
    tableBody.innerHTML = '';
    
    if (!data || data.length === 0) {
      showAlert("⚠️ لا يوجد أطراف من نوع 'مشتكي' في هذه القضية.", "warning");
      return;
    }

    data.forEach((p, i) => {
      const tr = document.createElement('tr');
      tr.dataset.index = i;
      
      tr.innerHTML = `
        <td>${p.name ?? ''}</td>
        <td>${p.national_id ?? ''}</td>
        <td>${p.type ?? ''}</td>
        <td>${p.job ?? ''}</td>
        <td>${p.residence ?? ''}</td>
        <td>${p.phone ?? ''}</td>
        <td>
          <select class="notification-method-select" data-index="${i}" style="width:100%; padding:4px; border:1px solid #ccc; border-radius:4px;">
            <option value="">-- اختر --</option>
            <option value="sms">رسالة قصيرة</option>
            <option value="email">بريد إلكتروني</option>
            <option value="قسم التباليغ">قسم التباليغ</option>
          </select>
        </td>
      `;
      
      const select = tr.querySelector('.notification-method-select');
      select.addEventListener('click', (e) => {
        e.stopPropagation();
      });
      
      tr.addEventListener('click', () => {
        if (selectedRow) selectedRow.classList.remove('selected');
        tr.classList.add('selected');
        selectedRow = tr;
        selectedParticipant = p;
      });
      
      tableBody.appendChild(tr);
    });
  }

  if (notifyBtn) {
    notifyBtn.addEventListener('click', () => {
      if (!selectedRow || !selectedParticipant) {
        showAlert('⚠️ حدد طرفا من الجدول', 'warning');
        return;
      }

      const rowIndex = selectedRow.dataset.index;
      const methodSelect = selectedRow.querySelector('.notification-method-select');
      const method = methodSelect.value;

      if (!method) {
        showAlert('⚠️ اختر طريقة التبليغ من القائمة', 'warning');
        return;
      }

      if (!currentCaseId) {
        showAlert('⚠️ لا يوجد معرف للقضية', 'warning');
        return;
      }

      notifyBtn.disabled = true;
      notifyBtn.textContent = "جاري الإرسال...";

      fetch("{{ route('notifications.save') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          case_id: currentCaseId,
          participant_name: selectedParticipant.name,
          method: method
        })
      })
      .then(res => {
        if (!res.ok) {
          return res.json().then(err => Promise.reject(err));
        }
        return res.json();
      })
      .then(data => {
        console.log(' Response:', data);
        showAlert(`✅ تم حفظ التبليغ للطرف: ${selectedParticipant.name} بطريقة: ${method}`, 'success');
      })
      .catch(err => {
        console.error(err);
        showAlert('❌ حدث خطأ أثناء حفظ التبليغ', 'danger');
      })
      .finally(() => {
        notifyBtn.disabled = false;
        notifyBtn.textContent = "تنفيذ تبليغ";
      });
    });
  }

  if (saveBtn) {
    saveBtn.addEventListener('click', () => {
      if (!selectedParticipant) {
        showAlert('⚠️ اختر طرفاً أولاً', 'warning');
        return;
      }

      const rowIndex = selectedRow.dataset.index;
      const methodSelect = selectedRow.querySelector('.notification-method-select');
      const method = methodSelect.value;

      if (!method) {
        showAlert('⚠️ اختر طريقة التبليغ من القائمة', 'warning');
        return;
      }

      if (!currentCaseId) {
        showAlert('⚠️ لا يوجد معرف للقضية', 'warning');
        return;
      }
      
      saveBtn.disabled = true;
      saveBtn.textContent = "جاري الحفظ...";

      fetch("{{ route('notifications.save') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          case_id: currentCaseId,
          participant_name: selectedParticipant.name,
          method: method
        })
      })
      .then(res => {
        if (!res.ok) {
          return res.json().then(err => Promise.reject(err));
        }
        return res.json();
      })
      .then(data => {
        console.log(' Response:', data);
        showAlert('✅ تم الحفظ بنجاح', 'success');
        setTimeout(() => {
          const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
          if (modal) modal.hide();
        }, 700);
      })
      .catch(err => {
        console.error(err);
        showAlert('❌ حدث خطأ أثناء الحفظ', 'danger');
      })
      .finally(() => {
        saveBtn.disabled = false;
        saveBtn.textContent = "حفظ وانهاء";
      });
    });
  }

  document.getElementById(modalId).addEventListener('hidden.bs.modal', function () {
    clearCaseDisplay();
    clearAlert();
    caseSerial.value = "";
    courtNumber.value = "";
    penNumber.value = "";
    yearNumber.value = "";
    currentCaseId = null;
  });

});
</script>

<script>
    // تبليغ شاهد موعد جلسة
document.addEventListener("DOMContentLoaded", function () {

  const modalId = "notif-witness-modal";
  const $ = id => document.getElementById(id);

  const caseSerial = $("notif-witness-case-serial");
  const courtNumber = $("notif-witness-court-number");
  const penNumber = $("notif-witness-pen-number");
  const yearNumber = $("notif-witness-year-number");
  const searchBtn = $("notif-witness-search");
  
  const caseType = $("notif-witness-case-type");
  const judgeName = $("notif-witness-judge-name");
  const tableBody = document.querySelector("#notif-witness-parties-table tbody");
  
  const saveBtn = $("notif-witness-save");
  const notifyBtn = $("notif-witness-notify");
  const alertBox = $("notif-witness-alert");

  let selectedRow = null;
  let selectedParticipant = null;
  let currentCaseData = [];
  let currentCaseId = null;

  function showAlert(msg, type = "info") {
    alertBox.innerHTML = `<div class="alert alert-${type}">${msg}</div>`;
  }

  function clearAlert() {
    alertBox.innerHTML = "";
  }

  function clearCaseDisplay() {
    caseType.value = "";
    judgeName.value = "";
    tableBody.innerHTML = "";
    selectedRow = null;
    selectedParticipant = null;
    currentCaseData = [];
  }

  // البحث عند الضغط Enter على رقم الدعوى
  caseSerial.addEventListener('keypress', function(e) {
    if (e.key !== 'Enter') return;

    const value = caseSerial.value.trim();
    if (value.length !== 4) {
      showAlert('⚠️ يجب إدخال 4 خانات', 'warning');
      return;
    }

    courtNumber.value = '{{ auth()->user()->tribunal->number }}';
    penNumber.value = '{{ auth()->user()->department->number }}';
    yearNumber.value = new Date().getFullYear();

    const fullCaseNumber = `${value}`;
    
    showAlert("⏳ جاري جلب بيانات القضية...", "info");

    const notificationType = "مذكرة تبليغ شاهد موعد جلسة";

    fetch(`/court-cases/${encodeURIComponent(fullCaseNumber)}?notification_type=${encodeURIComponent(notificationType)}`)
      .then(res => res.json().then(j => ({ ok: res.ok, json: j })))
      .then(({ ok, json }) => {
        if (!ok) throw json;

        clearAlert();

        currentCaseId = json.case_id || json.id;
        caseType.value = json.case_type ?? "";
        judgeName.value = json.judge_name ?? "";
        currentCaseData = json.participants ?? [];

        populateTable(currentCaseData);
      })
      .catch(err => {
        console.error(err);
        clearCaseDisplay();
        showAlert(err.error ?? "❌ لا يوجد سجل", "danger");
      });
  });

  searchBtn.addEventListener("click", function() {
    const event = new KeyboardEvent('keypress', { key: 'Enter', bubbles: true });
    caseSerial.dispatchEvent(event);
  });

  function populateTable(data) {
    tableBody.innerHTML = '';
    
    if (!data || data.length === 0) {
      showAlert("⚠️ لا يوجد أطراف من نوع 'شاهد' في هذه القضية.", "warning");
      return;
    }

    data.forEach((p, i) => {
      const tr = document.createElement('tr');
      tr.dataset.index = i;
      
      tr.innerHTML = `
        <td>${p.name ?? ''}</td>
        <td>${p.national_id ?? ''}</td>
        <td>${p.type ?? ''}</td>
        <td>${p.job ?? ''}</td>
        <td>${p.residence ?? ''}</td>
        <td>${p.phone ?? ''}</td>
        <td>
          <select class="notification-method-select" data-index="${i}" style="width:100%; padding:4px; border:1px solid #ccc; border-radius:4px;">
            <option value="">-- اختر --</option>
            <option value="sms">رسالة قصيرة</option>
            <option value="email">بريد إلكتروني</option>
            <option value="قسم التباليغ">قسم التباليغ</option>
          </select>
        </td>
      `;
      
      const select = tr.querySelector('.notification-method-select');
      select.addEventListener('click', (e) => {
        e.stopPropagation();
      });
      
      tr.addEventListener('click', () => {
        if (selectedRow) selectedRow.classList.remove('selected');
        tr.classList.add('selected');
        selectedRow = tr;
        selectedParticipant = p;
      });
      
      tableBody.appendChild(tr);
    });
  }

  if (notifyBtn) {
    notifyBtn.addEventListener('click', () => {
      if (!selectedRow || !selectedParticipant) {
        showAlert('⚠️ حدد طرفا من الجدول', 'warning');
        return;
      }

      const rowIndex = selectedRow.dataset.index;
      const methodSelect = selectedRow.querySelector('.notification-method-select');
      const method = methodSelect.value;

      if (!method) {
        showAlert('⚠️ اختر طريقة التبليغ من القائمة', 'warning');
        return;
      }

      if (!currentCaseId) {
        showAlert('⚠️ لا يوجد معرف للقضية', 'warning');
        return;
      }

      notifyBtn.disabled = true;
      notifyBtn.textContent = "جاري الإرسال...";

      fetch("{{ route('notifications.save') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          case_id: currentCaseId,
          participant_name: selectedParticipant.name,
          method: method
        })
      })
      .then(res => {
        if (!res.ok) {
          return res.json().then(err => Promise.reject(err));
        }
        return res.json();
      })
      .then(data => {
        console.log(' Response:', data);
        showAlert(`✅ تم حفظ التبليغ للطرف: ${selectedParticipant.name} بطريقة: ${method}`, 'success');
      })
      .catch(err => {
        console.error(err);
        showAlert('❌ حدث خطأ أثناء حفظ التبليغ', 'danger');
      })
      .finally(() => {
        notifyBtn.disabled = false;
        notifyBtn.textContent = "تنفيذ تبليغ";
      });
    });
  }

  if (saveBtn) {
    saveBtn.addEventListener('click', () => {
      if (!selectedParticipant) {
        showAlert('⚠️ اختر طرفاً أولاً', 'warning');
        return;
      }

      const rowIndex = selectedRow.dataset.index;
      const methodSelect = selectedRow.querySelector('.notification-method-select');
      const method = methodSelect.value;

      if (!method) {
        showAlert('⚠️ اختر طريقة التبليغ من القائمة', 'warning');
        return;
      }

      if (!currentCaseId) {
        showAlert('⚠️ لا يوجد معرف للقضية', 'warning');
        return;
      }
      
      saveBtn.disabled = true;
      saveBtn.textContent = "جاري الحفظ...";

      fetch("{{ route('notifications.save') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          case_id: currentCaseId,
          participant_name: selectedParticipant.name,
          method: method
        })
      })
      .then(res => {
        if (!res.ok) {
          return res.json().then(err => Promise.reject(err));
        }
        return res.json();
      })
      .then(data => {
        console.log(' Response:', data);
        showAlert('✅ تم الحفظ بنجاح', 'success');
        setTimeout(() => {
          const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
          if (modal) modal.hide();
        }, 700);
      })
      .catch(err => {
        console.error(err);
        showAlert('❌ حدث خطأ أثناء الحفظ', 'danger');
      })
      .finally(() => {
        saveBtn.disabled = false;
        saveBtn.textContent = "حفظ وانهاء";
      });
    });
  }

  document.getElementById(modalId).addEventListener('hidden.bs.modal', function () {
    clearCaseDisplay();
    clearAlert();
    caseSerial.value = "";
    courtNumber.value = "";
    penNumber.value = "";
    yearNumber.value = "";
    currentCaseId = null;
  });

});
</script>

<script>
    // تبليغ شاهد موعد جلسة
document.addEventListener("DOMContentLoaded", function () {

  const modalId = "notif-witness-modal";
  const $ = id => document.getElementById(id);

  const caseSerial = $("notif-witness-case-serial");
  const courtNumber = $("notif-witness-court-number");
  const penNumber = $("notif-witness-pen-number");
  const yearNumber = $("notif-witness-year-number");
  const searchBtn = $("notif-witness-search");
  
  const caseType = $("notif-witness-case-type");
  const judgeName = $("notif-witness-judge-name");
  const tableBody = document.querySelector("#notif-witness-parties-table tbody");
  
  const saveBtn = $("notif-witness-save");
  const notifyBtn = $("notif-witness-notify");
  const alertBox = $("notif-witness-alert");

  let selectedRow = null;
  let selectedParticipant = null;
  let currentCaseData = [];
  let currentCaseId = null;

  function showAlert(msg, type = "info") {
    alertBox.innerHTML = `<div class="alert alert-${type}">${msg}</div>`;
  }

  function clearAlert() {
    alertBox.innerHTML = "";
  }

  function clearCaseDisplay() {
    caseType.value = "";
    judgeName.value = "";
    tableBody.innerHTML = "";
    selectedRow = null;
    selectedParticipant = null;
    currentCaseData = [];
  }

  // البحث عند الضغط Enter على رقم الدعوى
  caseSerial.addEventListener('keypress', function(e) {
    if (e.key !== 'Enter') return;

    const value = caseSerial.value.trim();
    if (value.length !== 4) {
      showAlert('⚠️ يجب إدخال 4 خانات', 'warning');
      return;
    }

    courtNumber.value = '{{ auth()->user()->tribunal->number }}';
    penNumber.value = '{{ auth()->user()->department->number }}';
    yearNumber.value = new Date().getFullYear();

    const fullCaseNumber = `${value}`;
    
    showAlert("⏳ جاري جلب بيانات القضية...", "info");

    const notificationType = "مذكرة تبليغ شاهد موعد جلسة";

    fetch(`/court-cases/${encodeURIComponent(fullCaseNumber)}?notification_type=${encodeURIComponent(notificationType)}`)
      .then(res => res.json().then(j => ({ ok: res.ok, json: j })))
      .then(({ ok, json }) => {
        if (!ok) throw json;

        clearAlert();

        currentCaseId = json.case_id || json.id;
        caseType.value = json.case_type ?? "";
        judgeName.value = json.judge_name ?? "";
        currentCaseData = json.participants ?? [];

        populateTable(currentCaseData);
      })
      .catch(err => {
        console.error(err);
        clearCaseDisplay();
        showAlert(err.error ?? "❌ لا يوجد سجل", "danger");
      });
  });

  searchBtn.addEventListener("click", function() {
    const event = new KeyboardEvent('keypress', { key: 'Enter', bubbles: true });
    caseSerial.dispatchEvent(event);
  });

  function populateTable(data) {
    tableBody.innerHTML = '';
    
    if (!data || data.length === 0) {
      showAlert("⚠️ لا يوجد أطراف من نوع 'شاهد' في هذه القضية.", "warning");
      return;
    }

    data.forEach((p, i) => {
      const tr = document.createElement('tr');
      tr.dataset.index = i;
      
      tr.innerHTML = `
        <td>${p.name ?? ''}</td>
        <td>${p.national_id ?? ''}</td>
        <td>${p.type ?? ''}</td>
        <td>${p.job ?? ''}</td>
        <td>${p.residence ?? ''}</td>
        <td>${p.phone ?? ''}</td>
        <td>
          <select class="notification-method-select" data-index="${i}" style="width:100%; padding:4px; border:1px solid #ccc; border-radius:4px;">
            <option value="">-- اختر --</option>
            <option value="sms">رسالة قصيرة</option>
            <option value="email">بريد إلكتروني</option>
            <option value="قسم التباليغ">قسم التباليغ</option>
          </select>
        </td>
      `;
      
      const select = tr.querySelector('.notification-method-select');
      select.addEventListener('click', (e) => {
        e.stopPropagation();
      });
      
      tr.addEventListener('click', () => {
        if (selectedRow) selectedRow.classList.remove('selected');
        tr.classList.add('selected');
        selectedRow = tr;
        selectedParticipant = p;
      });
      
      tableBody.appendChild(tr);
    });
  }

  if (notifyBtn) {
    notifyBtn.addEventListener('click', () => {
      if (!selectedRow || !selectedParticipant) {
        showAlert('⚠️ حدد طرفا من الجدول', 'warning');
        return;
      }

      const rowIndex = selectedRow.dataset.index;
      const methodSelect = selectedRow.querySelector('.notification-method-select');
      const method = methodSelect.value;

      if (!method) {
        showAlert('⚠️ اختر طريقة التبليغ من القائمة', 'warning');
        return;
      }

      if (!currentCaseId) {
        showAlert('⚠️ لا يوجد معرف للقضية', 'warning');
        return;
      }

      notifyBtn.disabled = true;
      notifyBtn.textContent = "جاري الإرسال...";

      fetch("{{ route('notifications.save') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          case_id: currentCaseId,
          participant_name: selectedParticipant.name,
          method: method
        })
      })
      .then(res => {
        if (!res.ok) {
          return res.json().then(err => Promise.reject(err));
        }
        return res.json();
      })
      .then(data => {
        console.log(' Response:', data);
        showAlert(`✅ تم حفظ التبليغ للطرف: ${selectedParticipant.name} بطريقة: ${method}`, 'success');
      })
      .catch(err => {
        console.error(err);
        showAlert('❌ حدث خطأ أثناء حفظ التبليغ', 'danger');
      })
      .finally(() => {
        notifyBtn.disabled = false;
        notifyBtn.textContent = "تنفيذ تبليغ";
      });
    });
  }

  if (saveBtn) {
    saveBtn.addEventListener('click', () => {
      if (!selectedParticipant) {
        showAlert('⚠️ اختر طرفاً أولاً', 'warning');
        return;
      }

      const rowIndex = selectedRow.dataset.index;
      const methodSelect = selectedRow.querySelector('.notification-method-select');
      const method = methodSelect.value;

      if (!method) {
        showAlert('⚠️ اختر طريقة التبليغ من القائمة', 'warning');
        return;
      }

      if (!currentCaseId) {
        showAlert('⚠️ لا يوجد معرف للقضية', 'warning');
        return;
      }
      
      saveBtn.disabled = true;
      saveBtn.textContent = "جاري الحفظ...";

      fetch("{{ route('notifications.save') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          case_id: currentCaseId,
          participant_name: selectedParticipant.name,
          method: method
        })
      })
      .then(res => {
        if (!res.ok) {
          return res.json().then(err => Promise.reject(err));
        }
        return res.json();
      })
      .then(data => {
        console.log(' Response:', data);
        showAlert('✅ تم الحفظ بنجاح', 'success');
        setTimeout(() => {
          const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
          if (modal) modal.hide();
        }, 700);
      })
      .catch(err => {
        console.error(err);
        showAlert('❌ حدث خطأ أثناء الحفظ', 'danger');
      })
      .finally(() => {
        saveBtn.disabled = false;
        saveBtn.textContent = "حفظ وانهاء";
      });
    });
  }

  document.getElementById(modalId).addEventListener('hidden.bs.modal', function () {
    clearCaseDisplay();
    clearAlert();
    caseSerial.value = "";
    courtNumber.value = "";
    penNumber.value = "";
    yearNumber.value = "";
    currentCaseId = null;
  });

});
</script>


<script>
    //خيارات التباليغ
document.addEventListener("DOMContentLoaded", function () {

    const mainTrigger = document.getElementById("trigger-notifications");
    const menu = document.getElementById("notifications-menu");

    if (!mainTrigger || !menu) return; // Exit if elements not found

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
            .then(res => {
        if (!res.ok) {
          return res.json().then(err => Promise.reject(err));
        }
        return res.json();
      })
      .then(data => {
        console.log(' Response:', data);

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
        .then(res => {
        if (!res.ok) {
          return res.json().then(err => Promise.reject(err));
        }
        return res.json();
      })
      .then(data => {
        console.log(' Response:', data);
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
        .then(res => {
        if (!res.ok) {
          return res.json().then(err => Promise.reject(err));
        }
        return res.json();
      })
      .then(data => {
        console.log(' Response:', data);

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