<?php 
class Controller_Ads extends ControllerApi {

	/**
	 * return advertisements by location language
	 */
	public function action_index()
	{
		//using a CSRF token to control the origin of the script//CSRF::valid('ads','get') AND
		if ( !core::is_bot() 
			AND (Core::get('rep',0)<= Core::config('common.num_reps') 
				OR strpos($_SERVER['SERVER_NAME'],'adserum')!==FALSE) ) 
		{
			//find ad format if exists
			$adformat = Model_Adformat::get_format(Core::get('format'),Core::get('width'),Core::get('height'));

			if (!$adformat->loaded()) //not any format found
				$this->promote($width,$height);
			
			$format = $adformat->name;//d($format);

			//get the location for the ads to filter
			//list($city,$id_city,$country,$id_country) = $geo;//no idea why doesnt work :S
			$geo = Model_Location::get_location(Core::get('city'),Core::get('country'));//d($geo);

			//get the affiliate user
			$affiliate = new Model_User();
			$affiliate->where('id_user','=',Core::get('aff'))
						->where('status','=',Model_User::STATUS_ACTIVE)
						->limit(1)->cached()->find();
			$affiliate = ($affiliate->loaded())? $affiliate->id_user:Core::config('common.affiliate');

			$ads = new Model_Ad();
			$ads_ids = $ads->find($geo,parent::$lang->id_language,$adformat);

			//if nothing found use auto ads, those that dont get paid.

			$ads = $ads->select($ads_ids);

            //loop all the ads if 1 of them is paid (different than status 10) 
            //then we ad the adhit to the affiliate, if not, affiliate = Core::config('common.affiliate')
            foreach ($ads as $ad) 
            {
                if ($ad->status==Model_Ad::STATUS_ACTIVE)
                {
                    $free_affiliate = $affiliate;
                    break;
                }
                else
                    $free_affiliate = Core::config('common.affiliate');
            }
            $affiliate = $free_affiliate;

			//new hit
			$adhit = new Model_Adhit();

			//check if IP is abusing the system
			if($adhit->zscore('adhits:ip_address:'.date('Y-m-d'),ip2long(Request::$client_ip))>=Core::config('common.ip_hits_day'))
				$this->promote(Core::get('width'),Core::get('height'));
				//@todo notify admin about this ip and send domain and affiliate in the email, to scare them?

			$adhit->id_adformat 	= $adformat->id_adformat;
			$adhit->id_affiliate	= $affiliate;
			$adhit->id_language 	= parent::$lang->id_language;
			$adhit->id_location 	= $geo['id_city'];
			$adhit->domain 			= parse_url($this->request->referrer(), PHP_URL_HOST);
			$adhit->browser 		= Request::user_agent('browser');
			$adhit->platform 		= Request::user_agent('platform');
			$adhit->ip_address		= ip2long(Request::$client_ip);
			$adhit->status 			= Model_Adhit::STATUS_ACTIVE; //@todo here goes the fraud detector? or background process?
			$adhit->create();

			$hits = array();
			foreach ($ads as $ad) 
			{
				$ad->hit($adhit);//hit the ad
				$hits[$ad->id_ad]['hit'] = $adhit->id_adhit;
				//create links with redirect, so we know if clicked
				//csrf, id hit, url destination
				$hits[$ad->id_ad]['url'] = Route::url('default',array('controller'=>'ads','action'=>'click')).
											'?h='.$adhit->id_adhit.'&ad='.$ad->id_ad.
											'&csrf_c'.$adhit->id_adhit.'='.CSRF::token('c'.$adhit->id_adhit);
			}

			//loading view for image banner
			/*if (count($ads)==1){}*/
			
			//load the view for the ad format
			$this->template->content = View::factory('formats/'.$format,array('adformat'=>$adformat,
																			'ads'=>$ads,
																			'hits'=>$hits,
																			'affiliate'=>$affiliate));
		}
		else 
			$this->promote(Core::get('width'),Core::get('height'));
				
	}

	/**
	 * redirects a click to the url and we store it as clicked
	 * @return 302 redirect
	 */
	public function action_click()
	{
		$url = Core::config('common.front_url');

		$utm = '?utm_source=adserum&utm_medium=banner&utm_campaign='.date('Y-m-d');

		//valid referral
		if (CSRF::valid('c'.Core::get('h'),'get'))
		{
			//get the ad
			$ad = new Model_Ad(NULL,NULL,Core::get('ad'));
			if ($ad->loaded())
			{
				$url = $ad->click_url;
				$ad->click(Core::get('h'));
			}
		}
		//d($url);
		$this->request->redirect($url.$utm);
		
	}

