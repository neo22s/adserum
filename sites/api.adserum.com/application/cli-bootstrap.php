<?php
/**
 * CLI, used in CRON or for background process
 *
 * @package    OC
 * @category   CLI
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2012 Open Classifieds Team
 * @license    GPL v3
 */
    ignore_user_abort(true); 
    //set_time_limit(0);
    ini_set('memory_limit', '1024M');

    //horrible hosting....
    if ( !defined('__DIR__') ) define('__DIR__', dirname(__FILE__));
    
    // Path to Kohana's index.php
    $system = __DIR__.'/../www/index.php'; //die($system);
    
    if (file_exists($system) AND $argc>=1) //exists the file and the environment is given
    {
        define('CLI_REQUEST', TRUE);
        include $system; // @todo create an index and a custom bootstrap??
		
        //background bootstrap only if args are passed
        if ($argc > 1 )//CLI
        {
            switch ($argc)//depends how many arguments changes
            {
                case 2://just function name
                    exec::execute($argv[1]);
                    break;
                case 3://function with params
                    exec::execute($argv[1],$argv[2]);
                    break;
                case 4://function with params and jobid
                    exec::execute($argv[1],$argv[2],$argv[3]);
                    break;
                //no default
            }	
        } 
        //not any arg CRON bootstrap
        else
        {//echo 'cron';
            //initializing the crontab from DB
        	Cron::initialize();
        	// executes the cron
        	Cron::run();  
        }
        
    }