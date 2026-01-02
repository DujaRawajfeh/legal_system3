<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول - دائرة المحاكم</title>
    @if (session('success'))
    <script>
        alert("{{ session('success') }}");
    </script>
    @endif
    <style>
        body {
            margin: 0;
            font-family: "Cairo", sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f4;
        }

        .login-box {
            background:white ;
            width: 430px;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.25);
            text-align: center;
            border-top: 6px solid #b59b57;
            position: relative;
        }

        .login-box img {
            width: 120px;
            margin-bottom: 10px;
        }

        .system-title {
            font-size: 26px;
            font-weight: 800;
            color: #1f1f1f;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 17px;
            color: #333;
            margin-bottom: 25px;
        }

        label {
            font-size: 15px;
            font-weight: bold;
            color: #2c2c2c;
            display: block;
            text-align: right;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1.5px solid #bbb;
            border-radius: 6px;
            font-size: 15px;
            transition: 0.2s;
        }

        input:focus {
            border-color: #37678e;
            outline: none;
            box-shadow: 0 0 4px #37678e55;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 22px;
            background-color: #000;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        button:hover {
            background-color: #09263d;
        }

        .footer {
            margin-top: 18px;
            font-size: 13px;
            color: #444;
        }

        .forgot-password {
            display: block;
            margin-top: 15px;
            color: #37678e;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .alert {
            background-color: #fdecea;
            color: #b71c1c;
            border: 1px solid #f5c6cb;
            padding: 10px 12px;
            margin-top: 15px;
            border-radius: 6px;
            font-size: 14px;
            text-align: right;
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(-5px);}
            to {opacity: 1; transform: translateY(0);}
        }

        /* تنسيق النافذة */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #ccc;
            width: 350px;
            text-align: right;
        }

        .modal-content h3 {
            margin-top: 0;
        }

        .modal-content input {
            width: 100%;
            padding: 6px;
            margin-bottom: 10px;
        }

        .modal-content button {
            padding: 6px 12px;
            margin-right: 5px;
        }
    </style>
</head>
<body>

<div class="login-box">

    <img
  src="{{ asset('images/jordan-logo.png') }}"
  alt="شعار المملكة الأردنية الهاشمية"
  style="max-width:120px; height:auto;"
>

    <div class="system-title">نظام دائرة المحاكم</div>
    <div class="subtitle">تسجيل الدخول</div>

    {{-- عرض الأخطاء --}}
    @if ($errors->any())
        <div class="alert">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- عرض رسالة نجاح --}}
    @if (session('success'))
        <div class="alert" style="background-color:#e6ffed; color:#2d7a2d;">
            {{ session('success') }}
        </div>
    @endif

    {{-- فورم تسجيل الدخول --}}
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label for="national_id">الرقم الوطني</label>
        <input id="national_id" type="text" name="national_id" placeholder="ادخل الرقم الوطني">

        <label for="password">كلمة المرور</label>
        <input id="password" type="password" name="password" placeholder="ادخل كلمة المرور">
        <span class="toggle-password" onclick="togglePassword()"> إظهار</span>

        <button type="submit">دخول</button>
    </form>

   

    <div class="footer">© جميع الحقوق محفوظة — دائرة المحاكم</div>
</div>




<script>
    function togglePassword() {
        const passwordInput = document.getElementById("password");
        const toggleText = document.querySelector(".toggle-password");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleText.textContent = " إخفاء";
        } else {
            passwordInput.type = "password";
            toggleText.textContent = " إظهار";
        }
    }

    function openModal() {
        document.getElementById('changePasswordModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('changePasswordModal').style.display = 'none';
    }

    function openTotpModal() {
        document.getElementById('totpModal').style.display = 'block';
    }

    function closeTotpModal() {
        document.getElementById('totpModal').style.display = 'none';
    }

    // ⭐\ إذا الخطأ كان بسبب انتهاء صلاحية كلمة السر، افتح النافذة مباشرة
    @if($errors->has('password') || $errors->has('current_password'))
        openModal();
    @endif

    //  إذا رجعنا من الـ Controller مع فلاغ show_totp_modal، افتح نافذة TOTP مباشرة
    @if(session('show_totp_modal'))
        openTotpModal();
    @endif
</script>

</body>
</html>