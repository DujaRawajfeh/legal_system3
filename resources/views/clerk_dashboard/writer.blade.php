@extends('layouts.app')

@section('title', 'لوحة الكاتب')

@section('content')

<style>



    #case-options {
        position: absolute;
        background-color: #f8f9fa;
        border: 1px solid #ccc;
        width: 250px;
        z-index: 1000;
        display: none;
        text-align: right;
    }

    #case-options ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    #case-options li {
        padding: 10px;
        cursor: pointer;
        border-bottom: 1px solid #ddd;
    }

    #case-options li:hover {
        background-color: #e9ecef;
    }

</style>

<!-- ✅ القائمة -->
<div id="case-options">
    <ul>
        <li id="open-register-case">تسجيل دعوى</li>
         <li data-bs-toggle="modal" data-bs-target="#withdrawCaseModal">سحب دعوى</li>
         <li i d="withdraw-police-case" data-bs-toggle="modal" data-bs-target="#pullPoliceCaseModal">سحب قضية من الشرطة</li>
         <li id="financial-info">الترسيم و المعلومات المالية</li>
         <li data-bs-toggle="modal" data-bs-target="#participantsModal"> االمشاركين    </li>
    </ul>
</div>

<!-- ✅ نافذة modal -->
<div class="modal fade" id="registerCaseModal" tabindex="-1" aria-labelledby="registerCaseLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="margin-top: 80px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="registerCaseLabel">تسجيل الدعوى</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>

      <div class="modal-body">
        <form class="row g-3" method="POST" action="/writer/store-case">
  @csrf

          <!-- ✅ نوع الدعوى -->
          <div class="col-md-3">
            <label class="form-label">نوع الدعوى</label>
            <select class="form-select form-select-sm" id="caseType">
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

          <!-- ✅ رقم المحكمة -->
          <div class="col-md-3">
            <label class="form-label">رقم المحكمة</label>
            <input type="text" class="form-control form-control-sm" id="courtNumber" value="{{ auth()->user()->tribunal->number }}" readonly>
          </div>

          <!-- ✅ رقم القلم -->
          <div class="col-md-3">
            <label class="form-label">رقم القلم</label>
            <input type="text" class="form-control form-control-sm" id="departmentNumber" value="{{ auth()->user()->department->number }}" readonly>
          </div>

          <!-- ✅ رقم الدعوى -->
          <div class="col-md-3">
            <label class="form-label">رقم الدعوى</label>
            <input type="text" class="form-control form-control-sm" id="caseNumber" placeholder="اضغط Enter">
          </div>

          <!-- ✅ السنة -->
          <div class="col-md-3">
            <label class="form-label">السنة</label>
            <input type="text" class="form-control form-control-sm" id="caseYear" placeholder="اضغط Enter">
          </div>

          <!-- ✅ نوع الطرف -->
          <div class="col-md-3">
            <label class="form-label">نوع الطرف</label>
            <select class="form-select form-select-sm" id="partyType">
              <option value="">اختر...</option>
              <option value="مشتكي">مشتكي</option>
              <option value="مشتكى عليه">مشتكى عليه</option>
              <option value="شاهد">شاهد</option>
            </select>
          </div>

          <!-- ✅ اسم الطرف -->
          <div class="col-md-3">
            <label class="form-label">اسم الطرف</label>
            <input type="text" class="form-control form-control-sm" id="partyName">
          </div>

          <!-- ✅ الرقم الوطني -->
          <div class="col-md-3">
            <label class="form-label">الرقم الوطني</label>
            <input type="text" class="form-control form-control-sm" id="nationalId">
          </div>

          <!-- ✅ مكان السكن -->
          <div class="col-md-3">
            <label class="form-label">مكان السكن</label>
            <input type="text" class="form-control form-control-sm" id="residence">
          </div>

          <!-- ✅ الوظيفة / مكان العمل -->
          <div class="col-md-3">
            <label class="form-label">الوظيفة / مكان العمل</label>
            <input type="text" class="form-control form-control-sm" id="job">
          </div>

          <!-- ✅ رقم الهاتف -->
          <div class="col-md-3">
            <label class="form-label">رقم الهاتف</label>
            <input type="text" class="form-control form-control-sm" id="phone">
          </div>

          <div class="mb-3">
  <label class="form-label">القاضي المعيّن تلقائيًا</label>
  <input type="text" id="judge_name" class="form-control" readonly>
  <input type="hidden" name="judge_id" id="judge_id">
</div>


<div class="mb-3">
  <label class="form-label">موعد الجلسة</label>
  <input type="text" id="session_date" class="form-control" readonly>

</div>
        </form>
      </div>
      <!-- ✅ أزرار التحكم -->
      <div class="modal-footer d-flex justify-content-between">
        <div>
          <button type="button" class="btn btn-success btn-sm">حفظ</button>
          <button type="submit" class="btn btn-primary btn-sm" id="saveAndFinish">حفظ وإنهاء</button>
        </div>
        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">إنهاء المدخلات</button>
      </div>
    </div>
  </div>
</div>



<!-- ✅ القائمة المنسدلة للتباليغ -->
<div id="notifications-menu" style="display: none; position: absolute; top: 50px; right: 20px; background: white; border: 1px solid #ccc; padding: 10px; z-index: 999;">
  <ul style="list-style: none; padding: 0; margin: 0;">
    <li style="position: relative; margin-bottom: 5px;">
      <span id="security-toggle" class="dropdown-item fw-bold text-primary" style="cursor: pointer;">📎 كتب مخاطبات الأمن العام/الدعوى ▸</span>
      <ul id="security-submenu" style="display: none; position: absolute; top: 0; right: 100%; background: white; border: 1px solid #ccc; padding: 10px; z-index: 999;">
        <li><span class="dropdown-item" data-bs-toggle="modal" data-bs-target="#arrestMemoModal" style="cursor: pointer;"> مذكرة توقيف</span></li>
        <li><span class="dropdown-item" data-bs-toggle="modal" data-bs-target="#extendArrestModal" style="cursor: pointer;">مذكرة تمديد توقيف</span></li>
        <li>
       <span class="dropdown-item" data-bs-toggle="modal" data-bs-target="#releaseMemoModal" style="cursor: pointer;">مذكرة إفراج للموقوفين</span></li>
        <li><span class="dropdown-item open-notification-modal" data-type="مذكرة توديع نزلاء" style="cursor: pointer;">مذكرة توديع نزلاء</span></li>
      </ul>
    </li>

    <li style="position: relative;">
      <span id="notifications-toggle" class="dropdown-item fw-bold text-primary" style="cursor: pointer;">📄 تباليغ الدعوى ▸</span>
      <ul id="notifications-submenu" style="display: none; position: absolute; top: 0; right: 100%; background: white; border: 1px solid #ccc; padding: 10px; z-index: 999;">
        <li><span class="dropdown-item open-notification-modal" data-type="مذكرة تبليغ مشتكى عليه" style="cursor: pointer;">مذكرة تبليغ مشتكى عليه</span></li>
        <li><span class="dropdown-item open-notification-modal" data-type="مذكرة تبليغ مشتكي موعد جلسة" style="cursor: pointer;">مذكرة تبليغ مشتكي موعد جلسة</span></li>
        <li><span class="dropdown-item open-notification-modal" data-type="مذكرة حضور خاصة بالشهود" style="cursor: pointer;">مذكرة حضور خاصة بالشهود</span></li>
        <li><span class="dropdown-item open-notification-modal" data-type="مذكرة تبليغ حكم" style="cursor: pointer;">مذكرة تبليغ حكم</span></li>
      </ul>
    </li>
  </ul>
</div>

