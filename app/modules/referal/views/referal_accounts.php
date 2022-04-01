
<div class="wrap-content">
	<div class="users app-table">
		  	<div class="card">
		  		<div class="card-header">
		  			<div class="card-title">
                        <i class="<?=$module_icon?>" aria-hidden="true"></i> Referral Accounts
                    </div>
                    <div class="clearfix"></div>
		  		</div>
		  		<div class="card-body">
		  			<form action="<?=cn($module)?>">
		  			<div class="table-filter">
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
			                        	<input type="checkbox"class="filled-in chk-col-red checkItem" >
            							<label class="p0 m0" >&nbsp;</label>
            						</div>
		                    	</td>
		                        <th scope="row"><?=$page + $key + 1?></th>
		                        <?php foreach ($columns as $column_name => $column_title):?>
								<?php if($column_name == 'whatsapp'):?>
								<td><span><?=$row->$column_name;?></span> <button type="button" class="btn btn-sm btn-success btn-send-wa"><i class="fa fa-whatsapp"></i></button></td>
								<?php else:?>
		                        <td><?=custom_row($row->$column_name, $column_name, $module, "")?></td>
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

<div class="modal fade" tabindex="-1" id="modal-whatsapp" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Message</h4>
      </div>
      <form id="form-whatsapp">
      <div class="modal-body">
		<input type="hidden" id="nope">
		<div class="form-group">
          <label for="nilai">Pesan</label>
          <textarea class="form-control" required name="pesan" id="pesan" rows="5" placeholder="Tulis Pesan di sini" ></textarea>
        </div>
		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Kirim</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


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
	
	
	$('.btn-send-wa').click(function(){
		$('#nope').val($(this).prev().text());
		$('#modal-whatsapp').modal('show');
	});
	
	$('#form-whatsapp').submit(function(e){
		e.preventDefault();
		var nope= $('#nope').val(), pesan=$('#pesan').val();
		if(!/^08+[0-9]{7,11}$/.test(nope)){
			mynotif('Error','Nomor Whatsapp tidak valid');
			return false;
		}else{
			nope = '62'+ nope.substr(2);
		}
		if(pesan=='' ){
			mynotif('Error','Pesan tidak boleh kosong');
			return false;
		}
		var u = 'https://api.whatsapp.com/send?phone='+nope+'&text='+encodeURI($('#pesan').val());
		window.open(u);
	})
});
</script>