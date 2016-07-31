<!DOCTYPE HTML>
<html>
<head>
    <title>Panel administracyjny</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <link href="<?php echo AppHelper::BaseUrl(); ?>public/admin/css/bootstrap.min.css" rel='stylesheet' type='text/css' />
    <link href="<?php echo AppHelper::BaseUrl(); ?>public/admin/css/style.css" rel='stylesheet' type='text/css' />
    <link href="<?php echo AppHelper::BaseUrl(); ?>public/admin/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo AppHelper::BaseUrl(); ?>public/admin/css/icon-font.min.css" type='text/css' />
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,700,800&subset=latin,latin-ext" rel="stylesheet" type="text/css">
    <script src="<?php echo AppHelper::BaseUrl(); ?>public/admin/js/jquery.js"></script>
</head>

<body class="sticky-header left-side-collapsed">
<section>
    <!-- left side start-->
    <div class="left-side sticky-left-side ">
        <div class="logo-icon text-center">
            <a href="<?php echo AppHelper::UrlTo('/') ?>"><i class="lnr lnr-home"></i></a>
        </div>
        <div class="left-side-inner">
            <ul class="nav nav-pills nav-stacked custom-nav">
                <li><a href="<?php echo AppHelper::UrlTo('/') ?>"><i class="lnr lnr-eye"></i><span>Pulpit</span></a></li>
                <li><a href="<?php echo AppHelper::UrlTo('/page') ?>"><i class="lnr lnr-book"></i><span>Podstrony</span></a></li>
                <li><a href="<?php echo AppHelper::UrlTo('/news') ?>"><i class="lnr lnr-list"></i><span>Aktualności</span></a></li>
                <li class="menu-list">
                    <a href="#"><i class="lnr lnr-cog"></i>
                        <span>System</span></a>
                    <ul class="sub-menu-list">
                        @if(Auth::getAdmin()->typ == 1)
                            <li><a href="<?php echo AppHelper::UrlTo('/user') ?>">Uzytkownicy CMS</a></li>
                        @endif
                        <li><a href="<?php echo AppHelper::UrlTo('/home/logi') ?>">Logi</a></li>
                        <li><a href="<?php echo AppHelper::UrlTo('/home/info') ?>">Informacje</a></li>
                    </ul>
                </li>
                <li><a href="<?php echo AppHelper::UrlTo('/home/logout') ?>"><i class="lnr lnr-exit"></i><span>Wyloguj</span></a></li>
            </ul>
        </div>
    </div>
    <!-- left side end-->

    <!-- main content start-->
    <div class="main-content main-content2 main-content2copy">
        <div class="header-section">

            <div class="menu-right">
                <div class="user-panel-top">
                    <div class="profile_details_left">
                        <ul class="nofitications-dropdown">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-envelope"></i><span class="badge">3</span></a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <div class="notification_header">
                                            <h3>You have 3 new messages</h3>
                                        </div>
                                    </li>
                                    <li><a href="#">
                                            <div class="user_img"><img src="<?php echo AppHelper::BaseUrl(); ?>public/admin/images/1.png" alt=""></div>
                                            <div class="notification_desc">
                                                <p>Lorem ipsum dolor sit amet</p>
                                                <p><span>1 hour ago</span></p>
                                            </div>
                                            <div class="clearfix"></div>
                                        </a></li>
                                    <li>
                                        <div class="notification_bottom">
                                            <a href="#">See all messages</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <div class="clearfix"></div>
                        </ul>
                    </div>
                    <div class="profile_details">
                        <ul>
                            <li class="dropdown profile_details_drop">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <div class="profile_img">
                                        @if(Auth::getAdmin()->plik)
                                            <span style="background:url(<?php echo AppHelper::BaseUrl(); ?>public/uploads/users/<?php echo Auth::getAdmin()->plik ?>) no-repeat center"> </span>
                                        @else
                                            <span style="background:url(<?php echo AppHelper::BaseUrl(); ?>public/admin/images/1.png) no-repeat center"> </span>
                                        @endif
                                        <div class="user-name">
                                            <p>{{ Auth::getAdmin()->imie." ".Auth::getAdmin()->nazwisko }}<span>Administrator</span></p>
                                        </div>
                                        <i class="lnr"></i>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu drp-mnu">
                                    <li> <a href="<?php echo AppHelper::UrlTo('/user/edit?id='.Auth::getAdmin()->id) ?>"><i class="fa fa-user"></i>Profil</a> </li>
                                    <li> <a href="<?php echo AppHelper::UrlTo('/home/logout') ?>"><i class="fa fa-sign-out"></i> Wyloguj się</a> </li>
                                </ul>
                            </li>
                            <div class="clearfix"> </div>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <!--notification menu end -->
        </div>
        <!-- //header-ends -->
        <div id="page-wrapper">
            @if(AppHelper::isFlash('good'))
                <div class="alert alert-success" role="alert">
                    {{ AppHelper::getFlash('good') }}
                </div>
            @endif
            @if(AppHelper::isFlash('error'))
                <div class="alert alert-danger" role="alert">
                    {{ AppHelper::getFlash('error') }}
                </div>
            @endif
            <script type="text/javascript">
                $(function() {
                    if($('.alert').length > 0){
                        setTimeout(function() {
                            $(".alert").fadeOut('slow')
                        }, 5000);
                    }
                });
            </script>
            @yield('content')
            <div class="clearfix"> </div>
        </div>
        <!--footer section start-->
        <footer>
            <p>&copy 2016 Easy Admin Panel | {{ AppHelper::MemoryUsage() }}</p>
        </footer>
        <!--footer section end-->
</section>

<script src="<?php echo AppHelper::BaseUrl(); ?>public/admin/js/jquery.nicescroll.js"></script>
<script src="<?php echo AppHelper::BaseUrl(); ?>public/admin/js/scripts.js"></script>
<script src="<?php echo AppHelper::BaseUrl(); ?>public/admin/js/bootstrap.min.js"></script>

</body>
</html>