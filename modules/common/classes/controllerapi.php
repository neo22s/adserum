<?php 
/**
 * Front end controller for adserum api app
 *
 * @package    OC
 * @category   Controller
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2012 AdSerum.com
 * @license    GPL v3
 */

class ControllerApi extends Kohana_Controller
{
    public $template = 'main';

    public static $lang;
    
    /**
     * Initialize properties before running the controller methods (actions),
     * so they are available to our action.
     */
    public function before()
    {
        parent::before();
            
            $language = substr(core::get('lang','en-US'), 0,2);

        //language setting
        //if ($this->request->param('lang'))
       // {
            self::$lang = new Model_Language();
            self::$lang ->where('language', '=', $language)
                        ->limit(1)->cached()->find();
            if (!self::$lang->loaded())
            {
                self::$lang ->where('language', '=', 'en')->limit(1)->cached()->find();
            }
            
            I18n::gettext(self::$lang->locale,self::$lang->charset);
       // }

        // Load the template
        $this->template = View::factory($this->template);
    }
    
    /**
     * Fill in default values for our properties before rendering the output.
     */
    public function after()
    {
    	parent::after();
    	$this->response->body($this->template->render());
    }
}