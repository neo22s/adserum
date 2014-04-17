<?php 
/**
 * Crontab manager
 *
 * @package    OC
 * @category   Cron
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2012 Open Classifieds Team
 * @license    GPL v3
 */

class Cron extends Kohana_Cron {
    /**
     * Acquire the Cron mutex
     *
     * @return  boolean
     */
    protected static function _lock()
    {
        //$config = Kohana::config('cron');
        $config = Core::config('cron');
        $result = FALSE;

        if (file_exists($config->lock) AND ($stat = @stat($config->lock)) AND time() - $config->window < $stat['mtime'])
        {
            // Lock exists and has not expired
            return $result;
        }

        $fh = fopen($config->lock, 'a');

        if (flock($fh, LOCK_EX))
        {
            fseek($fh, 0, SEEK_END);

            if (ftell($fh) === (empty($stat) ? 0 : $stat['size']))
            {
                // Current size matches expected size
                // Claim the file by changing the size
                fwrite($fh, '.');

                $result = TRUE;
            }

            // else, Another process acquired during flock()
        }

        fclose($fh);

        return $result;
    }

        /**
     * @return  boolean FALSE when another instance is running
     */
    public static function run()
    {
        if (empty(Cron::$_jobs))
            return TRUE;

        if ( ! Cron::_lock())
            return FALSE;

        try
        {
            Cron::_load();

            $now = time();
            $threshold = $now - Core::config('cron')->window;

            foreach (Cron::$_jobs as $name => $job)
            {
                if (empty(Cron::$_times[$name]) OR Cron::$_times[$name] < $threshold)
                {
                    // Expired

                    Cron::$_times[$name] = $job->next($now);

                    if ($job->next($threshold) < $now)
                    {
                        // Within the window

                        $job->execute();
                    }
                }
                elseif (Cron::$_times[$name] < $now)
                {
                    // Within the window

                    Cron::$_times[$name] = $job->next($now);

                    $job->execute();
                }
            }
        }
        catch (Exception $e) {}

        Cron::_save();
        Cron::_unlock();

        if (isset($e))
            throw $e;

        return TRUE;
    }

    /**
     * Store the timestamps of when jobs should run next
     */
    protected static function _save()
    {
        Kohana::cache("Cron::run()", Cron::$_times, Core::config('cron')->window * 2);
    }

    /**
     * Release the Cron mutex
     */
    protected static function _unlock()
    {
        return @unlink(Core::config('cron')->lock);
    }
}

class Cronix extends Kohana_Cron {

    protected $_callback;
    protected $_period;
    protected $_id_cron; //id from the crontab table

    public function __construct($period, $callback,$_id_cron)
    {
        $this->_period = $period;
        $this->_callback = $callback;
        $this->_id_cron = $_id_cron;
    }
    
    /**
     *  retrieve items from crontab table and sets them as cronjobs
     */ 
    public static function initialize()
    {    
        $crontab = new Model_Crontab();
        $crontab = $crontab->where('active','=',1)->find_all();
        //Log::instance()->add(LOG_DEBUG,'Cron::initialize');
        //adding to cron for each item
        foreach ($crontab as $cron)
        { 
            Cron::setcron($cron);
        }  
   
    }
    
    /**
     * get the cron from the table crontab 
     * @param representation from table crontab
     */
    public static function setcron($cron)
    {
        //generating the cron job data, execution params
        //callback with params in DB: function_name|param_value1;param2;paramX
        if ( strpos($cron->callback, '|') !== FALSE )
        {
            $callback = explode('|',$cron->callback);
            $callback = array('function'=>$callback[0],
                              'params' => explode(';',$callback[1])
                             );
        }
        //normal callback, just function name
        else
        {
           $callback = $cron->callback;
        }          

        $job_data = array (
                            'period'   => $cron->period,
                            'callback' => $callback,
                            'id_cron'  => $cron->id_cron
                          );
        //Log::instance()->add(LOG_DEBUG,'Cron::setcron - '.$cron->name);    
        Cron::set( $cron->name,$job_data );
    }
    
    /**
     * Registers a job to be run
     * @param name for the cron
     * @param   array  Job to run example: 
     *  $job_data = array (
                'period' => '* * * * *',
                'callback' => array('function'=>'emailer::send',
                                    'params' => array(
                                                                                            'chema@tumanitas.com',
                                                                                            'cron test subject',
                                                                                            'cron body test'
                                                      ),
                                     'id_cron' => 1
                                    )
            );
            Cron::set('email queue 1',$job_data);
     */
    public static function set($name,$job_data)
    {
        if (is_array($job_data))
        {
            //Log::instance()->add(LOG_DEBUG,'Cron::set -'.$name.' data:'.print_r($job_data,1));
            
            $job = new Cron($job_data['period'], $job_data['callback'],$job_data['id_cron']);
            Cron::$_jobs[$name] = $job;
        }
        else 
        {
            return FALSE;
        }
    }
	
