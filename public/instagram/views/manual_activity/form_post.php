<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" />
<link rel="stylesheet" href="https://unpkg.com/nprogress@0.2.0/nprogress.css" />
<style>
.pb-20{
	padding-bottom:20px;
}
.mt-10{
	margin-top: 10px;
}
.mt-20{
	margin-top: 20px;
}
.additional{
	border-bottom: 1px solid #f5f5f5;
}
.mb-10{
	margin-bottom: 10px;
}

ul.multiselect-container label.checkbox{
	font-size: 14px;
}
.mb-20{
	margin-bottom:20px;
}
.block-parent1{
	border: 2px dashed #ccc;
	padding: 2px;
	min-height: 100px;
}
.block-post, .block-post-video{
	border: 1px solid #000;
	padding: 5px;
	margin-top: 5px;
	margin-bottom: 5px;
}
.block-img, .block-video{
	border: 1px solid #000;
    padding: 5px;
    margin: 5px;
    float: left;
	position: relative;
}
.block-action{
	position: absolute;
    top: 0;
    right: 0;
}
.btn-action{
	cursor:pointer;
	float:left;
	padding: 1px 2px;
	border: 1px solid #000;
	border-right:0;
	border-top:0;
	background-color: #fff;
}
.block-parameter{
	clear:both;
	padding-top: 10px;
	border-top: 1px dotted #ccc;
}
.thumb-img{
	width:100px;
}
.emojionearea .emojionearea-editor {
    max-height: 8em !important;
}
.btn-move{
	padding: 4px;
    border: 1px dotted #c0c0c0;
}
  .text-khusus {
    text-transform: none !important;
    font-weight: 400 !important;
    letter-spacing: normal !important;
    font-size: 14px;
    color: #333;
}
</style>

<div class="wrap-content tab-list">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
        <?=modules::run("caption/popup")?>
				<div class="card-header ">
					<div class="card-title">
                        <i class="fa fa-clone" aria-hidden="true"></i> Bulk Post
               <button type="button" class="btn btn-danger btn-sm" style="float:right;" data-toggle="modal" data-target="#myModal">
  Info </button>
            
