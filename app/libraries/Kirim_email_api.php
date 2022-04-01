<?php
class Kirim_email_api
{
	private $username;
	private $token;
	private $id_register=54285; //id_list_register_only
	private $id_paid=54286; //id_list_paid_member
	private $id_expired=54287; //id_list_expired
	
	public function __construct(){
		$this->username = get_option('kirim_email_username');
		$this->token = get_option('kirim_email_token');
	}
	
	private function get_list_id($name){
		if($name=='register') return $this->id_register;
		elseif($name=='paid') return $this->id_paid;
		elseif($name=='expired') return $this->id_expired;
		else die('list id not found');
	}
	
	public function delete_subscriber($email, $list_name){
		return $this->send_request('DELETE', 'subscriber/email/'.$email, ['List-Id: '.$this->get_list_id($list_name)]);
	}
	
	public function create_subscriber($email, $name, $list_name){
		$param=[
			'lists' => $this->get_list_id($list_name), 
			'email'=> $email,
			'full_name'=> $name
		];
		
		return $this->send_request('POST', 'subscriber', ["Content-Type: application/x-www-form-urlencoded"], $param);
	}
	
	private function send_request($method, $request, array $head = [], array $param=[])
	{
		
		$time = time();
		$generated_token = hash_hmac("sha256", $this->username . "::" . $this->token . "::" . $time, $this->token);
		$curl = curl_init();
		$headers = array(
			"Auth-Id: " . $this->username,
			"Auth-Token: " . $generated_token,
			"Timestamp: " . $time,
		);
		if (!empty($head)) {
			foreach($head as $header) {
				$headers[] = $header;
			}
		}
				
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.kirim.email/v3/" . $request,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => false,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_HTTPHEADER => $headers,
		));
		
		if($method=='POST' && !empty($param)){
			curl_setopt($curl,CURLOPT_POSTFIELDS ,http_build_query($param));
		}
		
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			// echo "cURL Error #:" . $err;
			return false;
		} else {
			$arr = json_decode($response, true);
			if(!empty($arr) && $arr['status']=='success')
			return $arr;
		}
	}
}
