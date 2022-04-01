<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class post extends MX_Controller {
	public $table;
	public $module;
	public $post;
	public $module_name;
	public $module_icon;

	public function __construct(){
		parent::__construct();

		$this->tb_accounts = INSTAGRAM_ACCOUNTS;
		$this->tb_posts = INSTAGRAM_POSTS;
		$this->module = get_class($this);
		$this->module_name = lang("instagram_accounts");
		$this->module_icon = "fa fa-instagram";
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function index(){
		$data = array(
			'module'       => $this->module,
			'module_name'  => $this->module_name,
			'module_icon'  => $this->module_icon,
			'accounts'     => $this->model->fetch("id, username, avatar, ids", $this->tb_accounts, "uid = ".session("uid")." AND status = 1")
		);
		$this->template->build('post/index', $data);
	}

	public function block_report(){
		$data = array();
		$this->load->view('post/block_report', $data);
	}

	public function block_general_settings(){
		$data = array();
		$this->load->view('post/general_settings', $data);
	}

	public function popup_search_media(){
		$this->load->view("post/popup_search_media");
	}
	
	public function popup_search_marketplace(){
		$this->load->view("post/popup_search_marketplace");
	}

	
	public function ajax_search_media($return=false){
		$keyword = post("keyword");
		$type = post("type");
		$ids = post("ids");
		$data = array();
		$next_max_id= post('next_max_id');
		$data['status']='error'; 
		
		//$instagram_account = $this->model->get("*", $this->tb_accounts, "uid = '".session("uid")."' AND status = 1 ", "rand()");
		$instagram_account = $this->db->get_where($this->tb_accounts, ['uid'=>session("uid"), "status"=>1, "ids"=>$ids])->row();
		if(!empty($instagram_account)){
			try {
				$ig   = new InstagramAPI($instagram_account->username, $instagram_account->password, $instagram_account->proxy);
				$data["result"] = $ig->search_media($keyword, $type, $next_max_id);
				$data["status"]='success'; 
			} catch (Exception $e) {
				if($return) {$data['message'] = $e->getMessage(); return $data;}
				echo "<div class='alert alert-danger'>".$e->getMessage()."</div>";
			}
		}else{
			$data['message'] = 'You dont have account';
		}
		
		if($return) return $data;
		
		$this->load->view("post/ajax_search_media", $data);
	}

	public function ajax_search_collection(){
		$id = post("ids");
		$data = array();
		$next_max_id= post('next_max_id');
		
		$instagram_account = $this->db->get_where($this->tb_accounts, ['uid'=>session("uid"), "status"=>1, "id"=>$id])->row();
		if(!empty($instagram_account)){
			try {
				$ig   = new InstagramAPI($instagram_account->username, $instagram_account->password, $instagram_account->proxy);
				$resp = $ig->list_collection($next_max_id);
			} catch (Exception $e) {
				ms([
					'status'=>'error',
					'message'=>$e->getMessage(),
				]);
			}
		}else{
			ms([
				'status'=>'error',
				'message'=>'You dont have account',
			]);
		}
		
		$html = '';
		if($next_max_id==null){
		$html .= '<div class="col-md-3 col-sm-4 mb15">
			<div class="item" style="background-image: url('.base_url('assets/img/gallery.png').');">
			    <div class="type" style="font-size:20px">&#x221e;</div>
				<img class="fake-bg popoverCaption" data-delay-show="300" data-title="All Post" src="'.base_url('assets/img/transparent.png').'">
				<div class="list-option btn-group bg-white" role="group">
					<div class="col-md-12 col-sm-12 col-xs-12 btn btn-default btn-sm btnGetSavedMedia" data-id=""  data-type="media">All Post</div>
				</div>
			</div>
		</div>';
		}
		
		if(!empty($resp)){
			$next_max_id = isset($resp->next_max_id)?$resp->next_max_id:null;
			foreach($resp->items as $row){
				$credit = isset($row->user->username)?$row->user->username:null;
				$bg = base_url('assets/img/gallery.png');
				if(isset($row->cover_media->image_versions2->candidates[1]->url)){
					$bg = $row->cover_media->image_versions2->candidates[1]->url;
				}elseif(isset($row->cover_media->image_versions2->candidates[0]->url)){
					$bg = $row->cover_media->image_versions2->candidates[0]->url;
				}
				$html .= '<div class="col-md-3 col-sm-4 mb15">
					<div class="item" style="background-image: url('.$bg.');">
						<div class="type" style="font-size:14px">'.$row->collection_media_count.'</div>
						<img class="fake-bg popoverCaption" data-delay-show="300" data-title="'.$row->collection_name.'" src="'.base_url('assets/img/transparent.png').'">
						<div class="list-option btn-group bg-white" role="group">
							<div class="col-md-12 col-sm-12 col-xs-12 btn btn-default btn-sm btnGetSavedMedia" data-id="'.$row->collection_id.'" data-credit="'.$credit.'" data-type="'.$row->collection_type.'">'.$row->collection_name.'</div>
						</div>
					</div>
				</div>';
			}
			
			
		}
		
		ms([
			'status'=>'success',
			'next_max_id'=>$next_max_id,
			'data'=>$html
		]);
		
	}

	public function ajax_search_collection_feed(){
		$id = post("ids");
		$collection_id = post("collection_id");
		$next_max_id= post('next_max_id');
		if(empty($next_max_id)) $next_max_id=null;

		$instagram_account = $this->db->get_where($this->tb_accounts, ['uid'=>session("uid"), "status"=>1, "id"=>$id])->row();
		if(!empty($instagram_account)){
			try {
				$ig   = new InstagramAPI($instagram_account->username, $instagram_account->password, $instagram_account->proxy);
				$res = empty($collection_id)?$ig->get_saved_feed($next_max_id):$ig->get_collection_feed($collection_id, $next_max_id);
			} catch (Exception $e) {
				ms([
					'status'=>'error',
					'message'=>$e->getMessage(),
				]);
			}
		}else{
			ms([
				'status'=>'error',
				'message'=>'You dont have account',
			]);
		}
		
		$data ='';
		$next_max_id = null;
		if(empty($res)){
			ms([
				'status'=>'error',
				'message'=>'No Media found in this collection',
			]);
		}else{
				$next_max_id = isset($res->next_max_id)?$res->next_max_id:null;
				foreach ($res->items as $row) {
					$row = $row->media;
					switch ($row->media_type) {
						case 8:
							$media = array();
							foreach ($row->carousel_media as $value) {
								//$media_temp = explode("?", $value->image_versions2->candidates[0]->url);
								$media_temp[0] = $value->image_versions2->candidates[0]->url;
								$media[] = $media_temp[0];
							}
							$bg = $row->carousel_media[0]->image_versions2->candidates[0]->url;
							$type = "Carousel";
							break;

						case 2:
							//$media_temp = explode("?", $row->video_versions[0]->url);
							$media_temp[0] = $row->video_versions[0]->url;
							$media = array($media_temp[0]);
							$bg = $row->image_versions2->candidates[0]->url;
							$type = "Video";
							break;

						default:
							//$media_temp = explode("?", $row->image_versions2->candidates[0]->url);
							$media_temp[0] = $row->image_versions2->candidates[0]->url;
							$media = array($media_temp[0]);
							$bg = $row->image_versions2->candidates[0]->url;
							$type = "Photo";
							break;
					}

					$caption = is_object($row->caption)?$row->caption->text:"";
					$singleMedia = htmlspecialchars(json_encode([$media[0]]), ENT_QUOTES, 'UTF-8');
					//$media = json_encode($media);
					$media = htmlspecialchars(json_encode($media), ENT_QUOTES, 'UTF-8');
					
					$data .='
					<div class="col-md-3 col-sm-4 mb15">
						<div class="item" style="background-image: url('.$bg.');">';
							if($row->media_type == 2){
								$data .= '<div class="btn-play"><i class="fa fa-play" aria-hidden="true"></i></div>';
							}
							$data .= '<div class="type">'.$type.'</div>
							<img class="fake-bg popoverCaption" data-content="'.$caption.'" data-delay-show="300" data-title="Caption" src="'.BASE.'assets/img/transparent.png">
							<div class="list-option btn-group bg-white" role="group">
								<div class="col-md-4 col-sm-4 col-xs-4 btn btn-default btn-sm btnGetInstagramMedia" data-credit="'.$row->user->username.'" data-media="'.$singleMedia.'" data-caption="'.$caption.'" data-type="photo">Post</div>
								<div class="col-md-4 col-sm-4 col-xs-4 btn btn-default btn-sm btnGetInstagramMedia" data-credit="'.$row->user->username.'" data-media="'.$media.'" data-caption="'.$caption.'" data-type="story">Story</div>
								<div class="col-md-4 col-sm-4 col-xs-4 btn btn-default btn-sm btnGetInstagramMedia" data-credit="'.$row->user->username.'" data-media="'.$media.'" data-caption="'.$caption.'" data-type="carousel">Carousel</div>
							</div>
						</div>
					</div>';
				}
			
		}
		ms([
			'status'=>'success',
			'next_max_id'=>$next_max_id,
			'data'=>$data
		]);
	}

	
	public function ajax_next_search(){
		$res = $this->ajax_search_media(true);
		$data = '';
		$next_max_id = null;
		if($res['status']=='success'){
			$result = $res['result'];
			if(!empty($result)){
				$next_max_id = $result->next_max_id;
				foreach ($result->items as $row) {
					switch ($row->media_type) {
						case 8:
							$media = array();
							foreach ($row->carousel_media as $value) {
								//$media_temp = explode("?", $value->image_versions2->candidates[0]->url);
								$media_temp[0] = $value->image_versions2->candidates[0]->url;
								$media[] = $media_temp[0];
							}
							$bg = $row->carousel_media[0]->image_versions2->candidates[0]->url;
							$type = "Carousel";
							break;

						case 2:
							//$media_temp = explode("?", $row->video_versions[0]->url);
							$media_temp[0] = $row->video_versions[0]->url;
							$media = array($media_temp[0]);
							$bg = $row->image_versions2->candidates[0]->url;
							$type = "Video";
							break;

						default:
							//$media_temp = explode("?", $row->image_versions2->candidates[0]->url);
							$media_temp[0] = $row->image_versions2->candidates[0]->url;
							$media = array($media_temp[0]);
							$bg = $row->image_versions2->candidates[0]->url;
							$type = "Photo";
							break;
					}

					$caption = is_object($row->caption)?$row->caption->text:"";
					$singleMedia = htmlspecialchars(json_encode([$media[0]]), ENT_QUOTES, 'UTF-8');
					//$media = json_encode($media);
					$media = htmlspecialchars(json_encode($media), ENT_QUOTES, 'UTF-8');
					
					$data .='
					<div class="col-md-3 col-sm-4 mb15">
						<div class="item" style="background-image: url('.$bg.');">';
							if($row->media_type == 2){
								$data .= '<div class="btn-play"><i class="fa fa-play" aria-hidden="true"></i></div>';
							}
							$data .= '<div class="type">'.$type.'</div>
							<img class="fake-bg popoverCaption" data-content="'.$caption.'" data-delay-show="300" data-title="Caption" src="'.BASE.'assets/img/transparent.png">
							<div class="list-option btn-group bg-white" role="group">
								<div class="col-md-4 col-sm-4 col-xs-4 btn btn-default btn-sm btnGetInstagramMedia" data-credit="'.$row->user->username.'" data-media="'.$singleMedia.'" data-caption="'.$caption.'" data-type="photo">Post</div>
								<div class="col-md-4 col-sm-4 col-xs-4 btn btn-default btn-sm btnGetInstagramMedia" data-credit="'.$row->user->username.'" data-media="'.$media.'" data-caption="'.$caption.'" data-type="story">Story</div>
								<div class="col-md-4 col-sm-4 col-xs-4 btn btn-default btn-sm btnGetInstagramMedia" data-credit="'.$row->user->username.'" data-media="'.$media.'" data-caption="'.$caption.'" data-type="carousel">Carousel</div>
							</div>
						</div>
					</div>';
				}
			}
		}
		$res['data'] =$data;
		$res['next_max_id'] =$next_max_id;
		ms($res);
	}

	public function ajax_search_marketplace($return=false){
		$url = trim(post("url"));
		$type    = post("type");
		$ids = post("ids");
		$data    = array();
		$next_max_id= post('next_max_id');
		$page= (int) post('page');
		if($page < 1) $page =1;
		$data['status']='error'; 
		
		//$instagram_account = $this->model->get("*", $this->tb_accounts, "uid = '".session("uid")."' AND status = 1", "rand()");
		$instagram_account = $this->db->get_where($this->tb_accounts, ['uid'=>session("uid"), "status"=>1, "ids"=>$ids])->row();
		if(!empty($instagram_account)){
			if(!empty($url) && !empty($type)){
				$url = str_replace(['://www.m.', '://m.'],['://www.','://'],$url); //force mobile to use desktop site
				$this->load->library('marketplace', null, 'market');
				$this->market->setProxy($instagram_account->proxy);
				$parse_url = parse_url($url);
				if(filter_var($url, FILTER_VALIDATE_URL)){
					$expPath = explode('/', $parse_url['path']);
					if(($type=='shopee' && preg_match('/-i\.[0-9]+\.[0-9]+/', $url)) || ($type=='tokopedia' && count($expPath)>2) || ($type=='bukalapak' && isset($expPath[1]) && $expPath[1]!='u')){
						$resp = $this->market->productInfo($url);
						$method='produk';
					}else{
						$resp = $this->market->tokoProducts($url, [$page,$page]);
						$method='toko';
					}
				}else{
					$url = $type=='shopee'?'https://shopee.co.id/'.$url:'https://www.tokopedia.com/'.$url;
					$resp = $this->market->tokoProducts($url, [$page,$page]);
					$method='toko';
				}
				
				$arr = $resp;
				if(!empty($arr)){
					$html = '';
					if($page==1) $html .= '<div class="instagram_search_media"><div class="row">';
					foreach($arr['data'] as $row){
						$media = htmlspecialchars(json_encode($row['images']), ENT_QUOTES, 'UTF-8');
						$html .='
						<div class="col-md-3 col-sm-4 mb15">
							<div class="item" style="background-image: url('.$row['image'].');">
								<div class="type">'.$type.'</div>
								<img class="fake-bg popoverCaption" data-content="'.$row['title'].'" data-delay-show="300" data-title="Caption" src="'.BASE.'assets/img/transparent.png">
								<div class="list-option btn-group bg-white" role="group" data-metode="'.$method.'" data-media="'. $media .'"> 
									<div class="col-md-4 col-sm-4 col-xs-4 btn btn-default btn-sm btnGetMarketplaceProduct" data-url="'. $row['url'] .'" data-type="photo">Post</div>
									<div class="col-md-4 col-sm-4 col-xs-4 btn btn-default btn-sm btnGetMarketplaceProduct" data-url="'.$row['url'].'" data-type="story">Story</div>
									<div class="col-md-4 col-sm-4 col-xs-4 btn btn-default btn-sm btnGetMarketplaceProduct" data-url="'.$row['url'].'" data-type="carousel">Carousel</div>
								</div>
							</div>
						</div>';
					}
					if($page==1) $html .= '</div></div>';
					$data["data"] = $html;
					$data['load_more'] = (int) $arr['data'][0]['more_page'];
					$data['page'] = $page+1;
					$data['status']='success';
				}else{
					$data['message'] = 'Failed to get page';
				}
			}else{
				$data['message'] = 'Url & type cannot empty';
			}
		}else{
			$data['message'] = 'You dont have account';
		}
		
		ms($data);
	}
	
	public function ajax_marketplace_prepare__product(){
		$url = post('url');
		$type = post('type');
		$markup = (int) post('markup');
		$result['ok']=0;
		$instagram_account = $this->model->get("*", $this->tb_accounts, "uid = '".session("uid")."' AND status = 1", "rand()");
		if(empty($instagram_account)){
			ms(['ok'=>0, 'msg'=>'you dont have account']);
		}
		
		if(filter_var($url, FILTER_VALIDATE_URL)){
			$this->load->library('marketplace', null, 'market');
			$this->market->setProxy($instagram_account->proxy);
			$resp = $this->market->productInfo($url, false);
			$product = isset($resp['data'][0])?$resp['data'][0]:$resp['data']; //ngantuk, ganti ini ntar
			if(isset($product['price']['post_max']) && $product['price']['original'] != $product['price']['post_max']){
				$price = number_format($product['price']['original'] * ((100+$markup)/100), 0, ',','.') ." - ". number_format($product['price']['post_max'] * ((100+$markup)/100), 0,',','.');
			}else{
				$price = number_format($product['price']['original'] * ((100+$markup)/100),0,',','.');
			}
			$desc = preg_replace('#<br\s*/?>#i', "\n", strip_tags($product['description'], '<br>'));
			$desc = trim($desc);
			$result['post']= $product['title'] ."\n\n".
			"Harga Rp ". $price."\n".
			$desc
			;
			$result['images']=$product['images'];
			$result['ok']=1;
		}else{
			$result['msg']='Url not valid';
		}
		ms($result);
	}
	
	public function check_watermark(){
		$uid = $this->input->post("uid");
		if(!empty(get_setting("watermark_image", "", $uid))){
			ms(['status'=>'success']);
		}else{
			ms(['status'=>'error', 'message'=>'Anda tidak memiliki watermark.']);
		}
	}

	public function ajax_post(){
		$accounts  = $this->input->post("account");
		$media     = $this->input->post("media");
		$type      = post("type");
		$time_post = post("time_post");
		$caption   = post("caption");
		$comment   = post("comment");
		$watermark = (bool) post("watermark");

		if(!$accounts){
			ms(array(
	        	"status"  => "error",
	        	"stop"    => true,
	        	"message" => lang('please_select_an_account')
	        ));
		}

		if(!$media && empty($media)){
			ms(array(
	        	"status"  => "error",
	        	"stop"    => true,
	        	"message" => lang('please_select_a_media')
	        ));
		}

		if(!Instagram_Post_Type($type)){
			ms(array(
	        	"status"  => "error",
	        	"stop"    => true,
	        	"message" => lang('please_select_a_post_type')
	        ));
		}
		
		
		if($type=='carousel' && count($media) <2){
			ms(array(
	        	"status"  => "error",
	        	"stop"    => true,
	        	"message" => 'You need post more than 1 media to use album/carousel type'
	        ));
		}

		if(!post("advance")){
			$comment   = "";
		}
		
		if(!post("is_schedule")){
			if(!empty($accounts)){
				foreach ($accounts as $account_id) {
					$instagram_account = $this->model->get("id, username, password, proxy, default_proxy", $this->tb_accounts, "ids = '".$account_id."' AND uid = '".session("uid")."' AND status = 1");
					if(!empty($instagram_account)){
						$data = array(
							"ids" => ids(),
							"uid" => session("uid"),
							"account" => $instagram_account->id,
							"type" => post("type"),
							"data" => json_encode(array(
										"media"   => $media,
										"caption" => $caption,
										"comment" => $comment,
										"watermark" => $watermark
									)),
							"time_post" => NOW,
							"delay" => 0,
							"time_delete" => 0,
							"changed" => NOW,
							"created" => NOW
						);
						
						$proxy_data = get_proxy($this->tb_accounts, $instagram_account->proxy, $instagram_account);	
						try {
							$ig = new InstagramAPI($instagram_account->username, $instagram_account->password, $proxy_data->use);
							$tmp_data = $data;
							if(preg_match('/\{+.+\}/', $caption) || preg_match('/\{+.+\}/', $comment)){
								$theData = json_decode($tmp_data['data']);
								if(isset($theData->caption) && !empty($theData->caption)){
									$theData->caption = Modules::run('instagram/manual_activity/parseMessage', $theData->caption, session('uid'), $ig);
								}
								if(isset($theData->comment) && !empty($theData->comment)){
									$theData->comment = Modules::run('instagram/manual_activity/parseMessage', $theData->comment, session('uid'), $ig);
								}
								$tmp_data['data'] = json_encode($theData);
							}
							$result = $ig->post($tmp_data);
						} catch (Exception $e) {
							ms(array(
								"status" => "error",
								"message" => $e->getMessage()
							));
						}
						
						if(is_array($result)){
							$data['status'] = 3;
							$data['result'] = $result['message'];

							//Save report
							update_setting("ig_post_error_count", get_setting("ig_post_error_count", 0) + 1);
							update_setting("ig_post_count", get_setting("ig_post_count", 0) + 1);

							$this->db->insert($this->tb_posts, $data);

							ms($result);
						}else{
							$data['status'] = 2;
							$data['result'] = json_encode(array("message" => "successfully", "id" => $result->id, "url" => "https://www.instagram.com/p/".$result->code));

							//Save report
							update_setting("ig_post_success_count", get_setting("ig_post_success_count", 0) + 1);
							update_setting("ig_post_count", get_setting("ig_post_count", 0) + 1);
							update_setting("ig_post_{$type}_count", get_setting("ig_post_{$type}_count", 0) + 1);

							$this->db->insert($this->tb_posts, $data);

						 	ms(array(
					        	"status"  => "success",
					        	"message" => lang('post_successfully')
					        ));
						}

					}else{
						ms(array(
				        	"status"  => "error",
				        	"message" => lang("instagram_account_not_exists")
				        ));
					}
				}
			}

			ms(array(
	        	"status"  => "error",
	        	"message" => lang("processing_is_error_please_try_again")
	        ));
		}else{
			if(!empty($accounts)){
				foreach ($accounts as $account_id) {
					$instagram_account = $this->model->get("id, username, password", $this->tb_accounts, "ids = '".$account_id."' AND uid = '".session("uid")."'");
					if(!empty($instagram_account)){
						$data = array(
							"ids" => ids(),
							"uid" => session("uid"),
							"account" => $instagram_account->id,
							"type" => post("type"),
							"data" => json_encode(array(
										"media"   => $media,
										"caption" => $caption,
										"comment" => $comment,
										"watermark" => $watermark
									)),
							"time_post" => get_timezone_system($time_post),
							"delay" => 0,
							"time_delete" => 0,
							"status" => 1,
							"changed" => NOW,
							"created" => NOW
						);
						if(preg_match('/\{+.+\}/', $caption) || preg_match('/\{+.+\}/', $comment)){
							$proxy_data = get_proxy($this->tb_accounts, $instagram_account->proxy, $instagram_account);	
							try {
								$ig = new InstagramAPI($instagram_account->username, $instagram_account->password, $proxy_data->use);
								$tmp_data = $data;
								$theData = json_decode($tmp_data['data']);
								if(isset($theData->caption) && !empty($theData->caption)){
									$theData->caption = Modules::run('instagram/manual_activity/parseMessage', $theData->caption, session('uid'), $ig);
								}
								if(isset($theData->comment) && !empty($theData->comment)){
									$theData->comment = Modules::run('instagram/manual_activity/parseMessage', $theData->comment, session('uid'), $ig);
								}
								$data['data'] = json_encode($theData);
							} catch (Exception $e) {
								ms(array(
									"status" => "error",
									"message" => $e->getMessage()
								));
							}
						}
						
						$this->db->insert($this->tb_posts, $data);
					}
				}
			}

			ms(array(
	        	"status"  => "success",
	        	"message" => lang('add_schedule_successfully')
	        ));
		}
	}

	/****************************************/
	/* CRON                                 */
	/* Time cron: once_per_minute           */
	/****************************************/
	public function cron(){
		$schedule_list = $this->db->select('post.*, account.username, account.password, account.proxy, account.default_proxy')
		->from($this->tb_posts." as post")
		->join($this->tb_accounts." as account", "post.account = account.id")
		->where("(post.status = 1 OR post.status = 4) AND post.time_post <= '".NOW."' AND account.status = 1")->limit(5,0)->get()->result();

		if(!empty($schedule_list)){
			foreach ($schedule_list as $key => $schedule) {
				if(!permission("instagram/post", $schedule->uid)){
					$this->db->delete($this->tb_posts, array("uid" => $schedule->uid, "time_post >=" => NOW));
				}
				
				$proxy_data = get_proxy($this->tb_accounts, $schedule->proxy, $schedule);
				try {
					$ig = new InstagramAPI($schedule->username, $schedule->password, $proxy_data->use);
					$result = $ig->post($schedule);
					if(isset($result->id)){
						//check media benar2 ke upload blm
						$checkMedia = $ig->get_mediainfo($result->id);
						if(empty($checkMedia)){
							$result = array(
								"status" => "error",
								"message" => 'upload reported succcess but not found'
							);
						}else{
							//check caption
							$realCaption = $schedule->data->caption;
							if(!empty($realCaption) && empty($checkMedia->caption->text)){
								//delete media
								$ig->delete_media($checkMedia->id);
								
								$result = array(
									"status" => "error",
									"message" => 'upload reported succcess but caption empty'
								);
							}
							
						}
					}
				} catch (Exception $e) {
					$result = array(
						"status" => "error",
						"message" => $e->getMessage()
					);
				}
				
				$data = array();
				if(is_array($result) && $result["status"] == "error"){
					$data['status'] = 3;
					$data['result'] = json_encode(array("message" => $result["message"]));

					//
					update_setting("ig_post_error_count", get_setting("ig_post_error_count", 0, $schedule->uid) + 1, $schedule->uid);
					update_setting("ig_post_count", get_setting("ig_post_count", 0, $schedule->uid) + 1, $schedule->uid);
					
					//Save report
					$this->db->update($this->tb_posts, $data, "id = '{$schedule->id}'");

					echo $result["message"]."<br/>";
				}else{

					//Save report
					update_setting("ig_post_success_count", get_setting("ig_post_success_count", 0, $schedule->uid) + 1, $schedule->uid);
					update_setting("ig_post_count", get_setting("ig_post_count", 0, $schedule->uid) + 1, $schedule->uid);
					update_setting("ig_post_{$schedule->type}_count", get_setting("ig_post_{$schedule->type}_count", 0, $schedule->uid) + 1, $schedule->uid);

					$data['status'] = 2;
					$data['result'] = json_encode(array("message" => "successfully", "id" => $result->id, "url" => "https://www.instagram.com/p/".$result->code));
					$this->db->update($this->tb_posts, $data, "id = '{$schedule->id}'");

					echo '<a target=\'_blank\' href=\'https://instagram.com/p/'.$result->code.'\'>'.lang('post_successfully').'</a><br/>';
				}
			}
		}else{
			
		}
	}
	//****************************************/
	//               END CRON                */
	//****************************************/

	/****************************************/
	/*           SCHEDULES POST             */
	/****************************************/
	public function block_schedules_xml(){
		$template = array(
			"controller" => "instagram",
			"color" => "#d62976",
			"name"  => lang("auto_post"),
			"icon"  => "fa fa-instagram"
		);

		echo Modules::run("schedules/block_schedules_xml", $template, $this->tb_posts);
	}

	public function schedules(){
		echo Modules::run("schedules/schedules", "username", $this->tb_posts, $this->tb_accounts);
	}
	
	public function ajax_edit_schedules(){
		$ids = post('id');
		$uid = session('uid');
		$q = $this->db->get_where('instagram_posts', ['ids'=>$ids, 'uid'=>$uid]);
		if($q->num_rows()>0){
			$d = json_decode($q->row()->data);
			ms([
				'status'=>'success',
				'id'=>$q->row()->ids,
				'caption'=>$d->caption,
				'time'=>$q->row()->time_post,
			]);
		}else{
			ms([
				'status'=>'error',
				'message'=>'Post not found',
			]);
		}
	}
	
	public function ajax_update_schedules(){
		$ids = post('id');
		$caption = post('caption');
		$time_post = post('time_post');
		if(empty($time_post)) $time_post = NOW;
		$uid = session('uid');
		
		
		$q = $this->db->get_where('instagram_posts', ['ids'=>$ids, 'uid'=>$uid]);
		if($q->num_rows()>0){
			$d = json_decode($q->row()->data);
			$d->caption = $caption;
			$newdata = json_encode($d);
			$this->db->update('instagram_posts', ['time_post'=>$time_post, 'data'=>$newdata, 'status'=>1], ['ids'=>$ids, 'uid'=>$uid]);
			ms([
				'status'=>'success',
				'message'=>'reschedule success',
			]);
		}else{
			ms([
				'status'=>'error',
				'message'=>'Post not found',
			]);
		}
	}
	
	
	public function ajax_post_now(){
		$ids = post('id');
		$caption = post('caption');
		$time_post = post('time_post');
		if(empty($time_post)) $time_post = NOW;
		$uid = session('uid');
		
		
		$q = $this->db->get_where('instagram_posts', ['ids'=>$ids, 'uid'=>$uid]);
		if($q->num_rows()>0){
			$d = json_decode($q->row()->data);
			$d->caption = $caption;
			$newdata = json_encode($d);
			$this->db->update('instagram_posts', ['time_post'=>$time_post, 'data'=>$newdata, 'status'=>1], ['ids'=>$ids, 'uid'=>$uid]);
			
			
			$schedule_list = $this->db->select('post.*, account.username, account.password, account.proxy, account.default_proxy')
			->from($this->tb_posts." as post")
			->join($this->tb_accounts." as account", "post.account = account.id")
			->where("post.ids",$ids)->where('post.uid', $uid)->get()->result();

			if(!empty($schedule_list)){
				foreach ($schedule_list as $key => $schedule) {
					if(!permission("instagram/post", $schedule->uid)){
						$this->db->delete($this->tb_posts, array("uid" => $schedule->uid, "time_post >=" => NOW));
					}
					
					$proxy_data = get_proxy($this->tb_accounts, $schedule->proxy, $schedule);
					try {
						$ig = new InstagramAPI($schedule->username, $schedule->password, $proxy_data->use);
						$result = $ig->post($schedule);
					} catch (Exception $e) {
						$result = array(
							"status" => "error",
							"message" => $e->getMessage()
						);
					}
					
					$data = array();
					$data['changed'] = NOW;
					if(is_array($result) && $result["status"] == "error"){
						$data['status'] = 3;
						$data['result'] = json_encode(array("message" => $result["message"]));
						$retry = $schedule->retry +1;
						$waktuUlang = 10;
						$maxRetry = 2;
						$data['retry'] = $retry;
						if($schedule->retry >=$maxRetry){
							$data['status'] = 1;
							$data['time_post'] = strtotime($schedule->time_post)+($waktuUlang*60);
							$data['result'] = json_encode(array("message" => 'Retry from error: '.$result["message"]));
						}
						
						//
						update_setting("ig_post_error_count", get_setting("ig_post_error_count", 0, $schedule->uid) + 1, $schedule->uid);
						update_setting("ig_post_count", get_setting("ig_post_count", 0, $schedule->uid) + 1, $schedule->uid);
						
						//Save report
						$this->db->update($this->tb_posts, $data, "id = '{$schedule->id}'");
						
						ms([
							'status'=>'error',
							'message'=>$result["message"],
						]);
					}else{

						//Save report
						update_setting("ig_post_success_count", get_setting("ig_post_success_count", 0, $schedule->uid) + 1, $schedule->uid);
						update_setting("ig_post_count", get_setting("ig_post_count", 0, $schedule->uid) + 1, $schedule->uid);
						update_setting("ig_post_{$schedule->type}_count", get_setting("ig_post_{$schedule->type}_count", 0, $schedule->uid) + 1, $schedule->uid);

						$data['status'] = 2;
						$data['retry'] = 0;
						$data['result'] = json_encode(array("message" => "successfully", "id" => $result->id, "url" => "https://www.instagram.com/p/".$result->code));
						$this->db->update($this->tb_posts, $data, "id = '{$schedule->id}'");

						ms([
							'status'=>'success',
							'message'=>'Post successfully',
						]);
					}
				}
			}

		}else{
			ms([
				'status'=>'error',
				'message'=>'Post not found',
			]);
		}
	}
	

	public function ajax_schedules(){
		echo Modules::run("schedules/ajax_schedules", "username", $this->tb_posts, $this->tb_accounts);
	}

	public function ajax_delete_schedules(){
		echo Modules::run("schedules/ajax_delete_schedules", $this->tb_posts);
	}
	//****************************************/
	//         END SCHEDULES POST            */
	//****************************************/
	
}