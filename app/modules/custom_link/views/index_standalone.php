<?php
$myhost= 'https://bioku.id';
?><!DOCTYPE html>
<html>
<head>
	<title>Lancarin - Aplikasi Instagram Marketing Serba Otomatis</title>
	<meta name="description" content="Datangkan Followers Tertarget yang siap membeli produk Anda kapanpun dengan mengotomatisasi semua kegiatan Instagram Marketing Anda." />
	<meta name="keywords" content="Aplikasi Instagram Marketing, Tools Instagram Marketing, Auto Follow Instagram, Lancarin" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="icon" type="image/png" href="https://lancarin.com/assets/uploads/user1/04b2dde2bd6264ef4409852a9457f13d.png" />
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap-theme.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fontawesome-iconpicker/1.4.1/css/fontawesome-iconpicker.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css" />
	
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.css" />
<style>
	.mt-10{
		margin-top: 10px;
	}
	.mt-20{
		margin-top: 20px;
	}
	.card-header{
		font-size: 24px;
	}
	.card-header{
		margin-bottom: 20px;
	}
</style>
</head>
<body>
<div class="container mt-20">
	<div class="users app-table">
		<div class="card">
			<div class="card-header">
				<div class="card-title">
					<i class="fa fa-link" aria-hidden="true"></i> Custom Link (Bioku.id)
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="card-body">
				<div class="table-filter">
		  <div class="bg-info" style="padding:10px !important;border-radius:0 !important;font-size: 14px;margin-bottom:20px !important;">Dengan menggunakan custom link, dapat memudahkan pelanggan untuk menghubungi Anda. Masukkan link chanel promosi Anda seperti WhatsApp, Line, Website, dan Marketplace. Link ini cocok ditempatkan di kolom link pada profile Instagram Anda.</div>
					<div class="row">
						<div class="col-md-9 col-sm-6">
							<div class="btn-group" role="group" aria-label="export">
								<button class="btn btn-default btn-add" <?=$max_user_link<=$list_link->num_rows()&&session('uid') !=1?'disabled':''?>><i class="fa fa-plus"></i> <?=lang("add_new")?></button>
							</div>
						</div>
					</div>
				</div>
				<div class="table-responsive mt-20">
					<table id="table-custom" class="table table-bordered table-striped table-hover mb0" >
						<thead>
							<tr>
								<th class="hidden-xs hidden-sm">Name</th>
								<th>Link</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php if($list_link->num_rows()==0):?>
							<tr class="table-empty">
								<td colspan="4">Belum ada link</td>
							</tr>
							<?php else: foreach($list_link->result() as $row):?>
							<tr data-id="<?=$row->id?>">
								<td class="hidden-xs hidden-sm"><a target="_blank" href="<?=$myhost.'/'.$row->slug?>"><?=$row->slug?></a></td>
								<td><input class="form-control" type="text" value="<?=$myhost.'/'.$row->slug?>" id="myInput"></td>
								<td><button type="button" data-id="<?=$row->id?>" class="btn btn-default btn-edit"><i class="fa fa-edit"></i></button> <button data-id="<?=$row->id?>" type="button" class="btn btn-default btn-delete"><i class="fa fa-trash"></i></button> <button data-id="<?=$row->id?>" title="Copy Link" type="button" class="btn btn-default btn-copy" data-clipboard-text="<?=$myhost.'/'.$row->slug?>"><i class="fa fa-copy"></i></button></td>
							</tr>
							<?php endforeach;endif;?>
							
						</tbody>
					</table>
				</div>
			</div>
			<div class="card-footer">
			</div>
		</div>
	</div>
	<div class="text-center mt-20">
		<a href="<?=site_url('profile/user_payment_history');?>"><button class="btn btn-primary" style="background-color: #337ab7 !important; border-color: #2e6da4 !important;"><i class="fa fa-user"></i>&nbsp;Profile</button></a> 
		<a href="<?=site_url('pricing');?>"><button class="btn btn-success" style="background-color: #28a745 !important; border-color: #28a745 !important;"><i class="fa fa-money"></i>&nbsp;Pricing</button></a>
	</div>
