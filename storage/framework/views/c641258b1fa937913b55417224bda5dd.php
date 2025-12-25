<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>محضر المحاكمة / ما بعد</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <style>
        body { padding: 30px; font-family: 'Cairo', sans-serif; }
        .case-number-box { position: absolute; top: 30px; right: 30px; font-weight: bold; }

        .finger-box {
            width: 70px;
            height: 70px;
            border: 2px dashed #666;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
            border-radius: 8px;
            opacity: .8;
        }

        .finger-box svg {
            width: 30px;
            height: 30px;
            fill: #666;
        }
    </style>
</head>
<body>

<?php
    // ✅ ضبط لغة Carbon إلى العربية
    \Carbon\Carbon::setLocale('ar');
?>

<!-- رقم الدعوى -->
<div class="case-number-box">
    رقم الدعوى: <?php echo e($case->number); ?> / <?php echo e($case->year); ?>

</div>

<!-- هيدر -->
<div class="text-center mb-4">
    <h2 class="my-3 fw-bold">محضر المحاكمة / ما بعد</h2>

    <!-- ✅ تاريخ الجلسة مع اليوم بالعربي -->
    <p class="mt-2">
        جلسة اليوم:
        <?php echo e(\Carbon\Carbon::parse($session->session_date)->translatedFormat('l d/m/Y')); ?>

    </p>

    <h5 class="mt-3">الهيئة الحاكمة:</h5>
    <p><?php echo e($judge->full_name); ?></p>

    <h5 class="mt-3">الطابعة:</h5>
    <p><?php echo e($typist->full_name); ?></p>
</div>

<form method="POST" action="<?php echo e(route('after.trial.report.store', $session->id)); ?>">
<?php echo csrf_field(); ?>

<input type="hidden" name="report_mode" value="after">
<input type="hidden" name="source" value="<?php echo e($source); ?>">

<!-- ================================ -->
<!-- 🔷 الأطراف الأساسيين -->
<!-- ================================ -->
<?php $__currentLoopData = $participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $part): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<?php
    $savedStatement = $reports->firstWhere('participant_id', $part->id);
?>

<div class="mb-4">
    <h6><?php echo e($part->type ?? 'طرف'); ?>: <?php echo e($part->name); ?></h6>

    <label>أقوال الطرف:</label>
    <textarea class="form-control" rows="3"
              name="participants[<?php echo e($part->id); ?>][statement]"><?php echo e($savedStatement->statement_text ?? ''); ?></textarea>

    <div class="finger-box">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
            <path d="M8 13a.5.5 0 0 1..."></path>
        </svg>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<hr>

<!-- ================================ -->
<!-- 🔷 الأطراف المضافة سابقاً -->
<!-- ================================ -->
<div id="newParties">
<?php $__currentLoopData = $added_parties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="mb-3 border p-3">
    <h6><?php echo e($ap->role); ?> : <?php echo e($ap->name); ?></h6>

    <label>أقوال الطرف:</label>
    <textarea class="form-control" rows="3"
              name="new_parties_existing[<?php echo e($ap->id); ?>][statement]"><?php echo e($ap->statement_text); ?></textarea>

    <div class="finger-box mt-2">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
            <path d="M8 13a.5.5 0 0 1..."></path>
        </svg>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<button type="button" class="btn btn-secondary mb-3" onclick="addNewParty()">إضافة طرف آخر</button>

<hr>

<!-- ================================ -->
<!-- 🔷 القرار النهائي -->
<!-- ================================ -->
<h5>الــقـــرار</h5>

<label>القرار النهائي:</label>
<textarea class="form-control" rows="3"
          name="decision_text"><?php echo e($savedDecision->decision_text ?? ''); ?></textarea>

<div class="finger-box mt-2">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
        <path d="M8 13a.5.5 0 0 1..."></path>
    </svg>
</div>

<hr>

<div class="d-flex gap-3">
    <button type="submit" class="btn btn-primary mt-4">حفظ المحضر</button>

    <button type="button"
            class="btn btn-danger mt-4"
            onclick="closeAndReturn('<?php echo e($source); ?>')">
        خروج
    </button>
</div>

</form>

<script>
let partyIndex = 0;

function addNewParty() {
    partyIndex++;

    let html = `
    <div class="mb-3 border p-3">
        <h6 id="role_name_${partyIndex}">طرف جديد</h6>

        <label>اسم الطرف:</label>
        <input type="text" class="form-control"
               name="new_parties[${partyIndex}][name]"
               oninput="updateRoleLabel(${partyIndex})">

        <label class="mt-2">نوع الطرف:</label>
        <input type="text" class="form-control"
               name="new_parties[${partyIndex}][role]"
               oninput="updateRoleLabel(${partyIndex})">

        <label class="mt-2">أقوال الطرف:</label>
        <textarea class="form-control" rows="3"
                  name="new_parties[${partyIndex}][statement]"></textarea>

        <div class="finger-box mt-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                <path d="M8 13a.5.5 0 0 1..."></path>
            </svg>
        </div>
    </div>
    `;

    document.getElementById("newParties").insertAdjacentHTML("beforeend", html);
}

function updateRoleLabel(i) {
    let role = document.querySelector(`input[name='new_parties[${i}][role]']`).value;
    let name = document.querySelector(`input[name='new_parties[${i}][name]']`).value;

    document.getElementById(`role_name_${i}`).textContent =
        (role ? role : "طرف") + " : " + (name ? name : "");
}
</script>

<script>
function closeAndReturn(source) {
    window.close();
    setTimeout(function () {
        if (source === 'writer') {
            window.location.href = "<?php echo e(route('writer.dashboard')); ?>";
        } else {
            window.location.href = "<?php echo e(route('typist.cases')); ?>";
        }
    }, 300);
}
</script>

</body>
</html><?php /**PATH C:\Users\LENOVO\legal_system\resources\views/clerk_dashboard/after_trial_report.blade.php ENDPATH**/ ?>