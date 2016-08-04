<?php $ilosc = count($lista)?>
@if($ilosc > 0)
<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-envelope"></i><span class="badge">{{ $ilosc }}</span></a>

    <ul class="dropdown-menu">
        <li>
            <div class="notification_header">
                <h3>Ilość nowych wiadomości: {{ $ilosc }}</h3>
            </div>
        </li>
        @foreach($lista as $rec)
        <li><a href="<?php echo AppHelper::UrlTo("/"); ?>">
                <div class="user_img"><img src="<?php echo AppHelper::BaseUrl(); ?>public/admin/images/1.png" alt=""></div>
                <div class="notification_desc">
                    <p>{{{ AppHelper::skrdane($rec['tresc'], 30) }}}</p>
                    <p><span>{{ date('d.m.Y H:i', strtotime($rec['created_at'])) }}</span></p>
                </div>
                <div class="clearfix"></div>
            </a>
        </li>
        @endforeach
        <li>
            <div class="notification_bottom">
                <a href="<?php echo AppHelper::UrlTo("/"); ?>">Wszystkie wiadomości</a>
            </div>
        </li>
    </ul>
</li>
@endif