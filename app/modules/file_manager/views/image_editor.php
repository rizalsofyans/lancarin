<link rel="stylesheet" href="<?=base_url('assets/plugins/pixie/styles.min.css')?>">
<link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500' rel='stylesheet' type='text/css'>
<style>
.vertical-alignment-helper {
    display:table;
    height: 100%;
    width: 100%;
    pointer-events:none; /* This makes sure that we can still click outside of the modal to close it */
}
pixie-editor .cdk-overlay-container {
    position: fixed;
    z-index: 100;
}  
.vertical-align-center {
    /* To center vertically */
    display: table-cell;
    vertical-align: middle;
    pointer-events:none;
}
#modal-save .modal-content {
    /* Bootstrap sets the size of the modal in the modal-dialog class, we need to inherit it */
    width:inherit;
    max-width:inherit; /* For Bootstrap 4 - to avoid the modal window stretching full width */
    height:inherit;
    /* To center horizontally */
    margin: 0 auto;
    pointer-events: all;
}
.loading-overplay{
		z-index: 1200;
	}
</style>
<div class="wrap-content " >
	<div class="row">
		<div class="col-md-12" style="height:90vh;">
			<pixie-editor></pixie-editor>
		</div>
	</div>
</div>

<img id="myimage" class="hidden" src="<?=base_url('assets/img/gallery.png');?>">

<div id="modal-save" class="modal fade" tabindex="-1" role="dialog">
	<div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Where do you want to save?</h4>
      </div>
      <div class="modal-body">
        <button type="button" class="btn btn-primary save-device"><i class="fa fa-desktop"></i> Device</button>
        <button type="button" class="btn btn-primary save-server"><i class="fa fa-cloud-upload"></i> Server</button>
      </div>
    </div>
  </div>
</div>
  </div>
</div>



<script src="<?=base_url('assets/plugins/pixie/scripts.js')?>"></script>
<script src="<?=base_url('assets/js/download.min.js')?>"></script>

    
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
		       assets: '<?=base_url('assets/plugins/pixie/')?>',
		    },
		    ui:{
		    	mode: 'inline'
		    },
	        onSave: function(data, name) {
	        	$('#myimage').attr('src', data);
	        	$('#modal-save').modal('show');
	        },
	    });

	    $('.save-device').click(function(){
	    	download($('#myimage').attr('src'), 'myimage.png', 'image/png');
	    	$('#modal-save').modal('hide');
	    });
	    $('.save-server').click(function(){
	    	var btn = $(this);
	    	$.ajax({
	    		url: '<?=base_url('file_manager/upload_base64');?>',
	    		type: 'post',
	    		data: {token: token, data: $('#myimage').attr('src')},
	    		dataType: 'json',
	    		beforeSend: function(){
	    			$(".loading-overplay").show();
	    		},
	    		success: function(d){
	    			if(d.status=='success'){
	    				mynotif(d.message, 'green');
	    				$('#modal-save').modal('hide');
	    			}else{
						mynotif(d.message);
	    			}
	    			$(".loading-overplay").hide();
	    		},
	    		error: function(){
	    			mynotif('Something Wrong');
	    			$(".loading-overplay").hide();
	    		}
	    	});

	    });
	});
</script>