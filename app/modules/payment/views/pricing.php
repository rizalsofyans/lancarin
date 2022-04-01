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
  
        <!-- Bootstrap CSS -->
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
        <link rel="stylesheet" type="text/css" href="<?=BASE?>assets/plugins/font-awesome/css/font-awesome.min.css">
  
    <link rel="stylesheet" type="text/css" href="<?=BASE?>assets/plugins/font-ps/css/pe-icon-7-stroke.css">
    <link rel='stylesheet' type="text/css" href='<?=BASE?>assets/css/custom.css'>
	  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
    <script src='<?=BASE?>assets/js/modernizr-2.8.3.min.js'></script>
<!-----------------------------------------------
/////////////////////////////////////////////////////////
///////// This Website Made by Razzo Digital ////////////
/////////////////////////////////////////////////////////
----------------------------------------------->
</head>
<body>
<div class="main">
        <section id="pricing" class="pricing-area ptb-100">
            <div class="container">
                <div class="section-title">
                    <span>Packages</span>
                    <h3>Pilih Paket yang Anda Mau</h3>
                    <p>Silahkan pilih paket berlangganan Anda di bawah ini. Anda bisa mendapatkan followers tertarget sebanyak yang Anda mau, tidak akan kami batasi.</p>
                </div>
                
                <div class="row">
                    <div class="col-lg-4 col-md-6">
						<div class="pricing-table">
							<span class="icon"><i class="icofont-globe"></i></span>
							<div class="pricing-table-header">
								<h3 class="title">Personal</h3>
								<span class="price-value">Rp129.000<b>/bulan</b></span>
							</div>
							<ul class="pricing-content">
                             <li><b>1 Akun Instagram</b></li>
                             <li>Schedule Posts</li>
                             <li>Bulk Posts</li>
                             <li>Auto Repost <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Maksimal 5 settingan auto repost"></i></li>
                             <li>Auto Follows</li>
                             <li>Auto Comments</li>
                             <li>Auto Direct Messages</li>
                             <li>Auto Unfollows</li>
                             <li>Auto Likes</li>
                             <li>Data Collection (Scrape Data)</li>
                             <li>Manual Activity</li>
                             <li>Image Editor</li>
                             <li>Auto Watermark</li>
                             <li>Random Comment</li>
                             <li>Like for Like (Arisan Likes)</li>
                             <li>Caption Template</li>
                             <li>Custom Link <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Maksimal 1 custom link"></i></li>
                             <li>Cloud Import : Google Drive & Dropbox</li>
                             <li>Max. Storage : <b>1 Gb <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Removable"></i></b></li>
                             <li><b>Dedicated Private Proxy</b></li>
                             <li><b>Grup Whatsapp Eksklusif</b></li>
							</ul>
							<a href="<?=empty(session('uid'))?site_url('auth/login?redirect=payment/d7394fc22455c18ee2eb177bacb0a082'):site_url('payment/d7394fc22455c18ee2eb177bacb0a082');?>" class="btn btn-primary">Pilih Paket</a>
						</div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
						<div class="pricing-table">
							<span class="icon"><i class="icofont-globe"></i></span>
							<div class="pricing-table-header">
								<h3 class="title">Business</h3>
								<span class="price-value">Rp209.000<b>/bulan</b></span>
							</div>
							<ul class="pricing-content">
                             <li><b>5 Akun Instagram</b></li>
                             <li>Schedule Posts</li>
                             <li>Bulk Posts</li>
                             <li>Auto Repost <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Maksimal 25 settingan auto repost"></i></li>
                             <li>Auto Follows</li>
                             <li>Auto Comments</li>
                             <li>Auto Direct Messages</li>
                             <li>Auto Unfollows</li>
                             <li>Auto Likes</li>
                             <li>Data Collection (Scrape Data)</li>
                             <li>Manual Activity</li>
                             <li>Image Editor</li>
                             <li>Auto Watermark <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Mendukung watermark per akun instagram"></i></li>
                             <li>Random Comment</li>
                             <li>Like for Like (Arisan Likes)</li>
                             <li>Caption Template</li>
                             <li>Custom Link <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Maksimal 5 custom link"></i></li>
                             <li>Cloud Import : Google Drive & Dropbox</li>
                            <li>Max. Storage : <b>2.5 Gb <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Removable"></i></b></li>
                             <li><b>Dedicated Private Proxy</b></li>
                             <li><b>Grup Whatsapp Eksklusif</b></li>
							</ul>
							<a href="<?=empty(session('uid'))?site_url('auth/login?redirect=payment/2c327cb5ab20f86cc0ea9cae47515da1'):site_url('payment/2c327cb5ab20f86cc0ea9cae47515da1');?>" class="btn btn-primary">Pilih Paket</a>
						</div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 offset-lg-0 offset-md-3">
						<div class="pricing-table">
							<span class="icon"><i class="icofont-globe"></i></span>
							<div class="pricing-table-header">
								<h3 class="title">Standard</h3>
								<span class="price-value">Rp169.000<b>/bulan</b></span>
							</div>
							<ul class="pricing-content">
                             <li><b>3 Akun Instagram</b></li>
                             <li>Schedule Posts</li>
                             <li>Bulk Posts</li>
                             <li>Auto Repost <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Maksimal 15 settingan auto repost"></i></li>
                             <li>Auto Follows</li>
                             <li>Auto Comments</li>
                             <li>Auto Direct Messages</li>
                             <li>Auto Unfollows</li>
                             <li>Auto Likes</li>
                             <li>Data Collection (Scrape Data)</li>
                             <li>Manual Activity</li>
                             <li>Image Editor</li>
                             <li>Auto Watermark <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Mendukung watermark per akun instagram"></i></li>
                             <li>Random Comment</li>
                             <li>Like for Like (Arisan Likes)</li>
                             <li>Caption Template</li>
                             <li>Custom Link <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Maksimal 3 custom link"></i></li>
                             <li>Cloud Import : Google Drive & Dropbox</li>
                             <li>Max. Storage : <b>1.5 Gb <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Removable"></i></b></li>
                             <li><b>Dedicated Private Proxy</b></li>
                             <li><b>Grup Whatsapp Eksklusif</b></li>
							</ul>
							<a href="<?=empty(session('uid'))?site_url('auth/login?redirect=payment/9088ff7e41e726e5e2bf7c1352c22340'):site_url('payment/9088ff7e41e726e5e2bf7c1352c22340');?>" class="btn btn-primary">Pilih Paket</a>
						</div>
                    </div>
                </div>
                <div style="color:#666666;margin-top:4rem!important;text-align: center;">
                <img style="max-width:100px;" src="assets/img/lancarin/payment-logo-baru.png" width="100px">
                <p style="font-size:12px;margin-top: 0.2rem;">Pembayaran Anda akan diverifikasi secara otomatis</p>
                          <div class="detail" style="margin-top:20px;text-align:center;">
        <a href="<?=site_url('profile/user_payment_history');?>"><button class="btn btn-primary" style="background-color: #337ab7 !important; border-color: #2e6da4 !important;"><i class="icofont-user"></i>&nbsp;Profile</button></a>
		    <a href="<?=site_url('custom_link');?>"><button class="btn btn-success" style="background-color: #28a745 !important; border-color: #28a745 !important;"><i class="icofont-link"></i>&nbsp;Custom Link</button></a>
        </div>
        
        </div>
            </div>
            </div>
        </section>
