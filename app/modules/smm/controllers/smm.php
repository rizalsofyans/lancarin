<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPHtmlParser\Dom;

class smm extends MX_Controller {

	private $proxy;

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
		$this->load->helper('string');
	}
	
	public function index(){
		if(empty(session('uid'))) redirect();
		$data=[
      		'list'=> $this->getTable()
    	];

		$this->template->build('index', $data);
	}
	
	public function getTable(){
		$url = 'https://internet.com/prices';
		$html = $this->curl($url);
		if(!$html) return;
		$html = $this->tidyHtml($html);
		$dom = new Dom;
		$dom->load($html);
		$table = $dom->find('table tbody tr');
		if(count($table)>0){
			$list = [];
			foreach($table as $tr){
				$text = $tr->innerHtml;
				if(stripos($text, 'instagram') !== false){
					$list[] = $text;
				}
				
			}
			return $list;
		}
	}

	public function curl($url, $referer=null, $ajax=false, $method='GET', array $request=[], array $header = [], $cookie=null){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
		if(!empty($referer)) curl_setopt($curl, CURLOPT_REFERER, $referer);
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36");
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		if($ajax) $header[] = "X-Requested-With: XMLHttpRequest";
		if($method=='POST'){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($request));
		}
		if(!empty($this->proxy)){
			$parse = parse_url($this->proxy);
			$proxyaddress = $parse['host'].':'.$parse['port'];
			curl_setopt($curl, CURLOPT_PROXY, $proxyaddress);
			if(isset($parse['user'])){
				$proxyauth = $parse['user'].':'.$parse['pass'];
				curl_setopt($curl, CURLOPT_PROXYUSERPWD, $proxyauth);
			}
		}
		if(!empty($cookie)){
			curl_setopt( $curl, CURLOPT_COOKIESESSION, true );
			curl_setopt( $curl, CURLOPT_COOKIEJAR, $cookie );
			curl_setopt( $curl, CURLOPT_COOKIEFILE, $cookie );
		}
		
		if(!empty($header)){
			curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		}
		$r = curl_exec($curl);
		curl_close($curl);
		return $r;
	}
	
	
	public function tidyHtml($html){
		$config = array(
		   'indent'         => true,
		   'output-xhtml'   => true
		);

		// Tidy
		$tidy = new tidy;
		$tidy->parseString($html, $config, 'utf8');
		$tidy->cleanRepair();
		
		// Output
		return $tidy;
	}

}