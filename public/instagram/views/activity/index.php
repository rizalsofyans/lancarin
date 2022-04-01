<!-- Live Chat Widget powered by https://keyreply.com/chat/ -->
<!-- Advanced options: -->
<!-- data-align="left" -->
<!-- data-overlay="true" -->
<style>
  .text-khusus {
    text-transform: none !important;
    font-weight: 400 !important;
    letter-spacing: normal !important;
    font-size: 14px;
    color: #333;
}
  @media screen and (max-width: 992px) {
  form .form-group {
    margin-top: 1.5rem;
  }
}
  
</style>
<script data-align="right" data-overlay="false" id="keyreply-script" src="//lancarin.com/assets/js-landingpage/widgetschat.js" data-color="#3F51B5" data-apps="JTdCJTIyd2hhdHNhcHAlMjI6JTIyNjI4MTkwNTY3OTczOSUyMiwlMjJmYWNlYm9vayUyMjolMjI0NDQ2ODUwNzkyNzI0OTAlMjIlN0Q="></script>
<div class="wrap-content instagram-app">
	<div class="activity-filter">
              <button type="button" class="btn btn-danger btn-sm pull-left" st data-toggle="modal" data-target="#myModal">
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
      <b>Perbedaan Auto Activity dan Manual Activity</b><br><br>
      <b>Auto Activity</b> adalah fitur auto action (follow, likes, comments, direct message, dan unfollow) ke <u>user maupun postingan</u> yang bisa anda targetkan berdasarkan hastags, locations, followers akun kompetitor dan/atau akun anda, followings akun kompetitor dan/atau akun anda, likers postingan akun kompetitor dan/atau akun anda, dan commenters akun kompetitor dan/atau akun anda.<br><br>
      <b>Manual Activity</b> adalah fitur auto repost dari <u>list data (url post/url product) yang telah anda miliki</u> dan auto action (follow, likes, comments, direct message, dan unfollow) ke <u>list data (username maupun url post) yang telah anda miliki</u></u>. List data tersebut bisa anda dapatkan menggunakan fitur <a href="https://lancarin.com/instagram/data_collection/form" style="font-weight:bold;">Data Collection</a>.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
		<form class="form-inline pull-right">
			<div class="form-group hidden">
				<button type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Add</button>
			</div>
      <div class="form-group">
				<button type="button" class="btn btn-primary btn-show-copy"><i class="fa fa-clone"></i> Copy Setting</button>
			</div>
		  	<div class="form-group">
		  		<label>&nbsp; <?=lang('sort:')?> </label>
		    	<select class="form-control activityFilterAction" name="type">
		    		<option value="" <?=get("type")==""?"selected":""?>>-</option>
		    		<option value="username" <?=get("type")=="username"?"selected":""?>> <?=lang('username')?></option>
		    		<option value="time" <?=get("type")=="time"?"selected":""?>> <?=lang('time')?></option>
		    	</select>
		  	</div>
		  	<div class="form-group">
		  		<label>&nbsp; <?=lang('filter:')?> </label>
		    	<select class="form-control activityFilterAction" name="time">
		    		<option value="">-</option>
		    		<option value="started" <?=get("time")=="started"?"selected":""?>> <?=lang('Started')?></option>
		    		<option value="stoped" <?=get("time")=="stoped"?"selected":""?>> <?=lang('Stopped')?></option>
		    		<option value="none" <?=get("time")=="none"?"selected":""?>> <?=lang('No_time')?></option>
		    	</select>
		  	</div>
		  	<div class="form-group">
		  		<label>&nbsp; Search </label>
		    	<div class="input-group">
			      	<input type="text" class="form-control" name="q" placeholder="<?=lang('enter_keyword')?>" value="<?=get("q")?>">
			      	<span class="input-group-btn">
			        	<button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> <?=lang('search')?></button>
			      	</span>
			    </div>
          
		  	</div>
		</form>
		<div class="clearfix"></div>
	</div>
	<div class="row">
		<?php if(!empty($activities)){
		foreach ($activities as $key => $row) {
		?>
		<div class="col-lg-4 col-md-4 mb15">
			<div class="activity-profile">
				<div class="activity-profile-header">
					<div class="info">
						<img src="<?=$row->avatar?>" class="img-rounded">
						<span class="brand">instagram</span>
						<span class="username"><?=$row->username?></span>
					</div>
					<div class="clearfix"></div>
					<i class="fa fa-instagram" aria-hidden="true"></i>
				</div>
				<div class="activity-profile-content">
					<div class="status">
						<?=lang('status')?> <?=igas($row)?>
					</div>
					<ul class="list-group">
					  	<li class="list-group-item"> <?=lang('like')?>  
					  		<?php if(ig_get_setting("like_block", "", $row->id) != ""){?>
			      			<i class="activity-option-help webuiPopover fa fa-exclamation-circle warning" data-content="<?=ig_get_setting("like_block", "", $row->id);?>" data-delay-show="300" data-title="<span class='warning'><?=lang("Warning")?><span>"></i>
			      			<?php }?>
					  		<span class="badge"><?=igac("like", $row->settings)?></span>
					  	</li>
					  	<li class="list-group-item"> <?=lang('comment')?> 
					  		<?php if(ig_get_setting("comment_block", "", $row->id) != ""){?>
					  		<i class="activity-option-help webuiPopover fa fa-exclamation-circle warning" data-content="<?=ig_get_setting("comment_block", "", $row->id);?>" data-delay-show="300" data-title="<span class='warning'><?=lang("Warning")?><span>"></i>
			      			<?php }?>
					  		<span class="badge"><?=igac("comment", $row->settings)?></span>
					  	</li> 
					  	<li class="list-group-item"> <?=lang('follow')?> 
					  		<?php if(ig_get_setting("follow_block", "", $row->id) != ""){?>
					  		<i class="activity-option-help webuiPopover fa fa-exclamation-circle warning" data-content="<?=ig_get_setting("follow_block", "", $row->id);?>" data-delay-show="300" data-title="<span class='warning'><?=lang("Warning")?><span>"></i>
			      			<?php }?>
					  		<span class="badge"><?=igac("follow", $row->settings)?></span>
					  	</li> 
					  	<li class="list-group-item"> <?=lang('unfollow')?> 
					  		<?php if(ig_get_setting("unfollow_block", "", $row->id) != ""){?>
					  		<i class="activity-option-help webuiPopover fa fa-exclamation-circle warning" data-content="<?=ig_get_setting("unfollow_block", "", $row->id);?>" data-delay-show="300" data-title="<span class='warning'><?=lang("Warning")?><span>"></i>
			      			<?php }?>
					  		<span class="badge"><?=igac("unfollow", $row->settings)?></span>
					  	</li> 
					  	<li class="list-group-item"> <?=lang('direct_message')?>
					  		<?php if(ig_get_setting("direct_message_block", "", $row->id) != ""){?>
					  		<i class="activity-option-help webuiPopover fa fa-exclamation-circle warning" data-content="<?=ig_get_setting("direct_message_block", "", $row->id);?>" data-delay-show="300" data-title="<span class='warning'><?=lang("Warning")?><span>"></i>
			      			<?php }?>
					  		<span class="badge"><?=igac("direct_message", $row->settings)?></span>
					  	</li> 
            	<li class="list-group-item hide"> <?=lang('repost_medias')?>
					  		<?php if(ig_get_setting("direct_message_block", "", $row->id) != ""){?>
					  		<i class="activity-option-help webuiPopover fa fa-exclamation-circle warning" data-content="<?=ig_get_setting("repost_media_block", "", $row->id);?>" data-delay-show="300" data-title="<span class='warning'><?=lang("Warning")?><span>"></i>
			      			<?php }?>
					  		<span class="badge"><?=igac("repost_media", $row->settings)?></span>
					  	</li> 
					</ul>
				</div>
				<div class="activity-profile-footer">
					<div class="btn-group btn-group-justified">
					<?php if($row->status != ""  && $row->status != 2){?>
						<?php if($row->status == 1){?>
						<a href="<?=cn("instagram/activity/stop/".$row->ids)?>" class="btn btn-grey btnActivityStop"> <?=lang('stop')?></a>
						<?php }else{?>
						<a href="<?=cn("instagram/activity/start/".$row->ids)?>" class="btn btn-primary btnActivityStart"> <?=lang('start')?></a>
						<?php }?>
						<a href="<?=cn("instagram/activity/settings/".$row->ids)?>" class="btn btn-grey"> <?=lang('settings')?></a>
						<div class="btn-group">
							<button type="button" class="btn btn-grey dropdown-toggle" data-toggle="dropdown"> <?=lang('more')?> <span class="caret"></span></button>
							<ul class="dropdown-menu  dropdown-menu-right" role="menu">
								<li><a href="<?=cn("instagram/activity/log/".$row->ids)?>"> <?=lang('log')?></a></li>
								<li><a href="<?=cn("instagram/activity/profile/".$row->ids)?>"> <?=lang('profile')?></a></li>
							</ul>
						</div>
					<?php }else{?>
						<a href="<?=cn("instagram/activity/settings/".$row->ids)?>" class="btn btn-grey"> <?=lang('settings')?></a>
					<?php }?>
					</div>
				</div>	
			</div>
		</div>
		<?php }}?>
	</div>