<!--ت نافذه التباليغ-->
<div class="modal fade" id="notificationModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="notification-title" class="modal-title">عنوان التبليغ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <!-- ✅ رقم الدعوى -->
        <div class="mb-3">
          <label>رقم الدعوى</label>
          <input type="text" id="notificationCaseNumber" class="form-control" placeholder="أدخل رقم الدعوى">
        </div>

        <!-- ✅ المحكمة والقلم والسنة -->
        <div class="row mb-3">
          <div class="col">
            <label for="courtDisplay" class="form-label">رقم المحكمة</label>
            <input type="text" id="courtDisplay" name="courtDisplay" class="form-control bg-white" readonly>
          </div>
          <div class="col">
            <label for="deptDisplay" class="form-label">رقم القلم</label>
            <input type="text" id="deptDisplay" name="deptDisplay" class="form-control bg-white" readonly>
          </div>
          <div class="col">
            <label for="yearDisplay" class="form-label">السنة</label>
            <input type="text" id="yearDisplay" name="yearDisplay" class="form-control bg-white" readonly>
          </div>
        </div>

        <!-- ✅ معلومات الدعوى -->
        <h6 class="mt-4 mb-2">📌 معلومات الدعوى</h6>
        <div class="mb-3">
          <label>نوع الدعوى</label>
          <input type="text" id="caseTypeDisplay" class="form-control" disabled>
        </div>

        <div id="finalVerdictBox" style="display: none;" class="mb-3">
          <label class="form-label fw-bold">📌 الحكم النهائي</label>
          <div class="border rounded p-2 bg-light" id="finalVerdictText">سيتم جلب الحكم لاحقًا...</div>
        </div>

        <!-- ✅ جدول معلومات الأطراف -->
        <table class="table table-bordered" id="participantsTable">
          <thead>
            <tr>
              <th>اختيار</th>
              <th>الاسم</th>
              <th>الرقم الوطني</th>
              <th>نوع الطرف</th>
              <th>الوظيفة</th>
              <th>مكان الإقامة</th>
              <th>رقم الهاتف</th>
              <th id="notifyHeader">قسم التباليغ</th>
              <th id="electronicHeader">تبليغ إلكتروني</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><input type="checkbox" class="participant-select"></td>
              <td>اسم الطرف</td>
              <td>رقم وطني</td>
              <td>نوع الطرف</td>
              <td>الوظيفة</td>
              <td>مكان الإقامة</td>
              <td>رقم الهاتف</td>
              <td class="notify-cell">
                <div>
                  <label>قسم التباليغ</label><br>
                  <button class="btn btn-sm btn-outline-primary">قسم التباليغ</button>
                </div>
              </td>
              <td class="electronic-cell">
                <div>
                  <label>تبليغ إلكتروني</label><br>
                  <select class="form-select form-select-sm">
                    <option selected disabled>اختر</option>
                    <option value="email">بريد إلكتروني</option>
                    <option value="sms">رسالة قصيرة</option>
                  </select>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-primary save-notifications">حفظ</button>
        <button type="button" class="btn btn-success save-notifications">حفظ وإنهاء</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إنهاء المدخلات</button>
      </div>
    </div>
  </div>
</div>



 <!-- ✅ نافذة سحب دعوى بشكل Bootstrap Modal -->
<div class="modal fade" id="withdrawCaseModal" tabindex="-1" aria-labelledby="withdrawCaseLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="margin-top: 80px;">
    <div class="modal-content">
      
      <!-- ✅ رأس النافذة -->
      <div class="modal-header">
        <h5 class="modal-title" id="withdrawCaseLabel">سحب دعوى</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>

      <!-- ✅ جسم النافذة -->
      <div class="modal-body">
        <form class="row g-3" id="withdraw-case-form">
          <!-- ✅ موقع المحكمة -->
          <div class="col-md-4">
            <label class="form-label">موقع المحكمة:</label>
            <select class="form-select form-select-sm" id="court-location">
              <option value="amman">عمان</option>
              <option value="nazaha">هيئة النزاهة و مكافحة الفساد</option>
            </select>
          </div>

          <!-- ✅ رقم الدعوى -->
          <div class="col-md-4">
            <label class="form-label">رقم الدعوى:</label>
            <input type="text" class="form-control form-control-sm" id="case-number" placeholder="أدخل رقم الدعوى">
          </div>

          <!-- ✅ المدعي العام -->
          <div class="col-md-4">
            <label class="form-label">المدعي العام:</label>
            <select class="form-select form-select-sm" id="public-prosecutor">
              <option value="">اختر</option>
              <option value="south">السجل العام/جنوب عمان</option>
              <option value="east">السجل العام/شرق عمان</option>
              <option value="north">السجل العام/شمال عمان</option>
            </select>
          </div>
        </form>
      </div>

      <!-- ✅ أزرار النافذة -->
      <div class="modal-footer d-flex justify-content-between">
        <button type="button" class="btn btn-primary btn-sm" onclick="submitWithdraw()">سحب</button>
        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">خروج</button>
      </div>

    </div>
  </div>
</div>





<!-- ✅ نافذة سحب قضية من الشرطة بشكل Bootstrap Modal -->
<!-- ✅ نافذة سحب قضية من الشرطة -->
<div class="modal fade" id="pullPoliceCaseModal" tabindex="-1" aria-labelledby="pullPoliceCaseLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="margin-top: 80px;">
    <div class="modal-content">

      <!-- ✅ رأس النافذة -->
      <div class="modal-header">
        <h5 class="modal-title" id="pullPoliceCaseLabel">سحب قضية من الشرطة</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>

      <!-- ✅ جسم النافذة -->
      <div class="modal-body">
        <form class="row g-3" id="pull-police-case-form">
          
          <!-- ✅ اختيار المركز الأمني مع زر بحث -->
          <div class="col-md-6 d-flex gap-2 align-items-end">
            <div class="w-100">
              <label class="form-label">المراكز الأمنية:</label>
              <select class="form-select form-select-sm" id="police-center">
                <option value="">اختر المركز الأمني</option>
                <option value="شرطة العاصمة">شرطة العاصمة</option>
                <option value="شرطة جنوب عمان">شرطة جنوب عمان</option>
                <option value="شرطة شمال عمان">شرطة شمال عمان</option>
                <option value="شرطة وسط عمان">شرطة وسط عمان</option>
                <option value="شرطة غرب عمان">شرطة غرب عمان</option>
                <option value="فرع أحداث شرق عمان">فرع أحداث شرق عمان</option>
                <option value="فرع أحداث وسط عمان">فرع أحداث وسط عمان</option>
                <option value="فرع شرق عمان حماية الأسرى">فرع شرق عمان حماية الأسرى</option>
                <option value="فرع وسط عمان حماية الأسرى">فرع وسط عمان حماية الأسرى</option>
              </select>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" id="search-police-cases">بحث</button>
          </div>

          <!-- ✅ جدول عرض القضايا -->
          <div class="col-12 mt-4">
            <h6>نتيجة البحث:</h6>
            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th>اختيار</th>
                  <th>المركز الأمني</th>
                  <th>رقم القضية لدى الأمن العام</th>
                  <th>تاريخ تسجيل القضية لدى الشرطة</th>
                  <th>تاريخ الجريمة</th>
                  <th>حالة القضية لدى الشرطة</th>
                </tr>
              </thead>
              <tbody id="police-case-results">
                <!-- يتم تعبئة الصفوف ديناميكياً -->
              </tbody>
            </table>
          </div>

        </form>
      </div>

      <!-- ✅ أزرار النافذة -->
      <div class="modal-footer d-flex justify-content-between">
        <button type="button" class="btn btn-primary btn-sm" onclick="submitPolicePull()">سحب</button>
        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">خروج</button>
      </div>

    </div>
  </div>
</div>



