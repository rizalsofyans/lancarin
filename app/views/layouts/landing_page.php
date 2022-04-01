<!DOCTYPE html>
<html>
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Required meta tags -->
    <title><?=get_option("website_title", "Stackposts - Social Marketing Tool")?></title>
    <meta name="description" content="<?=get_option("website_description", "save time, do more, manage multiple social networks at one place")?>" />
    <meta name="keywords" content="<?=get_option("website_keyword", 'social marketing tool, social planner, automation, social schedule')?>"/>
    <meta name="author" content="lancarin.com">
    <!-- schema tag -->
  <meta property="og:title" content="Lancarin - Aplikasi Instagram Marketing serba Otomatis">
  <meta property="og:description" content="Datangkan Followers Tertarget yang siap membeli produk Anda kapanpun dengan mengotomatisasi semua kegiatan Instagram Marketing Anda.">
  <meta property="og:image" content="<?=BASE;?>assets/img/lancarin/lancarin-og.jpg">
  <meta property="og:type" content="website">
  <meta property="og:image:width" content="1200" />
  <meta property="og:image:height" content="630" />
  <meta property="og:url" content="<?=BASE;?>">
  <!-- SEO Tag -->
  <meta name="google-site-verification" content="08xfjzHpvPNBnCQ87KfUaRm5YX_srHVqrh3H7oQlk2U" />
      
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
		<style>
		@import url("https://fonts.googleapis.com/css?family=Open+Sans:400,600");
		.custom-social-proof {
		  position: fixed;
		  bottom: 20px;
		  left: 20px;
		  z-index: 9999999999999 !important;
		  font-family: 'Open Sans', sans-serif;
		  display: none; /* Uncoment This Line to Hide Initially*/
		}
		.custom-social-proof .custom-notification {
		  width: 320px;
		  border: 0;
		  text-align: left;
		  z-index: 99999;
		  box-sizing: border-box;
		  font-weight: 400;
		  border-radius: 6px;
		  box-shadow: 2px 2px 10px 2px rgba(11, 10, 10, 0.2);
		  background-color: #fff;
		  position: relative;
		  cursor: pointer;
		}
		.custom-social-proof .custom-notification .custom-notification-container {
		  display: flex !important;
		  align-items: center;
		  height: 80px;
		}
		.custom-social-proof .custom-notification .custom-notification-container .custom-notification-image-wrapper img {
		  max-height: 75px;
		  width: 90px;
		  overflow: hidden;
		  border-radius: 6px 0 0 6px;
		  object-fit: cover;
		}
		.custom-social-proof .custom-notification .custom-notification-container .custom-notification-content-wrapper {
		  margin: 0;
		  height: 100%;
		  color: gray;
		  padding-left: 20px;
		  padding-right: 20px;
		  border-radius: 0 6px 6px 0;
		  flex: 1;
		  display: flex !important;
		  flex-direction: column;
		  justify-content: center;
		}
		.custom-social-proof .custom-notification .custom-notification-container .custom-notification-content-wrapper .custom-notification-content {
		  font-family: inherit !important;
		  margin: 0 !important;
		  padding: 0 !important;
		  font-size: 14px;
		  line-height: 16px;
		}
		.custom-social-proof .custom-notification .custom-notification-container .custom-notification-content-wrapper .custom-notification-content small {
		  margin-top: 3px !important;
		  display: block !important;
		  font-size: 12px !important;
		  opacity: .8;
		}
		.custom-social-proof .custom-notification .custom-close {
		  position: absolute;
		  top: 8px;
		  right: 8px;
		  height: 12px;
		  width: 12px;
		  cursor: pointer;
		  transition: .2s ease-in-out;
		  transform: rotate(45deg);
		  opacity: 0;
		}
		.custom-social-proof .custom-notification .custom-close::before {
		  content: "";
		  display: block;
		  width: 100%;
		  height: 2px;
		  background-color: gray;
		  position: absolute;
		  left: 0;
		  top: 5px;
		}
		.custom-social-proof .custom-notification .custom-close::after {
		  content: "";
		  display: block;
		  height: 100%;
		  width: 2px;
		  background-color: gray;
		  position: absolute;
		  left: 5px;
		  top: 0;
		}
		.custom-social-proof .custom-notification:hover .custom-close {
		  opacity: 1;
		}

		</style>
      
<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '2151003491877450');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=2151003491877450&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
      
    </head>
