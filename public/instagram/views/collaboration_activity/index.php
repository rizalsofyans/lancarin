<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css" />
<style>
  .text-khusus {
    text-transform: none !important;
    font-weight: 400 !important;
    letter-spacing: normal !important;
    font-size: 14px;
    color: #333;
}
</style>
<div class="wrap-content  instagram-app">
        <div class="card">
      <div class="card-header ">
					<div class="card-title">
                        <i class="fa fa-heart-o" aria-hidden="true"></i> Like for Like
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
       <b>Like for like</b> adalah fitur arisan likes yang pesertanya adalah semua pengguna Lancarin yang mengaktifkan fitur ini. Setiap postingan terbaru Anda (dengan interval 10 menit) akan mendapatkan likes secara otomatis dari peserta Like for like.<br><br>Maksud dari interval 10 menit adalah jika akun Anda melakukan posting <u>lebih dari 1 kali dalam waktu 10 menit</u>, maka <u>hanya 1 postingan</u> (postingan pertama) yang mendapatkan likes dari program LFL ini. Jika Anda ingin semua postingan Anda mendapatkan likes dari program LFL ini, maka Anda harus memberikan jeda minimal 10 menit ketika posting lebih dari 1 post.<br><br>
      <b>Perhatian :</b> Pastikan Akun Instagram Anda tidak dalam keadaan diprivate/dilock<br>
      <b>Jumlah Peserta :</b> <?=$member['real'];?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
                    </div>
				</div>
      </div>
	<div class="row">
		<?php if(empty($accounts)):?>
		<div class="dataTables_empty"></div>
		<?php else : foreach ($accounts as $row) :
		?>
		<div class="col-lg-4 col-md-4 mb15">
			<div class="activity-profile">
				<div class="activity-profile-header">
					<div class="info">
						<img src="<?=$row['avatar']?>" class="img-rounded">
						<span class="brand">instagram</span>
						<span class="username"><?=$row['username']?></span>
					</div>
					<div class="clearfix"></div>
					<i class="fa fa-instagram" aria-hidden="true"></i>
				</div>
				<div class="activity-profile-content">
					<div class="status">
						<?php if($row['status']==0):?>
						<?=lang('status')?> <span class="label label-danger pull-right">Re-login</span>
						<?php endif;?>
            <?php if($row['status']==1):?>
						<?=lang('status')?> <span class="label label-success pull-right">Ready</span>
						<?php endif;?>
					</div>
					<div class="status hidden">
						Point
						<span class="label <?=$row['point']<0?'label-danger':$row['point']==0?'label-default':'label-success';?> pull-right"><?=$row['point']?></span>
					</div>
					<form class="form-collaboration" data-id="<?=$row['id'];?>">
						<ul class="list-group">
						  	<li class="list-group-item">
						  		<div class="form-group">
									<span class="checkbox-text-left"> Like</span>
									<div class="activity-option-switch">
										<input class="js-switch collab_like hidden"  id="collab_like_<?=$row['id'];?>" name="like" <?=$row['like_enabled']==1?'checked':''?> type="checkbox" value="1">
										<label class="tgl-btn" for="collab_like_<?=$row['id'];?>"></label>
									</div>
								</div>
						  	</li>
							<li class="list-group-item hidden">
						  		<div class="form-group">
									<span class="checkbox-text-left"> Comment</span>
									<div class="activity-option-switch">
										<input class="js-switch collab_comment hidden"  id="collab_comment_<?=$row['id'];?>" name="comment" <?=$row['comment_enabled']==1?'checked':''?> type="checkbox" value="1">
										<label class="tgl-btn" for="collab_comment_<?=$row['id'];?>"></label>
									</div>
								</div>
						  	</li>
							
							<li class="list-group-item">
						  		<div class="form-group">
									<span class="checkbox-text-left"> Blacklist</span>
									<textarea rows="3" class="form-control collab_blacklist" placeholder="UsernameA, UsernameB, UsernameC" name="blacklist"><?=$row['blacklist']?></textarea>
								</div>
						  	</li> 
						</ul>
					</form>
				</div>
				<div class="activity-profile-footer">
					<button data-id="<?=$row['id']?>" class="btn <?=!$row['start']?'btn-primary':'btn-danger';?> form-control btn-save"><?=!$row['start']?'Start':'Stop';?></button>
				</div>	
			</div>
		</div>
		<?php endforeach;endif;?>
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js"></script>
<script>
$(function(){
	$.each($('.js-switch'), function(i,e){
		$(this).removeClass('hidden');
		new Switchery(e, {color: '#1E88E5'});
	})
	

	function mynotif(msg, color='red'){
		iziToast.show({
			icon: 'fa fa-bell-o',
			message: msg,
			color: color,
			position: 'bottomCenter'
		});
	}

	$('.collab_like, .collab_comment, .collab_blacklist').change(function(){
		changeSetting($(this));
	});


	function changeSetting(el){
		var form = el.closest('form.form-collaboration');
		var id = form.data('id');
		var formdata = form.serializeArray();
		formdata.push({name: 'id', value: id});
		formdata.push({name: 'token', value: token});
		$.ajax({
			url : '<?=site_url('instagram/collaboration_activity/save_setting');?>',
			type:'post',
			dataType:'json',
			data: formdata,
			beforeSend: function(){
				//btn.prop('disabled', true).removeClass('btn-default').addClass('btn-warning');
			},
			success: function(d){
				if(d.status=='success'){
					//mynotif(d.message, 'green');
				}else{
					mynotif(d.message);
				}
			},
			error: function(d){
				mynotif("Terjadi kesalahan");
			}
		});

	}

	$('.btn-save').click(function(){

		var btn = $(this);
		var val = btn.hasClass('btn-primary')?'btn-primary':'btn-danger';
		$.ajax({
			url : '<?=site_url('instagram/collaboration_activity/change_status');?>',
			type:'post',
			dataType:'json',
			data: {token: token, id: btn.data('id')},
			beforeSend: function(){
				$('.loading-overplay').show();
			},
			success: function(d){
				if(d.status=='success'){
					mynotif(d.message, 'green');
					location.reload();
				}else{
					mynotif(d.message);
				}
				$('.loading-overplay').hide();
			},
			error: function(d){
				mynotif("Terjadi kesalahan");
				$('.loading-overplay').hide();
			}
		});
	})

});
</script>