<!-- ✅ نافذة مذكرة توقيف -->
<div class="modal fade" id="arrestMemoModal" tabindex="-1" aria-labelledby="arrestMemoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="margin-top: 80px;">
    <div class="modal-content">

      <!-- ✅ رأس النافذة -->
      <div class="modal-header">
        <h5 class="modal-title" id="arrestMemoLabel">مذكرة توقيف</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>

      <!-- ✅ جسم النافذة -->
      <div class="modal-body">
        <form class="row g-3" id="arrest-memo-form">

          <!-- ✅ إدخال رقم الدعوى فقط -->
          <div class="col-md-3">
            <label class="form-label">رقم الدعوى:</label>
            <input type="text" class="form-control form-control-sm" name="case_number" id="case_number">
          </div>

          <!-- ✅ معلومات القضية تظهر تلقائيًا -->
          <div class="col-md-3">
            <label class="form-label">المحكمة:</label>
            <input type="text" class="form-control form-control-sm" name="court_name" id="court_name" readonly>
          </div>
          <div class="col-md-3">
            <label class="form-label">القلم:</label>
            <input type="text" class="form-control form-control-sm" name="pen_name" id="pen_name" readonly>
          </div>
          <div class="col-md-3">
            <label class="form-label">السنة:</label>
            <input type="text" class="form-control form-control-sm" name="case_year" id="case_year" readonly>
          </div>
          <div class="col-12">
            <label class="form-label">نوع الدعوى:</label>
            <input type="text" class="form-control form-control-sm" name="case_type" id="case_type" readonly>
          </div>

          <!-- ✅ جدول الأطراف -->
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th>اختيار</th>
                <th>الاسم</th>
                <th>نوع الطرف</th>
                <th>الوظيفة</th>
                <th>مكان الإقامة</th>
                <th>رقم الهاتف</th>
                <th>التبليغ بواسطة</th>
                <th>إجراء التبليغ</th>
              </tr>
            </thead>
            <tbody id="arrest-parties-table">
              <!-- يتم تعبئة الصفوف تلقائيًا عبر JavaScript -->
            </tbody>
          </table>

          <!-- ✅ اسم القاضي -->
          <div class="col-md-6">
            <label class="form-label">اسم القاضي:</label>
            <input type="text" class="form-control form-control-sm" name="judge_name" id="judge_name" readonly>
          </div>

          <!-- ✅ مدة التوقيف -->
          <div class="col-md-6">
            <label class="form-label">مدة التوقيف (أيام):</label>
            <input type="number" class="form-control form-control-sm" name="detention_days" min="1">
          </div>

          <!-- ✅ سبب التوقيف -->
          <div class="col-12">
            <label class="form-label">سبب التوقيف:</label>
            <select class="form-select form-select-sm" name="detention_reason">
              <option value="">اختر السبب</option>
              <option value="فرار">منع المشتكى عليه من الفرار</option>
              <option value="اتصال">منع المشتكى عليه من إجراء اتصال بشركائه في الجريمة</option>
              <option value="مختبرات">انتظار نتائج المختبرات الجنائية</option>
            </select>
          </div>

          <!-- ✅ مركز الإصلاح والتأهيل -->
          <div class="col-12">
            <label class="form-label">مركز الإصلاح والتأهيل:</label>
            <select class="form-select form-select-sm" name="detention_center">
              <option value="">اختر المركز</option>
              <option value="إربد">مركز إصلاح و تأهيل إربد</option>
              <option value="ماركا">مركز إصلاح و تأهيل ماركا</option>
              <option value="الكرك">مركز إصلاح و تأهيل الكرك</option>
            </select>
          </div>

        </form>
      </div>

      <!-- ✅ أزرار النافذة -->
      <div class="modal-footer d-flex justify-content-between">
        <button type="button" class="btn btn-secondary btn-sm">بصمة القاضي</button>
        <div>
          <button type="button" class="btn btn-success btn-sm" onclick="saveArrestMemo()">حفظ</button>
          <button type="button" class="btn btn-primary btn-sm" onclick="saveArrestMemo()">حفظ وإنهاء</button>
        </div>
      </div>

    </div>
  </div>
</div>






<!-- ✅ نافذة مذكرة تمديد توقيف -->
<div class="modal fade" id="extendArrestModal" tabindex="-1" aria-labelledby="extendArrestLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="margin-top: 80px;">
    <div class="modal-content">

      <!-- ✅ رأس النافذة -->
      <div class="modal-header">
        <h5 class="modal-title" id="extendArrestLabel">مذكرة تمديد توقيف</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>

      <!-- ✅ جسم النافذة -->
      <div class="modal-body">
        <form class="row g-3" id="extend-arrest-form">

          <!-- ✅ إدخال رقم الدعوى فقط -->
          <div class="col-md-3">
            <label class="form-label">رقم الدعوى:</label>
            <input type="text" class="form-control form-control-sm" name="case_number" id="extend_case_number">
          </div>

          <!-- ✅ معلومات القضية تظهر تلقائيًا -->
          <div class="col-md-3">
            <label class="form-label">المحكمة:</label>
            <input type="text" class="form-control form-control-sm" name="court_name" id="extend_court_name" readonly>
          </div>
          <div class="col-md-3">
            <label class="form-label">القلم:</label>
            <input type="text" class="form-control form-control-sm" name="pen_name" id="extend_pen_name" readonly>
          </div>
          <div class="col-md-3">
            <label class="form-label">السنة:</label>
            <input type="text" class="form-control form-control-sm" name="case_year" id="extend_case_year" readonly>
          </div>
          <div class="col-12">
            <label class="form-label">نوع الدعوى:</label>
            <input type="text" class="form-control form-control-sm" name="case_type" id="extend_case_type" readonly>
          </div>

          <!-- ✅ جدول الأطراف -->
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th>اختيار</th>
                <th>الاسم</th>
                <th>نوع الطرف</th>
                <th>الوظيفة</th>
                <th>مكان الإقامة</th>
                <th>رقم الهاتف</th>
                <th>التبليغ بواسطة</th>
                <th>إجراء التبليغ</th>
              </tr>
            </thead>
            <tbody id="extend-arrest-parties-table">
              <!-- يتم تعبئة الصفوف تلقائيًا عبر JavaScript -->
            </tbody>
          </table>

          <!-- ✅ اسم القاضي -->
          <div class="col-md-6">
            <label class="form-label">اسم القاضي:</label>
            <input type="text" class="form-control form-control-sm" name="judge_name" id="extend_judge_name" readonly>
          </div>

          <!-- ✅ تمديد التوقيف -->
          <div class="col-md-6">
            <label class="form-label">تمديد التوقيف (أيام):</label>
            <input type="number" class="form-control form-control-sm" name="detention_days" id="extend_detention_days" min="1">
          </div>

          <!-- ✅ سبب التوقيف -->
          <div class="col-12">
            <label class="form-label">سبب التوقيف:</label>
            <select class="form-select form-select-sm" name="detention_reason" id="extend_detention_reason">
              <option value="">اختر السبب</option>
              <option value="فرار">منع المشتكى عليه من الفرار</option>
              <option value="اتصال">منع المشتكى عليه من إجراء اتصال بشركائه في الجريمة</option>
              <option value="مختبرات">انتظار نتائج المختبرات الجنائية</option>
            </select>
          </div>

          <!-- ✅ مركز الإصلاح والتأهيل -->
          <div class="col-12">
            <label class="form-label">مركز الإصلاح والتأهيل:</label>
            <select class="form-select form-select-sm" name="detention_center" id="extend_detention_center">
              <option value="">اختر المركز</option>
              <option value="إربد">مركز إصلاح و تأهيل إربد</option>
              <option value="ماركا">مركز إصلاح و تأهيل ماركا</option>
              <option value="الكرك">مركز إصلاح و تأهيل الكرك</option>
            </select>
          </div>

        </form>
      </div>

      <!-- ✅ أزرار النافذة -->
      <div class="modal-footer d-flex justify-content-between">
        <button type="button" class="btn btn-secondary btn-sm">بصمة القاضي</button>
        <div>
          <button type="button" class="btn btn-success btn-sm" onclick="saveExtendArrestMemo()">حفظ</button>
          <button type="button" class="btn btn-primary btn-sm" onclick="saveExtendArrestMemo()">حفظ وإنهاء</button>
        </div>
      </div>

    </div>
  </div>
