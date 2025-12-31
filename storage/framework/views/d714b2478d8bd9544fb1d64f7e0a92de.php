<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title><?php echo $__env->yieldContent('title', 'صفحة الطابعة'); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
<?php echo $__env->yieldContent('styles'); ?>
</head>
<body>

<div class="court-bar">محكمة بداية عمان</div>

<nav class="navbar">
  <div class="user-info">الطابعة / <?php echo e(Auth::user()->full_name ?? 'مستخدم'); ?></div>
  <?php echo $__env->yieldContent('navbar-menu'); ?>
</nav>

<?php echo $__env->make('components.entry-search-bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->yieldContent('content'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<?php echo $__env->yieldContent('scripts'); ?>

</body>
</html>
<?php /**PATH C:\Users\DELL\Desktop\legal_system3\resources\views/layouts/typist-layout.blade.php ENDPATH**/ ?>