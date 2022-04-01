<div class="wrap-content">
  	<div class="users app-table">
		  	<div class="card">
		  		<div class="card-header">
		  			<div class="card-title">
                   WhatsApp Marketing Contact
                    </div>
                    <div class="clearfix"></div>
            <div class="card-body">
  <div class="row">
    <div id="app" class="col-md-6">
      <div class="form-group" style="margin-top: 15px;">
        <p>Fitur untuk marketing Lancarin</p>
        <input 
           id="input-text" 
           type="text" 
           v-model="tel" 
           class="form-control" 
           placeholder="Type phone number"/>
      </div>
      <button
        @click="sendMessage" 
        class="btn btn-primary">
        Send
      </button>
    </div>
  </div>
</div>
</div>
</div>
</div>
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
		  				<div class="row">
		  					<div class="col-md-9 col-sm-6 mb-mobile-15">
		  						<div class="btn-group" role="group" aria-label="export">
								    <a href="<?=cn($module."/update")?>" class="btn btn-default"><i class="fa fa-plus"></i> <?=lang("add_new")?></a>
								    <a href="<?=cn($module."/export")?>" class="btn btn-default"><i class="fa fa-file-excel-o"></i> <?=lang("export_csv")?></a>
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
		                foreach ($result as $key => $row) {
		                ?>
		                    <tr>
		                    	<td>
		                    		<div class="pure-checkbox grey mr15">
			                        	<input type="checkbox" name="id[]" id="check_<?=$row->ids?>" class="filled-in chk-col-red checkItem" value="<?=$row->ids?>">
            							<label class="p0 m0" for="check_<?=$row->ids?>">&nbsp;</label>
            						</div>
		                    	</td>
		                    	<td>
		                        	<div class="btn-group btn-group-option <?=($key + 1 == count($result)?"dropup":"")?>">
									  	<a href="<?=cn($module."/update/".$row->ids)?>" class="btn btn-default"><i class="ft-edit"></i></a>
									  	<div class="btn-group">
									    	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
									    		<span class="caret"></span>
									    	</button>
									    	<ul class="dropdown-menu dropdown-menu-left" role="menu">
												<li><a href="<?=cn($module."/login_as_user/".$row->ids)?>" class="">Login</a></li>
									      		<li><a href="<?=cn($module."/ajax_delete_item")?>" data-id="<?=$row->ids?>" data-redirect="<?=cn($module)?>" data-confirm="<?=lang("are_you_sure_want_delete_it")?>" class="actionItem"><?=lang("delete")?></a></li>
									    	</ul>
									  	</div>
									</div>
		                        </td>
		                        <th scope="row"><?=$page + $key + 1?></th>
		                        <?php foreach ($columns as $column_name => $column_title): if($column_name=='whatsapp'):?>
														<td><a target="_blank" href="https://api.whatsapp.com/send?phone=62<?=ltrim($row->$column_name, 0);?>&text=Halo%20Kak,%20Perkenalkan%20saya%20Rio%20dari%20Lancarin.com,%20ingin%20konfirmasi%20saat%20ini%20Anda%20memiliki%20tagihan%20yang%20belum%20dibayarkan.%0A%0AApakah%20ada%20yang%20bisa%20saya%20bantu%20untuk%20melakukan%20pembayaran%20/%20perpanjang%20paket%20berlangganan%20Anda%20di%20Lancarin.com?%20😊"><?=$row->$column_name?></a></td>
														<?php elseif($column_name=='percent'):?>
														<td><?=intval($row->$column_name);?> %</td>
														<?php else:?>
		                        <td><?=custom_row($row->$column_name, $column_name, $module, $row->ids)?></td>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
<script>
var vm = new Vue({
  el: '#app',
  data: {
    tel: '',
  },
  methods: {
    sendMessage: function (event) {
      window.open('https://api.whatsapp.com/send?phone=62' + this.tel.trim() + '&text=' + 'Halo%20Kak%2C%20Perkenalkan%20saya%20Rio%2C%20ingin%20memperkenalkan%20*Aplikasi%20Instagram%20Marketing%20serba%20Otomatis*%20yang%20bernama%20*Lancarin*%20sebagai%20solusi%20Binis%2FOnline%20Shop%20kakak.%20%0A%0ALancarin%20memiliki%20fitur-fitur%20yang%20membantu%20dan%20memudahkan%20kakak%20dalam%20*mengelola*%20dan%20*memasarkan*%20akun%20Instagram%20Bisnis%20kakak%2C%20fitur-fitur%20nya%20adalah%20%3A%0A%0A-%20Post%0A-%20Repost%20(Support%20Repost%20dari%20Tokopedia%2C%20Shopee%2C%20dan%20Bukalapak)%0A-%20Schedule%20Post%0A-%20Schedule%20Repost%20(Support%20Repost%20dari%20Tokopedia%2C%20Shopee%2C%20dan%20Bukalapak)%0A-%20Auto%20Repost%20(Support%20Repost%20dari%20Tokopedia%2C%20Shopee%2C%20dan%20Bukalapak)%0A-%20Auto%20Find%20%26%20Replace%20Words%20(Harga%2C%20No%20HP%2C%20dan%20lainnya)%20yang%20berfungsi%20untuk%20fitur%20Repost%20%26%20Auto%20Repost%0A-%20Auto%20Watermark%0A-%20Auto%20Follow%20Target%0A-%20Auto%20Unfollow%0A-%20Auto%20Like%0A-%20Auto%20Comment%0A-%20Auto%20Direct%20Message%0A-%20Scrape%20Data%20(Followers%2C%20Followings%2C%20Likers%2C%20Commenters%2C%20User%20Posts)%0A-%20Arisan%20Like%20(Like%20for%20Like)%0A-%20Image%20Editor%0A-%20Template%20Caption%0A-%20Custom%20Link%0A-%20Dan%20masih%20banyak%20lagi%20fitur-fitur%20lainnya%0A%0ADengan%20banyaknya%20fitur-fitur%20*powerfull*%20di%20atas%2C%20_Lancarin%20bisa%20membantu%20bisnis%20kakak%20di%20Instagram%20untuk%20lebih%20berkembang_.%0A%0A*Untuk%20info%20lebih%20lengkap%20kakak%20bisa%20check%20di*%20%3A%20http%3A%2F%2Fbit.ly%2FDaftarLancarin%0A%0AJika%20ada%20yang%20perlu%20_ditanyakan%2Fkonsultasi_%20mengenai%20Lancarin%2C%20kakak%20bisa%20Chat%20Saya%20ya%20Kak%20%3A)');
    }
  }
});
        </script>