<body>
<!-- Live Chat Widget powered by https://keyreply.com/chat/ -->
<!-- Advanced options: -->
<!-- data-align="left" -->
<!-- data-overlay="true" -->
<script data-align="right" data-overlay="false" id="keyreply-script" src="//lancarin.com/assets/js-landingpage/widgetschat.js" data-color="#3F51B5" data-apps="JTdCJTIyd2hhdHNhcHAlMjI6JTIyNjI4MTkwNTY3OTczOSUyMiwlMjJmYWNlYm9vayUyMjolMjI0NDQ2ODUwNzkyNzI0OTAlMjIlN0Q="></script>
  
        <!--
        <div class="preloader">
            <div class="loader">
                <div class="spinner">
                    <div class="double-bounce1"></div>
                    <div class="double-bounce2"></div>
                </div>
            </div>
        </div>
         -->
        
        <!-- Start Navbar Area -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="/"><img style="height:2.25rem !important;" src="assets/img/lancarin/logo-header-colour.png" alt=""></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link active" href="#home">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="#features">Fitur</a></li>
                        <li class="nav-item"><a class="nav-link" href="#pricing">Paket</a></li>
                        <li class="nav-item"><a class="nav-link" href="https://blog.lancarin.com">Blog</a></li>
                        <?php if(session("uid")){?>
                        <li class="nav-item"><a class="btn btn-primary" style="padding: 5px 10px !important;" href="<?=cn('dashboard')?>">Dashboard</a></li>
                        <?php }else{?>
                      <li class="nav-item"><a class="nav-link" href="<?=PATH.'auth/login'?>"><b>Login</b></a></li>
                        <li class="nav-item"><a class="btn btn-primary btn-signup" style="padding: 5px 10px !important;" href="#pricing">Daftar</a></li>
                        <?php }?>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navbar Area -->
        
        <!-- Start Main Banner Area -->
        <div id="home" class="main-banner">
		
			<div class="creative-bg"></div>
		
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="main-banner-content">
                                    <h1>Aplikasi Instagram Marketing serba Otomatis</h1>
                                    <p>Datangkan Followers Tertarget yang siap membeli produk Anda kapanpun dengan mengotomatisasi semua kegiatan Instagram Marketing Anda.</p>
                                    <a href="#pricing" class="btn btn-primary btn-signup">Daftar Sekarang</a>
                                </div>
                            </div>
                            
                            <div class="col-lg-6 col-md-6">
                                <div class="banner-img">
                                    <img src="assets/img/lancarin/main-banner.png" alt="img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			
			
            <div class="bg-bottom"></div>
			
        </div>
        <!-- End Main Banner Area -->
        <!-- Start Testimonials Area -->
        <section class="testimonials-area bg-gray ptb-100">
           
            <div class="bg-top"></div>
            <div class="bg-bottom"></div>
           
            <div class="container">
                <div class="section-title">
                    <span>Testimonials</span>
                    <h3>Apa yang Mereka Katakan</h3>
                    <p>Lancarin telah membantu <b>1.738</b> Bisnis di Instagram dalam meningkatkan Followers tertarget dan Engagements.</p>
                </div>
                
                <div class="row">
                    <div class="testimonials-slides">
                      <div class="col-lg-12 col-md-12">
                            <div class="testimonials-item">
                                <div class="client-info">
                                    <div class="img">
                                        <img src="https://avatars.io/instagram/carajualan/medium" alt="client">
                                    </div>
                                    
                                    <div class="client-title">
                                        <h4>Fikri W</h4>
                                        <span>Owner <a href="https://instagram.com/carajualan">@carajualan</a></span>
                                    </div>
                                </div>
                                <p>Saya pakai Tools ini karena sesuai dengan <b>kebutuhan saya</b>, upload konten dari PC dan untuk running Instagram Marketing Automation.</p>
                                
                                 
                            </div>
                        </div>
                      
                        <div class="col-lg-12 col-md-12">
                            <div class="testimonials-item">
                                <div class="client-info">
                                    <div class="img">
                                        <img src="https://avatars.io/instagram/thrift.lux/medium" alt="client">
                                    </div>
                                    
                                    <div class="client-title">
                                        <h4>Kemal</h4>
                                        <span>Owner <a href="https://instagram.com/thrift.lux">@thrift.lux</a></span>
                                    </div>
                                </div>
                                <p>Gue ngerasain banget perbedaan dari sebelum make Lancarin.com dimana gue harus melakukan semua marketing IG secara manual dan itu melelahkan. Sangat mudah untuk nge-reach target market gue dengan fitur Lancarin.com yang <b>easy to use yet so effective!</b></p>
                                
                                 
                            </div>
                        </div>
                        
                        <div class="col-lg-12 col-md-12">
                            <div class="testimonials-item">
                                <div class="client-info">
                                    <div class="img">
                                        <img src="https://avatars.io/instagram/waktusenja_/medium" alt="client">
                                    </div>
                                    
                                    <div class="client-title">
                                        <h4>Dimas Ridho W</h4>
                                        <span>Owner <a href="https://instagram.com/waktusenja_">@waktusenja_</a></span>
                                    </div>
                                </div>
                                <p>Pake Lancarin.com ajib banget euy. Peningkatan followersnya kerasa banget, jadi makin <b>banyak interaksi</b> dan <b>memunculkan leads baru</b>. Thankyou banget Lancarin! Sukses terus!</p>
                                
                            </div>
                        </div>
                        
                          <div class="col-lg-12 col-md-12">
                            <div class="testimonials-item">
                                <div class="client-info">
                                    <div class="img">
                                        <img src="https://avatars.io/instagram/s2collection.id/medium" alt="client">
                                    </div>
                                    
                                    <div class="client-title">
                                        <h4>Suci</h4>
                                        <span>Owner <a href="https://instagram.com/s2collection.id">@s2collection.id</a></span>
                                    </div>
                                </div>
                                <p>Seneng banget <b>setiap hari followers ig nambah terus</b>, dan gak perlu repot dan capek lagi untuk posting, like, comment, repost karena semua udah otomatis. Terima kasih lancarin.com!</p>
                                
                                 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Testimonials Area -->

        <!-- Start About Area -->
        <section id="overview" class="about-area ptb-100">
            <div class="container">
                <div class="section-title">
                    <span>Overview</span>
                    <h3>Lancarin Bekerja untuk Anda</h3>
                    <p>Lancarin adalah aplikasi web-based yang membantu dan memudahkan dalam <b>mengelola</b> dan <b>memasarkan</b> akun instagram Anda.</p>
                </div>
                
                  <div class="row mt-100">
                    <div class="col-lg-4 col-md-4">
                        <div class="about-text mt-0">
                            <h3>Saatnya Jadwalkan Postingan Anda</h3>
                            <p>Jadikan akun Instagram Anda tetap aktif dalam <b>24 jam seminggu</b> dengan menjadwalkan postingan Anda.</p>
                            <ul>
                                <li><i class="icofont-ui-check"></i> Support export media dari Google Drive dan Dropbox</li>
                                <li><i class="icofont-ui-check"></i> Support postingan berupa Feeds, Stories, dan Carousel</li>
                                <li><i class="icofont-ui-check"></i> Support posting ke multi akun</li>
                                <li><i class="icofont-ui-check"></i> Support template caption</li>
                                <li><i class="icofont-ui-check"></i> Repost Saved Collection akun Instagram Anda</li>
                                <li><i class="icofont-ui-check"></i> Cari dan repost produk dari Tokopedia, Bukalapak, dan Shopee</li>
                                <li><i class="icofont-ui-check"></i> Cari dan repost postingan milik orang lain di Instagram</li>
                                <li><i class="icofont-ui-check"></i> <span style="color:red;"><b>New!</b></span> Support Related Hastag</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-lg-8 col-md-8" style="text-align:right;">
                      <video style="max-width: 100%;" width="700" controls="" loop="" playsinline="">
                        <source src="assets/video/preview-schedule-post.mp4" type="video/mp4">
                      </video>
                    </div>
                </div>
              
               <div class="row mt-100">
                    <div class="col-lg-4 col-md-4">
                        <div class="about-text mt-0">
                            <h3>Dropship jadi Mudah dengan Auto Repost</h3>
                          <p>Fitur ini membantu Anda seorang Dropshipper/Reseller untuk Auto Repost postingan milik akun yang Anda targetkan.</p>
                            <ul>
                              <li><i class="icofont-ui-check"></i> Support Auto Repost dari sesama akun Instagram</li>
                              <li><i class="icofont-ui-check"></i> Support Auto Repost dari Toko di Shopee, Tokopedia, dan Bukalapak</li>
                              <li><i class="icofont-ui-check"></i> Mark Up / Mark Down harga secara otomatis</li>
                                <li><i class="icofont-ui-check"></i> Mengganti Nomor Handphone secara otomatis</li>
                                <li><i class="icofont-ui-check"></i> Mengganti kata-kata yang Anda inginkan secara otomatis</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-lg-8 col-md-8" style="text-align:right;">
                      <video style="max-width: 100%;" width="700" controls="" loop="" playsinline="">
                        <source src="assets/video/preview-auto-repost.mp4" type="video/mp4">
                      </video>
                    </div>
                </div>
              
                <div class="row mt-100">
                    <div class="col-lg-4 col-md-4">
                        <div class="about-text mt-0">
                            <h3>Tinggalkan Pasang Watermark Satu-per-satu</h3>
                            <p>Tinggalkan aktifitas pasang watermark manual satu per satu pada gambar postingan Anda.</p>
                            <ul>
                                <li><i class="icofont-ui-check"></i> Support Watermark per akun Instagram</li>
                                <li><i class="icofont-ui-check"></i> Support ganti Watermark tanpa batas</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-lg-8 col-md-8" style="text-align:right;">
                      <video style="max-width: 100%;" width="700" controls="" loop="" playsinline="">
                        <source src="assets/video/preview-auto-watermark.mp4" type="video/mp4">
                      </video>
                    </div>
                </div>

                <div class="row mt-100">
                    <div class="col-lg-4 col-md-4">
                        <div class="about-text mt-0">
                            <h3>Dapatkan Lebih Banyak Followers dan Engagements</h3>
                            <p>Dapatkan Followers Tertarget sesuai dengan target market Anda dengan melakukan aktifitas Follows, Likes, Comments, Direct Messages, dan Unfollows ke target market Anda secara otomatis.</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-8 col-md-8" style="text-align:right;">
                      <video style="max-width: 100%;" width="700" controls="" loop="" playsinline="">
                        <source src="assets/video/preview-auto-activity.mp4" type="video/mp4">
                      </video>
                    </div>
                </div>
              
               <div class="row mt-100">
                    <div class="col-lg-4 col-md-4">
                        <div class="about-text mt-0">
                            <h3>Scrape Data sebanyak yang Anda mau</h3>
                            <p>Dapatkan data dari akun target sebanyak yang Anda mau. Data-data yang bisa Anda dapatkan seperti :</p>
                           <ul>
                                <li><i class="icofont-ui-check"></i> Followers</li>
                                <li><i class="icofont-ui-check"></i> Followings</li>
                                <li><i class="icofont-ui-check"></i> Likers</li>
                                <li><i class="icofont-ui-check"></i> Commenters</li>
                                <li><i class="icofont-ui-check"></i> User Posts (Instagram & Marketplace)</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-lg-8 col-md-8" style="text-align:right;">
                      <video style="max-width: 100%;" width="700" controls="" loop="" playsinline="">
                        <source src="assets/video/preview-data-collection-new.mp4" type="video/mp4">
                      </video>
                    </div>
                </div>
              
                 <div class="row mt-100">
                    <div class="col-lg-4 col-md-4">
                        <div class="about-text mt-0">
                            <h3>Eksekusi Data yang telah Anda miliki</h3>
                            <p>Lakukan eksekusi secara otomatis pada data hasil scrape Anda. Tipe action yang dapat Anda lakukan adalah :</p>
                           <ul>
                                <li><i class="icofont-ui-check"></i> Repost</li>
                                <li><i class="icofont-ui-check"></i> Follow</li>
                                <li><i class="icofont-ui-check"></i> Unfollow</li>
                                <li><i class="icofont-ui-check"></i> Like</li>
                                <li><i class="icofont-ui-check"></i> Comment</li>
                                <li><i class="icofont-ui-check"></i> Detele Post</li>
                                <li><i class="icofont-ui-check"></i> Direct Message</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-lg-8 col-md-8" style="text-align:right;">
                      <video style="max-width: 100%;" width="700" controls="" loop="" playsinline="">
                        <source src="assets/video/preview-manual-activity.mp4" type="video/mp4">
                      </video>
                    </div>
                </div>
              
               <div class="row mt-100">
                    <div class="col-lg-4 col-md-4">
                        <div class="about-text mt-0">
                            <h3>Edit Gambar dengan Mudah</h3>
                            <p>Tidak perlu lagi install aplikasi Image Editor. Edit Gambar yang ingin Anda posting melalui Lancarin dengan Fitur Image Editor Terbaik.</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-8 col-md-8" style="text-align:right;">
                      <video style="max-width: 100%;" width="700" controls="" loop="" playsinline="">
                        <source src="assets/video/preview-image-editor.mp4" type="video/mp4">
                      </video>
                    </div>
                </div>
              
               <div class="row mt-100">
                    <div class="col-lg-4 col-md-4">
                        <div class="about-text mt-0">
                            <h3>Dapatkan Ratusan Likes di Setiap Postingan</h3>
                            <p>Dapatkan GRATIS ratusan hingga ribuan likes pada setiap postingan terbaru Anda dengan mengikuti program like for like yang pesertanya adalah sesama pengguna Lancarin.</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-8 col-md-8" style="text-align:right;">
                      <video style="max-width: 100%;" width="700" controls="" loop="" playsinline="">
                        <source src="assets/video/preview-like-for-like.mp4" type="video/mp4">
                      </video>
                    </div>
                </div>
              
             <div class="row mt-100">
                    <div class="col-lg-4 col-md-4">
                        <div class="about-text mt-0">
                            <h3>Mudahkan Pelanggan untuk Menghubungi Anda (Bioku.id)</h3>
                          <p>Bioku.id adalah Fitur Custom Link milik Lancarin yang dapat digunakan untuk memudahkan pelanggan menghubungi Anda melalui link di bio Instagram Anda ke channel promosi Anda seperti WhatsApp, Line, Website, dan Marketplace.</p>
                            <ul>
                                <li><i class="icofont-ui-check"></i> Support integrasi Facebook Pixel</li>
                              <li><i class="icofont-ui-check"></i> Bisa kustom warna nama dan background channel</li>
                                <li><i class="icofont-ui-check"></i> Menampilkan jumlah view custom link</li>
                             
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-lg-8 col-md-8" style="text-align:right;">
                      <video style="max-width: 100%;" width="700" controls="" loop="" playsinline="">
                        <source src="assets/video/preview-custom-link-feature.mp4" type="video/mp4">
                      </video>
                    </div>
                </div>
              
                <div class="row mt-100">
                    <div class="col-lg-4 col-md-4">
                        <div class="about-text mt-0">
                            <h3>Kelola Semua Akun Instagram Anda</h3>
                            <p>Dengan Lancarin, Anda dapat mengelola semua akun Instagram yang Anda punya dalam satu platform. Schedule Post, Repost, Auto Activity, Scrape Data, dan sebagainya cukup tinggal klak-klik saja tanpa perlu buka banyak tab browser atau aplikasi lain.</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-8 col-md-8" style="text-align:right;">
                        <div class="about-img">
                            <img style="max-width: 100%;" width="700" src="assets/img/lancarin/manage-account.png" alt="about">
                        </div>
                    </div>
                </div>
              
            </div>
            </div>
        </section>
        <!-- End About Area -->
        
        <!-- Start Services Area -->
        <section id="features" class="services-area bg-gray ptb-100">
            <div class="container">
                <div class="section-title">
                    <span>Features</span>
                    <h3>Fitur-fitur serba Otomatis Lancarin</h3>
                    <p>Semua fitur yang kami sediakan adalah fitur yang saat ini Anda butuhkan untuk <b>meningkatkan omzet</b> Bisnis Anda.</p>
                </div>
                
                <div class="row">
					<div class="col-lg-4 col-md-6">
						<div class="single-services text-center">
							<img style="height:65px !important;" src="assets/img/lancarin/schedule-post.png" alt="">
							<h3>Schedule Posts</h3>
							<p>Jadwalkan postingan dengan media milik Anda sendiri atau media milik orang lain (repost). Mendukung repost dari sesama Instagram dan Marketplace. Mendukung menjadwalkan postingan multi akun Instagram.<br/><br/></p>
						</div>
					</div>
					
					<div class="col-lg-4 col-md-6">
						<div class="single-services text-center">
							<img style="height:65px !important;" src="assets/img/lancarin/bulk-posts.png" alt="">
							<h3>Bulk Posts</h3>
							<p>Fitur untuk menjadwalkan banyak postingan sekaligus dengan caption yang sama/berbeda dan waktu posting yang sama/berbeda.<br/><br/><br/><br/></p>
						</div>
					</div>
					
					<div class="col-lg-4 col-md-6">
						<div class="single-services text-center">
							<img style="height:65px !important;" src="assets/img/lancarin/auto-repost.png" alt="">
							<h3>Auto Repost</h3>
							<p>Fitur full-auto-repost dari Instagram dan/atau Marketplace ke Akun Instagram Anda. Jadi, setiap target Anda update posting maka Akun Instagram Anda juga akan update. Mendukung find & replace harga, no hp, dan kata-kata yang Anda inginkan.</p>
						</div>
					</div>
					
					<div class="col-lg-4 col-md-6">
						<div class="single-services text-center">
							<img style="height:65px !important;" src="assets/img/lancarin/auto-follow.png" alt="">
							<h3>Auto Follows</h3>
							<p>Otomatis follow akun tertarget berdasarkan pilihan target: followers akun tertentu / kompetitor, following akun tertentu, lokasi tertentu, commenters akun tertentu, likers akun tertentu, dan hastag tertentu.</p>
						</div>
					</div>

                    <div class="col-lg-4 col-md-6">
                        <div class="single-services text-center">
                            <img style="height:65px !important;" src="assets/img/lancarin/auto-comments.png" alt="">
                            <h3>Auto Comments</h3>
                            <p>Otomatis comment pada postingan milik akun tertarget berdasarkan pilihan target: followers akun tertentu / kompetitor, following akun tertentu, lokasi tertentu, commenters akun tertentu, likers akun tertentu, dan hastag tertentu.</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="single-services text-center">
                            <img style="height:65px !important;" src="assets/img/lancarin/auto-dm.png" alt="">
                            <h3>Auto Direct Messages (DM)</h3>
                            <p>Fitur ini membantu Anda mengirimkan DM secara otomatis kepada akun berdasarkan pilihan target: followers akun tertentu / kompetitor, following akun tertentu, lokasi tertentu, commenters akun tertentu, likers akun tertentu, dan hastag tertentu.</p>
                        </div>
                    </div>		

					<div class="col-lg-4 col-md-6">
						<div class="single-services text-center">
							<img style="height:65px !important;" src="assets/img/lancarin/auto-unfollow.png" alt="">
							<h3>Auto Unfollows</h3>
							<p>Otomatis unfollow akun tertarget berdasarkan pilihan: user yang telah anda follow dalam waktu tertentu dan user yang tidak follback setelah waktu tertentu.<br/><br/><br/><br/><br/></p>
						</div>
					</div>
					   
					<div class="col-lg-4 col-md-6">
						<div class="single-services text-center">
							<img style="height:65px !important;" src="assets/img/lancarin/auto-like.png" alt="">
							<h3>Auto Likes</h3>
							<p>Otomatis like postingan milik akun tertarget berdasarkan pilihan target: followers akun tertentu / kompetitor, following akun tertentu, lokasi tertentu, commenters akun tertentu, likers akun tertentu, dan hastag tertentu.<br/><br/><br/></p>
						</div>
					</div>
                  
                     <div class="col-lg-4 col-md-6">
                        <div class="single-services text-center">
                            <img style="height:65px !important;" src="assets/img/lancarin/data-collection.png" alt="">
                            <h3>Data Collection (Scrape Data)</h3>
                            <p>Scrape Data seperti Followers, Followings, Likers, Comments dan Commenters dari Akun Target atau Postingan Target dengan mudah. Nantinya, data yang telah Anda miliki dapat Anda eksekusi seperti Follow, Unfollow, Direct Message, Like, dan Comment secara otomatis juga melalui fitur Manual Activity Lancarin.<br/></p>
                        </div>
                    </div>
                       
                    <div class="col-lg-4 col-md-6">
                        <div class="single-services text-center">
                            <img style="height:65px !important;" src="assets/img/lancarin/manual-activity.png" alt="">
                            <h3>Manual Activity</h3>
                            <p>Fitur ini membantu Anda untuk melakukan eksekusi ke data yang telah Anda miliki. Eksekusi yang dapat Anda lakukan seperti Auto Follows, Auto Unfollows, Auto Likes, Auto Comments, Auto Direct Messages, dan Auto Delete Post.<br/><br/><br/></p>
                        </div>
                    </div>
                  
                     <div class="col-lg-4 col-md-6">
                        <div class="single-services text-center">
                            <img style="height:65px !important;" src="assets/img/lancarin/image-editor.png" alt="">
                            <h3>Image Editor</h3>
                            <p>Edit gambar yang ingin Anda Posting dengan mudah menggunakan fitur image editor yang tersedia di Lancarin. Sekarang, Anda tidak perlu lagi download aplikasi-aplikasi Image Editor di Smartphone atau Komputer Anda.<br/><br/><br/></p>
                        </div>
                    </div>
                  
                     <div class="col-lg-4 col-md-6">
                        <div class="single-services text-center">
                            <img style="height:65px !important;" src="assets/img/lancarin/auto-watermark.png" alt="">
                            <h3>Auto Watermark</h3>
                            <p>Cukup tentukan sekali gambar, posisi, dan tingkat transparansi yang ingin Anda jadikan Watermark di setiap postingan yang Anda posting menggunakan Lancarin. Lebih efisien, dibanding harus pasang watermark satu per satu.<br/><br/><br/></p>
                        </div>
                    </div>
                  
                  <div class="col-lg-4 col-md-6">
                        <div class="single-services text-center">
                            <img style="height:65px !important;" src="assets/img/lancarin/random-comment.png" alt="">
                            <h3>Random Comment</h3>
                            <p>Fitur untuk melakukan random comments yang bisa Anda manfaatkan ketika sedang membuat Give Away dan sebagainya.<br/><br/><br/><br/></p>
                        </div>
                    </div>
                  
                    <div class="col-lg-4 col-md-6">
                        <div class="single-services text-center">
                            <img style="height:65px !important;" src="assets/img/lancarin/arisan-like.png" alt="">
                            <h3>Like for Like (Arisan Likes)</h3>
                            <p>Ikuti program Like for Like di Lancarin agar setiap postingan terbaru Anda akan mendapatkan likes secara otomatis dari semua pengguna Lancarin yang mengikuti program Like for Like. Jadi, likes yang Anda dapatkan adalah real (bukan bot likes).<br/></p>
                        </div>
                    </div>
                  
                    <div class="col-lg-4 col-md-6">
                        <div class="single-services text-center">
                            <img style="height:65px !important;" src="assets/img/lancarin/caption-template.png" alt="">
                            <h3>Caption Templates</h3>
                            <p>Fitur ini membantu Anda untuk menggunakan caption yang telah Anda simpan sebelumnya sehingga Anda tidak perlu repot-repot menulisnya kembali ketika menjadwalkan postingan.<br/><br/></p>
                        </div>
                    </div>
                  
                    <div class="col-lg-4 col-md-6">
                        <div class="single-services text-center">
                            <img style="height:65px !important;" src="assets/img/lancarin/custom-link.png" alt="">
                            <h3>Custom Link (Bioku.id)</h3>
                            <p>Custom Link dapat memudahkan pelanggan untuk menghubungi Anda melalui link di bio Instagram Anda ke channel promosi Anda seperti WhatsApp, Line, Website, dan Marketplace. Buat Custom Link Anda sekarang.<br/><br/></p>
                        </div>
                    </div>
				</div>
            </div>
        </section>
        <!-- End Services Area -->
		
                <!-- Start Features Area -->
        <section id="whyus" class="features-area ptb-100">
            <div class="container">
                <div class="section-title">
                    <span>Why Us</span>
                    <h3>Kenapa Harus Pakai Lancarin</h3>
                    <p>Beberapa alasan mengapa Anda <b>butuh</b> Lancarin untuk Bisnis Anda.</p>
                </div>
                
                <div class="row">
                    <div class="features-slides">
                        <div class="col-lg-12 col-md-12">
                            <div class="single-features">
                                <i class="icofont-price"></i>
                                <h3>Harga terjangkau</h3>
                                <p>Harga yang sangat terjangkau untuk Tools serba otomatis yang setiap menit mendatangan followers tertarget tanpa henti.</p>
                            </div>
                        </div>
                        
                        <div class="col-lg-12 col-md-12">
                            <div class="single-features">
                                <i class="icofont-headphone"></i>
                                <h3>Fast support</h3>
                                <p>Tim Customer support professional Lancarin selalu siap membantu & menjawab kebutuhan/pertanyaan Anda, melalui Live Chat Messanger & Email di Hari Kerja.</p>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12">
                            <div class="single-features">
                                <i class="icofont-responsive"></i>
                                <h3>Fully responsive</h3>
                                <p>Tidak perlu pusing, Tidak ada yang perlu Anda download atau install. Akses Lancarin di Web Browser dengan tampilan yang mobile responsive.</p>
                            </div>
                        </div>
                        
                        <div class="col-lg-12 col-md-12">
                            <div class="single-features">
                                <i class="icofont-rocket"></i>
                                <h3>Mudah digunakan</h3>
                                <p>Sangat mudah digunakan. Anda hanya perlu setting sekali, dan kemudian tinggalkan. Lancarin akan bekerja secara otomatis untuk akun instagram Anda.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Features Area -->
        
        
        
        <!-- Start How It Works Area -->
        <section class="how-works-area bg-gray ptb-100">
            <div class="container">
                <div class="section-title">
                    <span>Easy Setting</span>
                    <h3>Setting Sekali, Santai Kemudian</h3>
                    <p>Jangan Khawatir, Kami telah membuat Lancarin untuk sangat mudah digunakan.</p>
                </div>
                
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="single-box with-line">
                            <span>01.</span>
                            <h3>Setting</h3>
                            <p>Atur aktifitas otomatis yang Anda inginkan.</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <div class="single-box with-line">
                            <span>02.</span>
                            <h3>Start</h3>
                            <p>Segera mulai aktifitas yang telah Anda atur.</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 offset-lg-0 offset-md-3">
                        <div class="single-box">
                            <span>03.</span>
                            <h3>Santai</h3>
                            <p>Lancarin akan mendatangkan Followers Tertarget ke Akun Instagram Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End How It Works Area -->
        
        <!-- Start Pricing Area -->
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
                <img style="max-width:200px;" src="assets/img/lancarin/logo-payment.png" width="200px">
                <p style="font-size:12px;margin-top: 0.2rem;">Pembayaran Anda akan diverifikasi secara otomatis</p>
            </div>
            </div>
        </section>
        <!-- End Pricing Area -->

        <!-- Start FAQ Area -->
        <section id="faq" class="faq-area bg-gray ptb-100">
            <div class="container">
                <div class="section-title">
                    <span>FAQ</span>
                    <h3>Sering ditanyakan</h3>
                    <p>Hal-hal yang sering ditanyakan.</p>
                </div>
                
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="faq">
                            <ul class="accordion">
                                <li class="accordion-item">
                                    <a class="accordion-title active" href="javascript:void(0)">Apakah follower yang didapatkan itu pengguna aktif?</a>
                                    <p class="accordion-content show">Ya, follower yang didapatkan merupakan pengguna aktif yang sudah Anda targetkan di settingan auto activity akun Lancarin Anda.</p>
                                </li>
                                
                                <li class="accordion-item">
                                    <a class="accordion-title" href="javascript:void(0)">Bagaimana cara Lancarin mendapatkan follower?</a>
                                    <p class="accordion-content">Lancarin akan membantu Anda melakukan interaksi secara OTOMATIS 24 jam sehari sesuai settingan Anda sehingga menimbulkan interaksi dengan user yang Anda targetkan sehingga meningkatkan kemungkinan Anda difollow.</p>
                                </li>
                                
                                <li class="accordion-item">
                                    <a class="accordion-title" href="javascript:void(0)">Apakah saya harus selalu membuka Lancarin setiap saat?</a>
                                    <p class="accordion-content">Tidak. Lancarin berjalan setiap hari 24 jam dalam seminggu. Aktivitas tetap berjalan OTOMATIS sekalipun komputer atau handphone Anda mati.</p>
                                </li>
                                
                                <li class="accordion-item">
                                    <a class="accordion-title" href="javascript:void(0)">Apakah Lancarin bisa diakses menggunakan Smartphone?</a>
                                    <p class="accordion-content">Bisa, Lancarin dapat diakses menggunakan Smartphone maupun Komputer melalui Web Browser (Chrome, Firefox, Internet Explorer).</p>
                                </li>
                                
                                <li class="accordion-item">
                                    <a class="accordion-title" href="javascript:void(0)">Apakah jika saya menggunakan Lancarin bebas dari banned?</a>
                                    <p class="accordion-content">Pada dasarnya Lancarin hanyalah sebuah tools. Jika Anda mengikuti aturan dari Instagram, akun Anda akan aman karena variabel yang menyebabkan akun Anda dibanned itu banyak sekali. Namun Anda tidak perlu kuatir karena kami juga meminimalisir aktivitas yang akan mengakibatkan resiko akun Anda dibanned oleh Instagram.</p>
                                </li>
                                
                                <li class="accordion-item">
                                    <a class="accordion-title" href="javascript:void(0)">Saya punya lebih dari 5 akun Instagram. Apakah ada plan yang cocok?</a>
                                    <p class="accordion-content">Ada. Silahkan hubungi tim kami di email support@lancarin.com atau melalui live chat</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 col-md-12">
                        <div class="faq-img">
                            <img src="assets/img/lancarin/instagram-accounts-showcase.png" alt="faq">
                        </div>
                    </div>
                </div>
        </section>
        <!-- End FAQ Area -->
          
           <!-- Start Subscribe Area-->
        <section class="subscribe-area ptb-100">
            
            <div class="bg-top"></div>
            <div class="bg-bottom"></div>
           
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="newsletter">
                            <h4>Daftar Lancarin Sekarang Juga</h4>  
                                <a class="btn btn-primary btn-signup" href="#pricing">Daftar Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>
            
        </section>
        <!-- End Subscribe Area-->
        
        <!-- Start Footer Area -->
        <footer class="footer-area bg-gray">
            
            <div class="copyright-area" style="background:#fff !important;">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7 col-md-7">
                            <p>Copyright <i class="icofont-copyright"></i> 2018 All Rights Reserved.</p>
                            <p><a href="<?=BASE;?>p/program-referral">Program Referral</a></p>
                        </div>
                        
                        <div class="col-lg-5 col-md-5">
                            <ul>
                                <li><a href="https://instagram.com/lancarin.id" class="icofont-instagram"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- End Footer Area -->

		<!-- Begin Sales Notification -->
		 <section class="custom-social-proof">
			<div class="custom-notification">
			  <div class="custom-notification-container">
				<div class="custom-notification-image-wrapper">
				  <img src="https://via.placeholder.com/100">
				</div>
				<div class="custom-notification-content-wrapper">
				  <p class="custom-notification-content">
					
				  </p>
				</div>
			  </div>
			  <div class="custom-close"></div>
			</div>
		  </section>
		<!-- End Sales Notification -->

        
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.2.0/js.cookie.min.js"></script>
		<script>
    $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
    });
      
		$(function(){
			var ref= '<?=session('refcode');?>';
			if(Cookies.get('refcode')== undefined && ref !=''){
				Cookies.set('refcode', ref, { expires: <?=intval(get_option('cookie_referal_day',30));?> });
			}
			
		  $('.btn-signup').click(function(){
			$.notify('Mohon memilih paket terlebih dahulu', "info");
			$('html, body').animate({
				scrollTop: $("#pricing").offset().top
			});
		  });
		  
		  function getRecentUser(){
				$.ajax({
					url: "<?=site_url('get_recent_user');?>",
					type: 'post',
					dataType:'json',
					data:{token: '<?=$this->security->get_csrf_hash()?>'},
					success: function(d){
						if(d.ok==1){
							$(".custom-notification-content").html(d.content);
							$(".custom-notification-image-wrapper img").attr('src', d.img);
							$(".custom-social-proof").stop().slideToggle('slow');
							setTimeout(function(){
								$(".custom-social-proof").stop().slideToggle('slow');
							},8000); //hrz lebih kecil dari minSec
						}
					}
				})
			}
			
			//sales notification			
			var maxSec = 75000;//15 detik
			var minSec = 60000;//5detik
			(function loopNotif() {
				var rand = Math.round(Math.random() * (maxSec - minSec)) + minSec;
				setTimeout(function() {
					getRecentUser();
					loopNotif();  
				}, rand);
			}());
			
			$(".custom-close").click(function() {
				$(".custom-social-proof").stop().slideToggle('slow');
			});
		})
		</script>

</body>
</html>