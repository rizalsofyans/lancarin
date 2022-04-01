<style>
	@import url(https://fonts.googleapis.com/css?family=Roboto);

.well {
  padding: 25px;
  background-color: rgba(245, 245, 245, 0.7);
  border-radius: 5px;
}

/*
 * Airlines
 */
#plane {
	font-family: 'Roboto', sans-serif;
  width: 100%;
  position: relative;
}

#plane .content {
  width: 100%;
  float: left;
  font-size: 50px;
  font-weight: 300;
  line-height: 42px;
  margin-top: 30px;
  text-align: center;
}

#plane .content, footer .content{
  font-family: 'Pathway Gothic One';
  font-size: 70px;
  font-weight: 300;
  line-height: 80px;
}

.myplane {
  height: 67px;
  overflow: hidden;
  display: inline-block;
  text-align: left;
}
    .text-khusus {
    text-transform: none !important;
    font-weight: 400 !important;
    letter-spacing: normal !important;
    font-size: 14px;
    color: #333;
}
</style>

<link rel="stylesheet" href="<?=base_url('assets/plugins/slotmachine/jquery.slotmachine.min.css');?>">
<div class="wrap-content">
	<div class="users app-table">
		  	<div class="card">
		  		<div class="card-header">
		  			<div class="card-title">
                        <i class="fa fa-random" aria-hidden="true"></i> Random Comment
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
        Anda dapat menggunakan Random Comment untuk melakukan undian comment seperti Giveaway atau Lottery.
        Sebelum menggunakan fitur ini, Anda harus melakukan scrape comment post terlebih dahulu melalui <a href="https://lancarin.com/instagram/data_collection/form" style="font-weight:bold;">Data Collection</a>.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
                    </div>
                    <div class="clearfix"></div>
		  		</div>
		  		<div class="card-body">
		  			<div class="table-filter">
		  				<div class="row">
		  					<div class="col-md-12 mb15">
		  						<button type="button" class="btn btn-sm btn-show toggle-form">- Hide Form</button>
		  					</div>
		  					<form id="form-random">
		  					
		  					<div class="col-md-3">
								<div class="form-group">
									<label>Account <i class="fa fa-question-circle-o webuiPopover" data-content="Akun yang digunakan untuk melakukan pengecekan follower." data-delay-show="300" data-title="Mention Count"></i></label>
									<select class="form-control " id="account" name="account" >
										<option value="">Pilih Akun</option>
										<?php if($account):foreach($account as $row):?>
										<option value="<?=$row->id;?>"><?=$row->username;?></option>
										<?php endforeach;endif;?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Data <i class="fa fa-question-circle-o webuiPopover" data-content="Hasil dari Data Collection Comments yang akan dipakai" data-delay-show="300" data-title="Mention Count"></i></label>
									<select class="form-control " id="data-id" name="data-id" >
										<?php if(!empty($result)): foreach($result as $row):?>
										<option value="<?=$row->id;?>"><?=$row->id." : ".$row->target;?></option>
										<?php endforeach;else:?>
										<option value="">Pilih Data</option>
										<?php endif;?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Max Data <i class="fa fa-question-circle-o webuiPopover" data-content="Untuk mengambil komentar sejumlah N pertama saja. misal: 1000 data pertama" data-delay-show="300" data-title="Max Data"></i></label>
									<input type="number" min="0" class="form-control" id="max-number" name="max-number" value="0">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Keyword <i class="fa fa-question-circle-o webuiPopover" data-content="Hanya mengambil komentar yang mengandung keyword tertentu." data-delay-show="300" data-title="Keyword"></i></label>
									<input type="text" class="form-control" id="keyword" name="keyword" value="">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Max Time <i class="fa fa-question-circle-o webuiPopover" data-content="Hanya mengambil komentar yang di posting sebelum tanggal yang tertera. Anda tidak perlu merubahnya jika ingin mengambil semua data." data-delay-show="300" data-title="Max Time"></i></label>
									<input type="text" class="form-control mydatetime" id="max-time" name="max-time" value="<?=NOW?>">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Mention Count <i class="fa fa-question-circle-o webuiPopover" data-content="Jumlah mention minimum" data-delay-show="300" data-title="Mention Count"></i></label>
									<input type="number" min="0" class="form-control" id="mention-count" name="mention-count" value="0">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Allow Duplicate User <i class="fa fa-question-circle-o webuiPopover" data-content="Untuk menghindari duplikat user" data-delay-show="300" data-title="Mention Count"></i></label>
									<select class="form-control " id="duplicate-user" name="duplicate-user" >
										<option value="1">Yes</option>
										<option value="0">No</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Allow Duplicate Comment <i class="fa fa-question-circle-o webuiPopover" data-content="Memperbolehkan adanya komentar yang sama atau tidak" data-delay-show="300" data-title="Mention Count"></i></label>
									<select class="form-control " id="duplicate-comment" name="duplicate-comment" >
										<option value="1">Yes</option>
										<option value="0">No</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>My follower <i class="fa fa-question-circle-o webuiPopover" data-content="Untuk menentukan user merupakan follower atau bukan" data-delay-show="300" data-title="Mention Count"></i></label>
									<select class="form-control " id="my-follower" name="my-follower">
										<option value="1">Yes</option>
										<option selected value="0">No</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Exclude user more than 1 hashtag <i class="fa fa-question-circle-o webuiPopover" data-content="Untuk menghindari user yang melakukan spam hashtag" data-delay-show="300" data-title="Mention Count"></i></label>
									<select class="form-control " id="more-hashtag" name="more-hashtag">
										<option value="1">Yes</option>
										<option selected value="0">No</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Blacklist User <i class="fa fa-question-circle-o webuiPopover" data-content="Untuk mengexclude user dengan alasan tertentu." data-delay-show="300" data-title="Mention Count"></i></label>
									<textarea class="form-control " id="blacklist-user" name="blacklist-user"></textarea>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Winner count <i class="fa fa-question-circle-o webuiPopover" data-content="Jumlah pemenang" data-delay-show="300" data-title="Mention Count"></i></label>
									<select class="form-control " id="winner-count" name="winner-count">
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
									</select>
								</div>
							</div>
							</form>
							
		  				</div>
		  				<div id="plane" class="row mt15">
					      <div class="col-md-12">
					        <div class="text-center mb15">
					        	<button id="start" type="button" class="btn btn-primary btn-lg">MULAI</button> 
					        	<button id="reset" type="button" class="btn btn-danger btn-lg hidden">RESET</button>
					        </div>
					      </div>
					      <div class="col-md-12">
					        <div hidden class="well content" id="planeArea">
					          <p>Pemenang ke-<span class="winner-number">1</span></p>
					          <div id="planeTemp" class="text-center">. . .</div>
					          <div id="planeBox" ></div>
					        </div>

					      </div>
					    </div>
					    <div class="row">
					    	<div class="col-md-8 col-md-offset-2 row box-winner">
							</div>
					    </div>
					  </div>
		  			</div>
		  		</div>
		  	</div>
		</div>
	</div>
