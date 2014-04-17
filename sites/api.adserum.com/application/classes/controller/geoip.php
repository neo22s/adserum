<?php 

class Controller_Geoip extends ControllerApi {

	public function action_index()
	{		
	   	//@todo do not forget this line!!	
		//$ip = '70.35.42.243';
		$ip = Request::$client_ip;
		
		
		$record = Geoip3::instance()->record( $ip);
		


		if ($record===NULL)
            $record = 'none';

   

    	$this->template->content =   $record;
      		
	}
}