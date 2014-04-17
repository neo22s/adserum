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
    /*
	'memcache'   => array(
            'driver'         => 'memcache',  // Use Memcached as the default driver
            'default_expire' => 3600,        // Overide default expiry
            'servers'        => array(
                                        // Add a new server
                                        array(
                                                        'host'       => 'localhost',
                                                        'port'       => 11211,
                                                        'persistent' => FALSE
                                        )
                                    ),
            'compression'    => FALSE
    )*/
);