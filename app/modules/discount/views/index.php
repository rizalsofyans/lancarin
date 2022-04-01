<style>
.view-quota{
	cursor: pointer;
	font-weight: bold;
}
</style>
<div class="wrap-content">
	<div class="users app-table">
		  	<div class="card">
		  		<div class="card-header">
		  			<div class="card-title">
                        <i class="<?=$module_icon?>" aria-hidden="true"></i> <?=$module_name?>
                    </div>
                    <div class="clearfix"></div>
		  		</div>
		  		<div class="card-body">
					<form action="<?=cn($module)?>">
		  			<div class="table-filter">
						<div class="col-md-9 col-sm-6 mb-mobile-15">
							<div class="btn-group" role="group" aria-label="export">
								<a href="<?=cn($module."/update")?>" class="btn btn-default " ><i class="fa fa-plus"></i> Create Discount</a>
								<a href="<?=cn($module."/ajax_delete_item")?>" class="btn btn-default actionMultiItem" data-redirect="<?=cn($module)?>" data-confirm="<?=lang("are_you_sure_want_delete_it")?>"><i class="fa fa-trash-o"></i> <?=lang("delete")?></a>
							</div>
						</div>
		  				<div class="row">
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
		                foreach ($result as $key => $row) {
		                ?>
		                    <tr>
		                    	<td>
		                    		<div class="pure-checkbox grey mr15">
			                        	<input type="checkbox" name="id[]" id="check_<?=$row->id?>" class="filled-in chk-col-red checkItem" value="<?=$row->id?>">
            							<label class="p0 m0" for="check_<?=$row->id?>">&nbsp;</label>
            						</div>
		                    	</td>
		                    	<td>
									<div class="btn-group btn-group-option <?=($key + 1 == count($result)?"dropup":"")?>">
									  	<a href="<?=cn($module."/update/".$row->id)?>" class="btn btn-default"><i class="ft-edit"></i></a>
									  	<div class="btn-group">
									    	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
									    		<span class="caret"></span>
									    	</button>
									    	<ul class="dropdown-menu dropdown-menu-left" role="menu">
									      		<li><a href="<?=cn($module."/ajax_delete_item")?>" data-id="<?=$row->id?>" data-redirect="<?=cn($module)?>" data-confirm="<?=lang("are_you_sure_want_delete_it")?>" class="actionItem"><?=lang("delete")?></a></li>
									    	</ul>
									  	</div>
									</div>
		                        </td>
		                        <th scope="row"><?=$page + $key + 1?></th>
		                        <?php foreach ($columns as $column_name => $column_title):
									if($column_name=='quota') :?>
									<td><span class="view-quota text-primary" data-id="<?=$row->id;?>"><?=$row->$column_name?></span>
									<?php elseif($column_name=='percent') :?>
									<td><?=$row->$column_name?> %</td>
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
</div>

<div class="modal fade" tabindex="-1" id="modal-discount" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Discount History</h4>
      </div>
      <div class="modal-body">
		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>

<script>
  $(function(){
	  function mynotif(title, msg, color='red'){
		iziToast.show({
			title: title,
			message: msg,
			color: color,
			position: 'bottomCenter'
		});
	}
	
	
	//mynotif('Perhatian', 'Halaman ini sedang dalam perbaikan, anda tidak akan dapat melakukan post sampai proses ini selesai.', 'blue');
	  
	new ClipboardJS('#btn-copy-code');
	new ClipboardJS('#btn-copy-bitly');
	$('#btn-copy-code, #btn-copy-bitly').click(function(){
		var cp = $(this).find('.cp-text');
		cp.text('Copied');
		setTimeout(function(){cp.text('Copy');},500);
	});
	  
	  
	$('#form-bank').submit(function(e){
      e.preventDefault();
      var formdata = $(this).serializeArray();
	 formdata.push({name:'token', value: token});
      var btn = $(this).find('button[type="submit"]');
	  var title='Update';
      $.ajax({
        data: formdata,
        type: 'post',
        dataType: 'json',
        url: '<?=site_url('referal/update_bank');?>',
        beforeSend: function(){
          btn.prop('disabled', true).addClass('btn-warning').removeClass('btn-primary');
        },
        success: function(d){
          if(d.ok==1){
            mynotif(title, 'Success', 'green');
          }else{
            mynotif(title, d.msg);
          }
          setTimeout(function(){
            btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
          },2000);
          
        },
       error: function(d){
		  mynotif(title, 'Terjadi kesalahan');
          setTimeout(function(){
            btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
          },2000);
        }
      });
    });
	
    $('.view-quota').click(function(){
		var btn= $(this);
		var id = $(this).data('id');
		var title='View Quota';
		$.ajax({
        data: {token:token, id: id},
        type: 'post',
        dataType: 'json',
        url: '<?=site_url('discount/get_discount_history');?>',
        beforeSend: function(){
			btn.addClass('text-warning').removeClass('text-primary');
        },
        success: function(d){
          if(d.ok==1){
            $('#modal-discount .modal-body').html(d.table);
            $('#modal-discount').modal('show');
          }else{
			  mynotif(title, d.msg);
          }
          setTimeout(function(){
            btn.addClass('text-primary').removeClass('text-warning');
          },2000);
          
        },
       error: function(d){
		   mynotif(title, 'Terjadi kesalahan');
          setTimeout(function(){
            btn.addClass('text-primary').removeClass('text-warning');
          },2000);
        }
      });
	  
    });
	
  });
</script>