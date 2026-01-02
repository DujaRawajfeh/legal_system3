<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>التحقق من المصادقة الثنائية</title>

    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            align-items: center;      /* توسيط عمودي */
            justify-content: center;  /* توسيط أفقي */
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .container {
            background: #ffffff;
            padding: 30px;
            width: 100%;
            max-width: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        h3 {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            text-align: center;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #198754;
            border: none;
            color: #fff;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #157347;
        }
    </style>
</head>

<body>

<div class="container">
    <h3>أدخلي رمز المصادقة الثنائية</h3>

    <form method="POST" action="<?php echo e(route('2fa.verify')); ?>">
        <?php echo csrf_field(); ?>

        <label for="totp_code">رمز التطبيق أو الرمز الاحتياطي</label>
        <input
            type="text"
            name="totp_code"
            id="totp_code"
            placeholder="أدخل رمز مكوّن من 6 أرقام"
            required
            autofocus
        >

        <button type="submit">تحقق</button>
    </form>
</div>

</body>
</html><?php /**PATH C:\Users\LENOVO\legal_system\resources\views/auth/2fa-verify.blade.php ENDPATH**/ ?>