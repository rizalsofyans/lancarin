<!DOCTYPE html>
<html>
<head>
	<title><?=get_option("website_title", "Stackposts - Social Marketing Tool")?></title>
	<meta name="description" content="<?=get_option("website_description", "save time, do more, manage multiple social networks at one place")?>" />
    <meta name="keywords" content="<?=get_option("website_keyword", 'social marketing tool, social planner, automation, social schedule')?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="<?=get_option("website_favicon", BASE.'assets/img/favicon.png')?>" />
    <link rel="stylesheet" type="text/css" href="<?=BASE?>themes/aruba/assets/fonts/line-awesome/css/line-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?=BASE?>themes/aruba/assets/fonts/font-awesome/css/font-awesome.min.css">
  
  <meta property="og:title" content="Lancarin - Aplikasi Instagram Marketing serba Otomatis">
  <meta property="og:description" content="Datangkan Followers Tertarget yang siap membeli produk Anda kapanpun dengan mengotomatisasi semua kegiatan Instagram Marketing Anda.">
  <meta property="og:image" content="<?=BASE;?>assets/img/lancarin/lancarin-og.jpg">
  <meta property="og:type" content="website">
  <meta property="og:image:width" content="1200" />
  <meta property="og:image:height" content="630" />
  <meta property="og:url" content="<?=BASE;?>">

        <link rel="stylesheet" href="<?=BASE?>assets/css-landingpage/bootstrap.min.css">
        <!-- Animate Min CSS -->
        <link rel="stylesheet" href="<?=BASE?>assets/css-landingpage/animate.css">
        <!-- IcoFont CSS -->
        <link rel="stylesheet" href="<?=BASE?>assets/css-landingpage/icofont.min.css">
        <!-- Owl Carousel CSS -->
        <link rel="stylesheet" href="<?=BASE?>assets/css-landingpage/owl.carousel.css">
        <!-- Owl Theme Default CSS -->
        <link rel="stylesheet" href="<?=BASE?>assets/css-landingpage/owl.theme.default.min.css">
        <!-- Magnific Popup CSS -->
        <link rel="stylesheet" href="<?=BASE?>assets/css-landingpage/magnific-popup.css">
        <!-- Style CSS -->
        <link rel="stylesheet" href="<?=BASE?>assets/css-landingpage/style.css">
        <!-- Responsive CSS -->
        <link rel="stylesheet" href="<?=BASE?>assets/css-landingpage/responsive.css">
        <!-- Default Color CSS -->
        <link rel="stylesheet" href="<?=BASE?>assets/css-landingpage/color/color-default.css">
    <script type="text/javascript" src="<?=BASE?>assets/plugins/jquery/jquery.min.js"></script>
    <script type="text/javascript">
        var token = '<?=$this->security->get_csrf_hash()?>',
            PATH  = '<?=PATH?>',
            BASE  = '<?=BASE?>';
    </script>
</head>
<body class="aruba">

	<?php if($show){?>
	<!-- Header -->
	<header class="header">
 <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="/"><img style="height:2.25rem !important;" src="<?=BASE;?>assets/img/lancarin/logo-header-colour.png" alt=""></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link active" href="<?=BASE;?>">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?=BASE;?>#features">Fitur</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?=BASE;?>#pricing">Paket</a></li>
                        <?php if(session("uid")){?>
                        <li class="nav-item"><a class="btn btn-primary" style="padding: 5px 10px !important;" href="<?=cn('dashboard')?>">Dashboard</a></li>
                        <?php }else{?>
                      <li class="nav-item"><a class="nav-link" href="<?=PATH.'auth/login'?>"><b>Login</b></a></li>
                        <li class="nav-item"><a class="btn btn-primary btn-signup" style="padding: 5px 10px !important;" href="<?=BASE;?>#pricing">Daftar</a></li>
                        <?php }?>
                    </ul>
                </div>
            </div>
        </nav>
	</header>
	<!-- Header End -->
	<?php }?>