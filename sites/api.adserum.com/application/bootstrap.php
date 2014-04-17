<?php 
// -- Environment setup --------------------------------------------------------

// Load the core Kohana class
require SYSPATH.'classes/kohana/core'.EXT;
if (is_file(APPPATH.'classes/kohana'.EXT))
{
    // Application extends the core
    require APPPATH.'classes/kohana'.EXT;
}
elseif (is_file(APPMODPATH.'common/classes/kohana'.EXT))
{
    // Load application common modules extension
    require APPMODPATH.'common/classes/kohana'.EXT;
}
else // Load default empty core extension
    require SYSPATH.'classes/kohana'.EXT;


/**
 * Enable the Kohana auto-loader.
 *
 * @see  http://kohanaframework.org/guide/using.autoloading
 * @see  http://php.net/spl_autoload_register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @see  http://php.net/spl_autoload_call
 * @see  http://php.net/manual/var.configuration.php#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');


// -- Configuration and initialization -----------------------------------------

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
if (isset($_SERVER['KOHANA_ENV']))
{
        Kohana::$environment = constant('KOHANA::'.strtoupper($_SERVER['KOHANA_ENV']));
        Kohana::$environment_name = Kohana::$environment ? Kohana::$env_names[ Kohana::$environment ] : '';
} 
else 
{
        Kohana::$environment = Kohana::DEVELOPMENT;
        Kohana::$environment_name = Kohana::$env_names[ Kohana::$environment ];
}

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 */
Kohana::init(array(
	'base_url'  => '/',
	'errors'	=> TRUE,
	'profile'	=> (Kohana::$environment == Kohana::DEVELOPMENT),
	'caching'	=> (Kohana::$environment == Kohana::PRODUCTION),
));


/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);
/**
 * Attach the environment specific configuration file reader to config if not in production.
 */
Kohana::$config->attach(new Config_File('config/'.Kohana::$environment_name),TRUE);

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
if ((Kohana::$environment !== Kohana::DEVELOPMENT) AND (Kohana::$environment !== Kohana::STAGING))
{
    Kohana::$log->attach(new Log_File(APPPATH.'logs'), array(LOG_ERR));
}
else
{
    Kohana::$log->attach(new Log_File(APPPATH.'logs'), array(LOG_INFO,LOG_ERR,LOG_DEBUG));
}


/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
$modules = array(
			   'common'  	  => APPMODPATH.'common',   // shared models configs etc..
        	   'themes'	      => APPPATH.'themes',     // we load it as a module so we can later search file using kohana find_file
        	   'auth'         => MODPATH.'auth',       // Basic authentication
        	   'cache'        => MODPATH.'cache',      // Caching with multiple backends
        	   'database'     => MODPATH.'database',   // Database access
        	   //'image'        => MODPATH.'image',      // Image manipulation
        	   'orm'          => MODPATH.'orm',        // Object Relationship Mapping
			   //'pagination'   => APPMODPATH.'pagination',   // ORM Pagination
			   //'breadcrumbs'  => APPMODPATH.'breadcrumbs',   // breadcrumb view
			   //'formmanager'  => APPMODPATH.'formmanager',        // forms to objects ORM
			   'geoip3'  	  => APPMODPATH.'geoip3',        // maxmind geoip 	
			   'ko-predis'    => APPMODPATH.'ko-predis',        // maxmind geoip 	
);

//modules for development environment, not included in distributed KO with OC
if (Kohana::$environment == Kohana::DEVELOPMENT)
{
	//$modules['profilertoolbar'] = APPMODPATH.'profilertoolbar'; 
    //$modules['unittest'] =  MODPATH.'unittest';   // Unit testing
    //$modules['codebench'] = MODPATH.'codebench'; // Benchmarking tool
    //$modules['userguide'] = MODPATH.'userguide';  // User guide and API documentation
}

Kohana::modules($modules);
unset($modules);


// initializing the OC APP, and routes
Core::initialize();