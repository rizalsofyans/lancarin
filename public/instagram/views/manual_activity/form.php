<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" />
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
	
	ul.multiselect-container label.checkbox{
		font-size: 14px;
	}
</style>


<div class="wrap-content tab-list">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
        <?=modules::run("caption/popup")?>
        
				<div class="card-header ">
					<div class="card-title">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i> Other Activity
                    </div>
				</div>
				<div class="card-block p0">
					<div class="tab-content p15">
						<div id="general" class="tab-pane fade in active">
							<form class="form-manual-activity" >
								<div class="row mb0">
									<div class="col-md-6">
										<div class="form-group">
											<label>Used Account</label><br>
											<select name="account[]" id="account" multiple class="form-control show-tick" required >
												<?php if(!empty($accounts)):
												foreach ($accounts as $row) :
												?>
												<option value="<?=$row->id?>"><?=$row->username?></option>
												<?php endforeach;endif;?>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Action Type</label>
											<select name="type" id="type" class="form-control" required >
												<option value="">Choose Type</option>
												<option value="follow">Follow</option>
												<option value="unfollow">Unfollow</option>
												<option value="like">Like</option>
												<option hidden value="unlike">Unlike</option>
												<option value="comment">Comment</option>
												<option hidden value="delete_comment">Delete Comment</option>
												<option value="delete_media">Delete Post</option>
												<option value="direct_message">Direct Message</option>
											</select>
										</div>
									</div>
									<div class="col-md-12">
										<label>Target <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Target dapat berupa Username/Userid/Url Username/Url Post/Postid. Pemisah berupa koma/spasi/baris baru"></i></label>
										<textarea rows="5" name="target" id="target" class="form-control" placeholder="Target A, Target B, Target C"></textarea>
									</div>
									<div class="col-md-12 exceptional-target" style="display:none;">
										<label>Exceptional Target <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Target yang ingin dikecualikan"></i></label>
										<textarea rows="3" name="config[exceptional_target]" id="exceptional-target" class="form-control" placeholder="Target A, TargetB, TargetC"></textarea>
									</div>
									<div class="col-md-12 message" style="display:none;">
										<label>Pesan <i class="fa fa-question-circle-o btn-help-message" data-toggle="tooltip" title="Klik tombol berikut untuk mengetahui informasi tambahan"></i></label>
										<div class="form-group form-caption">
											<div class="list-icon">
												<a href="javascript:void(0);" class="getCaption" data-toggle="tooltip" data-placement="left" title="<?=lang("get_caption")?>"><i class="ft-command"></i></a>
												<a href="javascript:void(0);" data-toggle="tooltip" class="saveCaption" data-placement="left" title="<?=lang("save_caption")?>"><i class="ft-save"></i></a>
												<a href="javascript:void(0);" data-toggle="tooltip" class="btn-help-message" data-placement="left" title="Dokumentasi format pesan"><i class="fa fa-book"></i></a>
											</div>
											<textarea class="form-control post-message" name="config[message]" rows="3" placeholder="{Hi|Dear|Hai|Halo}, {target_fullname}. Perkenalkan kami {my_fullname} ingin {memberikan|menghadiahkan} anda sebuah {produk_255} secara {free|cuma2|gratis}" style="height: 114px;"></textarea>
										</div>
									</div>
								</div>
								<div class="row mb0 mt-10">
									<div class="col-md-12 mb-10">
										<div class="additional">Additional Options</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="start">Start Time</label>
											<input type="text" name="start" id="start" class="form-control mydatetime" required value="<?=NOW;?>"  >
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="delay">Delay (menit) <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Delay per account"></i></label>
											<input type="number" name="delay" id="delay" class="form-control" required value="1" min="1" >
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="interval">Interval (menit) <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Interval for next run"></i></label>
											<input type="number" name="interval" id="interval" class="form-control" required value="10" min="1" >
										</div>
									</div>
									<div class="col-md-5">
										<div class="form-group">
											<label for="interval">Job name <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Untuk mempermudah mengingat pengelompokan. "></i></label>
											<select name="jobname" id="jobname" class="form-control" >
												<option value="">Auto Generate</option>
												<?php if(!empty($jobname)): foreach($jobname as $job):?>
												<option value="<?=$job?>"><?=$job?></option>
												<?php endforeach;endif;?>
											</select>
										</div>
									</div>
									<div class="col-md-3 not-follow-only" style="display:none;">
										<div class="form-group">
											<label for="interval">Not Follower Only <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Hanya unfollow orang yang belum memfollow anda. "></i></label>
											<select name="config[notfollow]" id="config-not-follow" class="form-control" >
												<option value="">No</option>
												<option value="1">Yes</option>
											</select>
										</div>
									</div>
									<div class="col-md-3 follow-only" style="display:none;">
										<div class="form-group">
											<label for="interval">Follower Only <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Hanya orang yang anda follow saja."></i></label>
											<select name="config[follow_only]" id="config-follow-only" class="form-control" >
												<option value="">No</option>
												<option value="1">Yes</option>
											</select>
										</div>
									</div>
									
									<div class="col-md-3 min-like-only" style="display:none;">
										<div class="form-group">
											<label for="interval">Min Like <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Hanya media dengan minimal like."></i></label>
											<input type="number" min="0" value="0" name="config[min_like]" id="config-min-like" class="form-control" >
										</div>
									</div>
									
									<div class="col-md-3 min-comment-only" style="display:none;">
										<div class="form-group">
											<label for="interval">Min Comment <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Hanya media dengan minimal comment."></i></label>
											<input type="number" min="0" value="0" name="config[min_comment]" id="config-min-comment" class="form-control" >
										</div>
									</div>
									
									<div class="col-md-3 min-post-time-only" style="display:none;">
										<div class="form-group">
											<label for="post-time">Min Post Time <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Hanya media yang di post setelah waktu tertentu."></i></label>
											<input type="text"  value="<?=date('Y-m-d H:i:s', strtotime("-1 year"));?>" name="config[post_time]" id="config-post-time" class="form-control" >
										</div>
									</div>
									
									
								</div>
								<div class="row mb0 mt-10">
									<div class="col-md-12 mb-10">
										<div class="additional">Custom Time <button type="button" class="btn btn-default btn-sm btn-show-custom-time" value="hide"><i class="fa fa-plus"></i></button></div>
									</div>
									<div class="box-custom-time" style="display:none;" >
									<div class="col-md-3">
										<button type="button" class="btn btn-default btn-custom-time" value="allday">Allday</button> 
										<button type="button" class="btn btn-default btn-custom-time" value="weekend">Weekend</button>
										<button type="button" class="btn btn-default btn-custom-time" value="rabu-jumat">Rabu-Jumat</button>
									</div>
									<div class="col-md-9">
										<?php 
										$day = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
										for($a=0;$a<=6;$a++): ?>
										<div class="pure-checkbox grey mr15">
											<input type="checkbox" name="days[]" id="check_day_<?=$a?>" class="filled-in chk-col-red checkItem" checked value="<?=$a;?>">
											<label class="" for="check_day_<?=$a?>">&nbsp;</label> <?=$day[$a];?>
										</div>
										<?php endfor;?>
									</div>
									</div>
								</div>
								<div class="row mt-10">
									<div class="box-custom-time" style="display:none;">
									<div class="col-md-3">
										<button type="button" class="btn btn-default btn-custom-time" value="24jam">24 Jam</button> 
										<button type="button" class="btn btn-default btn-custom-time" value="14-17">14-17</button>
										<button type="button" class="btn btn-default btn-custom-time" value="17-21">17-21</button>
									</div>
									<div class="col-md-9">
										<?php for($a=0;$a<24;$a++):?>
										<div class="pure-checkbox grey mr15">
											<input type="checkbox" name="hours[]" id="check_hour_<?=$a?>" class="filled-in chk-col-red checkItem" checked value="<?=$a;?>">
											<label class="" for="check_hour_<?=$a?>">&nbsp;</label> <?=$a<10?'0'.$a:$a;?>
										</div>
										<?php endfor;?>
									</div>
									</div>
								</div>
								<div class="row mb0 mt-10">
									<div class="col-md-12 mb-10">
										<div class="additional">Information</div>
									</div>
									<div class="col-md-6 information">
										<table class="table">
											<tr>
												<th>Waktu Mulai</th>
												<td class="start-time"></td>
											</tr>
											<tr>
												<th>Action Per jam</th>
												<td class="speed-per-hour"></td>
											</tr>
											<tr>
												<th>Action Per hari</th>
												<td class="speed-per-day"></td>
											</tr>
											<tr>
												<th>Total Target</th>
												<td class="total-target"></td>
											</tr>
											<tr>
												<th>Estimasi Berakhir</th>
												<td class="estimate-end-time"></td>
											</tr>
										</table>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6" style="float: left;width: 50%;">
										<a href="<?=cn('instagram/manual_activity');?>" class="btn btn-default ">
											<i class="fa fa-arrow-circle-left" aria-hidden="true"></i> <span>Back</span>
										</a>
									</div>
									<div class="col-md-6" style="float: left;width: 50%;">
										<button type="submit" class="btn btn-primary waves-effect btnExtract pull-right">
											<i class="fa fa-save" aria-hidden="true"></i> <span>Save</span>
										</button>
									</div>
								</div>
								<div class="row" id="extract-result-instagram">
									
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="modal-warning" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Warning</h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary btn-adjust">Adjust</button>
        <button type="button" class="btn btn-danger btn-force">Force</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<?php echo Modules::run('caption/popup_documentation');?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>
