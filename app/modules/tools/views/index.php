<div class="wrap-content tab-list">
	<form id="form-watermark">
    <div class="row">
		<div class="col-md-3">
			<div class="card">
				<div class="card-header">
					<div class="card-title">
						<i class="fa fa-instagram" aria-hidden="true"></i> Accounts
					</div>
				</div>
				<div class="card-block p0">
					<div class="list-account max scrollbar scrollbar-dynamic">
						<?php if(!empty($accounts)){
							foreach ($accounts as $key => $row) {
						?>

						<a href="javascript:void(0);" class="item <?=$key==0?'active':'';?>">
							<img class="img-circle" src="<?=$row->avatar?>">
							<div class="checked"><i class="ft-check"></i></div>
							<input type="checkbox" name="accounts[]" <?=$key==0?'checked':'';?> value="<?=$row->ids?>" class="hide ckbox">
							<div class="content">
								<span class="title"><?=$row->username?></span>
							</div>
							<div class="clearfix"></div>
						</a>
						<?php }}else{?>
						<div class="empty">
							<span><?=lang("add_an_account_to_begin")?></span>
							<a href="<?=PATH?>account_manager" class="btn btn-primary"><?=lang("add_account")?></a>
						</div>
						<?php }?>
					</div>
				</div>
			</div>
		</div>
		
        <div class="col-md-9">
            <div class="card watermark-box">
                <div class="card-header">
                    <div class="card-title">
                        <i class="ft-award" aria-hidden="true"></i> <?=lang("watermark")?>
                        <div class="pull-right" style="position: relative; top: -7px;">
                            <div class="upload-btn-wrapper">
                                <button class="btn btn-primary"><i class="ft-upload"></i> <?=lang("upload_image")?></button>
                                <input type="file" name="files[]" accept="image/jpeg,image/x-png,image/jpg" class="form-control-file upload-watermark2"  id="upload_watermark2" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-block p0">
                    <div class="tab-content p15">
                        <div class="row">
                            <div class="col-md-5 col-sm-5 mb15">
                                <div class="wt-image">
                                    <img src="<?=BASE?>assets/img/bg-watermark.jpg">
                                    <?php 
                                        $watermark = BASE."assets/img/bg-watermark-warna.png";
										$size = 30;
										$opacity = 70;
										$position = "lb";
										
										if(isset($accounts[0]->watermark) &&!empty($accounts[0]->watermark)){
											$wm = json_decode($accounts[0]->watermark, true);
											if(isset($wm['watermark_image']) && !empty($wm['watermark_image'])){
												$watermark = isset($wm['watermark_image'])?$wm['watermark_image'].'?t='.time():$watermark;
												$size = $wm['watermark_size'];
												$opacity = $wm['watermark_opacity'];
												$position = $wm['watermark_position'];
											}
										}
                                    ?>
                                    <img class="wt-render" src="<?=$watermark?>" >
                                </div>
                            </div>
                            <div class="col-md-7 col-sm-7">
                                <div class="wt-option">
                                    <form>
                                        
                                        <div class="form-group wt-position-box">
                                            <span><?=lang("position")?></span>
                                            <div class="wt-positions">
                                                <div class="wt-position-item pos-lt <?=$position=="lt"?"active":""?>" data-direction="lt"></div>
                                                <div class="wt-position-item pos-ct <?=$position=="ct"?"active":""?>" data-direction="ct"></div>
                                                <div class="wt-position-item pos-rt <?=$position=="rt"?"active":""?>" data-direction="rt"></div>
                                                <div class="wt-position-item pos-lc <?=$position=="lc"?"active":""?>" data-direction="lc"></div>
                                                <div class="wt-position-item pos-cc <?=$position=="cc"?"active":""?>" data-direction="cc"></div>
                                                <div class="wt-position-item pos-rc <?=$position=="rc"?"active":""?>" data-direction="rc"></div>
                                                <div class="wt-position-item pos-lb <?=$position=="lb"?"active":""?>" data-direction="lb"></div>
                                                <div class="wt-position-item pos-cb <?=$position=="cb"?"active":""?>" data-direction="cb"></div>
                                                <div class="wt-position-item pos-rb <?=$position=="rb"?"active":""?>" data-direction="rb"></div>
                                                <input type="hidden" class="wt-position form-control" name="position" value="<?=$position?>">
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="wt-custom-box">
                                            <div class="form-group">
                                                <span><?=lang("size")?></span>
                                                <input type="range" name="size" class="rangeslider hide wt-size" min="0" max="100" step="1" value="<?=$size?>" data-rangeslider data-orientation="vertical" >
                                            </div>
                                            <div class="form-group">
                                                <span><?=lang("transparent")?></span>
                                                <input type="range" name="opacity" class="rangeslider hide wt-transparent" min="0" max="100" step="1" value="<?=$opacity?>" data-orientation="vertical" >
                                            </div> 
                                        </div>     
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="<?=cn("tools/ajax_delete_watermark")?>/<?=!empty($accounts)?$accounts[0]->ids:''?>" data-redirect="<?=current_url()?>" class="btn btn-danger actionItem" id="btn-delete"> <?=lang("delete")?></a>
                    <button type="submit" class="btn btn-primary"> <?=lang("apply")?></button>
                </div>
            </div>
        </div>
    </div>
	</form>
