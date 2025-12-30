<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

@php
\Carbon\Carbon::setLocale('ar');
@endphp

<div class="case-number-box">
    رقم الدعوى: {{ $case->number }} / {{ $case->year }}
</div>

<div class="text-center mb-4">
    <h2 class="fw-bold">محضر المحاكمة / ما بعد</h2>
    <p>
        جلسة اليوم:
        {{ \Carbon\Carbon::parse($session->session_date)->translatedFormat('l d/m/Y') }}
    </p>
    <p><strong>الهيئة الحاكمة:</strong> {{ $judge->full_name }}</p>
    <p><strong>الطابعة:</strong> {{ $typist->full_name }}</p>
</div>

<form method="POST" action="{{ route('after.trial.report.store', $session->id) }}">
@csrf

<input type="hidden" name="report_mode" value="after">
<input type="hidden" name="source" value="{{ $source }}">

{{-- ================= الأطراف الأساسيين ================= --}}
@foreach($participants as $part)
@php
    $savedStatement = $reports->firstWhere('participant_id', $part->id);
@endphp

<div class="mb-4">
    <h6>{{ $part->type ?? 'طرف' }} : {{ $part->name }}</h6>

    <textarea class="form-control mb-2" rows="3"
        name="participants[{{ $part->id }}][statement]">{{ $savedStatement->statement_text ?? '' }}</textarea>

    <div class="finger-box"
         id="finger-participant-{{ $part->id }}"
         onclick="readFingerprint({{ $part->id }}, 'participant')">
        أخذ البصمة
    </div>
</div>
@endforeach

<hr>

{{-- ================= الأطراف المضافة سابقاً ================= --}}
<div id="newParties">
@foreach($added_parties as $ap)
<div class="mb-3 border p-3">
    <h6>{{ $ap->role }} : {{ $ap->name }}</h6>

    <textarea class="form-control mb-2" rows="3"
        name="new_parties_existing[{{ $ap->id }}][statement]">{{ $ap->statement_text }}</textarea>

    <div class="finger-box"
         id="finger-added-{{ $ap->id }}"
         onclick="readFingerprint({{ $ap->id }}, 'added')">
        أخذ البصمة
    </div>
</div>
@endforeach
</div>

<button type="button" class="btn btn-secondary mb-3" onclick="addNewParty()">إضافة طرف آخر</button>

<hr>

{{-- ================= القرار النهائي ================= --}}
@php
$savedDecision = $reports->whereNotNull('decision_text')->first();
@endphp

<h5>الــقـــرار</h5>

<textarea class="form-control mb-2" rows="3"
    name="decision_text"
    id="decision-text">{{ $savedDecision->decision_text ?? '' }}</textarea>

<div class="finger-box"
     id="finger-decision"
     onclick="readFingerprint(0, 'decision')">
    أخذ البصمة
</div>

<hr>

<div class="d-flex gap-3">
    <button type="submit" class="btn btn-primary">حفظ المحضر</button>
    <button type="button" class="btn btn-danger" onclick="closeAndReturn('{{ $source }}')">خروج</button>
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
const courtCaseId = {{ $case->id }};
const caseSessionId = {{ $session->id }};
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
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
            ? "{{ route('writer.dashboard') }}"
            : "{{ route('typist.cases') }}";
}
</script>

</body>
</html>
