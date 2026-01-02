<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>محضر المحاكمة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <style>
        body { padding: 30px; font-family: 'Cairo', sans-serif; }

        .case-number-box {
            position: absolute;
            top: 30px;
            right: 30px;
            font-weight: bold;
        }

        .finger-box {
            width: 110px;
            height: 70px;
            border: 2px dashed #666;
            border-radius: 8px;
            background: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 14px;
            transition: .3s;
        }

        .finger-box[data-state="waiting"] {
            border-color: #ff9800;
            background: #fff8e1;
        }

        .finger-box[data-state="done"] {
            border-color: green;
            background: #e8f5e9;
        }

        .decision-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<?php
\Carbon\Carbon::setLocale('ar');
?>

<div class="case-number-box">
    رقم الدعوى: <?php echo e($case->number); ?> / <?php echo e($case->year); ?>

</div>

<div class="text-center mb-4">
    <h5>المملكة الأردنية الهاشمية</h5>
    <h6>وزارة العدل</h6>
    <h3 class="my-3">محضر المحاكمة</h3>

    <p>
        جلسة اليوم:
        <?php echo e(\Carbon\Carbon::parse($session->session_date)->translatedFormat('l d/m/Y')); ?>

    </p>

    <p>اسم الهيئة الحاكمة: <?php echo e($judge->full_name); ?></p>
    <p>الطابعة: <?php echo e($typist->full_name); ?></p>
</div>

<form method="POST" action="<?php echo e(route('trial.report.store', $session->id)); ?>">
<?php echo csrf_field(); ?>

<input type="hidden" name="report_mode" value="trial">
<input type="hidden" name="source" value="<?php echo e($source); ?>">


<?php $__currentLoopData = $participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $part): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php
    $savedStatement = $reports
        ->where('participant_id', $part->id)
        ->where('report_mode', 'trial')
        ->first();
?>

<div class="mb-4">
    <h6><?php echo e($part->type ?? 'طرف'); ?> : <?php echo e($part->name); ?></h6>

    <label>أقوال الطرف:</label>
    <textarea class="form-control" rows="3"
        name="participants[<?php echo e($part->id); ?>][statement]"><?php echo e($savedStatement->statement_text ?? ''); ?></textarea>

    <div class="finger-box mt-2"
         id="finger-participant-<?php echo e($part->id); ?>"
         onclick="readFingerprint(<?php echo e($part->id); ?>, 'participant')">
        أخذ البصمة
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<hr>


<div id="newParties">
<?php $__currentLoopData = $added_parties->where('report_mode','trial'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="mb-3 border p-3">
    <h6><?php echo e($ap->role); ?> : <?php echo e($ap->name); ?></h6>

    <textarea class="form-control" rows="3"
        name="new_parties_existing[<?php echo e($ap->id); ?>][statement]"><?php echo e($ap->statement_text); ?></textarea>

    <div class="finger-box mt-2"
         id="finger-added-<?php echo e($ap->id); ?>"
         onclick="readFingerprint(<?php echo e($ap->id); ?>, 'added')">
        أخذ البصمة
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<button type="button" class="btn btn-secondary mb-3" onclick="addNewParty()">إضافة طرف آخر</button>

<hr>


<?php
$savedDecision = $reports->whereNotNull('decision_text')
    ->where('report_mode','trial')->first();
?>

<div class="decision-box">
    <h5>القرار</h5>

    <textarea class="form-control" rows="3"
        id="decision-text"
        name="decision_text"><?php echo e($savedDecision->decision_text ?? ''); ?></textarea>

    <div class="finger-box mt-2"
         id="finger-decision"
         onclick="readFingerprint(0, 'decision')">
        أخذ البصمة
    </div>
</div>

<hr>

<div class="d-flex gap-3">
    <button type="submit" class="btn btn-primary">حفظ المحضر</button>
    <button type="button" class="btn btn-danger" onclick="closeAndReturn('<?php echo e($source); ?>')">خروج</button>
</div>

</form>

<script>
let partyIndex = 0;

function addNewParty() {
    partyIndex++;
    document.getElementById("newParties").insertAdjacentHTML("beforeend", `
        <div class="mb-3 border p-3">
            <h6 id="role_${partyIndex}">طرف جديد</h6>
            <input class="form-control mb-2" name="new_parties[${partyIndex}][name]" placeholder="الاسم"
                   oninput="updateRole(${partyIndex})">
            <input class="form-control mb-2" name="new_parties[${partyIndex}][role]" placeholder="الصفة"
                   oninput="updateRole(${partyIndex})">
            <textarea class="form-control mb-2"
                name="new_parties[${partyIndex}][statement]"></textarea>
            <div class="finger-box"
                 id="finger-added-new-${partyIndex}"
                 onclick="readFingerprint('new-${partyIndex}', 'added')">
                أخذ البصمة
        </div>
    `);
}

function updateRole(i) {
    const name = document.querySelector(`[name='new_parties[${i}][name]']`).value;
    const role = document.querySelector(`[name='new_parties[${i}][role]']`).value;
    document.getElementById(`role_${i}`).innerText = `${role || 'طرف'} : ${name || ''}`;
}
</script>


<script>
const ws = new WebSocket("ws://localhost:8080");
const courtCaseId = <?php echo e($case->id); ?>;
const caseSessionId = <?php echo e($session->id); ?>;

ws.onmessage = function (event) {
    if (!window.activeFingerprint) return;

    const { box, participantId, type } = window.activeFingerprint;
    const fingerId = event.data.replace('ID:', '').trim();

    fetch('/save-fingerprint', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({
            participant_id: participantId,
            court_case_id: courtCaseId,
            case_session_id: caseSessionId,
            fingerprint: fingerId,
            report_mode: 'trial',
            target: type
        })
    }).then(() => {
        box.dataset.state = "done";
        box.innerText = "تم أخذ البصمة ✅";
    });
};

function readFingerprint(id, type) {
    const box = document.getElementById(`finger-${type}-${id}`) || document.getElementById(`finger-${type}`);
    box.dataset.state = "waiting";
    box.innerText = "جاري أخذ البصمة...";
    window.activeFingerprint = { box, participantId: id, type };
    ws.send("start");
}

function closeAndReturn(source) {


    if (source && source.startsWith('judge')) {
        window.location.href = "/judge";
        return;
    }

    if (source && source.startsWith('writer')) {
        window.location.href = "/writer/dashboard";
        return;
    }

    if (source && source.startsWith('typist')) {
        window.location.href = "/typist/cases";
        return;
    }

    // fallback آمن
    window.location.href = "/";
}
</script>

</body>
</html>
<?php /**PATH C:\Users\LENOVO\legal_system\resources\views/clerk_dashboard/trial_report.blade.php ENDPATH**/ ?>