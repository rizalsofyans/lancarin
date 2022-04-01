<div class="wrap-content container schedules schedules-list" data-action="<?=cn(segment(1)."/post/ajax_schedules")?>" data-content="ajax-sc-list" data-append_content="1" data-result="html" data-page="0" data-hide-overplay="0">
    <div class="row">
        <div class="col-md-12">
            <div class="sc-head">
                <div class="item sc-processing <?=!get("t")?"active":""?>">
                    <a href="<?=cn(segment(1)."/post/schedules/{$date}")?>" class="name"><?=lang('processing')?> <span><?=($count_status && isset($count_status[1]))?$count_status[1]:0?></span></a>
                </div>
                <div class="item sc-complete <?=get("t")==2?"active":""?>">
                    <?php
                    $count_total = 0;
                    if(isset($count_status[2])) $count_total += $count_status[2];
                    if(isset($count_status[3])) $count_total += $count_status[3];
                    ?>
                    <a href="<?=cn(segment(1)."/post/schedules/{$date}?t=2")?>" class="name"><?=lang('complete')?> <span><?=$count_total?></span></a>
                </div>
            </div>
            <div class="sc-actions mb15">
                <div class="sc-form form-inline">
                    <span class="small"> <?=lang('select_account')?> </span>
                    <input type="hidden" name="schedule_type" value="<?=get("t")?>" >
                    <div class="form-group">
                        <select class="form-control" name="schedule_account">
                            <?php if(!empty($accounts)){
                            foreach ($accounts as $key => $row) {
                            ?>
                            <option value="<?=$row->ids?>"><?=$row->username." (".$row->total.")"?></option>
                            <?php }}?>
                        </select>
                    </div>
                    <a href="<?=cn(segment(1)."/post/ajax_delete_schedules")?>" data-redirect="<?=current_url()?>" data-id="-1" class="btn btn-danger pull-right actionItem" data-confirm="Are you sure want delete it?"> <?=lang('delete_all')?></a>
                    <div class="clearfix"></div>
                </div>  
            </div>
            <div class="sc-list">
                <div class="row ajax-sc-list">
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal-edit-schedule" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Schedule</h4>
      </div>
	          <form id="form-edit-schedule">

      <div class="modal-body">
			<input type="hidden" name="id" id="edit-id">
		   <div class="form-group form-caption">
				<div class="list-icon">
					<a href="javascript:void(0);" class="getCaption" data-toggle="tooltip" data-placement="left" title="<?=lang("get_caption")?>"><i class="ft-command"></i></a>
					<a href="javascript:void(0);" data-toggle="tooltip" class="saveCaption" data-placement="left" title="<?=lang("save_caption")?>"><i class="ft-save"></i></a>
				</div>
				<textarea class="form-control post-message" required name="caption" id="edit-caption" rows="6" placeholder="<?=lang('add_a_caption')?>" style="height: 114px;"></textarea>
			</div>
		  <div class="form-group">
				<label for="time_post"> <?=lang('time_post')?></label>
				<input type="text" name="time_post" required class="form-control mydatetime " id="edit-time">
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Reschedule</button>
        <button type="button" class="btn btn-success btn-post-now">Post Now</button>
      </div>
	  		</form>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


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
		$('.mydatetime').bootstrapMaterialDatePicker({ format : 'YYYY-MM-DD HH:mm:ss', minDate : new Date() });

		$(document).on('click', '.edit-schedule', function(){
			var btn = $(this);
			var id = $(this).data('id');
			$.ajax({
				url: '<?=site_url('instagram/post/ajax_edit_schedules');?>',
				data: {token: token, id: id},
				dataType: 'json',
				type: 'post',
				beforeSend: function(){
					btn.prop('disabled', true).addClass('btn-warning');
				},
				success: function(d){
					if(d.status =='success'){
						$('#edit-id').val(d.id);
						$("#edit-caption").data("emojioneArea").setText(d.caption);
						$('#edit-time').val(d.time);
						$('#modal-edit-schedule').modal('show');
						btn.prop('disabled', false).removeClass('btn-warning');
					}else{
						mynotif(d.message);
						btn.prop('disabled', false).removeClass('btn-warning');
					}
				},
				error: function(){
					mynotif('Data yang Anda masukkan salah atau tidak ditemukan');
					btn.prop('disabled', false).removeClass('btn-warning');
				}
			});
			
		});
		
		$(document).on('submit', '#form-edit-schedule', function(e){
			e.preventDefault();
			var form = $(this);
			formdata = form.serializeArray();
			btn = form.find('button[type="submit"]');
			formdata.push({name: 'token', value: token});
			$.ajax({
				url: '<?=site_url('instagram/post/ajax_update_schedules');?>',
				data: formdata,
				dataType: 'json',
				type: 'post',
				beforeSend: function(){
					btn.prop('disabled', true).addClass('btn-warning');
				},
				success: function(d){
					if(d.status =='success'){
						mynotif(d.message, 'green');
						btn.prop('disabled', false).removeClass('btn-warning');
						location.reload();
					}else{
						mynotif(d.message);
						btn.prop('disabled', false).removeClass('btn-warning');
					}
				},
				error: function(){
					mynotif('Data yang Anda masukkan salah atau tidak ditemukan');
						btn.prop('disabled', false).removeClass('btn-warning');
				}
			});
		});
		
		$(document).on('click', '.btn-post-now', function(){
			var form = $('#form-edit-schedule');
			formdata = form.serializeArray();
			btn = $(this);
			formdata.push({name: 'token', value: token});
			$.ajax({
				url: '<?=site_url('instagram/post/ajax_post_now');?>',
				data: formdata,
				dataType: 'json',
				type: 'post',
				beforeSend: function(){
					btn.prop('disabled', true).addClass('btn-warning');
				},
				success: function(d){
					if(d.status =='success'){
						mynotif(d.message, 'green');
						btn.prop('disabled', false).removeClass('btn-warning');
						location.reload();
					}else{
						mynotif(d.message);
						btn.prop('disabled', false).removeClass('btn-warning');
					}
				},
				error: function(){
					mynotif('Data yang Anda masukkan salah atau tidak ditemukan');
						btn.prop('disabled', false).removeClass('btn-warning');
				}
			});
		});
		

	
});
</script>