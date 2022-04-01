<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" />
<style>
	.p-10{
		padding: 10px;
	}
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
        <?=modules::run("caption/popup")?>
        
				<div class="card-header ">
					<div class="card-title">
                        <i class="fa fa-retweet" aria-hidden="true"></i> Auto Repost
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
       <b>Auto repost</b> berbeda dengan <b>manual repost</b>. Mohon pastikan settingan anda benar. Jika tidak maka akun anda akan melakukan spam posting yang tidak sesuai.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
                    </div>
				</div>
				<div class="card-block p0">
					<div class="tab-content p15">
						<div id="general" class="tab-pane fade in active">
							<form class="form-auto-repost" >
								<div class="row mb0">
									<div class="col-md-6">
										<input type="hidden" value="<?=!empty($data)?$data['id']:''?>" name="id">
										<div class="form-group">
											<label>Used Account</label><br>
											<select name="account[]" id="account" multiple class="form-control show-tick" required >
												<?php if(!empty($accounts)):
												foreach ($accounts as $row) :
												?>
												<option <?=(!empty($data) && $data['account']==$row->id)?'selected':''?> value="<?=$row->id?>"><?=$row->username?></option>
												<?php endforeach;endif;?>
											</select>
										</div>
									</div>
									<div class="col-md-12">
										<label>Target <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Target dapat berupa Url username IG/ username IG/ Url toko Marketplace."></i></label>
										<textarea rows="5" name="target" id="target" class="form-control" placeholder="Target A, TargetB, TargetC"><?=!empty($data)?$data['target']:''?></textarea>
									</div>
									<div class="col-md-6">
										<label>Must containt keyword <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Settingan tambahan agar target mengikuti harus memiliki keyword berikut. Support multiple keyword"></i></label>
										<input type="text" name="config[include]" id="keyword_include" class="form-control" placeholder="Keyword">
									</div>
									<div class="col-md-6">
										<label>Exclude keyword <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Settingan tambahan untuk mengindahkan postingan yang memiliki keyword berikut. Support multiple keyword"></i></label>
										<input type="text" name="config[exclude]" id="keyword_exclude" class="form-control" placeholder="Keyword">
									</div>
									<div class="col-md-12 message" >
										<label>Caption <i class="fa fa-question-circle-o btn-help-message" data-toggle="tooltip" title="Klik tombol berikut untuk mengetahui informasi tambahan"></i></label>
										<div class="form-group form-caption">
											<div class="list-icon">
												<a href="javascript:void(0);" class="getCaption" data-toggle="tooltip" data-placement="left" title="<?=lang("get_caption")?>"><i class="ft-command"></i></a>
												<a href="javascript:void(0);" data-toggle="tooltip" class="saveCaption" data-placement="left" title="<?=lang("save_caption")?>"><i class="ft-save"></i></a>
												<a href="javascript:void(0);" data-toggle="tooltip" class="btn-help-message" data-placement="left" title="Dokumentasi format pesan"><i class="fa fa-book"></i></a>
											</div>
											<textarea class="form-control post-message" name="message" rows="3" placeholder="{original_caption}" style="height: 114px;"><?=!empty($data)?$data['message']:'{original_post}'?></textarea>
										</div>
									</div>
								</div>
								
								<div class="row mb0 mt-10">
                  <div class="col-md-12">
										<div class="form-group">
											<div class="pure-checkbox grey mr15 mb-10">
												<input type="checkbox" id="md_checkbox_watermark" name="config[watermark]" class="filled-in chk-col-red enable_instagram_watermark" value="on" >
												<label class="p0 m0" for="md_checkbox_watermark" data-toggle="collapse" data-target="#watermark-option">&nbsp;</label>
												<span class="checkbox-text-right"> Watermark </span>
											</div>
											
											<div class="pure-checkbox grey mr15 mb-10">
												<input type="checkbox" id="md_checkbox_signature" name="signature" class="filled-in chk-col-red enable_instagram_signature" value="on">
												<label class="p0 m0" for="md_checkbox_signature" data-toggle="collapse" data-target="#signature-option">&nbsp;</label>
												<span class="checkbox-text-right"> Signature </span>
											</div>
										</div>
									</div>
									<div class="col-md-12 mb-10">
										<div class="additional">Find & Replace Original Caption</div>
									</div>
									<div class="col-md-12 mb-10">
										<button type="button" class="btn btn-default btn-add-pattern"><i class="fa fa-plus"></i> Add Pattern</button> 
										<button type="button" class="btn btn-default btn-show-findreplace"><i class="ft-grid"></i> Template Pattern</button> 
									</div>
									<div class="block-pattern col-md-12">
										
									</div>
								</div>
								<div class="row mb0 mt-10">
									<div class="col-md-12 mb-10">
										<div class="additional">Additional Options</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="start">Start Time</label>
											<input type="text" name="start" id="start" class="form-control mydatetime" required value="<?=!empty($data)?$data['start_time']:NOW;?>"  >
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="delay">Delay <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Delay per account. Dalam satuan menit."></i></label>
											<input type="number" name="delay" id="delay" class="form-control" required value="1" min="1" >
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="interval">Interval Post <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Interval for next run. Dalam satuan menit."></i></label>
											<input type="number" name="config[interval_post]" id="interval" class="form-control" required value="<?=!empty($data)?$data['interval_post']:10?>" min="1" >
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="interval">Interval Scan <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Interval for scanning post dari target. Dalam satuan menit. Contoh: Jika nilai anda adalah 30 menit dan target melakukan update 10 post dalam 30 menit, maka anda akan tetap mendapat 10 postingan tersebut."></i></label>
											<input type="number" name="interval" id="interval" class="form-control" required value="<?=!empty($data)?$data['interval_scan']:30?>" min="10" >
										</div>
									</div>
								</div>
								<div class="row mb0 mt-10">
									<div class="col-md-12 mb-10">
										<div class="additional">Custom Time <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Custom time untuk interval post, bukan untuk interval scan"></i><button type="button" class="btn btn-default btn-sm btn-show-custom-time" value="hide"><i class="fa fa-plus"></i></button></div>
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
											<?php if(!empty($data)):?>
											<input type="checkbox" name="config[days][]" id="check_day_<?=$a?>" class="filled-in chk-col-red checkItem" <?=in_array($a, $data['days'])?'checked':''?> value="<?=$a;?>">
											<?php else:?>
											<input type="checkbox" name="config[days][]" id="check_day_<?=$a?>" class="filled-in chk-col-red checkItem" checked value="<?=$a;?>">
											<?php endif;?>
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
											<?php if(!empty($data)):?>
											<input type="checkbox" name="config[hours][]" id="check_hour_<?=$a?>" class="filled-in chk-col-red checkItem" <?=in_array($a, $data['hours'])?'checked':''?> value="<?=$a;?>">
											<?php else:?>
											<input type="checkbox" name="config[hours][]" id="check_hour_<?=$a?>" class="filled-in chk-col-red checkItem" checked value="<?=$a;?>">
											<?php endif;?>
											<label class="" for="check_hour_<?=$a?>">&nbsp;</label> <?=$a<10?'0'.$a:$a;?>
										</div>
										<?php endfor;?>
									</div>
									</div>
								</div>
								<div class="row mb0 mt-10 hidden">
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
										<a href="<?=cn('instagram/auto_repost');?>" class="btn btn-default ">
											<i class="fa fa-list" aria-hidden="true"></i> <span>View List</span>
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

