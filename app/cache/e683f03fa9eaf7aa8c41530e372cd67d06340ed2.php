<?php $__env->startSection('content'); ?>

    <div class="graphs">
        <div class="error-main">
            <h3><i class="fa fa-exclamation-triangle"></i> <span>500</span></h3>
            <div class="col-xs-7 error-main-left">
                <span>Oops!</span>
                <p>Wystąpił wewnętrzny błąd serwera.</p>
                <div class="error-btn">
                    <a href="<?php echo AppHelper::UrlTo('/')?>">Strona główna?</a>
                </div>
            </div>
            <div class="col-xs-5 error-main-right">
                <img src="<?php echo AppHelper::BaseUrl(); ?>public/admin/images/7.png" alt=" " class="img-responsive" />
            </div>
            <div class="clearfix"> </div>
        </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout_login', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>