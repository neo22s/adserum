<?php 
class Controller_log extends Auth_Controller {

	public function __construct($request, $response)
	{
		parent::__construct($request, $response);
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Logs'))->set_url(Route::url('default',array('controller'  => 'log'))));
		
	}
    
	public function action_index()
	{
        $this->template->styles = array('css/pages/reports.css' => 'screen',
                                        'css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('js/bootstrap-datepicker.js',
                                                    'js/pages/log.js');

        $data = array();
        $data['sites']= array('www','panel','api');
        $data['default_apache'] = Core::get('id_site','www');
        $data['default_ko']     = Core::post('date_log_ko', 'www*'.date('Y-m-d'));

        $this->template->bind('content', $content);        
        $content = View::factory('pages/log',$data);
        $this->template->title = __('Logs');
	}
	
	public function action_apache()
	{
        $this->auto_render = FALSE;

        $id = $this->request->param('id','www');

        if ($id === 'www')
            $id = 'adserum.com';
        else
            $id.= '.adserum.com';

        $file = realpath(APPPATH.'../../'.$id.'/application/logs/').'/error_log';
        
        $this->response->body($this->tail($file));
    }  


    public function action_ko()
    {
        $this->auto_render = FALSE;

        $id = $this->request->param('id','www*'.date('Y/m/d'));

        $param = explode('*', $id);
        $id = $param[0];

        if ($id === 'www')
            $id = 'adserum.com';
        else
            $id.= '.adserum.com';

        $file = realpath(APPPATH.'../../'.$id.'/application/logs/') .'/'.str_replace('-', '/', $param[1]).'.php';
        
        $this->response->body($this->tail($file));
    }  

    
    function tail($file,$opts = '-n 50 ')
    {
        if (!file_exists($file))
            return 'File '.$file.' not found';;

        exec('tail '.$opts.$file, $mytail);
        $res_tail='';

        foreach($mytail as $arr)
            $res_tail.=$arr.'<br/>';

        $start = '
        <html>
        <head>
            <script type="text/javascript">
                function start(){
                    document.body.scrollTop = document.body.offsetHeight;
                }
            </script>
        </head>
        <body onload="start()" style="background-color:#fff;"><p>';
        $end = '</p>
        </body>
        </html>
        ';

        return $start.$res_tail.$end;
    }

	

}
