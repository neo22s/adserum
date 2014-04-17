<?php 
/**
 * restcontroller JS format for OC app and CRUD
 *
 * @package    OC
 * @category   Controller
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2012 AdSerum.com
 * @license    GPL v3
 */

class ControllerRest extends Kohana_Controller
{

    /**
     * @var $_orm_model ORM model name
     */
    protected $_orm_model = NULL;

    /**
     *
     * list of actions for the crud, original not modified
     * @var array
     */
    protected $_crud_actions = array('index','delete','create','update');

    /**
     *
     * list of possible actions for the crud, you can modify it to allow access or deny, by default all
     * @var array
     */
    public $crud_actions = array('index','delete','create','update');

    /**
     *
     * Contruct that checks you are loged in before nothing else happens!
     */
    function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        
        //we check if that action can be done, if not send KO
        if (!$this->allowed_crud_action())
        {
            //why this doesnt work?? no idea...
            /*$this->before();
            $this->template->content = 'Action `'.$this->request->action().'` Not allowed';
            $this->after();*/

            //ugly fix OMG!! kill me @ SFO
            die( json_encode('Action `'.$this->request->action().'` Not allowed') );
        }
    }

    /**
     * Initialize properties before running the controller methods (actions),
     * so they are available to our action.
     */
    public function before()
    {
        parent::before();
        // Load the template
        $this->template = View::factory('js');
    }
    
    /**
     * Fill in default values for our properties before rendering the output.
     */
    public function after()
    {
        parent::after();

        //from the other controllers we only need to set up th $content var
        //@todo detect if content is a model loded with values parse it to array
        $this->template->content = json_encode($this->template->content);
        //render the template
        $this->response->body($this->template->render());
       
    }
    

    /**
     *
     * Loads a basic list info
     * 
     */
    public function action_index()
    {

        $elements = ORM::Factory($this->_orm_model);//->find_all();
        //@todo determine how many elements we return, or paginate? or allow by params the offset?
        $elements = $elements->limit(10)->find_all();
        $data = array();
        foreach ($elements as $element) 
        {
            $data[] = $element->as_array();
        }
        //d($data);
        $this->template->content = $data;

    }

    /**
     * CRUD controller: DELETE
     */
    public function action_delete()
    {
        $element = ORM::Factory($this->_orm_model, $this->request->param('id'));

        try
        {
            $element->delete();
            $this->template->content = 'OK';
        }
        catch (Exception $e)
        {
            $this->template->content = $e->getMessage();
        }
    }

    /**
     * CRUD controller: CREATE
     */
    public function action_create()
    {
        $element = ORM::Factory($this->_orm_model);
    
        if ($this->request->post())
        {
            $element->values($this->request->post());

            try
            {
                $element->create();
                $this->template->content = 'OK';
            }
            catch (Exception $e)
            {
                $this->template->content = $e->getMessage();
            }
        }
    
    }
    
    
    /**
     * CRUD controller: UPDATE
     */
    public function action_update()
    {
        $element = ORM::Factory($this->_orm_model,$this->request->param('id'));
    
        if ($this->request->post())
        {
            $element->values($this->request->post());

            try
            {
                $element->update();
                $this->template->content = 'OK';
            }
            catch (Exception $e)
            {
                $this->template->content = $e->getMessage();
            }
        }
    }

    /**
     *
     * tells you if the crud action it's allowed in the controller
     * @param array $action
     * @return boolean
     */
    public function allowed_crud_action($action = NULL)
    {
        if ($action === NULL)
            $action = $this->request->action();

        //its a crud request? check whitelist
        if (in_array($action, $this->_crud_actions) )
        {
            //its not in the speific whitelist of the controller?
            if (!in_array($action, $this->crud_actions) )
            {
                //access not allowed
                return FALSE;
            }
        }
        return TRUE;
    }


}