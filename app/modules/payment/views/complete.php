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
    width: 50%;
    }
    
  </style>
</head>
<body id="body-main">
  <div class="container">
    <div classs="row">
      <div class="col-md-6 col-md-offset-3 container2">
        <br>
        <img class="thumb" src="<?=BASE?>assets/img/<?=$bank?>.png">
        <br>
        <div class="row detail">
          <div class="col-md-12 text-info">
            Sistem kami akan memproses pembayaran anda paling cepat 10 menit setelah anda melakukan pembayaran
          </div>
        </div>
        <div class="row detail">
          <div class="col-md-6 text-bold">
            Id Transaksi
          </div>
          <div class="col-md-6 ">
            <?=session('payment_transid');?>
          </div>
        </div>
        <div class="row detail">
         <div class="col-md-6 text-bold">
            Harga
          </div>
          <div class="col-md-6 ">
            <?=number_format($amount,0,'.','.');?>
          </div>
        </div>
        <div class="row detail">
         <div class="col-md-6 text-bold">
            Payment Expired
          </div>
          <div class="col-md-6 ">
            <?=session('payment_validuntil');?>
          </div>
        </div>
        <div class="row detail">
         <div class="col-md-6 text-bold">
            Kepada
          </div>
          <div class="col-md-6 ">
            <?=$bank_nama;?>
          </div>
        </div>
        <div class="row detail">
         <div class="col-md-6 text-bold">
            Bank
          </div>
          <div class="col-md-6 ">
            <?=strtoupper($bank);?>
          </div>
        </div>
        <div class="row detail">
         <div class="col-md-6 text-bold">
            No Rekening
          </div>
          <div class="col-md-6 ">
            <?=$norek;?>
          </div>
        </div>
        
        <div class="row detail">
         <a href="<?=site_url('profile');?>"><button class="btn btn-primary pull-right"><i class="fa fa-user"></i> Profile</button></a>
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