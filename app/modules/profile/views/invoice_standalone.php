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
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?=BASE?>assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?=BASE?>assets/plugins/bootstrap/css/bootstrap-extended.min.css">
    <link rel="stylesheet" type="text/css" href="<?=BASE?>assets/plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?=BASE?>assets/plugins/font-feather/feather.min.css">
    <link rel="stylesheet" type="text/css" href="<?=BASE?>assets/plugins/font-ps/css/pe-icon-7-stroke.css">
    <link rel="stylesheet" type="text/css" href="<?=BASE?>assets/css/colors.min.css">
    <link rel="stylesheet" type="text/css" href="<?=BASE?>assets/css/layout.css">
    <script type="text/javascript" src="<?=BASE?>assets/plugins/jquery/jquery.min.js"></script>
    <script type="text/javascript">
        var token = '<?=$this->security->get_csrf_hash()?>',
            PATH  = '<?=PATH?>',
            BASE  = '<?=BASE?>';
            GOOGLE_API_KEY   = 'AIzaSyA7rE-ngRgga_EJ38xZpAJkukB1bCoxOV0';
            GOOGLE_CLIENT_ID = '1088992811074-98f4e7d22gebaodjfa94hfdimnmvk6cl.apps.googleusercontent.com';
    </script>
  <style>
    .container2{
      background: white;
      margin-top: 20px;
      margin-bottom: 20px;
    }
    .text-bold{
      font-weight: bold;
    }
    .detail{
      padding:10px;
      border-bottom: 1px dotted #ccc;
    }
   .thumb{
		display: block;
		margin-left: auto;
		margin-right: auto;
		width: 5%;
		padding: 20px 0;
    }
    
  </style>
</head>
<body id="body-main">
	<div class="wrap-content container  " style="margin-top: 10px;">
		<div class="row">
			<div class="col-md-12 " style="">
				<div class="bg-primary" style="background-color:#fff !important;">
				<img class="thumb" src="<?=BASE;?>assets/uploads/user1/04b2dde2bd6264ef4409852a9457f13d.png"> 
				</div>
			</div>
			<div class="col-md-12">
				<div class="card payment-history">
					<div class="card-header">
						<div class="card-title pull-left">
							<i class="fa fa-credit-card" aria-hidden="true"></i> Invoice</div>
						<div class="card-title pull-right">ID: <?=$transaction_id;?>
						</div>
					  <br>
						<hr>
					</div>
					<div class="card-block ">
					  <div class="row">
						<div class="col-xs-6">
						  <address>
						  <strong>Dari:</strong><br> 
							<?=$fullname;?>,<br>
							<?=$email;?><br>
						  </address>
						</div>
						<div class="col-xs-6 text-right">
						  <address>
							<strong>Kepada:</strong><br>
							<?=get_option($bank.'_nama');?><br>
							<?=strtoupper($bank);?>: <b><?=get_option($bank.'_norek');?></b>
						  </address>
						</div>
					  </div>
					  <div class="row">
						<div class="col-xs-6">
						  <div>
						  <strong>Tanggal Invoice:</strong><br>
							<?=$created;?><br>
						  </div>
						<br>
						  <div>
						  <strong>Tanggal Invoice Kadaluarsa:</strong><br>
							<?=$invoice_expired;?><br>
						  </div>
						</div>
						<div class="col-xs-6 text-right">
						  <div>
						  <strong>Tanggal Akun Kadaluarsa:</strong><br>
							<?=$expired;?><br>
						  </div>
						</div>
					  </div>
					  
					  <div class="row" style="margin-top:20px;">
						<div class="col-md-12">
						  <div class="panel panel-default">
							<div class="panel-heading">
							  <h3 class="panel-title"><strong>Order summary</strong></h3>
							</div>
							<div class="panel-body">
							  <div class="table-responsive">
								<table class="table table-condensed">
								  <thead>
									 <tr>
									   <td><strong>Paket</strong></td>
										<td class="text-center"><strong>Deskripsi</strong></td>
										<td class="text-center"><strong>Tipe</strong></td>
										<td class="text-right"><strong>Harga</strong></td>
									 </tr>
								  </thead>
								  <tbody>
									<tr>
									  <td><?=$package_name;?></td>
									  <td class="text-center"><?=$package_description;?></td>
									  <td class="text-center"><?=$plan;?></td>
									  <td class="text-right"><?=$price;?></td>
									</tr>
									<tr>
									  <td class="no-line"></td>
									  <td class="no-line"></td>
									  <td class="no-line text-center"><strong>Total</strong></td>
									  <td class="no-line text-right"><?=$price;?></td>
									</tr>
									<tr>
									  <td class="no-line"></td>
									  <td class="no-line"></td>
									  <td class="no-line text-center"><strong>Status</strong></td>
									  <td class="no-line text-right"><span class="label label-<?=$status?'success':'danger';?>" style="font-weight:bold; font-size:16px;"><?=$status?'Sudah':'Belum';?> Dibayar</span></td>
									</tr>
								  </tbody>
								</table>
							  </div>
							</div>
						  </div>
						</div>
						<div class="" style="margin-left:10px;">
						 <a href="<?=site_url('profile/user_payment_history');?>"><button class="btn btn-primary "><i class="fa fa-chevron-left"></i> Back</button></a>
						</div>

					  </div>
					</div>
				</div>				
			</div>
		</div>
	</div>



    <script type="text/javascript" src="<?=BASE?>assets/js/moment.js"></script>
    <script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
    <script type="text/javascript" src="<?=BASE?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?=BASE?>assets/js/landing-page.js"></script>
    <script type="text/javascript" src="<?=BASE?>assets/js/main.js"></script>
    <?=htmlspecialchars_decode(get_option('embed_javascript', ''), ENT_QUOTES)?>
</body>
</html>