</div>



<!--   الجلسات    -->
<div id="sessions-menu" style="display: none; position: absolute; background-color: white; border: 1px solid #ccc; min-width: 200px; box-shadow: 0 2px 6px rgba(0,0,0,0.2); z-index: 1000;">
  <ul style="list-style: none; margin: 0; padding: 0;">
    <li><a href="{{ route('writer.dashboard', ['type' => 'today']) }}" style="display: block; padding: 10px;">📅 جلسات اليوم</a></li>
    <li><a href="{{ route('writer.dashboard', ['type' => 'upcoming']) }}" style="display: block; padding: 10px;">⏳ الجلسات القادمة</a></li>
    <li><a href="{{ route('writer.dashboard', ['type' => 'finished']) }}" style="display: block; padding: 10px;">✅ الجلسات المنتهية</a></li>
    <li><a href="{{ route('writer.dashboard', ['type' => 'postponed']) }}" style="display: block; padding: 10px;">🕒 الجلسات المؤجلة</a></li>
    <li><a href="{{ route('writer.dashboard', ['type' => 'no_decision']) }}" style="display: block; padding: 10px;">🚫 جلسات بدون قرار</a></li>
    <li><a href="{{ route('writer.dashboard', ['type' => 'needs_action']) }}" style="display: block; padding: 10px;">⚠️ جلسات تحتاج إجراء</a></li>
  </ul>
</div>


