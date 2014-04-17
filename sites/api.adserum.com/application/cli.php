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
    set_time_limit(0);
    ini_set('memory_limit', '1024M');

    define('SYSPATH', 1);
	require(__DIR__.'/../../../modules/common/classes/exec.php');

    // Path to Kohana's index.php
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
            
            //no default
        }
    }
