<?php 
class Controller_Publisher extends Auth_Controller {
	
	/**
	 *
	 * Contruct that checks you are loged in before nothing else happens!
	 */
	function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);

		$url_bread = Route::url('default',array('controller'  => 'publisher'));
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Publisher'))->set_url($url_bread));
	}

    public function action_index()
	{
		$url = Route::get('default')->uri(array('controller' => 'publisher', 'action'     => 'codes'));
		$this->request->redirect($url);
	}

	public function action_codes()
	{
		$this->template->title = __('Publisher Codes');
		Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title));


		$formats = new Model_AdFormat();
		$formats = $formats->where('active','=',1)
		->order_by('orientation','desc')
		->order_by('name','asc')
		->cached()->find_all();

		$this->template->content = View::factory('pages/publisher/codes',array('formats'=>$formats,'user'=>Auth::instance()->get_user()));
	}

	public function action_payments()
	{
		$this->template->title = __('Payments');
		Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title));

        $this->template->bind('content', $content);        
        $content = View::factory('pages/publisher/payments');

        $user = Auth::instance()->get_user();
        
        //if god we allow to see other affiliates stats
        if ($user->id_role == 1)
            $id_user = $this->request->param('id',$user->id_user);
        else
            $id_user = $user->id_user;


        $content->user = new Model_User($id_user);

        //get paid orders
        $orders = new Model_Order();
        $orders = $orders->where('id_user','=',$id_user)
                ->where('id_product','=',11)
                ->where('status','=',1)
                ->order_by('date_paid','desc')
                ->find_all()->as_array();
        $content->orders = $orders;
        //get last payment day
        if (isset($orders[0]))
            $last_date_paid = Date::mysql2unix($orders[0]->date_paid);
        else
            $last_date_paid = NULL;

        $content->last_date_paid = $last_date_paid;
        //d($last_date_paid);

		//get total earned
        $hits = new Model_Adhit();
        $content->total_hits    = $hits->count('id_affiliate:'.$id_user);

		//get total from last payment
        if ($last_date_paid!==NULL)
        {
            $dates     = Date::range($last_date_paid, strtotime('-1 day'),'+1 day','Y-m-d',array('date'=>0,'count'=> 0),'date');
            
            $hits_last_payment = 0;
            foreach ($dates as $k=>$d) 
               $hits_last_payment += $hits->zscore('adhits:id_affiliate:'.$d['date'],$id_user);

           $content->hits_last_payment = $hits_last_payment;
        }
        else
            $content->hits_last_payment = NULL;
       

		//if form submit and he has a paypal account and he has more than 50$ 
        if ($this->request->post())
        {
            //create order with product pay to publisher, but paid=0
            $order = new Model_Order();
            $order->id_user = $id_user;
            $order->id_product = 11;
            $order->id_ad = 1;
            $order->amount = 0; //we will calculate it later on the paymeny
            $order->ip_address = ip2long(Request::$client_ip);
            $order->create();

            //then send email to admin, he wants to get paid.
            //email includes link to publishers stats. 
            email::send(core::config('common.email'),core::config('common.email'),
                        'Pay request for '.$id_user.' '.Auth::instance()->get_user()->paypal_email,
                        Route::url('default',array('controller'=>'publisher','action'=>'stats','id'=>$id_user)));
            //and to pay to publisher, we can use a normal paypal link using as account the publisher. then by hand mark it as paid @todo

            Alert::set(Alert::SUCCESS, __('Nice! Please wait, we are reviewing your request'));
        }
			
		
	}

    //OLD not in use
    public function reset()
    {
        
        $redis = new Predis\Client(Core::config('database.redis'));
        $redis->connect();
        $pipe = $redis->pipeline();
        
        ////for each user        
        $users = new Model_User();
        $users = $users->find_all();

        //d($users);
        foreach ($users as $user)
        {
            $pipe->del('adhits:id_affiliate:'.$user->id_user);
            $pipe->del('adhits:domains:id_affiliate:'.$user->id_user);
            $pipe->del('adhits:domains:id_affiliate:'.$user->id_user.':2013');
        }
            

        //Remove since 1st jan 2013 till today:
        $dates     = Date::range(strtotime('-7 months'), time(),'+1 day','Y-m-d',array('date'=>0,'count'=> 0),'date');
        
        foreach ($dates as $k=>$d) 
        {
            $pipe->del('adhits:id_affiliate:'.$d['date']);

            foreach ($users as $user)
                $pipe->del('adhits:domains:id_affiliate:'.$user->id_user.':'.$d['date']);
        }
            

        $dates     = Date::range(strtotime('-7 months'), time(),'+1 month','Y-m',array('date'=>0,'count'=> 0),'date');
        foreach ($dates as $k=>$d) 
        {
            $pipe->del('adhits:id_affiliate:'.$d['date']);

            foreach ($users as $user)
                $pipe->del('adhits:domains:id_affiliate:'.$user->id_user.':'.$d['date']);
        }
        
        $pipe->del('adhits:id_affiliate:2013');

        die($pipe->execute());
        

        

    }
    
	public function action_stats()
	{
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Publisher Stats')));
		$this->template->title = __('Publisher Stats');

		//template
		$this->template->styles = array('css/pages/reports.css' => 'screen',
                                        'css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('js/bootstrap-datepicker.js',
                                                    'js/pages/stats/panel.js');

		$this->template->bind('content', $content);        
        $content = View::factory('pages/publisher/stats');


		$user = Auth::instance()->get_user();
		
		//if god we allow to see other affiliates stats
		if ($user->id_role == 1)
			$id_user = $this->request->param('id',$user->id_user);
		else
			$id_user = $user->id_user;


		$content->user = new Model_User($id_user);


		$hits = new Model_Adhit();
		//adhits:id_affiliate:IDAFF
		$content->today_hits 	= $hits->zscore('adhits:id_affiliate:'.date('Y-m-d'),$id_user);
        $content->yes_hits  	= $hits->zscore('adhits:id_affiliate:'.date('Y-m-d',strtotime('-1 day')),$id_user);
        $content->month_hits 	= $hits->zscore('adhits:id_affiliate:'.date('Y-m'),$id_user);
        $content->year_hits 	= $hits->zscore('adhits:id_affiliate:'.date('Y'),$id_user);
        $content->total_hits	= $hits->count('id_affiliate:'.$id_user);

		//stats on specific date by day
        $from_date = Core::post('from_date',strtotime('-1 month'));
        $to_date   = Core::post('to_date',time());
        $dates     = Date::range($from_date, $to_date,'+1 day','Y-m-d',array('date'=>0,'count'=> 0),'date');
        
        $content->from_date = (is_numeric($from_date))? date('Y-m-d',$from_date) : $from_date;
        $content->to_date   = (is_numeric($to_date))?   date('Y-m-d',$to_date) : $to_date;

        //chart impressions  selected dates
        $daily_hits 	= $dates;
        foreach ($dates as $k=>$d) 
           $daily_hits[$k]['count'] 	= $hits->zscore('adhits:id_affiliate:'.$d['date'],$id_user);
        $content->daily_hits 	= $daily_hits;

        $content->domains_all 	= $hits->range('domains:id_affiliate:'.$id_user);
        $content->domains_today = $hits->range('domains:id_affiliate:'.$id_user.':'.date('Y-m-d'));
        $content->domains_yes 	= $hits->range('domains:id_affiliate:'.$id_user.':'.date('Y-m-d',strtotime('-1 day')));
        $content->domains_month = $hits->range('domains:id_affiliate:'.$id_user.':'.date('Y-m'));
        $content->domains_last_month = $hits->range('domains:id_affiliate:'.$id_user.':'.date('Y-m',strtotime('-1 month')));
        $content->domains_year 	= $hits->range('domains:id_affiliate:'.$id_user.':'.date('Y'));

	}

	public static function money_cpm($impressions, $currency = TRUE)
	{
        if ($currency)
            return '$'.money_format('%n', ($impressions/1000)*core::config('common.publisher_CPM'));
        else 
            return ($impressions/1000)*core::config('common.publisher_CPM');
	}
}