</div>

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
	
	$(".list-account .item").click(function(e){
		e.stopPropagation();
		if(!$(this).hasClass("active")){
			$(".list-account .item").removeClass("active");
			$(".list-account .item input").prop("checked", false);
			
			var ids = $(this).find("input").val();
			getConfig(ids);
			var url = '<?=cn('tools/ajax_delete_watermark');?>/';
			$('#btn-delete').attr('href', url + ids);
			$(this).addClass("active");
			$(this).find("input").prop('checked',true);
		}
		
	});
	
	function getConfig(ids){
		$.ajax({
			url: '<?=site_url('tools/ajax_get_config');?>',
			data: {token:token, ids:ids},
			dataType: 'json',
			type: 'post',
			beforeSend: function(){
				$('.loading-overplay').show();
			},
			success: function(d){
				if(d.status =='success'){
					//change watermark
					$('#upload_watermark2').val('');
					$('.wt-position').val(d.data.position);
					$('.wt-position-item').removeClass('active');
					$('.wt-position-item.pos-'+d.data.position).addClass('active');
					var slider_size = $('input[name="size"]').data('ionRangeSlider');
					var slider_opacity = $('input[name="opacity"]').data('ionRangeSlider');
					slider_size.update({from: d.data.size});
					slider_opacity.update({from: d.data.opacity});
					$('.wt-render').remove();
					$(".wt-image").append('<img class="wt-render" src="'+d.data.image+'">');
					setTimeout(function(){
						FileManager.watermark_render($(".wt-positions .wt-position-item.active"));
					}, 50);
				}else{
					mynotif(d.message);
				}
				$('.loading-overplay').hide();
			},
			error: function(){
				mynotif('Terjadi kesalahan');
				$('.loading-overplay').hide();
			}
		});

	}
	
	$("#upload_watermark2").change(function() {
		input = this;

		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function(e) {
				$('.wt-render').remove();
				$(".wt-image").append('<img class="wt-render" src="'+e.target.result+'">');
				setTimeout(function(){
					FileManager.watermark_render($(".wt-positions .wt-position-item.active"));
				}, 50);
			}

			reader.readAsDataURL(input.files[0]);
		}
	});
	
	$('#form-watermark').submit(function(e){
		e.preventDefault();
		var form = $(this);
		var btn = $(this).find('button[type="submit"]');
		var formdata = new FormData($(this)[0]);
		formdata.append('token', token);
		$.ajax({
			url: '<?=site_url('tools/ajax_upload_watermark');?>',
			data: formdata,
			dataType: 'json',
			cache: false,
			contentType: false,
			enctype: 'multipart/form-data',
			processData: false,
			type: 'post',
			beforeSend: function(){
				btn.prop('disabled', true).addClass('btn-warning').removeClass('btn-primary');
			},
			success: function(d){
				if(d.status =='success'){
					btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
					mynotif(d.message, 'green');
				}else{
					btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
					mynotif(d.message);
				}
				
			},
			error: function(){
				mynotif('Terjadi kesalahan');
				btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
			}
		});

	});
});
</script>