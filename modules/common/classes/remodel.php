<?php
/**
 *
 * Models for redis!
 * 
 * @author      Chema <chema@garridodiaz.com>
 * @package     Core
 * @copyright   (c) 2012 AdSerum.com
 * @license     GPL v3
 * 
 * @todo
 * On update only save fields that are modified.
 * Fields validation, integer, varchar, email etc...
 * Create relations hasone , hasmany...
 * Fields filters, on read, on save.
 * if value is array: use a list 'TABLENAME:PK:SETNAME' and save it in a common list or set
 */

class Remodel {

    /**
     * fields with values
     * @var array
     */
    protected $_data = array();

    /**
     * fields that has this model, if empty everything is allowed, recommended to set it.
     * @var array
     */
    protected $_fields = array();

    /**
     * redis instance
     * @var Predis
     */
    protected $_redis; 

    /**
     * common name used as redis key
     * @var string
     */
    protected $_table_name;

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key;

    /**
     * redis keys constants to concat
     * DONOT change this if you had data already in the REDIS
     */
    const NEXT_PK   = '_pk_';   // 'TABLENAME:_pk_' => stores the pk counter
    const ALL       = '_all_';  // 'TABLENAME:_all_' => stores all the ids for this table
    const KSEP      = ':';      // Key Separator used for any KEY
    
    /**
     * create object and connect
     * @param Predis\Client $redis        connection or pipe
     * @param array $redis_config configuration
     * @param integer $pk          PK id to load.
     */
    public function __construct($redis = NULL, array $redis_config = NULL , $pk = NULL )
    {
        if($redis!==NULL)
        {
            $this->_redis = $redis;
        }
        else
        {
            if ($redis_config == NULL)
                $redis_config = Core::config('database.redis');

            $this->_redis = new Predis\Client($redis_config);

            try 
            {
                $this->_redis->connect();
            }
            catch (Exception $e) 
            {
                throw new Kohana_Exception($e);
            }
        } 

        if (is_numeric($pk))
            $this->load($pk);
    }

    /**
     * Create a new model instance.
     *
     *     $model = Remodel::factory($name);
     *
     * @param   string   model name
     * @return  Model
     */
    public static function factory($name)
    {
        $class = 'Model_'.$name;
        if(class_exists($class))
        {
            return new $class;
        }
        else
        {
            throw new Exception('Factory model not found: '.$name, 1);
        }
    }

    /**
     * creates a new item pk
     * @param  Redis $pipe usage of pipe to make it faster optional 
     * @return boolean 
     */
    public function create($pipe = NULL)
    {
        $conn = ($pipe!==NULL)? $pipe:$this->_redis;

        //getting the primary key, first we increase so there¡s no repeated id's
        $this->pk($this->get_last_pk(TRUE));
      
        //save the data at redis
        if ($this->update($conn))
        {            
            //save the primary in a global set
            $conn->sadd($this->_table_name.self::KSEP
                            .self::ALL, $this->pk());
            //end pipe
            return TRUE;
        }
        //if save not success decrease 
        else
        {
            $this->_redis->decr($this->_table_name.self::KSEP.self::NEXT_PK);
            return FALSE;
        }
    }

    /**
     * update the current data at redis
     * @param  Redis $pipe usage of pipe to make it faster optional 
     * @return boolean       
     */
    public function update($pipe = NULL)
    {        
        $conn = ($pipe!==NULL)? $pipe:$this->_redis;

        //we add the pk to the array
        $data = array_merge(array( $this->_primary_key => $this->pk() ),$this->_data);

        return $conn->hmset($this->_table_name.self::KSEP.$this->pk(), $data);     
    }
    
   /**
    * deletes a key from the redis
    * @param  integer $pk optional
    * @return boolean      
    */
    public function delete($pk = NULL)
    {
        //if isnumeric we try to be friendly and use it as the index
        if (!is_numeric($pk))
            $pk = $this->pk();
                
        //remove the KEY from redis
        $ret = $this->_redis->del($this->_table_name.self::KSEP.$pk);

        //delete the PK from the global list
        if ($ret>0)
            $ret = $this->_redis->srem($this->_table_name.self::KSEP.self::ALL, $this->pk());
        
        $this->unload();

        return ($ret>0)? TRUE : FALSE;
    }
    
    /**
     * updates or creates, shortcut
     * @param  Redis $pipe usage of pipe to make it faster optional
     */
    public function save($pipe = NULL)
    {
        //if index exists calls update if doesn't exists create
        ($this->loaded())? $this->update($pipe):$this->create($pipe);
    }
    
    /**
     * Just tells you if there's some data populated in the model using the primary
     * @return boolean
     */
    public function loaded()
    {
        return (array_key_exists($this->_primary_key, $this->_data));
    }
    
    /**
     * Unloads any data
     */
    public function unload()
    {
        unset($this->_data);
    }

