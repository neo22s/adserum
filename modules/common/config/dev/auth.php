<?php 

return array(

	'driver'       => 'oc',
	'hash_method'  => 'sha256',
	'hash_key'     => '',
	'lifetime'     => 90*24*60*60,//3 months for remember me
	'session_type' => Session::$default,
	'session_key'  => 'auth_user',
	'cookie_salt'  => '-',

	'ql_key'       => '',
    'ql_lifetime'  => 7*24*60*60,//7 days for the QL to work
    'ql_separator' => '|',
    'ql_mode'      => MCRYPT_MODE_NOFB,
    'ql_cipher'    => MCRYPT_RIJNDAEL_128,

);