<?php 

class Controller_Auth extends Auth_Controller {
    
    /**
     * Initialize properties before running the controller methods (actions),
     * so they are available to our action.
     */
    public function before()
    {

        if($this->auto_render===TRUE)
        {
        	$this->template = 'auth';

            //Get Language
            self::$lang = Model_Language::get_by_param();
            $language = self::$lang->language;
			I18n::gettext(self::$lang->locale,self::$lang->charset);					
        	
        	
        	// Load the template
        	$this->template = View::factory($this->template);
        	
            // Initialize empty values
            $this->template->title            = '';
            $this->template->meta_keywords    = '';
            $this->template->meta_description = '';
            $this->template->meta_copywrite   = 'Adserum '.Core::version;
            $this->template->language		  = $language;
            $this->template->content          = '';
            $this->template->styles           = array();
            $this->template->scripts          = array();

            $this->template->scripts['footer'] = array('js/signin.js');
        }
    }
    

    /**
     * 
     * Check if we need to login the user or display the form, same form for normal user and admin
     */
	public function action_login()
	{	    
		
	    //if user loged in redirect home
	    if (Auth::instance()->logged_in())
	    {
	    	Auth::instance()->login_redirect();
	    }
	    //posting data so try to login
	    elseif ($this->request->post() AND CSRF::valid('login'))
	    {	        
            Auth::instance()->login($this->request->post('email'), $this->request->post('password'),(bool) $this->request->post('remember'));
            
            //redirect index
            if (Auth::instance()->logged_in())
            {
            	// home redirect
            	Auth::instance()->login_redirect();
            }
            else 
            {
                Form::set_errors(array(__('Wrong email or password')));
            }
	        
	    }
	    	    
	    //Login page
	    $this->template->title   = __('Login');	    
	    $this->template->content = View::factory('pages/auth/login');
	}
	
	/**
	 * 
	 * Logout user session
	 */
	public function action_logout()
	{
	    Auth::instance()->logout(TRUE);    
	    $this->request->redirect(Route::url('default',array('controller'=>'auth','action'=>'login')));
	
	}
	
	/**
	 * Sends an email with a link to change your password
	 * 
	 */
	public function action_forgot()
	{
		//d(Core::post('email'));

		//if user loged in redirect home
		if (Auth::instance()->logged_in())
		{
			$this->request->redirect(Route::url('default'));
		}
		//posting data so try to remember password
		elseif ($_POST AND CSRF::valid('forgot'))
		{
			$email = $this->request->post('email');

			if (Valid::email($email,TRUE))
			{
				//check we have this email in the DB
				$user = new Model_User();
				$user = $user->where('email', '=', $email)
							->limit(1)
							->find();
				
				if ($user->loaded())
				{
					//we get the QL, and force the regen of token for security
					$url_ql = $user->ql('default',array( 'controller' => 'profile', 
														  'action'     => 'changepass'),TRUE);

					$ret = $user->email('remember',array('[URL.QL]'=>$url_ql));

					//email sent notify and redirect him
					if ($ret)
					{
						Alert::set(Alert::SUCCESS, __('Email to recover password sent'));
						$this->request->redirect(Route::url('default',array('controller'=>'auth','action'=>'login')));
					}
						
				}
				else
				{
					Form::set_errors(array(__('User not in database')));
				}
				
			}
			else
			{
				Form::set_errors(array(__('Email required')));
			}
		}
		
		//template header
		$this->template->title            = __('Remember password');	
		$this->template->content = View::factory('pages/auth/forgot');
	}
	
	/**
	 * Simple register for user
	 *
	 */
	public function action_register()
	{
		
		$this->template->content = View::factory('pages/auth/register');
		$this->template->content->msg = '';
		
		//if user loged in redirect home
		if (Auth::instance()->logged_in())
		{
			$this->request->redirect(Route::url('default'));
		}
		//posting data so try to remember password
		elseif ($_POST AND CSRF::valid('register'))
		{
			$email = $this->request->post('email');
				
			if (Valid::email($email,TRUE))
			{
				if ($this->request->post('password1')==$this->request->post('password2'))
				{
					//check we have this email in the DB
					$user = new Model_User();
					$user = $user->where('email', '=', $email)
							->limit(1)
							->find();
			
					if ($user->loaded())
					{
						Form::set_errors(array(__('User already exists')));
					}
					else
					{
						//create user
						$user->email 	= $email;
						$user->name		= $this->request->post('name');
						$user->status	= 1;
						$user->id_role	= 3;//this is hardcoded @todo
						$user->password = $this->request->post('password1');
						
						try
						{
							$user->save();
						}
						catch (ORM_Validation_Exception $e)
						{
							Form::errors($content->errors);
						}
						catch (Exception $e)
						{
							throw new HTTP_Exception_500($e->getMessage());
						}

						
						//send email
						$user->email('register',array('[USER.PWD]'=>$this->request->post('password1'),
													'[URL.QL]'=>$user->ql('default',NULL,TRUE))
													);

						Alert::set(Alert::SUCCESS, __('Welcome!'));
						//login the user
						Auth::instance()->login($this->request->post('email'), $this->request->post('password1'));
						$this->request->redirect(Route::url('default',array('controller'=>'auth','action'=>'login')));
						
					}
		
				}
				else
				{
					Form::set_errors(array(__('Passwords do not match')));
				}
			}
			else
			{
				Form::set_errors(array(__('Email required')));
			}
				
		}
	
		//template header
		$this->template->title            = __('Register new user');
			
	}


