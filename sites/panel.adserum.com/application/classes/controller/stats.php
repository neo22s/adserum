<?php 
class Controller_Stats extends Auth_Controller {

	public function __construct($request, $response)
	{
		parent::__construct($request, $response);
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Stats'))->set_url(Route::url('default',array('controller'  => 'stats'))));
		
	}
    
	public function action_index()
	{
		Request::current()->redirect(Route::url('default',array('controller'  => 'stats','action'=>'panel')));	
	}
	
	public function action_panel()
	{
        $this->template->styles = array('css/pages/reports.css' => 'screen',
                                        'css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('js/bootstrap-datepicker.js',
                                                    'js/pages/stats/panel.js');

        //$this->template->styles = array('css/pages/dashboard.css' => 'screen');
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Dashboard')));
		$this->template->title = 'Dashboard';
		
		$this->template->bind('content', $content);        
        $content = View::factory('pages/stats/panel');


        //Ads on specific date by day
        $from_date = Core::post('from_date',strtotime('-1 month'));
        $to_date   = Core::post('to_date',time());
        $dates     = Date::range($from_date, $to_date,'+1 day','Y-m-d',array('date'=>0,'count'=> 0),'date');
        
        $content->from_date = (is_numeric($from_date))? date('Y-m-d',$from_date) : $from_date;
        $content->to_date   = (is_numeric($to_date))?   date('Y-m-d',$to_date) : $to_date;

        ////////////////////////////////////////////////
        ////////////////////////////////////////////////
        $ad = new Model_Ad();

        ///////////////////////////////////////////////
        //Created Ads
        $daily_ads_count = 0;
        foreach ($dates as $k=>$d) 
        {
           $dates[$k]['count'] = $ad->count('created:'.$d['date']);
           $daily_ads_count += $dates[$k]['count'];
        }

        $content->daily_ads = $dates;
        $content->daily_ads_count = $daily_ads_count;
        $content->today_ads = $ad->count('created:'.date('Y-m-d'));
        $content->yes_ads = $ad->count('created:'.date('Y-m-d',strtotime('-1 day')));
        $content->month_ads = $ad->count('created:'.date('Y-m'));
        $content->year_ads = $ad->count('created:'.date('Y'));
        $content->total_ads = $ad->count();

        ////////////////////////////////////////////////
        //Published Ads
		$pub_ads_count = 0;
        foreach ($dates as $k=>$d) 
        {
           $dates[$k]['count'] = $ad->count('published:'.$d['date']);
           $pub_ads_count += $dates[$k]['count'];
        }

        $content->pub_ads = $dates;
        $content->pub_ads_count = $pub_ads_count;
        $content->today_pads = $ad->count('published:'.date('Y-m-d'));
        $content->yes_pads = $ad->count('published:'.date('Y-m-d'),strtotime('-1 day'));
        $content->month_pads = $ad->count('published:'.date('Y-m'));
        $content->year_pads = $ad->count('published:'.date('Y'));
        $content->total_pads = $ad->count('published');

        ////////////////////////////////////////////////
        ////////////////////////////////////////////////
        $hit = new Model_AdHit();

        ///////////////////////////////////////////////
        //Hits Ads
        $daily_hits_count = 0;
        foreach ($dates as $k=>$d) 
        {
           $dates[$k]['count'] = $hit->count('created:'.$d['date']);
           $daily_hits_count += $dates[$k]['count'];
        }

        $content->daily_hits = $dates;
        $content->daily_hits_count = $daily_hits_count;
        $content->today_hits = $hit->count('created:'.date('Y-m-d'));
        $content->yes_hits = $hit->count('created:'.date('Y-m-d',strtotime('-1 day')));
        $content->month_hits = $hit->count('created:'.date('Y-m'));
        $content->year_hits = $hit->count('created:'.date('Y'));
        $content->total_hits = $hit->count();

        ////////////////////////////////////////////////
        ////////////////////////////////////////////////
        $click = new Model_AdClick();

        ///////////////////////////////////////////////
        //Clicks Ads
        $daily_clicks_count = 0;
        foreach ($dates as $k=>$d) 
        {
           $dates[$k]['count'] = $click->count('created:'.$d['date']);
           $daily_clicks_count += $dates[$k]['count'];
        }

        $content->daily_clicks = $dates;
        $content->daily_clicks_count = $daily_clicks_count;
        $content->today_clicks = $click->count('created:'.date('Y-m-d'));
        $content->yes_clicks = $click->count('created:'.date('Y-m-d',strtotime('-1 day')));
        $content->month_clicks = $click->count('created:'.date('Y-m'));
        $content->year_clicks = $click->count('created:'.date('Y'));
        $content->total_clicks = $click->count();
	}
    

