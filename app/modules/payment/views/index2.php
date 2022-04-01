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
	<link href='https://fonts.googleapis.com/css?family=Lato:400,300,700,100' rel='stylesheet' type='text/css'>
	
    <link rel="stylesheet" type="text/css" href="<?=BASE?>assets/plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?=BASE?>assets/plugins/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?=BASE?>assets/plugins/izitoast/css/iziToast.min.css">
    <script type="text/javascript" src="<?=BASE?>assets/plugins/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="<?=BASE?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?=BASE?>assets/plugins/izitoast/js/iziToast.min.js"></script>

	
<!-----------------------------------------------
/////////////////////////////////////////////////////////
///////// This Website Made by Razzo Digital ////////////
/////////////////////////////////////////////////////////
----------------------------------------------->
	<style>
	body {
  background: #F5F7FA;
}

a {
  text-decoration: none;
}

h1 {
  text-align: center;
  font-family: 'Lato', sans-serif;
  font-size: 36px;
  line-height: 32px;
  padding-top: 20px;
	color: #212121;
	text-transform: uppercase;
}
h2 {
  text-align: center;
  font-family: 'Lato', sans-serif;
  font-size: 25px;
}

.price-table-wrapper {
  font-family: 'Lato', sans-serif;
  text-align: center;
  margin-top: 30px;
}
.price-table-wrapper .featured-table {
  box-shadow: 0px 0px 19px -3px rgba(0, 0, 0, 0.36);
}
.price-table-wrapper .pricing-table {
  display: inline-block;
  background: white;
  margin: 20px;
  transition: all 0.3s ease-in-out;
	min-width: 220px;
}
.price-table-wrapper .pricing-table__header {
	text-transform: uppercase;
  padding: 20px;
  font-size: 20px;
  color: #212121;
  background: #E0E0E0;
}
.price-table-wrapper .pricing-table__price {
  color: #212121;
  padding: 20px;
  margin: auto;
  font-size: 40px;
  font-weight: 600;
}
.price-table-wrapper .pricing-table__button {
  display: block;
  background: #2367ce;
  text-decoration: none;
  padding: 20px;
  color: white;
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease-in-out;
}
.price-table-wrapper .pricing-table__button:before {
  position: absolute;
  left: -20%;
  top: -10%;
  content: '';
  width: 60%;
  height: 220%;
  -webkit-transform: rotate(-30deg);
          transform: rotate(-30deg);
  background: white;
  opacity: .3;
  transition: all 0.3s ease-in-out;
}
.price-table-wrapper .pricing-table__button:after {
  position: absolute;
  content: '>';
  top: 0;
  right: 0;
  font-size: 25px;
  padding: 15px;
  padding-right: 40px;
  color: white;
  opacity: 0;
  transition: all 0.3s ease-in-out;
}
.price-table-wrapper .pricing-table__button:hover {
  background: #2a7af3;
}
.price-table-wrapper .pricing-table__list {
  padding: 20px;
  color: #212121;
}
.price-table-wrapper .pricing-table__list li {
  padding: 15px;
  border-bottom: 1px solid #C8C8C8;
}
.price-table-wrapper .pricing-table__list li:last-child {
  border: none;
}
.price-table-wrapper .pricing-table:hover {
  box-shadow: 0px 0px 19px -3px rgba(0, 0, 0, 0.36);
}
.price-table-wrapper .pricing-table:hover .pricing-table__button {
  padding-left: 0;
  padding-right: 35px;
}
.price-table-wrapper .pricing-table:hover .pricing-table__button:before {
  top: -80%;
  -webkit-transform: rotate(0deg);
          transform: rotate(0deg);
  width: 100%;
}
.price-table-wrapper .pricing-table:hover .pricing-table__button:after {
  opacity: 1;
  padding-right: 15px;
}
		
		ul.pricing-table__list{
			list-style: none;
		}

		
