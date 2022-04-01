<?php
//require_once 'vendor/autoload.php';
use PHPHtmlParser\Dom;

Class Marketplace{
	
	private $dom;
	private $proxy=null;
	private $pageFrom=1;
	private $pageTo=1;
	private $limit=10000;
	private $webPlugin =['tokopedia', 'bukalapak', 'shopee'];
	
	private function setLimit($limit=0){
		$limit = intval($limit);
		$this->limit = empty($limit)?10000:$limit;
	}
	
	private function setPage(array $page=[]){
		$this->pageFrom = isset($page[0])? intval($page[0]): 1;
		$this->pageTo = isset($page[1])? intval($page[1]): 1;
		if($this->pageTo<1 || $this->pageTo<$this->pageFrom) $this->pageTo=$this->pageFrom;
	}
	
	public function getNamaTokoFromUsername($web, $username){
		if($web == 'shopee'){
			return "https://shopee.co.id/$username";
		}elseif($web=='bukalapak'){
			return "https://www.bukalapak.com/u/$username";
		}elseif($web=='tokopedia'){
			return "https://www.tokopedia.com/$username";
		}else{
			return false;
		}
	}
	
	public function getUsernameFromUrl($url){
		$exp = explode('/', $url);
		return end($exp);
	}
	
	public function tokoProducts($url, array $page=[], $limit=0){
		$result['ok']=0;
		$data=[];
		
		$this->setPage($page);
		$this->setLimit($limit);
		
		$web = $this->getPluginByUrl($url);
		
		if(in_array($web, $this->webPlugin)){
			$res = $this->{'product_'.$web}($url);
			if(!empty($res)) $data= array_merge($data, $res);
		}
		
		if(!empty($data)) {
			$result['ok'] = 1;
			$result['data']=$data;
		}
		return $result;
	}
	
	public function getPluginByUrl($url){
		$host = parse_url($url, PHP_URL_HOST);
		foreach($this->webPlugin as $web){
			if(strpos($host, $web) !== false)return $web;
		}
	}
	
	public function tokoInfo($url){
		$result['ok']=0;
		$data=[];
		
		$web = $this->getPluginByUrl($url);
		
		if(in_array($web, $this->webPlugin)){
			$res = $this->{'toko_'.$web}($url);
			if(!empty($res)) $data= array_merge($data, $res);
		}
		
		if(!empty($data)) {
			$result['ok'] = 1;
			$result['data']=$data;
		}
		return $result;
	}
	
	
	public function productInfo($url, $sellerInfo=true){
		$result['ok']=0;
		$data=[];
		
		$web = $this->getPluginByUrl($url);
		
		if(in_array($web, $this->webPlugin)){
			$res = $this->{'detail_'.$web}($url, $sellerInfo);
			if(!empty($res)) $data= array_merge($data, $res);
		}
		
		if(!empty($data)) {
			$result['ok'] = 1;
			$result['data']=$data;
		}
		return $result;
	}
	
	
	public function search($keyword, array $webList, array $page=[], $limit=0){
		$result['ok']=0;
		$data=[];
		$keyword = rawurlencode($keyword);
		
		$this->setPage($page);
		$this->setLimit($limit);
		
		foreach($webList as $web){
			if(in_array($web, $this->webPlugin)){
				$res = $this->{'search_'.$web}($keyword);
				if(!empty($res)) $data= array_merge($data, $res);
			}
		}
		if(!empty($data)) {
			$result['ok'] = 1;
			$result['data']=$data;
		}
		return $result;
	}
	
	public function setProxy($proxy){
		$this->proxy = $proxy;
	}
	
	public function getProxy(){
		return $this->proxy;
	}
	
	private function conditionFromTitle($str){
		return (strpos($str, 'bekas') !== false || strpos($str, 'second') !== false) ?3:4;
	}
	
	private function getDetailProductUrlShopee($url){
		$path = parse_url($url, PHP_URL_PATH); 
		preg_match('/-i.(.*)\.(.*)$/', $path, $match);
		if(count($match)==3){
			return ['itemId'=> $match[2] ,'shopId'=> $match[1]];
		}
	}
	
	private function getProductPriceShopee($item){
		if(empty($item['price_max'])){
			return [
				'original'=> substr(!empty($item['discount'])?$item['price_before_discount']:$item['price'], 0,-5),
				'discount'=>$item['raw_discount'],
				'post'=>substr($item['price'],0,-5),
			];
		}else{
			return [
				'original'=> substr(!empty($item['discount'])?$item['price_before_discount']:$item['price'], 0,-5),
				'discount'=>$item['raw_discount'],
				'post'=>substr($item['price'],0,-5),
				'original_max'=> substr(!empty($item['discount'])?$item['price_max_before_discount']:$item['price_max'], 0,5),
				'post_max'=> substr($item['price_max'],0,-5),
			];
		}
	}
	
	private function detail_shopee($url, $sellerInfo=true){
		$result=[];
		$referer=$url;
		$detailInfoId = $this->getDetailProductUrlShopee($url);
		if(empty($detailInfoId)) return $result;
		$detailUrl = 'https://shopee.co.id/api/v2/item/get?itemid='. $detailInfoId['itemId'] .'&shopid='. $detailInfoId['shopId'];
		$html = $this->curl($detailUrl, $referer, true);
		$arr = json_decode($html, true);
		if(!isset($arr['item'])) return $result;
		$item = $arr['item'];
		
		if($sellerInfo){
			$sellerUrl = 'https://shopee.co.id/api/v2/shop/get?is_brief=1&shopid='. $detailInfoId['shopId'];
			$html = $this->curl($sellerUrl, $referer, true);
			$arr = json_decode($html, true);
			if(!isset($arr['data'])) return $result;
			$sellerData = $arr['data'];
			$seller = [
				'name'=> $sellerData['account']['username'],
				'url'=> 'https://shopee.co.id/'. $sellerData['account']['username'],
				'location'=> $sellerData['place'],
			];
		}else{			
			$seller = [
				'name'=> null,
				'url'=> null,
				'location'=> null
			];
		}
		
		$price = $this->getProductPriceShopee($item);
		$images = [];
		foreach($item['images'] as $im){
			$images[] = 'https://cf.shopee.co.id/file/'.$im;
		}
		$result[0] = [
			//'web'=> 'shopee',
			//'seller'=> $seller,
			//'product'=>[
				'title'=>$item['name'],
				'url'=> $url,
				'description'=> $item['description'],
				'price'=> $price,
				'image'=> 'https://cf.shopee.co.id/file/'.$item['image'].'_tn',
				'images'=> $images,
				'more_page'=>false
				/*'condition'=> null,
				'total_sold'=> $item['models'][0]['sold'],
				'total_seen'=> $item['view_count'],
				'review'=>[
					'rating'=> round($item['item_rating']['rating_star'],2),
					'count'=> array_sum($item['item_rating']['rating_count']),
				],
				'stock'=> $item['stock']*/
			//]
		];
		
		return $result;
	}
	
	private function search_shopee($keyword){
		$limit = 0;
		$result=[];
		for($page=$this->pageFrom; $page<=$this->pageTo;$page++){
			$sLimit = 50; //default shopee per page
			$sNewest = $sLimit*($page-1);
			$url = 'https://shopee.co.id/api/v2/search_items/?by=relevancy&keyword='.$keyword.'&limit='.$sLimit.'&newest='.$sNewest.'&order=desc&page_type=search'; 
			$referer='https://shopee.co.id/';
			$html = $this->curl($url, $referer);
			if($html !== false) {
				$arr = json_decode($html, true);
				if(isset($arr['items'])) {
					foreach($arr['items'] as $productKey=>$productVal){
						$result[] = [
							'web'=>'shopee',
							'url'=> 'https://shopee.co.id/'. $this->slugify($productVal['name']). '-i.' .$productVal['shopid']. '.' .$productVal['itemid'], 
							'image'=> 'https://cf.shopee.co.id/file/'.$productVal['image'].'_tn',
							'price'=> [
								'original'=> !empty($productVal['show_discount'])?$productVal['price_before_discount']/100000:$productVal['price']/100000,
								'discount'=>$productVal['show_discount'],
								'post'=>$productVal['price']/100000,
							],
							'seller'=> [
								'name'=>null,
								'url'=>null,
								'location'=>null,
							],
							'title'=>  $productVal['name'],
							'condition'=>  $this->conditionFromTitle($productVal['name']),
							'sold_count'=>  $productVal['sold'],
							'view_count'=>  $productVal['view_count'],
							'review'=>  [
								'rating'=> $productVal['item_rating']['rating_star'],
								'count'=> array_sum($productVal['item_rating']['rating_count'])
							],
							'stock'=>  $productVal['stock'],
						];
						$limit++;
						if($limit>= $this->limit) break;
					}
				}				
			}
		}
		return $result;
	}
	
	private function curlShopee($url, $referer=null, $request){
		$cookie= sys_get_temp_dir().DIRECTORY_SEPARATOR . 'cookie-shopee.txt';
		if(empty($referer)) $referer='https://shopee.co.id/';
		$header = [
			'x-api-source: pc',
			'x-csrftoken: A8vCnrhV6a276AFRz9g1bRKurX5oC8ET',
			'x-requested-with: XMLHttpRequest',
			'origin: https://shopee.co.id',
			'accept: application/json',
			'accept-language: en-US,en;q=0.9',
			'content-type: application/json',
			'cookie: _gcl_au=1.1.79878949.1539372619; SPC_IA=-1; SPC_F=FyjBa7B6JNxbDEG2fHA8SlGws1izzfCQ; REC_T_ID=59c6ac84-ce55-11e8-8293-3c15fb7ea0ed; _gcl_aw=GCL.1539372621.CjwKCAjwjIHeBRAnEiwAhYT2h8DzlTf4VUj5bp-dhOdxqW8-eSzqK4TOQKBYo2jO9YcVdVG0gzVishoCBZwQAvD_BwE; _ga=GA1.3.724708085.1539372741; SPC_EC="dZB42xYMdU1M50v/Keh+n+ZRpDDQ0iEvY91QdWLraWcwyoiB2NwIYolGpT7Q+8LPPQf1IgJLsJxKdrsGrh4l8ykr2TM4PPP0a+f5uaMYhWXTFlqM9TT1IYX6G+6eUaZkRguayCM/216HvYiB+NhJyQ=="; SPC_T_ID="x9WrnlzfFfZn28sGG3m1rpUT8tMNtarO19CXg3kZEBK+Z+Icpzr6lNXSisATq8zNBAaNyR3nef+PGjuJQQyH7+DOfq4DDbyIanV2PBxdB4c="; SPC_U=96617297; SPC_T_IV="cDjOaDkOm7MW5JG42zE+uw=="; SPC_SC_TK=037bf305fd386e2e7f36b8c01b4b179a; SPC_SC_UD=96617297; SPC_SI=fzl1qbh42gixckdkihr17r65p0t63h5i; _gid=GA1.3.1957026899.1539964454; csrftoken=A8vCnrhV6a276AFRz9g1bRKurX5oC8ET; bannerShown=true; AMP_TOKEN=%24NOT_FOUND; _dc_gtm_UA-61904553-8=1'
		];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_REFERER, $referer);
		$agent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36";
		curl_setopt($ch, CURLOPT_HTTPHEADER,  $header);
		curl_setopt($ch, CURLOPT_HEADER,  0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);         
		curl_setopt($ch, CURLOPT_USERAGENT, $agent); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
		//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); 
		//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie); 

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request); 

		$r = curl_exec($ch);
		curl_close($ch);
		return $r;
	}
	
	private function getShopIdByUsernameShopee($username, $urlToko){
		$html = $this->curlShopee('https://shopee.co.id/api/v1/shop_ids_by_username/', $urlToko, '{"usernames":["'. $username .'"]}');
		if(!$html) return false;
		$arr = json_decode($html, true);
		return $arr[0][$username];
	}
	
	private function toko_shopee($urlToko){
		$result=[];
		$exp = explode('/', $urlToko);
		$username = end($exp);
		$shopId = $this->getShopIdByUsernameShopee($username, $urlToko);
		$shopInfo = $this->curlShopee('https://shopee.co.id/api/v1/shops/', $urlToko, '{"shop_ids":['. $shopId .']}');
		if(!$shopInfo) return $result;
		$arr = json_decode($shopInfo, true);
		$speed = $arr[0]['preparation_time']/86400;
		$badge = round($arr[0]['total_avg_star'], 2) .' star'; //g ada , d ganti rating
		$result = [
			'web'=>'shopee',
			'name'=>$username,
			'reputasi'=> [
				'badge'=>$badge, 
				'value'=> $arr[0]['rating_bad'] + $arr[0]['rating_good'] + $arr[0]['rating_normal']
			],
			'location'=> $arr[0]['place'],
			'total_product'=> $arr[0]['item_count'],
			'speed'=> floor($speed) .'-'. ceil($speed).' hari',
			'total_sold'=> null
		];
		
		return $result;
	}
	
	function shopeSHIT($url, $referer) {
		$header = array(
'accept: */*',
'accept-encoding: gzip, deflate, br',
'accept-language: en-US,en;q=0.9',
'cookie: _gcl_au=1.1.79878949.1539372619; SPC_IA=-1; SPC_F=FyjBa7B6JNxbDEG2fHA8SlGws1izzfCQ; REC_T_ID=59c6ac84-ce55-11e8-8293-3c15fb7ea0ed; _ga=GA1.3.724708085.1539372741; csrftoken=A8vCnrhV6a276AFRz9g1bRKurX5oC8ET; cto_lwid=1c21fe0c-cb15-46cd-91bd-5105249402d4; _gcl_aw=GCL.1541072249.CjwKCAjwyOreBRAYEiwAR2mSkgRg4JGk4SL83AMXs8saiY_k5loJKCVF333Q-tqLUaiU1oaHIkgD4RoCJzAQAvD_BwE; _gac_UA-61904553-8=1.1541072366.CjwKCAjwyOreBRAYEiwAR2mSkmlCNuTzFYpWmf2A6D8X73xW3IORzqIxfEYznrPrbqIxuvbakRqRsxoC63UQAvD_BwE; SPC_EC=-; SPC_T_ID="llRfP/U7jQVgwp2GWWZzvFZzKsd1Xtbf5UJXJDmHqKY7OpffZTKmnYltpUkKOFoR+2nlkDVAcgkrKnh6VeIG21JdHmpGimTaUQTlwpA4UPg="; SPC_U=-; SPC_T_IV="6db+bqT0yoiyS2CK/83eew=="; SPC_SI=8tko88eedhyuc3gv2fbfoka350pmhx3a; _gid=GA1.3.1993100549.1544092592; _fbp=fb.2.1544102733258.660951017',
'if-none-match: "5da73c091fd849ec6b6363532f2b6b46;gzip"',
'if-none-match-: 55b03-d1fae6077d1013dcae837d66909245f5',
'referer: https://shopee.co.id/fygalery',
'user-agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36',
'x-api-source: pc',
'x-requested-with: XMLHttpRequest');
    $ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_REFERER, $referer);
		$agent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36";
		curl_setopt($ch, CURLOPT_HTTPHEADER,  $header);
		curl_setopt($ch, CURLOPT_HEADER,  0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);         
		curl_setopt($ch, CURLOPT_USERAGENT, $agent); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
		
    curl_setopt($ch, CURLOPT_ENCODING, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST , "GET");

    $data = curl_exec($ch);

    $info = curl_getinfo($ch);


    if(curl_errno($ch)) {
        throw new Exception('Curl error: ' . curl_error($ch));
    }

    curl_close($ch);

    if ($data === FALSE) {
        throw new Exception("curl_exec returned FALSE. Info follows:\n" . print_r($info, TRUE));
    }

    return $data;
}
	
	private function product_shopee($urlToko){
		$limit = 0;
		$result=[];
		$exp = explode('/', $urlToko);
		$username = end($exp);
		$shopId = $this->getShopIdByUsernameShopee($username, $urlToko);
		/*$referer= 'https://shopee.co.id/shop/'.$shopId.'/search';
		$shopInfo = $this->curlShopee('https://shopee.co.id/api/v1/shops/', $referer, '{"shop_ids":['. $shopId .']}');
		$arrShopInfo = json_decode($shopInfo, true);*/
		for($page=$this->pageFrom; $page<=$this->pageTo;$page++){
			$offset = ($page-1)*30;
			$url = 'https://shopee.co.id/api/v2/search_items/?by=ctime&limit=30&match_id='.$shopId.'&newest='.$offset.'&order=desc&page_type=shop';
			//$html = $this->curl($url, 'https://shopee.co.id/'.$username);
			$html = $this->shopeSHIT($url, 'https://shopee.co.id/'.$username);
			if(!$html) continue;
			$arr =json_decode($html, true);
			
			if(isset($arr['items']) && count($arr['items'])>0){
				$more=false;
				if(isset($arr['total_count'])){
					$totalItem = (int) $arr['total_count'];
					if($totalItem > $page*30 ) $more=true;
				}
				foreach($arr['items'] as $li){
					$images = [];
					foreach($li['images'] as $im){
						$images[] = 'https://cf.shopee.co.id/file/'.$im;
					}
					
					$isDiscount = empty($li['show_discount'])?false:true;
					$result[] = [
						//'web'=>'shopee',
						'url'=> 'https://shopee.co.id/'.$this->slugify($li['name']).'-i.'.$shopId.'.'.$li['itemid'], 
						'image'=> 'https://cf.shopee.co.id/file/'.$li['image'].'_tn',
						'images'=> $images,
						'price'=> [
							'original'=> substr($isDiscount?$li['price_before_discount']:$li['price'],0,-5),
							'discount'=>$li['show_discount'],
							'post'=>substr($li['price'],0,-5),
						],
						/*'seller'=> [
							'name'=>$arrShopInfo[0]['username'],
							'url'=>$urlToko,
							'location'=> $arrShopInfo[0]['place']
						],*/
						'title'=>  $li['name'],
						/*'condition'=>  null,
						'sold_count'=>  $li['sold'],
						'view_count'=>  $li['view_count'],
						'review'=>  [
							'rating'=> isset($li['item_rating']['rating_star'])?round($li['item_rating']['rating_star'],2):null,
							'count'=> isset($li['item_rating']['rating_count'])?array_sum($li['item_rating']['rating_count']):0
						],
						'stock'=>  $li['stock'],*/
						'more_page'=>$more
					];
					$limit++;
					if($limit>= $this->limit) break;
				}
			}
			
		}
		return $result;
	}
	
	private function getFeedbackBukalapak($text){
		preg_match('/([\d]+ feedback)/i', $text, $output);
		if(isset($output[1])){
			return $this->getPriceInt($output[1]);
		}
	}
	
	private function toko_bukalapak($urlToko){
		$result=[];
		$url = $urlToko.'/feedback?feedback_as=as_seller&filter_by=all';
		$referer='https://www.bukalapak.com/';
		$html = $this->curl($url, $referer);
		if(!$html) return $result;
		$html = $this->tidyHtml($html);
		$dom = new Dom;
		$dom->load($html);
		preg_match('/\((.*)\)/', $dom->find('.o-list__item a',0)->text, $match);
		$total_product= isset($match[1])?intval($match[1]):null;
		$badge ="";
		$badgeEl = $dom->find('.c-user-badges-exhibit__badge .o-flag__body span'); 
		if(count($badgeEl)>0){
			foreach($badgeEl as $row){
				$badge = trim($row->text);
				break;//nggak jadi, 1 aja
			}
		}
		
		$feedback = count($dom->find('.c-user-identification__feedback'))>0? $this->getFeedbackBukalapak($dom->find('.c-user-identification__feedback')->text) : null;
		
		$result = [
			'web'=>'bukalapak',
			'name'=>$dom->find('.qa-seller-name')->text,
			'reputasi'=> [
				'badge'=>$badge,
				'value'=> $feedback
			],
			'location'=> trim($dom->find('.qa-seller-location a')->text),
			'total_product'=> $total_product,
			'speed'=> trim(strip_tags($dom->find('.qa-seller-delivery-duration-value div span')->innerHtml)),
			'total_sold'=> null
		];
		
		return $result;
	}
	
	private function getUsernameByTokoUrlBukalapak($url){
		$path = parse_url($url, PHP_URL_PATH);
		$exp = explode('/', $path);
		return $exp[2];
	}
	
	private function product_bukalapak($url){
		$limit = 0;
		$result=[];
		$username = $this->getUsernameByTokoUrlBukalapak($url);
		for($page=$this->pageFrom; $page<=$this->pageTo;$page++){
			$sPage=($page>1)?'&page='.$page:'';
			$url = 'https://www.bukalapak.com/u/'. $username. '/products?dtm_campaign=default&dtm_section=sidebar&dtm_source=product_detail'.$sPage;
			$referer='https://www.bukalapak.com/';
			$html = $this->curl($url, $referer);
			if(!$html) continue;
			$dom = new Dom; 
			$html = $this->tidyHtml($html); 
			$dom->load($html);
			//$pagination = $dom->find('ul.c-pagination li.c-pagination__item a');
			//$more=(count($pagination)>$page+2)?true:false;
			$pagination = $dom->find('.pagination a.next_page');
			$more=(count($pagination)==1)?true:false;
			$ul = $dom->find("ul.products li div.product-card");
			if(count($ul)>0){
				foreach($ul as $li){
					$isDiscount = count($li->find('span.product-discount-percentage-amount'))==0?false:true;
					//$isReviewAvailable = count($li->find('.product__rating .rating'))==0?false:true;
					$result[] = [
						//'web'=>'bukalapak',
						'url'=> 'https://www.bukalapak.com'. $li->find('a.product-media__link')->getAttribute('href'), 
						'image'=> $li->find('img.product-media__img')->getAttribute('data-src'),
						'images'=> [],
						'title'=>  $li->find('h3 a.product__name')->text,
						'price'=> [
							'original'=> $isDiscount?$this->getPriceInt($li->find('.product-price__original .amount')->text):$li->find('div.product-price')->getAttribute('data-reduced-price'),
							'discount'=>$isDiscount?$li->find('span.product-discount-percentage-amount')->text:0,
							'post'=>$isDiscount?$this->getPriceInt($li->find('.product-price__reduced .amount')->text):$li->find('div.product-price')->getAttribute('data-reduced-price'),
						],
						'more_page'=>$more
						/*'seller'=> [
							'name'=>$li->find('h5.user__name a')->text,
							'url'=>'https://www.bukalapak.com'.$li->find('h5.user__name a')->getAttribute('href'),
							'location'=> $li->find('.user-city__txt')->text
						],
						'condition'=>  $li->find('.product__condition')->text=='Baru'?1:2,
						'sold_count'=>  null,
						'view_count'=>  null,
						'review'=>  [
							'rating'=> $isReviewAvailable?$li->find('.rating')->getAttribute('title'):null,
							'count'=> $isReviewAvailable?$li->find('.review__aggregate span')->text:0
						],
						'stock'=>  null,*/
					];
					$limit++;
					if($limit>= $this->limit) break;
				}
			}
			
		}
		return $result;
	}
	
	private function getProductSeenBukalapak($itemId, $productUrl){
		$html = $this->curl('https://www.bukalapak.com/api/v2/products/'.$itemId.'/seen', $productUrl);
		$arr = json_decode($html, true);
		return $arr['seen'];
	}
	
	private function detail_bukalapak($url){
		$result=[];
		$referer='https://www.bukalapak.com/';
		$html = $this->curl($url, $referer);
		if(!$html) return $result;
		$dom = new Dom;
		$html = $this->tidyHtml($html);
		$dom->load($html);
		$isDiscount = count($dom->find('.c-badge__content'))==0?false:true;
		//$isReviewAvailable= count($dom->find('.qa-product-review-count span'))==0?false:true;
		//$itemId = $dom->find('input[name="item[product_id]]')->getAttribute('value');
		//$totalSeen = trim($dom->find('.qa-pd-seen-value')->text()) >0? trim($dom->find('.qa-pd-seen-value')->text()):$this->getProductSeenBukalapak($itemId, $url);
		$images=[];
		$imageDom =$dom->find('div.js-product-image-gallery__main a.js-product-image-gallery__image');
		if(count($imageDom)>0){
			foreach($imageDom as $im){
				$images[] = $im->getAttribute('href');
			}
		}
		$result[0] = [
			/*'web'=> 'bukalapak',
			'seller'=> [
				'name'=> $dom->find('.qa-seller-name')->text,
				'url'=> 'https://www.bukalapak.com'. $dom->find('.qa-seller-name')->getAttribute('href'),
				'location'=> $dom->find('.qa-seller-location')->text,
			],*/
			//'product'=>[
				'title'=> trim($dom->find('.qa-pd-name')->text),
				'url'=> $url,
				'image'=> $images[0],
				'images'=> $images,
				'more_page'=> false,
				'description'=> $dom->find('.qa-pd-description')->innerHtml,
				'price'=> [
					'original'=> $isDiscount?$this->getPriceInt($dom->find('.c-product-detail-price__original .amount')->text):$this->getPriceInt($dom->find('.c-product-detail-price .amount')->text),
					'discount'=>$isDiscount?$dom->find('.c-badge__content')->text:0,
					'post'=>$isDiscount?$this->getPriceInt($dom->find('.c-product-detail-price__reduced .amount')->text):$this->getPriceInt($dom->find('.c-product-detail-price .amount')->text),
				],
				/*'condition'=> stripos($dom->find('.qa-pd-condition-value span')->text(), 'Bekas' )!==false?2:1,
				'total_sold'=> trim($dom->find('.qa-pd-sold-value')->text()),
				'total_seen'=> $totalSeen,
				'review'=>[
					'rating'=> $isReviewAvailable?round($dom->find('.qa-product-review-count span')->text*5/100,2):null,
					'count'=> $isReviewAvailable?$dom->find('.c-product-rating__visual')->getAttribute('title'):null,
				],
				'stock'=> $dom->find('.js-qty-field__input')->getAttribute('data-max-value')
				*/
			//]
		];
		
		return $result;
	}
	
	private function search_bukalapak($keyword){
		$limit = 0;
		$result=[];
		for($page=$this->pageFrom; $page<=$this->pageTo;$page++){
			$sPage=($page>1)?'&page='.$page:'';
			$url = 'https://www.bukalapak.com/products?utf8=%E2%9C%93&source=navbar&from=omnisearch&search_source=omnisearch_organic&search%5Bhashtag%5D=&search%5Bkeywords%5D='.$keyword.$sPage;
			$referer='https://www.bukalapak.com/';
			$html = $this->curl($url, $referer);
			if(!$html) continue;
			$dom = new Dom;
			$html = $this->tidyHtml($html);
			$dom->load($html);
			$ul = $dom->find("li.product--sem");
			if(count($ul)>0){
				foreach($ul as $li){
					$isDiscount = count($li->find('span.product-discount-percentage-amount'))==0?false:true;
					$isReviewAvailable = count($li->find('.product__rating .rating'))==0?false:true;
					$result[] = [
						'web'=>'bukalapak',
						'url'=> 'https://www.bukalapak.com'.$li->find('a')->getAttribute('href'), 
						'image'=> $li->find('picture.product-picture source')->getAttribute('data-src'),
						'price'=> [
							'original'=> $isDiscount?$this->getPriceInt($li->find('.product-price__original .amount')->text):$li->find('div.product-price')->getAttribute('data-reduced-price'),
							'discount'=>$isDiscount?$li->find('span.product-discount-percentage-amount')->text:0,
							'post'=>$isDiscount?$this->getPriceInt($li->find('.product-price__reduced .amount')->text):$li->find('div.product-price')->getAttribute('data-reduced-price'),
						],
						'seller'=> [
							'name'=>$li->find('h5.user__name a')->text,
							'url'=>'https://www.bukalapak.com'.$li->find('h5.user__name a')->getAttribute('href'),
							'location'=> $li->find('.user-city__txt')->text
						],
						'title'=>  $li->find('h3 a.product__name')->text,
						'condition'=>  $li->find('.product__condition')->text=='Baru'?1:2,
						'sold_count'=>  null,
						'view_count'=>  null,
						'review'=>  [
							'rating'=> $isReviewAvailable?$li->find('.rating')->getAttribute('title'):null,
							'count'=> $isReviewAvailable?$li->find('.review__aggregate span')->text:0
						],
						'stock'=>  null,
					];
					$limit++;
					if($limit>= $this->limit) break;
				}
			}
			
		}
		return $result;
	}
	
	private function search_tokopedia($keyword, array $page=[], $limit=0){
		$limit = 0;
		$result=[];
		for($page=$this->pageFrom; $page<=$this->pageTo;$page++){
			$sPage=($page>1)?'&page='.$page:'';
			$url = 'https://www.tokopedia.com/search?st=product&q='.$keyword.$sPage;
			$referer= 'https://www.tokopedia.com/';
			$html = $this->curl($url, $referer);
			if(!$html) continue;
			preg_match('/window.__data =(.*);/', $html, $match);
			if(!isset($match[1])) continue;
			$arr = json_decode($match[1], true);
			if(!isset($arr['search']['filter']['data']['selected']['ob'])) continue;
			$ob = $arr['search']['filter']['data']['selected']['ob'];
			$url = 'https://ace.tokopedia.com/search/product/v3?scheme=https&device=desktop&related=true&_catalog_rows=5&catalog_rows=5&_rows=60&source=search&ob='.$ob.'&st=product&rows=60&q='.$keyword.'&unique_id='.md5($this->generateRandomString());
			$referer = 'https://www.tokopedia.com/search?st=product&q='.$keyword.$sPage;
			$html = $this->curl($url, $referer, true);
			if(!$html) continue;
			$arr = json_decode($html, true);
			
			$result = [];
			if(isset($arr['data']['products'])){
				foreach($arr['data']['products'] as $productKey=>$productVal){
					$result[] = [
						'web'=>'tokopedia',
						'url'=> $productVal['url'], 
						'image'=> $productVal['image_url'],
						'price'=> [
							'original'=> !empty($productVal['original_price'])?$this->getPriceInt($productVal['original_price']):$productVal['price_int'],
							'discount'=>$productVal['discount_percentage'],
							'post'=>$productVal['price_int'],
						],
						'seller'=> [
							'name'=>$productVal['shop']['name'],
							'url'=>$productVal['shop']['url'],
							'location'=>$productVal['shop']['location'],
						],
						'title'=>  $productVal['name'],
						'condition'=>  $productVal['condition'],
						'sold_count'=>  null,
						'view_count'=>  null,
						'review'=>  [
							'rating'=> $productVal['rating'],
							'count'=> $productVal['count_review']
						],
						'stock'=>  $productVal['stock'],
					];
					$limit++;
					if($limit>= $this->limit) break;
				}
			}
			
		}
		return $result;
	}
	
	private function getBadgeTokopedia($i){
		$i = intval($i);
		if($i < 5) return null;
		elseif($i <= 10) return 'BRONZE 1';
		elseif($i <= 35) return 'BRONZE 2';
		elseif($i <= 50) return 'BRONZE 3';
		elseif($i <= 100) return 'BRONZE 4';
		elseif($i <= 250) return 'BRONZE 5';
		elseif($i <= 500) return 'SILVER 1';
		elseif($i <= 1000) return 'SILVER 2';
		elseif($i <= 1500) return 'SILVER 3';
		elseif($i <= 3000) return 'SILVER 4';
		elseif($i <= 4500) return 'SILVER 5';
		elseif($i <= 10000) return 'GOLD 1';
		elseif($i <= 15000) return 'GOLD 2';
		elseif($i <= 30000) return 'GOLD 3';
		elseif($i <= 45000) return 'GOLD 4';
		elseif($i <= 50000) return 'GOLD 5';
		elseif($i <= 100000) return 'DIAMOND 1';
		elseif($i <= 150000) return 'DIAMOND 2';
		elseif($i <= 200000) return 'DIAMOND 3';
		elseif($i <= 500000) return 'DIAMOND 4';
		elseif($i > 500000) return 'DIAMOND 5';
	}
	
	private function getSpeedTokoPedia($shopId, $urlToko) {
		$url = 'https://slicer.tokopedia.com/shop-speed/cube/shop_speed_daily/aggregate?cut=shop_id:'.$shopId.'|finish_date:'.date('Ymd', strtotime("-1 year")).'-';
		$html = $this->curl($url, $urlToko);
		if(!$html) return;
		$arr= json_decode($html, true);
		$sum = $arr['summary']['sum_speed'];
		$count = $arr['summary']['order_count'];
		if ($count > 0) {
			$speed = $sum / $count;
			$speed = ($speed > 5) ? 5 : $speed;
			if($speed < 1) {
				return ;
			} elseif($speed <= 1.5) {
				return 'Sangat Cepat';
			} elseif($speed <= 2.5) {
				return 'Cepat';
			} elseif($speed <= 3.5) {
				return 'Sedang';
			} elseif($speed <= 4.5) {
				return 'Lambat';
			} elseif($speed <= 5) {
				return 'Sangat Lambat';
			} else {
				return;
			}
		}
    }
	
	private function getTokoReputationTokopedia($shopId, $urlToko){
		$url = 'https://www.tokopedia.com/reputationapp/reputation/api/v1/shop/'.$shopId;
		$html = $this->curl($url, $urlToko);
		if(!$html) return;
		$arr= json_decode($html, true);
		if(!empty($arr)){
			return $this->getPriceInt($arr['data']['shop_score']);
		}
	}
	
	function test(){
		$html = getPageTokopedia('https://www.tokopedia.com/senangtrusttshop');
		echo $html;
	}
	
	function getPageTokopedia($url, $referer=null){
		if(empty($referer)) $referer= 'https://www.tokopedia.com/';
		
		$header = [
			'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
			'accept-language: en-US,en;q=0.9',
			'content-type: application/json',
			'cookie: lang=id; _gcl_au=1.1.1211124143.1539272583; __auc=c4e8412f16663cba7f5e2c21e54; cto_lwid=045dae41-49c6-41c2-8874-2eb4827b364a; ins-mig-done=1; spUID=153927259964222c962230a.db04369d; _ga=GA1.2.1742390914.1539272600; fe_discovery_experiments=%7B%220%22%3A%7B%22exp_middle_row_ads%22%3A%7B%22name%22%3A%22exp_middle_row_ads%22%2C%22selected%22%3A%22topads-current%22%7D%7D%2C%22undefined%22%3A%7B%22exp_middle_row_ads%22%3A%7B%22name%22%3A%22exp_middle_row_ads%22%2C%22selected%22%3A%22topads-current%22%7D%7D%7D; __atuvc=2%7C42; _gcl_aw=GCL.1541073812.CjwKCAjwyOreBRAYEiwAR2mSkvHwyRr0k92WB441hJJhGGvBrLFFVgf9lPHJM28QZpSvXeLcPlTDmRoCqPcQAvD_BwE; _gac_UA-9801603-1=1.1541073818.CjwKCAjwyOreBRAYEiwAR2mSkvHwyRr0k92WB441hJJhGGvBrLFFVgf9lPHJM28QZpSvXeLcPlTDmRoCqPcQAvD_BwE; ISID=%7B%22www.tokopedia.com%22%3A%22d3d3LnRva29wZWRpYS5jb20%3D.8d2537519f9fd4a1a8c8c6aaed468fde.1539363396460.1541077471098.1542032201990.19%22%7D; zarget_visitor_info=%7B%7D; _SID_Tokopedia_=SKXpH-WWhJ_GhRZE4Dc9rng7iZso0peeG0eXLf9DR0Mc9k63wZpDbeav5BQdFTRGk47lDqH3fQjOK9P9GauiDoL9YwPNeQJhA6DAbolol6F0E4L9kHm708slgOo93l_Q; state=eyJyZWYiOiJodHRwczovL3d3dy50b2tvcGVkaWEuY29tL3NlbmFuZ3RydXN0dHNob3AiLCJ1dWlkIjoiMDQwZWNkYjUtMmRlMi00NWZjLWFhZWQtMGMzYjhmMTNmNThkIiwidGhlbWUiOiJpZnJhbWUiLCJwIjoiaHR0cHM6Ly93d3cudG9rb3BlZGlhLmNvbS9zZW5hbmd0cnVzdHRzaG9wIn0; _ID_autocomplete_=8fcd0f32740e41049bc99a8b7590d69a; _BID_TOKOPEDIA_=e21d91adde56f1813a15fa0ed7777436; _gid=GA1.2.1592331165.1543624565; __asc=e8ba493b1676731a26d849cc23c; _fbp=fb.1.1543624582476.752051437; scs=%7B%22t%22%3A1%7D; _dc_gtm_UA-9801603-1=1; _gat_UA-9801603-1=1; insdrSV=163',
			'pragma: no-cache',
			'upgrade-insecure-requests: 1',
			'cache-control: no-cache'
		];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_REFERER, $referer);
		$agent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36";
		curl_setopt($ch, CURLOPT_HTTPHEADER,  $header);
		curl_setopt($ch, CURLOPT_HEADER,  0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);         
		curl_setopt($ch, CURLOPT_USERAGENT, $agent); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

		$html = curl_exec($ch);
		curl_close($ch);
		return $html;
	}
	
	private function toko_tokopedia($urlToko){
		$result=[];
		$url = $urlToko.'/info';
		$referer='https://www.tokopedia.com/';
		//$html = $this->curl($url, $referer);
		$html = $this->getPageTokopedia($url, $referer);
		if(!$html) return $result;
		$dom = new Dom;
		$html = $this->tidyHtml($html);
		$dom->load($html);
		$statistik = $dom->find('.shop-statistics li.span3 div strong');echo $html;exit;
		$shopId = $dom->find('#shop-id')->getAttribute('value');
		
		if(count($dom->find('#shop-reputation-points'))>0 && $dom->find('#shop-reputation-points')->getAttribute('data-original-title')!=''){
			$badgeValue = $dom->find('#shop-reputation-points')->getAttribute('data-original-title');
		}else{
			$badgeValue = $this->getTokoReputationTokopedia($shopId, $urlToko);
		}
		
		
		$result = [
			'web'=>'tokopedia',
			'name'=>$dom->find('.shop-owner-wrapper h3 a')->text,
			'reputasi'=> [
				'badge' => $this->getBadgeTokopedia($badgeValue),
				'value' => $this->getPriceInt($badgeValue),
			],
			'location'=> count($dom->find('span[itemprop="location"]'))>0?$dom->find('span[itemprop="location"]')->text:null,
			'total_product'=> $this->getPriceInt($statistik[3]->text),
			'speed'=> $this->getSpeedTokoPedia($shopId, $urlToko),
			'total_sold'=> $this->getPriceInt($statistik[1]->text)
		];
		
		return $result;
	}
	
	private function getShopIdFromUrlTokopedia($url){
		$referer='https://www.tokopedia.com/';
		$html = $this->curl($url, $referer);
		if($html !== false) {
			$dom = new Dom;
			$dom->load($html);
			$shop_id = count($dom->find('#shop-id'))>0?$dom->find('#shop-id')->getAttribute('value'):'';
			if(empty($shop_id)){
				preg_match('/"shop_id":(\d+)/', $html, $match);
				if(isset($match[1]) &&!empty($match[1])) $shop_id = $match[1];
			}
			if(empty($shop_id)){
				preg_match_all('/\\\"shopIDs\\\":\[(\d+)\]/', $html, $match);
				if(isset($match[1][0]) &&!empty($match[1][0])) $shop_id = $match[1][0];
			}
			return $shop_id;
		}
		
	}
	
	
	private function product_tokopedia($urlProduct){
		$shopId = $this->getShopIdFromUrlTokopedia($urlProduct);
		$limit = 0;
		$result=[];
		$maxPage = 10000;
		
		for($page=$this->pageFrom; $page<=$this->pageTo;$page++){
			$sPage=($page>1)?'&page='.$page:'';
			if($page>$maxPage) break;
			$url = $urlProduct;
			$start = ($page-1)*80;
			$url = 'https://ace.tokopedia.com/search/product/v3?shop_id='. $shopId .'&ob=11&rows=80&start='. $start.'&full_domain=www.tokopedia.com&scheme=https&device=desktop&source=shop_product';//echo $url;exit;
			$referer= $urlProduct;
			$html = $this->curl($url, $referer);
			if(!$html) return $result;
			$arr = json_decode($html, true);
			$maxPage = !empty($arr['header']['total_data'])?$arr['header']['total_data'] /80:1;
			$more=($page<$maxPage)?true:false;
			if(isset($arr['data']['products'])){
				foreach($arr['data']['products'] as $product){
					$isDiscount = $product['discount_percentage']==0?false:true;
					$expUrl = explode('?',$product['url']);
					$result[] = [
						//'web'=>'tokopedia',
						'id'=> $product['id'],
						'title'=>  $product['name'],
						'url'=> $expUrl[0], 
						'image'=> $product['image_url_300'],
						'images'=>[],
						'price'=> [
							'original'=> !empty($product['original_price'])?$this->getPriceInt($product['original_price']):$product['price_int'],
							'discount'=>$product['discount_percentage'],
							'post'=>$product['price_int']
						],
						/*'seller'=> [
							'name'=>$product['shop']['name'],
							'url'=>$product['shop']['url'],
							'location'=> $product['shop']['location']
						],
						'condition'=>  $product['condition'],
						'sold_count'=>  null,
						'view_count'=>  null,
						'review'=>  [
							'rating'=> $product['rating'],
							'count'=> $product['count_review']
						],
						'stock'=>  $product['stock'],*/
						'more_page'=>  $more,
					];
					$limit++;
					if($limit>= $this->limit) break;
				}
			}
			
		}

		
		return $result;
	}
	
	private function getProductViewCountTokopedia($itemId, $itemUrl=null){
		$url = 'https://www.tokopedia.com/provi/check?pid='.$itemId.'&callback=show_product_view';
		$referer=!empty($itemUrl)?$itemUrl:'https://www.tokopedia.com/';
		
		$header = [
			'accept: text/javascript, application/javascript, application/ecmascript, application/x-ecmascript, */*; q=0.01',
			'accept-encoding: gzip, deflate, br',
			'accept-language: en-US,en;q=0.9',
			'content-type: application/json',
			'cookie: lang=id; _gcl_aw=GCL.1539272583.Cj0KCQjw6fvdBRCbARIsABGZ-vTKk3yUoAyg8nty_iReB4dz2pi7Tr6RH_CWPL2Fkkks6VrsfGL11zgaAjjOEALw_wcB; _gcl_au=1.1.1211124143.1539272583; __auc=c4e8412f16663cba7f5e2c21e54; _ID_autocomplete_=b5df5227097f46eaae80465a8be647a8; cto_lwid=045dae41-49c6-41c2-8874-2eb4827b364a; scs=%7B%22t%22%3A1%7D; ins-mig-done=1; spUID=153927259964222c962230a.db04369d; _ga=GA1.2.1742390914.1539272600; _gac_UA-9801603-1=1.1539272715.Cj0KCQjw6fvdBRCbARIsABGZ-vTKk3yUoAyg8nty_iReB4dz2pi7Tr6RH_CWPL2Fkkks6VrsfGL11zgaAjjOEALw_wcB; fe_discovery_experiments=%7B%220%22%3A%7B%22exp_middle_row_ads%22%3A%7B%22name%22%3A%22exp_middle_row_ads%22%2C%22selected%22%3A%22topads-current%22%7D%7D%2C%22undefined%22%3A%7B%22exp_middle_row_ads%22%3A%7B%22name%22%3A%22exp_middle_row_ads%22%2C%22selected%22%3A%22topads-current%22%7D%7D%7D; USER_DATA=%7B%22attributes%22%3A%5B%5D%2C%22subscribedToOldSdk%22%3Afalse%2C%22deviceUuid%22%3A%22b87c77c7-6fdc-4b72-8a9c-f9b08f4066e4%22%2C%22deviceAdded%22%3Afalse%7D; _hjIncludedInSample=1; __atuvc=2%7C42; ISID=%7B%22www.tokopedia.com%22%3A%22d3d3LnRva29wZWRpYS5jb20%3D.8d2537519f9fd4a1a8c8c6aaed468fde.1539363396460.1540067404791.1540125986750.17%22%7D; _BID_TOKOPEDIA_=c60c4d7fdf3a7712eb8ac91cede9ee0a; __asc=d2a4c4c8166aa0643853b02910b; AMP_TOKEN=%24NOT_FOUND; _SID_Tokopedia_=HaFjoTZOsDNU_L6otB1dgn3iDn65PpJcnPmp96hCVFHVNWi70MEUK6luiiAFCSeb8ivBk4UsSoE4BHRvAqHJvxRGPFXG98cvafKUpBGKi4ki0ggMCLdPUGahICdMh9FX; _gid=GA1.2.1676916014.1540450836; pdpABTestDisplay0=false; insdrSV=132; _fbp=fb.1.1540450840613.952466709; prm2_0_g_m_id=[]; ins-gaSSId=8fe963b1-3c57-41f6-4332-15c447ae2fe1_1540454446; current-currency=IDR; _dc_gtm_UA-9801603-1=1; _gat_UA-9801603-1=1; state=eyJyZWYiOiJodHRwczovL3d3dy50b2tvcGVkaWEuY29tL2pvZ2phc3RyaWtlL2pvcmFuLXRlZ2VrLWN1c3RvbS1iYXR0bGUteC0zbS1taW5pLWtlaXJ5dT90cmtpZD1mPUNhMTQ1M0wwMDBQMFcwUzBTaDAwQ28wUG8wRnIwQ2IwX3NyYz1kaXJlY3RvcnlfcGFnZT0xX29iPThfcT1fcG89N19jYXRpZD0xNDUzXHUwMDI2bHQ9L3Avb2xhaHJhZ2EvYWxhdC1wYW5jaW5nL2pvcmFuLXBhbmNpbmclMjAtJTIwcHJvZHVjdCUyMDUlMjAtJTIwcHJvZHVjdCUyMGxpc3QiLCJ1dWlkIjoiNGM0ZTZmMTItMGJmMi00NDFjLTg2MDAtMzQxYzQ1ODdkZDdmIiwidGhlbWUiOiJpZnJhbWUiLCJwIjoiaHR0cHM6Ly93d3cudG9rb3BlZGlhLmNvbS9qb2dqYXN0cmlrZS9qb3Jhbi10ZWdlay1jdXN0b20tYmF0dGxlLXgtM20tbWluaS1rZWlyeXU_dHJraWQ9Zj1DYTE0NTNMMDAwUDBXMFMwU2gwMENvMFBvMEZyMENiMF9zcmM9ZGlyZWN0b3J5X3BhZ2U9MV9vYj04X3E9X3BvPTdfY2F0aWQ9MTQ1M1x1MDAyNmx0PS9wL29sYWhyYWdhL2FsYXQtcGFuY2luZy9qb3Jhbi1wYW5jaW5nJTIwLSUyMHByb2R1Y3QlMjA1JTIwLSUyMHByb2R1Y3QlMjBsaXN0In0',
			'x-requested-with: XMLHttpRequest'
		];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_REFERER, $referer);
		$agent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36";
		curl_setopt($ch, CURLOPT_HTTPHEADER,  $header);
		curl_setopt($ch, CURLOPT_HEADER,  0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);         
		curl_setopt($ch, CURLOPT_USERAGENT, $agent); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

		$html = curl_exec($ch);
		curl_close($ch);
		
		if($html !== false){
			preg_match('/show_product_view\((.*)\)/', $html, $match);
			if(isset($match[1])){
				$arr =json_decode($match[1], true);
				return $arr['view'];
			}
		}
	}
	
	private function getProductSoldCountTokopedia($itemId, $itemUrl=null){
		$url = 'https://js.tokopedia.com/productstats/check?pid='.$itemId;
		$referer=!empty($itemUrl)?$itemUrl:'https://www.tokopedia.com/';
		$html = $this->curl($url, $referer);
		if($html !== false){
			preg_match('/show_product_stats\((.*)\)/', $html, $match);
			if(isset($match[1])){
				$arr =json_decode($match[1], true);
				return $arr['item_sold'];
			}
		}
	}
	
	private function getProductPriceTokokpedia($html){
		preg_match_all('/var campaign = \{(.*)\};/is', $html, $match);
		if(isset($match[0][0])){
			$json = str_replace(['var campaign = ',';'],'', $match[0][0]);
			$json = str_replace("'", '"', $json);
			$arr = json_decode(trim($json), true);
			if(isset($arr['is_active']) && $arr['is_active']==true){
				return [
					'original'=> $arr['original_price'],
					'discount'=> $arr['percentage_amount'],
					'post'=> $arr['discounted_price'],
				];
			}else{
				$dom = new Dom;
				$dom->load($html);
				return [
					'original'=> $dom->find('#product_price_int')->getAttribute('value'),
					'discount'=>0,
					'post'=>$dom->find('#product_price_int')->getAttribute('value'),
				];
			}
			
		}
	}
	
	private function detail_tokopedia($url){
		$result=[];
		$referer='https://www.tokopedia.com/';
		$html = $this->curl($url, $referer);
		if(!$html) return $result;
		$dom = new Dom;
		$html = $this->tidyHtml($html);
		$dom->load($html);
		$shopId = $dom->find('#shop-id')->getAttribute('value');
		$itemId = $dom->find('#product-id')->getAttribute('value');
		$isReviewAvailable= count($dom->find('meta[itemprop="ratingValue"]'))==0?false:true;
		$images = [];
		$imageDom = $dom->find('.thumbnail-img-show .content-img-relative img');
		if(count($imageDom)>0){
			foreach($imageDom as $im){
				$images[] = $im->getAttribute('src');
			}
		}
		$result[0] = [
			/*'web'=> 'tokopedia',
			'seller'=> [
				'name'=> $dom->find('#shop-name-info')->text,
				'url'=> $dom->find('.rvm-merchat-name a')->getAttribute('href'),
				'location'=> $dom->find('.rvm-merchat-city span')->text,
			],
			'product'=>[*/
				'title'=> $dom->find('.rvm-product-title span')->text,
				'url'=> $url,
				'description'=> $dom->find('#info')->innerHtml,
				'price'=> $this->getProductPriceTokokpedia($html),
				'image'=> $images[0],
				'images'=> $images,
				'more_page'=> false,
				/*'condition'=> stripos($dom->find('.rvm-product-info--item_value')->text(), 'Baru' )!==false?1:2,
				'total_sold'=> $this->getProductSoldCountTokopedia($itemId, $url),
				'total_seen'=> $this->getProductViewCountTokopedia($itemId, $url),
				'review'=>[
					'rating'=> $isReviewAvailable?$dom->find('meta[itemprop="ratingValue"]')->getAttribute('content'):null,
					'count'=> $isReviewAvailable?$dom->find('meta[itemprop="ratingCount"]')->getAttribute('content'):null,
				],
				'stock'=> $dom->find('.input-quantity')->getAttribute('data-stock-qty')
			]*/
		];
		
		return $result;
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
	
	private function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
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
