<div class="wrap-content">
	<div class="caption-page app-table">
		<div class="card">
	  		<div class="card-header">
	  			<div class="card-title" style="display: inline-block;">
	                <i class="<?=$module_icon?>" aria-hidden="true"></i> Caption
	                <div class="clearfix"></div>
		  		</div>
                <div class="pull-right">
                	<div class="btn-group" role="group" aria-label="export" style="position: relative; top: -7px;">
				    	<button type="button" class="btn btn-default btn-test-engine"><i class="fa fa-wrench"></i> <span class="hidden-xs">Test Engine</span></button>
				   	</div>
                	<div class="btn-group" role="group" aria-label="export" style="position: relative; top: -7px;">
				    	<button type="button" class="btn btn-default btn-help-message" ><i class="fa fa-book"></i> <span class="hidden-xs">Documentation</span></button>
				   	</div>
                  	<div class="btn-group" role="group" aria-label="export" style="position: relative; top: -7px;">
				    	<a href="javascript:;" class="btn btn-default btn-custom-format"><i class="fa fa-sticky-note-o"></i> <span class="hidden-xs">Add Custom Format</span></a>
				   	</div>
                	<div class="btn-group" role="group" aria-label="export" style="position: relative; top: -7px;">
				    	<a href="<?=cn("caption/update")?>" class="btn btn-default"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=lang("add_new")?></span></a>
				   	</div>
                </div>
		  	</div>
	  	</div>
		<div class="row">
			<?php if(!empty($result) && !empty($columns)){
            foreach ($result as $key => $row) {
            ?>
            <div class="col-lg-3 col-md-4">
              	<div class="card">
                	<div class="card-content">
	                  	<div class="card-body p15">
		                    <h4 class="card-title primary" style="font-size: 20px;">#<?=$page + $key + 1?> <?php if($row->signature==1):?><i class="fa fa-star" data-toggle="tooltip" data-placement="right" title="Signature"></i><?php endif;?></h4>
		                    <div class="dropdown pull-right">
						        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
						            <i class="ft-more-vertical"></i>
						        </button>
						        <ul class="dropdown-menu dropdown-menu-right">
			                        <li><a href="<?=cn("caption/update/".$row->ids)?>"><?=lang("edit")?></a></li>
			                        <li><a href="<?=cn("caption/set_signature")?>" data-redirect="<?=cn("caption")?>" class="actionItem" data-id="<?=$row->ids?>">Set as Signature</a></li>
			                        <li><a href="<?=cn("caption/ajax_delete_item")?>" data-redirect="<?=cn("caption")?>" class="actionItem" data-id="<?=$row->ids?>"><?=lang("delete")?></a></li>
						        </ul>
						    </div>
		                    <div class="caption-text caption-scrollbar scrollbar scrollbar-dynamic"><?=specialchar_decode(nl2br($row->content))?></div>
	                  	</div>
                	</div>
              	</div>
            </div>
            <?php }}else{?>
			<div class="ml15 mr15 bg-white dataTables_empty"></div>
            <?php }?>


            <?php if(!empty($result) && !empty($columns) && $this->pagination->create_links() != ""){?>
            <div class="clearfix"></div>
	  		<div class="card-footer">
				<?=$this->pagination->create_links();?>
	  		</div>
	  		<?php }?>
      	</div>
    </div>
</div>

<?php echo Modules::run('caption/popup_documentation');?>

