<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPHtmlParser\Dom;

class auto_repost extends MX_Controller {
	public $max_size = 5*1024;

	public function __construct(){
	parent::__construct();
		$this->module = get_class($this);
		$this->load->model(get_class($this).'_model', 'model');
		
	}
	
	public function index(){
		$module_name = 'Auto Repost';
		$module_icon = "fa fa-paper-plane";
		$columns = array(
			"id" => 'Id',
			"username" => 'Username',
			"target" => 'Target',
			'interval_scan' => 'Scan.Interval',
			'last_scan' => 'Scan.Last',
			'next_scan' => 'Scan.Next',
			"log_created" => 'Created',
			"log_modified" => 'Changed',
			"note" => 'Note',
		);
		
		$page        = (int)get("p");
		$limit       = 50;
		$result      = $this->model->getTableAutoRepost($columns, $limit, $page);
		$total       = $this->model->getTableAutoRepost($columns, -1, -1);
		$total_final = $total;

		$query = array();
		$query_string = "";
		if(get("c")) $query["c"] = get("c");
		if(get("t")) $query["t"] = get("t");
		if(get("k")) $query["k"] = get("k");

		if(!empty($query)){
			$query_string = "?".http_build_query($query);
		}

		$configs = array(
			"base_url"   => cn('instagram/'.get_class($this).$query_string), 
			"total_rows" => $total_final, 
			"per_page"   => $limit
		);

		$this->pagination->initialize($configs);

		$data = array(
			"columns" => $columns,
			"result"  => $result,
			"total"   => $total_final,
			"page"    => $page,
			"limit"   => $limit,
			"module"  => 'instagram/'. get_class($this),
			"module_class"  => 'instagram/'.get_class($this),
			"module_name" => $module_name,
			"module_icon" => $module_icon
		);
		$this->template->build('auto_repost/index', $data);
	}

	
	public function form(){
		$data = array(
			'accounts'     => $this->model->fetch("id, username, avatar, ids", INSTAGRAM_ACCOUNTS, "uid = ".session("uid")." AND status = 1"),
			'data'=> $this->getData()
		);
		$this->template->build('auto_repost/form', $data);
	}
	
	private function getData(){
		$id = get('id');
		$uid = session('uid');
		if(empty($id)) return null;
		$q = $this->db->get_where('instagram_auto_repost', ['id'=>$id, 'uid'=>$uid]);
		if($q->num_rows() ==0) return null;
		$row = $q->row();
		$data = [];
		$data['id'] = $row->id;
		$data['account'] = $row->account_id;
		$data['message'] = $row->message;
		$data['target'] = $row->target;
		$data['start_time'] = $row->start_time;
		$data['interval_scan'] = $row->interval_scan;
		$config = json_decode($row->config,true);
		$data['days'] = (isset($config['days']) && !empty($config['days']))?$config['days']:null;
		$data['hours'] = (isset($config['hours']) && !empty($config['hours']))?$config['hours']:null;
		$data['watermark'] = (isset($config['watermark']) && !empty($config['watermark']))?$config['watermark']:null;
		$data['include'] = (isset($config['include']) && !empty($config['include']))?$config['include']:null;
		$data['exclude'] = (isset($config['exclude']) && !empty($config['exclude']))?$config['exclude']:null;
		$data['pattern_from'] = (isset($config['pattern_from']) && !empty($config['pattern_from']))?$config['pattern_from']:null;
		$data['pattern_to'] = (isset($config['pattern_to']) && !empty($config['pattern_to']))?$config['pattern_to']:null;
		$data['interval_post'] = $config['interval_post'];
		//print_r($data);exit;
		return $data;
		
	}
	