</div>

<div id="modal-add" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Custom Link</h4>
      </div>
     <form id="form-add">
      <div class="modal-body">
      	<div class="row">
			<input type="hidden" name="id" id="custom-link-id">
      		<div class="col-md-6 mb-10">
      			<label for="account">Profile Type</label>
				<select name="profile-type" id="profile-type" class="form-control" >
					<option value="">Select Media Social Type</option>
					<option value="facebook">Facebook</option>
					<option value="instagram">Instagram</option>
					<option value="twitter">Twitter</option>
					<option value="gravatar">Gravatar</option>
				</select>
			</div>
			<div class="col-md-6 mb-10">
      			<label for="account">Profile Username</label>
				<input name="profile-username" id="profile-username" class="form-control" placeholder="username" >
			</div>
			<div hidden class="col-md-12 mt-10 mb-10">
      			<label for="account">Body Color</label>
				<input type="color" name="body_color" id="body_color" class="form-control color-picker"  value="#FFFFFF" >
			</div>
			<div class="col-md-12 mb-10">
      			<label for="account">Description (opsional)</label>
				<input name="description" id="description" class="form-control" placeholder="description" >
			</div>
     <div class="col-md-12 mb-10">
      			<label for="account">Facebook Pixel (opsional)</label>
				<input name="fb_pixel" id="fb_pixel" class="form-control" placeholder="facebook pixel" >
			</div>
			<div class="col-md-12">
				<div class="form-group">
				<label for="fullname">Link </label>
					<div class="input-group" style="padding:0;">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" ><?=$myhost?>/</button>
						</span>
						<input type="text" class="form-control" id="slug" name="slug" required value="" aria-describedby="btn-copy-code">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" id="btn-copy-code" data-clipboard-target="#link-ref"><i class="fa fa-copy"></i> <span class="cp-text">Copy</span></button>
						</span>
					</div>
				</div>
			</div>
			<div class="col-md-12">
      			<button type="button" class="btn btn-default btn-add-custom"><i class="fa fa-plus"></i> Add</button>
			</div>
			<div class="col-md-12 box-link row">
      			
			</div>
      	</div>
         <div class="bg-info" style="padding:10px !important;border-radius:0 !important;font-size: 14px;margin-top:10px !important;">
    				- Title adalah teks yang tampil di tombol.<br>
    				- Awali link dengan <strong>https://</strong><br>
    				- Untuk Generator Link Chat <strong>Whatsapp</strong>, Anda dapat gunakan fitur <a target="_blank" href="https://wasap.goricreative.id/"><strong>WASAP</strong></a><br>
    				- Untuk <strong>LINE@</strong>, gunakan format: <em>https://line.me/R/ti/p/%40<strong>idlineat</strong></em><br>
    				- Untuk <strong>Marketplace</strong>, gunakan nama domain marketplace /username, contoh: <em>https://tokopedia.com/<strong>username</strong></em>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
	  </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="col-md-12 hidden clone-custom">
	<div class="row">
		<div class="col-md-4">
			<label for="account">Icon</label>
			<input type="text" name="icon[]" readonly class="form-control icon" placeholder="icon" >
		</div>
		<div class="col-md-6">
		<br>
			<button type="button" class=" btn btn-default btn-remove"><i class="fa fa fa-trash-o"></i> Delete</button>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<label for="account">Background Color</label><br>
			<input type="color" name="bgcolor[]" class=" bgcolor color-picker" value="#4CAF50" >
		</div>
		<div class="col-md-6">
			<label for="account">Text Color</label><br>
			<input type="color" name="textcolor[]" class="form-control textcolor color-picker" value="#ffffff" >
		</div>
	</div>
	<div class="row mb-10">
		<div class="col-md-6">
			<label for="account">Title</label>
			<input name="title[]" required class="form-control title" placeholder="title" >
		</div>
		<div class="col-md-6">
			<label for="account">Url <i class="fa fa-question-circle-o webuiPopover" data-content="Awali link dengan https://, contoh: https://facebook.com/buzztheday" data-title="Url-CustomLink"></i></label>
			<input name="url[]" class="form-control url" placeholder="url" >
		</div>
		
	</div>
	<hr>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fontawesome-iconpicker/1.4.1/js/fontawesome-iconpicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
