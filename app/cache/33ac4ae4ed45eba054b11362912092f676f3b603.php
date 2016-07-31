<?php $__env->startSection('content'); ?>
<div class="graphs">
    <div class="sign-in-form">
        <div class="sign-in-form-top">
            <p><span>Panel administracyjny</span></p>
        </div>
        <div>
            <?php echo Form::open(AppHelper::UrlTo('/home/login_check')) ?>
                <div class="log-input">
                    <div>
                        <?php echo Form::text('login', "", array('class' => 'user', 'placeholder' => 'Login')) ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="log-input">
                    <div>
                        <?php echo Form::password('haslo', "", array('class' => 'lock', 'placeholder' => 'Hasło')) ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="log-center">
                    <div>
                        <?php echo Form::submit('Zaloguj się') ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
            <?php echo Form::close($data) ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout_login', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>