</div>


<div id="modal-copy" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"> Copy Setting</h4>
      </div>
	  <form id="form-copy">
      <div class="modal-body">
        <div class="row">
			<div class="col-md-12"><div class="bg-info" style="margin-bottom:10px !important;">Hanya untuk member yang memiliki lebih dari 1 akun</div></div>
			<div class="col-md-12">
				<div class="form-group">
					<label>From:</label>
					<select class="form-control" id="copy-from" required name="from">
						<?php if(!empty($activities)): foreach($activities as $key=>$row):?>
						<option value="<?=$row->account?>"><?=$row->username;?></option>
						<?php endforeach;endif;?>
					</select>
				</div>
				<div class="form-group">
					<label>To:</label>
					<select class="form-control" id="copy-to" required name="to">
						<?php if(!empty($activities)): foreach($activities as $key=>$row):?>
						<option value="<?=$row->account?>"><?=$row->username;?></option>
						<?php endforeach;endif;?>
					</select>
				</div>
			</div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary btn-copy">Copy</button>
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
	
	$('#btn-show-add').click(function(){
		$('#modal-add').modal('show');
	});
	
	$('.btn-show-copy').click(function(){
		$('#modal-copy').modal('show');
	});
	
	$('#form-copy').submit(function(e){
		e.preventDefault();
		var btn = $(this).find('button[type="submit"]');
		if($('#copy-from').val()==$('#copy-to').val()){
			mynotif('Sumber dan target tidak boleh sama');
			return false;
		}
		var formdata = $(this).serializeArray();
		formdata.push({name: 'token', value: token});
		$.ajax({
			url : '<?=site_url('instagram/activity/copy_setting')?>',
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