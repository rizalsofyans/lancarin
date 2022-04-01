<?php

class Mandiribaru_lib {

	public function __construct()
    {
	}
	
	private function fixDateBug($transaction){
		//fix bug tanggal transaksi bank mandiribaru
		//asumsi tdk melakukan pengecekan mutasi lebih dari 4 bln
		$ts = time();
		$bln4 = $ts - (10368000); //4*30*86400 = 4bln
		$exp = explode('/', $transaction['transactionDate']);
		$transactionDate = implode('-', [$exp[2], $exp[1], $exp[0]]);
		if(strtotime($transactionDate) < $bln4){
			//asumsi tahun = tahun sekarang
			$transactionDate = $transaction['postingDate'] .'/'.date('Y');
			$exp = explode('/', $transactionDate);
			$transactionDate = implode('-', [$exp[2], $exp[1], $exp[0]]);
		}
		return $transactionDate;
	}
	
    public function get_transactions($user, $pass, $norek, $from_date = null, $to_date = null)
    {
	    putenv("PHANTOMJS_EXECUTABLE=/usr/local/bin/phantomjs");

		if(empty($from_date)) $from_date=date('Y-m-d');
		if(empty($to_date)) $to_date=date('Y-m-d');
		$transaksi = 'C';
		$current = false;
		$corefile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'mandiribaru.js';
		exec("casperjs $corefile --user=$user --pass=$pass --tgl1=$from_date --tgl2=$to_date --transaksi=$transaksi --current=$current --norek=$norek 2>&1", $output);
		$arr = json_decode($output[0], true);
		$transactions = [];
		if(isset($arr['ok']) && $arr['ok']==1){
			if(!empty($arr['data']['aaData'])){
				foreach($arr['data']['aaData'] as $transaction){
					$expAmount = explode('.', $transaction['amount']);
					$transaction['amount'] = str_replace(',', '', $expAmount[0]);
					if($transaction['debitCreditFlag']=='K'){
						$debet = 0;
						$kredit=  $transaction['amount'];
					}else{
						$kredit = 0;
						$debet=  $transaction['amount'];
					}
					$transactions[] = [
						'tanggal'=> $this->fixDateBug($transaction),
						'keterangan'=> $transaction['transactionRemark'],
						'debet'=> $debet,
						'kredit'=> $kredit,
					];
				}
			}
		}
		return $transactions;
    }
}