<script>
	$(function(){
		var adjust = false;
		var force = false;
		
		function mynotif(msg, color='red'){
			iziToast.show({
				icon: 'fa fa-bell-o',
				message: msg,
				color: color,
				position: 'bottomCenter'
			});
		}
		
		
		$('.btn-show-custom-time').click(function(){//alert(1);
			if($(this).val()=='hide'){
				$('.box-custom-time').show();
				$(this).find('i').addClass('fa-minus').removeClass('fa-plus');
				$(this).val('show');
			}else{
				$('.box-custom-time').hide();
				$(this).find('i').addClass('fa-plus').removeClass('fa-minus');
				$(this).val('hide');
			}
		});
		
		var allhour_pressed = false;
		var allday_pressed = false;
		$('.btn-custom-time').click(function(){
			var val = $(this).val();
			if(val=='24jam'){
				$('input[id^="check_hour_"]').prop('checked', !allhour_pressed);
				allhour_pressed = !allhour_pressed;
			}else if(val =='14-17'){
				$('input[id^="check_hour_"]').prop('checked', false);
				$('input[id="check_hour_14"]').prop('checked', true);
				$('input[id="check_hour_15"]').prop('checked', true)
				$('input[id="check_hour_16"]').prop('checked', true);
				$('input[id="check_hour_17"]').prop('checked', true)
			}else if(val =='17-21'){
				$('input[id^="check_hour_"]').prop('checked', false);				
				$('input[id="check_hour_17"]').prop('checked', true);
				$('input[id="check_hour_18"]').prop('checked', true);
				$('input[id="check_hour_19"]').prop('checked', true);
				$('input[id="check_hour_20"]').prop('checked', true);
				$('input[id="check_hour_21"]').prop('checked', true);
			}else if(val=='allday'){
				$('input[id^="check_day_"]').prop('checked', !allday_pressed);
				allday_pressed = !allday_pressed;
			}else if(val =='weekend'){
				$('input[id^="check_day_"]').prop('checked', false);				
				$('input[id="check_day_0"]').prop('checked', true);
				$('input[id="check_day_6"]').prop('checked', true);
			}else if(val =='rabu-jumat'){
				$('input[id^="check_day_"]').prop('checked', false);				
				$('input[id="check_day_3"]').prop('checked', true);
				$('input[id="check_day_4"]').prop('checked', true);
				$('input[id="check_day_5"]').prop('checked', true);
			}
			parseTarget();
		});
		
		$('input[id^="check_day_"], input[id^="check_hour_"]').click(function(){
			parseTarget(); 
		});
		$('.btn-adjust').click(function(){
			adjust = true;
		});
		
		$('.btn-force').click(function(){
			force = true;
		});
		
		
		$('#jobname').selectize({
			delimiter: ',',
			persist: false,
			create: function(input) {
				return {
					value: input,
					text: input
				}
			}
		});
		
		$('.btn-help-message').click(function(){
			$('#modal-help-message').modal('show');
		});
		
		$('#account').multiselect();
		$('.mydatetime').bootstrapMaterialDatePicker({ format : 'YYYY-MM-DD HH:mm:ss', minDate : new Date() });
		
		function parseTarget(){
			var val = $("#target").val().trim();
			if(val=='') return false;
			var days =[];
			var hours =[];
			var total_hour = $('input[id^="check_hour_"]:checked').length;
			if(total_hour==0 || $('input[id^="check_day_"]:checked').length==0){
				mynotif('Hari dan jam tidak boleh kosong');
				return false;
			}
			
			$('input[id^="check_day_"]:checked').each(function(i,e){
				days.push($(this).val());
			});
			
			$('input[id^="check_hour_"]:checked').each(function(i,e){
				hours.push($(this).val());
			});
			
			val = val.replace(new RegExp(" ", 'g'), "\n");
			val = val.replace(new RegExp(",", 'g'), "\n");
			var split = val.split("\n");
			var total = 0;
			for(a=0;a<split.length;a++){
				if(split[a].trim() != '') total++;
			}
			var start = $('#start').val();
			var interval = new Number($('#interval').val());
			$('.total-target').text(total);
			
			var total2 = total<1?1:total;
			var today = moment.unix('YYYY-MM-DD'); 
			var today_ts = moment(today).unix(); 
			var start_ts = moment(start).unix(); 
			var jam_mulai = moment.unix(start_ts).format('H');
			var jam_pertama = Number($('input[id^="check_hour_"]:checked').eq(0).val()); 
			var jam_pertama_setelah_mulai = jam_pertama;
			for(a=jam_mulai; a<=Number($('input[id^="check_hour_"]:checked:last').val());a++){
				if($('input[id="check_hour_'+a+'"]:checked').length==1){
					jam_pertama_setelah_mulai = a;
					break;
				}
			}
			
			if($('input[id="check_hour_'+jam_mulai+'"]:checked').length==0){				
				if(jam_pertama_setelah_mulai > jam_mulai ){
					//start_ts = start_ts + (3600 * (jam_pertama-jam_mulai));
					start_ts = moment.unix(start_ts).hour(jam_pertama_setelah_mulai).minute(0).second(0).unix();
				}else{
					start_ts = start_ts + 86400;
					start_ts = moment.unix(start_ts).hour(jam_pertama).minute(0).second(0).unix();
				}
			}
			
			$('.start-time').text(moment.unix(start_ts).format('YYYY-MM-DD HH:mm:ss'));
			
			master_start_ts = start_ts;
			for(a=0; a<total;a++){
				calculatNextRun(a,interval, 0, days, hours);
			}
			
			$('.estimate-end-time').text(moment.unix(master_start_ts).format('YYYY-MM-DD HH:mm:ss'));
			var speed = 60/interval;
			$('.speed-per-hour').text(speed>total?total:speed);
			speed = speed * total_hour;
			$('.speed-per-day').text(speed>total?total:speed);
			
		}
		
		var master_start_ts;
		function calculatNextRun($a, $interval, $delay, $days, $hours){
			$start_ts = master_start_ts;
      $next = $start_ts + ($a, $interval*60)+ ($delay*60);

			$d = moment.unix($next).format('d');
			$h = moment.unix($next).format('H');
			$ts = 0;
			$tmp = [];
			while(!in_array($d, $days) || !in_array($h, $hours) ){
				if(!in_array($d, $days)){
					$ts = moment.unix($next).hour(0).minute(0).second(0).unix();
					$next = $next + 86400;
					$d = moment.unix($ts).format('d');
					$h = moment.unix($ts).format('H');
				}
				
				if(!in_array($h,$hours)){
					$ts = moment.unix($next).minute(0).second(0).unix();
					$next = $next + 3600 ;
					$d = moment.unix($ts).format('d');
					$h = moment.unix($ts).format('H');
				}
			}
			
			master_start_ts = $ts==0?$next:$ts;
		}
		
		function in_array($string, $array){
			return $array.indexOf($string) != -1?true:false;
		}
		
		$('#target, #interval, #start').change(function(){
			parseTarget();
		});
		
		$('#type').change(function(){
			var type = $(this).val();
			//$('.exceptional-target').show(); //nggak ke pake keknya
			if(type=='follow'){
				$('.exceptional-target').hide();
			}
			if(type=="unfollow"){
				$('.not-follow-only').show();
			}else{
				$('.not-follow-only').hide();
			}
			if(type=="delete_post"){
				$('.min-comment-only, .min-like-only, .min-post-time-only').show();
			}else{
				$('.min-comment-only, .min-like-only, .min-post-time-only').hide();
			}
			
			if(type=="direct_message" || type=="comment"){
				$('.message').show();
			}else{
				$('.message').hide();
			}
			
			if(type=="direct_message"){
				$('.follow-only').show();
			}else{
				$('.follow-only').hide();
			}
		});
		
		
		$('form.form-manual-activity').submit(function(e){
			e.preventDefault();
			var btn = $(this).find('button[type="submit"]');
			var formdata = $(this).serializeArray();
			if($('#target').val().trim()==''){
				mynotif('Target tidak boleh kosong');
				return false;
			}
			formdata.push({name: 'token', value: token});
			formdata.push({name: 'adjust', value: adjust});
			formdata.push({name: 'force', value: force});
			formdata.push({name: 'start-time', value: $('.start-time').text()});
			$.ajax({
				url : '<?=site_url('instagram/manual_activity/save')?>',
				type:'post',
				dataType:'json',
				data: formdata,
				beforeSend: function(){
					btn.prop('disabled', true).removeClass('btn-primary').addClass('btn-warning');
				},
				success: function(d){
					adjust = false;
					force = false;
					if(d.status=='success'){
						mynotif('Success', 'green');
					}else if(d.status=='warning'){
						mynotif('Warning', 'yellow');
						$('#modal-warning .modal-body').html(d.message);
						$('#modal-warning').modal('show');
					}else{
						mynotif(d.message);
					}
					setTimeout(function(){
						btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
					},2000);
				},
				error: function(d){
					adjust = false;
					force = false;
					mynotif("Terjadi kesalahan");
					setTimeout(function(){
						btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
					},2000);
				}
			});
		});
		
		
	});
</script>