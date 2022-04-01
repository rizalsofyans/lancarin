<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class tools extends MX_Controller {

	public $max_size = 1*1024;
	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function index(){
		$data = array(
			'accounts' => $this->db->get_where('instagram_accounts', ['uid'=>session('uid'), 'status'=>1])->result()
		);

		$this->template->build('index', $data);
	}

	public function ajax_get_config(){
		$ids = post('ids');
		$q = $this->db->get_where('instagram_accounts', ['uid'=>session('uid'), 'ids'=>$ids]);
		if($q->num_rows()==0){
			ms(['status'=>'error', 'message'=>'Akun tidak ditemukan']);
		}
		$watermark = json_decode($q->row()->watermark, true);
		$data['position'] = isset($watermark['watermark_position'])?$watermark['watermark_position']:'lb';
		$data['size'] = isset($watermark['watermark_size'])?$watermark['watermark_size']:30;
		$data['opacity'] = isset($watermark['watermark_opacity'])?$watermark['watermark_opacity']:70;
		$data['image'] = isset($watermark['watermark_image'])&&!empty($watermark['watermark_image'])?$watermark['watermark_image'].'?t='.time():BASE."assets/img/bg-watermark-warna.png";
		
		ms([
			'status'=>'success',
			'data'=>$data
		]);
	}
	
	public function ajax_upload_watermark(){
		get_upload_folder();
		$size = post("size");
		$opacity = post("opacity");
		$position = post("position");
		$accounts = post("accounts");
		if(!isset($accounts[0]) || empty($accounts[0])){
			ms(['status'=>'error', 'message'=>'Pilih akun terlebih dahulu']);
		}
		$account= $accounts[0];
		$q = $this->db->get_where('instagram_accounts', ['uid'=>session('uid'), 'ids'=>$account]);
		if($q->num_rows()==0){
			ms(['status'=>'error', 'message'=>'Akun tidak ditemukan']);
		}
		$accountId = $q->row()->id;
		$originalWatermark = json_encode($q->row()->watermark, true);
		$types = $types = 'gif|jpg|jpeg|png';

		$config['upload_path']          = './assets/uploads/user'.session("uid");
        $config['allowed_types']        = $types;
        $config['max_size']             = $this->max_size;
        $config['max_width']            = 0;
        $config['max_height']           = 0;
        $config['encrypt_name']         = FALSE;
        $config['overwrite']         	= TRUE;
        $config['file_name']            = 'watermark_'.$accountId;

		$data = [
			'watermark_size'=>$size,
			'watermark_opacity'=>$opacity,
			'watermark_position'=>$position,
			'watermark_image'=>isset($originalWatermark['watermark_image'])?$originalWatermark['watermark_image']:'',
		];

        $this->load->library('upload', $config);
        
        if(isset($_FILES['files']['name'][0]) && !empty($_FILES['files']['name'][0])){
	        $files = $_FILES;
		    $files_num = count($_FILES['files']['name']);
		    for($i=0; $i< $files_num; $i++){
		        $_FILES['files']['name']= $files['files']['name'][$i];
		        $_FILES['files']['type']= $files['files']['type'][$i];
		        $_FILES['files']['tmp_name']= $files['files']['tmp_name'][$i];
		        $_FILES['files']['error']= $files['files']['error'][$i];
		        $_FILES['files']['size']= $files['files']['size'][$i];
		        
		        $this->model->get_storage("file", $_FILES['files']['size']/1024);
		        $this->upload->initialize($config);

		        if (!$this->upload->do_upload("files"))
		        {
	                ms(array(
	                	"status"  => "error",
	                	"message" => $this->upload->display_errors()
	                ));
		        }
		        else
		        {
		        	$info = (object)$this->upload->data();
					$data['watermark_image']= $config['upload_path']."/".$info->file_name;
		        }
		    }
			
        }
		$this->db->update('instagram_accounts', ['watermark'=>json_encode($data)],['uid'=>session('uid'), 'ids'=>$account]);
		ms(array(
			"status"  => "success",
			"message" => 'Berhasil'
		));
	}

	public function ajax_delete_watermark($ids){
		$accounts = [$ids];
		if(!isset($accounts[0]) || empty($accounts[0])){
			ms(['status'=>'error', 'message'=>'Pilih akun terlebih dahulu']);
		}
		$account= $accounts[0];
		$q = $this->db->get_where('instagram_accounts', ['uid'=>session('uid'), 'ids'=>$account]);
		if($q->num_rows()==0){
			ms(['status'=>'error', 'message'=>'Akun tidak ditemukan']);
		}
		$accountId = $q->row()->id;
		$watermarkFile = json_decode($q->row()->watermark,true);
		if(isset($watermarkFile['watermark_image']) && file_exists($watermarkFile['watermark_image'])){
			@unlink($watermarkFile['watermark_image']);
		}
		
		$data = [
			'watermark_size'=>30,
			'watermark_opacity'=>70,
			'watermark_position'=>'1b',
			'watermark_image'=>'',
		];
		$this->db->update('instagram_accounts', ['watermark'=>json_encode($data)],['uid'=>session('uid'), 'ids'=>$account]);

		ms(array(
        	"status"  => "success",
        	"message" => lang("delete_successfully")
        ));
	}
}