<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'نظام المحكمة')</title>

    <!-- ✅ Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ✅ Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            direction: rtl;
            background-color: #f9f9f9;
        }

        .top-bar {
            background-color: #004080;
            color: white;
            font-size: 14px;
            padding: 4px 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .menu-bar {
            background-color: #e0e0e0;
            font-size: 13px;
            padding: 4px 12px;
            display: flex;
            gap: 20px;
            border-bottom: 1px solid #ccc;
        }

        .menu-bar div {
            cursor: default;
        }

        .third-bar {
            background-color: #f0f0f0;
            font-size: 13px;
            padding: 6px 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ccc;
        }

        .third-bar .form-check {
            margin-left: 10px;
            font-size: 12px;
        }

        .third-bar .form-check-input {
            width: 14px;
            height: 14px;
            margin-top: 0;
        }

        .third-bar input {
            width: 90px;
            font-size: 13px;
        }

        .content {
            padding: 20px;
        }
    </style>
</head>
<body>

    {{-- ✅ الشريط الأول --}}
    <div class="top-bar">
         <div>المحكمة: {{ $tribunalName ?? '---' }}</div>
    <div>القلم: {{ $departmentName ?? '---' }}</div>
        <div>الموظف: {{ $userName ?? '---' }}</div>
    </div>

    {{-- ✅ الشريط الثاني --}}
    <div class="menu-bar">
        <span id="trigger-cases" style="cursor: pointer; position: relative;">الدعوى / الطلب</span>
        <div>إعدادات الدعوى/الطلب</div>
        <span id="trigger-notifications" style="cursor: pointer; position: relative;">التباليغ</span>
        <span id="sessions-trigger" style="cursor: pointer;">الجلسات</span>
        <div>البحث</div>
        <div>الإعدادات</div>
        <div>الحماية</div>
    </div>

    {{-- ✅ الشريط الثالث --}}
    <div class="third-bar">
        {{-- اختيار النوع --}}
        <div class="d-flex align-items-center">
            <label class="me-2 mb-0">النوع:</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="entry_type" id="type_case" value="case" checked>
                <label class="form-check-label" for="type_case">دعوى</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="entry_type" id="type_request" value="request">
                <label class="form-check-label" for="type_request">طلب</label>
            </div>
        </div>

        {{-- رقم الدعوى --}}
        <div class="d-flex align-items-center gap-2">
            <label class="mb-0">رقم الدعوى:</label>
            <input type="text" class="form-control form-control-sm" placeholder="المحكمة" readonly value="{{ $tribunalNumber ?? '---' }}">
            <input type="text" class="form-control form-control-sm" placeholder="القلم" readonly value="{{ $departmentNumber ?? '---' }}">
            <input type="text" class="form-control form-control-sm" placeholder="رقم الدعوى">
            <input type="text" class="form-control form-control-sm" placeholder="السنة" readonly value="{{ date('Y') }}">
        </div>
    </div>

    {{-- ✅ محتوى الصفحة --}}
    <div class="content">
        @yield('content')
    </div>

    <!-- ✅ Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ✅ كود التفاعل مع التباليغ -->
    <script>
      const trigger = document.getElementById('trigger-notifications');
      trigger.addEventListener('mouseenter', () => {
        document.dispatchEvent(new Event('showNotificationsMenu'));
      });

      // ✅ تحقق من تحميل Axios
      if (typeof axios === 'undefined') {
        alert('❌ Axios غير محمّل!');
      } else {
        console.log('✅ Axios تم تحميله بنجاح');
      }
    </script>

</body>
</html>