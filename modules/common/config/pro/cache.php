<?php 
return array
(
    'file'  => array
    (
        'driver'             => 'file',
        'cache_dir'          => APPPATH.'cache/',
        'default_expire'     => 3600,
    ),
    'apc'      => array(
        'driver'             => 'apc',
        'default_expire'     => 3600,
    ),
    
);