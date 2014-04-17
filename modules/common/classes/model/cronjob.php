<?php 
/**
 * description...
 *
 * @author		Chema <chema@garridodiaz.com>
 * @package		OC
 * @copyright	(c) 2012 AdSerum.com
 * @license		GPL v3
 * *
 */
class Model_Cronjob extends ORM {
	
    /**
     * Table name to use
     *
     * @access	protected
     * @var		string	$_table_name default [singular model name]
     */
    protected $_table_name = 'cronjobs';

    /**
     * Column to use as primary key
     *
     * @access	protected
     * @var		string	$_primary_key default [id]
     */
    protected $_primary_key = 'id_cronjob';

    
	/**
     * Validation rules
     * @return  array  of rules to be added to the Validation object
     */
    public function rules()
    {
        return array(
            'id_cronjob_locking' => array(
                array('numeric'),
            ),
            'id_cron' => array(
                array('not_empty'),
                array('numeric'),
            ),
            'output' => array(
              //  array('max_length', array(':value',50)),
            ),
            'date_start' => array(
              //  array('max_length', array(':value',50)),  
            ),
            'date_finished' => array(
              //  array('max_length', array(':value',50)),  
            ),
            'pid' => array(
                array('numeric'),
            ),
            'running' => array(
                array('numeric'),
            ),
        );
    }
    
    public static function update_PID($id_cronjob,$pid)
    {
        $cj = new Model_Cronjob;
        $cj->where('id_cronjob','=',$id_cronjob)->find();
        
        if ($cj->loaded())  // Load was successful
        { 
            $cj->pid=$pid; 
            try
            {
                $cj->save();
                //Log::instance()->add(LOG_DEBUG,'Cronjob->updatePID - updated id_cronjob: '.$id_cronjob.' PID: '.$pid);
                return TRUE;
            }
            catch (Exception $e)
            {
                //Log::instance()->add(LOG_ERR,'Cronjob->updatePID - error saving id_cronjob: '.$id_cronjob.' PID: '.$pid.': '.print_r($e->errors(),1));
                return FALSE;
            }
        }
        else
        {
            Log::instance()->add(LOG_ERR,'Cronjob->updatePID - Not found id_cronjob: '.$id_cronjob);         
            return FALSE;
        }
    }
    
	/**
     * updates a cronjob in the DB and unlocks it
     * 
     * @param id cron job to be updated
     * @param return to be posted in job
     */
    public static function finish($id_cronjob, $return)
    {   
        
        $cj = new Model_Cronjob;
        $cj->where('id_cronjob','=',$id_cronjob)->find();
        
        if ($cj->loaded())
        {
            // Load was successful
            $cj->date_finished=DB::expr('NOW()');//date('Y-m-d H:i:s');
            $cj->output=$return;
            $cj->running=0;
            
            try
            {
                $cj->save();                
                Cron::jobunlock($cj->id_cron);//cache unlocking, releases the cron mutex    
                //Log::instance()->add(LOG_DEBUG,'Cronjob->finish saved idcronjob: '.$id_cronjob.', return: '.$return); 
                return TRUE;
            }
            catch (Exception $e)
            {
                Log::instance()->add(LOG_ERR,'Cronjob->finish - error saving id_cronjob: '.$id_cronjob.': '.$e);
                return FALSE;
            }
        }
        else
        {
            Log::instance()->add(LOG_ERR,'Cronjob->finish - Not found id_cronjob: '.$id_cronjob);         
            return FALSE;
        }
    }
    
	/**
     * creates a new cronjob in the DB and returns an ID
     * @param numeric id from crontab
     * @param numeric used to save locked jobs only if not null
     * @return numeric id cron job
     */
    public static function create_job($id_cron,$job_lock=NULL)
    {
    	$cj = new Model_Cronjob;
    	$cj->id_cron = $id_cron;
    	
    	//job locked
    	if ($job_lock!==NULL)
    	{
    	    $cj->id_cronjob_locking = $job_lock;
    	    //$cj->output             = 'locked';//redundant??
    	    $cj->running            = 0;
    	    //Log::instance()->add(LOG_DEBUG,'Cronjob->create_job - Save new lock: '.$job_lock); 
    	}

    	try
    	{
    	    $cj->save();
    	    //Log::instance()->add(LOG_DEBUG,'Cronjob->create_job - Save new id_cron_job: '.$cj->id_cronjob); 

    	    //cache lock only if was not locked previously
    	    if ($job_lock===NULL)
    	    {
    	        Cron::joblock($id_cron,$cj->id_cronjob);
    	    }
    	    
    	    return $cj->id_cronjob;
    	}
    	catch (Exception $e)
        {
            Log::instance()->add(LOG_ERR,'Cronjob->create_job - Fail saving new id_cron: '.$id_cron);         
            return FALSE;
        }
    }

    protected $_table_columns = 
array (
  'id_cronjob' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_cronjob',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 1,
    'display' => '10',
    'comment' => '',
    'extra' => 'auto_increment',
    'key' => 'PRI',
    'privileges' => 'select,insert,update,references',
  ),
  'id_cronjob_locking' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_cronjob_locking',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => true,
    'ordinal_position' => 2,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'id_cron' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_cron',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 3,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => 'MUL',
    'privileges' => 'select,insert,update,references',
  ),
  'output' => 
  array (
    'type' => 'string',
    'column_name' => 'output',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => true,
    'ordinal_position' => 4,
    'character_maximum_length' => '50',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'date_start' => 
  array (
    'type' => 'string',
    'column_name' => 'date_start',
    'column_default' => 'CURRENT_TIMESTAMP',
    'data_type' => 'timestamp',
    'is_nullable' => false,
    'ordinal_position' => 5,
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'date_finished' => 
  array (
    'type' => 'string',
    'column_name' => 'date_finished',
    'column_default' => NULL,
    'data_type' => 'datetime',
    'is_nullable' => false,
    'ordinal_position' => 6,
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'pid' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'pid',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => true,
    'ordinal_position' => 7,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'running' => 
  array (
    'type' => 'int',
    'min' => '-128',
    'max' => '127',
    'column_name' => 'running',
    'column_default' => '1',
    'data_type' => 'tinyint',
    'is_nullable' => false,
    'ordinal_position' => 8,
    'display' => '1',
    'comment' => '',
    'extra' => '',
    'key' => 'MUL',
    'privileges' => 'select,insert,update,references',
  ),
);

} // END Model_Cronjob
