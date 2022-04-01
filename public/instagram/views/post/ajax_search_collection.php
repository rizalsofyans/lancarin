<div class="instagram_search_collection instagram_search_media">
	<div class="row">
		<div class="col-md-3 col-sm-4 mb15">
			<div class="item" style="background-image: url(<?=base_url('assets/img/gallery.png')?>);">
			    <div class="type" style="font-size:20px">&#x221e;</div>
				<img class="fake-bg popoverCaption" data-delay-show="300" data-title="Caption" src="<?=BASE?>assets/img/transparent.png">
				<div class="list-option btn-group bg-white" role="group">
					<div class="col-md-12 col-sm-12 col-xs-12 btn btn-default btn-sm btnGetSavedMedia" data-id=''  data-type='media'>All Post</div>
				</div>
			</div>
		</div>
		<?php if(!empty($result)){
			foreach ($result->items as $row) {
				$bg = isset($row->cover_media->image_versions2->candidates[1]->url)?$row->cover_media->image_versions2->candidates[1]->url:$row->cover_media->image_versions2->candidates[0]->url;
				
		?>
		<div class="col-md-3 col-sm-4 mb15">
			<div class="item" style="background-image: url(<?=$bg?>);">
			    <div class="type" style="font-size:14px"><?=$row->collection_media_count?></div>
				<img class="fake-bg popoverCaption" data-delay-show="300" data-title="<?=$row->collection_name?>" src="<?=BASE?>assets/img/transparent.png">
				<div class="list-option btn-group bg-white" role="group">
					<div class="col-md-12 col-sm-12 col-xs-12 btn btn-default btn-sm btnGetSavedMedia" data-id='<?=$row->collection_id?>' data-type='<?=$row->collection_type?>'><?=$row->collection_name?></div>
				</div>
			</div>
		</div>
		<?php }}?>
	</div>
</div>

<script type="text/javascript">
$(function(){
	$('.popoverCaption').webuiPopover({content: 'Content' , width: 300, trigger: 'click'});
	<?php if(!empty($result->next_max_id)):?>
	$('.load-more').attr('data-id', '<?=$result->next_max_id;?>').show();
	<?php else:?>
	$('.load-more').hide();
	<?php endif;?>
	$(document).on('click', '.load-more-collection', function(){
		var btn = $(this), next = btn.attr('data-id'), formdata= $('form.actionForm').serializeArray();
		formdata.push({name: 'token', value: token});
		formdata.push({name: 'next_max_id', value: next});
		$.ajax({
			url: '<?=site_url('instagram/post/ajax_next_collection');?>',
			data: formdata,
			dataType: 'json',
			type: 'post',
			beforeSend: function(){
				btn.prop('disabled', true).addClass('btn-warning').removeClass('btn-primary');
			},
			success: function(d){
				if(d.status =='success'){
					$('.instagram_search_collection .row').append(d.data);
					if(d.next_max_id !=null){
						$('.load-more-collection').prop('disabled', false).addClass('btn-primary').removeClass('btn-warning').attr('data-id', d.next_max_id);
					}else{
						$('.load-more-collection').prop('disabled', false).addClass('btn-primary').removeClass('btn-warning').hide();
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
	
	$(document).on('click', '.btnGetSavedMedia', function(){
		var btn = $(this), id = btn.attr('data-id'), formdata= $('form.actionForm').serializeArray();
		formdata.push({name: 'token', value: token});
		formdata.push({name: 'collection_id', value: id});
		$.ajax({
			url: '<?=site_url('instagram/post/ajax_saved_media');?>',
			data: formdata,
			dataType: 'json',
			type: 'post',
			beforeSend: function(){
				btn.prop('disabled', true).addClass('btn-warning').removeClass('btn-primary');
			},
			success: function(d){
				if(d.status =='success'){
					$('.instagram-saved-media .row').html(d.data);
					if(d.next_max_id !=null){
						$('.load-more-collection').prop('disabled', false).addClass('btn-primary').removeClass('btn-warning').attr('data-id', d.next_max_id);
					}else{
						$('.load-more-collection').prop('disabled', false).addClass('btn-primary').removeClass('btn-warning').hide();
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
	
	
});	
</script>