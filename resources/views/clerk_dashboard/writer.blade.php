@extends('layouts.app')

@section('title', 'لوحة الكاتب')

@section('content')

<!-- ⭐ قائمة الدعوى / الطلب الخاصة بالكاتب -->
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


<li id="open-register-request"
    style="padding:10px; border-bottom:1px solid #ddd; cursor:pointer;">
    تسجيل طلب
</li>
        <li style="padding:10px; border-bottom:1px solid #ddd; cursor:pointer;"
            data-bs-toggle="modal" data-bs-target="#withdrawCaseModal">
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
                        <input type="text" id="first_name" placeholder="مثال: محمد">
                    </div>
                    <div class="field">
                        <label for="father_name">اسم الأب</label>
                        <input type="text" id="father_name" placeholder="مثال: علي">
                    </div>
                    <div class="field">
                        <label for="grandfather_name">اسم الجد (اختياري)</label>
                        <input type="text" id="grandfather_name" placeholder="مثال: حمد">
                    </div>
                    <div class="field">
                        <label for="family_name">اسم العائلة</label>
                        <input type="text" id="family_name" placeholder="مثال: الخطيب">
                    </div>
                    <div class="field">
                        <label for="mother_name">اسم الأم</label>
                        <input type="text" id="mother_name" placeholder="مثال: سعاد">
                    </div>
                    <div class="field">
                        <label for="occupation">المهنة</label>
                        <input type="text" id="occupation" placeholder="مثال: مهندس">
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
                        <input type="text" id="nationality" placeholder="مثال: أردني">
                    </div>
                </div>

                <div class="controls">
                    <button class="search-btn" onclick="searchCivilRegistry()">بحث</button>
                    <div style="margin-left:8px;color:#555">اضغط بحث لعرض نتائج في الجدول أدناه</div>
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
















@yield('chief-extra')
@endsection
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
    input.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            fetchRequestSchedule();
        }
    });

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
                console.log("🔑 Keys:", Object.keys(data[0]));
                console.log("🟢 First Name Value:", data[0].first_name);
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
document.addEventListener('DOMContentLoaded', function() {

    const trigger = document.getElementById('sessions-trigger'); // من layouts.app
    const menu = document.getElementById('writer-sessions-submenu');

    if (!trigger || !menu) return;

    // عند الوقوف على كلمة الجلسات
    trigger.addEventListener('mouseenter', () => {
        menu.style.display = 'block';
    });

    // اخفاء القائمة عند خروج الماوس
    trigger.addEventListener('mouseleave', () => {
        setTimeout(() => {
            if (!menu.matches(':hover')) {
                menu.style.display = 'none';
            }
        }, 150);
    });

    menu.addEventListener('mouseleave', () => {
        menu.style.display = 'none';
    });

});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const trigger = document.getElementById('sessions-trigger'); // من layouts.app
    const menu = document.getElementById('writer-sessions-submenu');

    if (!trigger || !menu) return;

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

    // ⭐ إضافة مستند
    const addEvidenceBtn = document.getElementById("addEvidence");
    const evidenceContainer = document.getElementById("evidenceContainer");

    if (addEvidenceBtn && evidenceContainer) {
        addEvidenceBtn.addEventListener("click", () => {
            const newEvidence = document.createElement("div");
            newEvidence.className = "evidence-block";
            newEvidence.innerHTML = `
                <button type="button" class="remove-party" onclick="this.parentElement.remove()">×</button>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">نوع المستند</label>
                        <input type="text" class="form-control evidence-type">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">رفع الملف</label>
                        <input type="file" class="form-control evidence-file">
                    </div>
                </div>
            `;
            evidenceContainer.appendChild(newEvidence);
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

                // حذف المستندات المضافة (ماعدا الأول)
                const allEvidence = evidenceContainer.querySelectorAll(".evidence-block");
                allEvidence.forEach((evidence, index) => {
                    if (index > 0) evidence.remove();
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
                        <td>${item.full_name         ?? '-'}</td>
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