    /**
     * loads values
     * @param  inteeger PK to load data  
     * @return boolean
     */
    public function load($pk = NULL)
    {
        if (is_numeric($pk))
        {
            $data = $this->_redis->hgetall($this->_table_name.self::KSEP.$pk);
            if (!empty($data))
            {
                $this->values($data);
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * loads/get an array of values into the model
     * @param  array $data  
     * @return boolean/array
     */
    public function values(array $data = NULL)
    {
        if (is_array($data))
        {
            $this->_data = $data;
            return TRUE;
        }
        else return $this->_data;        
    }

    /**
     * retrieves models for the specified range
     * lrange over TABLENAME:ALL
     * @param  integer/array $elements if array return those elements, if not = from
     * @param  integer $stop  to
     * @return array   remodel
     */
    public function select($elements = 0, $stop = 9)
    {
        if (!is_array($elements))
            $elements = $this->get_all_pk($elements,$stop);

        $ret = array();
       
        // return array of Models
        if(count($elements) >= 1)
        {
            //@todo use of a pipe to improve load?
            //get values in a pipe put them in array and push them to the model
            $class = get_class($this);//using same model
            foreach ($elements as $element)
            {
                $model = new $class;
                $model->load($element);
                if ($model->loaded()) 
                    $ret[]= $model;
                unset($model);
            }
        }

        return $ret;
    }

    /**
     * returns the key in the table
     * @param string key_name
     * @return mixed
     */
    public function get($key_name)
    {        
        return $this->_redis->get($this->_table_name.self::KSEP.$key_name);   
    }

    /**
     * gets range for the key
     * @param  string  $key   
     * @param  integer $start 
     * @param  integer $stop  
     * @param  boolean $withscores  
     * @return array     
     */
    public function range($key,$start = 0, $stop = -1, $withscores = TRUE)
    {
        return $this->_redis->zrevrange($this->_table_name.self::KSEP.$key , $start,$stop, array('withscores' => $withscores));
    }

    /**
     * returns the amount of elements in the redis set for that table
     * @param string set_name
     * @return integer
     */
    public function count($set_name = NULL)
    {        
        //count all
        if ($set_name === NULL)
            return $this->_redis->scard($this->_table_name.self::KSEP.self::ALL);
        else//count set
            return $this->_redis->scard($this->_table_name.self::KSEP.$set_name);   
    }

    /**
     * returns the intersection of X keys
     * @param array $keys
     * @param bool $count we want the result as counter
     * @return mixed bool/array
     */
    public function sinter(Array $keys, $count = FALSE)
    {
        $res = $this->_redis->sinter($keys);
        return ($count===TRUE)? count($res):$res;
    }

    /**
     * returns zscore
     * @param string $key
     * @param string $member 
     * @return integer 
     */
    public function zscore($key, $member)
    {
        $res = $this->_redis->zscore($key, $member);
        return (!is_numeric($res))?0:$res;
    }

    /**
     * returns KEYS
     * @param string $key
     * @return array 
     */
    public function keys($key)
    {
        return $this->_redis->keys($key);
    }
	
    /**
     * set/get the primary key value of the object
     * @return integer id
     */
    public function pk($pk = NULL)
    {
        //acting as setter
        if (is_numeric($pk))
            $this->_data[$this->_primary_key] = $pk;

        return ($this->loaded())? $this->_data[$this->_primary_key] : FALSE;
    }

    /**
     * retrieve the PK for the list of this table
     * @param  integer $start from
     * @param  integer $stop  to
     * @return array 
     */
    public function get_all_pk($start = 0, $stop = 9)
    {
        //todo improve this!!
        return $this->_redis->sort($this->_table_name.self::KSEP.self::ALL,array('by'=> 'nosort','limit' => array($start,$stop)));
    }

    /**
     * retrieve the last PK used for the table
     * @param bool $increase if set to true we increase the redis PK before.
     * @return integer
     */
    public function get_last_pk($increase = FALSE)
    {
        if ($increase === TRUE)
            $this->_redis->incr($this->_table_name.self::KSEP.self::NEXT_PK);

        return $this->_redis->get($this->_table_name.self::KSEP.self::NEXT_PK);
    }
		
	/**
	 * Magic methods to set get
	 */
    public function __set($name, $value)
    {
        //check if fields exists in the `model`
        if ( empty($this->_fields) OR in_array($name, $this->_fields) )
        {
            $this->_data[$name] = $value;
        }
        else throw new Exception($name.' does not exist in the model.', 1);
        
    }

    public function __get($name)
    {
        return (array_key_exists($name, $this->_data)) ? $this->_data[$name] : NULL;
    }

    public function __isset($name)
    {
        return isset($this->_data[$name]);
    }

    public function __unset($name)
    {
        unset($this->_data[$name]);
    }


    /**
     * Redis functions
     */
    public function info()
    {
        return $this->_redis->info();
    }
    
}