	public function action_score()
    {
        $type = $this->request->param('id');
        if ($type===NULL)
            $type = 'domain';

        

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Stats ').$type));
        $this->template->title = __('Stats ').$type;
        
        $this->template->bind('content', $content);        
        $content = View::factory('pages/stats/scores');

        $content->type = $type;

        //hits
        $hit = new Model_AdHit();
        $content->today_hits    = $hit->range($type.':'.date('Y-m-d'),0,10);
        $content->yes_hits      = $hit->range($type.':'.date('Y-m-d',strtotime('-1 day')),0,10);
        $content->month_hits    = $hit->range($type.':'.date('Y-m'),0,10);
        $content->lmonth_hits   = $hit->range($type.':'.date('Y-m',strtotime('-1 month')),0,10);
        $content->year_hits     = $hit->range($type.':'.date('Y'),0,10);
        $content->total_hits    = $hit->range($type,0,10);

        //hits
        $click = new Model_AdClick();
        $content->today_clicks    = $click->range($type.':'.date('Y-m-d'),0,10);
        $content->yes_clicks      = $click->range($type.':'.date('Y-m-d',strtotime('-1 day')),0,10);
        $content->month_clicks    = $click->range($type.':'.date('Y-m'),0,10);
        $content->lmonth_clicks   = $click->range($type.':'.date('Y-m',strtotime('-1 month')),0,10);
        $content->year_clicks     = $click->range($type.':'.date('Y'),0,10);
        $content->total_clicks    = $click->range($type,0,10);
        

        //types that loads form other source
        $type_models = array('id_affiliate' => array('model'      => 'Model_User',
                                                'route'      => 'default',
                                                'controller' => 'publisher',
                                                'action'     => 'stats',
                                                'id'         => 'id_user',     
                                                'pk'         => 'id_user',
                                                'name'       => 'email',
                                                ), 
                            'id_adformat' => array('model'      => 'Model_Adformat',
                                                'route'      => 'default',
                                                'controller' => 'adformat',
                                                'action'     => 'update',
                                                'id'         => 'id_adformat',     
                                                'pk'         => 'id_adformat',
                                                'name'       => 'name',
                                                ), 
                            'id_language' => array('model'   => 'Model_Language',
                                                'route'      => 'default',
                                                'controller' => 'language',
                                                'action'     => 'update',
                                                'id'         => 'id_language',     
                                                'pk'         => 'id_language',
                                                'name'       => 'language_name',
                                                ), 
                            'id_location' => array('model'   => 'Model_Location',
                                                'route'      => 'default',
                                                'controller' => 'location',
                                                'action'     => 'update',
                                                'id'         => 'id_location',     
                                                'pk'         => 'id_location',
                                                'name'       => 'city',
                                                ), 
                        );

        $content->links = NULL;
        //specific scores related to other DB
        if (array_key_exists($type, $type_models))
        {
            $ids = array();
            //we loop all the arrays to get the ids of the models so we can search
            foreach ($content->today_hits as $h) 
                $ids[] = $h[0];
            foreach ($content->yes_hits as $h) 
                $ids[] = $h[0];
            foreach ($content->month_hits as $h) 
                $ids[] = $h[0];
            foreach ($content->lmonth_hits as $h) 
                $ids[] = $h[0];
            foreach ($content->year_hits as $h) 
                $ids[] = $h[0];
            foreach ($content->total_hits as $h) 
                $ids[] = $h[0];
            
            $ids = array_unique($ids); //d($ids);

            $links = array();
            //retrieving the data
            $model = new $type_models[$type]['model'];
            $model = $model->where($type_models[$type]['pk'],'in',$ids)->find_all();// d($model);

            foreach ($model as $m) 
            {
                $links[$m->pk()]['link'] = Route::url($type_models[$type]['route'],
                                                            array('controller'=>$type_models[$type]['controller'],
                                                                    'action' => $type_models[$type]['action'],
                                                                    'id'     => $m->__get($type_models[$type]['id']))
                                                    );

                $links[$m->pk()]['name'] = $m->__get($type_models[$type]['name']);
            }

            $content->links = $links;

            //d($links);

            //at the end I want an array with the name and link to the action
        }
        
    }

    /**
     * clean DB from not needed indexes
     */
    public function cleanscores()
    {
        $redis = new Predis\Client(Core::config('database.redis'));
        $redis->connect();
        $pipe = $redis->pipeline();


        $_sets = array ('id_adformat',
                        'id_language',
                        'id_location',
                        'domain',
                        'browser',
                        'platform');
        foreach ($_sets as $key) 
        {
            $del = $redis->keys('adhits:'.$key.':*');
            //var_dump($del);
            foreach ($del as $k)
                $pipe->del($k);
        }


        //clicks
        $_sets =  array ('id_ad',
                        'id_adformat',
                        'id_affiliate',
                        'id_language',
                        'id_location',
                        'domain',
                        'browser',
                        'platform');

        foreach ($_sets as $key) 
        {
            $del = $redis->keys('adclicks:'.$key.':*');
            //var_dump($del);
            foreach ($del as $k)
                $pipe->del($k);
        }


        //d('');

        $pipe->execute();
        $redis->bgsave();

    }

}