<!-- المشاركين  -->
<div class="modal fade" id="participantsModal" tabindex="-1" aria-labelledby="participantsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" style="margin-top: 80px;">
    <div class="modal-content">

      <!-- ✅ رأس النافذة -->
      <div class="modal-header">
        <h5 class="modal-title" id="participantsModalLabel"> بيانات المشاركين</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>

      <!-- ✅ جسم النافذة -->
      <div class="modal-body">
        <h6 class="mb-3">معلومات الشخص المشترك:</h6>

        <form method="GET" action="{{ route('participants.search') }}" class="row g-3">
          <!-- الاسم الكامل -->
          <div class="col-md-2">
            <label class="form-label">الاسم الأول:</label>
            <input type="text" name="first_name" class="form-control form-control-sm">
          </div>
          <div class="col-md-2">
            <label class="form-label">اسم الأب:</label>
            <input type="text" name="father_name" class="form-control form-control-sm">
          </div>
          <div class="col-md-2">
            <label class="form-label">اسم الأم:</label>
            <input type="text" name="mother_name" class="form-control form-control-sm">
          </div>
          <div class="col-md-2">
            <label class="form-label">اسم الجد:</label>
            <input type="text" name="grandfather_name" class="form-control form-control-sm">
          </div>
          <div class="col-md-2">
            <label class="form-label">اسم العائلة:</label>
            <input type="text" name="family_name" class="form-control form-control-sm">
          </div>

          <!-- معلومات إضافية -->
          <div class="col-md-4">
            <label class="form-label">المهنة:</label>
            <input type="text" name="occupation" class="form-control form-control-sm">
          </div>
          <div class="col-md-4">
            <label class="form-label">الجنسية:</label>
            <input type="text" name="nationality" class="form-control form-control-sm">
          </div>
          <div class="col-md-4">
            <label class="form-label">تاريخ الميلاد:</label>
            <input type="date" name="birth_date" class="form-control form-control-sm">
          </div>

          <!-- زر البحث -->
          <div class="col-12 mt-3">
            <button type="submit" class="btn btn-outline-primary btn-sm">🔍 بحث الأحوال المدنية</button>
          </div>
        </form>

        <!-- ✅ جدول النتائج -->
        <div class="col-12 mt-4">
          <h6>نتائج البحث:</h6>
          <table class="table table-bordered table-sm">
            <thead class="table-light">
              <tr>
                <th>الرقم الوطني</th>
                <th>الاسم الكامل</th>
                <th>الأب</th>
                <th>الأم</th>
                <th>الجد</th>
                <th>العائلة</th>
                <th>تاريخ الميلاد</th>
                <th>العمر</th>
                <th>الجنس</th>
                <th>المهنة</th>
                <th>الجنسية</th>
                <th>مكان السجل</th>
              </tr>
            </thead>
            <tbody>
              @forelse($results as $person)
                <tr>
                  <td>{{ $person->national_id }}</td>
                  <td>{{ $person->full_name }}</td>
                  <td>{{ $person->father_name }}</td>
                  <td>{{ $person->mother_name }}</td>
                  <td>{{ $person->grandfather_name }}</td>
                  <td>{{ $person->family_name }}</td>
                  <td>{{ $person->birth_date }}</td>
                  <td>{{ $person->age }}</td>
                  <td>{{ $person->gender }}</td>
                  <td>{{ $person->occupation }}</td>
                  <td>{{ $person->nationality }}</td>
                  <td>{{ $person->record_location }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="12" class="text-center">لا توجد نتائج مطابقة</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- ✅ أزرار النافذة -->
      <div class="modal-footer d-flex justify-content-between">
        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">خروج</button>
      </div>

    </div>
  </div>
</div>





<!-- ✅ نافذة مذكرة الإفراج عن الموقوفين -->
<div class="modal fade" id="releaseMemoModal" tabindex="-1" aria-labelledby="releaseMemoLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" style="margin-top: 80px;">
    <div class="modal-content">

      <!-- ✅ رأس النافذة -->
      <div class="modal-header">
        <h5 class="modal-title" id="releaseMemoLabel">مذكرة الإفراج عن الموقوفين</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>

      <!-- ✅ جسم النافذة -->
      <div class="modal-body">
        <form class="row g-3" id="release-memo-form">
          
          <!-- ✅ معلومات المحكمة والقلم والسنة -->
          <div class="col-md-4">
            <label class="form-label">رقم المحكمة:</label>
            <input type="text" class="form-control form-control-sm" id="tribunal-number" readonly>
          </div>

          <div class="col-md-4">
            <label class="form-label">رقم القلم:</label>
            <input type="text" class="form-control form-control-sm" id="department-number" readonly>
          </div>

          <div class="col-md-4">
            <label class="form-label">السنة:</label>
            <input type="text" class="form-control form-control-sm" id="case-year" readonly>
          </div>

          <!-- ✅ إدخال رقم الدعوى -->
          <div class="col-md-6">
            <label class="form-label">رقم الدعوى:</label>
            <input type="text" class="form-control form-control-sm" id="case-id" placeholder="أدخل رقم الدعوى">
          </div>

          <div class="col-md-6 d-flex align-items-end">
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="fetchCaseParticipants()">عرض الأطراف</button>
          </div>

          <!-- ✅ نوع الدعوى واسم القاضي -->
          <div class="col-md-6">
            <label class="form-label">نوع الدعوى:</label>
            <input type="text" class="form-control form-control-sm" id="case-type" readonly>
          </div>

          <div class="col-md-6">
            <label class="form-label">اسم القاضي:</label>
            <input type="text" class="form-control form-control-sm" id="judge-name" readonly>
          </div>

          <!-- ✅ جدول المشاركين -->
          <div class="col-12">
            <table class="table table-bordered table-sm mt-3">
              <thead class="table-dark">
                <tr>
                  <th>اسم الطرف</th>
                  <th>نوع الطرف</th>
                  <th>التهمة</th>
                </tr>
              </thead>
              <tbody id="participants-table-body">
                <!-- يتم تعبئة الصفوف ديناميكيًا -->
              </tbody>
            </table>
          </div>

        </form>
      </div>

      <!-- ✅ أزرار النافذة -->
      <div class="modal-footer d-flex justify-content-between">
        <button type="button" class="btn btn-success btn-sm" onclick="submitReleaseMemo()">إفراج عن الموقوفين</button>
        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">خروج</button>
      </div>

    </div>
  </div>
</div>
















<!-- ✅ نافذة الجلسات داخل Bootstrap Modal -->
<!-- ✅ نافذة الجلسات داخل Bootstrap Modal -->
  @php
    $title = $title ?? 'الجلسات';
    $courtNumber = $courtNumber ?? '-';
    $departmentNumber = $departmentNumber ?? '-';
    $currentYear = $currentYear ?? now()->year;
  @endphp

  <div class="modal fade" id="sessionsModal" tabindex="-1" aria-labelledby="sessionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="margin-top: 80px;">
      <div class="modal-content">

        <!-- ✅ رأس النافذة -->
        <div class="modal-header">
          <h5 class="modal-title" id="sessionsModalLabel">{{ $title }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
        </div>

        <!-- ✅ جسم النافذة -->
        <div class="modal-body">
          <form method="GET" action="{{ route('writer.dashboard') }}" class="row g-3" id="sessionSearchForm">
            <div class="col-md-4">
              <label class="form-label">📌 رقم المحكمة:</label>
              <input type="text" class="form-control form-control-sm" value="{{ $courtNumber }}" disabled>
            </div>
            <div class="col-md-4">
              <label class="form-label">🖋️ رقم القلم:</label>
              <input type="text" class="form-control form-control-sm" value="{{ $departmentNumber }}" disabled>
            </div>
            <div class="col-md-4">
              <label class="form-label">📅 السنة:</label>
              <input type="text" class="form-control form-control-sm" value="{{ $currentYear }}" disabled>
            </div>

            <div class="col-md-6 d-flex gap-2 align-items-end mt-2">
              <div class="w-100">
                <label class="form-label">رقم الدعوى:</label>
                <input type="text" name="court_case_id" class="form-control form-control-sm" placeholder="🔍 رقم الدعوى">
                <input type="hidden" name="type" value="{{ request('type') }}">
              </div>
              <button type="submit" class="btn btn-outline-primary btn-sm">بحث</button>
            </div>

            <div class="col-12 mt-4">
              <h6>نتائج الجلسات:</h6>
              <table class="table table-bordered table-sm">
                <thead class="table-light">
                  <tr>
                    <th>رقم الدعوى</th>
                    <th>تاريخ الجلسة</th>
                    <th>وقت الجلسة</th>
                    <th>نوع الجلسة</th>
                    <th>القاضي</th>
                    <th>الحالة</th>
                    <th>نوع الحكم</th>
                    <th>الإجراء</th>
                    <th>المحضر</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($sessions as $session)
                    <tr>
                      ,<td>{{ $session->case_number ?? '-' }}</td>
                      <td>{{ $session->session_date }}</td>
                      <td>{{ $session->session_time ?? '-' }}</td>
                      <td>{{ $session->session_type ?? '-' }}</td>
                      <td>{{ $session->judge_name ?? '-' }}</td>
                      <td>{{ $session->status ?? '-' }}</td>
                      <td>{{ $session->judgment_type ?? '-' }}</td>
                      <td>
                        @if($session->action_done)
                          ✅ تم
                        @else
                          <span class="text-danger">⚠️ لم يتم</span>
                        @endif
                      </td>
                      <td>
                        <button type="button" class="btn btn-sm btn-secondary" disabled>عرض المحضر</button>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="9" class="text-center">لا توجد جلسات مطابقة</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </form>
        </div>

        <!-- ✅ أزرار النافذة -->
        <div class="modal-footer d-flex justify-content-between">
          <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">خروج</button>
        </div>

      </div>
    </div>
  </div>


















<script>
document.addEventListener('DOMContentLoaded', function () {
  const caseOptions = document.getElementById('case-options');
  const caseTrigger = document.getElementById('trigger-cases');
  const openRegisterCase = document.getElementById('open-register-case');
  const caseType = document.getElementById('caseType');
  const caseNumber = document.getElementById('caseNumber');
  const caseYear = document.getElementById('caseYear');
  const sessionDate = document.getElementById('session_date');
  const judgeId = document.getElementById('judge_id');
  const judgeName = document.getElementById('judge_name');
  const saveButton = document.getElementById('saveAndFinish');
  const courtInput = document.getElementById('courtNumber');
  const deptInput = document.getElementById('departmentNumber');

  const trigger = document.getElementById('trigger-notifications');
  const menu = document.getElementById('notifications-menu');
  const subToggle = document.getElementById('sub-notifications-toggle');
  const subMenu = document.getElementById('sub-notifications-menu');
  const subArea = menu; // ✅ تعديل مهم: استخدام menu بدل عنصر غير موجود

  let hideTimeout;
  let subMenuTimeout;
  let currentCaseData = null;

  // ✅ دالة تحويل الأرقام
  const convertToArabic = (num) => {
    const western = ['0','1','2','3','4','5','6','7','8','9'];
    const arabic  = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
    return num.toString().split('').map(d => arabic[western.indexOf(d)] ?? d).join('');
  };

  // ✅ تحويل رقم المحكمة والقلم
  if (courtInput) courtInput.value = convertToArabic(courtInput.value);
  if (deptInput) deptInput.value = convertToArabic(deptInput.value);

  // ✅ عرض قائمة القضايا عند المرور
  if (caseTrigger && caseOptions) {
    caseTrigger.addEventListener('mouseenter', () => {
      const rect = caseTrigger.getBoundingClientRect();
      caseOptions.style.top = rect.bottom + 'px';
      caseOptions.style.right = (window.innerWidth - rect.right) + 'px';
      caseOptions.style.display = 'block';
    });

    caseTrigger.addEventListener('mouseleave', () => {
      hideTimeout = setTimeout(() => {
        caseOptions.style.display = 'none';
      }, 500);
    });

    caseOptions.addEventListener('mouseenter', () => {
      clearTimeout(hideTimeout);
      caseOptions.style.display = 'block';
    });

    caseOptions.addEventListener('mouseleave', () => {
      caseOptions.style.display = 'none';
    });
  }

  // ✅ فتح نافذة تسجيل الدعوى
  if (openRegisterCase) {
    openRegisterCase.addEventListener('click', () => {
      const modal = new bootstrap.Modal(document.getElementById('registerCaseModal'));
      modal.show();
    });
  }

  // ✅ توليد رقم الدعوى فقط عند Enter في caseNumber
  if (caseNumber) {
    caseNumber.addEventListener('keydown', async (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();

        const typeValue = caseType.value;
        if (!typeValue) {
          alert('❗ يرجى اختيار نوع الدعوى أولاً');
          return;
        }

        try {
          const response = await fetch('/court-cases/store', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ type: typeValue })
          });

          if (!response.ok) throw new Error('فشل في توليد رقم القضية');

          const data = await response.json();
          currentCaseData = data;

          caseNumber.value = convertToArabic(data.number); // فقط الرقم
        } catch (error) {
          console.error('❌ خطأ في توليد رقم الدعوى:', error);
          alert('حدث خطأ أثناء توليد رقم الدعوى: ' + error.message);
        }
      }
    });
  }


  // ✅ توليد السنة والقاضي والجلسة عند Enter في caseYear
  if (caseYear) {
    caseYear.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();

        if (!currentCaseData) {
          alert('❗ يرجى توليد رقم الدعوى أولاً');
          return;
        }

        caseYear.value = convertToArabic(currentCaseData.year);
        sessionDate.value = currentCaseData.session_date;
        judgeId.value = currentCaseData.judge_id;
        judgeName.value = currentCaseData.judge_name;
      }
    });
  }

  // ✅ زر حفظ وإنهاء
  if (saveButton) {
    saveButton.addEventListener('click', async () => {
      try {
        const getValue = (id) => {
          const el = document.getElementById(id);
          if (!el) throw new Error(`العنصر ${id} غير موجود`);
          return el.value;
        };

        const caseId = currentCaseData?.id;
        if (!caseId) throw new Error('رقم القضية غير موجود');

        const participantResponse = await fetch('/participants/store', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            court_case_id: caseId,
            type: getValue('partyType'),
            name: getValue('partyName'),
            national_id: getValue('nationalId'),
            residence: getValue('residence'),
            job: getValue('job'),
            phone: getValue('phone')
          })
        });

        const participantData = await participantResponse.json();
        alert('✅ تم الحفظ بنجاح');
      } catch (error) {
        console.error('❌ خطأ أثناء الحفظ:', error);
        alert('حدث خطأ أثناء الحفظ: ' + error.message);
      }
    });
  }


  