.bank-option[type=radio] { 
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

.bank-option[type=radio] + img {
  cursor: pointer;
  padding: 5px;
}

.bank-option[type=radio]:checked + img {
  outline: 2px solid #31708f;
}

.bank-container{
	height: 100px;
}
.img-responsive {
    display: block;
    max-width: 250px;
    width: 100%;
    height: auto;    
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
  fbq('track', 'AddToCart');
</script>
	</head>
</head>
<body>

<?php if(!empty($package) && get_option("bca_enable")==1):?>
	<h1><?=lang('plan')?> <?=$package->name?></h1>

<div class="price-table-wrapper">
  <div class="pricing-table">
    <h2 class="pricing-table__header">- <?=lang('monthly')?> -</h2>
    <h3 class="pricing-table__price"><?=number_format($package->price_monthly, 0,',','.')?></h3>
    <a  id="addToCartButton" class="pricing-table__button show-bank" data-type="1" href="<?=cn('payment/bca/process/'.$package->ids)?>">
      <b>Pilih Sekarang!</b>
    </a>
    <ul class="pricing-table__list">
      <li><?=get_option('payment_currency','USD')." ".number_format($package->price_monthly, 0,',','.')?> / <?=lang('month')?></li>
      <li>-</li>
      <li><?=$package->description?></li>
      <li>1 Bulan</li>
    </ul>
  </div>
  <div class="pricing-table featured-table">
    <h2 class="pricing-table__header">- <?=lang('annually')?> -</h2>
    <h3 class="pricing-table__price"><?=number_format($package->price_annually*12, 0,',','.')?></h3>
    <a  id="addToCartButton" class="pricing-table__button show-bank"  data-type="2" href="<?=cn('payment/bca/process/'.$package->ids.'/2')?>">
      <b>Pilih Sekarang!</b>
    </a>
    <ul class="pricing-table__list">
      <li class="pulse"><b><?=get_option('payment_currency','USD')." ".number_format($package->price_annually, 0,',','.')?></b> /  <?=lang('month')?></li>
      <li><b>Hemat :</b> <?=number_format(($package->price_monthly - $package->price_annually)*12,0,',','.')?></li>
      <li><?=$package->description?></li>
      <li>12 Bulan</li>
    </ul>
  </div>
</div>
<?php endif;?>
	<div class="fly" style="text-align:center;margin-bottom: 30px;"><a href="<?=site_url('pricing');?>" style="color: #c7c7c7;"><button class="btn btn-primary"><i class="fa fa-angle-double-left" aria-hidden="true"></i> Back to Pricing</button></a></div>
	
	
	
	<div class="modal fade" tabindex="-1" id="modal-bank" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Pilih Bank</h4>
      </div>
      <form id="form-bank">
      <div class="modal-body">
		<div class="row">
		<?php $bankEnabled = 0; $no =1;foreach(['bca', 'bni','mandiri','bri'] as $bank): if(get_option($bank.'_enable')==1):?>
		<div class="col-md-6 bank-container">
        <label>
		  <input type="radio" name="bank" class="bank-option" data-bank="<?=$bank;?>" value="small" <?=$no==1?'checked':'';?>>
		  <img class="img-responsive " src="<?=base_url('assets/img/'.$bank.'.png');?>">
		</label>
		</div>
		<?php $bankEnabled = 1;$no++;endif;endforeach;?>
		<?php if($bankEnabled==0):?>
		<div class="alert alert-danger" role="alert"><b>Warning:</b> Semua bank dimatikan</div>
		<?php endif;?>
		<div class="col-md-12">
			<div class="form-inline">
				<div class="form-group">
					<label for="exampleInputName2">Diskon</label>
					<input type="text" class="form-control" id="discount" placeholder="Kode diskon">
				  </div>
				<button type="button" class="btn btn-default " id="btn-cek-discount">Verify</button>
			</div>
		</div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary" <?=$bankEnabled==0?'disabled':'';?>>Checkout</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

	
	
	
	
	<script>
	(function(c){var k={init:function(a){var b={color:c(this).css("background-color"),reach:20,speed:1E3,pause:0,glow:!0,repeat:!0,onHover:!1};c(this).css({"-moz-outline-radius":c(this).css("border-top-left-radius"),"-webkit-outline-radius":c(this).css("border-top-left-radius"),"outline-radius":c(this).css("border-top-left-radius")});a&&c.extend(b,a);b.color=c("<div style='background:"+b.color+"'></div>").css("background-color");!0!==b.repeat&&!isNaN(b.repeat)&&0<b.repeat&&(b.repeat-=1);return this.each(function(){b.onHover?
c(this).bind("mouseover",function(){g(b,this,0)}).bind("mouseout",function(){c(this).pulsate("destroy")}):g(b,this,0)})},destroy:function(){return this.each(function(){clearTimeout(this.timer);c(this).css("outline",0)})}},g=function(a,b,d){var e=a.reach;d=d>e?0:d;var h=(e-d)/e,f=a.color.split(","),h="rgba("+f[0].split("(")[1]+","+f[1]+","+f[2].split(")")[0]+","+h+")",f={outline:"2px solid "+h};a.glow&&(f["box-shadow"]="0px 0px "+parseInt(d/1.5)+"px "+h);f["outline-offset"]=d+"px";c(b).css(f);b.timer&&
clearTimeout(b.timer);b.timer=setTimeout(function(){if(d>=e&&!a.repeat)return c(b).pulsate("destroy"),!1;if(d>=e&&!0!==a.repeat&&!isNaN(a.repeat)&&0<a.repeat)a.repeat-=1;else if(a.pause&&d>=e)return l(a,b,d+1),!1;g(a,b,d+1)},a.speed/e)},l=function(a,b,c){innerfunc=function(){g(a,b,c)};b.timer=setTimeout(innerfunc,a.pause)};c.fn.pulsate=function(a){if(k[a])return k[a].apply(this,Array.prototype.slice.call(arguments,1));if("object"!==typeof a&&a)c.error("Method "+a+" does not exist on jQuery.pulsate");
else return k.init.apply(this,arguments)}})(jQuery);
	</script>
<script>
$(function(){
	function mynotif(title, msg, color='red'){
		iziToast.show({
			title: title,
			message: msg,
			color: color,
			position: 'bottomCenter'
		});
	}
	var packageIds = "<?=$package->ids;?>" , type=1;
	$('.pulse').pulsate({
		color: '#2a7af3'
	});
	
	$('.show-bank').click(function(e){
		e.preventDefault();
		type = $(this).data('type');
		$('#modal-bank').modal('show');
	});
	
	$('#form-bank').submit(function(e){
		e.preventDefault();
		var bank = $('.bank-option:checked').data('bank'),
		url = '<?=cn('payment/bank/process');?>'+'/'+bank+'/'+ packageIds +'/'+type;
		window.location = url;
	});
	var cb = 0;
	$('#btn-cek-discount').click(function(){
		var btn=$(this);
		cb++;
		if(cb >3){
			$(this).remove();
			return false;
		}
		var title='Diskon';
		$.ajax({
        data: {token: '<?=$this->security->get_csrf_hash()?>', code:$('#discount').val()},
        type: 'post',
        dataType: 'json',
        url: '<?=site_url('payment/check_discount');?>',
        beforeSend: function(){
			btn.prop('disabled', true).addClass('btn-warning').removeClass('btn-default');
        },
        success: function(d){
          if(d.ok==1){
			mynotif(title, 'Discount valid', 'green');
          }else{
			  mynotif(title, d.msg);
			  
          }
          setTimeout(function(){
				btn.prop('disabled', false).addClass('btn-default').removeClass('btn-warning ');
			  },2000);
        },
       error: function(d){
		   mynotif(title, 'Terjadi kesalahan');
		   
          setTimeout(function(){
				btn.prop('disabled', false).addClass('btn-default').removeClass('btn-warning ');
			  },2000);
        }
      });
		
	})
	
	$( '#addToCartButton' ).click(function() {
		fbq('track', 'AddToCart');
	});
});

</script>


</body>
</html>