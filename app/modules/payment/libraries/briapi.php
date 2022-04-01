<?php
require "bri/Bri_lib.php";

class briapi{
    private $output;
    private $bri;
	private $user;
    private $pass;
    private $norek;
  
    public function __construct(){
		$this->user = get_option('bri_username');
		$this->pass = get_option('bri_password');
		$this->norek = get_option('bri_norek');
		if(!empty($this->user) && !empty($this->pass) && !empty($this->norek)){
			$this->bri = new Bri_lib();
		}else{
			die();
		}
    }
	
	public function getMutasi($dari, $ke){
		$output = $this->bri->get_transactions($this->user, $this->pass, $this->norek, 2);
		if(isset($output['data']) && !empty($output['data'])){
			foreach($output['data'] as $transaksi){
				$date = explode('/', $transaksi['date']);
				$this->output[] =[
					'tanggal'=> implode('-', [$date[2], $date[1], $date[0]]),
					'jumlah'=> $transaksi['nominal'],
					'type'=> $transaksi['type']=='CR'?'kredit':'debet'
				];
			}
		}
		
		return $this;
    
    }
	
    public function getOutput(){
		return $this->output;
    }
}
