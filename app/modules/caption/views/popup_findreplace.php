<div id="modal-findreplace" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-book"></i> Format Pattern FindReplace</h4>
      </div>
      <div class="modal-body">
        <h3><u>Template Pattern</u></h3>
        <div>Format yang tersedia:</div>
		<table class="table table-striped table-hover table-bordered">
			<tr><th>Custom Find</th><th>Keterangan</th></tr>
			<tr><td>{money}</td><td>Uang. co: 200.000, 200k, Rp 200.000, 200jt, 200 juta</td></tr>
			<tr><td>{handphone}</td><td>Nomor handphone. co: 081299995555, 0812-999-95555, +6281299995555</td></tr>
			<tr><td>{url}</td><td>Url. co: https://myweb.com/item1.html</td></tr>
			<tr><td>{domain}</td><td>Domain. co: example.com, sub.example.com, sub.example.co.id</td></tr>
			<tr><td>{after_[myword]}</td><td>Mencari konten setelah kata. Contoh: {after_[silahkan hubungi]} pada kata <i>Jika mau order <b>Silahkan hubungi</b> kami melalui : 09999999</i>, maka akan menghasilkan <b>Silahkan hubungi kami melalui : 09999999</b>. Ini berguna untuk menghapus template footer dari postingan asli.</td></tr>
			<tr><td>{before_[myword]}</td><td>Kebalikan dari <b>after</b>. Ini berguna untuk menghapus template header/salam perkenalan dari postingan asli</td></tr>
			<tr><td>{between_[from]_[to]}</td><td>Mencari konten diantara <b>from</b> dan <b>to</b>. Contoh: {between_[makan nasi padang]_[segelas es teh]} pada kalimat <i>enaknya <b>makan nasi padang</b> dan ditemani <b>segelas es teh manis</b> di siang hari</i>, akan menghasilkan <b>makan nasi padang dan ditemani segelas es teh manis</b></td></tr>
			<tr><td>{target_fullname}</td><td>Target fullname</td></tr>
			<tr><td>{target_username}</td><td>Target username</td></tr>
			<tr><th>Custom Replace</th><th>Keterangan</th></tr>
			<tr><td>{my_fullname}</td><td>Fullname akun anda</td></tr>
			<tr><td>{my_username}</td><td>Username akun anda</td></tr>
			<tr><td>{+10%}</td><td>Markup prosentase 10% dari nilai yang ditemukan . Digunakan dengan {money} untuk memarkup harga. Silahkan ganti nilainya dengan kebutuhan anda, misal: {+37,5%}</td></tr>
			<tr><td>{-10%}</td><td>Markdown 10%. Kebalikan dari {+10%}</td></tr>
			<tr><td>{+1000}</td><td>Markup dengan nilai tetap 1000. Sama seperti {+10%} hanya saja menggunakan nilai tetap</td></tr>
			<tr><td>{-1000}</td><td>Kebalikan dari {+1000}</td></tr>
		</table>
		<div><b>Note:</b> Selain itu anda juga bisa menggunakan custom format, spintax ataupun text biasa</div>
		<h4><i>Contoh penggunaan sederhana:</i></h4>
		<table class="table table-striped table-hover table-bordered">
			<tr><th>Text</th><th>Find</th><th>Replace</th><th>Hasil</th></tr>
			<tr><td>Sepatu Adidas</td><td>adidas</td><td>Ad*d*s</td><td>Sepatu Ad*d*s</td></tr>
			<tr><td>Follow akun lemari88</td><td>{target_username}</td><td>{my_username}</td><td>Follow akun meja77</td></tr>
			<tr><td>Call: 08122222222</td><td>{handphone}</td><td>08111111111</td><td>Call: 08111111111</td></tr>
			<tr><td>Makan roti</td><td>roti</td><td>{biskuit|pisang}</td><td>Makan biskuit atau Makan pisang</td></tr>
			<tr><td>Harga 200k</td><td>{money}</td><td>{+20%}</td><td>Harga 240.000</td></tr>
			<tr><td>Harga 200.000</td><td>{money}</td><td>{-50000}</td><td>Harga 150.000</td></tr>
		</table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
