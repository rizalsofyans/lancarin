<style>
  .instagram_search_media .item{
    background-size: 100%;
    background-repeat: no-repeat;
  }
  .box-signature{
        padding: 10px 20px;
    word-break: break-all;
  }
  .mb-10{
	  margin-bottom: 10px;
  }
  .text-khusus {
    text-transform: none !important;
    font-weight: 400 !important;
    letter-spacing: normal !important;
    font-size: 14px;
    color: #333;
}
</style>

<div class="wrap-content container instagram-app post-module">
    <form action="<?=PATH.segment(1)?>/post/ajax_post" method="POST" class="actionForm">
      <div class="card">
      <div class="card-header ">
					<div class="card-title">
                        <i class="fa ft-edit" aria-hidden="true"></i> Post
              <button type="button" class="btn btn-danger btn-sm" style="float:right;" data-toggle="modal" data-target="#myModal">
  Info </button>

<!-- Modal -->
<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Informasi</h4>
      </div>
      <div class="modal-body text-khusus">
        Anda dapat membatalkan, me-reschedule, post now, dan edit caption postingan yang telah anda jadwalkan melalui <a href="https://lancarin.com/schedules" style="font-weight:bold;">Calendar Schedules</a>.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
                    </div>
				</div>
      </div>
        <div class="row">
            <div class="col-md-3">
              
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fa fa-instagram" aria-hidden="true"></i> <?=lang('instagram_accounts')?>
                        </div>
                    </div>
                    <div class="card-block p0">
                        <div class="list-account max scrollbar scrollbar-dynamic">
                            <?php if(!empty($accounts)){
                                foreach ($accounts as $key => $row) {
                            ?>

                            <a href="javascript:void(0);" class="item">
                                <img class="img-circle" src="<?=$row->avatar?>">
                                <div class="checked"><i class="ft-check"></i></div>
                                <input type="checkbox" name="account[]" value="<?=$row->ids?>" class="hide">
                                <div class="content">
                                    <span class="title"><?=$row->username?></span>
                                </div>
                                <div class="clearfix"></div>
                            </a>
                            <?php }}else{?>
                            <div class="empty">
                                <span><?=lang("add_an_account_to_begin")?></span>
                                <a href="<?=PATH?>account_manager" class="btn btn-primary"><?=lang("add_account")?></a>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card">
                    <?=modules::run("caption/popup")?>
                    
                    <div class="card-overplay"><i class="pe-7s-config pe-spin"></i></div>
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fa ft-edit" aria-hidden="true"></i>
                            <div class="pull-right card-option">
                                <a href="<?=cn("instagram/post/popup_search_media")?>" class="ajaxModal2 btn" data-toggle="tooltip" data-placement="left" title="Search Media" data-original-title="<?=lang('Search media on instagram')?>"><div class="hidden-xs"><i class="ft-search"></i> <?=lang('search_media')?></div><div class="visible-xs-inline-block"><i class="ft-search"></i> Search</div></a>
                                <a href="<?=cn("instagram/post/popup_search_collection")?>" class="ajaxModal-collection btn" data-toggle="tooltip" data-placement="left" title="" data-original-title="Data Collection"><i class="ft-bookmark"></i> Collection</a>
								<a href="<?=cn("instagram/post/popup_search_marketplace")?>" class="ajaxModal-marketplace btn btn-danger" data-toggle="tooltip" data-placement="left" title="" data-original-title="Search media on marketplace"><i class="ft-shopping-cart"></i> marketplace</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-block pt0">
                        <div class="row">
                            <div class="tab-type schedule-instagram-type file-manager-change-type">
                                <a href="javascript:void(0);" class="col-md-4 col-sm-4 col-xs-4 item active" data-type="photo" data-type-image="single">
                                    <i class="ft-image"></i> <?=lang('post')?>
                                    <input type="radio" name="type" value="photo" class="hide" checked="">
                                </a>
                                <a href="javascript:void(0);" class="col-md-4 col-sm-4 col-xs-4 item" data-type="story" data-type-image="single">
                                    <i class="ft-camera"></i> <?=lang('story')?>
                                    <input type="radio" name="type" class="hide" value="story">
                                </a>
                                <a href="javascript:void(0);" class="col-md-4 col-sm-4 col-xs-4 item" data-type="carousel" data-type-image="multi">
                                    <i class="ft-layers"></i> <?=lang('carousel')?>
                                    <input type="radio" name="type" class="hide" value="carousel">
                                </a>
                            </div>
                        </div>

                        <?=modules::run("file_manager/block_file_manager")?>
                      <div class="lead bg-info" style="padding:10px;font-size: 12px;font-weight:400;">
						Batas maksimal file yang diupload adalah 50mb.
					  </div>

                        <div class="form-group form-caption">
                            <div class="list-icon">
                                <a href="javascript:void(0);" class="getCaption" data-toggle="tooltip" data-placement="left" title="<?=lang("get_caption")?>"><i class="ft-command"></i></a>
                                <a href="javascript:void(0);" data-toggle="tooltip" class="saveCaption" data-placement="left" title="<?=lang("save_caption")?>"><i class="ft-save"></i></a>
								<a href="javascript:void(0);" data-toggle="tooltip" data-box="caption" class="getRelatedHashtag" style="color: #2196F3;" data-placement="left" title="Related Hashtag"><i class="ft-hash"></i></a>
                            </div>
                            <textarea class="form-control post-message" name="caption" rows="6" placeholder="<?=lang('add_a_caption')?>" style="height: 114px;"></textarea>
                        </div>

                        <div class="form-group">
                            <div class="pure-checkbox grey mr15 mb-10">
                                <input type="checkbox" id="md_checkbox_schedule" name="is_schedule" class="filled-in chk-col-red enable_instagram_schedule" value="on">
                                <label class="p0 m0" for="md_checkbox_schedule">&nbsp;</label>
                                <span class="checkbox-text-right"> <?=lang('schedule')?></span>
                            </div>
                            <div class="pure-checkbox grey mr15 mb-10">
                                <input type="checkbox" id="md_checkbox_signature" name="signature" class="filled-in chk-col-red enable_instagram_signature" value="on">
                                <label class="p0 m0" for="md_checkbox_signature" data-toggle="collapse" data-target="#signature-option">&nbsp;</label>
                                <span class="checkbox-text-right"> Signature </span>
                            </div>
							<div class="pure-checkbox grey mr15 mb-10">
                                <input type="checkbox" id="md_checkbox_credit" name="credit" class="filled-in chk-col-red enable_instagram_credit" value="on" data-credit="">
                                <label class="p0 m0" for="md_checkbox_credit" data-toggle="collapse" data-target="#credit-option">&nbsp;</label>
                                <span class="checkbox-text-right"> Credit </span>
                            </div>
                            <div class="pure-checkbox grey mr15 mb-10">
                                <input type="checkbox" id="md_checkbox_watermark" name="watermark" class="filled-in chk-col-red enable_instagram_watermark" value="on" >
                                <label class="p0 m0" for="md_checkbox_watermark" data-toggle="collapse" data-target="#watermark-option">&nbsp;</label>
                                <span class="checkbox-text-right"> Watermark </span>
                            </div>
                            <?php 
                                $enable_advance_option = (int)get_option('enable_advance_option',1); 
                                if($enable_advance_option){
                            ?>
                            
                            <div class="pure-checkbox grey mr15 mb-10">
                                <input type="checkbox" id="md_checkbox_comment" name="advance" class="filled-in chk-col-red enable_instagram_comment" value="on">
                                <label class="p0 m0" for="md_checkbox_comment" data-toggle="collapse" data-target="#comment-option">&nbsp;</label>
                                <span class="checkbox-text-right"> <?=lang('advance_option')?></span>
                            </div>
                            <?php } ?>
                        </div>
                        
                        <div class="form-group collapse form-caption" id="comment-option">
                            <div class="list-icon">
                                <a href="javascript:void(0);" class="getCaption" data-toggle="tooltip" data-placement="left" title="<?=lang("get_caption")?>"><i class="ft-command"></i></a>
                                <a href="javascript:void(0);" data-toggle="tooltip" class="saveCaption" data-placement="left" title="<?=lang("save_caption")?>"><i class="ft-save"></i></a>
                                <a href="javascript:void(0);" data-toggle="tooltip" data-box="comment" class="getRelatedHashtag" data-placement="left" title="Related Hashtag"><i class="ft-hash"></i></a>
                            </div>
                            <textarea class="form-control post-message" name="comment" rows="3" placeholder="<?=lang('add_a_first_comment_on_your_post')?>" style="height: 114px;"></textarea>
                        </div>

                        <div class="schedule-option collapse in" id="schedule-option">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="time_post"> <?=lang('time_post')?></label>
                                        <input type="text" name="time_post" class="form-control datetime time_post" id="time_post" disabled="true">
                                    </div>
                                </div>
                            </div>
                        </div>  
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-primary pull-right btnPostNow"> <?=lang('post_now')?></button>
                        <button type="submit" class="btn btn-primary pull-right btnSchedulePost hide"> <?=lang('schedule_post')?></button>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-block p0">
                        <div class="preview-instagram preview-instagram-photo">
                            <div class="preview-header">
                                <div class="pull-left"><i class="ft-camera"></i></div>
                                <div class="instagram-logo"><img src="<?=BASE?>public/instagram/assets/img/instagram-logo.png"></div>
                                <div class="pull-right"><i class="icon-paper-plane"></i></div>
                            </div>
                            <div class="preview-content">
                                <div class="user-info">
                                    <img class="img-circle" src="<?=BASE?>public/instagram/assets/img/avatar.png">
                                    <span><?=lang('anonymous')?></span>
                                </div>
                                <div class="preview-image" style="height: 360px;">
                                </div>
                                <div class="post-info">
                                    <div class="info-active pull-left"> <?=lang('be_the_first_to_Like_this')?></div>
                                    <div class="pull-right">1s</div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="caption-info pt0">
                                    <div class="line-no-text"></div>
                                    <div class="line-no-text"></div>
                                    <div class="line-no-text w50"></div>
                                </div>
                                <div class="box-signature"></div>
                                <div class="preview-comment">
                                    <?=lang("add_a_comment")?>…                                    
                                    <div class="icon-3dot"></div>
                                </div>
                            </div>
                        </div>
                        <div class="preview-instagram preview-instagram-story hide"></div>
                        <div class="preview-instagram preview-instagram-carousel hide">
                            <div class="preview-header">
                                <div class="pull-left"><i class="ft-camera"></i></div>
                                <div class="instagram-logo"><img src="<?=BASE?>public/instagram/assets/img/instagram-logo.png"></div>
                                <div class="pull-right"><i class="icon-paper-plane"></i></div>
                            </div>
                            <div class="preview-content">
                                <div class="user-info">
                                    <img class="img-circle" src="<?=BASE?>public/instagram/assets/img/avatar.png">
                                    <span><?=lang('anonymous')?></span>
                                </div>
                                <div class="preview-image">
                                    <div id="preview-carousel" class="preview-carousel carousel slide"></div>
                                </div>
                                <div class="post-info">
                                    <div class="info-active pull-left"> <?=lang('be_the_first_to_Like_this')?></div>
                                    <div class="pull-right">1s</div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="caption-info pt0">
                                    <div class="line-no-text"></div>
                                    <div class="line-no-text"></div>
                                    <div class="line-no-text w50"></div>
                                </div>
                                <div class="box-signature"></div>
                                <div class="preview-comment">
                                    <?=lang("add_a_comment")?>… 
                                    <div class="icon-3dot"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php $this->load->view('popup_search_media.php');?>
<?php $this->load->view('popup_search_marketplace.php');?>
<?php $this->load->view('popup_search_collection.php');?>
<?php $this->load->view('file_manager/image_editor_popup.php');?>


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
	
	
	//mynotif('Halaman ini sedang dalam penambahan fitur, akan terjadi beberapa error selama proses ini.', 'blue');
  
/*		Menghilangkan 'Anda belum memiliki watermark (sementara)'
    $('#md_checkbox_watermark').click(function(){
        if($(this).prop('checked')){
            $.ajax({
                url: '<?=site_url('instagram/post/check_watermark');?>',
                data: {token:token},
                dataType: 'json',
                type: 'post',
                beforeSend: function(){
                    $('.card-overplay').show();
                },
                success: function(d){
                    if(d.status =='success'){
                        
                    }else{
                        mynotif(d.message);
                        $('#md_checkbox_watermark').prop('checked', false);
                    }
                    $('.card-overplay').hide();
                },
                error: function(){
                    mynotif('Something wrong');
                    $('#md_checkbox_watermark').prop('checked', false);
                    $('.card-overplay').hide();
                }
            });
        }
    });
*/
	$('#md_checkbox_credit').click(function(){
        var checked = $(this).prop('checked');
        var credit = $(this).attr('data-credit');
        if($(this).prop('checked')){
			if(credit !=''){
				var t = $(".post-message").data("emojioneArea").getText();
				$(".post-message").data("emojioneArea").setText(t + "\n\n"+'#repost from @'+credit);
				//signature = d.data;
			}
		}else{
			var t = $(".post-message").data("emojioneArea").getText();
			t = t.replace("\n\n"+'#repost from @'+credit,'');
            $(".post-message").data("emojioneArea").setText(t);
		}
	});
	
	
    $('.getRelatedHashtag').click(function(){
		var checked = $('input[name="account[]"]:checked');
		if(checked.length==0) {
			mynotif('Mohon pilih akun terlebih dahulu'); 
			return false;
		}
		var box = $(this).data('box');
console.log(box);
		var t = $('.post-message[name="'+box+'"]').data("emojioneArea").getText();
		var hash = findHashtags(t);
		
        if(hash !== false){
            $.ajax({
                url: '<?=site_url('instagram/data_collection/get_related_hashtag');?>',
                data: {token:token, hashtag: hash, account_id: checked.val()},
                dataType: 'json',
                type: 'post',
                beforeSend: function(){
                    $('.card-overplay').show();
                },
                success: function(d){
                    if(d.status =='success'){
						$('.post-message[name="'+box+'"]').data("emojioneArea").setText(t + " "+d.data);
                    }else{
                        mynotif(d.message);
                    }
					$('.card-overplay').hide();
                },
                error: function(){
					$('.card-overplay').hide();
                    mynotif('Something wrong');
                }
            });
            
        }else{
			mynotif('Silahkan isi textbox dengan hashtag yang anda inginkan terlebih dahulu');
        }
    });
	
	function findHashtags(searchText) {
		var regexp = /\B\#\w\w+\b/g
		result = searchText.match(regexp);
		if (result) {
			return result[result.length-1];
		} else {
			return false;
		}
	}
	
	
    var signature;
    $('#md_checkbox_signature').click(function(){
        var checked = $(this).prop('checked');
        if($(this).prop('checked')){
            $.ajax({
                url: '<?=site_url('caption/get_signature');?>',
                data: {token:token},
                dataType: 'json',
                type: 'post',
                beforeSend: function(){
                    $('#md_checkbox_signature').prop('disabled', true);
                },
                success: function(d){
                    if(d.status =='success'){
                        var t = $(".post-message").data("emojioneArea").getText();
                        $(".post-message").data("emojioneArea").setText(t + "\n"+d.data);
                        signature = d.data;
                    }else{
                        mynotif(d.message);
                        $('#md_checkbox_signature').prop('checked', false);
                    }
                    $('#md_checkbox_signature').prop('disabled', false);
                },
                error: function(){
                    mynotif('Something wrong');
                    $('#md_checkbox_signature').prop('disabled', false).prop('checked', false);
                }
            });
            
        }else{
			var myemoji = $(".post-message").data("emojioneArea");
            var t = myemoji.getText();
			var sp = signature.split("\n");
			for(i=0;i<sp.length;i++){
				sp[i] = sp[i].trim();
			}
			signature = sp.join("\n");
			t = t.replace("\n"+signature,"");
			myemoji.setText(t);
			
        }
    });
	
	$(".ajaxModal2").click(function(){
			var checked = $('input[name="account[]"]:checked');
			if(checked.length==0) {
				mynotif('Mohon pilih akun terlebih dahulu'); 
				return false;
			}
			
			$('#modal-search-media').modal({
				backdrop: 'static',
				keyboard: false 
			});
			$('#modal-search-media').modal('show');
			$('.account-ids').val(checked.val());
		
			return false;
	});
	
	$(".ajaxModal-collection").click(function(){
		$('#modal-collection').modal({
			backdrop: 'static',
			keyboard: false 
		});
		$('#modal-collection').modal('show');
		return false;
	});
	
	
	$(".ajaxModal-marketplace").click(function(){
			var checked = $('input[name="account[]"]:checked');
			if(checked.length==0) {
				mynotif('Mohon pilih akun terlebih dahulu'); 
				return false;
			}
			
			$('#modal-marketplace').modal({
					backdrop: 'static',
					keyboard: false 
			});
			$('#modal-marketplace').modal('show');
			$('.account-ids').val(checked.val());
			return false;
	});
	
	function getMarketplace(e, elemen){
		e.stopPropagation();
		e.preventDefault();
		
		var form = $('#form-marketplace'),
		formdata = form.serializeArray(),
		btn = elemen=='submit'?form.find('button[type="submit"]'): $('.load-more'),
		next = elemen=='submit'?1:$('.load-more').attr('data-id');
		formdata.push({name: 'token', value: token});
		formdata.push({name: 'page', value: next});
		$.ajax({
			url: '<?=site_url('instagram/post/ajax_search_marketplace');?>',
			data: formdata,
			dataType: 'json',
			type: 'post',
			beforeSend: function(){
				btn.prop('disabled', true).addClass('btn-warning').removeClass('btn-primary');
			},
			success: function(d){
				if(d.status =='success'){
					if(elemen=='submit'){
						//$('.instagram_search_media .row').empty();
						$('.instagram-marketplace-content').html(d.data);
						$('.popoverCaption').webuiPopover({content: 'Content' , width: 300, trigger: 'click'});
					}else{
						$('.instagram-marketplace-content .row').append(d.data);
					}
						
					if(d.load_more ==1){
						$('.load-more-marketplace').attr('data-id', d.page).show();
					}else{
						$('.load-more-marketplace').hide();
					}
					btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
				}else{
					mynotif(d.message);
					btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
				}
			},
			error: function(){
				mynotif('Data yang Anda masukkan salah atau tidak ditemukan');
				btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
			}
		});
	}
	
	$(document).on('submit', '#form-marketplace', function(e){
		getMarketplace(e, 'submit');
	});
	$(document).on('change', '#url-marketplace', function(e){
		var u = getUrlParts($(this).val());
		if(u.domainroot=='tokopedia.com') $('#type-marketplace').val('tokopedia').change();
		else if(u.domainroot=='shopee.co.id') $('#type-marketplace').val('shopee').change();
		else if(u.domainroot=='bukalapak.com') $('#type-marketplace').val('bukalapak').change();
	});
	
	$(document).on('click', '.load-more-marketplace', function(e){
		getMarketplace(e, 'load-more');
	});
	
	$(document).on('click', '.btnGetMarketplaceProduct', function(){
		var btn=$(this),
		div = btn.closest('div.list-option'),
		method = div.data('metode'),
		type=btn.data('type'),
		media = div.data('media'),
		markup = $('#markup').val(),
		url=btn.data('url');
		
		//if(method=='toko'){
			$.ajax({
				url: '<?=site_url('instagram/post/ajax_marketplace_prepare__product');?>',
				data: {token:token, url: url, type:type, markup:markup},
				dataType: 'json',
				type: 'post',
				beforeSend: function(){
					btn.prop('disabled', true).addClass('btn-warning').removeClass('btn-default');
				},
				success: function(d){
					if(d.ok==1){
						//Select tab
						$(".schedule-instagram-type .item[data-type="+type+"]").trigger("click");
						//Set caption
						$(".post-message").data("emojioneArea").setText(d.post);
						
						if(media.length==0) media = d.images;
						
						//Add image
						if(type == "carousel"){
							FileManager.type_select = 'multi';
							for (var i = 0; i < media.length; i++) {
								FileManager.saveMarketplace(media[i], 'jpg');
							}
						}else{
							FileManager.type_select = 'single';
							FileManager.saveMarketplace(media[0], 'jpg');
						}
						
						//Hide modal
						$('#modal-marketplace').modal('hide');

					}else{
						mynotif(d.msg);
					}
					btn.prop('disabled', false).addClass('btn-default').removeClass('btn-warning');
				},
				error: function(){
					mynotif('Data yang Anda masukkan salah atau tidak ditemukan');
					btn.prop('disabled', false).addClass('btn-default').removeClass('btn-warning');
				}
			});
		//}else{
		//	alert('produk');
		//}
	});
	
	function getUrlParts(fullyQualifiedUrl) {
		var url = {},
			tempProtocol
		var a = document.createElement('a')
		// if doesn't start with something like https:// it's not a url, but try to work around that
		if (fullyQualifiedUrl.indexOf('://') == -1) {
			tempProtocol = 'https://'
			a.href = tempProtocol + fullyQualifiedUrl
		} else
			a.href = fullyQualifiedUrl
		var parts = a.hostname.split('.')
		url.origin = tempProtocol ? "" : a.origin
		url.domain = a.hostname
		url.subdomain = parts[0]
		url.domainroot = ''
		url.domainpath = ''
		url.tld = '.' + parts[parts.length - 1]
		url.path = a.pathname.substring(1)
		url.query = a.search.substr(1)
		url.protocol = tempProtocol ? "" : a.protocol.substr(0, a.protocol.length - 1)
		url.port = tempProtocol ? "" : a.port ? a.port : a.protocol === 'http:' ? 80 : a.protocol === 'https:' ? 443 : a.port
		url.parts = parts
		url.segments = a.pathname === '/' ? [] : a.pathname.split('/').slice(1)
		url.params = url.query === '' ? [] : url.query.split('&')
		for (var j = 0; j < url.params.length; j++) {
			var param = url.params[j];
			var keyval = param.split('=')
			url.params[j] = {
				'key': keyval[0],
				'val': keyval[1]
			}
		}
		// domainroot
		if (parts.length > 2) {
			url.domainroot = parts[parts.length - 2] + '.' + parts[parts.length - 1];
			// check for country code top level domain
			if (parts[parts.length - 1].length == 2 && parts[parts.length - 1].length == 2)
				url.domainroot = parts[parts.length - 3] + '.' + url.domainroot;
		}
		// domainpath (domain+path without filenames) 
		if (url.segments.length > 0) {
			var lastSegment = url.segments[url.segments.length - 1]
			var endsWithFile = lastSegment.indexOf('.') != -1
			if (endsWithFile) {
				var fileSegment = url.path.indexOf(lastSegment)
				var pathNoFile = url.path.substr(0, fileSegment - 1)
				url.domainpath = url.domain
				if (pathNoFile)
					url.domainpath = url.domainpath + '/' + pathNoFile
			} else
				url.domainpath = url.domain + '/' + url.path
		} else
			url.domainpath = url.domain
		return url
	}
	
	function getCollection(e, elemen){
		e.stopPropagation();
		e.preventDefault();
		
		var form = $('#form-collection'),
		formdata = form.serializeArray(),
		btn = elemen=='submit'?form.find('button[type="submit"]'): $('.load-more-collection'),
		next = elemen=='submit'?null:$('.load-more-collection').attr('data-id');
		formdata.push({name: 'token', value: token});
		formdata.push({name: 'next_max_id', value: next});
		$.ajax({
			url: '<?=site_url('instagram/post/ajax_search_collection');?>',
			data: formdata,
			dataType: 'json',
			type: 'post',
			beforeSend: function(){
				btn.prop('disabled', true).addClass('btn-warning').removeClass('btn-primary');
			},
			success: function(d){
				if(d.status =='success'){
					if(elemen=='submit'){
						$('.instagram-search-collection .row').html(d.data);
					}else{
						$('.instagram-search-collection .row').append(d.data);
					}
					$('.popoverCaption').webuiPopover({content: 'Content' , width: 300, trigger: 'click'});

					$('.load-more-collection-feed').hide();
					if(d.next_max_id != null && d.next_max_id != ''){
						$('.load-more-collection').attr('data-id', d.next_max_id).show();
					}else{
						$('.load-more-collection').hide();
					}
					btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
				}else{
					mynotif(d.message);
					btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
				}
			},
			error: function(){
				mynotif('Data yang Anda masukkan salah atau tidak ditemukan');
				btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
			}
		});
	}
	
	$(document).on('submit', '#form-collection', function(e){
		getCollection(e, 'submit');
	});
	
	$(document).on('click', '.load-more-collection', function(e){
		getCollection(e, 'load-more');
	});
	
	
	function getCollectionFeed(e, elemen, btn=null){
		e.stopPropagation();
		e.preventDefault();
		
		var form = $('#form-collection'),
		formdata = form.serializeArray(),
		btn = elemen=='submit'?btn: $('.load-more-collection-feed'),
		next = elemen=='submit'?null:$('.load-more-collection-feed').attr('data-id');
		collection_id = elemen=='submit'?btn.attr('data-id'):$('.load-more-collection-feed').attr('data-collection-id');
		formdata.push({name: 'token', value: token});
		formdata.push({name: 'next_max_id', value: next});
		formdata.push({name: 'collection_id', value: collection_id});
		$.ajax({
			url: '<?=site_url('instagram/post/ajax_search_collection_feed');?>',
			data: formdata,
			dataType: 'json',
			type: 'post',
			beforeSend: function(){
				btn.prop('disabled', true).addClass('btn-warning').removeClass('btn-primary');
			},
			success: function(d){
				if(d.status =='success'){
					if(elemen=='submit'){
						$('.instagram-search-collection .row').html(d.data);
					}else{
						$('.instagram-search-collection .row').append(d.data);
					}
					$('.popoverCaption').webuiPopover({content: 'Content' , width: 300, trigger: 'click'});

					$('.load-more-collection').hide();
					if(d.next_max_id != null){
						$('.load-more-collection-feed').attr('data-id', d.next_max_id).show();
					}else{
						$('.load-more-collection-feed').hide();
					}
					btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
				}else{
					mynotif(d.message);
					btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
				}
			},
			error: function(){
				mynotif('Data yang Anda masukkan salah atau tidak ditemukan');
				btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
			}
		});
	}
	
	$(document).on('click', '.btnGetSavedMedia', function(e){
		$('.load-more-collection-feed').attr('data-collection-id', $(this).attr('data-id'));
		$('.load-more-collection-feed').attr('data-id', null);
		getCollectionFeed(e, 'submit', $(this));
	});
	
	$(document).on('click', '.load-more-collection-feed', function(e){
		getCollectionFeed(e, 'load-more');
	});
	
});
</script>