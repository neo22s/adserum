<?php 
/**
 * user
 *
 * @author		Chema <chema@garridodiaz.com>
 * @package		OC
 * @copyright	(c) 2012 AdSerum.com
 * @license		GPL v3
 * *
 */
class Model_User extends ORM {

    /**
     * Table name to use
     *
     * @access	protected
     * @var		string	$_table_name default [singular model name]
     */
    protected $_table_name = 'users';

    /**
     * Column to use as primary key
     *
     * @access	protected
     * @var		string	$_primary_key default [id]
     */
    protected $_primary_key = 'id_user';


     /**
     * Status constants
     */
    const STATUS_INACTIVE            = 0;    // not confirmed
    const STATUS_ACTIVE              = 1;   // good!
    const STATUS_MODERATED           = 5;   //was moderated some reason, spammer or whatever..

    protected $_has_many = array(
        'ads' => array(
            'model'       => 'ad',
            'foreign_key' => 'id_user',
        ),
        'orders' => array(
            'model'       => 'order',
            'foreign_key' => 'id_user',
        ),
    );

    /**
     * @var  array  ORM Dependency/hirerachy
     */
    protected $_belongs_to = array(
        'role' => array(
                'model'       => 'role',
                'foreign_key' => 'id_role',
            ),
        'language' => array(
                'model'       => 'language',
                'foreign_key' => 'id_language',
            ),
        /*'location' => array(
                'model'       => 'location',
                'foreign_key' => 'id_location',
            ),*/
    );

    
    /**
     * Rule definitions for validation
     *
     * @return array
     */
    public function rules()
    {
    	return array(
				        'id_user'	    => array(array('numeric')),
				        'name'	        => array(array('max_length', array(':value', 145))),
				        'email'	        => array(array('not_empty'), array('max_length', array(':value', 145)), ),
				        'password'	    => array(array('not_empty'), array('max_length', array(':value', 64)), ),
				        'status'	    => array(array('numeric')),
				        'created'	    => array(),
				        'last_modified' => array(),
				        'logins'	    => array(),
				        'last_login'    => array(),
				        'last_ip'	    => array(),
				        'user_agent'	=> array(),
				        'token'	        => array(array('max_length', array(':value', 40))),
				        'token_created'	=> array(),
				        'token_expires'	=> array(),
				    );
    }
    
    

    /**
     * Label definitions for validation
     *
     * @return array
     */
    public function labels()
    {
    	return array(
    					'id_user'	    => 'Id',
				        'name'	    	=> 'Name',
				        'email'	    	=> 'Email',
				        'password'		=> 'Password',
				        'status'		=> 'Status',
				        'id_role'		=> 'Role',
				        'created'	    => 'Created',
				        'last_modified'	=> 'Last modified',
				        'last_login'	=> 'Last login',
				    );
    }

    /**
     * Filters to run when data is set in this model. The password filter
     * automatically hashes the password when it's set in the model.
     *
     * @return array Filters
     */
    public function filters()
    {
        return array(
    			'password' => array(
                                array(array(Auth::instance(), 'hash'))
                              )
        );
    }

    
	/**
	 * complete the login for a user
	 * incrementing the logins and saving login timestamp
	 * @param integer $lifetime Regenerates the token used for the autologin cookie
	 * 
	 */
	public function complete_login($lifetime=NULL)
	{
		if ($this->_loaded)
		{   
			//want to remember the login using cookie
		    if (is_numeric($lifetime))
		    	$this->create_token($lifetime);
		    
			// Update the number of logins
			$this->logins = new Database_Expression('logins + 1');

			// Set the last login date
			$this->last_login = Date::unix2mysql(time());
			
			// Set the last ip address
			$this->last_ip = ip2long(Request::$client_ip);

			try 
			{
				// Save the user
				$this->update();
			}
			catch (ORM_Validation_Exception $e)
			{
				Form::set_errors($e->errors(''));
			}
			catch(Exception $e)
			{
				throw new HTTP_Exception_500($e->getMessage());
			}
			
		}
	}
	
	/**
	 * Creates a unique token for the autologin
	 * @param integer $lifetime token alive
	 * @return string
	 */
	public function create_token($lifetime=NULL)
	{
		if ($this->_loaded)
		{
			//we need to be sure we have a lifetime
			if ($lifetime==NULL)
			{
				$config = Kohana::$config->load('auth');
				$lifetime = $config['lifetime'];
			}
			
			//we assure the token is unique
			do
			{
				$this->token = sha1(uniqid(Text::random('alnum', 32), TRUE));
			}
			while(ORM::factory('user', array('token' => $this->token))->limit(1)->loaded());
			
			// user Token data
			$this->user_agent    = sha1(Request::$user_agent);
			$this->token_created = Date::unix2mysql(time());
			$this->token_expires = Date::unix2mysql(time() + $lifetime);
			
			try
			{
				$this->update();
			}
			catch(Exception $e)
			{
				throw new HTTP_Exception_500($e->getMessage());
			}
		}
		
	    
	}
	


