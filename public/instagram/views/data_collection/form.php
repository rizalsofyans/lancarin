<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" />
<style>
	.pb-20{
		padding-bottom:20px;
	}
	.mt-10{
		margin-top: 10px;
	}
	.additional{
		border-bottom: 1px solid #f5f5f5;
	}
	.mb-10{
		margin-bottom: 10px;
	}
	.hide{
		display:none;
	}
  .text-khusus {
    text-transform: none !important;
    font-weight: 400 !important;
    letter-spacing: normal !important;
    font-size: 14px;
    color: #333;
}
</style>


<div class="wrap-content tab-list">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header" style="border-bottom: 1px solid #F5F7FA;">
		  			<div class="card-title">
                        <i class="fa fa-database" aria-hidden="true"></i> Data Collection
              <button type="button" class="btn btn-danger btn-sm" style="float:right;" data-toggle="modal" data-target="#myModal">
  Info </button>

<!-- Modal -->
<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Informasi</h4>
      </div>
      <div class="modal-body text-khusus">
              <b>Data Collection</b> adalah fitur yang dapat digunakan untuk melakukan pengambilan data (scrape data) dari instagram dan marketplace berupa url product, url user post, dan username (followers, followings, likers, commenters). Data yang didapatkan dari Data Collection dapat dieksekusi dengan fitur <a href="https://lancarin.com/instagram/manual_activity" style="font-weight:bold;">Manual Activity</a>.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
        </div>
                    <div class="clearfix"></div>
		  		</div>
				<div class="card-header p0">
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#general"><i class="fa fa-instagram" aria-hidden="true"></i> Instagram</a></li>
						<li><a data-toggle="tab" href="#payment"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Marketplace</a></li>
						<li class="<?=empty(get('t'))?'hidden':''?>"><a data-toggle="tab" href="#oauth"><i class="fa fa-shopping-bag" aria-hidden="true"></i> Online Shop</a></li>
					</ul>
				</div>
				<div class="card-block p0">
					<div class="tab-content p15">
						<div id="general" class="tab-pane fade in active">
							<form class="formExtract" >
								<div class="row mb0">
									<div class="col-md-3">
										<div class="form-group">
											<label>Used Account</label>
											<select name="account" id="account" class="form-control show-tick" required >
												<?php if(!empty($accounts)):
												foreach ($accounts as $row) :
												?>
												<option value="<?=$row->id?>"><?=$row->username?></option>
												<?php endforeach;endif;?>
											</select>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>Data Type <i class="fa fa-question-circle-o webuiPopover" data-content="<b>Followers</b>: Mengambil data username yang merupakan followers akun target.<br><b>Followings</b>: Mengambil data username yang merupakan followings akun target.<br><b>Likers</b>: Mengambil data username yang merupakan likers postingan target.<br><b>User Post</b>: Mengambil data postingan akun target." data-delay-show="300" data-title="Tipe Data"></i></label>
											<select name="type" id="type" class="form-control" required >
												<option value="followers">Followers</option>
												<option value="followings">Followings</option>
												<option value="likers">Likers</option>
												<option value="commenters">Commenters</option>
												<option value="comments">Comments</option>
												<option value="userpost">User Post</option>
											</select>
										</div>
									</div>
									<div class="col-md-7">
										<label>Target <i class="fa fa-question-circle-o webuiPopover" data-content="Target bisa berupa url username, username, userid, url media, dan id media.Pisahkan dengan spasi, koma atau baris baru (enter)" data-delay-show="300" data-title="Target"></i></label>
										<textarea rows="3" name="target" id="target" class="form-control" required placeholder="Username/Url Username/Url Post"></textarea>
									</div>
								</div>
								<div class="row mb0 mt-10">
									<div class="col-md-12 mb-10">
										<div class="additional">Additional Options</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="limit">Limit <i class="fa fa-question-circle-o webuiPopover" data-content="<b>Limit</b> adalah batasan pengambilan data. Untuk pengambilan data followers, likers, dan following batasannya adalah 2000, untuk komentar dan posting adalah 200. Lebih dari itu akan dijadwalkan.<br><b>Contoh</b>: Scrap follower 3000. 2000 data akan diterima segera. 1000 data berikutnya akan dimasukkan ke penjadwalan.<br><b>Pengecualian</b> untuk like hanya bisa mengambil 1000 likers saja." data-delay-show="300" data-title="Delay"></i></label>
											<input type="number" name="limit" id="limit" class="form-control" required value="2000" min="0" >
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="delay">Delay (detik) <i class="fa fa-question-circle-o webuiPopover" data-content="Delay per entry target dalam satuan detik" data-delay-show="300" data-title="Delay"></i></label>
											<input type="number" name="delay" id="delay" class="form-control" required value="3" min="0" >
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="interval">Interval (menit) <i class="fa fa-question-circle-o webuiPopover" data-content="Interval untuk proses selanjutnya. Dalam satuan menit" data-delay-show="300" data-title="Interval"></i></label>
											<input type="number" name="interval" id="interval" class="form-control" required value="10" min="1" >
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6" style="width: 50%;float: left;">
										<a href="<?=cn('instagram/data_collection');?>" class="btn btn-default ">
											<i class="fa fa-list" aria-hidden="true"></i> <span>View List</span>
										</a>
									</div>
									<div class="col-md-6 " style="width: 50%;float: left;">
										<button type="submit" class="btn btn-primary waves-effect btnExtract pull-right">
											<i class="fa fa-download" aria-hidden="true"></i> <span>Collect</span>
										</button>
									</div>
								</div>
								<div class="row" id="extract-result-instagram">
									
								</div>
							</form>
						</div>
						<div id="payment" class="tab-pane fade in">
							<form class="formMarketplace">
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label>Web</label>
										<select name="web" id="marketplace-web" class="form-control " required >
											<option value="shopee">Shopee</option>
											<option value="bukalapak">Bukalapak</option>
											<option value="tokopedia">Tokopedia</option>
										</select>
									</div>
								</div>
								<div class="col-md-9">
									<div class="form-group">
										<label>Target</label>
										<textarea name="target" id="marketplace-target" rows="3" class="form-control" required placeholder="nama / url toko"></textarea>
									</div>
								</div>
							</div>
							<div class="row mb0 mt-10">
								<div class="col-md-12 mb-10">
									<div class="additional">Additional Options</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="limit">Page from</label>
										<input type="number" name="from" id="from" class="form-control" required value="1" min="1" >
									</div>
								</div>
								
								<div class="col-md-2">
									<div class="form-group">
										<label for="limit">Page to</label>
										<input type="number" name="to" id="to" class="form-control" required value="1" min="1" >
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="delay">Delay (detik)</label>
										<input type="number" name="delay" id="delay" class="form-control" required value="5" min="5" >
										<p class="help-block">Delay per entry</p>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="interval">Interval (menit)</label>
										<input type="number" name="interval" id="interval" class="form-control" required value="10" min="5" >
										<p class="help-block">Interval for next run</p>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<a href="<?=cn('instagram/data_collection');?>" class="btn btn-default ">
										<i class="fa fa-list" aria-hidden="true"></i> <span>View List</span>
									</a>
								</div>
								<div class="col-md-6 ">
									<button type="submit" class="btn btn-primary waves-effect btnExtract pull-right">
										<i class="fa fa-download" aria-hidden="true"></i> <span>Collect</span>
									</button>
								</div>
							</div>
							<div class="row" id="extract-result-marketplace">
								
							</div>
							</form>
						</div>
						<div id="oauth" class="tab-pane fade in">
							<form class="formOnlineshop">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>Toko</label>
										<select name="web" id="os-web" class="form-control " required >
											<option value="">Pilih Toko</option>
											<?php if(!empty($onlineshop)): foreach($onlineshop as $row):?>
											<option value="<?=$row->nama;?>" data-url="<?=$row->url;?>"><?=$row->nama;?></option>
											<?php endforeach;endif;?>
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>Category</label>
										<select name="target" id="os-category" class="form-control " >
											<option class="default-category" value="">Semua Category</option>
										</select>
									</div>
								</div>
							</div>
								<div class="row mb0 mt-10">
									<div class="col-md-12 mb-10">
										<div class="additional">Additional Options</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="os_from">Page From</label>
											<input type="number" name="from" id="os_from" class="form-control" required value="1" min="1" >
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="os_to">Page To</label>
											<input type="number" name="to" id="os_to" class="form-control" required value="1" min="1" >
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="interval">Interval (menit)</label>
											<input type="number" name="interval" id="interval" class="form-control" required value="10" min="1" >
											<p class="help-block">Interval for next run</p>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6" style="float: left;width: 50%;">
										<a href="<?=cn('instagram/data_collection');?>" class="btn btn-default ">
											<i class="fa fa-list" aria-hidden="true"></i> <span>View List</span>
										</a>
									</div>
									<div class="col-md-6" style="float: left;width: 50%;">
										<button type="submit" class="btn btn-primary waves-effect btnExtract pull-right">
											<i class="fa fa-download" aria-hidden="true"></i> <span>Collect</span>
										</button>
									</div>
								</div>
								<div class="row" id="extract-result-onlineshop">
									
								</div>
							</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="col-sm-12 pb-20 hidden " id="extract-result-clone">	
	<label></label><span class="badge"></span>
	<textarea  class="form-control" rows="5" placeholder="Result will be here"></textarea>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