<div id="modal-test-engine" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Test Engine Format, Spintax, FindReplace</h4>
      </div>
      <form id="form-test-engine">
      <div class="modal-body">
      	<div class="row"><div class="col-md-6">
        <div class="form-group">
		    <label for="account">Account</label>
		    <select class="form-control" required id="account" name="account" >
		    	<option value="">Select Account</option>
		    	<?php if(!empty($accounts)): foreach($accounts as $account):?>
		    	<option value="<?=$account->id?>"><?=$account->username?></option>
		    	<?php endforeach; endif;?>
		    </select>
		  </div></div>
		  <div class="col-md-6">
		  <div class="form-group">
		    <label for="target">Target</label>
		    <input type="text" class="form-control" id="target" required name="target" placeholder="username / user url" value="lancarin.id">
		  </div></div></div>
		  <div class="form-group">
		    <label for="text">Text</label>
		    <textarea class="form-control" rows="6" class="form-control" id="text" required name="text">{Hi|Dear|Hai|Halo}, {target_fullname}. Perkenalkan kami {my_fullname} ingin {memberikan|menghadiahkan} anda sebuah produk secara {gratis|dengan diskon sampai {30|20|10}%} demi merayakan postingan kami yang ke {my_post_count}.

Hormat Kami,
{my_username}</textarea>
		  </div>
		  <div class="form-group">
		    <label for="result">Hasil</label>
		    <textarea class="form-control" disabled rows="6" class="form-control" id="result" ></textarea>
		  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Test</button>
      </div>
  </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="modal-custom-format" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Custom Format</h4>
      </div>
     
      <div class="modal-body">
      	<div class="row">
      		<form id="form-custom-format">
      		<div class="col-md-4">
      			<label for="account">Format</label>
      			<div class="input-group">
				  <span class="input-group-addon">{</span>
				  <input type="text" placeholder="format" name="format" required class="form-control" >
				  <span class="input-group-addon">}</span>
				</div>
			</div>
			<div class="col-md-4">
        		<div class="form-group">
				    <label for="replace">Replace</label>
				    <input type="text" class="form-control" name="replace" required placeholder="Kata ganti">
		  		</div>
			</div>
			<div class="col-md-4">
				<label for="replace">&nbsp;</label><br>
        		<button type="submit" class="btn btn-primary" >Add</button>
			</div>
			</form>
      	</div>
      	<hr>
      	<div class="row">
      		<div class="col-md-12">
      			<table class="table table-hover table-striped table-bordered">
      				<?php if(empty($template_message)):?>
      				<tr><td colspan="3">No additional custom format found.</td></tr>
      				<?php else:?>
      				<tr><th>Format</th><th>Replace</th><th>Action</th></tr>
      				<?php foreach($template_message as $row):?>
      				<tr><td><?=$row->text?></td><td><?=$row->replace_text?></td><td><a href="<?=cn("caption/delete_template_message")?>" class="btn btn-danger btn-sm actionItem" data-redirect="<?=cn("caption")?>" data-id="<?=$row->id?>"><i class="fa fa-times"></a></td></tr>
      				<?php endforeach;endif;?>
      			</table>
      		</div>
      	</div>
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

		$('.btn-help-message').click(function(){
			$('#modal-help-message').modal('show');
		});
		$('.btn-test-engine').click(function(){
			$('#modal-test-engine').modal('show');
		});
		$('.btn-custom-format').click(function(){
			$('#modal-custom-format').modal('show');
		});

		$('#form-test-engine').submit(function(e){
			e.preventDefault();
			var btn = $(this).find('button[type="submit"]');
			var formdata = $(this).serializeArray();
			formdata.push({name: 'token', value: token});
			$.ajax({
				url : '<?=site_url('instagram/manual_activity/test_engine')?>',
				type:'post',
				dataType:'json',
				data: formdata,
				beforeSend: function(){
					btn.prop('disabled', true).removeClass('btn-primary').addClass('btn-warning');
				},
				success: function(d){
					if(d.status=='success'){
						$('#result').val(d.data);
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

		$('#form-custom-format').submit(function(e){
			e.preventDefault();
			var btn = $(this).find('button[type="submit"]');
			var formdata = $(this).serializeArray();
			formdata.push({name: 'token', value: token});
			$.ajax({
				url : '<?=site_url('caption/add_template_message')?>',
				type:'post',
				dataType:'json',
				data: formdata,
				beforeSend: function(){
					btn.prop('disabled', true).removeClass('btn-primary').addClass('btn-warning');
				},
				success: function(d){
					if(d.status=='success'){
						mynotif('Success', 'green');
						location.reload();
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
	})
</script>