    /**
    * Check the actual controller and action request and validates if the backuser has access to it
    * @todo    code something that you can show to your mom.
    * @param   string  $action
    * @return  boolean
    */
    public function has_access($controller, $action='index', $directory='')
    {
        $this->get_access_controllers();
        $this->get_access_actions();

        //Las peticiones desde controller back llegan con el 3er parametro,
        // pero normalmente las otras no, llegarían con el dir por delante.
        // Lo ponemos por delante si nos llega para tratarlas todas igual
        if(strlen($directory))
        {
            $controller = $directory.'/'.$controller;
        }

        //die(print_r($this->role->access->find_all()->as_array()));

        $granted = $this->get_access_actions();


        //die(var_dump($granted));
        if((in_array('*.*', $granted)) OR (in_array($controller.'.*', $granted)) OR (in_array($controller.'.'.$action, $granted)))
        {
            //die('1');
            return TRUE;
        }
        else
        {
            //die(print_r($this->granted));
            //die($controller.'.'.$action);
            //die('2');
            return FALSE;
        }

    }

    /**
     *
     * returns an array with all the actions that the backuser can do
     */
    private function get_access_actions()
    {
        $granted = Session::instance()->get('granted_actions');
        if( ! isset($granted))
        {
            $access = $this->role->access->find_all()->as_array();
            $granted = array();


            foreach($access as $k=>$v)
            {
                $granted[] = $v->access;
            }

            //@todo auto controller added
            
            /*
            foreach ($this->get_access_controllers() as $k=>$v)
            {
                $granted[] = $v.'.grid';
                $granted[] = $v.'.grid_js';
                $granted[] = $v.'.grid_data';
            }*/

            $granted[] = 'auth.*';
            $granted[] = 'home.*';

            Session::instance()->set('granted_actions', $granted);
        }

        return $granted;
    }

    /**
     *
     * returns an array with the controllers within the backuser has any right
     */
    private function get_access_controllers()
    {
        $granted = Session::instance()->get('granted_controllers');
        if( ! isset($granted))
        {
            $access = $this->role->access->find_all()->as_array();
            $granted = array();


            foreach($access as $k=>$v)
            {
                $granted[] = strstr($v->access, '.', TRUE);
            }

            Session::instance()->set('granted_controllers', $granted);
        }
        return $granted;
    }

    /**
     * Rudimentary access control list
     * @todo    code something that you can show to your mom.
     * @param   array  $controllers
     * @return  boolean
     */
    public function has_access_to_any($controllers)
    {
        $granted = $this->get_access_controllers();
        $out = array_intersect($granted, $controllers);
        if(( ! empty($out) ) OR (in_array('*', $granted)))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }


    /**
     * sends email to the current user replacing tags
     * @param  string $seotitle 
     * @param  array $replace  
     * @return boolean 
     */
    public function email($seotitle, array $replace = NULL)
    {
        if ($this->loaded())
        {
            if ($replace===NULL) $replace = array(); 
            $email = Model_Content::get($seotitle,NULL,'email');

            //content found
            if ($email->loaded())
            {   
                
                //adding extra replaces
                $replace+= array('[USER.NAME]' =>  $this->name,
                                 '[USER.EMAIL]' =>  $this->email);

                //adding anchor tags to any [URL.* match
                // foreach ($replace as $key => $value) 
                // {
                //     if(strpos($key, '[URL.')===0 OR $key == '[SITE.URL]')
                //         $replace[$key] = '[url='.$value.']'.$value.'[/url]';
                // }

                $subject = str_replace(array_keys($replace), array_values($replace), $email->title);
                $body    = str_replace(array_keys($replace), array_values($replace), $email->description);

                return Email::send($this->email,$email->from_email,$subject,$body);

            }
            else return FALSE;     
        }
        return FALSE;
    }

    /**
     * get url with autolo QL login and redirect
     * @param  string  $route            
     * @param  array  $params           
     * @param  boolean $regenerate_token 
     * @return string                    
     */
    public function ql($route = 'default', array $params = NULL, $regenerate_token = FALSE)
    {
        if ($this->loaded())
        {
            if ($regenerate_token==TRUE)//regenerating the token, for security or new user...
                $this->create_token();

            $url = Route::url($route,$params,'http');
            //we replace api.adserum for panel.adserum to avoid login problems. Not nicest solution but...should work for normal route
            $url = str_replace(Core::config('common.api_url') , Core::config('common.panel_url') , $url);
            $ql = Auth::instance()->ql_encode($this->token,$url);
            
            $url = Route::url('default',array('controller' => 'auth', 'action' => 'ql', 'id' =>$ql),'http');
            $url = str_replace(Core::config('common.api_url') , Core::config('common.panel_url') , $url);

            return $url;
        }
        return NULL;               
    }

    public function form_setup($form)
    {
        $form->fields['id_role']['caption']     = 'name';
        $form->fields['id_language']['caption'] = 'language_name';
        $form->fields['status']['display_as']   = 'bool';
    }

    public function exclude_fields()
    {
       return array('id_location','password','created','user_agent','token');
    }

    protected $_table_columns = array ();//@todo

} // END Model_User
