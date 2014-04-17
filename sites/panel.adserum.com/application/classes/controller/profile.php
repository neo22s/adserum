<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Profile extends Auth_Controller {

    

	public function action_index()
	{
		$this->request->redirect(Route::url('default', array('controller'=>'ads')));
	}


	public function action_changepass()
	{
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Change password')));

		$this->template->title   = __('Change password');

		$user = Auth::instance()->get_user();

		$this->template->bind('content', $content);
		$this->template->content = View::factory('pages/profile/edit',array('user'=>$user));
		$this->template->content->msg ='';

		if ($this->request->post())
		{
			$user = Auth::instance()->get_user();
			
			if (core::post('password1')==core::post('password2'))
			{
				$new_pass = core::post('password1');
				if(!empty($new_pass)){

					$user->password = core::post('password1');

					try
					{
						$user->save();
					}
					catch (ORM_Validation_Exception $e)
					{
						throw new HTTP_Exception_500($e->getMessage());
					}
					catch (Exception $e)
					{
						throw new HTTP_Exception_500($e->getMessage());
					}

					Alert::set(Alert::SUCCESS, __('Password is changed'));
				}
				else
				{
					Form::set_errors(array(__('Nothing is provided')));
				}
			}
			else
			{
				Form::set_errors(array(__('Passwords do not match')));
			}
			
		}

	  
	}

	public function action_edit()
	{
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Edit profile')));
		$this->template->title = __('Edit profile');
		// $this->template->title = $user->name;
		//$this->template->meta_description = $user->name;//@todo phpseo
		$user = Auth::instance()->get_user();

		$this->template->bind('content', $content);
		$this->template->content = View::factory('pages/profile/edit',array('user'=>$user));
		// $this->template->content = View::factory('pages/useredit',array('user'=>$user, 'captcha_show'=>$captcha_show));

		if($this->request->post())
		{
			
			$user->name = core::post('name');
			$user->email = core::post('email');
			$user->paypal_email = core::post('paypal_email');
			$user->website = core::post('website');

			try {
				$user->save();
				Alert::set(Alert::SUCCESS, __('You have successfuly changed your data'));
				$this->request->redirect(Route::url('default', array('controller'=>'profile','action'=>'edit')));
				
			} catch (Exception $e) {
				//throw 500
				throw new HTTP_Exception_500();
			}	
		}
	}





}
