<?php
/**
 * Kohana Core Overload
 *
 */
class Kohana extends Kohana_Core {

    /**
     * @var  array  Nombres de los estados del desarrollo, para su uso en carpetas
     */
    public static $env_names = array(
        Kohana::PRODUCTION  => 'pro',
        Kohana::STAGING     => 'pre',
        Kohana::TESTING     => 'test',
        Kohana::DEVELOPMENT => 'dev'
    );

    /**
     * @var  string  Current environment name
     */
    public static $environment_name = '';
}