	private function isTargetValid($target,$uid){
		$target = str_replace(['://www.m.', '://m.'],['://www.','://'],$target); //force mobile to use desktop site
		$host = parse_url($target, PHP_URL_HOST);
		$target = trim($target, '/');
		$exp = explode('/', $target);
		if(empty($host)){
			$osUrl = $this->isOnlineShop($target, $uid);
		}else{
			$osUrl = $this->isOnlineShop($host, $uid);
		}
		if($host=="instagram.com" || $host=="www.instagram.com"){
			$target= 'https://www.instagram.com/'.end($exp);
		}elseif($host=="bukalapak.com" || $host=="www.bukalapak.com"){
			$target= 'https://bukalapak.com/u/'.end($exp);
		}elseif($host=="tokopedia.com" || $host=="www.tokopedia.com"){
			$target= 'https://tokopedia.com/'.end($exp);
		}elseif($host=="shopee.co.id" || $host=="www.shopee.co.id"){
			$target= 'https://shopee.co.id/'.end($exp);
		}elseif($osUrl){
			return $osUrl;
		}elseif(preg_match('/^[a-zA-Z0-9._]+$/', $target)){
			return 'https://www.instagram.com/'.$target;
		}else{
			$target = false;
		}
		return $target;
	}
	
	public function save(){
		$max_activity_per_account = get_option('max_auto_repost_per_account',5);
		$id = post('id');
		$uid = session('uid');
		$accounts = post('account');
		$target = post('target');
		$message = post('message');
		$start = post('start');
		if(empty($start)) $start = date("Y-m-d H:i:s");
		
		$delay = (int) post('delay');
		if($delay<1) $delay = 1;
		
		$interval = (int) post('interval');
		if($interval<1) $interval = 1;
		$config = post('config');
		$tmp = [];
		if(!empty($accounts)){
			foreach($accounts as $account_id){
				if($this->db->get_where(INSTAGRAM_ACCOUNTS, ['uid'=>$uid, 'id'=>$account_id, 'status'=>1])->num_rows()==1){
					$tmp[] = $account_id;
				}
			}
		}

		if(empty($tmp)){
			ms([
				'status'=>'error',
				'message'=>'Tidak ada akun yang valid',
			]);
		}elseif(!empty($id) && count($accounts)>1){
			ms([
				'status'=>'error',
				'message'=>'Akun hanya boleh 1 jika anda melakukan pengeditan',
			]);
		}else{
			$accounts = $tmp;
		}
		
		$max_account = $this->db->select('a.number_accounts')->from('general_packages a')->join('general_users b','a.id=b.package')->where('b.id', $uid)->get()->row()->number_accounts;
		$max_activity_per_account= $max_account * $max_activity_per_account;
		if($uid==1) $max_activity_per_account =9999;
		
		$targets = [];
		$target = str_replace(",","\n", $target);
		$target = str_replace(" ","\n", $target);
		foreach(explode("\n", $target) as $t){
			$t = trim($t);
			$t = $this->isTargetValid($t, $uid);
			if(!empty($t)){
				$targets[] = $t;
			}
		}
		
		if(empty($targets)){
			ms([
				'status'=>'error',
				'message'=>'Tidak ada target yang valid',
			]);
		}elseif(!empty($id) && count($targets)>1){
			ms([
				'status'=>'error',
				'message'=>'Target hanya boleh 1 jika anda melakukan pengeditan',
			]);
		}
		
		if(isset($config['include']) && !empty($config['include'])){
			$config['include'] = explode('[[delimiter]]', $config['include']);
		}
		if(isset($config['exclude']) && !empty($config['exclude'])){
			$config['exclude'] = explode('[[delimiter]]', $config['exclude']);
		}
		
		$additional_config = json_encode($config);
		
		$ts_start = strtotime($start);
		foreach($accounts as $iAccount=>$account_id){
			$theDelay = $delay * $iAccount;
			foreach($targets as $iTarget=>$target){
				$theInterval = $interval * $iTarget;
				if(!empty($id)){
					$q = $this->db->get_where('instagram_auto_repost', ['id'=>$id, 'uid'=>$uid,'account_id'=>$account_id]);
					if($q->num_rows()==0){
						ms([
							'status'=>'error',
							'message'=>'Id tidak ditemukan',
						]);
					}
					$next_scan = strtotime($q->row()->next_scan);
					if($next_scan < time()) $next_scan = time();
					$next_scan = $next_scan + 600 + ($theDelay*60)+ ($theInterval*60); //klo d rubah jadi 10 menit lg baru jalan
					$data =[
						'uid'=>$uid,
						'account_id'=>$account_id,
						'message'=>$message,
						'start_time'=>date('Y-m-d H:i:s', $ts_start),
						'interval_scan'=>$interval,
						'next_scan'=>date('Y-m-d H:i:s', $next_scan),
						'target'=>$target,
						'config'=>$additional_config,
						'log_modified'=>NOW
					];
					$this->db->update('instagram_auto_repost', $data, ['id'=>$id]);
				}else{
					$count = $this->db->get_where('instagram_auto_repost', ['uid'=>$uid])->num_rows();
					if($count > $max_activity_per_account){
						$m = $this->db->get_where(INSTAGRAM_ACCOUNTS, ['uid'=>$uid, 'id'=>$account_id]);
						ms([
							'status'=>'error',
							'message'=>'Akun '.$m->row()->username .' melebihi batasan auto repost per akun yaitu '.$max_activity_per_account,
						]);
					}
					
					$next_scan = $ts_start + 600 + ($theDelay*60)+ ($theInterval*60); //10 menit lg baru jalan
					$data =[
						'uid'=>$uid,
						'account_id'=>$account_id,
						'message'=>$message,
						'start_time'=>date('Y-m-d H:i:s', $ts_start),
						'interval_scan'=>$interval,
						'next_scan'=>date('Y-m-d H:i:s', $next_scan),
						'target'=>$target,
						'config'=>$additional_config,
					];
					$this->db->insert('instagram_auto_repost', $data);
				}
			}
			
		}
		
		ms([
			'status'=>'success',
			'message'=>'berhasil',
		]);
	}
	
