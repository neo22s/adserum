<?php
/**
 * Ad hits click
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Core
 * @copyright   (c) 2012 AdSerum.com
 * @license     GPL v3
 *
 * 
 */
class Model_Adclick extends Remodel {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'adclicks';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_adclick';

    /**
     * fields that has this model
     * @var array
     */
    protected $_fields = array( 'id_ad',
                                'id_hit',
                                'id_adformat',
                                'id_affiliate',
                                'id_language',
                                'id_location',
                                'domain',
                                'browser',
                                'platform',
                                'ip_address',
                                'status', 
                                'created', 
                                );


    /**
     * Status constants
     */
    const STATUS_INACTIVE            = 0;    
    const STATUS_ACTIVE              = 1;   
    const STATUS_FRAUD               = 5;   



    /**
     * this fields create a set from the given field. Ex: set TABLENAME:FIELDNAME:FIELDVALUE value inserted PRIMARYKEY
     * Also we create a sorted set to have a leader board. TABLENAME:FIELDNAME -> FIELDVALUE = count+1
     * @var array
     */
     protected $_sets =  array ('id_ad',
                                'id_adformat',
                                'id_affiliate',
                                'id_language',
                                'id_location',
                                'domain',
                                'browser',
                                'platform',
                                );


    /**
     * creates a new item pk
     * @param  Redis $pipe usage of pipe to make it faster optional 
     * @return boolean 
     */
    public function create($pipe = NULL)
    {
        $this->created = Date::unix2mysql();

        $pipe = $this->_redis->pipeline();

        //if saving was succesfull
        if ($ret = parent::create($pipe))
        {            
            //date sets
            $pipe->sadd($this->_table_name.self::KSEP
                            .'created'.self::KSEP
                            .date('Y-m-d'), $this->pk());
            $pipe->sadd($this->_table_name.self::KSEP
                            .'created'.self::KSEP
                            .date('Y-m'), $this->pk());
            $pipe->sadd($this->_table_name.self::KSEP
                            .'created'.self::KSEP
                            .date('Y'), $this->pk());

            //fields sets
            foreach ($this->_sets as $field) 
            {
                if (isset($this->_data[$field]))
                {
                    $pipe->sadd($this->_table_name.self::KSEP
                                    .$field.self::KSEP
                                    .$this->_data[$field], $this->pk());
                    //sorted sets
                    $pipe->zincrby($this->_table_name.self::KSEP.$field, 1, $this->_data[$field]);
                    $pipe->zincrby($this->_table_name.self::KSEP.$field.self::KSEP.date('Y-m-d'), 1, $this->_data[$field]);
                    $pipe->zincrby($this->_table_name.self::KSEP.$field.self::KSEP.date('Y-m'), 1, $this->_data[$field]);
                    $pipe->zincrby($this->_table_name.self::KSEP.$field.self::KSEP.date('Y'), 1, $this->_data[$field]);
                }
            }
            
        }

        $pipe->execute();
        
        return $ret;
    }

    /**
     * deletes a key from the redis
     * @param  integer $pk optional
     * @return boolean      
     */
    public function delete($pk = NULL)
    {
        //WE CAN NOT DELETE A CLICK thats all!
        
        $this->unload();

        return  FALSE;
    }



}//end remodel