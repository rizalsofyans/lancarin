<?php
	$dataStatus =['Pending', 'Finish', 'Progress', 'Error'];
?>
<style>
  .text-khusus {
    text-transform: none !important;
    font-weight: 400 !important;
    letter-spacing: normal !important;
    font-size: 14px;
    color: #333;
}
</style>

<div class="wrap-content">
	<div class="users app-table">
		<div class="card">
			<div class="card-header">
				<div class="card-title">
					<i class="<?=$module_icon?>" aria-hidden="true"></i> <?=$module_name;?> HISTORY
            <!-- Button trigger modal -->
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
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="card-body">
				<form action="<?=cn($module)?>">
				<div class="table-filter">
					<div class="row">
						<div class="col-md-9 col-sm-6 mb-mobile-15">
							<div class="btn-group" role="group" aria-label="export">
								<!-- <a href="<?=cn($module."/form_post")?>" class="btn btn-default"><i class="fa fa-edit"></i> Add Bulk Post</a> -->
								<a href="<?=site_url($module.'/form_repost');?>" class="btn btn-default"><i class="fa fa-clone"></i> Add Bulk Repost</a>
								<a href="<?=cn($module."/form")?>" class="btn btn-default"><i class="fa fa-tasks"></i> Add Other</a>
								<a href="<?=cn($module."/ajax_delete_item")?>" class="btn btn-default actionMultiItem" data-redirect="<?=cn($module)?>" data-confirm="<?=lang("are_you_sure_want_delete_it")?>"><i class="fa fa-trash-o"></i> <?=lang("delete")?></a>
							</div>
						</div>
						<div class="col-md-3  col-sm-6 pull-right">
							<div class="input-group">
								<input type="text" class="form-control" name="k" placeholder="<?=lang("enter_keyword")?>" aria-describedby="button-addon" value="<?=get("k")?>">
								<span class="input-group-btn" id="button-addon">
									<button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
								</span>
							</div>
						</div>
					</div>
				</div>
				<table class="table table-bordered table-datatable mb0" width="100%">
					<?php if(!empty($columns)){?>
					<thead>
						<tr>
							<th>
								<div class="pure-checkbox grey mr15">
									<input type="checkbox" name="id[]" id="checkAll" class="filled-in chk-col-red checkAll">
									<label class="p0 m0" for="checkAll">&nbsp;</label>
								</div>
							</th>
							<th>
								<a href="javascript:void(0);">
									<span class="text"><?=lang("option")?></span>
								</a>
							</th>
							<th>
								<a href="javascript:void(0);">
									<span class="text"><?=lang("#")?></span>
								</a>
							</th>
							<?php 
							$i = 0;
							foreach ($columns as $key => $column) {?>
							<th>
								<?php 
								$sort_type = "asc";
								$sort_icon = "";
								if(get("c") == $i){
									if(get("t") == "asc"){
										$sort_type = "desc";
										$sort_icon = "up";
									}else{
										$sort_type = "asc";
										$sort_icon = "down";
									}
								}
								
								$sort_link = cn($module."?c={$i}&t={$sort_type}");
								if(get("k")){
									$sort_link = $sort_link."&k=".get("k");
								}
								?>
								<a href="<?=$sort_link?>">
									<span class="text"><?=$column?></span>

									<span class="sort-caret pull-right <?=$sort_icon?>">
										<i class="asc fa fa-sort-asc" aria-hidden="true"></i>
										<i class="desc fa fa-sort-desc" aria-hidden="true"></i>
									</span>
								</a>
							</th>
							<?php 
							$i ++;
							}?>
						</tr>
					</thead>
					<?php }?>
					<tbody>
					<?php if(!empty($result) && !empty($columns)){
					foreach ($result as $key => $row) { ?>
						<tr>
							<td>
								<div class="pure-checkbox grey mr15">
									<input type="checkbox" name="id[]" id="check_<?=$row->id?>" class="filled-in chk-col-red checkItem" value="<?=$row->id?>">
									<label class="p0 m0" for="check_<?=$row->id?>">&nbsp;</label>
								</div>
							</td>
							<td>
								<div class="btn-group btn-group-option <?=($key + 1 == count($result)?"dropup":"")?>">
									<a href="<?=cn($module."/run_now")?>" data-redirect="<?=cn($module_class)?>" data-id="<?=$row->id?>" data-toggle="tooltip"  title="Run Now" class="btn btn-default actionItem"><i class="fa fa-play"></i></a>
									<div class="btn-group">
										<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
											<span data-toggle="tooltip"  title="Option" class="caret"></span>
										</button>
										<ul class="dropdown-menu dropdown-menu-left" role="menu">
											<li><a href="<?=cn($module_class."/ajax_delete_item")?>" data-id="<?=$row->id?>" data-redirect="<?=cn($module_class)?>" data-confirm="Apakah anda ingin menghapus data?" class="actionItem">Delete</a></li>
										</ul>
									</div>
								</div>
							</td>
							<th scope="row"><?=$page + $key + 1?></th>
							<?php foreach ($columns as $column_name => $column_title):
							if($column_name =='stat'):?>
							<td><?=$dataStatus[$row->$column_name]?></td>
							<?php else:?>
							<td><?=custom_row($row->$column_name, $column_name, $module, $row->id)?></td>
							<?php endif;endforeach;?>
						</tr>
					<?php }}?>
					</tbody>
				</table>
				</form>
			</div>
			<?php if(!empty($result) && !empty($columns) && $this->pagination->create_links() != ""){?>
			<div class="card-footer">
				<?=$this->pagination->create_links();?>
			</div>
			<?php }?>
		</div>
	</div>
</div>

<div id="modal-preview-data" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Preview Data</h4>
      </div>
      <div class="modal-body">
        <div><textarea class="form-control textarea-data" rows="5"></textarea></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
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
		
		$('.preview-data').click(function(e){
			e.preventDefault();
			e.stopPropagation();
			var btn = $(this);
			$.ajax({
				url : $(this).attr('href'),
				type:'post',
				dataType:'json',
				data: {token:token, id: $(this).data('id')},
				beforeSend: function(){
					btn.prop('disabled', true).removeClass('btn-default').addClass('btn-warning');
				},
				success: function(d){
					if(d.status=='success'){
						mynotif('Success', 'green');
						
						if(d.url !=''){
							window.open(d.url);
						}else{
							$('#modal-preview-data .textarea-data').val(d.data);
							$('#modal-preview-data').modal('show');
						}
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
	
		})
		
		$('.download_excel').click(function(e){
			e.preventDefault();
			e.stopPropagation();
			var btn = $(this);
			$.ajax({
				url : $(this).attr('href'),
				type:'post',
				dataType:'json',
				data: {token:token, id: $(this).data('id')},
				beforeSend: function(){
					btn.prop('disabled', true).removeClass('btn-default').addClass('btn-warning');
				},
				success: function(d){
					if(d.status=='success'){
						mynotif('Success', 'green');
						window.location = d.url;
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
	
		})
	});
</script>