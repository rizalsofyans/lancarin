<div class="instagram_search_media">
	<div class="row">
		<?php if(!empty($result)){
			foreach ($result->items as $row) {
				switch ($row->media_type) {
					case 8:
						$media = array();
						foreach ($row->carousel_media as $value) {
							//$media_temp = explode("?", $value->image_versions2->candidates[0]->url);
							$media_temp[0] = $value->image_versions2->candidates[0]->url;
							$media[] = $media_temp[0];
						}
						$bg = $row->carousel_media[0]->image_versions2->candidates[0]->url;
						$type = "Carousel";
						break;

					case 2:
						//$media_temp = explode("?", $row->video_versions[0]->url);
						$media_temp[0] = $row->video_versions[0]->url;
						$media = array($media_temp[0]);
						$bg = $row->image_versions2->candidates[0]->url;
						$type = "Video";
						break;

					default:
						//$media_temp = explode("?", $row->image_versions2->candidates[0]->url);
						$media_temp[0] = $row->image_versions2->candidates[0]->url;
						$media = array($media_temp[0]);
						$bg = $row->image_versions2->candidates[0]->url;
						$type = "Photo";
						break;
				}

				$caption = is_object($row->caption)?$row->caption->text:"";
				$singleMedia = htmlspecialchars(json_encode([$media[0]]), ENT_QUOTES, 'UTF-8');
				$media = json_encode($media);
				
		?>
		<div class="col-md-3 col-sm-4 mb15">
			<div class="item" style="background-image: url(<?=$bg?>);">
				<?php if($row->media_type == 2){?>
			        <div class="btn-play"><i class="fa fa-play" aria-hidden="true"></i></div>
			    <?php }?>
			    <div class="type"><?=$type?></div>
				<img class="fake-bg popoverCaption" data-content="<?=$caption?>" data-delay-show="300" data-title="Caption" src="<?=BASE?>assets/img/transparent.png">
				<div class="list-option btn-group bg-white" role="group">
					<div class="col-md-4 col-sm-4 col-xs-4 btn btn-default btn-sm btnGetInstagramMedia" data-media='<?=$singleMedia?>' data-credit="<?=$row->user->username?>" data-caption="<?=$caption?>" data-type='photo'>Post</div>
					<div class="col-md-4 col-sm-4 col-xs-4 btn btn-default btn-sm btnGetInstagramMedia" data-media='<?=$media?>' data-credit="<?=$row->user->username?>" data-caption="<?=$caption?>" data-type='story'>Story</div>
					<div class="col-md-4 col-sm-4 col-xs-4 btn btn-default btn-sm btnGetInstagramMedia" data-media='<?=$media?>' data-credit="<?=$row->user->username?>" data-caption="<?=$caption?>" data-type='carousel'>Carousel</div>
				</div>
			</div>
		</div>
		<?php }}?>
	</div>
</div>

<script type="text/javascript">
	$('.popoverCaption').webuiPopover({content: 'Content' , width: 300, trigger: 'click'});
	<?php if(!empty($result->next_max_id)):?>
	$('.load-more').attr('data-id', '<?=$result->next_max_id;?>').show();
	<?php else:?>
	$('.load-more').hide();
	<?php endif;?>
	$(document).on('click', '.load-more', function(){
		var btn = $(this), next = btn.attr('data-id'), formdata= $('form.actionForm').serializeArray();
		formdata.push({name: 'token', value: token});
		formdata.push({name: 'next_max_id', value: next});
		$.ajax({
			url: '<?=site_url('instagram/post/ajax_next_search');?>',
			data: formdata,
			dataType: 'json',
			type: 'post',
			beforeSend: function(){
				btn.prop('disabled', true).addClass('btn-warning').removeClass('btn-primary');
			},
			success: function(d){
				if(d.status =='success'){
					$('.instagram_search_media .row').append(d.data);
					if(d.next_max_id !=null){
						$('.load-more').prop('disabled', false).addClass('btn-primary').removeClass('btn-warning').attr('data-id', d.next_max_id);
					}else{
						$('.load-more').prop('disabled', false).addClass('btn-primary').removeClass('btn-warning').hide();
					}
				}else{
					alert('Load more failed');
					btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
				}
			},
			error: function(){
				alert('Terjadi kesalahan');
				btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
			}
		});
	});
	
</script>