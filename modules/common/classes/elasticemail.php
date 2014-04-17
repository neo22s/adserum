<?php 
/* 
 * Name:    ElasticEmail 
 * Version:    0.1 
 * Date:    17/07/2012 
 * Author:    chema@garridodiaz.com 
 * License: GPL v3 
 * Notes:    based on Marco Pracucci  <marco.pracucci@spreaker.com> Rocco Zanni <rocco.zanni@spreaker.com> work 
 * Usage example: 

//creating the conection to elasticemail 
$elasticEmail = new ElasticEmail('yourelasticemail@example.es','APIKEYHERE'); 

//sending the email 
var_dump($elasticEmail->sendMail('from@gmail.com','from name', 
                                        'to@gmail.com','test subject'.time(),'html body'));         

 */ 
class ElasticEmail 
{ 
    /** 
     * Api send uri 
     */ 
    private $_api_uri = 'https://api.elasticemail.com/mailer/send'; 
    /** 
     * Api username (email address) 
     */ 
    private $_api_username; 
    /** 
     * Api secret key 
     */ 
    private $_api_key; 
    /** 
     * Curl handle 
     */     
    private $_handle; 

    //setting the key and username 
    public function __construct($api_username, $api_key) 
    { 
        $this->_api_username    = $api_username; 
        $this->_api_key         = $api_key; 
        $this->_handle          = null; 
    } 
    //closes the connection with the curl handler 
    public function __destruct() 
    { 
        if ($this->_handle) 
        { 
            curl_close($this->_handle); 
            $this->_handle = null; 
        } 
    } 

    /** 
     * Send an email via Elastic Mail 
     * 
     * @return mixed boolean/string (id email) 
     */ 
    public function sendMail($from,$from_name,$to,$subject,$body) 
    { 
        //d(func_get_args());
        // Get handle 
        $handle = $this->_getHandle(); 
        if (!$handle)  die('Cannot connect to the service'); 

        $data = array( 
            'username'  => $this->_api_username, 
            'api_key'   => $this->_api_key, 
            'from'      => $from, 
            'from_name' => $from_name, 
            'to'        => $to, 
            'is_html'   => "true", 
            'subject'   => $subject, 
            'body'      => $body 
        );//echo(var_dump($data)); 
         
        // Set POST data 
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data); 

        // Exec request 
        $response = curl_exec($handle); 

        // Check response 
        if (!$response || !preg_match('/[\w]+-[\w]+-[\w]+-[\w]+-[\w]+/', $response)) { 
            return FALSE; 
        } 
         
        return $response; 
      
    } 

    /** 
     * With this function we reuse the connection socket of curl so will be a bit faster for multiple emails 
     */ 
    private function _getHandle() 
    { 
        if ($this->_handle) { 
            return $this->_handle; 
        } 

        // Create handle 
        $this->_handle = curl_init($this->_api_uri); 
        if (!$this->_handle) { 
            return false; 
        } 

        // Set default config 
        curl_setopt_array($this->_handle, array( 
            CURLOPT_RETURNTRANSFER  => true, 
            CURLOPT_POST            => true, 
            CURLOPT_SSL_VERIFYPEER  => false, 
            CURLOPT_TIMEOUT         => 10, 
            CURLOPT_HTTPHEADER      => array('Connection: keep-alive', 'Keep-Alive: 360') 
        )); 

        return $this->_handle; 
    } 

}