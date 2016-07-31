<?php $__env->startSection('content'); ?>

    <div class="col_12">
        <ol class="breadcrumb">
            <li><a href="<?php echo AppHelper::UrlTo('/home/info')?>">Informacje systemowe</a></li>
            <li class="active">Lista</li>
        </ol>
        <h1>Informacje</h1>
        <div class="editor">

            <p>Wersja PHP: <b><?php echo e(phpversion()); ?></b></p><hr/>
            <p>
                Zainstalowane moduły: <b><?php echo e(implode(", ", get_loaded_extensions())); ?></b>
            </p><hr/>
            <p>Baza danych: <b><?php echo @$orm->info()['driver'] ?>&nbsp;<?php echo @$orm->info()['version'] ?></b></p><hr/>
            <p>
                System: <b><?php echo e(php_uname()); ?></b>
            </p><hr/>
            <p>
                Host: <b><?php echo e(gethostname()); ?></b>
            </p><hr/>
            <p>
                Konfiguracja PHP:<br/>
                <?php
                echo 'display_errors = <b>' . ini_get('display_errors') . "</b><br/>";
                echo 'register_globals = <b>' . ini_get('register_globals') . "</b><br/>";
                echo 'post_max_size = <b>' . ini_get('post_max_size') . "</b><br/>";
                echo 'post_max_size+1 = <b>' . (ini_get('post_max_size')+1) . "</b><br/>";
                echo 'post_max_size in bytes = <b>' . (ini_get('post_max_size'))."</b>";
                ?>
            </p><hr/>
            <p>
                Copyright &copy; <a href="http://dean26.pl" target="_blank">dean26.pl</a> 2016
            </p>
        </div>
        <div class="clearfix"></div>
    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>