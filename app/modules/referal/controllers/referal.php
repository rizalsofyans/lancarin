<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class referal extends MX_Controller {

	
	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
		$this->load->helper('string');
	}

	
	
	function ref($code=null){
		if(!empty($code)){
			if(empty(session('refcode'))){
				$this->session->set_userdata('refcode', $code);
			}
		}
		
		redirect();
	}
	
	public function referal_accounts(){
		$module_name = 'Referal Account';
		$module_icon = "fa ft-users";
		$columns = array(
			"fullname" => 'Nama',
			"whatsapp" => 'Whatsapp',
			"email" => 'Email',
			"name" => 'Paket',
			"price_monthly" => 'Harga',
			'expiration_date' => 'Expired'
		);
		
		$page        = (int)get("p");
		$limit       = 50;
		$result      = $this->model->getTableUserReferal($columns, $limit, $page);
		$total       = $this->model->getTableUserReferal($columns, -1, -1);
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
			"columns" => $columns,
			"result"  => $result,
			"total"   => $total_final,
			"page"    => $page,
			"limit"   => $limit,
			"module"  => get_class($this).'/'. __FUNCTION__,
			"module_name" => $module_name,
			"module_icon" => $module_icon
		);

		$this->template->build('referal_accounts', $data);
	}
	
	public function referal_history(){
		$module_name = 'Referal History';
		$module_icon = "fa ft-users";
		$columns = array(
			"created"              => 'Tanggal',
			"transaction_id"              => 'Invoice',
			"fullname"              => 'Nama',
			"name"              => 'Paket',
			"nilai"                 => 'Nilai',
		);
		
		$page        = (int)get("p");
		$limit       = 50;
		$result      = $this->model->getTableReferalHistory($columns, $limit, $page);
		$total       = $this->model->getTableReferalHistory($columns, -1, -1);
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
			"columns" => $columns,
			"result"  => $result,
			"total"   => $total_final,
			"page"    => $page,
			"limit"   => $limit,
			"module"  => get_class($this).'/'. __FUNCTION__,
			"module_name" => $module_name,
			"module_icon" => $module_icon
		);

		$this->template->build('referal_history', $data);
	}
	
	
	
	public function withdraw_history(){
		$module_name = 'Withdraw History';
		$module_icon = "fa ft-money";
		$columns = array(
			"id"              => 'Id',
			"code"              => 'Invoice',
			"amount"              => 'Nilai',
			"stat"              => 'Status',
			"log_created"                 => 'Tanggal',
		);
		
		$page        = (int)get("p");
		$limit       = 50;
		$result      = $this->model->getTableUserWithdraw($columns, $limit, $page);
		$total       = $this->model->getTableUserWithdraw($columns, -1, -1);
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
			"columns" => $columns,
			"result"  => $result,
			"total"   => $total_final,
			"page"    => $page,
			"limit"   => $limit,
			"module"  => get_class($this).'/'. __FUNCTION__,
			"module_name" => $module_name,
			"module_icon" => $module_icon
		);

		$this->template->build('referal_history', $data);
	}
	
	private function update_status_withdraw(){
		$result=[
      'status'=> 'error',
      'tag'=> 'tag-danger',
      'text'=> 'Error',
      'message'=> 'Error'
    ];
    $id = post('id');
	$q = $this->db->get_where('general_withdraw_history', ['id'=>$id, 'uid'=>session('uid')]);
    if($q->num_rows()>0){
      if($q->row()->status==1){
        $status=0;
        $this->db->update('general_withdraw_history', ['status'=>$status, 'log_modified'=>NOW],['id'=>$id]);
        $result['status']='success';
        $result['text']='Disable';
        $result['message']='Update status successfully';
      }else{
        $status=1;
        $this->db->update('general_withdraw_history', ['status'=>$status, 'log_modified'=>NOW],['id'=>$id]);
        $result['status']='success';
        $result['tag']='tag-success';
        $result['text']='Enable';
        $result['message']='Update status successfully';
      }
      
    }else{
		$result['message']='Id not found';
    }
		ms($result);
	}
	
	private function delete_withdraw(){
		$listid = post('id');
		if(!is_array($listid)){
			$listid=[$listid];
		}
		$userId=session('uid');
		foreach($listid as $id){
			$this->db->delete('general_withdraw_history', ['id'=>$id, 'uid'=>$userId]);
		}
		
		$result['status']='success';
		$result['tag']='tag-success';
		//$result['text']='Enable';
		$result['message']='Delete data successfully';
	
		ms($result);
	}
	
	public function withdraw_claim($ajax=null){
		if(session('uid') !=1) redirect();
		if($ajax=='ajax_delete_item'){
			$this->delete_withdraw();
		}elseif($ajax=='ajax_update_status'){
			$this->update_status_withdraw();
		}
		
		$module_name = 'Withdraw Claim';
		$module_icon = "fa ft-money";
		$columns = array(
			"id"              => 'Id',
			"code"              => 'Invoice',
			"nilai"              => 'Nilai',
			"balance"              => 'Saldo',
			"status"              => 'Status',
			"log_modified"        => 'Modified',
			"log_created"        => 'Created',
		);
		
		$page        = (int)get("p");
		$limit       = 50;
		$result      = $this->model->getTableWithdrawClaim($columns, $limit, $page);
		$total       = $this->model->getTableWithdrawClaim($columns, -1, -1);
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
			"columns" => $columns,
			"result"  => $result,
			"total"   => $total_final,
			"page"    => $page,
			"limit"   => $limit,
			"module"  => get_class($this).'/'. __FUNCTION__,
			"module_name" => $module_name,
			"module_icon" => $module_icon
		);

		$this->template->build('withdraw_claim', $data);
	}
	
	
	public function index(){
		
		$data = array(
			'refcode'=> $this->get_referal_info()
		);

		$this->template->build('index', $data);
	}
	
	
	
	public function get_referal_info(){
		$userId = session('uid');
		$q=$this->db->select('b.*')->from('general_users a')->join('general_referal b', 'a.id=b.uid')->where('a.id', $userId)->get();
		
		if($q->num_rows()==0){
			$nama = $bank = $norek = null;
			
			$refCode = $this->generate_code();
			$refcodeurl = site_url('ref/'.$refCode);
			$balance=0;
			$percent = get_option('default_referal_percent', 20);
			$bitly ='';
			$this->load->helper('bitly');
			$params['access_token'] = get_option('api_bitlink');
			$params['longUrl'] = $refcodeurl;
			$resp = bitly_get('shorten', $params);
			if($resp['status_code']==200){
				$bitly=$resp['data']['url'];
			}
			$this->db->insert('general_referal', ['code'=>$refCode, 'uid'=>$userId, 'bitly'=>$bitly, 'percent'=>$percent]);
		}else{
			$nama = $q->row()->nama;
			$bank = $q->row()->bank;
			$norek = $q->row()->norek;
			$refCode = $q->row()->code;
			$bitly = $q->row()->bitly;
			$percent = $q->row()->percent;
			$balance = $q->row()->balance;
			$refcodeurl = site_url('ref/'.$refCode);
		}
		return ['code'=>$refCode, 'url'=> $refcodeurl, 'bitly'=>$bitly, 'balance'=>$balance, 'nama'=>$nama, 'bank'=>$bank, 'norek'=>$norek, 'percent'=>$percent];
	}
	
	private function generate_code(){
		while(true){
			$refCode = random_string('alnum', 5);
			$q = $this->db->get_where('general_referal', ['code'=>$refCode]);
			if($q->num_rows()==0) return $refCode;
		}
	}
	
	public function update_bank(){
		$bank = post('bank');
		$nama = post('nama');
		$norek = post('norek');
		$result['ok']=0;
		if(!empty($bank) && !empty($nama) &&!empty($norek)){
			$this->db->update('general_referal', ['bank'=>$bank, 'nama'=>$nama, 'norek'=>$norek],['uid'=>session('uid')]);
			$result['ok']=1;
		}else{
			$result['msg']='Input tidak boleh kosong';
		}
		ms($result);
	}
	
	public function create_withdraw(){
		$result['ok']=0;
		$nilai = intval(post('nilai'));
		$keterangan = post('keterangan');
		$minWithdraw = get_option('min_withdraw');
		if($nilai > 0 && $nilai >= $minWithdraw ){
			$userId = session('uid');
			$q=$this->db->select('a.fullname, a.email, b.*')->from('general_users a')->join('general_referal b', 'a.id=b.uid')->where('a.id', $userId)->get();
			if($q->num_rows()==1){
				if(!empty($q->row()->bank) && !empty($q->row()->nama) && !empty($q->row()->norek) ){
					if($nilai <= $q->row()->balance){
						$invoice = 'WD-'.date('Ymd').'-'.$userId.'-';
						$p = $this->db->from('general_withdraw_history')->like('code', $invoice, 'after')->get();
						$num = $p->num_rows()+1;
						$invoice .= $num;
						$subject = 'Pengajuan Withdraw '.$invoice;
						$content = $q->row()->fullname .' ingin penarikan fee referal, sebagai berikut:<br><br>
						<b><u>Informasi Akun :</u></b>
						<ul>
						<li><b>Id akun : </b>'.$userId.'</li>
						<li><b>Nama akun : </b>'.$q->row()->fullname.'</li>
						<li><b>Email akun : </b>'.$q->row()->email.'</li>
						</ul>
						<b><u>Informasi Tagihan :</u></b>
						<ul>
						<li><b>Invoice : </b>'.$invoice.'</li>
						<li><b>Jumlah Total Balance : </b>Rp '.number_format($q->row()->balance, 0,',','.').'</li>
						<li><b>Jumlah Penarikan : </b>Rp '.number_format($nilai, 0,',','.').'</li>
						<li><b>Bank: </b>Bank '.$q->row()->bank.'</li>
						<li><b>Rekening : </b>'.$q->row()->norek.'</li>
						<li><b>Nama : </b>'.$q->row()->nama.'</li>
						<li><b>Keterangan : </b>'.$keterangan.'</li>
						</ul><br>
						Terima kasih.
						';
				
						$this->model->send_email($subject, $content, 1);
				
						$this->db->insert('general_withdraw_history',['code'=>$invoice, 'uid'=>$userId, 'amount'=>$nilai]);
						$this->db->update('general_referal', ['balance'=>$q->row()->balance-$nilai],['uid'=>$userId]);
				
						$result['ok']=1; 
					}else{
						$result['msg']='Nominal tidak boleh lebih dari '. $q->row()->balance;
					}
				}else{
					$result['msg']='Mohon isi informasi bank anda dengan lengkap';
				}
			}else{
				$result['msg'] = 'User tidak ditemukan';
			}
		}else{
			$result['msg']='Nominal tidak boleh kurang dari '. $minWithdraw;
		}
		ms($result);
	}

}