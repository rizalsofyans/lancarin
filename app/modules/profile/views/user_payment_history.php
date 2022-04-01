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
		width: 10%;
		padding-top: 10px;
		padding-bottom: 10px;
    }
  </style>
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
  fbq('track', 'Purchase');
</script>
</head>
<body id="body-main">
  <!-- Live Chat Widget powered by https://keyreply.com/chat/ -->
<!-- Advanced options: -->
<!-- data-align="left" -->
<!-- data-overlay="true" -->
<script data-align="right" data-overlay="false" id="keyreply-script" src="//lancarin.com/assets/js-landingpage/widgetschat.js" data-color="#3F51B5" data-apps="JTdCJTIyd2hhdHNhcHAlMjI6JTIyNjI4MTkwNTY3OTczOSUyMiwlMjJmYWNlYm9vayUyMjolMjI0NDQ2ODUwNzkyNzI0OTAlMjIlN0Q="></script>
  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3 container2">
        <div class="row detail bg-primary" style="background-color:#fff !important;">
          <div class="col-md-12 " style=""><img class="thumb" src="<?=get_option('website_logo_white');?>">  
          </div>
        </div>
        
		<div class="row detail">
          <div class="col-md-12 text-info">
            Anda tinggal <b>satu langkah lagi</b>, silahkan melakukan pembayaran untuk dapat mengakses <b>dashboard area</b> dan menikmati <b>fitur-fitur</b> Lancarin.
          </div>
        </div>
        <div class="row detail">
            <div class="col-md-12">
            <div class="card payment-history">
                <div class="card-header">
                    <div class="card-title pull-left">
                        <i class="fa fa-credit-card" aria-hidden="true"></i> Payment History
                    </div>
                    <div class="card-title pull-right">
                        <button class="btn btn-success show-confirm">
                          <i class="fa fa-envelope " aria-hidden="true"></i> Konfirmasi</button>
                    </div>
                  <br>
                </div>
                <div class="card-block " style="margin-top: 10px;">
                  <table class="table table-hover table-striped table-bordered ">
                    <thead><tr><th>Id Transaksi</th><th>Status</th></tr></thead>
                    <tbody><?=$paymentHistory;?></tbody>
                  </table>
                </div>
            </div>
        </div>

        </div>
        <div class="" style="margin-left:10px;margin-right:10px;">
        <div class="row detail">
		<a href="<?=site_url('auth/logout');?>"><button class="btn btn-danger "><i class="fa fa-sign-out"></i> Logout</button></a>
         <a href="<?=site_url('pricing');?>"><button class="btn btn-primary pull-right"><i class="fa fa-paper-plane"></i> Ganti Paket</button></a>
        </div>
          </div>
      </div>
    </div>
  </div>
  
<div class="modal fade" tabindex="-1" id="modal-payment" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Konfirmasi Pembayaran</h4>
      </div>
      <form id="form-confirm">
      <div class="modal-body">
        <div class="form-group">
          <label for="nama">Nama Pemilik Rekening</label>
          <input type="text" required class="form-control" name="nama" id="nama" placeholder="Nama Pengirim">
        </div>
        <div class="form-group">
          <label for="bank">Nama Bank</label>
          <input type="text" required class="form-control" name="bank" id="bank" placeholder="Nama Bank">
        </div>
        <div class="form-group">
          <label for="norek">No Rekening</label>
          <input type="text" required class="form-control" name="norek" id="norek" placeholder="No Rekening">
        </div>
        <div class="form-group">
          <label for="invoice">No Invoice/Transaction ID</label>
          <input type="text" required class="form-control" name="invoice" id="invoice" placeholder="No Invoice">
        </div>
        <div class="form-group">
          <label for="nilai">Jumlah</label>
          <input type="number" class="form-control" name="nilai" id="nilai" placeholder="Isi jika berbeda dgn invoice">
        </div>
        <div class="form-group">
          <label for="bukti">Bukti pengiriman</label>
          <input accept="image/*" type="file" id="bukti" name="bukti">
          <p class="help-block">Optional: bukti pengiriman.</p>
        </div>
        <div class="form-group">
          <label for="invoice">Keterangan tambahan</label>
          <textarea class="form-control" rows="5" name="keterangan" id="keterangan" placeholder="Keterangan tambahan jika diperlukan"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Kirim</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

  
  
    <script type="text/javascript" src="<?=BASE?>assets/js/moment.js"></script>
    <script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
    <script type="text/javascript" src="<?=BASE?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?=BASE?>assets/js/landing-page.js"></script>
    <script type="text/javascript" src="<?=BASE?>assets/js/main.js"></script>
    <?=htmlspecialchars_decode(get_option('embed_javascript', ''), ENT_QUOTES)?>
	
	<script>
  $(function(){
    $('.show-confirm').click(function(){
      $('#modal-payment').modal('show');
    });
	
	var firstPayCheck = setInterval(checking, 5000);
	function checking() {
		$.ajax({
			url: '<?=site_url('profile/checkHaveFirstPayment');?>',
			dataType: 'json',
			data: {token: token},
			success: function(d){
				if(d.ok==1){
					stopChecking();
          fbq('track', 'Purchase');
					window.location = '<?=site_url('dashboard');?>';
				}
			}
		});
	}

	function stopChecking() {
		clearInterval(firstPayCheck);
	}
	
	
    $('#form-confirm').submit(function(e){
      e.preventDefault();
      var formdata = new FormData($(this)[0]);
      formdata.append('token', token);
      var btn = $(this).find('button[type="submit"]');
      $.ajax({
       cache: false,
       contentType: false,
       enctype: 'multipart/form-data',
       processData: false,
        data: formdata,
        type: 'post',
        dataType: 'json',
        url: '<?=site_url('profile/confirmPayment');?>',
        beforeSend: function(){
          btn.prop('disabled', true).addClass('btn-warning').removeClass('btn-primary');
        },
        success: function(d){
          if(d.ok==1){
            alert('Terima kasih');
            $('#modal-payment').modal('hide');
          }else{
            alert(d.msg);
          }
          setTimeout(function(){
            btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
          },2000);
          
        },
       error: function(d){
          alert('Terjadi kesalahan');
          setTimeout(function(){
            btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
          },2000);
        }
      });
    });
  });
</script>
</body>
</html>