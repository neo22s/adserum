<?php 
/**
* Error controller
*
* @package    OC
* @category   Controller
* @author     Chema <chema@garridodiaz.com>
* @copyright  (c) 2012 AdSerum.com
* @license    GPL v3
*/
class Controller_Error extends ControllerApi
{
   
    /**
     * @var string
     */
    protected $_requested_page;
 
    /**
     * @var string
     */
    protected $_message;
 
    /**
     * Pre determine error display logic
     */
    public function before($template = NULL)
    {        
        parent::before();
 
        // Sub requests only!
        if ( ! $this->request->is_initial() )
        {
            if ($message = rawurldecode($this->request->param('message')))
            {
                $this->_message = $message;
            }
 
            if ($requested_page = rawurldecode($this->request->param('origuri')))
            {
                $this->_requested_page = $requested_page;
            }
        }
        else
        {
            // This one was directly requested, don't allow
            $this->request->action(404);
 
            // Set the requested page accordingly
            $this->_requested_page = Arr::get($_SERVER, 'REQUEST_URI');
        }
 
        $this->response->status((int) $this->request->action());
    }

    /**
     * Serves HTTP 404 error page
     */
    public function action_404()
    {
        die('404 '.__('not found'));
    }
 
    /**
     * Serves HTTP 500 error page
     */
    public function action_500()
    {
        //@todo actually we should display another banner just in case...
        die('<a target="_blank" href="http://adserum.com/?utm_campaign='.date('Y-m-d').'&utm_medium=banner&utm_source=adserum_error">
            <img src="http://api.adserum.com/images/adserum_bottom.png"></a>');
        //die('Powered by <a href="http://adserum.com">Adserum.com</a>');
    }
}