<!--BEGIN clone pattern-->
<div class="block-clone-pattern hidden mb-10 row">
	<div class="col-md-4">
		<label>Find Pattern</label>
		<textarea class="form-control pattern_from" name="config[pattern_from][]" class="pattern-form"></textarea>
	</div>
	<div class="col-md-4">
		<label>Replace</label>
		<textarea class="form-control pattern_to" name="config[pattern_to][]" class="pattern-to"></textarea>
	</div>
	<div class="col-md-4">
		<label>Copy / Delete</label><br>
		<button type="button" class="btn btn-default btn-clone-pattern"><i class="fa fa-clone"></i></button> 
		<button type="button" class="btn btn-default btn-delete-pattern"><i class="fa fa-minus"></i></button> 
	</div>
</div>
<!--END clone pattern-->


<?php echo Modules::run('caption/popup_documentation');?>
<?php echo Modules::run('caption/popup_findreplace');?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>
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

/*		Menghilangkan 'Anda belum memiliki watermark (sementara)'

		$('#md_checkbox_watermark').click(function(){
			if($(this).prop('checked')){
				$.ajax({
					url: '<?=site_url('instagram/post/check_watermark');?>',
					data: {token:token},
					dataType: 'json',
					type: 'post',
					beforeSend: function(){
						$('.card-overplay').show();
					},
					success: function(d){
						if(d.status =='success'){
							
						}else{
							mynotif(d.message);
							$('#md_checkbox_watermark').prop('checked', false);
						}
						$('.card-overplay').hide();
					},
					error: function(){
						mynotif('Something wrong');
						$('#md_checkbox_watermark').prop('checked', false);
						$('.card-overplay').hide();
					}
				});
			}
		});
*/
		var signature;
		$('#md_checkbox_signature').click(function(){
			var checked = $(this).prop('checked');
			if($(this).prop('checked')){
				$.ajax({
					url: '<?=site_url('caption/get_signature');?>',
					data: {token:token},
					dataType: 'json',
					type: 'post',
					beforeSend: function(){
						$('#md_checkbox_signature').prop('disabled', true);
					},
					success: function(d){
						if(d.status =='success'){
							var t = $(".post-message").data("emojioneArea").getText();
							$(".post-message").data("emojioneArea").setText(t + "\n"+d.data);
							signature = d.data;
						}else{
							mynotif(d.message);
							$('#md_checkbox_signature').prop('checked', false);
						}
						$('#md_checkbox_signature').prop('disabled', false);
					},
					error: function(){
						mynotif('Something wrong');
						$('#md_checkbox_signature').prop('disabled', false).prop('checked', false);
					}
				});
				
			}else{
				var t = $(".post-message").data("emojioneArea").getText();
				t = t.replace("\n"+signature,'');
				$(".post-message").data("emojioneArea").setText(t);
			}
		});
		
		
		
		var maxpattern = 20;
		$('.btn-add-pattern').click(function(){
			var newElem = $('.block-clone-pattern').clone();
			newElem.removeClass('block-clone-pattern hidden');
			newElem.addClass('item-pattern');
			$('.block-pattern').append(newElem);
			$('.btn-add-pattern').prop('disabled', $('.block-pattern .item-pattern').length>=maxpattern);
		});
		
		$('.block-pattern').on('click', '.btn-delete-pattern', function(){
			$(this).closest('.item-pattern').remove();
			$('.btn-add-pattern').prop('disabled', $('.block-pattern .item-pattern').length>=maxpattern);
		});
		
		$('.block-pattern').on('click', '.btn-clone-pattern', function(){
			var parent = $(this).closest('.item-pattern');
			var newElem = parent.clone();
			parent.after(newElem);
			$('.btn-add-pattern').prop('disabled', $('.block-pattern .item-pattern').length>=maxpattern);
		});
		
		$('.btn-show-findreplace').click(function(){
			$('#modal-findreplace').modal('show');
		});
		
		<?php if(isset($data['pattern_from']) && !empty($data['pattern_from'])): for($a=0;$a<count($data['pattern_from']);$a++):?>
		$('.btn-add-pattern').click();
		$('.block-pattern .pattern_from').eq(<?=$a?>).val('<?=$data['pattern_from'][$a]?>');
		$('.block-pattern .pattern_to').eq(<?=$a?>).val('<?=$data['pattern_to'][$a]?>');
		<?php endfor;endif;?>
		
		
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
		
		var selectizeInclude = $('#keyword_include').selectize({
			delimiter: '[[delimiter]]',
			persist: false,
			create: function(input) {
				return {
					value: input,
					text: input
				}
			}
		});

		<?php if(!empty($data) && !empty($data['include'])): foreach($data['include'] as $item):?>
		selectizeInclude[0].selectize.addOption({text: "<?=$item?>", value: "<?=$item?>"});
		selectizeInclude[0].selectize.addItem("<?=$item?>");
		<?php endforeach;endif;?>
		var selectizeExclude = $('#keyword_exclude').selectize({
			delimiter: '[[delimiter]]',
			persist: false,
			create: function(input) {
				return {
					value: input,
					text: input
				}
			}
		});
		<?php if(!empty($data) && !empty($data['exclude'])): foreach($data['exclude'] as $item):?>
		selectizeExclude[0].selectize.addOption({text: "<?=$item?>", value: "<?=$item?>"});
		selectizeExclude[0].selectize.addItem("<?=$item?>");
		<?php endforeach;endif;?>
				
		$('.btn-help-message').click(function(){
			$('#modal-help-message').modal('show');
		});
		
		$('#account').multiselect();
		$('.mydatetime').bootstrapMaterialDatePicker({ format : 'YYYY-MM-DD HH:mm:ss'});
		
		function parseTarget(){return false;
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
			
			val = val.replace(" ", "\n");
			val = val.replace(",","\n");
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
				calculatNextRun(a, interval, 0, days, hours);
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
			$next = $start_ts + ($a*$interval*60)+ ($delay*60);

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
		
		$('#target').change(function(){
			var target = $(this).val();
			parseTarget();
		});
		
		$('form.form-auto-repost').submit(function(e){
			e.preventDefault();
			var btn = $(this).find('button[type="submit"]');
			var formdata = $(this).serializeArray();
			if($('#target').val().trim()==''){
				mynotif('Target tidak boleh kosong');
				return false;
			}
			formdata.push({name: 'token', value: token});
			$.ajax({
				url : '<?=site_url('instagram/auto_repost/save')?>',
				type:'post',
				dataType:'json',
				data: formdata,
				beforeSend: function(){
					btn.prop('disabled', true).removeClass('btn-primary').addClass('btn-warning');
				},
				success: function(d){
					if(d.status=='success'){
						mynotif('Success', 'green');
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
		
		
	});
</script>