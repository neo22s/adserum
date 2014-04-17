<?php 
/**
 * Common configs various
 */
return array(
	'cache'		   => 'apc',
	'email'		   => 'admin@adserum.com',//@todo
	'email_method' => 'elasticemail', // 'elasticemail' or 'default' @todo production in relay?
	'name'    	   => 'Adserum.com',
	'timezone'	   => 'Europe/Madrid',
	'charset'	   => 'utf-8',
	'api_url'		=> 'http://api.adserum.lo/',
	'front_url'		=> 'http://adserum.lo/',
	'panel_url'		=> 'http://panel.adserum.lo/',
	'tld'			=> 'lo',
	'elastic_user'	=> '',
	'elastic_api'	=> '',
	'num_reps'		=> 4, //number of times we allow the JS to be included
	'affiliate'		=> 1, //default user if there's no affiliate or not found or disabled.
	'publisher_CPM' => 0.2, //how much a publisher earns per thousand USD
	'publisher_min_pay' => 50, //minimun payment that a publisher can get USD
	'ip_hits_day'   => 400, //how many impressions per day per IP we allow.
    'translate'     => '',//google api key translate
);