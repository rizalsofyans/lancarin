<div class="wrap-content">
	<form action="<?=cn($module."/ajax_update")?>" data-redirect="<?=cn($module)?>" class="actionForm" method="POST">
	<input type="hidden" name="ids" value="<?=!empty($result)?$result->id:""?>">

	<div class="row">
		<div class="col-md-5">
			<div class="users">
			  	<div class="card">
			  		<div class="card-header">
			  			<div class="card-title">
	                        <i class="<?=$module_icon?>" aria-hidden="true"></i> <?=$module_name?>
	                    </div>
	                    <div class="clearfix"></div>
			  		</div>
			  		<div class="card-body pl15 pr15">
			  			<div class="row">
			  				<div class="col-md-12">
								<div class="form-group">
								  <label for="nilai">Nama Discount</label>
								  <input type="text" class="form-control" required name="nama" id="nama" placeholder="Nama Diskon" value="<?=!empty($result)?$result->nama:"";?>">
								</div>
								<div class="form-group">
								  <label for="nilai">Code Discount</label>
								  <input type="text" class="form-control" size="150" required name="code" id="code" placeholder="URI discount" value="<?=!empty($result)?$result->code:"";?>">
								</div>
								
								<div class="row">
								<div class="col-md-4">
								<div class="form-group">
								  <label for="nilai">Prosentase</label>
								  <input type="number" class="form-control" required name="percent" id="percent" placeholder="prosentase" min="0" max="100" value="<?=!empty($result)?$result->percent:10;?>">
								</div>
								</div>
								<div class="col-md-4">
								<div class="form-group">
								  <label for="nilai">Kuota</label>
								  <input type="number" class="form-control" required name="quota" id="quota" placeholder="Kuota" min="0" value="<?=!empty($result)?$result->quota:10;?>" >
								</div>
								</div>
								<div class="col-md-4">
								<div class="form-group">
								  <label for="nilai">Status</label>
								  <select class="form-control" required name="status" id="status" >
									<option value="1" <?=(!empty($result) && $result->status==1)?"selected":"";?>>Enable</option>
									<option value="0" <?=(!empty($result) && $result->status==0)?"selected":"";?>>Disable</option>
								  </select>
								</div>
								</div>
								</div>
								<div class="row">
								<div class="col-md-6">
								<div class="form-group">
								  <label for="nilai">Tanggal Mulai</label>
								  <input type="text" class="form-control date " required name="tgl_start" id="tgl_start" placeholder="Tanggal Mulai" value="<?=!empty($result)?date('M d, Y', strtotime($result->tgl_start)):"";?>">
								</div>
								</div>
								<div class="col-md-6">
								<div class="form-group">
								  <label for="nilai">Tanggal Berakhir</label>
								  <input type="text" class="form-control date" required name="tgl_end" id="tgl_end" placeholder="Tanggal Berakhir" value="<?=!empty($result)?date('M d, Y', strtotime($result->tgl_end)):"";?>">
								</div>
								</div>
								</div>

                            </div>
			  			</div>
			  		</div>
			  		<div class="card-footer">
	  					<a href="<?=cn($module)?>" class="btn btn-default"><?=lang('cancel')?></a>
	                    <button type="submit" class="btn btn-primary pull-right"><?=lang('update')?></button>
	                    <div class="clearfix"></div>
			  		</div>
			  	</div>
			</div>
		</div>
	</div>
	</form>
</div>

<script>
	$(function(){
		
	});
</script>