<script>
	$(function(){
		function mynotif(msg, color='red'){
			iziToast.show({
				icon: 'fa fa-bell-o',
				message: msg,
				color: color,
				position: 'bottomCenter'
			});
		}
		
		
		//$('#account').multiselect();
		/*
		$('#type').change(function(){
			var val = $(this).val();
			if(val == 'likers'){
				$('#limit').val(1000);
			}
		});
		*/
		$('#limit').change(function(){
			var type = $('#type').val();
			var val = $(this).val();
			if(type=='ikers' && val >1000 ){
				mynotif('Hanya dapat mengambil 1000 data liker saja');
			}
		})
		
		$('#target-option1').click(function(){
			$('#target-username').show().prop('required', true);
			$('#target-url').hide().prop('required', false);
			$('#type option:eq(0), #type option:eq(1)').prop('disabled', false);
			var val = $('#type').val();
			if(val == 'likers' || val == 'commenters'){
				if($('#target-option1').prop('checked'))
				$('#labelLimit').text('Limit Post');
			}else{
				$('#labelLimit').text('Limit User');
			}
		});
		
		$('#target-option2').click(function(){
			$('#target-url').show().prop('required', true);
			$('#target-username').hide().prop('required', false);
			$('#type option:eq(0), #type option:eq(1)').prop('disabled', true);
			$('#type').val('likers');
			$('#labelLimit').text('Limit User');
		});
		
		
		$('form.formExtract').submit(function(e){
			e.preventDefault();
			var btn = $(this).find('button[type="submit"]');
			var formdata = $(this).serializeArray();
			formdata.push({name: 'token', value: token});
			$.ajax({
				url : '<?=site_url('instagram/data_collection/ajax_collect')?>',
				type:'post',
				dataType:'json',
				data: formdata,
				beforeSend: function(){
					btn.prop('disabled', true).removeClass('btn-primary').addClass('btn-warning');
					$('#extract-result-instagram').empty();
				},
				success: function(d){
					if(d.status=='success'){
						mynotif('Success', 'green');
						$.each(d.data, function(i,e){
							var newElem=$('#extract-result-clone').clone();
							newElem.attr('id', '');
							newElem.removeClass('hidden');
							newElem.addClass('result');
							newElem.find('label').text(i);
							newElem.find('.badge').text(e.count);
							newElem.find('textarea').val(e.list);
							$('#extract-result-instagram').append(newElem);
						});
					}else{
						mynotif(d.message);
						console.log(d.message);
					}
					setTimeout(function(){
						btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
					},2000);
				},
				error: function(d){
					mynotif("Terjadi kesalahan");
					setTimeout(function(){
						btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
					},2000);
				}
			});
		});
		
		$('form.formMarketplace').submit(function(e){
			e.preventDefault();
			var btn = $(this).find('button[type="submit"]');
			var formdata = $(this).serializeArray();
			formdata.push({name: 'token', value: token});
			$.ajax({
				url : '<?=site_url('instagram/data_collection/ajax_collect_marketplace')?>',
				type:'post',
				dataType:'json',
				data: formdata,
				beforeSend: function(){
					btn.prop('disabled', true).removeClass('btn-primary').addClass('btn-warning');
					$('#extract-result-marketplace').empty();
				},
				success: function(d){
					if(d.status=='success'){
						mynotif('Success', 'green');
						$.each(d.data, function(i,e){
							var newElem=$('#extract-result-clone').clone();
							newElem.attr('id', '');
							newElem.removeClass('hidden');
							newElem.addClass('result');
							newElem.find('label').text(i);
							newElem.find('.badge').text(e.count);
							newElem.find('textarea').val(e.list);
							$('#extract-result-marketplace').append(newElem);
						});
					}else{
						mynotif(d.message);
						console.log(d.message);
					}
					setTimeout(function(){
						btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
					},2000);
				},
				error: function(d){
					mynotif("Terjadi kesalahan");
					setTimeout(function(){
						btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
					},2000);
				}
			});
		});
		
		$('form.formOnlineshop').submit(function(e){
			e.preventDefault();
			var btn = $(this).find('button[type="submit"]');
			var formdata = $(this).serializeArray();
			formdata.push({name: 'token', value: token});
			$.ajax({
				url : '<?=site_url('instagram/data_collection/ajax_collect_onlineshop')?>',
				type:'post',
				dataType:'json',
				data: formdata,
				beforeSend: function(){
					btn.prop('disabled', true).removeClass('btn-primary').addClass('btn-warning');
					$('#extract-result-onlineshop').empty();
				},
				success: function(d){
					if(d.status=='success'){
						mynotif('Success', 'green');
						$.each(d.data, function(i,e){
							var newElem=$('#extract-result-clone').clone();
							newElem.attr('id', '');
							newElem.removeClass('hidden');
							newElem.addClass('result');
							newElem.find('label').text(i);
							newElem.find('.badge').text(e.count);
							newElem.find('textarea').val(e.list);
							$('#extract-result-onlineshop').append(newElem);
						});
					}else{
						mynotif(d.message);
						console.log(d.message);
					}
					setTimeout(function(){
						btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
					},2000);
				},
				error: function(d){
					mynotif("Terjadi kesalahan");
					setTimeout(function(){
						btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
					},2000);
				}
			});
		});
		
		function deleteAllCategory(){
			$('#os-category option:not(.default-category)').remove();
		}
		
		var cache_category= [];
		$('#os-web').change(function(){
			
			var val = $(this).val();
			var web = $('#os-web').val();
			if(val=='') {
				deleteAllCategory();
				return false;
			}
			if(cache_category[web] != undefined){
				deleteAllCategory();
				$("#os-category").append(cache_category[web]);
				return false;
			}
			$.ajax({
				url : '<?=site_url('instagram/data_collection/get_category_onlineshop')?>',
				type:'post',
				dataType:'json',
				data: {token: token, web: web},
				beforeSend: function(){
					deleteAllCategory();
				},
				success: function(d){
					if(d.status=='success'){
						cache_category[web] = d.data;
						$("#os-category").append(d.data);
					}
				},
				error: function(d){
					mynotif("Terjadi kesalahan");
					
				}
			});
		});
		
	});
</script>
