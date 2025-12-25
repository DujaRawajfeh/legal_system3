

<?php if(!$enabled): ?>
    
    <div>
        <h4 style="font-size: 1rem; margin: 15px 0 10px 0; color: #000;">امسح هذا الرمز في تطبيق المصادقة:</h4>

        <?php if(isset($qrSvg)): ?>
            <div class="qr-code"><?php echo $qrSvg; ?></div>
        <?php endif; ?>

        <?php if(isset($secret)): ?>
            <p>أو أدخلي المفتاح يدويًا:</p>
            <div class="secret-key"><strong><?php echo e($secret); ?></strong></div>
        <?php endif; ?>
    </div>

    <form id="enable2FAForm" onsubmit="enable2FA(); return false;">
        <label>أدخلي الرمز من التطبيق:</label>
        <input type="text" id="totp_code" name="totp_code" required autocomplete="off">
        <div class="modal-2fa-footer">
            <button type="submit" class="btn-2fa-enable">تفعيل</button>
            <button type="button" class="btn-2fa-cancel" onclick="close2FAModal()">إلغاء</button>
        </div>
    </form>
<?php else: ?>
    
    <div class="alert-2fa alert-2fa-success">
        ✅ المصادقة الثنائية مفعلة حاليًا
    </div>

    <form id="disable2FAForm" onsubmit="disable2FA(); return false;">
        <div class="modal-2fa-footer">
            <button type="submit" class="btn-2fa-disable">تعطيل المصادقة الثنائية</button>
            <button type="button" class="btn-2fa-cancel" onclick="close2FAModal()">إلغاء</button>
        </div>
    </form>

    <?php if(is_array(auth()->user()->two_factor_recovery_codes) && count(auth()->user()->two_factor_recovery_codes) > 0): ?>
        <div class="recovery-codes">
            <h4>⚠️ الرموز الاحتياطية - احتفظ بها في مكان آمن:</h4>
            <ul>
                <?php $__currentLoopData = auth()->user()->two_factor_recovery_codes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($code); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php /**PATH C:\Users\DELL\Desktop\legal_system3\resources\views/auth/2fa-setup-content.blade.php ENDPATH**/ ?>