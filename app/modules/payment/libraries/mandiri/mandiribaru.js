//initialize
var casper = require('casper').create({   
    verbose: true, 
    //logLevel: 'debug', //comment out for debugging
    viewportSize: {width: 1280, height: 1024}, //set browser window large, to avoid load mobile version
    pageSettings: {
        userAgent: 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.157 Safari/537.36'
    }
});

casper.on( 'page.error', function (msg, trace) {
//    this.echo( 'Error: ' + msg, 'ERROR' );
});
var urlLogin1 = 'https://ibank.bankmandiri.co.id/retail3/'; //url login palsu
var urlLogin2 = 'https://ibank.bankmandiri.co.id/retail3/loginfo/loginRequest'; //url login asli
var urlLogout = 'https://ibank.bankmandiri.co.id/retail3/loginfo/logout'; //url logout

/*
note: 
1. setiap transaksi harus melakukan logout untuk menghindari adanya session yang masih aktif. Karena 1 akun hanya bisa di akses 1 orang saja. Timeout rata2 5 menit pada saat idle. oleh karena itu perlu force logout jika login sukses
2. waktu tunggu atau wait() bisa dikurangi tergantung speed internet dan kemampuan server. delay sudah dipilihkan yang terbaik. jika merasa terlalu lama silahkan di kurangi
*/


//prepare the output
var res = {
	ok : 0,
	msg : '',
	data : []
}

//stop if user or pass was empty
if(!casper.cli.has("user") || !casper.cli.has("pass")){
	res.msg = 'please input user or password';
	casper.echo(JSON.stringify(res));
	casper.exit();
}
var user = casper.cli.get("user");
var pass = casper.cli.get("pass");
var norek = casper.cli.has("norek") ? casper.cli.raw.get("norek") : "";
//jika tanggal awal kosong maka, set sbg hari ini
var tgl1 = casper.cli.has("tgl1") ? casper.cli.get("tgl1") : getTimeNow();
//jika tanggal akhir kosong maka set sama dengan tanggal awal
var tgl2 = casper.cli.has("tgl2") ? casper.cli.get("tgl2") : tgl1;

var urlReq = null;
var retry = 0;

//jika tipe transaksi kosong maka set sebagai both (empty)
var transaksi = casper.cli.has("transaksi") ? casper.cli.get("transaksi") : '';
//jika tipe transaksi bukan debit (D) atau kredit (C) maka set sebagai both (empty)
if(transaksi !=='D' && transaksi !== 'C'){
	transaksi = '';
}

//jika pilih current maka tabel sblm memilih tanggal yg di ambil
var current = casper.cli.has("current") ? casper.cli.get("current") : false;

//for debugging purpose, to capture each step using c()
var stepCapture = 1;

//set logged in as false in the beginning
var loggedIn = false;


casper.start() //start 
.thenOpen(urlLogin2) //open url login iframe
.then(function fillForm(){ //mengisi form
	this.evaluate(function evalFillForm(user,pass){
		document.querySelector('#userid_sebenarnya').value = user; //mengisi user
		document.querySelector('#pwd_sebenarnya').value = pass; //mengisi password
		document.querySelector('#btnSubmit').click(); //click submit
	}, user,pass);
})
.wait(5000) //tunggu 5 detik
.then(checkLoginResult) //cek apakah berhasil login atau tidak 
.then(function(){
	if(loggedIn !== true) { //jika tidak berhasil login maka quit
		this.then(echoResult)
			.then(logout).then(quit);
	}
	else{ //jika berhasil login, lanjut
		
		this
			.then(checkMutasi) //cek tabel mutasi
			.wait(2000)
			.then(openAjax) //get json
			.then(parsingMutasi) //tampilkan json
			.then(echoResult) //tampilkan json
			.then(logout).then(quit); //logout dan quit
			

	}
})


casper.run();

function getTimeNow(){
	var dateObj = new Date();
	var month = dateObj.getMonth() + 1; //months from 1-12
	month = month < 10 ? '0' + month : month;
	var day = dateObj.getDate();
	day = day < 10 ? '0' + day : day;
	var year = dateObj.getFullYear();

	return year + "-" + month + "-" + day;
}

function parsingMutasi(){
	if(res.ok){
		try {
			res.data = JSON.parse(res.data);
		} catch(e) {
			res.msg = 'error parse json output';
		}
	}
}

function echoResult(){
	this.echo(JSON.stringify(res));
}

function checkMutasi(){
	this.then(function getAjax(){
		var tsnow = new Date().getTime();
		var myDate= tgl1.split("-");
		var newDate=myDate[0]+"/"+myDate[1]+"/"+myDate[2];
		var fromDate = new Date(newDate).getTime();
		var myDate= tgl2.split("-");
		var newDate=myDate[0]+"/"+myDate[1]+"/"+myDate[2];
		var toDate = new Date(newDate).getTime(); 
		var searchBy = current ? 'TODAY' : 'PERIOD';
		urlReq = 'https://ibank.bankmandiri.co.id/retail3/secure/pcash/retail/account/portfolio/searchTransaction?accountNo='+ norek +'&searchCasaBy='+ searchBy +'&fromDate='+ fromDate +'&toDate='+ toDate +'&transactionTypeCode=S&transactionType=&keyWord=&_='+tsnow;
	});
}

function openAjax(){
	if(urlReq != null){
		this.thenOpen(urlReq, function(result){
			if(result.status == 200){
				res.data = this.page.plainText;
				res.ok=1;
				res.msg='success';
			}else{
				res.msg = 'cannot get json';
			}
		});
	}else{
		if(retry <1) 
		{
			retry++;
			this.wait(2000, openAjax);
		}
		res.msg = 'url json null';
	}
}

function checkLoginResult(){//cara mengecek apakah berhasil login atau tdk adalah dengan menemukan ada tidak nya tombol/icon logout 
	loggedIn = this.evaluate(function(){
		return document.querySelectorAll('#nav-logout').length > 0 ? true : false;
	});
	if(!loggedIn) res.msg = 'login failed';
}

function logout(){
	this.open(urlLogout);
}

function quit(){
	this.exit;
}
function c(){ //untuk debugging capture screen
	this.capture(stepCapture + '.png');
	stepCapture++;
}