</div>
  
   <!-- Jquery Min JS -->
        <script src="<?=base_url('assets/js-landingpage/jquery.min.js');?>"></script>
        <!-- Popper Min JS -->
        <script src="<?=base_url('assets/js-landingpage/popper.min.js');?>"></script>
        <!-- Bootstrap Min JS -->
        <script src="<?=base_url('assets/js-landingpage/bootstrap.min.js');?>"></script>
        <!-- Owl Carousel JS -->
        <script src="<?=base_url('assets/js-landingpage/owl.carousel.min.js');?>"></script>
        <!-- Counterup Min JS -->
        <script src="<?=base_url('assets/js-landingpage/jquery.counterup.min.js');?>"></script>
        <!-- Magnific Min JS -->
        <script src="<?=base_url('assets/js-landingpage/jquery.magnific-popup.min.js');?>"></script>
        <!-- Waypoints Min JS -->
        <script src="<?=base_url('assets/js-landingpage/waypoints.min.js');?>"></script>
        <!-- Form Validator Min JS -->
        <script src="<?=base_url('assets/js-landingpage/form-validator.min.js');?>"></script>
        <!-- Contact Form Min JS -->
        <script src="<?=base_url('assets/js-landingpage/contact-form-script.js');?>"></script>
		    <!-- Fox Map JS FILE -->
        <script src="<?=base_url('assets/js-landingpage/axolot-map.js');?>"></script>
        <!-- Main JS -->
        <script src="<?=base_url('assets/js-landingpage/main.js');?>"></script>
  <script src='assets/js/main-landing-page.js'></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script src='assets/js/map.js'></script>
<script>
$(function(){
	
});

</script>



</body>
</html>