//التباليغ
 if (trigger && menu && subToggle && subMenu && subArea) {
  trigger.addEventListener('mouseenter', () => {
    menu.style.display = 'block';
  });

  menu.addEventListener('mouseleave', () => {
    menu.style.display = 'none';
    subMenu.style.display = 'none';
    clearTimeout(subMenuTimeout);
  });

  subToggle.addEventListener('mouseenter', () => {
    subMenuTimeout = setTimeout(() => {
      subMenu.style.display = 'block';
    }, 400);
  });

  subArea.addEventListener('mouseleave', () => {
    subMenu.style.display = 'none';
    clearTimeout(subMenuTimeout);
  });
}

document.querySelectorAll('.open-notification-modal').forEach(item => {
  item.addEventListener('click', async () => {
    const type = item.getAttribute('data-type');
    const isVerdictMemo = type.includes('تبليغ حكم');
    const modal = new bootstrap.Modal(document.getElementById('notificationModal'));
    document.getElementById('notification-title').textContent = type;
    modal.show();

    // ✅ تنظيف الحقول السابقة
    document.getElementById('notificationCaseNumber').value = '';
    document.getElementById('caseTypeDisplay').value = '';
    document.getElementById('courtDisplay').value = '';
    document.getElementById('deptDisplay').value = '';
    document.getElementById('yearDisplay').value = '';
    document.querySelector('#participantsTable tbody').innerHTML = '';

    // ✅ إظهار أو إخفاء الحكم النهائي
    const finalVerdictBox = document.getElementById('finalVerdictBox');
    if (finalVerdictBox) {
      finalVerdictBox.style.display = isVerdictMemo ? 'block' : 'none';
      finalVerdictBox.querySelector('#finalVerdictText').textContent = 'سيتم جلب الحكم لاحقًا...';
    }

    // ✅ إدخال رقم الدعوى وجلب نوعها والأطراف
    const caseInput = document.getElementById('notificationCaseNumber');
    if (caseInput) {
      caseInput.onkeydown = async function (e) {
        if (e.key === 'Enter') {
          const caseNumber = this.value.trim();
          if (!caseNumber) return;

          try {
            const response = await fetch(`/court-cases/${caseNumber}`);
            const data = await response.json();
            console.log('📦 المشاركون:', data.participants);

            // ✅ تعبئة بيانات القضية
            document.getElementById('caseTypeDisplay').value = data.type || '';
            document.getElementById('courtDisplay').value = data.tribunal?.number || '';
            document.getElementById('deptDisplay').value = data.department?.number || '';
            document.getElementById('yearDisplay').value = data.year || '';

            // ✅ الأطراف حسب نوع المذكرة
            const tbody = document.querySelector('#participantsTable tbody');
            tbody.innerHTML = '';

            let targetType = null;
            if (type.includes('مشتكي عليه')) {
              targetType = 'مشتكى عليه';
            } else if (type.includes('مشتكي')) {
              targetType = 'مشتكي';
            } else if (type.includes('شهود')) {
              targetType = 'شاهد';
            }

            const filtered = targetType ? data.participants.filter(p => p.type === targetType) : data.participants;

            if (filtered.length === 0) {
              tbody.innerHTML = `<tr><td colspan="10" class="text-center text-danger">لا يوجد ${targetType || 'أطراف'} في هذه الدعوى</td></tr>`;
            } else {
              filtered.forEach(part => {
                const row = document.createElement('tr');
                row.innerHTML = `
                  <td><input type="checkbox" class="participant-select"></td>
                  <td>${part.name}</td>
                  <td>${part.national_id}</td>
                  <td>${part.type}</td>
                  <td>${part.job}</td>
                  <td>${part.residence}</td>
                  <td>${part.phone}</td>

                  <!-- ✅ قسم التباليغ -->
                  <td>
                    <div class="d-flex flex-column align-items-start">
                      <label class="fw-bold mb-1">قسم التباليغ</label>
                      <button class="btn btn-sm btn-outline-success">✔️</button>
                    </div>
                  </td>

                  <!-- ✅ تبليغ إلكتروني -->
                  <td>
                    <div class="d-flex flex-column align-items-start">
                      <label class="fw-bold mb-1">تبليغ إلكتروني</label>
                      <select class="form-select form-select-sm w-auto">
                        <option selected disabled>اختر</option>
                        <option value="sms">رسالة قصيرة</option>
                        <option value="email">تبليغ إلكتروني</option>
                      </select>
                    </div>
                  </td>
                `;
                tbody.appendChild(row);

                // ✅ تفعيل زر قسم التباليغ عند الضغط
                const notifyBtn = row.querySelector('.btn-outline-success');
                notifyBtn.addEventListener('click', () => {
                  const allBtns = row.querySelectorAll('.btn-outline-success, .btn-success');
                  allBtns.forEach(b => {
                    b.classList.remove('btn-success');
                    b.classList.add('btn-outline-success');
                    b.textContent = '✔️';
                  });

                  notifyBtn.classList.remove('btn-outline-success');
                  notifyBtn.classList.add('btn-success');
                  notifyBtn.textContent = 'تم اختيار القسم';
                });
              });
            }

            // ✅ زر حفظ أو حفظ وإنهاء يخزن التبليغات دفعة واحدة
            document.querySelectorAll('.save-notifications').forEach(button => {
              button.addEventListener('click', async () => {
                const caseId = document.getElementById('notificationCaseNumber').value.trim();
                const rows = document.querySelectorAll('#participantsTable tbody tr');

                if (!caseId) {
                  alert('يرجى إدخال رقم القضية أولاً.');
                  return;
                }

                let savedCount = 0;

                for (const row of rows) {
                  const name = row.querySelector('td:nth-child(2)').textContent.trim();
                  const methodSelect = row.querySelector('select');
                  const notifyBtn = row.querySelector('.btn-success');

                  let method = null;

                  if (methodSelect && methodSelect.value && methodSelect.value !== 'اختر') {
                    method = methodSelect.value;
                  }

                  if (notifyBtn) {
                    method = 'قسم التباليغ';
                  }

                  if (!method || !name) continue;

                  try {
                    await fetch('/notifications/save', {
                      method: 'POST',
                      headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                      },
                      body: JSON.stringify({
                        case_id: caseId,
                        participant_name: name,
                        method: method
                      })
                    });

                    savedCount++;
                  } catch (err) {
                    console.error('❌ فشل في إرسال التبليغ:', err);
                  }
                }

                alert(`✅ تم حفظ ${savedCount} تبليغ بنجاح.`);
              });
            });

          } catch (err) {
            console.error('❌ خطأ في جلب بيانات القضية:', err);
          }
        }
      };
    }
  });
});