	/**
	 * return a default ad if anthing goes wrong
	 * in case we need to show ads, if not adserum ads
	 */
	private function promote($width=300,$height=250)
	{
		//die('<img src="http://ima.gs/'.$width.'x'.$height.'.png">');//@todo
		die('<a href="http://adserum.com?utm_source=adserum&utm_medium=banner&utm_campaign='.date('Y-m-d').'"><img src="http://www.placehold.it/'.$width.'x'.$height.'&text=Adserum.com"></a>');
		//load the view for the ad format
		//$this->template->content = View::factory('formats/'.$format);
	}

	/**
	 * get the JS that retrieves the html with ads, route ads.js
	 */
	public function action_js()
	{
		$this->template = View::factory('js');

		//js that can be loadaed
		$views = array('async','sync');

		//backward compatibility, we used to use ads.js instead of async.js/sync.js
		$view = $this->request->param('id');
		if (!in_array($view, $views)) $view = 'async';

		$this->template->content = View::factory($view);
	}
    


	public function model()
	{

		$content = '';
		$start_time = microtime(true);


		
		$domains = array('oc.com','dc.com','as.com','test.com');
		$browsers = array('Chrome','Firefox','IE');
		$platforms = array('Windows','Mac','Linux');
		
		$adhit = new Model_Adhit();
		echo ($adhit->count().'--');

		$ad = new Model_Ad();
		$count_ads = $ad->count();

		for ($i=0; $i < 1000; $i++) 
		{ 
			

			$adhit = new Model_Adhit();
			$adhit->id_adformat 	= rand(1,8);
			$adhit->id_affiliate	= rand(1,12);
			$adhit->id_language 	= rand(1,3);
			$adhit->id_location 	= rand(0,1500);
			$adhit->domain 			= $domains[array_rand($domains)];
			$adhit->browser 		= $browsers[array_rand($browsers)];
			$adhit->platform 			= $platforms[array_rand($platforms)];
			$adhit->ip_address	= ip2long(Request::$client_ip);
			$adhit->status 		= 1; 
			$adhit->create();

			$rnum = rand(0,$count_ads);
			//$adhit->hit_ads(range($rnum-rand(1,4),$rnum));
			
			echo ($adhit->pk().'--');
			$adhit->unload();
		}
		
		
		echo ($adhit->count().'--');
		
		$adh = new Model_Adhit();
		$adh->load(rand(0,$adhit->count()));
		$content.= print_r($adh->values(),1);


		//d('');

		/*
		$adhit = new Model_AdHit(NULL,NULL,4);
		d($adhit->loaded());*/


		/*
		$adhit = new Model_Adhit();
		//d($adhit);
		
		d($adhit->get_all_pk(0,10));

		$ads = $adhit->select(6,6);

		//d($ads);
		
		
		foreach ($ads as $a) {
			//var_dump($a);
			echo print_r($a->values(),1).'<br>';
		}
		d('');*/

		$content.= "<br>Total exectution time:".round((microtime(true)-$start_time),3);
		$this->template->content = $content;
	}


	/**
	 * filling ads for testing
	 * @return [type] [description]
	 */
	public function fill()
	{
		$content = '';
		$start_time = microtime(true);

		$ads = new Model_Ad();
		$content.= $ads->count().'--';

		for ($i=0; $i < 1000; $i++) 
		{ 
			$ads = new Model_Ad();
			$ads->set_locations(array(20626,96099,123));
			$ads->id_user = rand(1,10);
			$ads->id_language =  rand(1,3);
			$ads->title = 'Title '.Text::random('alpha',rand(10,50));
			$ads->description = 'Desc '.Text::random('alpha',rand(20,70));
			$ads->description2 = 'Desc2 '.Text::random('alpha',rand(20,70));
			$ads->display_url = 'URL display '.Text::random('alpha',rand(10,30));
			$ads->click_url = 'Click url '.Text::random('alpha',rand(20,130));
			$ads->ip_address = ip2long(Request::$client_ip);
			$ads->displays = rand(1000,100000000);
			$ads->displays_left = rand(100,$ads->displays);
			$ads->save();
		}

		$ad = new Model_Ad();
		$content.= $ads->count().'--';
		$ad->load(rand(0,$ads->count()));
		$content.= print_r($ad->values(),1);



		$content.= "<br>Total exectution time:".round((microtime(true)-$start_time),3);

		$this->template->content = $content;
	}

}