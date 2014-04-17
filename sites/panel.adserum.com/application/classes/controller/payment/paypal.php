<?php 
/**
 * paypal class
 *
 * @package Open Classifieds
 * @subpackage Core
 * @category Controller
 * @author Chema Garrido <chema@garridodiaz.com>
 * @license GPL v3
 */

class Controller_Payment_Paypal extends Controller{
	
	public function action_ipn()
	{
		$this->auto_render = FALSE;

		//START PAYPAL IPN
		//manual checks
		$id_order 		= Core::post('item_number');

		//retrieve info for the item in DB
		$order = new Model_Order();
		$order = $order->where('id_order', '=', $id_order)
					   ->where('status', '=', Model_Order::STATUS_CREATED)
					   ->limit(1)->find();

		if ($order->loaded())
		{
			//smart select which paypal account to use depending on the amount to pay
			$paypal_account = ($order->amount > Core::config('paypal.micro_amount'))? Core::config('paypal.account'): Core::config('paypal.account_micro');


			if (   Core::post('mc_gross') 		== $order->amount 
				&& Core::post('mc_currency') 	== core::config('paypal.currency') 
				&&(Core::post('receiver_email') == $paypal_account 
				|| Core::post('business') 		== $paypal_account ) 
				)
			{//same price , currency and email no cheating ;)

				//check result if was paid
				if (paypal::validate_ipn()) 
				{
					//MONEY!! :D
					//update order
					$order->date_paid   = date::unix2mysql();
					$order->transaction = Core::post('txn_id');
					$order->payer_email = Core::post('payer_email');
					$order->status 		= Model_Order::STATUS_PAID;
					$order->save();

					//loading models
					$ad 		= new Model_Ad(NULL,NULL,$order->id_ad);
					$product 	= new Model_Product($order->id_product);
					$user 		= new Model_User($order->id_user);

					//inactive means just created
					if ($ad->status == Model_Ad::STATUS_INACTIVE)
					{
						//move ad to moderation
						$ad->to_moderation();
						//email user telling him ad is in moderation
						$user->email('ad.paid.moderation');
					}
					//if status of ad was published just add the impressions
					else
					{
						$ad->displays 		+= $product->displays;
	            		$ad->displays_left 	+= $product->displays;

						//ad was expired republish
						if ($ad->status == Model_Ad::STATUS_FINISH)
							$ad->status = Model_Ad::STATUS_ACTIVE;

						$ad->update();

						//email user telling him ad renewed
						$user->email('ad.paid.renew');
					}

				} 
				else
				{
					// Log an invalid request to look into
					// PAYMENT INVALID & INVESTIGATE MANUALY!
					$subject = 'Invalid Payment';
					$message = 'Dear Administrator,<br />
								A payment has been made but is flagged as INVALID.<br />
								Please verify the payment manually and contact the buyer. <br /><br />Here is all the posted info:';
					email::send(Core::config('common.email'),Core::config('common.name'),$subject,$message.'<br />'.print_r($_POST,true));
				}	

			} 
			//trying to cheat....
			else
			{
				$subject = 'Cheat Payment !?';
				$message = 'Dear Administrator,<br />
							A payment has been made but is flagged as Cheat.<br />
							We suspect some forbidden or illegal actions have been made with this transaction.<br />
							Please verify the payment manually and contact the buyer. <br /><br />Here is all posted info:';
				email::send(Core::config('common.email'),Core::config('common.name'),$subject,$message.'<br />'.print_r($_POST,true));
			}	
		
		}//END order loaded
		else
		{
			//order not loaded
			$subject = 'Order not loaded';
			$message = 'Dear Administrator,<br />
						Someone is trying to pay an inexistent Order...
						Please verify the payment manually and contact the buyer. <br /><br />Here is all posted info:';
			email::send(Core::config('common.email'),Core::config('common.name'),$subject,$message.'<br />'.print_r($_POST,true));
		}
		
		$this->response->body('OK');
	}

	/**
	 * generates the form to pay at paypal
	 * @return [type] [description]
	 */
	public function action_form()
	{ 
		$this->auto_render = FALSE;

		$order_id = $this->request->param('id');
	
		$order = new Model_Order();
		$order->where('id_order','=',$order_id)
			->where('id_user','=',Auth::instance()->get_user()->id_user)
			->where('status','=',Model_Order::STATUS_CREATED)
			->limit(1)->find();

		if ($order->loaded())
		{
			$product = new Model_Product($order->id_product);
		
			$paypal_url = (Core::config('paypal.sandbox')) ? Paypal::url_sandbox_gateway : Paypal::url_gateway;

			//smart select which paypal account to use depending on the amount to pay
			$paypal_account = ($order->amount > Core::config('paypal.micro_amount'))? Core::config('paypal.account'): Core::config('paypal.account_micro');

			$paypal_data = array('order_id'			=> $order_id,
								 'amount'			=> $order->amount,//number_format($order->amount, 2, '.', ''),
								 'site_name'		=> Core::config('common.name'),
								 'site_url'			=> Core::config('site.url'),
								 'paypal_url'		=> $paypal_url,
								 'paypal_account'	=> $paypal_account,
								 'paypal_currency'	=> Core::config('paypal.currency'),
								 'item_name'		=> $product->name);
			
			$this->template = View::factory('paypal', $paypal_data); 
			$this->response->body($this->template->render());
		}
		else
		{
			Alert::set(Alert::INFO, __('Order could not be loaded'));
			$this->request->redirect(Route::url('default'));
		}
		
	}


}