document.addEventListener('showNotificationsMenu', () => {
  const menu = document.getElementById('notifications-menu');
  if (menu) {
    menu.style.display = 'block';
  }

  // إغلاق القائمة عند مغادرة الماوس
  menu.addEventListener('mouseleave', () => {
    menu.style.display = 'none';
    document.getElementById('security-submenu').style.display = 'none';
    document.getElementById('notifications-submenu').style.display = 'none';
  });

  // ✅ كتب مخاطبات الأمن العام
  const securityToggle = document.getElementById('security-toggle');
  const securitySubmenu = document.getElementById('security-submenu');

  if (securityToggle && securitySubmenu) {
    securityToggle.addEventListener('mouseenter', () => {
      securitySubmenu.style.display = 'block';
    });

    securityToggle.addEventListener('mouseleave', () => {
      setTimeout(() => {
        securitySubmenu.style.display = 'none';
      }, 300);
    });

    securitySubmenu.addEventListener('mouseenter', () => {
      securitySubmenu.style.display = 'block';
    });

    securitySubmenu.addEventListener('mouseleave', () => {
      securitySubmenu.style.display = 'none';
    });
  }

  // ✅ تباليغ الدعوى
  const notificationsToggle = document.getElementById('notifications-toggle');
  const notificationsSubmenu = document.getElementById('notifications-submenu');

  if (notificationsToggle && notificationsSubmenu) {
    notificationsToggle.addEventListener('mouseenter', () => {
      notificationsSubmenu.style.display = 'block';
    });

    notificationsToggle.addEventListener('mouseleave', () => {
      setTimeout(() => {
        notificationsSubmenu.style.display = 'none';
      }, 300);
    });

    notificationsSubmenu.addEventListener('mouseenter', () => {
      notificationsSubmenu.style.display = 'block';
    });

    notificationsSubmenu.addEventListener('mouseleave', () => {
      notificationsSubmenu.style.display = 'none';
    });
  }
});



let securityHideTimeout;

securityToggle.addEventListener('mouseenter', () => {
  clearTimeout(securityHideTimeout); // نلغي أي مؤقت إخفاء
  securitySubmenu.style.display = 'block'; // تظهر فورًا
});

securityToggle.addEventListener('mouseleave', () => {
  securityHideTimeout = setTimeout(() => {
    securitySubmenu.style.display = 'none';
  }, 800); // ← تأخير الإخفاء (مثلاً 800ms)
});

securitySubmenu.addEventListener('mouseenter', () => {
  clearTimeout(securityHideTimeout); // نلغي الإخفاء لو دخل المستخدم
  securitySubmenu.style.display = 'block';
});

securitySubmenu.addEventListener('mouseleave', () => {
  securityHideTimeout = setTimeout(() => {
    securitySubmenu.style.display = 'none';
  }, 800); // ← نفس التأخير عند مغادرة القائمة نفسها
});


});
</script>






<script>
function submitWithdraw() {
  const caseNumber = document.getElementById("case-number").value.trim();
  const courtLocation = document.getElementById("court-location").value.trim();
  const prosecutorOffice = document.getElementById("public-prosecutor").value.trim();

  if (!caseNumber || !courtLocation || !prosecutorOffice) {
    alert("يرجى تعبئة جميع الحقول قبل السحب");
    return;
  }

  fetch("/cases/pull", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
      "Accept": "application/json"
    },
    body: JSON.stringify({
      case_number: caseNumber,
      court_location: courtLocation,
      prosecutor_office: prosecutorOffice
    })
  })
  .then(async response => {
    const data = await response.json();
    if (response.ok) {
      document.activeElement.blur();
      const modalElement = document.getElementById("withdrawCaseModal");
      const modalInstance = bootstrap.Modal.getInstance(modalElement);
      modalInstance.hide();
      alert(data.message || "تم السحب بنجاح");
      location.reload();
    } else {
      alert(data.error || "حدث خطأ أثناء السحب");
    }
  })
  .catch(error => {
    console.error("Fetch error:", error);
    alert("تعذر الاتصال بالخادم. يرجى المحاولة لاحقًا.");
  });
}
</script>

























<script>
document.addEventListener('DOMContentLoaded', function () {
  const searchBtn = document.getElementById('search-police-cases');
  const centerSelect = document.getElementById('police-center');
  const resultsBody = document.getElementById('police-case-results');

  // ✅ تنفيذ البحث عند الضغط على زر "بحث"
  searchBtn.addEventListener('click', function () {
    const center = centerSelect.value;
    if (!center) {
      alert('يرجى اختيار مركز أمني أولاً');
      return;
    }

    // ✅ ترميز الاسم للتعامل مع الرموز مثل /
    const encodedCenter = encodeURIComponent(center);

    axios.get(`/police-cases/by-center/${encodedCenter}`)
      .then(response => {
        resultsBody.innerHTML = '';

        if (response.data.length === 0) {
          resultsBody.innerHTML = `<tr><td colspan="6" class="text-center">لا توجد قضايا لهذا المركز</td></tr>`;
          return;
        }

        response.data.forEach(caseItem => {
          const row = document.createElement('tr');
          row.innerHTML = `
            <td><input type="radio" name="selected_case" value="${caseItem.id}"></td>
            <td>${caseItem.center_name}</td>
            <td>${caseItem.police_case_number}</td>
            <td>${caseItem.police_registration_date}</td>
            <td>${caseItem.crime_date}</td>
            <td>${caseItem.status || 'غير محدد'}</td>
          `;
          resultsBody.appendChild(row);
        });
      })
      .catch(error => {
        alert('فشل في جلب القضايا');
        console.error('❌ خطأ في جلب القضايا:', error);
      });
  });

  // ✅ تنفيذ السحب عند الضغط على زر "سحب"
  window.submitPolicePull = function () {
    const selected = document.querySelector('input[name="selected_case"]:checked');
    if (!selected) {
      alert('يرجى اختيار قضية أولاً');
      return;
    }

    const caseId = selected.value;

    axios.post(`/writer/pull-police-case/${caseId}`)
      .then(response => {
        alert(response.data.message);

        // ✅ إغلاق النافذة بعد السحب
        const modal = bootstrap.Modal.getInstance(document.getElementById('pullPoliceCaseModal'));
        if (modal) modal.hide();

        // ✅ إعادة تحميل القضايا بعد السحب
        searchBtn.click();
      })
      .catch(error => {
        alert('فشل في سحب القضية');
        console.error('❌ خطأ في سحب القضية:', error);
      });
  };
});
</script>
















<script>
document.addEventListener('DOMContentLoaded', function () {
  // ✅ عند إدخال رقم الدعوى
  document.querySelector('#case_number').addEventListener('change', function () {
    const caseNumber = this.value;

    axios.post('/writer/arrest-memo', {
      case_number: caseNumber
    })
    .then(response => {
      const caseData = response.data.case;

      // ✅ تعبئة معلومات القضية تلقائيًا من العلاقات
      document.querySelector('#court_name').value = response.data.tribunal_number ?? '---';
      document.querySelector('#pen_name').value = response.data.department_number ?? '---';
      document.querySelector('#case_year').value = caseData.year ?? '---';
      document.querySelector('#case_type').value = caseData.type ?? '---';
      document.querySelector('#judge_name').value = response.data.judge_name ?? '---';

      // ✅ تعبئة جدول الأطراف
      const table = document.querySelector('#arrest-parties-table');
      table.innerHTML = '';
      response.data.participants.forEach(p => {
        table.innerHTML += `
          <tr>
            <td><input type="radio" name="selected_party" value="${p.id}"></td>
            <td>${p.name}</td>
            <td>${p.type}</td>
            <td>${p.jod}</td>
            <td>${p.residence}</td>
            <td>${p.phone}</td>
            <td>الأمن العام</td>
            <td><button type="button" class="btn btn-sm btn-outline-primary">تبليغ الأمن العام</button></td>
          </tr>
        `;
      });
    })
    .catch(error => {
      alert('❌ القضية غير موجودة أو حدث خطأ في جلب البيانات');
    });
  });
});

// ✅ عند الضغط على زر "حفظ"
function saveArrestMemo() {
  const form = document.querySelector('#arrest-memo-form');

  const selectedParty = form.querySelector('input[name="selected_party"]:checked');
  if (!selectedParty) {
    alert('يرجى اختيار طرف من الجدول');
    return;
  }

  const payload = {
    case_number: form.case_number.value,
    judge_name: form.judge_name.value,
    detention_duration: form.detention_days.value,
    detention_reason: form.detention_reason.value,
    detention_center: form.detention_center.value,
    save: true
  };

  axios.post('/writer/arrest-memo', payload)
    .then(res => {
      alert('✅ ' + res.data.message);
      // ممكن تغلقي النافذة أو تعملي إعادة تحميل
    })
    .catch(err => {
      alert('❌ حدث خطأ أثناء الحفظ');
    });
}
</script>








