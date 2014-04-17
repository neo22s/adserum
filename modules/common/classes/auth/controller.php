<?php 
/**
 * Front end controller for OC user/admin auth in the app
 * Also contains basic CRUD actions for the
 *
 * @package    OC
 * @category   Controller
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2012 AdSerum.com
 * @license    GPL v3
 */

class Auth_Controller extends Controller
{
	/**
	 *
	 * Contruct that checks you are loged in before nothing else happens!
	 */
	function __construct(Request $request, Response $response)
	{
		// Assign the request to the controller
		$this->request = $request;

		// Assign a response to the controller
		$this->response = $response;


		//login control, don't do it for auth controller so we dont loop
		if ($this->request->controller()!='auth')
		{
			//home url used in the breadcrumb
			//Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
											
			//admin url used in the breadcrumb
			Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Panel'))->set_url(Route::url('default',array('controller'  => 'home'))));
					
			//check if user is login
			//if (!Auth::instance()->logged_in($this->role))
			if (!Auth::instance()->logged_in( $request->controller(), $request->action(), $request->directory()))
			{
			    //Alert::set(Alert::ERROR, __('You do not have permissions to access '.$request->controller().' '.$request->action()));
			    Alert::set(Alert::ALERT, __('You need to login to access.'));
				$url = Route::get('default')->uri(array('controller' => 'auth', 'action'     => 'login'));
				$this->request->redirect($url);
			}

		}

		//the user was loged in and with the right permissions
		
		
	}


}
