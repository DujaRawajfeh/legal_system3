<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>دائرة المحاكم</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap');
  body {
    background-color: #f4f4f4;
    font-family: "Cairo", sans-serif;
    margin: 0;
    padding: 0;
  }

  /* الشريط العلوي */
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

  .container.content {
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    margin: 40px auto;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    max-width: 1200px;
  }

  .split-container {
    display: flex;
    gap: 25px;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
  }

  .left-side, .right-side {
    flex: 1;
    min-width: 300px;
  }

  .preview-box {
    background: #f1f1f1;
    border-radius: 12px;
    border: 1px solid #ddd;
    padding: 10px;
    min-height: 420px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .preview-box img, .preview-box iframe {
    max-width: 100%;
    max-height: 400px;
    border-radius: 8px;
  }

  label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
  }

  input[type=text], select, input[type=file] {
    width: 100%;
    padding: 8px;
    border-radius: 6px;
    border: 1px solid #ccc;
    margin-bottom: 15px;
    box-sizing: border-box;
    font-size: 14px;
  }

  button {
    font-family: "Cairo", sans-serif;
  font-weight: bold;
  background-color: #37678e;
  border: none;
  color: white;
  cursor: pointer;
  transition: 0.2s;
  flex-shrink: 0;
  
  font-size: 10px; /* بدل 11 */
  padding: 6px 10px; /* بدل 8px 14px */
  border-radius: 5px; /* بدل 6px */
  margin-left: 15px; /* بدل 20 */
}

.case-strip button:hover {
  background-color: #37678e;
  }

  .btn-success {
    background-color: #28a745;
    color: #fff;
  }

  .btn-outline-primary {
    background-color: transparent;
    color: #0d6efd;
    border: 1px solid #0d6efd;
    padding: 5px 10px;
    border-radius: 6px;
  }

  .text-center {text-align: center;}
  .mb-3 {margin-bottom: 1rem;}
  .mb-4 {margin-bottom: 1.5rem;}
  .mt-4 {margin-top: 1.5rem;}
  .mt-15 {margin-top: 15px;}
  .fw-bold {font-weight: bold;}
  .d-none {display: none;}
  .text-muted {color: #6c757d;}

  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 30px;
    text-align: center;
  }

  thead {
    background-color: #000;
    color: #fff;
  }

  th, td {
    border: 1px solid #ccc;
    padding: 8px;
    vertical-align: middle;
  }

  .alert {
    margin-top: 15px;
    padding: 10px;
    border-radius: 8px;
    background-color: #d4edda;
    color: #155724;
    text-align: center;
  }

</style>
</head>
<body>

<div class="court-bar"><?php echo e(optional(auth()->user()->tribunal)->name ?? 'محكمة بداية عمان'); ?> / <?php echo e(optional(auth()->user()->department)->name ?? '-'); ?></div>

<nav class="navbar">
  <div class="left-section">
    <div class="user-info">المؤرشف / <?php echo e($archiver->full_name); ?></div>
    
    <ul class="nav-links">
      <li><a href="<?php echo e(route('2fa.setup')); ?>" class="security-link" target="_self">إعدادات الحماية</a></li>
    </ul>
  </div>

  <form method="POST" action="<?php echo e(route('logout')); ?>" style="margin:0;">
    <?php echo csrf_field(); ?>
    <button type="submit" class="logout-btn">
      تسجيل الخروج
    </button>
  </form>
</nav>

