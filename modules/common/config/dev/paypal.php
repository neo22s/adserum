<?php 
/**
 * paypal configs
 */
return array(
	'sandbox'		   =>  TRUE,
	'currency'		   =>  'USD',
	'micro_amount'	   =>  10,//amount that micro is better than normal account
	'account'		   =>  '@.com',
	'account_micro'	   =>  '@.com',//for payments less than micro_amount

);