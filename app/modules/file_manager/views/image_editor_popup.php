<link rel="stylesheet" href="<?=base_url('assets/plugins/pixie/styles.min.css')?>">
<link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500' rel='stylesheet' type='text/css'>
<style>
	.wrap-main .header{
		z-index: 950;
	}
	.loading-overplay{
		z-index: 1000;
	}
</style>
<pixie-editor></pixie-editor>
<img id="myimage" class="hidden" src="<?=base_url('assets/img/gallery.png');?>">

<script src="<?=base_url('assets/plugins/pixie/scripts.js')?>"></script>
    
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

		var pixie = new Pixie({
	    	urls: {
		       base: '<?=base_url('assets/plugins/pixie/')?>',
		       assets: '<?=base_url('assets/plugins/pixie/')?>'
		    },
		    ui:{
		    	mode: 'overlay',
		    	openImageDialog: {
		            show: false
		        }
		    },
		    tools: {
		        export: {
		            defaultFormat: 'png', //png, jpeg or json
		            defaultName: 'image', //default name for downloaded photo file
		            defaultQuality: 1, //works with jpeg only, 0 to 1
		        }
		    },
		    onOpen: function(){
				$(".loading-overplay").hide();
		    },
	        onSave: function(data, name) {
	        	$(".loading-overplay").show();
	            pixie.http().post(
	            	'<?=base_url('file_manager/upload_base64');?>', 
	            	{token: token, name: name, data: data, ids: $('#myimage').attr('data-ids')}
	            	).subscribe(function successCallback(d) {
		                if(d.status=='success'){
		                	mynotif(d.message, 'green');
		                	var ts = new Date().getTime();
		                	$('.file-manager-content .item[data-file="'+ d.link +'"], .preview-image[data-file="'+ d.link +'"], .file-manager-list-images .item[data-file="'+ d.link +'"]').css('background-image','url('+d.link+'?t='+ts+')');
		                	$('.thumb-img[data-file="'+ d.link +'"]').attr('src',d.link+'?t='+ts);
		                }else{
		                	mynotif(d.message);
		                }
		                $(".loading-overplay").hide();
	            	}, function errorCallback(d){
	            		mynotif('Something wrong');
	            		$(".loading-overplay").hide();
	            	});
	        },
	    });
		
		$(document).on('click', '.btn-action.btn-edit', function(){
			var ts = new Date().getTime();
			var img = $(this).closest('.block-img').find('.thumb-img');
			$('#myimage').attr('src', img.attr('src')+'?t='+ts).attr('data-ids', img.attr('data-ids'));
			$(".loading-overplay").show();
			pixie.resetAndOpenEditor('image', $('#myimage').attr('src'));
		});

		$(document).on('click', '#edit-image', function(){
			var c = $('input[id^="md_checkbox_"]:checked');
			if(c.length==0){
				mynotif("Please pick image first");
				return;
			}else if(c.length>1){
				mynotif("Please pick only 1 image to edit");
				return;
			}else if(c.closest('.item').data('type')=='mp4'){
				mynotif("Cannot edit video as an image");
				return;
			}
			var ts = new Date().getTime();
			$('#myimage').attr('src', c.closest('.item').data('file')+'?t='+ts).attr('data-ids', c.val());
			$('#mainModal').modal('hide');
			$(".loading-overplay").show();
			pixie.resetAndOpenEditor('image', $('#myimage').attr('src'));
		});
	});
    
</script>