<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            direction: rtl;
            padding: 40px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 250px;
            padding: 6px;
            font-size: 14px;
        }

        .toggle-password {
            cursor: pointer;
            margin-right: 8px;
            font-size: 13px;
            color: #0077cc;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h2>تسجيل الدخول</h2>

    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label for="national_id">الرقم الوطني:</label><br>
        <input type="text" name="national_id" id="national_id"><br><br>

        <label for="password">كلمة المرور:</label><br>
        <input type="password" name="password" id="password">
        <span class="toggle-password" onclick="togglePassword()">👁️ إظهار</span><br><br>

        <button type="submit">دخول</button>
    </form>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const toggleText = document.querySelector(".toggle-password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleText.textContent = "👁️ إخفاء";
            } else {
                passwordInput.type = "password";
                toggleText.textContent = "👁️ إظهار";
            }
        }
    </script>
</body>
</html>