<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class payment_history extends MX_Controller {
	public $table;
	public $columns;
	public $module_name;
	public $module_icon;

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');

		//Config Module
		$this->table       = PAYMENT_HISTORY;
		$this->module_name = lang('payment_history');
		$this->module_icon = "fa ft-credit-card";
		$this->columns = array(
			"package"              => lang('package'),
			"account"              => lang('user'),
			"type"                 => lang("type"),
			"transaction_id"       => lang('transaction_id'),
			"plan"                 => lang('plan'),
			"amount"               => lang("amount").' ('.get_option('payment_currency').')',
			"created"              => lang("created"),
			"status" => 'status',
			'valid_until' => 'expired payment'
		);
	}

	public function index(){
		$page        = (int)get("p");
		$limit       = 50;
		$result      = $this->model->getList($this->table, $this->columns, $limit, $page);
		$total       = $this->model->getList($this->table, $this->columns, -1, -1);
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
			"base_url"   => cn(get_class($this).$query_string), 
			"total_rows" => $total_final, 
			"per_page"   => $limit
		);

		$this->pagination->initialize($configs);

		$data = array(
			"columns" => $this->columns,
			"result"  => $result,
			"total"   => $total_final,
			"page"    => $page,
			"limit"   => $limit,
			"module"  => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon
		);

		$this->template->build('index', $data);
	}

	public function export(){
		export_csv($this->table);
	}
	
	public function ajax_delete_item(){
		$this->model->delete($this->table, post("id"), false);
	}
	
	  public function update_package($userId, $package_new, $plan){
		$user = $this->model->get("*", USERS, "id = '".$userId."'");
		$new_days  = $plan==2?365:30;
		if(!empty($user)){
			$package_old = $this->model->get("*", PACKAGES, "id = '".$user->package."'");
			$package_id = $package_new->id;
			if(!empty($package_old)){
				if(strtotime(NOW) < strtotime($user->expiration_date)){
					$date_now = date("Y-m-d", strtotime(NOW));
					$date_expiration = date("Y-m-d", strtotime($user->expiration_date));
					$diff = abs(strtotime($date_expiration) - strtotime($date_now));
					$left_days = floor($diff/86400);

					if($plan == 2){
						$day_added = round(($package_old->price_annually/$package_new->price_annually)*$left_days);
					}else{
						$day_added = round(($package_old->price_monthly/$package_new->price_monthly)*$left_days);
					}

					$total_day = $new_days + $day_added;
					$expiration_date = date('Y-m-d', strtotime(NOW." +".$total_day." days"));
				}else{
					$expiration_date = date('Y-m-d', strtotime(NOW." +".$new_days." days"));
				}
			}else{
				$expiration_date = date('Y-m-d', strtotime(NOW." +".$new_days." days"));
			}

			$data = array(
				"package"      => $package_id,
				"expiration_date" => $expiration_date,
				"permission"=>$package_new->permission
			);

			$this->db->update(USERS, $data, "id = '".$userId."'");
		}
	}
  
	public function ajax_update_status(){
		//$this->model->update_status($this->table, post("id"), false);
    $result=[
      'status'=> 'error',
      'tag'=> 'tag-danger',
      'text'=> 'Error',
      'message'=> 'Error'
    ];
    $id = post('id');
    $q = $this->db->get_where(PAYMENT_HISTORY, ['ids'=>$id]);
    if($q->num_rows()>0){
      if($q->row()->status==1){
        $status=0;
        $this->db->update(PAYMENT_HISTORY, ['status'=>$status],['ids'=>$id]);
		Modules::run('users/update_user_kirim_email', $q->row()->uid);
		
        $result['status']='success';
        $result['text']='Disabled';
        $result['message']='Update status successfully';
      }else{
        $status=1;
		if(!empty($q->row()->ref_uid) && $q->row()->ref_claim==0){
			Modules::run('payment/send_withdraw', $q->row()->ref_uid, $q->row()->amount);
		}
        $this->db->update(PAYMENT_HISTORY, ['status'=>$status, 'ref_claim'=>1],['ids'=>$id]);
        $paymentId = $q->row()->id;
        $package = $this->model->get("*", PACKAGES, "id = '".$q->row()->package."'  AND status = 1");
        $this->update_package($q->row()->uid, $package, $q->row()->plan);
        $this->model->send_email(get_option("email_payment_subject", ""), get_option("email_payment_content", ""), $q->row()->uid);
		Modules::run('users/update_user_kirim_email', $q->row()->uid);
		
        $result['status']='success';
        $result['tag']='tag-success';
        $result['text']='Enabled';
        $result['message']='Update status successfully';
      }
      
    }else{
		$result['message']='Id not found';
    }
		ms($result);
	}
	

}