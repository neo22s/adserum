<?php 
/**
 * Simple email class
 *
 * @package    OC
 * @category   Core
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2012 Open Classifieds Team
 * @license    GPL v3
 */

class Email {
	
	/**
	 * Simple function to send an email
	 *
	 * @param string $to
	 * @param string $from
	 * @param string $subject
	 * @param string $body
	 * @param string $extra_header
	 * @return boolean
	 */
	public static function send($to,$from,$subject,$body,$headers=NULL,$view = 'email')
	{
		//d(func_get_args());
		if ($headers==NULL)
		{
			$headers = 'MIME-Version: 1.0' . PHP_EOL;
			$headers.= 'Content-type: text/html; charset=utf8'. PHP_EOL;
			$headers.= 'From: '.$from.PHP_EOL;
			$headers.= 'Reply-To: '.$from.PHP_EOL;
			$headers.= 'Return-Path: '.$from.PHP_EOL;
			$headers.= 'X-Mailer: PHP/' . phpversion().PHP_EOL;
		}
	

		//get the template from the html email boilerplate

		$body = Text::bb2html($body,TRUE);
        //get the template from the html email boilerplate
        $body = View::factory($view,array('title'=>$subject,'content'=>nl2br($body)))->render();

		
		switch (Core::config('common.email_method')) 
		{
			case 'elasticemail':
					//creating the conection to elasticemail 
					$elasticEmail = new ElasticEmail(Core::config('common.elastic_user'),Core::config('common.elastic_api')); 

					//sending the email 
					return $elasticEmail->sendMail($from,'Adserum.com',$to,$subject,$body);   
				break;
			
			default:
					return mail($to,$subject,$body,$headers);
				break;
		}
		
		
	}




} //en email