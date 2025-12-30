<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>محضر المحاكمة / ما بعد</title>
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
            padding: 5px;
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
    <h2 class="fw-bold">محضر المحاكمة / ما بعد</h2>
    <p>
        جلسة اليوم:
        <?php echo e(\Carbon\Carbon::parse($session->session_date)->translatedFormat('l d/m/Y')); ?>

    </p>
    <p><strong>الهيئة الحاكمة:</strong> <?php echo e($judge->full_name); ?></p>
    <p><strong>الطابعة:</strong> <?php echo e($typist->full_name); ?></p>
</div>

<form method="POST" action="<?php echo e(route('after.trial.report.store', $session->id)); ?>">
<?php echo csrf_field(); ?>

<input type="hidden" name="report_mode" value="after">
<input type="hidden" name="source" value="<?php echo e($source); ?>">


<?php $__currentLoopData = $participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $part): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php
    $savedStatement = $reports->firstWhere('participant_id', $part->id);
?>

<div class="mb-4">
    <h6><?php echo e($part->type ?? 'طرف'); ?> : <?php echo e($part->name); ?></h6>

    <textarea class="form-control mb-2" rows="3"
        name="participants[<?php echo e($part->id); ?>][statement]"><?php echo e($savedStatement->statement_text ?? ''); ?></textarea>

    <div class="finger-box"
         id="finger-participant-<?php echo e($part->id); ?>"
         onclick="readFingerprint(<?php echo e($part->id); ?>, 'participant')">
        أخذ البصمة
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<hr>


<div id="newParties">
<?php $__currentLoopData = $added_parties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="mb-3 border p-3">
    <h6><?php echo e($ap->role); ?> : <?php echo e($ap->name); ?></h6>

    <textarea class="form-control mb-2" rows="3"
        name="new_parties_existing[<?php echo e($ap->id); ?>][statement]"><?php echo e($ap->statement_text); ?></textarea>

    <div class="finger-box"
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
$savedDecision = $reports->whereNotNull('decision_text')->first();
?>

<h5>الــقـــرار</h5>

<textarea class="form-control mb-2" rows="3"
    name="decision_text"
    id="decision-text"><?php echo e($savedDecision->decision_text ?? ''); ?></textarea>

<div class="finger-box"
     id="finger-decision"
     onclick="readFingerprint(0, 'decision')">
    أخذ البصمة
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

            <input class="form-control mb-2"
                name="new_parties[${partyIndex}][name]"
                placeholder="اسم الطرف"
                oninput="updateRole(${partyIndex})">

            <input class="form-control mb-2"
                name="new_parties[${partyIndex}][role]"
                placeholder="نوع الطرف"
                oninput="updateRole(${partyIndex})">

            <textarea class="form-control"
                name="new_parties[${partyIndex}][statement]"
                rows="3"></textarea>
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
    document.getElementById(`role_${i}`).innerText =
        `${role || 'طرف'} : ${name || ''}`;
}
</script>

<script>
const courtCaseId = <?php echo e($case->id); ?>;
const caseSessionId = <?php echo e($session->id); ?>;
const ws = new WebSocket("ws://localhost:8080");
let activeFinger = null;

ws.onmessage = function (event) {
    if (!activeFinger) return;

    const { box, participantId } = activeFinger;
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
            report_mode: 'after'
        })
    }).then(() => {
        box.dataset.state = "done";
        box.innerText = "تم أخذ البصمة ✅";
        activeFinger = null;
    }).catch(() => {
        box.dataset.state = "idle";
        box.innerText = "حدث خطأ ❌";
        activeFinger = null;
    });
};

function readFingerprint(id, type) {
    const box =
        document.getElementById(`finger-${type}-${id}`) ||
        document.getElementById(`finger-${type}`);

    box.dataset.state = "waiting";
    box.innerText = "جاري أخذ البصمة...";
    activeFinger = { box, participantId: id };
    ws.send("start");
}

function closeAndReturn(source) {
    window.location.href =
        source === 'writer'
            ? "<?php echo e(route('writer.dashboard')); ?>"
            : "<?php echo e(route('typist.cases')); ?>";
}
</script>

</body>
</html>
<?php /**PATH C:\legal_system3\resources\views/clerk_dashboard/after_trial_report.blade.php ENDPATH**/ ?>