    /**
     * Execute this job and creates cronjob
     */
    public function execute()
    { 
        //check if another instance of that cron is running
        $job_lock = $this->_checkjoblock(); //dump($job_lock);
        //Log::instance()->add(LOG_DEBUG,'Cron->execute - job_lock:'.$job_lock);

        if ($job_lock==-1)
        {    	       
            //create cronjob in DB   
            $id_cronjob = Model_Cronjob::create_job($this->_id_cron);//dump($id_cronjob);
               
            //in case its a callback with parameters, be awate prams needs to be an array same order values as params in function
            if (is_array($this->_callback))
            {
                //Log::instance()->add(LOG_DEBUG,'Cron->execute - is array callback: '.$this->_callback['function']);
                $pid = exec::background($this->_callback['function'],$this->_callback['params'],$id_cronjob);
            }
            //normal callback to a function no params
            else
            {
                //Log::instance()->add(LOG_DEBUG,'Cron->execute - no array callback: '.$this->_callback);
                $pid = exec::background($this->_callback,NULL,$id_cronjob);
            }
            //save $pid in cronjobs table
            Model_Cronjob::update_PID($id_cronjob,$pid);//return $pid;
        }
        else //found another process working on this creates cronjob in DB explaining which cronjob locked him 
        {
            Model_Cronjob::create_job($this->_id_cron,$job_lock);
        }

    }
	
    /**
     * creates the CronJob mutex
     * @param id cron to lock
     * @param id cron job to write the lock
     */
    public static function joblock($id_cron,$id_cronjob)
    {
        //dump($id_cronjob,TRUE);
        $cache  = Cache::instance();		
        $config = Kohana::$config->load('cron');

        //only if we have cronjob id we can create the lock
        $cache->set($config->lock.$id_cron, 
                    array('id_cronjob'=>$id_cronjob, 'time'=>time() ), 
                    $config->window * 2);
        //Log::instance()->add(LOG_DEBUG,'Cron->joblock key: '.$config->lock.$id_cron.', values: '.$id_cronjob.' | '.time() );             
    }
    
	/**
     * Release the CronJob mutex
     * @param id cron to release
     */
    public static function jobunlock($id_cron)
    {
        $cache = Cache::instance();		
        $cache->delete(Kohana::$config->load('cron')->lock.$id_cron);
    }

    /**
     * checks the CronJob mutex
     *
     * @return  integer id_cronjob or FALSE in case there's no block
     */
    private function _checkjoblock()
    {
        $cache = Cache::instance();		
        $config = Kohana::$config->load('cron');
        $result = -1;
        $lock_job = $cache->get($config->lock.$this->_id_cron);
        
        //Log::instance()->add(LOG_DEBUG,'Cron->_checkjoblock key: '.$config->lock.$this->_id_cron.', value: '.print_r($lock_job,1) ); 

        // Lock exists and has not expired
        if (  (time() - $config->window) <  $lock_job['time'])
        {
            //returning the id_cronjob that locked it
            $result = $lock_job['id_cronjob'];	 
        }

        //@todo if window greater add log??
        return $result;
    }
    
    /**
     * Retrieve the timestamps of when jobs should run, using tm3 cache
     */
    protected static function _load()
    {		
        $cache = Cache::instance();
        Cron::$_times = $cache->get("Cron::run()");
    }
	
    /**
     * Store the timestamps of when jobs should run next, using tm3 cache
     */
    protected static function _save()
    {
        $cache = Cache::instance();		
        $cache->set("Cron::run()", Cron::$_times, Kohana::$config->load('cron')->window * 2);//30*24*60*60);
    }

    /**
     * erased file lock
     * @return  boolean FALSE when another instance is running
     */
    public static function run()
    {	    
        if (empty(Cron::$_jobs))
        {
            return TRUE;
        }
			
        try
        {
            Cron::_load();

            $now = time();
            $threshold = $now - Kohana::$config->load('cron')->window;

            foreach (Cron::$_jobs as $name => $job)
            {
                if (empty(Cron::$_times[$name]) OR Cron::$_times[$name] < $threshold)
                {
                    // Expired
                    Cron::$_times[$name] = $job->next($now);

                    if ($job->next($threshold) < $now)
                    {
                            // Within the window
                            $job->execute();
                    }
                }
                elseif (Cron::$_times[$name] < $now)
                {
                    // Within the window
                    Cron::$_times[$name] = $job->next($now);//@todo maybe sohuld count after the execution is done??

                    $job->execute();
                }
            }
        }
        catch (Exception $e) {}

        Cron::_save();

        if (isset($e))
        {
            Log::instance()->add(LOG_ERR,'Cron->run() - exception '.$e);
        }

        return TRUE;
    }
}