<?php 
class Controller_Redis extends Auth_Controller {


	/**
	 * redis info
	 * @return [type] [description]
	 */
	public function action_index()
	{

		$this->template->styles = array('css/pages/reports.css' => 'screen');
        $this->template->scripts['footer'] = array('js/bootstrap-datepicker.js',
                                                    'js/pages/stats/panel.js');

		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Redis info')));
		$this->template->title = __('Redis info');

		$redis = new remodel();

		$redis = $redis->info();
		//$redis->flushdb();
		
		$this->template->bind('content', $content);
    	$content = View::factory('pages/redis',array('redis'=>$redis));
	}

}