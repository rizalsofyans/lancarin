<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class stripe extends MX_Controller {

	public $tb_packages;
	public $tb_payment_history;
	public $tb_users;

	public function __construct(){
		parent::__construct();
		$this->tb_packages = PACKAGES;
		$this->tb_payment_history = PAYMENT_HISTORY;
		$this->tb_users = USERS;
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function index(){
		if(ajax_page()){
			$package = $this->model->get("*", $this->tb_packages, "ids = '".get_secure("pid")."'  AND status = 1");
			if(empty($package)){
				$ms = lang('can_not_processing_this_gateway_please_try_again_later');
				echo $ms;
				exit(0);
			}
			$data = array(
				'package' => $package
			);
			$this->load->view('stripe', $data);
		}
	}

	public function process(){
		if(ajax_page()){
			$token  = post('stripeToken');
			$ids  = post('ids');
			$plan  = post('plan');
			$package = $this->model->get("*", $this->tb_packages, "ids = '".$ids."'  AND status = 1");
			$user = $this->model->get("*", $this->tb_users, "id = '".session("uid")."'");

			if(!empty($package) && !empty($user)){
				$this->load->library('stripeapi');
				$amount = $package->price_monthly;
				if($plan == 2){
					$amount = $package->price_annually*12;
				}else{
					$plan = 1;
				}

				try {
					\Stripe\Stripe::setApiKey(get_option('stripe_secret_key'));

					$customer = \Stripe\Customer::create(array(
						'email' => $user->email,
						'description' => 'Payment date: '.NOW,
				  		'source'   => $token
					));

					$result = \Stripe\Charge::create(array(
						'customer' => $customer->id,
					  	'amount'   => $amount*100,
					  	'currency' => get_option('payment_currency','USD')
					));

					if($result->paid == 1){
						$data = array(
							'ids' => ids(),
							'uid' => session("uid"),
							'package' => $package->id,
							'type' => 'stripe_charge',
							'transaction_id' => $result->id,
							'amount' => $result->amount/100,
							'plan' => $plan,
							'status' => 1,
							'created' => NOW
						);
					}

					$this->db->insert($this->tb_payment_history, $data);
					$this->update_package($package, $plan);

					redirect(cn('thank_you'));
				} catch (Exception $e) {
					redirect(cn('pricing'));
				}
			}else{
				redirect(cn('pricing'));
			}
		}
	}

	public function update_package($package_new, $plan){
		$user = $this->model->get("*", $this->tb_users, "id = '".session("uid")."'");
		if(!empty($user)){
			$package_old = $this->model->get("*", $this->tb_packages, "id = '".$user->package."'");
			$package_id = $package_new->id;

			$new_days  = 30;
			if($plan == 2){
				$new_days  = 365;
			}
			
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
				"expiration_date" => $expiration_date
			);

			$this->db->update($this->tb_users, $data, "id = '".session("uid")."'");
		}
	}

}