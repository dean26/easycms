<?php $ilosc = count($lista)?>
<?php if($ilosc > 0): ?>
<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-envelope"></i><span class="badge"><?php echo e($ilosc); ?></span></a>

    <ul class="dropdown-menu">
        <li>
            <div class="notification_header">
                <h3>Ilość nowych wiadomości: <?php echo e($ilosc); ?></h3>
            </div>
        </li>
        <?php foreach($lista as $rec): ?>
        <li><a href="<?php echo AppHelper::UrlTo("/"); ?>">
                <div class="user_img"><img src="<?php echo AppHelper::BaseUrl(); ?>public/admin/images/1.png" alt=""></div>
                <div class="notification_desc">
                    <p><?php echo e(AppHelper::skrdane($rec['tresc'], 30)); ?></p>
                    <p><span><?php echo e(date('d.m.Y H:i', strtotime($rec['created_at']))); ?></span></p>
                </div>
                <div class="clearfix"></div>
            </a>
        </li>
        <?php endforeach; ?>
        <li>
            <div class="notification_bottom">
                <a href="<?php echo AppHelper::UrlTo("/"); ?>">Wszystkie wiadomości</a>
            </div>
        </li>
    </ul>
</li>
<?php endif; ?>