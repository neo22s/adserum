<?php 

/**
 * Paypal class
 *
 * @package    OC
 * @category   Core
 * @author     Chema <chema@garridodiaz.com>, Slobodan <slobodan.josifovic@gmail.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

Class Paypal {

	/**
	 * for form generation
	 */
	const url_sandbox_gateway	= 'https://www.sandbox.paypal.com/cgi-bin/webscr';
	const url_gateway 			= 'https://www.paypal.com/cgi-bin/webscr';


	
	/**
	 * IPN URL
	 */
	const ipn_sandbox_url	 = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
	const ipn_url 	 		 = 'https://www.paypal.com/cgi-bin/webscr';



    public static function validate_ipn()
    {
        if (core::config('paypal.sandbox'))
            $ipn_url  = self::ipn_sandbox_url;
        else
            $ipn_url  = self::ipn_url;

        // STEP 1: Read POST data
 
        // reading posted data from directly from $_POST causes serialization 
        // issues with array data in POST
        // reading raw POST data from input stream instead. 
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
          $keyval = explode ('=', $keyval);
          if (count($keyval) == 2)
             $myPost[$keyval[0]] = urldecode($keyval[1]);
        }
        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';
        if(function_exists('get_magic_quotes_gpc')) {
           $get_magic_quotes_exists = true;
        } 
        foreach ($myPost as $key => $value) {        
           if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
                $value = urlencode(stripslashes($value)); 
           } else {
                $value = urlencode($value);
           }
           $req .= "&$key=$value";
        }
         
         
        // STEP 2: Post IPN data back to paypal to validate
        $ch = curl_init($ipn_url);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
         
        if( !($res = curl_exec($ch)) ) {
            // error_log("Got " . curl_error($ch) . " when processing IPN data");
            curl_close($ch);
            exit;
        }
        curl_close($ch);
         
        // STEP 3: Inspect IPN validation result and act accordingly
        if (strcmp ($res, "VERIFIED") == 0) 
        {
            return TRUE;
        }
        // Verfication result was invalid.  Log it.
        elseif(strcmp ($res, "INVALID") == 0)
        {
            Kohana::$log->add(Log::ERROR, 'Paypal invalid payment error. Result: '.$result.' Data: '. json_encode($_POST));
            return FALSE;
        }
        // Unknown result. Log it.
        else
        {
            Kohana::$log->add(Log::ERROR, 'Unknown result from IPN verification. Result: '.$result.' Data: '. json_encode($_POST));
            return FALSE;
        }

    }

	/**
	 * Validate an IPN request that has been recieved.
	 *
	 */
	public static function validate_ipn_new()
	{
		// lets prepend the command to the data we need to verify.
		$data_send = array_merge(array('cmd', '_notify-validate'), $_POST);


		if (core::config('paypal.sandbox'))
			$ipn_url  = self::ipn_sandbox_url;
		else
			$ipn_url  = self::ipn_url;

		// Init cURL
		$ch = curl_init($ipn_url);

//https://github.com/Austinb/Paypal/blob/master/classes/paypal/ipn.php
//https://www.x.com/developers/PayPal/documentation-tools/code-sample/216623

		// Set the cURL options
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data_send));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

		// Now run the command
		if(!$result = trim(curl_exec($ch)))
		{
			Kohana::$log->add(Log::ERROR, 'Paypal connection error');
			return FALSE;
		}

		// Close the cURL connection.
		curl_close($ch);

		// Now lets check the result.
		if($result == 'VERIFIED')
		{
			return TRUE;
		}
		// Verfication result was invalid.  Log it.
		elseif($result == 'INVALID')
		{
			//Kohana::$log->add(Log::ERROR, 'Paypal invalid payment error. Result: '.$result.' Data: '. json_encode($_POST));
			return FALSE;
		}
		// Unknown result. Log it.
		else
		{
			//Kohana::$log->add(Log::ERROR, 'Unknown result from IPN verification. Result: '.$result.' Data: '. json_encode($_POST));
			return FALSE;
		}
	}




	/**
	 * For IPN validation
	 */
	/*const ipn_sandbox_url 	 = 'ssl://www.sandbox.paypal.com';
	const ipn_url 			 = 'ssl://www.paypal.com';
	const ipn_sandbox_host   = 'www.sandbox.paypal.com';
	const ipn_host 			 = 'www.paypal.com';*/
	

	/**
	 *
	 * validates the IPN
	 */
	public static function validate_ipn_old()
	{	
		if (core::config('paypal.sandbox'))
		{
			$url  = self::ipn_sandbox_url;
			$host = self::ipn_sandbox_host;
		} 
		else
		{
			$url  = self::ipn_url;
			$host = self::ipn_host;
		} 

		$result = FALSE;

		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';

		foreach ($_POST as $key => $value) 
		{
			$value = urlencode(stripslashes($value));
			
			if($key=='sess' || $key=='session') continue;
				$req .= '&'.$key.'='.$value;
		}

		$header  = 'POST /cgi-bin/webscr HTTP/1.1\r\n';
		$header .= 'Content-Type: application/x-www-form-urlencoded\r\n';
		$header .= 'Host: '.$host.'\r\n'; 
		$header .= 'Content-Length: ' . strlen($req) . '\r\n';
		$header .= 'Connection: close\r\n\r\n';

		$fp = fsockopen ($url, 443, $errno, $errstr, 60);
		
		if (!$fp) 
		{
			Kohana::$log->add(Log::ERROR, 'Paypal connection error');
		} 
		else 
		{
			fputs($fp, $header . $req);
			
			while (!feof($fp)) 
			{
				$res = fgets ($fp, 1024);
			
				if (stripos($res, 'VERIFIED') !== FALSE) 
				{
					$result = TRUE;
				}
				else if (stripos($res, 'INVALID') !== FALSE) 
				{
					Kohana::$log->add(Log::ERROR, 'INVALID payment');
				}
			}
			fclose ($fp);
		}
		return $result;
	}



}