</div>

<div id="planeMachine-clone" class="text-center hidden myplane">
	<div>. . .</div>
	<div>aaa</div>
	<div>bbb</div>
	<div>ccc</div>
</div>

<div id="clone-pic" class="col-sm-6 col-md-4 " style="display:none;">
    <div class="thumbnail" style="text-align:center;">
    	<div>
    	<h4 class="badge badge-info text-center">Winner <span class="win-number">1</span></h4>
    </div>
      <img class="img-responsive img-thumbnail" src="" alt="myusername">
      <div class="caption">
        <h3 style="word-break: break-word;margin-top:0 !important;font-size:2rem !important;">Jokowi</h3>
        <p class="comment-date badge"></p>
        <p class="comment" style="word-break: break-word;">Cras justo odio, dapibus ac facilisis in, egestas eget quam.</p>
        <p><a href="#" target="_blank" class="btn btn-primary link-profile" role="button">Profile</a></p>
      </div>
    </div>
</div>



<script src="<?=base_url('assets/plugins/slotmachine/slotmachine.min.js');?>"></script>
<script src="<?=base_url('assets/plugins/slotmachine/jquery.slotmachine.min.js');?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script>
	var machine =[];
	$(function(){
		$('#open').click(function(){
			$('#mymodal').modal('show');
		});

		$('.toggle-form').click(function(){
			if($('#form-random').hasClass('hidden')){
				$(this).text('- Hide Form');
				$('#form-random').removeClass('hidden');
			}else{
				$(this).text('+ Show Form');
				$('#form-random').addClass('hidden');
			}
		})
		 
		function mynotif(msg, color='red'){
			iziToast.show({
				icon: 'fa fa-bell-o',
				message: msg,
				color: color,
				position: 'bottomCenter'
			});
		}
		function clonePic(id,data){
			var newElem = $('#clone-pic').clone();
			var myid = 'winner-pic-'+id;
			newElem.attr('id',myid);
			newElem.find('img').attr('src','https://avatars.io/instagram/'+data[1]);
			newElem.find('h3').text(data[1]);
			newElem.find('.win-number').text(id+1);
			newElem.find('.comment').text(data[3]);
			newElem.find('.comment-date').text(data[4]);
			newElem.find('.link-profile').attr('href', 'https://instagram.com/'+data[1]);
			$('.box-winner').append(newElem);
			$('#'+myid).show("slide", {direction: "right" }, "slow");
		}

		
		function clonePlane(id, winner, html, dataWinner){
			$('#planeTemp').hide();
			$('.winner-number').text(id+1);
			var newElem = $('#planeMachine-clone').clone();
			var name = 'machine'+id;
			newElem.html(html);
			newElem.attr('id', name);
			newElem.removeClass('hidden');
			$('#planeBox').append(newElem);
			machine[name] = $('#'+name).slotMachine({
			    active: winner,
			    delay: 500,
				/*randomize : function(){
					return winner;
				}*/
			});
			machine[name].shuffle(9999999);
			setTimeout(function(){
				machine[name].changeSettings({ randomize: function(){return winner;}})
				
				machine[name].stop(function(){console.log(id+1);
					clonePic(id, dataWinner);
					$('#'+name).hide();
				});
			}, 2000);
		}
		
		function createMachine(id){
			return $('#'+id).slotMachine({
			    active: 0,
			    delay: 500,
			});
		}

		$('#reset').click(function(){
			location.reload();
		});

		$('#start').click(function(){
			$('.toggle-form').text('+ Show Form');
			$('#form-random').addClass('hidden');
			var btn = $(this);
			var btnText = btn.text();
			var formdata = $('#form-random').serializeArray();
			if($('#data-id').val()==''){
				mynotif('Mohon pilih data terlebih dahulu');
				return false;
			}
			if($('#account').val()=='' && $('#my-follower').val()==1){
				mynotif('Mohon pilih akun terlebih dahulu jika ingin melakukan pengecekan follower');
				return false;
			}
			formdata.push({name:'token',value:token});
			$.ajax({
				url : '<?=site_url('instagram/data_collection/get_data_comment')?>',
				type:'post',
				dataType:'json',
				data: formdata,
				beforeSend: function(){
					$('#planeMachine').html('<div>...</div>');
					btn.prop('disabled', true).addClass('btn-warning').removeClass('btn-primary').text('Fetching...');
				},
				success: function(d){
					if(d.status=='success'){
						btn.addClass('hidden');
						$('#reset').removeClass('hidden');
						$('#planeMachine ').empty();
						var time = 500;
						$.each(d.winner, function(x,y){
							setTimeout( function(){ 
								clonePlane(x, y, d.html, d.data[y]);
							}, time)
      						time += 4000;
						});
						
						
						
					}else{
						btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning').text(btnText);
						mynotif(d.message);
					}
				},
				error: function(d){
					btn.prop('disabled', false).addClass('btn-primary').removeClass('btn-warning').text(btnText);
					mynotif("Terjadi kesalahan");
				}
			});
		});
		
		$('.mydatetime').bootstrapMaterialDatePicker({ format : 'YYYY-MM-DD HH:mm:ss'});

		
		
	});
</script>