<div class="container content">
  <h4 class="text-center mb-4">📄 نظام أرشفة الوثائق</h4>

  <div class="split-container">
    <div class="left-side">
      <!-- ✅ الفورم مربوط بالـ store -->
      <form method="POST" action="<?php echo e(route('archived-documents.store')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <!-- رقم الدعوى = 4 بوكسات -->
        <label>🔢 رقم الدعوى</label>
        <div style="display:flex; gap:10px; margin-bottom:15px;">
          <!-- رقم الدعوى الأساسي -->
          <input type="text" name="court_case_id" id="casePart1" placeholder="رقم الدعوى" required>

          <!-- رقم القلم -->
          <input type="text" id="casePart2" value="<?php echo e($archiver->department->number ?? ''); ?>" disabled>

          <!-- رقم المحكمة -->
          <input type="text" id="casePart3" value="<?php echo e($archiver->tribunal->number ?? ''); ?>" disabled>

          <!-- السنة -->
          <input type="text" id="casePart4" value="<?php echo e($year); ?>" disabled>
        </div>

        <div class="mb-3">
          <label>📑 نوع الوثيقة</label>
          <select name="document_type" required>
            <option selected disabled>اختر نوع الوثيقة</option>
            <option>مسودة قرار</option>
            <option>قرارات وأحكام</option>
            <option>قرار تصحيح خطأ مادي</option>
            <option>وصولات مالية</option>
            <option>مستندات الصرف</option>
            <option>ملف محال من محكمة أخرى</option>
            <option>كتب رسمية</option>
            <option>إستدعاءات</option>
            <option>تعهد صحة بيانات وأوراق شخصية</option>
            <option>وكالات وإنابات المدعي</option>
            <option>وكالات وإنابات المدعى عليه</option>
            <option>لائحة الدعوى</option>
            <option>لائحة جوابية</option>
            <option>لوائح ومذكرات اعتراضية أخرى</option>
            <option>لوائح رد على الجواب</option>
            <option>لوائح مقابلة (الادعاء المقابل والرد عليه)</option>
            <option>لوائح الإدخال والتدخل والرد عليهم</option>
            <option>لوائح الاعراض والمعدة المشروعة والرد عليهم</option>
            <option>بيان المدعي</option>
            <option>بيان المدعى عليه</option>
            <option>البيان اللاحق والبيان الإضافي</option>
            <option>بيان المدعي الشخصي</option>
            <option>بيان المدعى عليه الشخصي</option>
            <option>تقارير خبرة</option>
            <option>محاضر الجلسات</option>
            <option>مرافعات</option>
            <option>أدلةجنائية</option>
            <option>طلبات المدعي</option>
            <option>طلبات المدعى عليه</option>
          </select>
        </div>

        <div class="mb-3">
          <label>📤 رفع الوثيقة (PDF / صورة)</label>
          <input type="file" name="document_file" id="documentFile" accept=".pdf,.jpg,.png,.jpeg" required>
        </div>

        <div class="text-center">
          <button type="submit" class="btn btn-success">أرشفة الوثيقة</button>
        </div>
      </form>

      <!-- رسالة نجاح -->
      <?php if(session('success')): ?>
        <div id="resultMessage" class="alert alert-success mt-4">
          ✅ <?php echo e(session('success')); ?>

        </div>
      <?php endif; ?>
    </div>

    <div class="right-side">
      <label class="fw-bold mb-2"> معاينة الوثيقة</label>
      <div class="preview-box" id="previewBox">
        <p class="text-muted">لم يتم اختيار أي وثيقة بعد</p>
      </div>
    </div>
  </div>
</div>
  </div>
  <table id="archiveTable">
  <table id="archiveTable">
  <table class="table table-bordered">
  <thead>
    <tr>
      <th>رقم الدعوى</th>
      <th>رقم الوثيقة</th>
      <th>نوع الوثيقة</th>
      <th>تاريخ/وقت الأرشفة</th>
      <th>عرض</th>
    </tr>
  </thead>
  <tbody>
    <?php $__empty_1 = true; $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <tr>
        <!-- رقم الدعوى (أربع أرقام من جدول القضايا) -->
        <td><?php echo e($doc->courtCase->number); ?></td>

        <!-- رقم الوثيقة (مثلاً 0382/1 أو 0382/2) -->
        <td><?php echo e($doc->document_number); ?></td>

        <!-- نوع الوثيقة -->
        <td><?php echo e($doc->document_type); ?></td>

        <!-- تاريخ ووقت الأرشفة -->
        <td><?php echo e($doc->created_at->format('Y-m-d H:i')); ?></td>

        <!-- رابط عرض الملف -->
        <td>
          <a href="<?php echo e(asset('uploads/archived_documents/'.$doc->file_name)); ?>" 
             target="_blank" 
             class="btn btn-outline-primary">
             عرض
          </a>
        </td>
      </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <tr class="text-muted">
        <td colspan="5">لا توجد وثائق مؤرشفة بعد.</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>
</div>
<script>
// ✅ تعبئة رقم الدعوى (اختياري، إذا بدك تربطيه بـ Route في Laravel)
async function fetchCaseNumber() {
    let part1 = document.getElementById("casePart1").value.trim();
    if (part1.length < 4) return;

    try {
        const response = await fetch(`/getCaseNumber/${part1}`);
        const data = await response.json();

        if (data.error) {
            alert("رقم الدعوى غير موجود في قاعدة البيانات.");
            return;
        }

        document.getElementById("casePart2").value = data.part2;
        document.getElementById("casePart3").value = data.part3;
        document.getElementById("casePart4").value = data.part4;

    } catch (error) {
        console.log(error);
        alert("خطأ في الاتصال بالسيرفر.");
    }
}

// ✅ معاينة الملف قبل الأرشفة
document.getElementById("documentFile").addEventListener("change", function () {
  const file = this.files[0];
  const previewBox = document.getElementById("previewBox");

  if (!file) {
    previewBox.innerHTML = "<p class='text-muted'>لم يتم اختيار أي وثيقة بعد</p>";
    return;
  }

  const url = URL.createObjectURL(file);

  if (file.type === "application/pdf") {
    previewBox.innerHTML = `<iframe src="${url}" width="100%" height="400"></iframe>`;
  } else {
    previewBox.innerHTML = `<img src="${url}" alt="preview">`;
  }
});

// ✅ عرض الوثيقة في نافذة جديدة
function viewDocument(url) {
  window.open(url, "_blank");
}


</script>
</body>
</html><?php /**PATH C:\legal_system3\resources\views/clerk_dashboard/archiver.blade.php ENDPATH**/ ?>