<?php 
/**
 * paypal configs
 */
/*
return array(
	'sandbox'		   =>  TRUE,
	'currency'		   =>  'USD',
	'micro_amount'	   =>  10,//amount that micro is better than normal account
	'account'		   =>  'sandbo_1289077831_biz@deambulando.com',
	'account_micro'	   =>  'sandbo_1289077831_biz@deambulando.com',//for payments less than micro_amount

);
*/
return array(
	'sandbox'		   =>  FALSE,
	'currency'		   =>  'USD',
	'micro_amount'	   =>  10,//amount that micro is better than normal account
	'account'		   =>  'payments@adserum.com',//normal account belongs to OC
	'account_micro'	   =>  'paypal@adserum.com',//for payments less than micro_amount

);