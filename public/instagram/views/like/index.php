<div class="wrap-content container instagram-app">
	<div class="activity-filter">
		<form class="form-inline pull-right">
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
		<?php if(!empty($accounts)){
		foreach ($accounts as $key => $row) {
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
						<?=lang('status')?> 
						<span class="label label-default label-danger pull-right">Stopped</span>
					</div>
					<ul class="list-group">
					  	<li class="list-group-item"> Like  
					  		<span class="badge">20</span>
					  	</li>
					</ul>
				</div>
				<div class="activity-profile-footer">
					<div class="btn-group btn-group-justified">
					<a href="<?=cn("instagram/show/stop/".$row->ids)?>" class="btn btn-grey btnActivityStop"> <?=lang('stop')?></a>
					<a href="<?=cn("instagram/analytics/show/".$row->ids)?>" class="btn btn-grey"> <?=lang('settings')?></a>
					<div class="btn-group">
						<button type="button" class="btn btn-grey dropdown-toggle" data-toggle="dropdown"> <?=lang('more')?> <span class="caret"></span></button>
						<ul class="dropdown-menu  dropdown-menu-right" role="menu">
							<li><a href="<?=cn("instagram/analytics/log/".$row->ids)?>"> <?=lang('log')?></a></li>
							<li><a href="<?=cn("instagram/analytics/profile/".$row->ids)?>"> <?=lang('profile')?></a></li>
						</ul>
					</div>
				</div>	
			</div>
		</div>
	</div>
	<?php }}?>
</div>