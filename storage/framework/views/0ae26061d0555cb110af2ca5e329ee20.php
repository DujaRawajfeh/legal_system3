<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعداد المصادقة الثنائية</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: "Cairo", sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
            direction: rtl;
        }
        
        .container-2fa {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        h3 {
            color: #000;
            font-size: 1.5rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }
        
        h4 {
            color: #333;
            font-size: 1.1rem;
            margin: 20px 0 10px 0;
        }
        
        .qr-section {
            text-align: center;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 6px;
            margin: 20px 0;
        }
        
        .qr-section svg {
            max-width: 300px;
            height: auto;
        }
        
        .secret-key {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            font-family: monospace;
            font-size: 14px;
            word-break: break-all;
        }
        
        label {
            display: block;
            margin: 15px 0 8px 0;
            font-weight: 600;
            color: #000;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: "Cairo", sans-serif;
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        button, .btn {
            padding: 10px 25px;
            border: none;
            border-radius: 4px;
            font-family: "Cairo", sans-serif;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
            transition: 0.3s;
        }
        
        button[type="submit"] {
            background-color: #28a745;
            color: white;
        }
        
        button[type="submit"]:hover {
            background-color: #218838;
        }
        
        button[style*="background:#b00"] {
            background-color: #dc3545 !important;
            color: white;
        }
        
        button[style*="background:#b00"]:hover {
            background-color: #c82333 !important;
        }
        
        .btn {
            background-color: #6c757d;
            color: white;
        }
        
        .btn:hover {
            background-color: #5a6268;
        }
        
        .recovery-codes {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        
        .recovery-codes h4 {
            color: #155724;
            margin-top: 0;
        }
        
        .recovery-codes ul {
            list-style: none;
            padding: 0;
            margin: 10px 0;
        }
        
        .recovery-codes li {
            background: white;
            padding: 10px;
            margin: 5px 0;
            border-radius: 4px;
            font-family: monospace;
            border: 1px solid #c3e6cb;
        }
        
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
        }
        
        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .form-actions {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container-2fa">
    <h3>إعداد المصادقة الثنائية</h3>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert" style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24;">
            <?php echo e($errors->first()); ?>

        </div>
    <?php endif; ?>

    
    <?php if(!auth()->user()->two_factor_enabled): ?>
        <div>
            <h4> امسح هذا الرمز في تطبيق المصادقة:</h4>

            <?php if(isset($qrSvg)): ?>
                <div class="qr-section"><?php echo $qrSvg; ?></div>
            <?php endif; ?>

            <?php if(isset($secret)): ?>
                <p style="margin: 15px 0 5px 0;">أو أدخل المفتاح يدويًا:</p>
                <div class="secret-key"><strong><?php echo e($secret); ?></strong></div>
            <?php endif; ?>
        </div>

        <form method="POST" action="<?php echo e(route('2fa.enable')); ?>">
            <?php echo csrf_field(); ?>
            <label>أدخل الرمز من التطبيق:</label>
            <input type="text" name="totp_code" required autocomplete="off" placeholder="أدخل الرمز المكون من 6 أرقام">
            <div class="form-actions">
                <button type="submit"> تفعيل المصادقة الثنائية</button>
                <button type="button" class="btn" onclick="goBack()">إلغاء</button>
            </div>
        </form>
    <?php else: ?>
        
        <div class="alert alert-success">
            ✅ المصادقة الثنائية مفعلة حاليًا
        </div>

        <form method="POST" action="<?php echo e(route('2fa.disable')); ?>">
            <?php echo csrf_field(); ?>
            <div class="form-actions">
                <button type="submit" style="background:#b00;color:#fff"> تعطيل المصادقة الثنائية</button>
                <button type="button" class="btn" onclick="goBack()">رجوع</button>
            </div>
        </form>

        <?php if(is_array(auth()->user()->two_factor_recovery_codes) && count(auth()->user()->two_factor_recovery_codes) > 0): ?>
            <div class="recovery-codes">
                <h4> الرموز الاحتياطية - احتفظ بها في مكان آمن:</h4>
                <ul>
                    <?php $__currentLoopData = auth()->user()->two_factor_recovery_codes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($code); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <p style="margin-top: 15px; font-size: 13px; color: #856404;">
                    💡 يمكنك استخدام هذه الرموز لتسجيل الدخول في حال فقدت الوصول لتطبيق المصادقة.
                </p>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
function goBack() {
    // إذا كانت النافذة مفتوحة في تبويب/نافذة جديدة
    if (window.opener) {
        window.close();
    } 
    // إذا كانت النافذة popup (من judge page)
    else if (window.name === '2FASetup') {
        window.close();
    }
    // إذا كان هناك صفحة سابقة في نفس التبويب
    else if (document.referrer) {
        window.history.back();
    }
    // الحالة الافتراضية - الرجوع للوحة التحكم
    else {
        window.location.href = '<?php echo e(route("dashboard")); ?>';
    }
}
</script>

</body>
</html><?php /**PATH C:\Users\LENOVO\legal_system\resources\views/auth/2fa-setup.blade.php ENDPATH**/ ?>