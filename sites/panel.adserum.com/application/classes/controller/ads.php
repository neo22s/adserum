<?php 
class Controller_Ads extends Auth_Controller {



    public function __construct($request, $response)
	{
		parent::__construct($request, $response);
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Advertisements'))->set_url(Route::url('default',array('controller'  => 'ads'))));
		
	}
    
	public function action_index()
	{

		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Listing')));
		$this->template->title = __('Listing');

		$ads = new Model_Ad();
		$pagination = Pagination::factory(array(
					'view'           => 'pagination',
					'total_items' 	 =>  $ads->count('user:'.Auth::instance()->get_user()->id_user),
		))->route_params(array(
					'directory'  => $this->request->directory(),
					'controller' => $this->request->controller(),
					'action' 	 => $this->request->action(),
		));

		//selecting the ids to display
		$pks = $ads->get_ads_user(Auth::instance()->get_user()->id_user, $pagination->offset,$pagination->items_per_page);

		//getting the values
		$ads = $ads->select($pks);

		//d($ads);

		$pagination = $pagination->render();

		$this->template->bind('content', $content);        
    	$content = View::factory('pages/ads/list',array('ads' => $ads,'pagination'=>$pagination));
	}

	public function action_stats()
	{
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Stats')));
		$this->template->title = __('Stats');

		//get advertisement
		$id = $this->request->param('id');

		if ($id===NULL OR !is_numeric($id))
			$this->request->redirect(Route::get('default')->uri(array('controller' => 'ads', 'action'     => 'index')));

		$ad = new Model_Ad(NULL,NULL,$id);

		if (!$ad->loaded())
			$this->request->redirect(Route::get('default')->uri(array('controller' => 'ads', 'action'     => 'index')));

		//check the owner
		//god can see it
		if (Auth::instance()->get_user()->id_role!=1)
		{
			if (Auth::instance()->get_user()->id_user!==$ad->id_user)
				$this->request->redirect(Route::get('default')->uri(array('controller' => 'ads', 'action'     => 'index')));
		}
		

		//template
		$this->template->styles = array('css/pages/reports.css' => 'screen',
                                        'css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('js/bootstrap-datepicker.js',
                                                    'js/pages/stats/panel.js');

		$this->template->bind('content', $content);        
        $content = View::factory('pages/ads/stats');

		//get stats data
		$content->ad = $ad;

		//impressions & clicks yesterday, today, month, year, total

        $content->today_hits 	= $ad->sinter(array('ads:'.$ad->id_ad.':hits','adhits:created:'.date('Y-m-d')),TRUE);
        $content->yes_hits 		= $ad->sinter(array('ads:'.$ad->id_ad.':hits','adhits:created:'.date('Y-m-d',strtotime('-1 day'))),TRUE);
        $content->month_hits 	= $ad->sinter(array('ads:'.$ad->id_ad.':hits','adhits:created:'.date('Y-m')),TRUE);
        $content->year_hits 	= $ad->sinter(array('ads:'.$ad->id_ad.':hits','adhits:created:'.date('Y')),TRUE);
        $content->total_hits 	= $ad->count($ad->id_ad.':hits');

		$content->today_clicks 	= $ad->zscore('adclicks:id_ad:'.date('Y-m-d'),$ad->id_ad);
        $content->yes_clicks  	= $ad->zscore('adclicks:id_ad:'.date('Y-m-d',strtotime('-1 day')),$ad->id_ad);
        $content->month_clicks 	= $ad->zscore('adclicks:id_ad:'.date('Y-m'),$ad->id_ad);
        $content->year_clicks 	= $ad->zscore('adclicks:id_ad:'.date('Y'),$ad->id_ad);
        $content->total_clicks	= $ad->count($ad->id_ad.':clicks');


		//stats on specific date by day
        $from_date = Core::post('from_date',strtotime('-1 month'));
        $to_date   = Core::post('to_date',time());
        $dates     = Date::range($from_date, $to_date,'+1 day','Y-m-d',array('date'=>0,'count'=> 0),'date');
        
        $content->from_date = (is_numeric($from_date))? date('Y-m-d',$from_date) : $from_date;
        $content->to_date   = (is_numeric($to_date))?   date('Y-m-d',$to_date) : $to_date;

        //chart impressions & clicks selected dates
        $daily_hits 	= $dates;
        $daily_clicks 	= $dates;
        foreach ($dates as $k=>$d) 
        {
           $daily_hits[$k]['count'] 	= $ad->sinter(array('ads:'.$ad->id_ad.':hits','adhits:created:'.$d['date']),TRUE);
           $daily_clicks[$k]['count'] 	= $ad->zscore('adclicks:id_ad:'.$d['date'],$ad->id_ad);
        }
        $content->daily_hits 	= $daily_hits;
		$content->daily_clicks 	= $daily_clicks;

        //top domains, browsers, platforms impressions & clicks by dates
//sinter ads:IDAD:hits adhits:browsers:* adhits:created:2013-08-02
        

	}
	