	/**
	 *
	 * Quick login for users.
	 * Useful for confirmation emails, remember passwords etc...
	 */
	public function action_ql()
	{
		$ql = $this->request->param('id');
		
		$url = Auth::instance()->ql_login($ql);
		
		//not a url go to login!
		if ($url==FALSE)
		{
			$url = Route::url('default',array(
										  'controller' => 'auth', 
										  'action'     => 'login'),'http');	
		}
		$this->request->redirect($url);
	}


    /**
     * Simple publish new advertisement form that registers a user
     *
     */
    public function action_new()
    {
        
        $this->template->scripts['footer'] = array( 'js/plugins/validate/jquery.validate.js',
                                                    'js/pages/ads/new.js',
                                                    'js/pages/ads/new_auth.js');
        $this->template->styles = array('css/pages/new_auth_style.css' => 'screen');

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

            if (!Auth::instance()->logged_in())
            {    
                $validation ->rule('name', 'not_empty')
                ->rule('name', 'min_length', array(':value', 2))
                ->rule('name', 'max_length', array(':value', 145))

                ->rule('email', 'not_empty')
                ->rule('email', 'email')
                ->rule('email', 'max_length', array(':value', 145));
            }

            $product = new Model_Product(Core::post('product'));

            if ($validation->check() and $product->loaded())
            {
                //get or create user
                if (!Auth::instance()->logged_in())
                {  
                    //check we have this email in the DB
                    $email = $this->request->post('email');
                    $user = new Model_User();
                    $user = $user->where('email', '=', $email)
                            ->limit(1)
                            ->find();
                    if ($user->loaded())
                    {
                        Alert::set(Alert::ERROR, __('Email exists, please log in before publishing'));
                        $this->request->redirect(Route::url('default',array('controller'=>'auth','action'=>'login')));
                    }
                    else
                    {
                        $password = Text::random();
                        //create user
                        $user->email    = $email;
                        $user->name     = $this->request->post('name');
                        $user->status   = 1;
                        $user->id_role  = 3;
                        $user->password = $password;
                        
                        try
                        {
                            $user->save();
                        }
                        catch (Exception $e)
                        {
                            throw new HTTP_Exception_500($e->getMessage());
                        }
                        
                        //send email
                        $user->email('register',array('[USER.PWD]'=>$password,
                                                    '[URL.QL]'=>$user->ql('default',NULL,TRUE))
                                                    );
                        Auth::instance()->login($email, $password);
                    }
                }
                else
                    $user = Auth::instance()->get_user();
        

                // Data has been validated, register the ad
                $ad->id_user        = $user->id_user;
                $ad->title          = Core::post('title');
                $ad->description    = Core::post('desc');
                $ad->description2   = Core::post('desc2');
                $ad->click_url      = ( strpos('http://', Core::post('url'))==0 OR strpos('https://', Core::post('url'))==0 )? Core::post('url') : 'http://'.Core::post('url');
                $ad->display_url    = Core::post('durl');
                $ad->ip_address     = ip2long(Request::$client_ip);

                $ad->displays       = $product->displays;
                $ad->displays_left  = $product->displays;

                $lang               = Model_Language::get_by_lang(Core::post('lang'));
                $ad->id_language    = $lang->id_language;

                $countries  = Core::post('countries');
                if (!is_array($countries))
                    $countries = array($countries);
                $countries_ids = Model_Location::get_countries_id($countries);

                $cities     = Core::post('cities');
                if (!is_array($cities))
                    $cities = array($cities);

                $locations  = array_merge($countries_ids,$cities);
                $ad->set_locations($locations);

                $ad->create();

                //redirect to payment gateway
                if ($product->price > 0)
                {
                    //create order
                    $order = new Model_Order();
                    $order->id_product  = $product->id_product;
                    $order->id_user     = $user->id_user;
                    $order->id_ad       = $ad->id_ad;
                    $order->amount      = $product->price;
                    $order->ip_address  = $ad->ip_address;
                    $order->status      = Model_Order::STATUS_CREATED;
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
        $content = View::factory('pages/auth/new');
        $content->errors = $errors;
        $content->geo    = $geo;
        $content->countries = Model_Location::get_countries();
        $content->products = Model_Product::get_all();
            
    }

    /**
     * used to be in controller location, but we need it public :()
     * @return [type] [description]
     */
    public function action_cities()
    {
        $this->auto_render = FALSE;
        $this->template    = View::factory('js');

        $search     = Core::get('s','bar');
        $countries  = Core::get('countries',array('ES'));

        $cities = array();
        
        $city = new Model_Location();
        $city->where('country','in',$countries)
             ->where('city','IS NOT',NULL)
             ->where('city','like',$search.'%');
        $cities_country = $city->limit(20)->cached()->find_all();

        foreach ($cities_country as $c) 
        {
            $cities[] = array(
                'label' =>  ($c->country!='')? $c->city.' ('.$c->country.')':$c->city,//$c->country.'-'.$c->city,
                'id'    =>  $c->id_location,
            );
        }
        
        $this->template->content = json_encode($cities);
        $this->response->body($this->template->render());
    }


}