<script>
	$(function(){
		var token = '<?=$this->security->get_csrf_hash()?>';
		
		new ClipboardJS('#btn-copy-code', {
			container: document.getElementById('modal-add'),
			text: function(trigger) {
				return trigger.getAttribute('data-clipboard-text');
			}
		}).on('success', function(event) {
			event.clearSelection();
			event.trigger.classList.add('btn-warning');
			event.trigger.classList.remove('btn-default');
			setTimeout(function() {
				event.trigger.classList.add('btn-default');
				event.trigger.classList.remove('btn-warning');
			}, 1000);
		});
		
		$('#slug').on('input', function(){
			var val = $('#btn-site-url').text() + $(this).val();
			$('#btn-copy-code').attr('data-clipboard-text', val);
		});
		
		function initCopy(){
			new ClipboardJS('.btn-copy').on('success', function(event) {
				event.clearSelection();
				event.trigger.classList.add('btn-warning');
				event.trigger.classList.remove('btn-default');
				setTimeout(function() {
					event.trigger.classList.add('btn-default');
					event.trigger.classList.remove('btn-warning');
				}, 1000);
			});
		}
		initCopy();
		function initColor(el){
			el.spectrum({
				preferredFormat: "hex",
				showInput: true,
				appendTo: "#modal-add"
			});
		}
		initColor($("#body_color"));
		
		function mynotif(msg, color='red'){
			iziToast.show({
				icon: 'fa fa-bell-o',
				message: msg,
				color: color,
				position: 'bottomCenter'
			});
		}
		var max_user_link = <?=$max_user_link;?>;
		var max_custom_link = <?=get_option('max_custom_link', 10);?>;
		$('.btn-add').click(function(){
			resetForm();
			$('#modal-add').modal('show');
		});
		
		
		
		function resetForm(){
			$('#form-add')[0].reset();
			$('.box-link').empty();
			calculateNumber();
		}
		
		function addClone(){
			var newElem = $('.clone-custom').clone();
			newElem.removeClass('clone-custom hidden').addClass('custom-item');
			$('.box-link').append(newElem);
			initColor($('.box-link .custom-item:last .color-picker'));
			$('.box-link .icon').iconpicker();
			calculateNumber();
		}
		
		function calculateNumber(){
			if($('.custom-item').length>=max_custom_link) $('.btn-add-custom').prop('disabled',true);
			else $('.btn-add-custom').prop('disabled',false);
			<?php if(session('uid') != 1):?>
			if($('#table-custom tbody tr:not(.table-empty)').length>=max_custom_link) $('.btn-add').prop('disabled',true);
			else $('.btn-add').prop('disabled',false);
			<?php endif;?>
		}
		
		$('.btn-add-custom').click(function(){
			addClone();
		});
		$('.box-link').on('click', '.btn-remove', function(){
			$(this).closest('.custom-item').remove();
			calculateNumber();
		});
		
		$('#form-add').submit(function(e){
			e.preventDefault();
			var btn = $(this).find('button[type="submit"]');
			var formdata = $(this).serializeArray();
			formdata.push({name: 'token', value: token});
			$.ajax({
				url : '<?=site_url('custom_link/ajax_update')?>',
				type:'post',
				dataType:'json',
				data: formdata,
				beforeSend: function(){
					btn.prop('disabled', true).removeClass('btn-primary').addClass('btn-warning');
				},
	success: function(d){
					if(d.status=='success'){
						mynotif(d.message, 'green');
						var id = $('#custom-link-id').val();
						if(id==''){
							$('#table-custom tr.table-empty').remove();
							var tr = '<tr data-id="'+d.data.id+'">'+
							'<td><a target="_blank" href="<?=$myhost?>/'+d.data.slug+'">'+d.data.slug+'</a></td>'+
							'<td><input class="form-control" type="text" value="<?=$myhost?>/'+d.data.slug+'" id="myInput"></td>'+
							'<td><button type="button" data-id="'+d.data.id+'" class="btn btn-default btn-edit"><i class="fa fa-edit"></i></button> <button data-id="'+d.data.id+'" type="button" class="btn btn-default btn-delete"><i class="fa fa-trash"></i></button> <button data-id="'+d.data.id+'" title="copy" type="button" class="btn btn-default btn-copy" data-clipboard-text="<?=$myhost?>/'+d.data.slug+'"><i class="fa fa-copy"></i></button></td>'+
							'</tr>';
							$('#table-custom tbody').append(tr);
							initCopy();
						}else{
							var td = $('#table-custom tbody tr[data-id="'+id+'"]').find('td');
							td.eq(0).find('a').attr('src', '<?=$myhost?>/'+d.data.slug).text(d.data.slug);
							td.eq(1).text('<?=$myhost?>/'+d.data.slug);
						}
						$('#modal-add').modal('hide');
					}else{
						mynotif(d.message);
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

		$('#table-custom').on('click', '.btn-edit', function(){
			var btn = $(this);
			$.ajax({
				url : '<?=site_url('custom_link/get_data')?>',
				type:'post',
				dataType:'json',
				data: {token: token, id: btn.data('id')},
				beforeSend: function(){
					btn.prop('disabled', true).removeClass('btn-default').addClass('btn-warning');
				},
				success: function(d){
					if(d.status=='success'){
						resetForm();
						var myform = $('#form-add');
						myform.find('#custom-link-id').val(d.data.id);
						myform.find('#profile-type').val(d.data.profile_type);
						myform.find('#profile-username').val(d.data.profile_username);
						myform.find('#description').val(d.data.description);
            myform.find('#fb_pixel').val(d.data.fb_pixel);
						myform.find('#body_color').val(d.data.body_color);
						var val = $('#btn-site-url').text() + d.data.slug;
						$('#btn-copy-code').attr('data-clipboard-text', val);
						myform.find('#slug').val(d.data.slug);
						$.each(d.data.item, function(i,e){
							addClone();
							var elem = $('.box-link .custom-item:last');
							elem.find('.icon').val(e.icon);
							elem.find('.title').val(e.title);
							elem.find('.url').val(e.url);
							elem.find('.bgcolor').val(e.bgcolor);
							elem.find('.textcolor').val(e.textcolor);
							initColor(elem.find(".color-picker"));
						})
						
						calculateNumber();
						$('#modal-add').modal('show');
					}else{
						mynotif(d.message);
					}
					setTimeout(function(){
						btn.prop('disabled', false).addClass('btn-default').removeClass('btn-warning');
					},2000);
				},
				error: function(d){
					mynotif("Terjadi kesalahan");
					setTimeout(function(){
						btn.prop('disabled', false).addClass('btn-default').removeClass('btn-warning');
					},2000);
				}
			});
		});

		$('#table-custom').on('click', '.btn-delete', function(){
			var btn = $(this);
			$.ajax({
				url : '<?=site_url('custom_link/ajax_delete_item')?>',
				type:'post',
				dataType:'json',
				data: {token: token, id: btn.data('id')},
				beforeSend: function(){
					btn.prop('disabled', true).removeClass('btn-default').addClass('btn-warning');
				},
				success: function(d){
					if(d.status=='success'){
						mynotif(d.message, 'green');
						btn.closest('tr').remove();
						calculateNumber();
					}else{
						mynotif(d.message);
					}
					setTimeout(function(){
						btn.prop('disabled', false).addClass('btn-default').removeClass('btn-warning');
					},2000);
				},
				error: function(d){
					mynotif("Terjadi kesalahan");
					setTimeout(function(){
						btn.prop('disabled', false).addClass('btn-default').removeClass('btn-warning');
					},2000);
				}
			});
		});

		
	});
</script>
</body>
</html>