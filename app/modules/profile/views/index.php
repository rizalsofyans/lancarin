<div class="wrap-content tab-list profile-page">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <form action="<?=cn("profile/ajax_update_account")?>" class="actionForm">
                <div class="card-header">
                    <div class="card-title">
                        <i class="ft-user" aria-hidden="true"></i> <?=lang("my_account")?>
                    </div>
                </div>
                <div class="card-block p0">
                    <div class="tab-content p15 report-content">
                        <div id="profile" class="tab-pane fade in active">
                            <div class="form-group">
                                <label for="fullname"><?=lang("fullname")?></label>
                                <input type="text" class="form-control" name="fullname" id="fullname"  placeholder="fullname" value="<?=!empty($account)?$account->fullname:""?>">
                            </div>
                            <div class="form-group">
                                <label for="email"><?=lang("email")?></label>
                                <input type="text" class="form-control" name="email" id="email"  placeholder="email" value="<?=!empty($account)?$account->email:""?>">
                            </div>
								<div class="form-group">
                                <label for="whatsapp">Whatsapp</label>
                                <input type="text" class="form-control" name="whatsapp" id="whatsapp" placeholder="whatsapp number" value="<?=!empty($account)?$account->whatsapp:""?>">
                            </div>
							<div hidden class="form-group <?=get_option('email_problem_reporting', 1)==1?'':'hidden'?>">
                                <label for="whatsapp">Error Reporting by email <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Jika akun anda mengalami gangguan, maka kami akan mengirimkan hal ini melalui email anda"></i></label>
                                <select class="form-control" name="email_error" id="email_error">
									<option <?=$account->email_error==0?'selected':''?> value="0">Disabled</option>
									<option <?=$account->email_error==1?'selected':''?>  value="1">Enabled</option>
								</select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"> <?=lang("Update")?></button>
                </div>
                </form>
            </div>

            <div class="card">
                <form action="<?=cn("profile/ajax_change_password")?>" class="actionForm">
                <div class="card-header">
                    <div class="card-title">
                        <i class="ft-lock" aria-hidden="true"></i> <?=lang("change_password")?>
                    </div>
                </div>
                <div class="card-block p0">
                    <div class="tab-content p15 report-content">
                        <div id="profile" class="tab-pane fade in active">
                            <div class="form-group">
                                <label for="password"><?=lang("password")?></label>
                                <input type="password" class="form-control" name="password" id="password">
                            </div>
                            <div class="form-group">
                                <label for="confirm_password"><?=lang("confirm_password")?></label>
                                <input type="password" class="form-control" name="confirm_password" id="confirm_password">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"> <?=lang("Update")?></button>
                </div>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card profile-package">
                <div class="card-header">
                    <div class="card-title">
                        <i class="ft-package" aria-hidden="true"></i> <?=lang("package")?>
                    </div>
                </div>
                <div class="card-block p0">
                    <div class="tab-content report-content">
                        <div id="profile" class="tab-pane fade in active">
                            <ul class="list-group">
                              <li class="list-group-item"><span class="name"><?=lang("your_package")?></span> <span class="pull-right"><?=(!empty($account) && $account->package_name != "")?$account->package_name:"None"?></span></li>
                              <li class="list-group-item"><span class="name"><?=lang("expire_date")?></span> <span class="pull-right"><?=(!empty($account) && $account->package_name != "")?convert_date($account->expiration_date):"None"?></span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php if(get_payment()){?>
                <div class="card-footer">
                    <a href="<?=cn("pricing")?>" class="btn btn-primary"> Upgrade Paket</a>
                </div>
                <?php }?>
            </div>
        </div>
		<div class="col-md-6">
            <div class="card payment-history">
                <div class="card-header">
                    <div class="card-title pull-left">
                        <i class="fa fa-credit-card" aria-hidden="true"></i> Payment History
                    </div>
                    <div class="card-title pull-right">
                        <button class="btn btn-danger show-confirm">
                          <i class="fa fa-envelope " aria-hidden="true"></i> Konfirmasi</button>
                    </div>
                  <br>
                </div>
                <div class="card-block ">
                  <table class="table table-hover table-striped table-bordered table-condensed">
                    <thead><tr><th>Id Transaksi</th><th>Status</th></tr></thead>
                    <tbody><?=$paymentHistory;?></tbody>
                  </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modal-payment" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Konfirmasi Pembayaran</h4>
      </div>
      <form id="form-confirm">
      <div class="modal-body">
        <div class="form-group">
          <label for="nama">Nama Pemilik Rekening</label>
          <input type="text" required class="form-control" name="nama" id="nama" placeholder="Nama Pengirim">
        </div>
        <div class="form-group">
          <label for="bank">Nama Bank</label>
          <input type="text" required class="form-control" name="bank" id="bank" placeholder="Nama Bank">
        </div>
        <div class="form-group">
          <label for="norek">No Rekening</label>
          <input type="text" required class="form-control" name="norek" id="norek" placeholder="No Rekening">
        </div>
        <div class="form-group">
          <label for="invoice">No Invoice/Transaction ID</label>
          <input type="text" required class="form-control" name="invoice" id="invoice" placeholder="No Invoice">
        </div>
        <div class="form-group">
          <label for="nilai">Jumlah</label>
          <input type="number" class="form-control" name="nilai" id="nilai" placeholder="Isi jika berbeda dgn invoice">
        </div>
        <div class="form-group">
          <label for="bukti">Bukti pengiriman</label>
          <input accept="image/*" type="file" id="bukti" name="bukti">
          <p class="help-block">Optional: bukti pengiriman.</p>
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

<script>
  $(function(){
    $('.show-confirm').click(function(){
      $('#modal-payment').modal('show');
    });
    
    $('#form-confirm').submit(function(e){
      e.preventDefault();
      var formdata = new FormData($(this)[0]);
      formdata.append('token', token);
      var btn = $(this).find('button[type="submit"]');
      $.ajax({
       cache: false,
       contentType: false,
       enctype: 'multipart/form-data',
       processData: false,
        data: formdata,
        type: 'post',
        dataType: 'json',
        url: '<?=site_url('profile/confirmPayment');?>',
        beforeSend: function(){
          btn.prop('disabled', true).addClass('btn-warning').removeClass('btn-primary');
        },
        success: function(d){
          if(d.ok==1){
            alert('Terima kasih');
            $('#modal-payment').modal('hide');
          }else{
            alert(d.msg);
          }
          setTimeout(function(){
            btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
          },2000);
          
        },
       error: function(d){
          alert('Terjadi kesalahan');
          setTimeout(function(){
            btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
          },2000);
        }
      });
    });
  });
</script>