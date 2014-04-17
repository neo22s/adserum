<?php 
class Controller_Tools extends Auth_Controller {

	public function __construct($request, $response)
	{
		parent::__construct($request, $response);
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Tools'))->set_url(Route::url('default',array('controller'  => 'tools'))));
		
	}
    
	public function action_index()
	{
		Request::current()->redirect(Route::url('default',array('controller'  => 'tools','action'=>'cache')));	
	}
	
	
	public function action_cache()
	{
		
		Cache::instance()->delete_all();		

		Alert::set(Alert::SUCCESS, __('Success, cache deleted'));

        Request::current()->redirect(Route::url('default'));  
	}
	

}
