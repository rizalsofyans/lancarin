<div id="modal-marketplace" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form id="form-marketplace" class="actionForm2"method="POST">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-title"><i class="fa fa-search"></i> Search Marketplace</div>
            </div>
            <div class="modal-body">
                <div class="row">
					<input type="hidden" class="account-ids" name="ids" value="">
                    <div class="form-group col-md-6">
                        <input type="text" class="form-control" id="url-marketplace" name="url" placeholder="Username Toko / Url Toko / Url Produk">
                    </div>
                    <div class="form-group col-md-2">
						<div class="input-group">
						  <input type="number" class="form-control" id="markup" title="markup" name="markup" value="0" min="0" max="500"aria-describedby="basic-addon2" placeholder="Persentase Keuntungan">
						  <span class="input-group-addon" id="basic-addon2">%</span>
						</div>
                        
                    </div>
                    <div class="form-group col-md-2">
                        <select class="form-control" id="type-marketplace" name="type">
                            <option value="shopee">Shopee</option>
                            <option value="tokopedia">Tokopedia</option>
                            <option value="bukalapak">Bukalapak</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <button type="submit" class="btn mb-1 btn-primary btn-block"><span class="fa fa-search"></span> <?=lang('search')?></button>
                    </div>
                </div>
                <div class="instagram-marketplace-content">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" style="display:none;" class="pull-left load-more-marketplace btn btn-primary" >Load More</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=lang("close")?></button>
            </div>
        </div>
        </form>
    </div>
</div>