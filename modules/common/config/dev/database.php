<?php 
return array
(
    'default' => array(
        'type'       => 'mysql',
        'connection' => array(
            'hostname'   => 'localhost',
            'username'   => '',
            'password'   => '',
            'persistent' => FALSE,
            'database'   => 'adserum',
            ),
        'table_prefix' => 'as_',
        'charset'      => 'utf8',
        'profiling'    => FALSE,
     ),

    'redis' => array(
        'host'       => '127.0.0.1',
        'port'       => '6379',
        'password'   => '',   
     ),

);