<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Informasi</h4>
      </div>
      <div class="modal-body text-khusus">
        Fitur Bulk Post support postingan berupa single post dan multiple post/carousel (tidak support Instastory) dan support tipe media berupa foto dan video.<br><br>
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
				<div class="card-block p0">
					<div class="tab-content p15">
						<div id="general" class="tab-pane fade in active">
							<form class="form-manual-activity" >
								<div class="row mb0">
									<div class="col-md-4">
										<div class="form-group">
											<label>Used Account</label><br>
											<select name="account[]" id="account" multiple class="form-control show-tick" required >
												<?php if(!empty($accounts)):
												foreach ($accounts as $row) :
												?>
												<option value="<?=$row->id?>"><?=$row->username?></option>
												<?php endforeach;endif;?>
											</select>
										</div>
									</div>
									<div class="col-md-8">
										<br>
										<button type="button" id="btn-show-filemanager" class="btn btn-default"><i class="fa fa-laptop"></i> File Manager</button> 
										<button type="button" id="browse" class="btn btn-default"><i class="fa fa-upload"></i> Upload</button> 
										<button type="button" class="btn btn-default hidden"><i class="fa fa-google-drive"></i> Google Drive</button> 
										<button type="button" class="btn btn-default hidden"><i class="fa fa-dropbox"></i> Dropbox</button> 
									</div>
								</div>
								<div class="row mb0">
									<div class="col-md-4">
										<div class="form-group form-caption master-caption ">
											<label>Default Caption</label>
											<div class="list-icon">
												<a href="javascript:void(0);" class="getCaption" data-toggle="tooltip" data-placement="left" title="<?=lang("get_caption")?>"><i class="ft-command"></i></a>
												<a href="javascript:void(0);" data-toggle="tooltip" class="saveCaption" data-placement="left" title="<?=lang("save_caption")?>"><i class="ft-save"></i></a>
												<a href="javascript:void(0);" data-toggle="tooltip" class="btn-help-message" data-placement="left" title="Dokumentasi format pesan"><i class="fa fa-book"></i></a>
											</div>
											<textarea class="form-control post-message" id="master-post" name="master-caption" rows="3" placeholder="default caption" style="height: 114px;"></textarea>
										</div>
									</div>
									<div class="col-md-8" style="margin-top:40px;">
										<div class="row">
											<div class="col-md-12 mb-10">
												<div class="additional">Additional Options</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="start">Start Time</label>
													<input type="text"  id="start" class="form-control mydatetime" required value="<?=NOW;?>"  >
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<label for="delay">Delay <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Delay per account dalam satuan menit"></i></label>
													<input type="number" name="delay" id="delay" class="form-control" required value="1" min="1" >
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<label for="interval">Interval <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Interval for next run dalam satuan menit"></i></label>
													<input type="number" id="interval" class="form-control" required value="10" min="1" >
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label>Watermark</label><br/>
													<div class="pure-checkbox grey mb-10">
														<input type="checkbox" id="md_checkbox_watermark" name="config[watermark]" class="filled-in chk-col-red enable_instagram_watermark" value="on" >
														<label class="p0 m0" for="md_checkbox_watermark" data-toggle="collapse" data-target="#watermark-option">&nbsp;</label>
														<span class="checkbox-text-right"> Watermark </span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row mb-10 mt-10 hidden">
									<div class="col-md-12 mb-10 ">
										<div class="additional">Custom Time <button type="button" class="btn btn-default btn-sm btn-show-custom-time" value="hide"><i class="fa fa-plus"></i></button></div>
									</div>
									<div class="box-custom-time" style="display:none;" >
									<div class="col-md-3">
										<button type="button" class="btn btn-default btn-custom-time" value="allday">Allday</button> 
										<button type="button" class="btn btn-default btn-custom-time" value="weekend">Weekend</button>
										<button type="button" class="btn btn-default btn-custom-time" value="rabu-jumat">Rabu-Jumat</button>
									</div>
									<div class="col-md-9">
										<?php 
										$day = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
										for($a=0;$a<=6;$a++): ?>
										<div class="pure-checkbox grey mr15">
											<input type="checkbox" name="days[]" id="check_day_<?=$a?>" class="filled-in chk-col-red checkItem" checked value="<?=$a;?>">
											<label class="" for="check_day_<?=$a?>">&nbsp;</label> <?=$day[$a];?>
										</div>
										<?php endfor;?>
									</div>
									</div>
								</div>
								<div class="row mb-10 mt-10">
									<div class="box-custom-time" style="display:none;">
									<div class="col-md-3">
										<button type="button" class="btn btn-default btn-custom-time" value="24jam">24 Jam</button> 
										<button type="button" class="btn btn-default btn-custom-time" value="14-17">14-17</button>
										<button type="button" class="btn btn-default btn-custom-time" value="17-21">17-21</button>
									</div>
									<div class="col-md-9">
										<?php for($a=0;$a<24;$a++):?>
										<div class="pure-checkbox grey mr15">
											<input type="checkbox" name="hours[]" id="check_hour_<?=$a?>" class="filled-in chk-col-red checkItem" checked value="<?=$a;?>">
											<label class="" for="check_hour_<?=$a?>">&nbsp;</label> <?=$a<10?'0'.$a:$a;?>
										</div>
										<?php endfor;?>
									</div>
									</div>
								</div>
								
								<div class="row mb0 block-parent1 " id="upload-area">
									<div id="not-support" style="margin-top: 32px;" class="col-md-8 col-md-offset-2 text-center">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
								</div>
								<div class="row mt-20">
									<div class="col-md-6" style="float: left;width: 50%;">
										<a href="https://lancarin.com/schedules" class="btn btn-default ">
											<i class="fa fa-arrow-circle-left" aria-hidden="true"></i> <span>Schedules</span>
										</a>
									</div>
									<div class="col-md-6" style="float: left;width: 50%;">
										<button type="submit" disabled class="btn btn-primary waves-effect btn-save pull-right">
											<i class="fa fa-save" aria-hidden="true"></i> <span>Save</span>
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!--begin clone block upload-->
<div id="clone-block-upload" class="col-md-12 block-post hidden ">
	<div class="block-img ">
		<div class="block-action">
			<div class="btn-action btn-edit"><i class="fa fa-edit"></i></div><div class="btn-action btn-delete"><i class="fa fa-times"></i></div>
		</div>
		<div>
			<img class="thumb-img " src="https://via.placeholder.com/50?text=img">
		</div>
	</div>
	<div class="block-parameter">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group form-caption ">
					<label>Caption</label>
					<div class="list-icon" >
						<a href="javascript:void(0);" class="getCaption" data-toggle="tooltip" data-placement="left" title="<?=lang("get_caption")?>"><i class="ft-command"></i></a>
						<a href="javascript:void(0);" data-toggle="tooltip" class="saveCaption" data-placement="left" title="<?=lang("save_caption")?>"><i class="ft-save"></i></a>
						<a href="javascript:void(0);" data-toggle="tooltip" class="btn-help-message" data-placement="left" title="Dokumentasi format pesan"><i class="fa fa-book"></i></a>
					</div>
					<textarea class="form-control textarea-caption" name="caption[]" rows="3" placeholder="default caption" style="height: 114px;">{default_caption}</textarea>
				</div>
				<!--<div class="form-group ">
					<textarea class="form-control textarea-caption" name="caption[]" rows="3" placeholder="default caption"></textarea>
				</div>-->
			</div>
			<div class="col-md-4">
        <label for="timepost">Time Post</label>
				<input type="text" name="start[]" class="form-control mydatetime start-post" required value="<?=NOW;?>">
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<div class="pure-checkbox grey mb-10">
            <label for="watermarkcheck">Watermark</label><br>
						<input type="checkbox"  id="watermark-option-dummy" name="watermark[]" class="filled-in chk-col-red enable_instagram_watermark block-watermark" value="on" >
						<label class="p0 m0 watermark-label"  data-toggle="collapse" data-target="#watermark-option-dummy">&nbsp;</label>
						<span class="checkbox-text-right"> Watermark </span>
					</div>
				</div>
			</div>
			<div class="col-md-1">
				<button type="button" class="btn btn-danger btn-delete-block"><i class="fa fa-trash"></i></button>
			</div>
		</div>
	</div>
