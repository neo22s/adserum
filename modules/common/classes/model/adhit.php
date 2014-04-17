<?php
/**
 * Ad hits/impressions
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Core
 * @copyright   (c) 2012 AdSerum.com
 * @license     GPL v3
 *
 * Sets created:
 * adhits:_all_ = id_adhit
 *
 * Globals sets
 * 
 * adhits:created:2013-12-25 = id_adhit--- store id_adhit by date
 * adhits:created:2013-12 = id_adhit--- store id_adhit by date
 * adhits:created:2013 = id_adhit--- store id_adhit by date
 * 
 * adhits:id_adformat:IDFORMAT = id_hit --- do we need it? having the score sets...
 * adhits:id_affiliate:IDAFF = id_hit
 * adhits:id_language:IDLANG = id_hit --- do we need it? having the score sets...
 * adhits:id_location:IDLOC = id_hit --- do we need it? having the score sets...
 * adhits:domain:DOMAINNAME = id_hit
 * adhits:browser:BROWSERNAME= id_hit--- do we need it? having the score sets...
 * adhits:platform:PLATFORMNAME = id_hit--- do we need it? having the score sets...
 *
 * Sorted sets, all of them have also adhit:KEY:Y-m-d
 * adhits:id_adformat = IDFORMAT
 * adhits:id_affiliate = IDAFF
 * adhits:id_language = IDLANG
 * adhits:id_location = IDLOC
 * adhits:domain = DOMAINNAME
 * adhits:browser= BROWSERNAME
 * adhits:platform = PLATFORMNAME 
 * adhits:ip_address = ip2long(ip_address) 
 * adhits:domains:id_affiliate:IDNUM = DOMAIN
 * 
 */
class Model_Adhit extends Remodel {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'adhits';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_adhit';

    /**
     * fields that has this model
     * @var array
     */
    protected $_fields = array( 'id_adformat',
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
     protected $_sets =  array ('id_adformat',
                                'id_affiliate',
                                'id_language',
                                'id_location',
                                'domain',
                                'browser',
                                'platform',
                                'ip_address',
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

            //adhits:domains:id_affiliate:IDNUM
            $name = $this->_table_name.self::KSEP.'domains'.self::KSEP.'id_affiliate'.self::KSEP.$this->id_affiliate;
            $pipe->zincrby($name, 1, $this->domain);
            $pipe->zincrby($name.self::KSEP.date('Y-m-d'), 1, $this->domain);
            $pipe->zincrby($name.self::KSEP.date('Y-m'), 1, $this->domain);
            $pipe->zincrby($name.self::KSEP.date('Y'), 1, $this->domain);
            
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
        //WE CAN NOT DELETE A HIT thats all!
        
        $this->unload();

        return  FALSE;
    }

}//end remodel