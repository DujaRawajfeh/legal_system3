@extends('layouts.app')

@section('title', 'صفحة الطابعة')

@section('content')
{{-- ✅ قائمة الجلسات الخاصة بالطابعة (تظهر عند المرور على الكلمة الموجودة في layouts.app) --}}
<div id="sessions-menu-typist" class="position-absolute bg-white border rounded shadow-sm px-2 py-1"
     style="display: none; top: 38px; right: 12px; z-index: 1000; min-width: 220px;">
    <div class="dropdown-item">جدول أعمال المحكمة</div>
    <div class="dropdown-item">جدول أعمال القاضي</div>
    <div class="dropdown-item">جدول الدعوى</div>
    <div class="dropdown-item">جدول الطلب</div>
    <div class="dropdown-item">تحديد جلسات الدعوى</div>
    <div class="dropdown-item">إعادة تحديد جلسات الدعوى</div>
    <div class="dropdown-item">إلغاء جلسات الدعوى</div>
    <div class="dropdown-item">أحكام الدعوى</div>
    <div class="dropdown-item">البحث عن جلسات الدعوى</div>
    <div class="dropdown-item">طباعة جلسات الدعوى</div>
</div>

<!-- ✅ هذا الكود يظهر قائمة الجلسات فقط إذا كان النوع المختار هو "دعوى" -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const trigger = document.getElementById('sessions-trigger');
    const menu = document.getElementById('sessions-menu-typist');

    function getCurrentType() {
        const selected = document.querySelector('input[name="entry_type"]:checked');
        return selected ? selected.value : null;
    }

    let isOverTrigger = false;
    let isOverMenu = false;

    trigger.addEventListener('mouseenter', function () {
        isOverTrigger = true;
        if (getCurrentType() === 'case') {
            menu.style.display = 'block';
        }
    });

    trigger.addEventListener('mouseleave', function () {
        isOverTrigger = false;
        setTimeout(() => {
            if (!isOverMenu) menu.style.display = 'none';
        }, 200);
    });

    menu.addEventListener('mouseenter', function () {
        isOverMenu = true;
    });

    menu.addEventListener('mouseleave', function () {
        isOverMenu = false;
        setTimeout(() => {
            if (!isOverTrigger) menu.style.display = 'none';
        }, 200);
    });

    const radios = document.querySelectorAll('input[name="entry_type"]');
    radios.forEach(radio => {
        radio.addEventListener('change', () => {
            menu.style.display = 'none';
        });
    });
});
</script>
@endsection