</div>
<!--end clone block upload-->

<div id="modal-warning" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Warning</h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary btn-adjust">Adjust</button>
        <button type="button" class="btn btn-danger btn-force">Force</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php echo Modules::run('caption/popup_documentation');?>
<?php echo Modules::run('file_manager/popup_filemanager');?>
<?php $this->load->view('file_manager/image_editor_popup.php');?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>
<script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>
<script src="<?=site_url('assets/plugins/plupload/js/plupload.full.min.js');?>"></script>

<script>

$(function(){
	var adjust = false;
		var force = false;
	$('.master-caption').height(114);
	
	function mynotif(msg, color='red'){
		iziToast.show({
			icon: 'fa fa-bell-o',
			message: msg,
			color: color,
			position: 'bottomCenter'
		});
	}
	
	$('.block-parent1').on('click', '.watermark-label', function(){
		var checkbox = $(this).closest('.pure-checkbox').find('.block-watermark');
		var c = checkbox.prop('checked');
		console.log(c);
		checkbox.prop('checked', !c);
	});
	
	
	$('#md_checkbox_watermark').click(function(){
		var c = $(this).prop('checked');
		$('.block-parent1 .block-watermark').prop('checked', c);
	});
	
	var filemanager_opened=false;
	$('#btn-show-filemanager').click(function(){
		$("#modal-filemanager").modal('show');
		if(!filemanager_opened){
			filemanager_opened=true;
			filemanager_load();
			FileManager.uploadFile("#fileuploadpopup");
		}
		
	});
	
	$('.file-manager-add-file').click(function(){
		var check = $('#modal-filemanager .checkItem:checked');
		if(check.length==0){
			mynotif('Tidak ada item yang di pilih');
		}else{
			$.each(check, function(i,e){
				$(this).prop('checked',false);
				var tmp = {id:$(this).val()};
				var link = $(this).closest('.item').data('file');
				var type = $(this).closest('.item').data('type');
				var ids = $(this).val();console.log(ids);
				type = type=='mp4'?'image':'video';
				createPreview(tmp, link, ids);
			});
			doSort(1);
			doSort(2);
		}
	})
	
	function filemanager_load(page=0){
    	if($(".file-manager-loader2").length > 0){
    		_loader = $(".file-manager-loader2");
    		_url    = PATH + "file_manager/ajax_load_files";
    		_data   = $.param({token:token, page: page});
    		if(!$(".file-manager-loading").length > 0){
				_loader.append('<div class="file-manager-loading"></div>');
			}
    		$.post(_url, _data, function(_result){
                $(".file-manager-total-item").html(_result.total_item);
    			if(page == 0){
                    _loader.html(_result.data);
                }else{
                    $(".file-manager-loading").remove();
                    _loader.append(_result.data); 
                }

                if($(".file-manager-scrollbar").length > 0){
                    const ps = new PerfectScrollbar('.file-manager-scrollbar', {
                      wheelSpeed: 2,
                      wheelPropagation: true,
                      minScrollbarLength: 20
                    });

                    $(".file-manager-scrollbar").scroll(function(e) {
                        $(window).resize();
                    });
                }
                var ts = new Date().getTime();
                $(".lazy").Lazy({
                    afterLoad: function(element) {
                        _image = element.attr("src");
                        element.parents(".item").css({ 'background-image' : 'url('+ _image +'?t='+ts+')' });
                        element.remove();
                    }
                });
    		}, "json");
    	}
    };
	
	
	function isJSON(text){
		if (typeof text!=="string"){
			return false;
		}
		try{
			JSON.parse(text);
			return true;
		}
		catch (error){
			return false;
		}
	}
	var maxNumFile = 50;
	var addedFile = 0;
	var uploader = new plupload.Uploader({
		runtimes : 'html5,flash,silverlight,html4',
		drop_element : 'general',
		file_data_name: 'files[]',
		browse_button : 'browse', // you can pass in id...
		container: $('#upload-area')[0], // ... or DOM Element itself
	
		url : "<?=site_url('file_manager/upload_files');?>",
		 
		filters : {
			max_file_size : '5mb',
			mime_types: [
				{title : "Image files", extensions : "jpg,jpeg,png"},
				{title : "Video files", extensions : "mp4"}
			],
			//prevent_duplicates: true
		},
	 
		// Flash settings
		//flash_swf_url : 'https://cdnjs.cloudflare.com/ajax/libs/plupload/3.1.2/Moxie.swf',
		flash_swf_url : '<?=site_url('assets/plugins/plupload/js/Moxie.swf');?>',
		
		// Silverlight settings
        silverlight_xap_url : '<?=site_url('assets/plugins/plupload/js/Moxie.xap');?>',
		multipart_params : {
			token : token
		},
	 
		init: {
			PostInit: function() {
				$('#not-support').text('You can drop file here');
			},
	 
			FilesAdded: function(up, files) {
				
				if(up.files.length > maxNumFile )
				{
					up.splice(maxNumFile);
					alert('no more than '+ maxNumFile + ' file(s)');
					return false;
				}
				if (up.files.length === maxNumFile) {
					$('#browse').prop('disabled', true);
				}
				plupload.each(files, function(file) {
					//NProgress.set(0.1);
					NProgress.inc(0.2);
					up.start();
				});
			},

			UploadProgress: function(up, file) {
				doProgress(file);
				//NProgress.set(Math.round(file.percent)/100);
			},
			
			FilesRemoved: function(up, files) {
                addedFile--;
				checkNumFiles();
            },
			
			FileUploaded: function(up, file, result){
				if(isJSON(result.response)){
					json = JSON.parse(result.response);
					if( json.status=='success'){
						createPreview(file, json.link, json.ids);
						addedFile++;
						checkNumFiles();
					}else{
						mynotif('Error', 'Upload file: '+ file.name + ' failed');
					}
				}else{
					mynotif('Error', 'Something wrong');
				}
			},
			
			UploadComplete: function(up, file){
				//mergeFiles();
				NProgress.done();
			},
	 
			Error: function(up, err) {console.log(err.code);
				message = err.message;
				if(err.code == -601) message='Only PDF File allowed';
				else if(err.code == -602) message='Duplicate file is not allowed';
				mynotif('Error', message, 'red');
			}
		}
	});
	 
	uploader.init();
	function createPreview(file, link, ids=''){
		var now = moment().unix();
		$('#not-support').remove();
		var newElem = $('#clone-block-upload').clone();
		var countBlockPost = $('.block-parent1 .block-post').length;
		var date = $('#start').val();
		var ts = moment(date).unix();
		var interval = Number($('#interval').val()) * 60;
		var next = ts + (interval * countBlockPost);
		newElem.find('.thumb-img').attr('src', link+'?t='+ts).attr('data-ids', ids).attr('data-file',link);
		newElem.find('.textarea-caption').emojioneArea();
		newElem.find('.textarea-caption').addClass('tc'+ts);
		newElem.find('.start-post').val(moment.unix(next).format('YYYY-MM-DD HH:mm:ss'));
		newElem.find('#watermark-option-dummy').attr('id','watermark-option-'+now);
		newElem.find('.watermark-label').attr('data-target', '#watermark-option-'+now);
		newElem.attr('id', file.id);
		newElem.removeClass('hidden');
		$('.block-parent1').append(newElem);
		$('.mydatetime').bootstrapMaterialDatePicker({ format : 'YYYY-MM-DD HH:mm:ss', minDate : new Date() });
		checkNumFiles();
	}
	
	function doProgress(file){
		$('#'+file.id).find('b').html('<span>' + file.percent + "%</span>");
	}
	
	function checkNumFiles(){
		addedFile =  $('.block-parent1 .thumb-img').length;
		if(addedFile>0){
			$('.btn-save').prop('disabled',false);
		}else{
			$('.btn-save').prop('disabled',true);
		}
		if(addedFile>=maxNumFile){
			$('#browse').prop('disabled',true);
		}else{
			$('#browse').prop('disabled',false);
		}
	}
	
	function doSort(id){
		if(id==1){
			$( ".block-parent1" ).sortable({
				handle: '.btn-move'
			});
		}else{
			$( ".block-post" ).sortable({
				connectWith: ".block-post",
				items: ".block-img"
			});
		}
	}
	
	
	
	$('.btn-show-custom-time').click(function(){//alert(1);
		if($(this).val()=='hide'){
			$('.box-custom-time').show();
			$(this).find('i').addClass('fa-minus').removeClass('fa-plus');
			$(this).val('show');
		}else{
			$('.box-custom-time').hide();
			$(this).find('i').addClass('fa-plus').removeClass('fa-minus');
			$(this).val('hide');
		}
	});
	
	var allhour_pressed = false;
	var allday_pressed = false;
	$('.btn-custom-time').click(function(){
		var val = $(this).val();
		if(val=='24jam'){
			$('input[id^="check_hour_"]').prop('checked', !allhour_pressed);
			allhour_pressed = !allhour_pressed;
		}else if(val =='14-17'){
			$('input[id^="check_hour_"]').prop('checked', false);
			$('input[id="check_hour_14"]').prop('checked', true);
			$('input[id="check_hour_15"]').prop('checked', true)
			$('input[id="check_hour_16"]').prop('checked', true);
			$('input[id="check_hour_17"]').prop('checked', true)
		}else if(val =='17-21'){
			$('input[id^="check_hour_"]').prop('checked', false);				
			$('input[id="check_hour_17"]').prop('checked', true);
			$('input[id="check_hour_18"]').prop('checked', true);
			$('input[id="check_hour_19"]').prop('checked', true);
			$('input[id="check_hour_20"]').prop('checked', true);
			$('input[id="check_hour_21"]').prop('checked', true);
		}else if(val=='allday'){
			$('input[id^="check_day_"]').prop('checked', !allday_pressed);
			allday_pressed = !allday_pressed;
		}else if(val =='weekend'){
			$('input[id^="check_day_"]').prop('checked', false);				
			$('input[id="check_day_0"]').prop('checked', true);
			$('input[id="check_day_6"]').prop('checked', true);
		}else if(val =='rabu-jumat'){
			$('input[id^="check_day_"]').prop('checked', false);				
			$('input[id="check_day_3"]').prop('checked', true);
			$('input[id="check_day_4"]').prop('checked', true);
			$('input[id="check_day_5"]').prop('checked', true);
		}
		parseTarget();
	});

	$('#general').on('click','.btn-action.btn-delete', function(){
		$(this).closest('.block-img').remove();
	});
	
	$('#general').on('click','.btn-delete-block', function(){
		$(this).closest('.block-post').remove();
	});
	
	$('input[id^="check_day_"], input[id^="check_hour_"]').click(function(){
		parseTarget(); 
	});
	$('.btn-adjust').click(function(){
		adjust = true;
	});
	
	$('.btn-force').click(function(){
		force = true;
	});
	
	$('#target, #interval, #start').change(function(){
		parseTarget();
	});
	
	$('#jobname').selectize({
		delimiter: ',',
		persist: false,
		create: function(input) {
			return {
				value: input,
				text: input
			}
		}
	});
	
	$('.btn-help-message').click(function(){
		$('#modal-help-message').modal('show');
	});
	
	$('#account').multiselect();
	$('.mydatetime').bootstrapMaterialDatePicker({ format : 'YYYY-MM-DD HH:mm:ss', minDate : new Date() });
	
	function parseTarget(){
		var val = $("#target").val()!==undefined?$("#target").val().trim():$("#target").val();
		if(val=='' || val==undefined) return false;
		var days =[];
		var hours =[];
		var total_hour = $('input[id^="check_hour_"]:checked').length;
		if(total_hour==0 || $('input[id^="check_day_"]:checked').length==0){
			mynotif('Hari dan jam tidak boleh kosong');
			return false;
		}
		
		$('input[id^="check_day_"]:checked').each(function(i,e){
			days.push($(this).val());
		});
		
		$('input[id^="check_hour_"]:checked').each(function(i,e){
			hours.push($(this).val());
		});
		
		val = val.replace(" ", "\n");
		val = val.replace(",","\n");
		var split = val.split("\n");
		var total = 0;
		for(a=0;a<split.length;a++){
			if(split[a].trim() != '') total++;
		}
		var start = $('#start').val();
		var interval = new Number($('#interval').val());
		$('.total-target').text(total);
		
		var total2 = total<1?1:total;
		var today = moment.unix('YYYY-MM-DD'); 
		var today_ts = moment(today).unix(); 
		var start_ts = moment(start).unix(); 
		var jam_mulai = moment.unix(start_ts).format('H');
		var jam_pertama = Number($('input[id^="check_hour_"]:checked').eq(0).val()); 
		var jam_pertama_setelah_mulai = jam_pertama;
		for(a=jam_mulai; a<=Number($('input[id^="check_hour_"]:checked:last').val());a++){
			if($('input[id="check_hour_'+a+'"]:checked').length==1){
				jam_pertama_setelah_mulai = a;
				break;
			}
		}
		
		if($('input[id="check_hour_'+jam_mulai+'"]:checked').length==0){				
			if(jam_pertama_setelah_mulai > jam_mulai ){
				//start_ts = start_ts + (3600 * (jam_pertama-jam_mulai));
				start_ts = moment.unix(start_ts).hour(jam_pertama_setelah_mulai).minute(0).second(0).unix();
			}else{
				start_ts = start_ts + 86400;
				start_ts = moment.unix(start_ts).hour(jam_pertama).minute(0).second(0).unix();
			}
		}
		
		$('.start-time').text(moment.unix(start_ts).format('YYYY-MM-DD HH:mm:ss'));
		
		master_start_ts = start_ts;
		for(a=0; a<total;a++){
			calculatNextRun(interval, 0, days, hours);
		}
		
		$('.estimate-end-time').text(moment.unix(master_start_ts).format('YYYY-MM-DD HH:mm:ss'));
		var speed = 60/interval;
		$('.speed-per-hour').text(speed>total?total:speed);
		speed = speed * total_hour;
		$('.speed-per-day').text(speed>total?total:speed);
		
	}
	
	var master_start_ts;
	function calculatNextRun($interval, $delay, $days, $hours){
		$start_ts = master_start_ts;
		$next = $start_ts + ($interval*60)+ ($delay*60);

		$d = moment.unix($next).format('d');
		$h = moment.unix($next).format('H');
		$ts = 0;
		$tmp = [];
		while(!in_array($d, $days) || !in_array($h, $hours) ){
			if(!in_array($d, $days)){
				$ts = moment.unix($next).hour(0).minute(0).second(0).unix();
				$next = $next + 86400;
				$d = moment.unix($ts).format('d');
				$h = moment.unix($ts).format('H');
			}
			
			if(!in_array($h,$hours)){
				$ts = moment.unix($next).minute(0).second(0).unix();
				$next = $next + 3600 ;
				$d = moment.unix($ts).format('d');
				$h = moment.unix($ts).format('H');
			}
		}
		
		master_start_ts = $ts==0?$next:$ts;
	}
	
	function in_array($string, $array){
		return $array.indexOf($string) != -1?true:false;
	}
	
	$('form.form-manual-activity').submit(function(e){
		e.preventDefault();
		var btn = $(this).find('button[type="submit"]');
		var formdata = $(this).serializeArray();
		if($('.block-parent1 .block-img').length ==0){
			mynotif('Media tidak boleh kosong');
			return false;
		}
		$(".block-post:not(:has(.thumb-img))").remove();
		var images = new Array();
		$.each($('.block-parent1 .block-post'), function(a,b){
			var im = new Array();
			$.each($(this).find('.thumb-img'), function(c,d){
				im[c]=$(this).attr('src');
			});
			images[a]=im;
		});
		//console.log(images);
		formdata.push({name: 'token', value: token});
		formdata.push({name: 'images', value:  JSON.stringify(images)});
		formdata.push({name: 'adjust', value: adjust});
		formdata.push({name: 'force', value: force});
		formdata.push({name: 'start-time', value: $('.start-time').text()});
		$.ajax({
			url : '<?=site_url('instagram/manual_activity/save_multi_post')?>',
			type:'post',
			dataType:'json',
			data: formdata,
			beforeSend: function(){
				btn.prop('disabled', true).removeClass('btn-primary').addClass('btn-warning');
			},
			success: function(d){
				adjust = false;
				force = false;
				if(d.status=='success'){
					mynotif('Success', 'green');
				}else if(d.status=='warning'){
					mynotif('Warning', 'yellow');
					$('#modal-warning .modal-body').html(d.message);
					$('#modal-warning').modal('show');
				}else{
					mynotif(d.message);
				}
				setTimeout(function(){
					btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
				},2000);
			},
			error: function(d){
				adjust = false;
				force = false;
				mynotif("Terjadi kesalahan");
				setTimeout(function(){
					btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning');
				},2000);
			}
		});
	});
	
});
</script>