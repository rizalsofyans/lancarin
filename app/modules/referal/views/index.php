<div class="wrap-content container tab-list profile-page">
    <div class="row">
     <div class="bg-info" style="background:#fff !important;border-radius: 4px;margin: 0px 15px 10px 15px;"><b>Syarat & Ketentuan : <a href="<?=BASE;?>p/program-referral">Klik di sini</a></b></div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="ft-user" aria-hidden="true"></i> Info Referral
                    </div>
                </div>
                <div class="card-block p0">
                    <div class="tab-content p15 report-content">
                        <div id="profile" class="tab-pane fade in active">
							<div class="form-group">
								<label for="fullname">Link Referral</label>
								<div class="input-group" style="padding:0;">
								  <input type="text" class="form-control" readonly id="link-ref" value="<?=!empty($refcode)?$refcode['url']:""?>" aria-describedby="btn-copy-code">
								  
								  <span class="input-group-btn">
									<button class="btn btn-default" type="button" id="btn-copy-code" data-clipboard-target="#link-ref"><i class="fa fa-copy"></i> <span class="cp-text">Copy</span></button>
								  </span>
								</div>
							</div>
                            <div class="form-group">
								<label for="fullname">Link Bitly</label>
								<div class="input-group" style="padding:0;">
								  <input type="text" class="form-control" readonly id="link-bitly" value="<?=!empty($refcode)?$refcode['bitly']:""?>" aria-describedby="btn-copy-bitly">
								  <span class="input-group-btn">
									<button class="btn btn-default" type="button" id="btn-copy-bitly" data-clipboard-target="#link-bitly"><i class="fa fa-copy"></i> <span class="cp-text">Copy</span></button>
								  </span>
								</div>
							</div>
							<?php $balance=!empty($refcode)?$refcode['balance']:0;?>
                            <div class="form-group">
								<label for="fullname">Balance</label>
								  <input type="text" class="form-control" readonly id="balance" data-amount="<?=$balance;?>" value="Rp <?=number_format($balance, 0,',','.');?>">
							</div>
							<div class="form-group">
								<label for="prosentase">Prosentase</label>
								  <input type="text" class="form-control" readonly id="prosentase" value="<?=$refcode['percent'];?> %">
							</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
					<button type="button" id="btn-withdraw" class="btn btn-danger"> Withdraw</button>
					<a target="_blank"  href="<?=$refcode['bitly'];?>+" class="btn btn-success"> Link Info</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card profile-package">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa fa-bank" aria-hidden="true"></i> Bank
                    </div>
                </div>
				<form id="form-bank">
                <div class="card-block p0">
                    <div class="tab-content report-content">
						<div class="tab-content p15 report-content">
                        <div  class="tab-pane fade in active">
							<div class="form-group">
								<label >Bank</label>
								<select class="form-control" required name="bank" >
									<option value="">Pilih Bank</option>
									<?php foreach(['MANDIRI','BCA','BNI','BRI'] as $b):?>
									<option <?=$refcode['bank']==$b?'selected':'';?> value="<?=$b;?>">Bank <?=$b;?></option>
									<?php endforeach;?>
								</select>
							</div>
                            <div class="form-group">
								<label >Nama</label>
								<input type="text" required class="form-control" name="nama" value="<?=$refcode['nama'];?>">
							</div>
							<div class="form-group">
								<label >No Rekening</label>
								<input type="text" required class="form-control" name="norek" value="<?=$refcode['norek'];?>">
							</div>
							
                        </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit">Update</button>
                </div>
				</form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modal-withdraw" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Withdraw Balance</h4>
      </div>
      <form id="form-withdraw">
      <div class="modal-body">
		<div class="alert alert-info" role="alert"><b>Perhatian</b> Pastikan anda telah mengisi detail rekening bank anda</div>
        <div class="form-group">
          <label for="nilai">Jumlah</label>
          <input type="number" class="form-control" required name="nilai" id="nilai" placeholder="Jumlah withdraw" min="<?=get_option('min_withdraw');?>" max="<?=$refcode['balance'];?>">
        </div>
        <div class="form-group">
          <label for="invoice">Keterangan tambahan</label>
          <textarea class="form-control" rows="5" name="keterangan" id="keterangan" placeholder="Keterangan tambahan jika diperlukan"></textarea>
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
	var minWithdraw= <?=intval(get_option('min_withdraw'));?>;
	var maxWithdraw= <?=$refcode['balance'];?>;
	
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
	
    $('#btn-withdraw').click(function(){
		if(minWithdraw>maxWithdraw){
		  mynotif('Perhatian', 'Minimal withdraw adalah Rp '+ '<?=number_format(intval(get_option('min_withdraw')),0,',','.');?>');
	  }else{
      $('#modal-withdraw').modal('show');
	  }
    });
    
    $('#form-withdraw').submit(function(e){
      e.preventDefault();
	  if(minWithdraw>maxWithdraw){
		  mynotif('Perhatian', 'Minimal withdraw adalah Rp '+ '<?=number_format(intval(get_option('min_withdraw')),0,',','.');?>');
		  return false;
	  }
	  var title='Withdraw';
	  
      var formdata = $(this).serializeArray();
	 formdata.push({name:'token', value: token});
      var btn = $(this).find('button[type="submit"]');
      $.ajax({
        data: formdata,
        type: 'post',
        dataType: 'json',
        url: '<?=site_url('referal/create_withdraw');?>',
        beforeSend: function(){
          btn.prop('disabled', true).addClass('btn-warning').removeClass('btn-primary');
        },
        success: function(d){
          if(d.ok==1){
            mynotif(title,'Terima kasih', 'green');
            $('#modal-withdraw').modal('hide');
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
	
	
  });
</script>