<?php
$desc=!empty($info->description)?$info->description:'';
$by=!empty($info->slug)?$info->slug .' by ':'';
?><!DOCTYPE html>
<html>
<head>
	<title><?=$by?>Lancarin - Aplikasi Instagram Marketing Serba Otomatis</title>
	<meta name="description" content="<?=$desc?>" />
	<meta name="keywords" content="Aplikasi Instagram Marketing, Tools Instagram Marketing, Auto Follow Instagram, Lancarin" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="icon" type="image/png" href="https://lancarin.com/assets/uploads/user1/04b2dde2bd6264ef4409852a9457f13d.png" />
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap-theme.css" />
	
	<style>
	body{
		font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,sans-serif;
		background-color: <?=$info->body_color;?>;
	}
	.area{
		background-color: <?=$info->body_color;?>;
		text-align:center;
		padding-top: 20px;
	}
	.service-container{
		max-width: 700px;
		width: auto;
	}
	.user-pic{
		width: 100px;
	}
	.user-link{
    color: #5a5a5a;
    font-size:1.6rem;
    font-weight: bold;
    text-overflow: ellipsis;
    display: block;
    overflow: hidden;
	}
  .user-link:hover {
    text-decoration: none;
    color:#222;
  }
  .user-link:active {
    text-decoration: none;
    color:#222;
  }
  .user-link:visited {
    text-decoration: none;
  }
  .user-link:link {
    text-decoration: none;
  }
	.mt-10{
		margin-top:10px;
	}
	.mt-20{
		margin-top:20px;
	}
	ul.item-parent{
		list-style: none;
		padding-left: 0;
		width: 100%;
		min-height: 60vh;
		margin: 10px auto 0 auto;
	}
	li.item-child{
		text-decoration:none;
		margin-top: 20px;
		list-style-type: none;
		font-size: 16px;
    border-radius:.5rem;
	}
	li.item-child a{
		display: block;
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
    -ms-hyphens: auto;
    -webkit-hyphens: auto;
    hyphens: auto;
    white-space: normal;
    padding: 15px 20px;
    display: block;
    width: 100%;
    position: relative;
    font-weight:500;
	}
	.item-child a:hover, .mylink2:hover{
		text-decoration:none;
    background:rgba(0,0,0,0.2);
	}
	.item-child a:active, .mylink2:active{
		text-decoration:none;
	}
	.item-child a:visited, .mylink2:visited{
		text-decoration:none;
	}
	.item-child a:link, .mylink2:link{
		text-decoration:none;
	}
	.footer{
		font-size: 1.2rem;
		text-decoration: none;
		/*position: absolute;*/
		margin-top: 25px;
		bottom: 8px;
		left: 0;
		width: 100%;
		padding-bottom: 15px;
	}
  .footer a{
     color:#333 !important;
     font-weight:500;
    }
  .footer a:hover{
    text-decoration:none;  
    }
	.logo{
		width: 40px;
	}
   .blockquote-footer {
    margin-top: 0.75rem;
    display: block;
    color: #555;
    font-size: 1.3rem;
  }
   .blockquote-footer::before {
    content: "\2014 \00A0";
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
   s.parentNode.insertBefore(t,s)}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');
    // Insert Your Facebook Pixel ID below. 
    fbq('init', '<?=$info->fb_pixel?>');
    fbq('track', 'PageView');
    </script>
    <!-- Insert Your Facebook Pixel ID below. --> 
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?=$info->fb_pixel?>&ev=PageView&noscript=1"/></noscript>
    <!-- End Facebook Pixel Code -->
  
</head>
<body>
	<div class="container service-container">
		<div class="area">
			<div class="text-xs-center">
				<?php if(!empty($info->profile_type) && !empty($info->profile_username)):?>
				<img src="https://avatars.io/<?=$info->profile_type?>/<?=$info->profile_username?>/medium" alt="<?=$info->profile_username?>" class="img-circle user-pic ">
				<div style="margin:15px 0 25px 0;">
					<a href="https://<?=$info->profile_type?>.com/<?=$info->profile_username?>" class="user-link">@<?=$info->profile_username?></a>
					<span class="blockquote-footer"><?=$info->description?></span>
				</div>
				<?php else:?>
				<img src="<?=site_url('assets/img/default-avatar.png')?>" alt="default avatar" class="img-circle user-pic">
				<?php endif;?>
			</div>
			<ul class="item-parent" >
			<?php $content=json_decode($info->data); foreach($content as $row):?>
			<li class="item-child" style="background-color:<?=$row->bgcolor?>; ">
				<a class="mylink" style="color:<?=$row->textcolor?>;" href="<?=!empty($row->url)?$row->url:'javascript:;'?>">
				<?php if(!empty($row->icon)):?>
				<i class="fa <?=$row->icon?>"></i> 
				<?php endif;?>
				<?=$row->title?>
				</a>
			</li>
			<?php endforeach;?>
			</ul>
			<div class="footer"><div class="">Dilihat : <b><?=$viewCount;?></b> kali</div>Dibuat GRATIS dengan <a href="//bioku.id">Bioku.id</a> oleh <a href="<?=site_url()?>">Lancarin.com</a><br>Aplikasi Instagram Marketing serba Otomatis</div>
		</div>
	</div>
	
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script></script>
</body>
</html>