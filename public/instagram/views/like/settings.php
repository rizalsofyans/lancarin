<div class="container col-md-6 col-md-offset-3">
	<div class="row ig-auto-box">
		<div class="col-md-12 ig-auto-main">
			<div class="ig-icon"><i class="fa fa-instagram"></i></div>
			<div class="user">
				<div class="img">
					<img src="http://chothuevanphonghcm.com/upload/images/cho-thue-van-phong-quan-1-time-square.jpg">
				</div>
				<div class="name">@tienpham1606</div>
			</div>
			<div class="list-button">
				<div class="btn-group btn-group-justified">
				  <a class="btn btn-grey">START</a>
				  <a class="btn btn-grey">LOG</a>
				</div>
			</div>			
		</div>
		<div class="col-md-12 ig-auto-content">
			<div class="" style="padding: 15px;">
                <div class="row">
                	<div class="col-md-12">
                		<div class="ig-auto-dropdown">
	                		<a href="#target" data-toggle="collapse" class="ig-auto-head" aria-expanded="true"><i class="ft-target panel-dropdown-icon"></i> Target</a>
					    	<div class="ig-auto-block collapse in" id="target" aria-expanded="true" style="">
			                	<div class="row">
			                		<div class="col-md-4">
			                			<div class="item">
					                		<div class="pure-checkbox black mr15 mb15">
							                    <input type="checkbox" id="md_checkbox_target_tag" name="target" class="filled-in chk-col-red" value="tag">
							                    <label class="p0 m0" for="md_checkbox_target_tag">&nbsp;</label>
							                    <span class="checkbox-text-right"> Tags</span>
							                </div>
			                			</div>
				                	</div>
				                	<div class="col-md-4">
				                		<div class="item">
					                		<div class="pure-checkbox black mr15 mb15">
							                    <input type="checkbox" id="md_checkbox_target_location" name="target" class="filled-in chk-col-red" value="location">
							                    <label class="p0 m0" for="md_checkbox_target_location">&nbsp;</label>
							                    <span class="checkbox-text-right"> Locations</span>
							                </div>
				                		</div>
				                	</div>
				                	<div class="col-md-4">
				                		<div class="item">
					                		<div class="pure-checkbox black mr15 mb15">
							                    <input type="checkbox" id="md_checkbox_target_username" name="target" class="filled-in chk-col-red" value="username">
							                    <label class="p0 m0" for="md_checkbox_target_username">&nbsp;</label>
							                    <span class="checkbox-text-right"> Usernames</span>
							                </div>
				                		</div>
				                	</div>
			                	</div>
			               	</div>
		                </div>
                	</div>

                	<div class="col-md-12">
                		<div class="ig-auto-dropdown">
					    	<a href="#comments" data-toggle="collapse" class="ig-auto-head" aria-expanded="true"><i class="ft-message-square panel-dropdown-icon"></i> Comments</a>
					    	<div class="panel-dropdown-block collapse in" id="comments" aria-expanded="true" style="">
						      	<div class="ig-auto-option-list">
						      		<div class="row">
						      			<div class="col-md-12">
								      		<div class="item multi-item">
								      			<div class="ig-auto-list-title">
								      				<span class="text">Comments</span>
								      				<i class="ig-auto-help webuiPopover fa fa-question-circle" data-content="

								      					Add at least one tag to get media from
													  	if you are using <b>Tags</b> as your Media source.<br/><br/>

													  	You can search tags or you can upload a list of tags
													  	by pressing <b>Enter your list of tags</b> link. Please
													  	note that <b>#</b> symbol is not required. Using 10 tags
													  	and more is recommended for this setting.<br/><br/>

													  	You can add up to 1000 tags.
								      				" data-delay-show="300" data-title="Tags"></i>
								      			</div>
								      			
								      			
								      			<div class="btn-group ig-auto-add-comment">
												  	<a href="<?=cn("instagram/".$module."/popup/comment/".segment(4))?>" class="btn btn-grey ajaxModal"><?=lang('add')?></a>
												  	<div class="btn-group">
												    	<button type="button" class="btn btn-grey dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
												    	<ul class="dropdown-menu dropdown-menu-right" role="menu">
												      		<li><a href="javscript:void(0);" class="activityDeleteAllOption">Delete all tags</a></li>
												    	</ul>
												  </div>
												</div>
								      		</div>
								      	</div>
						      		</div>
						      	</div>
					    	</div>
						</div>
                	</div>

                	<div class="col-md-12">
                		<div class="ig-auto-dropdown">
					    	<a href="#tags" data-toggle="collapse" class="ig-auto-head" aria-expanded="true"><i class="ft-tag panel-dropdown-icon"></i> Tags</a>
					    	<div class="panel-dropdown-block collapse in" id="tags" aria-expanded="true" style="">
						      	<div class="ig-auto-option-list">
						      		<div class="row">
						      			<div class="col-md-12">
								      		<div class="item multi-item">
								      			<div class="ig-auto-list-title">
								      				<span class="text">Tags</span>
								      				<i class="ig-auto-help webuiPopover fa fa-question-circle" data-content="

								      					Add at least one tag to get media from
													  	if you are using <b>Tags</b> as your Media source.<br/><br/>

													  	You can search tags or you can upload a list of tags
													  	by pressing <b>Enter your list of tags</b> link. Please
													  	note that <b>#</b> symbol is not required. Using 10 tags
													  	and more is recommended for this setting.<br/><br/>

													  	You can add up to 1000 tags.
								      				" data-delay-show="300" data-title="Tags"></i>
								      			</div>
								      			
								      			
								      			<div class="btn-group ig-auto-add-tag">
												  	<a href="<?=cn("instagram/".$module."/popup/tag/".segment(4))?>" class="btn btn-grey ajaxModal"><?=lang('add')?></a>
												  	<div class="btn-group">
												    	<button type="button" class="btn btn-grey dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
												    	<ul class="dropdown-menu dropdown-menu-right" role="menu">
												      		<li><a href="javscript:void(0);" class="activityDeleteAllOption">Delete all tags</a></li>
												    	</ul>
												  </div>
												</div>
								      		</div>
								      	</div>
						      		</div>
						      	</div>
					    	</div>
						</div>
                	</div>

                	<div class="col-md-12">
                		<div class="ig-auto-dropdown">
					    	<a href="#locations" data-toggle="collapse" class="ig-auto-head" aria-expanded="true"><i class="ft-map-pin panel-dropdown-icon"></i> Locations</a>
					    	<div class="panel-dropdown-block collapse in" id="locations" aria-expanded="true" style="">
						      	<div class="ig-auto-option-list">
						      		<div class="row">
						      			<div class="col-md-12">
								      		<div class="item multi-item">
								      			<div class="ig-auto-list-title">
								      				<span class="text">Locations</span>
								      				<i class="ig-auto-help webuiPopover fa fa-question-circle" data-content="

								      					Add at least one tag to get media from
													  	if you are using <b>Tags</b> as your Media source.<br/><br/>

													  	You can search tags or you can upload a list of tags
													  	by pressing <b>Enter your list of tags</b> link. Please
													  	note that <b>#</b> symbol is not required. Using 10 tags
													  	and more is recommended for this setting.<br/><br/>

													  	You can add up to 1000 tags.
								      				" data-delay-show="300" data-title="Tags"></i>
								      			</div>
								      			
								      			
								      			<div class="btn-group ig-auto-add-location">
												  	<a href="<?=cn("instagram/".$module."/popup/location/".segment(4))?>" class="btn btn-grey ajaxModal"><?=lang('add')?></a>
												  	<div class="btn-group">
												    	<button type="button" class="btn btn-grey dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
												    	<ul class="dropdown-menu dropdown-menu-right" role="menu">
												      		<li><a href="javscript:void(0);" class="activityDeleteAllOption">Delete all tags</a></li>
												    	</ul>
												  </div>
												</div>
								      		</div>
								      	</div>
						      		</div>
						      	</div>
					    	</div>
						</div>
                	</div>

                	<div class="col-md-12">
                		<div class="ig-auto-dropdown">
					    	<a href="#usernames" data-toggle="collapse" class="ig-auto-head" aria-expanded="true"><i class="ft-user panel-dropdown-icon"></i> Usernames</a>
					    	<div class="panel-dropdown-block collapse in" id="usernames" aria-expanded="true" style="">
						      	<div class="ig-auto-option-list">
						      		<div class="row">
						      			<div class="col-md-12">
								      		<div class="item multi-item">
								      			<div class="ig-auto-list-title">
								      				<span class="text">Usernames</span>
								      				<i class="ig-auto-help webuiPopover fa fa-question-circle" data-content="

								      					Add at least one tag to get media from
													  	if you are using <b>Tags</b> as your Media source.<br/><br/>

													  	You can search tags or you can upload a list of tags
													  	by pressing <b>Enter your list of tags</b> link. Please
													  	note that <b>#</b> symbol is not required. Using 10 tags
													  	and more is recommended for this setting.<br/><br/>

													  	You can add up to 1000 tags.
								      				" data-delay-show="300" data-title="Tags"></i>
								      			</div>
								      			
								      			
								      			<div class="btn-group ig-auto-add-username">
												  	<a href="<?=cn("instagram/".$module."/popup/username/".segment(4))?>" class="btn btn-grey ajaxModal"><?=lang('add')?></a>
												  	<div class="btn-group">
												    	<button type="button" class="btn btn-grey dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
												    	<ul class="dropdown-menu dropdown-menu-right" role="menu">
												      		<li><a href="javscript:void(0);" class="activityDeleteAllOption">Delete all tags</a></li>
												    	</ul>
												  </div>
												</div>
								      		</div>
								      	</div>
						      		</div>
						      	</div>
					    	</div>
						</div>
                	</div>

                	<div class="col-md-12">
                		<div class="ig-auto-dropdown">
					    	<a href="#advance" data-toggle="collapse" class="ig-auto-head" aria-expanded="true"><i class="ft-crosshair panel-dropdown-icon"></i> Advance</a>
					    	<div class="ig-auto-block collapse in" id="advance" aria-expanded="true" style="">
			                	<div class="row">
			                		<div class="col-md-4">
			                			<div class="item">
			                				<div class="text">
			                					Speed
			                					<i class="ig-auto-help webuiPopover fa fa-question-circle" data-content="

							      					Add at least one tag to get media from
												  	if you are using <b>Tags</b> as your Media source.<br/><br/>

												  	You can search tags or you can upload a list of tags
												  	by pressing <b>Enter your list of tags</b> link. Please
												  	note that <b>#</b> symbol is not required. Using 10 tags
												  	and more is recommended for this setting.<br/><br/>

												  	You can add up to 1000 tags.
							      				" data-delay-show="300" data-title="Tags"></i>
			                				</div> 
					                		<select class="form-control" name="">
						      					<option value="">-</option>
												<option value="user">Slow</option>
												<option value="me">Normal</option>
												<option value="all">Fast</option>
						      				</select>
			                			</div>
				                	</div>
				                	<div class="col-md-4">
				                		<div class="item">
				                			<div class="text">
				                				Pause
				                				<i class="ig-auto-help webuiPopover fa fa-question-circle" data-content="

							      					Add at least one tag to get media from
												  	if you are using <b>Tags</b> as your Media source.<br/><br/>

												  	You can search tags or you can upload a list of tags
												  	by pressing <b>Enter your list of tags</b> link. Please
												  	note that <b>#</b> symbol is not required. Using 10 tags
												  	and more is recommended for this setting.<br/><br/>

												  	You can add up to 1000 tags.
							      				" data-delay-show="300" data-title="Tags"></i>
				                			</div>
						      				<input type="" class="form-control" placeholder="00:19-00:05" name="">
				                		</div>
				                	</div>
			                	</div>
			               	</div>
						</div>
                	</div>

                </div>
            </div>
		</div>
	</div>
