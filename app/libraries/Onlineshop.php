<?php 
use PHPHtmlParser\Dom;

Class Onlineshop{
	
	private $proxy;
	
	function __construct(){
		
	}
	
	function setProxy($proxy){
		$this->proxy = $proxy;
	}
	
	function getProductInfo($web, $url){
		$webRoute = str_replace('.', '_', $web);
		return $this->{'info_'.$webRoute}($url);
	}
	
	function getProductList($web, $page, $category=null){
		$webRoute = str_replace('.', '_', $web);
		return $this->{'product_'.$webRoute}($page, $category);
	}
	
	function getTokoCategory($web){
		$webRoute = str_replace('.', '_', $web);
		return $this->{'category_'.$webRoute}();
	}
	
	function getMaxPage($web, $category=null){
		$webRoute = str_replace('.', '_', $web);
		return $this->{'maxpage_'.$webRoute}($category);
	}
	
	
	private function category_tasimportir_com(){
		$url = "https://tasimportir.com/";
		$referer = 'https://tasimportir.com';
		$html = $this->curl($url, $referer);
		if(!$html) return false;
		$dom = new Dom;
		$dom->load($html);
		
		$ul = $dom->find('#menu-item-489 ul li a');
		if(count($ul)>0){
			$list = [];
			foreach($ul as $li){
				$list[] = ['nama'=>$li->text, 'url'=>$li->getAttribute('href')];
			}
			return $list;
		}
	}
	
	private function category_market_lancarin_com(){
		$url = "https://market.lancarin.com/";
		$referer = 'https://market.lancarin.com';
		$html = $this->curl($url, $referer);
		if(!$html) return false;
		$dom = new Dom;
		$dom->load($html);

		$ul = $dom->find('li.menu-item-53138  ul li a');
		if(count($ul)>0){
			$list = [];
			$tmp = []; //ngantuk pake ini aja buat ngilangin duplicate nya
			foreach($ul as $li){
				$nama = trim(strip_tags($li->innerHtml));
				if(!in_array($nama, $tmp)) $tmp[]=$nama;
				else continue;
				$list[] = ['nama'=>$nama, 'url'=>$li->getAttribute('href')];
			}
			return $list;
		}
	}
	
	
	private function maxpage_tasimportir_com($category=null){
		if(empty($category)) $category='uncategorized';
		$url = "https://tasimportir.com/product-category/$category/page/1/";
		$referer = 'https://tasimportir.com';
		$html = $this->curl($url, $referer);
		if(!$html) return false;
		$dom = new Dom;
		$dom->load($html);
		
		$maxpage = 1;
		$ul = $dom->find('ul.nav-pagination li a.page-number');
		if(count($ul)>0){
			foreach($ul as $li){
				$p = (int) $li->text;
				if($p>$maxpage) $maxpage=$p;
			}
		}
		
		return $maxpage;
	}
	
	
	private function maxpage_market_lancarin_com($category=null){
		if(empty($category)) $url = "https://market.lancarin.com/shop/";
		else $url = "https://market.lancarin.com/product-category/$category/";
		$referer = 'https://market.lancarin.com';
		$html = $this->curl($url, $referer);
		if(!$html) return false;
		$dom = new Dom;
		$dom->load($html);
		
		$maxpage = 1;
		$ul = $dom->find('.woocommerce-pagination a');
		if(count($ul)>0){
			foreach($ul as $li){
				$p = (int) $li->text;
				if($p>$maxpage) $maxpage=$p;
			}
		}
		
		return $maxpage;
	}
	
	private function info_tasimportir_com($url){
		$referer = 'https://tasimportir.com';
		$html = $this->curl($url, $referer);
		if(!$html) return false;
		$dom = new Dom;
		$dom->load($html);
		$images = $dom->find('.product-images .product-gallery-slider .woocommerce-product-gallery__image a');
		$imgs = [];
		if(count($images)>0){
			foreach($images as $im){
				$imgs[] = $im->getAttribute('href');
			}
		}
		
		$harga = strip_tags($dom->find('.product-page-price .woocommerce-Price-amount')->innerHtml);
		$harga = $this->getPriceInt($harga)/100;
		$data = [
			'harga'=>$harga,
			'images'=>$imgs,
			'description'=>count($dom->find('#tab-description'))>0?$dom->find('#tab-description')->innerHtml:'',
			'nama'=> trim($dom->find('.product-title')->text)
		];
		return $data;
	}
	
	
	private function product_tasimportir_com($page, $category=null){
		if(empty($category)) $category='uncategorized';
		$url = "https://tasimportir.com/product-category/$category/page/$page/";
		$referer = 'https://tasimportir.com';
		$html = $this->curl($url, $referer);
		if(!$html) return false;
		$dom = new Dom;
		$dom->load($html);
		$container = $dom->find('.shop-container .products .product-small');
		$data = [];
		if(count($container)>0){
			foreach($container as $cont){
				$harga = strip_tags($cont->find('.amount ')->innerHtml);
				$harga = $this->getPriceInt($harga)/100;
				$data[] = [
					'url'=>$cont->find('a',0)->getAttribute('href'),
					'harga'=>$harga,
					'nama'=>$cont->find('.product-title a')->text
				];
			}
		}
		
		return $data;
	}
	
	private function product_market_lancarin_com($page, $category=null){
		if(empty($category)) $url = "https://market.lancarin.com/shop/page/$page/";
		else $url = "https://market.lancarin.com/product-category/$category/";
		
		$referer = 'https://market.lancarin.com';
		$html = $this->curl($url, $referer);
		if(!$html) return false;
		$dom = new Dom;
		$dom->load($html);
		$container = $dom->find('article.product figure.woocom-project');
		$data = [];
		if(count($container)>0){
			foreach($container as $cont){
				$harga = count($cont->find('figcaption span .amount '))>0?strip_tags($cont->find('figcaption span .amount ')->innerHtml):0;
				$harga = $this->getPriceInt($harga)/100;
				$data[] = [
					'url'=>$cont->find('a',0)->getAttribute('href'),
					'harga'=>$harga,
					'nama'=>$cont->find('.entry-title a')->text
				];
			}
		}
		
		return $data;
	}
	
	private function getPriceInt($p){
		return preg_replace('/[^0-9]/', '', $p);
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
	
	public function slugify($text)
	{
	  // replace non letter or digits by -
	  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

	  // transliterate
	  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

	  // remove unwanted characters
	  $text = preg_replace('~[^-\w]+~', '', $text);

	  // trim
	  $text = trim($text, '-');

	  // remove duplicate -
	  $text = preg_replace('~-+~', '-', $text);

	  // lowercase
	  $text = strtolower($text);

	  if (empty($text)) {
		return 'n-a';
	  }

	  return $text;
	}
	
	private function tidyHtml($html){
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