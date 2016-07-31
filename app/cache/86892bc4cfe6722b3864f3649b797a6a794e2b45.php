<!DOCTYPE HTML>
<html>
<head>
    <title>Easy CMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="<?php echo AppHelper::BaseUrl(); ?>public/admin/css/bootstrap.min.css" rel='stylesheet' type='text/css'/>
    <link href="<?php echo AppHelper::BaseUrl(); ?>public/admin/css/style.css" rel='stylesheet' type='text/css'/>
    <link href="<?php echo AppHelper::BaseUrl(); ?>public/admin/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo AppHelper::BaseUrl(); ?>public/admin/css/icon-font.min.css" type='text/css'/>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,700,800&subset=latin,latin-ext" rel="stylesheet" type="text/css">
    <script src="<?php echo AppHelper::BaseUrl(); ?>public/admin/js/jquery.js"></script>
</head>

<body class="sign-in-up">
<section>
    <div id="page-wrapper" class="sign-in-wrapper">
        <?php if(AppHelper::isFlash('good')): ?>
            <div class="alert alert-success" role="alert">
                <?php echo e(AppHelper::getFlash('good')); ?>

            </div>
        <?php endif; ?>
        <?php if(AppHelper::isFlash('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo e(AppHelper::getFlash('error')); ?>

            </div>
        <?php endif; ?>
        <script type="text/javascript">
            $(function() {
                if($('.alert').length > 0){
                    setTimeout(function() {
                        $(".alert").fadeOut('slow')
                    }, 5000);
                }
            });
        </script>
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    <!--footer section start-->
    <footer>
        <p>&copy 2016 Easy CMS</p>
    </footer>
    <!--footer section end-->
</section>

<script src="<?php echo AppHelper::BaseUrl(); ?>public/admin/js/jquery.nicescroll.js"></script>
<script src="<?php echo AppHelper::BaseUrl(); ?>public/admin/js/scripts.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="<?php echo AppHelper::BaseUrl(); ?>public/admin/js/bootstrap.min.js"></script>
</body>
</html>