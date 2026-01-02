<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تغيير كلمة المرور</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .toggle-password {
            cursor: pointer;
            font-size: 0.9rem;
            color: #000; /* أسود */
            user-select: none;
        }
        .toggle-password:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh;">

<div class="card shadow-sm" style="width: 420px;">
    <div class="card-body">

        <h5 class="text-center mb-3">تغيير كلمة المرور</h5>

        <p class="text-center text-muted small">
            انتهت صلاحية كلمة المرور الخاصة بك، يرجى تغييرها للمتابعة
        </p>

        
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul class="mb-0 small">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        
        <form method="POST" action="<?php echo e(route('password.update')); ?>">
            <?php echo csrf_field(); ?>

            <!-- كلمة المرور الحالية -->
            <div class="mb-3">
                <label class="form-label">كلمة المرور الحالية</label>
                <div class="input-group">
                    <input type="password"
                           name="current_password"
                           id="current_password"
                           class="form-control"
                           required>
                    <span class="input-group-text toggle-password"
                          onclick="togglePassword('current_password', this)">
                        إظهار
                    </span>
                </div>
            </div>

            <!-- كلمة المرور الجديدة -->
            <div class="mb-3">
                <label class="form-label">كلمة المرور الجديدة</label>
                <div class="input-group">
                    <input type="password"
                           name="new_password"
                           id="new_password"
                           class="form-control"
                           required>
                    <span class="input-group-text toggle-password"
                          onclick="togglePassword('new_password', this)">
                        إظهار
                    </span>
                </div>
            </div>

            <!-- تأكيد كلمة المرور الجديدة -->
            <div class="mb-3">
                <label class="form-label">تأكيد كلمة المرور الجديدة</label>
                <div class="input-group">
                    <input type="password"
                           name="new_password_confirmation"
                           id="new_password_confirmation"
                           class="form-control"
                           required>
                    <span class="input-group-text toggle-password"
                          onclick="togglePassword('new_password_confirmation', this)">
                        إظهار
                    </span>
                </div>
            </div>

            <!-- زر الحفظ -->
            <button type="submit" class="btn btn-dark w-100">
                حفظ كلمة المرور
            </button>

        </form>

    </div>
</div>

<script>
    function togglePassword(id, element) {
        const input = document.getElementById(id);

        if (input.type === 'password') {
            input.type = 'text';
            element.innerText = 'إخفاء';
        } else {
            input.type = 'password';
            element.innerText = 'إظهار';
        }
    }
</script>

</body>
</html><?php /**PATH C:\Users\LENOVO\legal_system\resources\views/auth/change-password.blade.php ENDPATH**/ ?>