<div id="modal-collection" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form id="form-collection" class="actionForm3" role="form" method="POST">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-title"><i class="fa fa-search"></i> Data Collection / Saved Media</div>
            </div>
            <div class="modal-body">
                <div class="row">
					<div class="form-group col-md-10">
						<select class="form-control" id="account-ids" name="ids" >
							<?php if(!empty($accounts)):
							foreach ($accounts as $key => $row) :?>
							<option value="<?=$row->id;?>"><?=$row->username;?></option>
							<?php endforeach; else:?>
							<option value="">Account not found</option>
							<?php endif;?>
						</select>
					</div>
					<div class="form-group col-md-2">
                        <button type="submit" class="btn mb-1 btn-primary btn-block"><span class="fa fa-search"></span> <?=lang('search')?></button>
                    </div>
                </div>
                <div class="instagram-search-collection instagram_search_media">
						<div class="row">
						</div>
                </div>
				
                <div class="instagram-saved-media">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" style="display:none;" class="pull-left load-more-collection-feed btn btn-primary" >Load More</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=lang("close")?></button>
            </div>
        </div>
        </form>
    </div>
</div>
