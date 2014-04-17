<?php 
class Controller_Moderation extends Auth_Controller {



    public function __construct($request, $response)
	{
		parent::__construct($request, $response);
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Moderation'))->set_url(Route::url('default',array('controller'  => 'moderation'))));
		
	}
    
	public function action_index()
	{

		//@todo allow status filters like in log
		$filter = $this->request->param('id','moderate');

		$this->template->title = __('Ads').' '.$filter;
		Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title));
		

		$ads = new Model_Ad();
		$pagination = Pagination::factory(array(
					'view'           => 'pagination',
					'total_items' 	 =>  $ads->count($filter),
		))->route_params(array(
					'directory'  => $this->request->directory(),
					'controller' => $this->request->controller(),
					'action' 	 => $this->request->action(),
                    'id'         => $filter,
		));

		//selecting the ids to display
		$pks = $ads->get_ads($filter,$pagination->offset,$pagination->items_per_page);

		//getting the values
		$ads = $ads->select($pks);
		
		$pagination = $pagination->render();

		$this->template->bind('content', $content);
    	$content = View::factory('pages/moderation/list',array('ads' => $ads,
    															'pagination'=>$pagination,
    															'title' => $this->template->title));
	}

	public function action_edit()
	{

		$id = $this->request->param('id');

		if ($id===NULL OR !is_numeric($id))
			$this->request->redirect(Route::get('default')->uri(array('controller' => 'moderation', 'action'     => 'index')));

		$ad = new Model_Ad(NULL,NULL,$id);

		if (!$ad->loaded())
			$this->request->redirect(Route::get('default')->uri(array('controller' => 'moderation', 'action'     => 'index')));

		$errors = NULL;	 
		$user = new Model_User($ad->id_user);

		if ($this->request->post())
		{
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
	            ->rule('durl', 'max_length', array(':value', 35));

	        if ($validation->check())
	        {
	        	
	        	//save ad
	        	$ad->title 			= Core::post('title');
	            $ad->description 	= Core::post('desc');
	            $ad->description2	= Core::post('desc2');
	            $ad->click_url 		= Core::post('url');
	            $ad->display_url 	= Core::post('durl');
	            $ad->id_language 	= Core::post('lang');
	            $ad->displays 		= Core::post('displays');
	            $ad->displays_left 	= Core::post('displays');
                $ad->status         = Core::post('status');
	            $locations 			= Core::post('locations');
	        	if (!is_array($locations))
	            	$locations = array($locations);
	            $ad->set_locations($locations);
	        	$ad->save();

	        	//change status and add it to the published lists
	        	$ad->publish();

	        	// send email that was published
	        	$user->email('ad.published');
			
				//if everything ok
				Alert::set(Alert::SUCCESS, __('Advertisement Published'));
				$this->request->redirect(Route::get('default')->uri(array('controller' => 'moderation', 'action'     => 'index')));
	        
	        }
	 
	        // Validation failed, collect the errors
	        $errors = $validation->errors('ad');
					
		}
		
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Edit').' '.$id));
		$this->template->title = __('Edit').' '.$id;
		
		$this->template->bind('content', $content); 
    	$content = View::factory('pages/moderation/edit',array('ad' => $ad));
    	$content->errors = $errors;     
    	//locations show name, and allow select more, like at new ad.
    	$locations = new Model_Location();
    	$content->locations = $locations->where('id_location','in',$ad->locations())->find_all();
    	//load order and user information
    	$order = new Model_Order();
    	$content->order = $order->where('id_ad','=',$ad->id_ad)->limit(1)->find();
    	$content->user  = $user;
    	$content->geoip = Model_Location::get_geoip(long2ip($ad->ip_address));

		
	}


	public function action_delete()
	{

		$id = $this->request->param('id');

		if ($id===NULL OR !is_numeric($id))
			$this->request->redirect(Route::get('default')->uri(array('controller' => 'moderation', 'action'     => 'index')));

		$ad = new Model_Ad(NULL,NULL,$id);

		if ($ad->delete())//if everything ok
			Alert::set(Alert::SUCCESS, __('Advertisement Deleted'));
		else
			Alert::set(Alert::ERROR, __('Advertisement could not be deleted'));
	        	
		
		$this->request->redirect(Route::get('default')->uri(array('controller' => 'moderation', 'action'     => 'index')));
		
	}
}