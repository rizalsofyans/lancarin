<div id="modal-help-message" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-book"></i> Format Pesan</h4>
      </div>
      <div class="modal-body">
        <h3><u>Template Pesan</u></h3>
        <div>Format yang tersedia:</div>
		<table class="table table-striped table-hover table-bordered">
			<tr><th>Format</th><th>Keterangan</th></tr>
			<tr><td>{my_fullname}</td><td>Nama lengkap anda di instagram</td></tr>
			<tr><td>{my_username}</td><td>Username anda</td></tr>
			<tr><td>{my_post_count}</td><td>Jumlah postingan anda</td></tr>
			<tr><td>{my_follower_count}</td><td>Jumlah follower anda</td></tr>
			<tr><td>{my_following_count}</td><td>Jumlah following anda</td></tr>
			<tr><td>{target_fullname}</td><td>Nama lengkap target di instagram</td></tr>
			<tr><td>{target_username}</td><td>Username target</td></tr>
			<tr><td>{target_post_count}</td><td>Jumah postingan target anda</td></tr>
			<tr><td>{target_follower_count}</td><td>Jumlah follower target anda</td></tr>
			<tr><td>{target_following_count}</td><td>Jumlah following target anda</td></tr>
		</table>
		<div><b>Note:</b> Ingin menambahkan sendiri custom format anda? silahkan buka menu <a class="text-primary" target="_blank" href="<?=site_url('caption');?>">caption</a> dan pilih add custom format.</div>
		<h3><u>Spintax Pesan</u></h3>
        <div>Selain template pesan, system kami juga support spintax. <b>Apa itu spintax?</b> spintax adalah metoda sederhana untuk mengacak suatu kumpulan kata. Format penulisan spintax diawali dengan buka kurung kurawa <b>{</b>, di pisahkan dengan pipeline <b>|</b> dan diakhiri dengan tutup kurung kurawa <b>}</b>. Kami juga support <i>nested spintax</i> atau spintax di dalam spintax.</div>
		<br><div>Berikut adalah contoh spintax</div>
		<table class="table table-striped table-hover table-bordered">
			<tr><th colspan="2" class="text-center">Tadi {pagi|siang|malam} {pak|bu} lurah {{makan|sarapan} nasi|minum {kopi|susu}}</th></tr>
			<tr><td colspan="2" class="text-center">Kemungkinan Hasilnya</td></tr>
			<tr><td>Tadi pagi pak lurah sarapan nasi</td><td>Tadi malam pak lurah makan nasi</td></tr>
			<tr><td>Tadi siang pak lurah minum kopi</td><td>Tadi pagi bu lurah makan nasi</td></tr>
			<tr><td>Tadi malam bu lurah minum susu</td><td>Tadi siang bu lurah minum kopi</td></tr>
		</table>
		<div>Silahkan coba disini untuk mengetahui apa itu spintax.</div><br>
		<textarea class="form-control" rows="3" id="spintax-textarea">Tadi {pagi|siang|malam} {pak|bu} lurah {{makan|sarapan} nasi|minum {kopi|susu}}</textarea><br>
		<button type="button" class="btn btn-primary" id="spintax-btn">Spin</button><br><br>
		<div><b>Hasil: </b><span id="spintax-result"></span></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
$(function(){
	$(document).on('click', '#spintax-btn', function(){
		var text = $('#spintax-textarea').val();
		var matches, options, random;
		var regEx = new RegExp(/{([^{}]+?)}/);

		while((matches = regEx.exec(text)) !== null) {
			options = matches[1].split("|");
			random = Math.floor(Math.random() * options.length);
			text = text.replace(matches[0], options[random]);
		}

		$("#spintax-result").text(text);
	});
});
</script>