<?php 
/**
* Extended functionality for Kohana View
*
* @package    OC
* @category   View
* @author     Chema <chema@garridodiaz.com>
* @copyright  (c) 2012 AdSerum.com
* @license    GPL v3
*/

class View extends Kohana_View{
    
    
    private static $theme       = 'default';
    private static $views_path  = 'views';
    public  static $scripts     = array();
    public  static $styles      = array();
    
    
    //@todo merge and minify
    public static function scripts($scripts,$type='header')
    {
    	$ret = '';
    
    	if (isset($scripts[$type])===TRUE)
    	{
    		foreach($scripts[$type] as $file)
    		{
    			$file = self::public_path($file);
    			$ret .= HTML::script($file, NULL, TRUE);
    		}
    	}
    	return $ret;
    }
    
    //@todo merge and minify, vendor
    public static function styles($styles)
    {
    	$ret = '';
    	foreach($styles as $file => $type)
    	{
    		$file = self::public_path($file);
    		$ret .= HTML::style($file, array('media' => $type));
    	}
    	return $ret;
    }
    
    /**
     *
     * gets the where the views are located in the default theme
     * @return string path
     *
     */
    public static function default_views_path()
    {
    	return 'default'.DIRECTORY_SEPARATOR.self::$views_path;
    }
    
    /**
     *
     * gets the where the views are located in the theme
     * @return string path
     *
     */
    public static function views_path()
    {
    	return self::$theme.DIRECTORY_SEPARATOR.self::$views_path;
    }
    
    /**
     *
     * given a file returns it's public path relative to the selected theme
     * @param string $file
     * @return string
     */
    public static function public_path($file)
    {
    	//not external file we need the public link
    	if (!Valid::url($file))
    	{
    		//@todo add a hook here in case we want to use a CDN
    		return URL::base('http').'themes'.DIRECTORY_SEPARATOR.self::$theme.DIRECTORY_SEPARATOR.$file;
    	}
    	 
    	//seems an external url
    	return $file;
    }
    
    
    
    /**
     * Initialization of the theme that we want to see.
     *
     */
    public static function initialize($theme = 'default')
    {
    	/**
    	 * @todo
    	 * Get the theme
    	 * 1st the one seted in config
    	 * 2nd by param
    	 * 3rd by cookie
    	 * 4th mobile theme?
    	 */
    	$theme = Core::config('site.theme');
    
    	//load theme init.php like in module, to load default JS and CSS for example
    	$init_theme = APPPATH.'themes'.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.'init.php';
    	if (file_exists($init_theme))
    	{
    		//since file exists use it in all the app
    		self::$theme = $theme;
    		Kohana::load($init_theme);
    	}
    	else
    	{
    		//theme doesn't exist or seems wrong...using default
    		$init_theme = APPPATH.'themes'.DIRECTORY_SEPARATOR.self::$theme.DIRECTORY_SEPARATOR.'init.php';
    		if (file_exists($init_theme)) 
                Kohana::load($init_theme);
    	}
    
    	//this is much slower, but more flexible...mmmm
    	/*
    	if (($init_theme = Kohana::find_file(self::$path,'init')))
    	{
    	require_once $init_theme;
    	}
    	*/
    
    
    }
    
    /**
     * Sets the view filename. Overriide from origianl to liad from theme folder
     *
     *     $view->set_filename($file);
     *
     * @param   string  view filename
     * @return  View
     * @throws  View_Exception
     */
    public function set_filename($file)
    {
    	//folder loaded as module in the bootstrap :D
    	if (($path = Kohana::find_file(self::views_path(), $file)) === FALSE)
    	{
    		//in case view not found try to read from default theme
    		if (($path = Kohana::find_file(self::default_views_path(), $file)) === FALSE)
    		{
	    		//still not found :(, try from cascading system
	    		if (($path = Kohana::find_file('views', $file)) === FALSE)
	    		{
                    //d($file);
                    throw new View_Exception('The requested view ´'.$file.'´ could not be found');
	    		}
    		}
    
    	}
    
    	// Store the file path locally
    	$this->_file = $path;
    
    	return $this;
    }
    
}