</div>


<style type="text/css">
	.item .text{
		padding: 8px;
		display: inline-block;
	}

	.item .form-control{
		display: inline-block;
		width: auto!important;
		float: right;
		max-width: 120px!important;
		text-align: center;
	}

	.ig-auto-box{
		margin: 15px 0;
		box-shadow: 0 8px 24px rgba(21,48,142,.02);
	}

	.ig-auto-main{
		padding: 0px;
		background: #0089cf;
		height: 300px;
		background-image: linear-gradient(135deg, rgb(246, 210, 66) 10%, rgb(255, 82, 229) 100%);
		border-radius: 6px 6px 0 0px;
	}

	.ig-auto-main .ig-icon{
		font-size: 80px;
		color: #fff;
		margin-left: 20px;
		opacity: 0.5;
	}

	.ig-auto-main .user{
		text-align: center;
		position: absolute;
    	top: 50%;
    	width: 100%;
    	left: 0;
    	margin-top: -110px;
	}

	.ig-auto-main .user .img{
		width: 120px;
		height: 120px;
		display: inline-block;
		border-radius: 100px;
		border: 15px solid rgba(255,255,255,0.3);
	}

	.ig-auto-main .user .img img{
		width: 100%;
		height: 100%;
		border-radius: 100px;
	}

	.ig-auto-main .user .name{
		font-size: 20px;
		color: #fff;
		margin-top: 15px;
		font-weight: bold;
	}

	.ig-auto-main .list-button{
		position: absolute;
		bottom: 0;
		width: 100%;
		background: #eee;
	}

	.ig-auto-main .list-button a.btn{
		border-radius: 0px;
		padding: 20px 5px;
	}

	.ig-auto-main .list-button a.btn:first-child{
		border-right: 1px solid #eee;
	}

	.ig-auto-content{
		background: #fff;
		border-radius: 0 6px 6px 0;
	    padding: 15px 15px 0;
	}

	.ig-auto-content .ig-auto-dropdown{
		margin-bottom: 20px;
	}

	.ig-auto-content .ig-auto-head{
		background: #fff;
		padding: 18px 15px;
		margin-bottom: 15px;
		border-radius: 6px;
		border: 2px solid #f5f5f5;
		width: 100%;
		display: block;
		font-size: 18px;
	}

	.ig-auto-content .ig-auto-help{
		color: #d4d4d4;
		font-size: 18px;
	}

	.ig-auto-content .ig-auto-block .item{
		background: #f5f5f5;
		padding: 10px 10px 7px;
		margin-bottom: 15px;
		border-radius: 4px;
	}

	.ig-auto-content .ig-auto-block .item .pure-checkbox{
		width: 100%;
	    position: relative;
	    right: 24px;
	    top: 3px;
	}

	.ig-auto-content .ig-auto-block .item .pure-checkbox label{
		float: right;
	}

	.ig-auto-content .ig-auto-option-list{
		padding: 15px;
		background: #f5f5f5;
		border-radius: 4px;
	}

	.ig-auto-content .ig-auto-option-list .ig-auto-list-title{
	    display: inline-block;
    	margin-right: 50px;
	}



</style>