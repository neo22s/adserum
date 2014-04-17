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
class Model_Crontab extends ORM {
	
    /**
     * Table name to use
     *
     * @access	protected
     * @var		string	$_table_name default [singular model name]
     */
    protected $_table_name = 'crontab';

    /**
     * Column to use as primary key
     *
     * @access	protected
     * @var		string	$_primary_key default [id]
     */
    protected $_primary_key = 'id_cron';

    /**
     * Validation rules
     * @return  array  of rules to be added to the Validation object
     */
    public function rules()
    {
        return array(
            'name' => array(
                array('not_empty'),
                array('max_length', array(':value',50)),
            ),
            'period' => array(
                array('not_empty'),
                array('max_length', array(':value',50)),
            ),
            'callback' => array(
                array('not_empty'),
                array('max_length', array(':value',140)),  
            ),
            'description' => array(
                array('max_length', array(':value',255)),  
            ),

            // in fact, this should never be filled...is autofilled on creation
            'date_created' => array(
//                array('not_empty'),
//                array('max_length', array(':value',50)),
            ),
            'active' => array(
                array('not_empty'),
                array('numeric'),
            ),
        );
    }


    public function form_setup($form)
    {
        $form->fields['description']['display_as']   = 'textarea';
    }

    public function exclude_fields()
    {
       return array('date_created');
    }

    protected $_table_columns =
array (
  'id_cron' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_cron',
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
  'name' => 
  array (
    'type' => 'string',
    'column_name' => 'name',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 2,
    'character_maximum_length' => '50',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => 'UNI',
    'privileges' => 'select,insert,update,references',
  ),
  'period' => 
  array (
    'type' => 'string',
    'column_name' => 'period',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 3,
    'character_maximum_length' => '50',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'callback' => 
  array (
    'type' => 'string',
    'column_name' => 'callback',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 4,
    'character_maximum_length' => '140',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'description' => 
  array (
    'type' => 'string',
    'column_name' => 'description',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => true,
    'ordinal_position' => 5,
    'character_maximum_length' => '255',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'date_created' => 
  array (
    'type' => 'string',
    'column_name' => 'date_created',
    'column_default' => 'CURRENT_TIMESTAMP',
    'data_type' => 'timestamp',
    'is_nullable' => false,
    'ordinal_position' => 6,
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'active' => 
  array (
    'type' => 'int',
    'min' => '-128',
    'max' => '127',
    'column_name' => 'active',
    'column_default' => '1',
    'data_type' => 'tinyint',
    'is_nullable' => false,
    'ordinal_position' => 7,
    'display' => '1',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
);

} // END Model_Crontab