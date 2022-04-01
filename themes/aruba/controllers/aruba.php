<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class aruba extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
		$this->template->set_layout('blank_page');
	}

	public function index(){
		$data = array();
		
		//$this->template->build('index', $data);
		$this->load->view('layouts/landing_page', $data);
	}

	public function fake_user_notification(){
		$result['ok']=0;
		$rand = rand(0,1);
		$gender = $rand==1? 'female':'male';
		$url = 'http://api.namefake.com/indonesian-indonesia/'.$gender;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5000); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 5000);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0');

		$o = curl_exec($ch);
		$curl_errno = curl_errno($ch);
		$curl_error = curl_error($ch);
		
		curl_close($ch);
		$o = json_decode($o, true);
		if(!empty($o)){
			//paket
			$listPaket =['Personal','Standard','Standard' ,'Standard','Business','Business','Business'];
			$paket = $listPaket[rand(0,count($listPaket)-1)];
			
			//kota dan provinsi
			$exp = explode(',', $o['address']);
			$city = isset($exp[1])?trim(preg_replace("/[^A-Za-z\- ]/", '', $exp[1])):$exp[0];
			//$provinsi = isset($exp[2])?trim(preg_replace("/[^A-Za-z\- ]/", '', $exp[2])):'';
			
			//nama
			$exp = explode(' ', $o['name']);
			$name = implode(' ', array_slice($exp, 0, 2));
			
			//gambar
			$img = 'https://via.placeholder.com/100';
			$images = [];
			$avatarFolder = 'assets/img/avatar';
			$imgFolder = 'assets/img/notif_user/'.$gender;
			if(is_dir($imgFolder)){
				$images = glob($imgFolder."/*.{jpg,jpeg,png}", GLOB_BRACE);
			}
			if(empty($images) && is_dir($avatarFolder)){
				$images = glob($avatarFolder."/*.png");
			}
			if(!empty($images)) $img = base_url($images[rand(0, count($images)-1)]);
			
			//waktu
			$maxWaktu = 3500;  //2 hari lalu
			$minWaktu = 10;  //10 detik lalu
			$waktu=$this->ago(time()-rand($minWaktu, $maxWaktu));
			
			
			$result['ok']=1;
			$result['content'] = $name .' membeli <br>'.
						//'<i>'.$provinsi.'</i><br>'.
						'<b>Paket '.$paket.'</b><br>'.
						'<small>'.$waktu.'</small>';
			$result['img']=$img;
		}
		ms($result);
	}
	
	private function ago($time)
	{
	   $periods = array("detik", "menit", "jam", "hari", "minggu", "bulan", "tahun", "abad");
	   $lengths = array("60","60","24","7","4.35","12","10");

	   $now = time();

		   $difference     = $now - $time;
		   $tense         = "ago";

	   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
		   $difference /= $lengths[$j];
	   }

	   $difference = round($difference);
		/* klo inggris
	   if($difference != 1) {
		   $periods[$j].= "s";
	   }
		*/
	   return "$difference $periods[$j] yang lalu";
	}
	
	public function header($show_html = true){
		$header_data = array(
			"show" => $show_html,
			"languages" => $this->model->fetch("*", LANGUAGE_LIST, "status = 1")
		);
		
		$this->load->view('header', $header_data);
	}

	public function footer($show_html = true){
		$footer_data = array("show" => $show_html);
		$this->load->view('footer', $footer_data);
	}

	public function page(){
		$result = $this->model->get("*", CUSTOM_PAGE, "slug = '".segment(2)."'");
		if(empty($result)) redirect(PATH);

		$data = array(
			"result" => $result
		);
		$this->template->build('page', $data);
	}
}