	public function ajax_delete_item(){
		$listid = post('id');
		if(!empty($listid)){
			if(!is_array($listid)) $listid=[$listid];
			foreach($listid as $id){
				$this->db->delete('instagram_auto_repost', ['id'=>$id , 'uid'=>session('uid')]);
			}
			ms([
				'status'=>'success',
				'message'=>'Data berhasil dihapus'
			]);
		}else{
			ms([
				'status'=>'error',
				'message'=>'id cannot empty'
			]);
		}
		
	}
	
	private function isOnlineShop($host, $uid){
		$q = $this->db->query("SELECT url FROM general_online_shop WHERE nama=? AND status=1 AND (is_private=0 OR uid=?)", [$host, $uid]);
		return ($q->num_rows()>0)?$q->row()->url:false;
	}
	
	private function scan_post($row){
		$uid = $row->uid;
		$id = $row->id;
		$jobname = 'auto_repost_'.$id;
		$config = json_decode($row->config, true);
		$start_ts = strtotime($row->start_time);
		//get from db first
		//gantii ngawur ini kurang efektif
		//berat cok
		$q = $this->db->query("SELECT * FROM instagram_master_new_post a WHERE a.target=? AND a.post_time <= ? ORDER BY a.post_time DESC LIMIT 20", [$row->target, $row->start_time]);
		$foundUnscheduledPost=false;
		if($q->num_rows() > 0){
			$n=0;
			foreach($q->result() as $line){
				$r = $this->db->query("SELECT * FROM instagram_auto_repost_activity a WHERE a.master_post_id=? AND a.auto_repost_id=? ORDER BY a.log_created DESC LIMIT 10", [$line->id, $id])->row();
				if(!$r){
					$foundUnscheduledPost=true;
					$master_post_id=$line->id;
					$caption = $line->message;
					$masterPostTime_ts = strtotime($line->post_time);
					$this->db->update('instagram_auto_repost', ['note'=>''], ['id'=>$id]);
					if($start_ts>$masterPostTime_ts){
						$this->db->insert('instagram_auto_repost_activity', ['auto_repost_id'=>$id, 'master_post_id'=>$master_post_id, 'queued'=>2, 'note'=>'post time too old']);
					}elseif(!empty($config['include']) && !$this->isContaintWord($caption, $config['include'])){
						$this->db->insert('instagram_auto_repost_activity', ['auto_repost_id'=>$id, 'master_post_id'=>$master_post_id, 'queued'=>2, 'note'=>'message not cointain include word']);
					}elseif(!empty($config['exclude']) && !$this->isNotContaintWord($caption, $config['exclude'])){
						$this->db->insert('instagram_auto_repost_activity', ['auto_repost_id'=>$id, 'master_post_id'=>$master_post_id, 'queued'=>2, 'note'=>'message cointain exclude word']);
					}else{
						$this->db->insert('instagram_auto_repost_activity', ['auto_repost_id'=>$id, 'master_post_id'=>$master_post_id, 'queued'=>1]);
						$post_time = $this->calculatNextRun2($row->account_id, $n * $config['interval_post'], $row->start_time, $config['days'], $config['hours']);
						$tmp_config['message']= $row->message;
						if(isset($config['pattern_from']) && !empty($config['pattern_from'])){
							$tmp_config['pattern_from']= $config['pattern_from'];
						}
						if(isset($config['pattern_to']) && !empty($config['pattern_to'])){
							$tmp_config['pattern_to']= $config['pattern_to'];
						}
						$additional_config = json_encode($tmp_config);
						$this->db->insert('instagram_manual_activity', ['uid'=>$uid, 'account_id'=>$row->account_id, 'target'=>$url, 'job_name'=>$jobname, 'type'=>'repost', 'post_time'=>$post_time, 'additional_config'=>$additional_config]);
						$n++;
					}
				}
			}
			
			
		}
		
		if($foundUnscheduledPost) return true;
		
		//klo g ada d db scrap data baru
		$host = parse_url($row->target, PHP_URL_HOST);
		$osUrl = $this->isOnlineShop($host, $uid);
		if(stripos($host, 'instagram.com')!==false){
			$type = 'instagram';
			$exp = explode('/',$row->target);
			$targetUsername = end($exp);
			$ig = new InstagramAPI($row->username, $row->password, $row->proxy);
			$userinfo = $ig->get_userinfo($targetUsername);
			if(empty($userinfo)){
				$this->db->update('instagram_auto_repost', ['log_modified'=>NOW, 'note'=>'Cant get userinfo'], ['id'=>$id]);
				return false;
			}
			$userId = $userinfo->pk;
			if($userinfo->is_private){
				$friendship = $ig->get_friendships($userId);
				if(empty($friendship) || !isset($friendship->$userId)){
					$this->db->update('instagram_auto_repost', ['log_modified'=>NOW, 'note'=>'Cant get friendship'], ['id'=>$id]);
					return false;
				}elseif($friendship->$userId->following!=1){
					$this->db->update('instagram_auto_repost', ['log_modified'=>NOW, 'note'=>'You must follow private user first'], ['id'=>$id]);
					return false;
				}
			}
			
			$feeds = $ig->get_feed($userId, null, false, 10);
			if(empty($feeds)){
				$this->db->update('instagram_auto_repost', ['log_modified'=>NOW, 'note'=>'Cannot get target feed'], ['id'=>$id]);
				return false;
			}
			
			$n =0;
			foreach($feeds as $feed){
				$url = 'https://www.instagram.com/p/'.$feed->code .'/';
				$m = $this->db->get_where('instagram_master_new_post', ['url'=>$url]);
				if($m->num_rows()>0) continue; //asumsi udah di queued jg
				$data = json_encode($feed);
				$caption = isset($feed->caption->text)?$feed->caption->text:'';
				$this->db->insert('instagram_master_new_post', ['type'=>$type, 'target'=>$row->target, 'url'=>$url, 'data'=>$data, 'post_time'=>date('Y-m-d H:i:s', $feed->taken_at), 'message'=>$caption]);
				$master_post_id = $this->db->insert_id();
				$this->db->update('instagram_auto_repost', ['note'=>''], ['id'=>$id]);
				if($start_ts>$feed->taken_at){
					$this->db->insert('instagram_auto_repost_activity', ['auto_repost_id'=>$id, 'master_post_id'=>$master_post_id, 'queued'=>2, 'note'=>'post time too old']);
				}elseif(!empty($config['include']) && !$this->isContaintWord($caption, $config['include'])){
					$this->db->insert('instagram_auto_repost_activity', ['auto_repost_id'=>$id, 'master_post_id'=>$master_post_id, 'queued'=>2, 'note'=>'message not cointain include word']);
				}elseif(!empty($config['exclude']) && !$this->isNotContaintWord($caption, $config['exclude'])){
					$this->db->insert('instagram_auto_repost_activity', ['auto_repost_id'=>$id, 'master_post_id'=>$master_post_id, 'queued'=>2, 'note'=>'message cointain exclude word']);
				}else{
					$this->db->insert('instagram_auto_repost_activity', ['auto_repost_id'=>$id, 'master_post_id'=>$master_post_id, 'queued'=>1]);
					$post_time = $this->calculatNextRun2($row->account_id, $n * $config['interval_post'], $row->start_time, $config['days'], $config['hours']);
					$tmp_config['message']= $row->message;
					if(isset($config['pattern_from']) && !empty($config['pattern_from'])){
						$tmp_config['pattern_from']= $config['pattern_from'];
					}
					if(isset($config['pattern_to']) && !empty($config['pattern_to'])){
						$tmp_config['pattern_to']= $config['pattern_to'];
					}
					$additional_config = json_encode($tmp_config);
					$this->db->insert('instagram_manual_activity', ['uid'=>$uid, 'account_id'=>$row->account_id, 'target'=>$url, 'job_name'=>$jobname, 'type'=>'repost', 'post_time'=>$post_time, 'additional_config'=>$additional_config]);
					$n++;
				}
			}
			
			
		}elseif(preg_match('/(www\.)?+(bukalapak.com|shopee.co.id|tokopedia.com)$/i', $host)){
			$type = 'marketplace';
			$this->load->library('marketplace', null, 'market');
			$this->market->setProxy($row->proxy);
			$tokoProducts = $this->market->tokoProducts($row->target);
			
			if(!isset($tokoProducts['data']) || empty($tokoProducts['data'])){
				$this->db->update('instagram_auto_repost', ['log_modified'=>NOW, 'note'=>'Cant get toko product'], ['id'=>$id]);
				return false;
			}
			
			$feeds = $tokoProducts['data'];
			array_splice($feeds, 10); //batasin cm 10 post krn g ada post time. biar g ngeblast semua
			
			$n =0;
			foreach($feeds as $feed){
				$url = $feed['url'];
				$m = $this->db->get_where('instagram_master_new_post', ['url'=>$url]);
				if($m->num_rows()>0) continue; //asumsi udah di queued jg
				$data = json_encode($feed);
				$product = $this->market->productInfo($url, false);
				
				if(!isset($product['data']) || empty($product['data'])){
					$this->db->update('instagram_auto_repost', ['log_modified'=>NOW, 'note'=>'Cant get product info'], ['id'=>$id]);
					return false;
				}
				$product = $product['data'][0];
				$markup =0;
				if(isset($product['price']['post_max']) && $product['price']['original'] != $product['price']['post_max']){
					$price = number_format($product['price']['original'] * ((100+$markup)/100), 0, ',','.') ." - ". number_format($product['price']['post_max'] * ((100+$markup)/100), 0,',','.');
				}else{
					$price = number_format($product['price']['original'] * ((100+$markup)/100),0,',','.');
				}
				$desc = preg_replace('#<br\s*/?>#i', "\n", strip_tags($product['description'], '<br>'));
				$desc = trim($desc);
				$original_caption= $product['title'] ."\n\n".
				"Harga Rp ". $price."\n".
				$desc
				;
				$original_caption = preg_replace('#<br\s*/?>#i', "\n", $original_caption);
				$exp = explode("\n", $original_caption);
				foreach($exp as $i=>$e){
					$exp[$i] = trim($e);
				}
				$original_caption = implode("\n", $exp);
				
				$caption = $original_caption;
				$productPostTime = time();
				$this->db->insert('instagram_master_new_post', ['type'=>$type, 'target'=>$row->target, 'url'=>$url, 'data'=>$data, 'post_time'=>date('Y-m-d H:i:s', $productPostTime), 'message'=>$caption]);
				$master_post_id = $this->db->insert_id();
				$this->db->update('instagram_auto_repost', ['note'=>''], ['id'=>$id]);
				if($start_ts>$productPostTime){
					$this->db->insert('instagram_auto_repost_activity', ['auto_repost_id'=>$id, 'master_post_id'=>$master_post_id, 'queued'=>2, 'note'=>'post time too old']);
				}elseif(!empty($config['include']) && !$this->isContaintWord($caption, $config['include'])){
					$this->db->insert('instagram_auto_repost_activity', ['auto_repost_id'=>$id, 'master_post_id'=>$master_post_id, 'queued'=>2, 'note'=>'message not cointain include word']);
				}elseif(!empty($config['exclude']) && !$this->isNotContaintWord($caption, $config['exclude'])){
					$this->db->insert('instagram_auto_repost_activity', ['auto_repost_id'=>$id, 'master_post_id'=>$master_post_id, 'queued'=>2, 'note'=>'message cointain exclude word']);
				}else{
					$this->db->insert('instagram_auto_repost_activity', ['auto_repost_id'=>$id, 'master_post_id'=>$master_post_id, 'queued'=>1]);
					$post_time = $this->calculatNextRun2($row->account_id, $n * $config['interval_post'], $row->start_time, $config['days'], $config['hours']);
					$tmp_config['message']= $row->message;
					if(isset($config['pattern_from']) && !empty($config['pattern_from'])){
						$tmp_config['pattern_from']= $config['pattern_from'];
					}
					if(isset($config['pattern_to']) && !empty($config['pattern_to'])){
						$tmp_config['pattern_to']= $config['pattern_to'];
					}
					$additional_config = json_encode($tmp_config);
					$this->db->insert('instagram_manual_activity', ['uid'=>$uid, 'account_id'=>$row->account_id, 'target'=>$url, 'job_name'=>$jobname, 'type'=>'repost', 'post_time'=>$post_time, 'additional_config'=>$additional_config]);
					$n++;
				}
			}
			
		}elseif($osUrl){
			$type = 'online_shop';
			$this->load->library('onlineshop', NULL, 'os');
			$this->os->setProxy($row->proxy);
			$tokoProducts = $this->os->getProductList($host, 1);
			
			if(!isset($tokoProducts) || empty($tokoProducts)){
				$this->db->update('instagram_auto_repost', ['log_modified'=>NOW, 'note'=>'Cant get toko product'], ['id'=>$id]);
				return false;
			}
			
			$feeds = $tokoProducts;
			array_splice($feeds, 10); //batasin cm 10 post krn g ada post time. biar g ngeblast semua
			
			$n =0;
			foreach($feeds as $feed){
				$url = $feed['url'];
				$m = $this->db->get_where('instagram_master_new_post', ['url'=>$url]);
				if($m->num_rows()>0) continue; //asumsi udah di queued jg
				$data = json_encode($feed);
				$product = $this->os->getProductInfo($host, $url);
				
				if(empty($product)){
					$this->db->update('instagram_auto_repost', ['log_modified'=>NOW, 'note'=>'Cant get product info'], ['id'=>$id]);
					return false;
				}
				$desc = preg_replace('#<br\s*/?>#i', "\n", strip_tags($product['description'], '<br>'));
				$desc = trim($desc);
				$original_caption = $product['nama'] ."\n\n".
				"Harga Rp ". $product['harga']."\n".
				$desc
				;
				
				$caption = $original_caption;
				$productPostTime = time();
				$this->db->insert('instagram_master_new_post', ['type'=>$type, 'target'=>$row->target, 'url'=>$url, 'data'=>$data, 'post_time'=>date('Y-m-d H:i:s', $productPostTime), 'message'=>$caption]);
				$master_post_id = $this->db->insert_id();
				$this->db->update('instagram_auto_repost', ['note'=>''], ['id'=>$id]);
				if($start_ts>$productPostTime){
					$this->db->insert('instagram_auto_repost_activity', ['auto_repost_id'=>$id, 'master_post_id'=>$master_post_id, 'queued'=>2, 'note'=>'post time too old']);
				}elseif(!empty($config['include']) && !$this->isContaintWord($caption, $config['include'])){
					$this->db->insert('instagram_auto_repost_activity', ['auto_repost_id'=>$id, 'master_post_id'=>$master_post_id, 'queued'=>2, 'note'=>'message not cointain include word']);
				}elseif(!empty($config['exclude']) && !$this->isNotContaintWord($caption, $config['exclude'])){
					$this->db->insert('instagram_auto_repost_activity', ['auto_repost_id'=>$id, 'master_post_id'=>$master_post_id, 'queued'=>2, 'note'=>'message cointain exclude word']);
				}else{
					$this->db->insert('instagram_auto_repost_activity', ['auto_repost_id'=>$id, 'master_post_id'=>$master_post_id, 'queued'=>1]);
					$post_time = $this->calculatNextRun2($row->account_id, $n * $config['interval_post'], $row->start_time, $config['days'], $config['hours']);
					$tmp_config['message']= $row->message;
					if(isset($config['pattern_from']) && !empty($config['pattern_from'])){
						$tmp_config['pattern_from']= $config['pattern_from'];
					}
					if(isset($config['pattern_to']) && !empty($config['pattern_to'])){
						$tmp_config['pattern_to']= $config['pattern_to'];
					}
					$additional_config = json_encode($tmp_config);
					$this->db->insert('instagram_manual_activity', ['uid'=>$uid, 'account_id'=>$row->account_id, 'target'=>$url, 'job_name'=>$jobname, 'type'=>'repost', 'post_time'=>$post_time, 'additional_config'=>$additional_config]);
					$n++;
				}
			}
			
		}else{
			$this->db->update('instagram_auto_repost', ['next_scan'=>null, 'log_modified'=>NOW, 'note'=>'Host not recognized'], ['id'=>$id]);
		}
	}
	
