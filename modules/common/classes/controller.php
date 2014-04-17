<?php 
/**
 * Default controller
 *
 * @package    Core
 * @category   Controller
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2012 AdSerum.com
 * @license    GPL v3
 */

class Controller extends Kohana_Controller
{
    public $template = 'main';

    /**
     * @var  boolean  auto render template
     */
    public $auto_render = TRUE;
    
    public static $lang;
    
    /**
     * Initialize properties before running the controller methods (actions),
     * so they are available to our action.
     */
    public function before()
    {
        parent::before();
        if($this->auto_render===TRUE)
        {
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
            $this->template->header           = View::factory('header');
            $this->template->content          = '';
            $this->template->footer           = View::factory('footer');
            $this->template->styles           = array();
            $this->template->scripts          = array();

            if (Auth::instance()->logged_in())
                $this->template->user = Auth::instance()->get_user();
        }
    }
    
    /**
     * Fill in default values for our properties before rendering the output.
     */
    public function after()
    {
    	parent::after();
    	if ($this->auto_render === TRUE)
    	{
             $this->template->title .= ' - adserum.com'; 
    		// Add defaults to template variables.
    		$this->template->styles  = array_reverse(array_merge($this->template->styles, View::$styles));
    		$this->template->scripts = array_reverse(array_merge_recursive(View::$scripts,$this->template->scripts));
    		
    		/*
    		 //auto generate keywords and description from content
    		if ($this->template->meta_keywords=='' || $this->template->meta_description=='')
    		{
    		$seo = new phpSEO($this->template->content,CHARSET);//loading the php SEO class
    		
    		if ($this->template->meta_keywords=='')//not meta keywords given
    		{
    		$this->template->meta_keywords=$seo->getKeyWords(12);
    		}
    		if ($this->template->meta_description=='')//not meta description given
    		{
    		$this->template->meta_description=$seo->getMetaDescription(150);//die($this->template->meta_description);
    		}
    		}*/

            $this->response->body($this->template->render());
    	}
    	
       
    }
}