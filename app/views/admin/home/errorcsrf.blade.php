@extends('admin.layout_login')

@section('content')

    <div class="graphs">
        <div class="error-main">
            <h3><i class="fa fa-exclamation-triangle"></i> <span>CSRF</span></h3>
            <div class="col-xs-7 error-main-left">
                <span>Oops!</span>
                <p>Wystąpił błąd podwójnego przekierowania strony. Jest to zabezpieczenie formularzy przed atakami hakerów.</p>
                <div class="error-btn">
                    <a href="<?php echo AppHelper::UrlTo('/')?>">Strona główna?</a>
                </div>
            </div>
            <div class="col-xs-5 error-main-right">
                <img src="<?php echo AppHelper::BaseUrl(); ?>public/admin/images/7.png" alt=" " class="img-responsive" />
            </div>
            <div class="clearfix"> </div>
        </div>

@stop