	public function action_new()
	{

        $this->template->scripts['footer'] = array( 'js/plugins/validate/jquery.validate.js',
        											'js/pages/ads/new.js');

		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('New Advertisement')));
		$this->template->title = __('New Advertisement');
		
		$errors = NULL;

		if ($this->request->post())
		{
			$ad = new Model_Ad();
 
	        $validation = Validation::factory($this->request->post())
	            ->rule('title', 'not_empty')
	            ->rule('title', 'min_length', array(':value', 2))
	            ->rule('title', 'max_length', array(':value', 25))

	            ->rule('desc', 'not_empty')
	            ->rule('desc', 'min_length', array(':value', 2))
	            ->rule('desc', 'max_length', array(':value', 35))

	            ->rule('desc2', 'not_empty')
	            ->rule('desc2', 'min_length', array(':value', 2))
	            ->rule('desc2', 'max_length', array(':value', 35))

	            ->rule('url', 'not_empty')
	            ->rule('url', 'min_length', array(':value', 10))
	            ->rule('url', 'max_length', array(':value', 300))

	            ->rule('durl', 'not_empty')
	            ->rule('durl', 'min_length', array(':value', 5))
	            ->rule('durl', 'max_length', array(':value', 35))
	 
	            ->rule('lang', 'not_empty')
	            ->rule('product', 'not_empty')
	            ->rule('product', 'numeric');
	            //->rule('lang', 'url')

			$product = new Model_Product(Core::post('product'));

	        if ($validation->check() and $product->loaded())
	        {
	            // Data has been validated, register the ad
	            $ad->id_user 		= Auth::instance()->get_user()->id_user;
	            $ad->title 			= Core::post('title');
	            $ad->description 	= Core::post('desc');
	            $ad->description2	= Core::post('desc2');
                $ad->click_url      = ( strpos('http://', Core::post('url'))==0 OR strpos('https://', Core::post('url'))==0 )? Core::post('url') : 'http://'.Core::post('url');
	            $ad->display_url 	= Core::post('durl');
	            $ad->ip_address 	= ip2long(Request::$client_ip);

	            $ad->displays 		= $product->displays;
	            $ad->displays_left 	= $product->displays;

	            $lang 				= Model_Language::get_by_lang(Core::post('lang'));
	            $ad->id_language	= $lang->id_language;

	            $countries 	= Core::post('countries');
	            if (!is_array($countries))
	            	$countries = array($countries);
	            $countries_ids = Model_Location::get_countries_id($countries);

	            $cities 	= Core::post('cities');
	            if (!is_array($cities))
	            	$cities = array($cities);

	            $locations 	= array_merge($countries_ids,$cities);
	            $ad->set_locations($locations);

	            $ad->create();

				//redirect to payment gateway
				if ($product->price > 0)
				{
					//create order
					$order = new Model_Order();
					$order->id_product 	= $product->id_product;
					$order->id_user 	= Auth::instance()->get_user()->id_user;
					$order->id_ad 		= $ad->id_ad;
					$order->amount 		= $product->price;
					$order->ip_address 	= $ad->ip_address;
					$order->status 		= Model_Order::STATUS_CREATED;
					$order->create();
				
					$this->request->redirect(Route::url('default',array('controller'=>'payment_paypal','action'=>'form','id'=>$order->id_order)));
				}
				//no need to pay
				else
				{
					//move ad to moderation
					$ad->to_moderation();
					// Always redirect after successful 
		            Alert::set(Alert::SUCCESS, __('Advertisement in moderation, we will publish it ASAP!'));
					$this->request->redirect(Route::url('default'));
				}
	            
	        }
	 
	        // Validation failed, collect the errors
	        $errors = $validation->errors('ad');
		}
		//else
		//{
			$geo = Model_Location::get_location(Core::get('city'),Core::get('country'));
		//}
		
		$this->template->bind('content', $content);        
    	$content = View::factory('pages/ads/new');
		$content->errors = $errors;
		$content->geo 	 = $geo;
		$content->countries = Model_Location::get_countries();
		$content->products = Model_Product::get_all();
		
		

     }


     /**
      *Used to put impressions in the ad / renew
      */
    public function action_credit()
	{
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Renew')));
		$this->template->title = __('Renew Advertisement');

		//get advertisement
		$id = $this->request->param('id');

		if ($id===NULL OR !is_numeric($id))
			$this->request->redirect(Route::get('default')->uri(array('controller' => 'ads', 'action'     => 'index')));

		$ad = new Model_Ad(NULL,NULL,$id);

		if (!$ad->loaded())
			$this->request->redirect(Route::get('default')->uri(array('controller' => 'ads', 'action'     => 'index')));

		//check the owner
		//god can see it
		if (Auth::instance()->get_user()->id_role!=1)
		{
			if (Auth::instance()->get_user()->id_user!==$ad->id_user)
				$this->request->redirect(Route::get('default')->uri(array('controller' => 'ads', 'action'     => 'index')));
		}

		//paying
		if ($this->request->post())
		{
			$product = new Model_Product(Core::post('product'));

			//create order
			$order = new Model_Order();
			$order->id_product 	= $product->id_product;
			$order->id_user 	= Auth::instance()->get_user()->id_user;
			$order->id_ad 		= $ad->id_ad;
			$order->amount 		= $product->price;
			$order->ip_address 	= ip2long(Request::$client_ip);
			$order->status 		= Model_Order::STATUS_CREATED;
			$order->create();
		
			$this->request->redirect(Route::url('default',array('controller'=>'payment_paypal','action'=>'form','id'=>$order->id_order)));

		}
		

		$this->template->bind('content', $content);        
        $content = View::factory('pages/ads/credit');

        $content->ad = $ad;
        $content->products = Model_Product::get_all();

    }




     //@TODO
     public function action_banner()
     {

     	$this->template->scripts['footer'] = array( 'js/plugins/validate/jquery.validate.js',
        											'js/pages/ads/new.js');

		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('New Banner')));
		$this->template->title = __('New Banner');

		$errors = NULL;

		if ($this->request->post())
		{
			$ad = new Model_Ad();
 
	        $validation = Validation::factory($this->request->post())
	            ->rule('title', 'not_empty')
	            ->rule('title', 'min_length', array(':value', 2))
	            ->rule('title', 'max_length', array(':value', 25))

	            //validate image size and type

	            ->rule('url', 'not_empty')
	            ->rule('url', 'min_length', array(':value', 10))
	            ->rule('url', 'max_length', array(':value', 300))

	            ->rule('durl', 'not_empty')
	            ->rule('durl', 'min_length', array(':value', 5))
	            ->rule('durl', 'max_length', array(':value', 35))
	 
	            ->rule('lang', 'not_empty')
	            ->rule('product', 'not_empty')
	            ->rule('product', 'numeric');
	            //->rule('lang', 'url')

			$product = new Model_Product(Core::post('product'));

	        if ($validation->check() and $product->loaded())
	        {
	            // Data has been validated, register the ad
	            $ad->id_user 		= Auth::instance()->get_user()->id_user;
	            $ad->title 			= Core::post('title');

	            $ad->has_image		= 1;
	            $ad->click_url 		= Core::post('url');
	            $ad->display_url 	= Core::post('durl');
	            $ad->ip_address 	= ip2long(Request::$client_ip);

	            $ad->displays 		= $product->displays;
	            $ad->displays_left 	= $product->displays;

	            $lang 				= Model_Language::get_by_lang(Core::post('lang'));
	            $ad->id_language	= $lang->id_language;

	            $countries 	= Core::post('countries');
	            if (!is_array($countries))
	            	$countries = array($countries);
	            $countries_ids = Model_Location::get_countries_id($countries);

	            $cities 	= Core::post('cities');
	            if (!is_array($cities))
	            	$cities = array($cities);

	            $locations 	= array_merge($countries_ids,$cities);
	            $ad->set_locations($locations);

	            $ad->create();

				//redirect to payment gateway
				if ($product->price > 0)
				{
					//create order
					$order = new Model_Order();
					$order->id_product 	= $product->id_product;
					$order->id_user 	= Auth::instance()->get_user()->id_user;
					$order->id_ad 		= $ad->id_ad;
					$order->amount 		= $product->price;
					$order->ip_address 	= $ad->ip_address;
					$order->status 		= Model_Order::STATUS_CREATED;
					$order->create();
				
					$this->request->redirect(Route::url('default',array('controller'=>'payment_paypal','action'=>'form','id'=>$order->id_order)));
				}
				//no need to pay
				else
				{
					//move ad to moderation
					$ad->to_moderation();
					// Always redirect after successful 
		            Alert::set(Alert::SUCCESS, __('Advertisement in moderation, we will publish it ASAP!'));
					$this->request->redirect(Route::url('default'));
				}
	            
	        }
	 
	        // Validation failed, collect the errors
	        $errors = $validation->errors('ad');
		}


		$geo = Model_Location::get_location(Core::get('city'),Core::get('country'));

		$this->template->bind('content', $content);        
    	$content = View::factory('pages/ads/banner');
		$content->errors = $errors;
		$content->geo 	 = $geo;
		$content->countries = Model_Location::get_countries();
		$content->products = Model_Product::get_all();
     }


}