<script>
  // ✅ عرض تمديد مدة التوقيف
// ✅ إدخال رقم الدعوى والضغط Enter → جلب التفاصيل من السيرفر
document.getElementById('extend_case_number').addEventListener('keydown', function (e) {
  if (e.key === 'Enter') {
    e.preventDefault();

    const rawValue = this.value.trim(); // مثال: 123/2023
    const [numberRaw, yearRaw] = rawValue.split('/');
    const number = numberRaw.trim().replace(/^0+/, '');
    const year = yearRaw.trim();

    fetch(`/writer/get-case-details?number=${number}&year=${year}`)
      .then(res => {
        if (!res.ok) throw new Error('القضية غير موجودة.');
        return res.json();
      })
      .then(data => {
        document.getElementById('extend_case_type').value = data.type;
        document.getElementById('extend_case_year').value = data.year;
        document.getElementById('extend_pen_name').value = data.department_name;
        document.getElementById('extend_court_name').value = data.tribunal_name;
        document.getElementById('extend_judge_name').value = data.judge_name;
        document.getElementById('extend-arrest-form').dataset.caseId = data.id;

        const tbody = document.getElementById('extend-arrest-parties-table');
        tbody.innerHTML = '';
        data.participants.forEach(p => {
          const row = document.createElement('tr');
          row.innerHTML = `
            <td><input type="checkbox" value="${p.id}"></td>
            <td>${p.name}</td>
            <td>${p.type}</td>
            <td>${p.job}</td>
            <td>${p.residence}</td>
            <td>${p.phone}</td>
            <td><input type="text" class="form-control form-control-sm"></td>
            <td><input type="text" class="form-control form-control-sm"></td>
          `;
          tbody.appendChild(row);
        });
      })
      .catch(err => {
        alert(err.message);
      });
  }
}

// ✅ حفظ مذكرة تمديد التوقيف
function saveExtendArrestMemo() {
  const form = document.getElementById('extend-arrest-form');
  const caseId = form.dataset.caseId;

  const judgeName = document.getElementById('extend_judge_name').value.trim();
  const detentionDays = document.getElementById('extend_detention_days').value.trim();
  const detentionReason = document.getElementById('extend_detention_reason').value;
  const detentionCenter = document.getElementById('extend_detention_center').value;

  if (!caseId || !detentionDays || !detentionReason || !detentionCenter) {
    alert('يرجى تعبئة جميع الحقول المطلوبة.');
    return;
  }

  const selectedParticipants = [];
  document.querySelectorAll('#extend-arrest-parties-table input[type="checkbox"]').forEach(cb => {
    if (cb.checked) {
      selectedParticipants.push({ id: cb.value });
    }
  });

  if (selectedParticipants.length === 0) {
    alert('يرجى اختيار طرف واحد على الأقل.');
    return;
  }

  const memoData = {
    case_id: caseId,
    judge_name: judgeName,
    detention_duration: detentionDays,
    detention_reason: detentionReason,
    detention_center: detentionCenter,
    participants: selectedParticipants
  };

  fetch('/writer/save-extend-arrest-memo', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(memoData)
  })
  .then(res => {
    if (!res.ok) throw new Error('فشل الحفظ');
    return res.json();
  })
  .then(() => {
    alert('تم حفظ مذكرة تمديد التوقيف بنجاح.');
    bootstrap.Modal.getInstance(document.getElementById('extendArrestModal')).hide();
    form.reset();
    document.getElementById('extend-arrest-parties-table').innerHTML = '';
  })
  .catch(err => {
    console.error(err);
    alert('حدث خطأ أثناء الحفظ.');
  });
}

)
</script>










<script>
//الجلسات
document.addEventListener('DOMContentLoaded', function () {
  const trigger = document.getElementById('sessions-trigger');
  const menu = document.getElementById('sessions-menu');

  if (trigger && menu) {
    // ✅ إظهار القائمة عند تمرير السهم
    trigger.addEventListener('mouseenter', function () {
      menu.style.display = 'block';
    });

    // ✅ إخفاء القائمة إذا خرج السهم من الزر والقائمة
    trigger.addEventListener('mouseleave', function () {
      setTimeout(() => {
        if (!menu.matches(':hover')) {
          menu.style.display = 'none';
        }
      }, 200); // تأخير بسيط لتسمح بالانتقال للقائمة
    });

    menu.addEventListener('mouseleave', function () {
      menu.style.display = 'none';
    });

    menu.addEventListener('mouseenter', function () {
      menu.style.display = 'block';
    });
  }
});

</script>


@if(request('type'))
  <script>
    window.addEventListener('load', function () {
      const modal = new bootstrap.Modal(document.getElementById('sessionsModal'));
      modal.show();
    });
  </script>
@endif







<script>
  document.addEventListener('DOMContentLoaded', function () {

    // ✅ تشغيل عند الضغط على Enter داخل حقل رقم الدعوى
    const caseInput = document.getElementById('case-id');
    caseInput.addEventListener('keypress', function (e) {
      if (e.which === 13 || e.key === 'Enter') {
        e.preventDefault();
        fetchCaseParticipants();
      }
    });

    // ✅ دالة جلب بيانات الدعوى والمشاركين
    window.fetchCaseParticipants = function () {
      const caseId = document.getElementById('case-id').value;
      if (!caseId) return alert('يرجى إدخال رقم الدعوى');

      fetch(`/release-memo/fetch?case_id=${caseId}`)
        .then(response => {
          if (!response.ok) throw new Error('رقم الدعوى غير موجود');
          return response.json();
        })
        .then(data => {
          document.getElementById('tribunal-number').value = data.tribunal?.number || '';
          document.getElementById('department-number').value = data.department?.number || '';
          document.getElementById('case-year').value = new Date().getFullYear();
          document.getElementById('case-type').value = data.courtCase?.type || '';
          document.getElementById('judge-name').value = data.courtCase?.judge?.full_name || 'غير محدد';

          const tbody = document.getElementById('participants-table-body');
          tbody.innerHTML = '';

          if (data.participants.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center">لا يوجد أطراف لهذه الدعوى</td></tr>';
          } else {
            data.participants.forEach(p => {
              const row = document.createElement('tr');
              row.innerHTML = `
                <td>${p.name}</td>
                <td>${p.type}</td>
                <td>${p.charge || 'غير محددة'}</td>
              `;
              tbody.appendChild(row);
            });
          }
        })
        .catch(error => {
          alert(error.message);
        });
    };

    // ✅ دالة حفظ مذكرة الإفراج
    window.submitReleaseMemo = function () {
      const caseId = document.getElementById('case-id').value;
      const judgeName = document.getElementById('judge-name').value;
      const firstRow = document.querySelector('#participants-table-body tr:first-child td:first-child');
      const participantName = firstRow ? firstRow.textContent : '';

      if (!caseId || !judgeName || !participantName) {
        return alert('يرجى التأكد من إدخال رقم الدعوى وجلب البيانات أولاً');
      }

      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      fetch('/release-memo/store', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          case_id: caseId,
          judge_name: judgeName,
          participant_name: participantName,
          detention_duration: '',
          detention_reason: '',
          detention_center: ''
        })
      })
        .then(response => {
          if (!response.ok) throw new Error('حدث خطأ أثناء الحفظ');
          return response.json();
        })
        .then(data => {
          alert('تم حفظ مذكرة الإفراج بنجاح');
          const modal = bootstrap.Modal.getInstance(document.getElementById('releaseMemoModal'));
          modal.hide();
        })
        .catch(error => {
          alert(error.message);
        });
    };

  });
</script>

@endsection