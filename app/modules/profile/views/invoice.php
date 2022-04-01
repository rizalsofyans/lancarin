<div class="wrap-content container  ">
    <div class="row">
        <div class="col-md-12">
            <div class="card payment-history">
                <div class="card-header">
                    <div class="card-title pull-left">
                        <i class="fa fa-credit-card" aria-hidden="true"></i> Invoice</div>
                    <div class="card-title pull-right">ID: <?=$transaction_id;?>
                    </div>
                  <br>
                    <hr>
                </div>
                <div class="card-block ">
                  <div class="row">
                    <div class="col-xs-6">
                      <address>
                      <strong>Dari:</strong><br> 
                        <?=$fullname;?>,<br>
                        <?=$email;?><br>
                      </address>
                    </div>
                    <div class="col-xs-6 text-right">
                      <address>
                        <strong>Kepada:</strong><br>
                        <?=get_option($bank.'_nama');?><br>
                        <?=strtoupper($bank);?>: <b><?=get_option($bank.'_norek');?></b>
                      </address>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-6">
                      <div>
                      <strong>Tanggal Invoice:</strong><br>
                        <?=$created;?><br>
                      </div>
                    <br>
                      <div>
                      <strong>Tanggal Invoice Kadaluarsa:</strong><br>
                        <?=$invoice_expired;?><br>
                      </div>
                    </div>
                    <div class="col-xs-6 text-right">
                      <div>
                      <strong>Tanggal Akun Kadaluarsa:</strong><br>
                        <?=$expired;?><br>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row" style="margin-top:20px;">
                    <div class="col-md-12">
                      <div class="panel panel-default">
                        <div class="panel-heading">
                          <h3 class="panel-title"><strong>Order summary</strong></h3>
                        </div>
                        <div class="panel-body">
                          <div class="table-responsive">
                            <table class="table table-condensed">
                              <thead>
                                 <tr>
                                   <td><strong>Paket</strong></td>
                                    <td class="text-center"><strong>Deskripsi</strong></td>
                                    <td class="text-center"><strong>Tipe</strong></td>
                                    <td class="text-right"><strong>Harga</strong></td>
                                 </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td><?=$package_name;?></td>
                                  <td class="text-center"><?=$package_description;?></td>
                                  <td class="text-center"><?=$plan;?></td>
                                  <td class="text-right"><?=$price;?></td>
                                </tr>
                                <tr>
                                  <td class="no-line"></td>
                                  <td class="no-line"></td>
                                  <td class="no-line text-center"><strong>Total</strong></td>
                                  <td class="no-line text-right"><?=$price;?></td>
                                </tr>
                                <tr>
                                  <td class="no-line"></td>
                                  <td class="no-line"></td>
                                  <td class="no-line text-center"><strong>Status</strong></td>
                                  <td class="no-line text-right"><span class="label label-<?=$status?'success':'danger';?>" style="font-weight:bold; font-size:16px;"><?=$status?'Sudah':'Belum';?> Dibayar</span></td>
                                </tr>
                              </tbody>
                            </table>
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


<script>
  $(function(){
    
  })
</script>