	private function isContaintWord($string, array $words){
		foreach($words as $word){
			if(stripos($string, $word)!==false) return true;
		}
		return false;
	}
	
	private function isNotContaintWord($string, array $words){
		foreach($words as $word){
			if(stripos($string, $word)!==false) return false;
		}
		return true;
	}
	
	public function schedule_scan(){
		if(!is_cli() && session('uid') !=1) redirect();
		//$this->db->truncate('instagram_master_new_post');
		//$this->db->truncate('instagram_auto_repost_activity');

		$q = $this->db->query("SELECT a.*, c.username, c.password, c.proxy FROM instagram_auto_repost a JOIN general_users b ON a.uid=b.id JOIN instagram_accounts c ON a.account_id=c.id  WHERE a.next_scan <=? AND b.status=1 AND c.status=1 ORDER BY a.next_scan ASC LIMIT 10", [NOW]);
		//$q = $this->db->query("SELECT a.*, c.username, c.password, c.proxy FROM instagram_auto_repost a JOIN general_users b ON a.uid=b.id JOIN instagram_accounts c ON a.account_id=c.id  WHERE a.next_scan <=? AND b.status=1 AND c.status=1 AND c.username='sherlyanantav' ORDER BY a.next_scan ASC LIMIT 10", ['2020-01-01']);
		if($q->num_rows()==0) die('kosong');
		foreach($q->result() as $row){
			$next_scan = strtotime($row->next_scan);
			$ts = time();
			if($next_scan < $ts) $next_scan = $ts;
			$next_scan = $next_scan + ($row->interval_scan *60);
			$next_scan = date('Y-m-d H:i:s', $next_scan);
			$this->db->update('instagram_auto_repost', ['next_scan'=>$next_scan,'last_scan'=>NOW], ['id'=>$row->id]);
		}
		
		foreach($q->result() as $row){
			$this->scan_post($row);
		}
	}
	
	function calculatNextRun2($account_id, $interval, $start_time, array $days, array $hours){
		$start_ts = strtotime($start_time);
		$next = $start_ts + ($interval*60);

		$d = date('w', $next);
		$h = date('G', $next);
		$ts = 0;
		$tmp = [];
		while(!in_array($d, $days) || !in_array($h, $hours)){
			if(!in_array($d, $days)){
				$ts = mktime(0,0,0, date('n', $next), date('j', $next), date('Y', $next));
				$next = $next + 86400;
				$d =date('w', $ts);
				$h =date('G', $ts);
			}
			
			if(!in_array($h,$hours)){
				$ts = mktime(date('H', $next),0,0, date('n', $next), date('j', $next), date('Y', $next));
				$next = $next + 3600 ;
				$d =date('w', $ts);
				$h =date('G', $ts);
			}
		}
		$start_ts = empty($ts)?$next:$ts;
		return date('Y-m-d H:i:s', $start_ts);
	}
	
}