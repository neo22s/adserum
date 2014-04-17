<?php 
class Controller_Home extends Auth_Controller {

    
	public function action_index()
	{

		/*
		$this->template->title = 'Adserum Panel';
		$this->template->content = View::factory('pages/home');

		$this->template->styles = array('css/pages/dashboard.css' => 'screen');*/


		//only for admin
		if (Auth::instance()->get_user()->id_role==1) 
		{
			$url = Route::get('default')->uri(array('controller' => 'stats', 'action'     => 'panel'));
		}
		else
		{
			//@todo have a nice welcome page...
			$url = Route::get('default')->uri(array('controller' => 'ads', 'action'     => 'index'));
		}
			

		$this->request->redirect($url);
	}
    
}
