<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class dashboard extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function index(){
		$this->checkPaymentStatus();
		$data = array();
    $data['rss'] = $this->getRss();
		$this->template->title('Dashboard');
		$this->template->build('index', $data);
	}

	public function report(){
		echo block_report(segment(3))->data;
	}
	
	public function checkPaymentStatus(){
		$q= $this->db->get_where(USERS, ['id'=>session('uid'), 'package'=>1]);
		if($q->num_rows()> 0){
			$q = $this->db->from(USERS.' a')->join(PAYMENT_HISTORY .' b', 'a.id=b.uid')->where('a.package', 1)->where('b.status',0)->where('a.id', session('uid'))->get();
			if($q->num_rows()==0){
				redirect('pricing');
			}else{
				redirect('profile/user_payment_history');
			}
		}
	}
  	public function getRss(){
		$max_news = $max_tutorial = get_option('rss_max_item', 5);
		$url = get_option('rss_url', 'https://blog.lancarin.com/feed/');
		$rss = Feed::loadRss($url);
		$result = ['news'=>[], 'tutorial'=>[]];
		$count_tutorial = $count_news = 0;
		if(!empty($rss)) {
			foreach ($rss->item as $item) {
				if(strtolower($item->category) == 'news' && $count_news < $max_news ){
					$result['news'][] = ['date'=> date('d-m-Y', strtotime($item->pubDate)), 'text'=> '<a target="_blank" href="'.$item->link.'">'. $item->title ."</a>"];
					$count_news++;
				}elseif(strtolower($item->category) == 'tutorial' && $count_tutorial < $max_tutorial ){
					$result['tutorial'][] = ['date'=> date('d-m-Y', strtotime($item->pubDate)), 'text'=> '<a target="_blank" href="'.$item->link.'">'. $item->title ."</a>"];
					$count_tutorial++;
				}
			}
		}
		return $result;
		
	}
} 
