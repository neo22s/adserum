<?php
/**
 * Adformats
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Core
 * @copyright   (c) 2012 AdSerum.com
 * @license     GPL v3
 */
class Model_Adformat extends ORM {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'adformats';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_adformat';

    /**
     * get the format from specific values
     * @param  integer $format id_format
     * @param  integer $width  
     * @param  integer $height 
     * @return Model_Adformat         
     */
    public static function get_format($format,$width,$height)
    {
		//search idformat
		$adformat = new Model_Adformat();
		$adformat->where('id_adformat','=', $format)
				 ->limit(1)->cached()->find();
		//d($adformat);
		if (!$adformat->loaded())//not found so lets try with that size...
		{
			$adformat   ->where('width', '=', $width)
						->where('height','=', $height)
				 		->limit(1)->cached()->find();
		}

		return $adformat;
    }

    protected $_table_columns = 
array (
  'id_adformat' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_adformat',
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
  'max_ad_slots' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '255',
    'column_name' => 'max_ad_slots',
    'column_default' => NULL,
    'data_type' => 'tinyint unsigned',
    'is_nullable' => false,
    'ordinal_position' => 3,
    'display' => '1',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'orientation' => 
  array (
    'type' => 'string',
    'column_name' => 'orientation',
    'column_default' => NULL,
    'data_type' => 'enum',
    'is_nullable' => false,
    'ordinal_position' => 4,
    'collation_name' => 'utf8_general_ci',
    'options' => 
    array (
      0 => 'horizontal',
      1 => 'vertical',
      2 => 'square',
    ),
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'width' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '65535',
    'column_name' => 'width',
    'column_default' => NULL,
    'data_type' => 'smallint unsigned',
    'is_nullable' => false,
    'ordinal_position' => 5,
    'display' => '5',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'height' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '65535',
    'column_name' => 'height',
    'column_default' => NULL,
    'data_type' => 'smallint unsigned',
    'is_nullable' => false,
    'ordinal_position' => 6,
    'display' => '5',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'title_size' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '255',
    'column_name' => 'title_size',
    'column_default' => '25',
    'data_type' => 'tinyint unsigned',
    'is_nullable' => false,
    'ordinal_position' => 7,
    'display' => '3',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'description_size' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '255',
    'column_name' => 'description_size',
    'column_default' => '35',
    'data_type' => 'tinyint unsigned',
    'is_nullable' => false,
    'ordinal_position' => 8,
    'display' => '3',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'description2_size' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '255',
    'column_name' => 'description2_size',
    'column_default' => '35',
    'data_type' => 'tinyint unsigned',
    'is_nullable' => false,
    'ordinal_position' => 9,
    'display' => '3',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'url_size' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '255',
    'column_name' => 'url_size',
    'column_default' => '25',
    'data_type' => 'tinyint unsigned',
    'is_nullable' => false,
    'ordinal_position' => 10,
    'display' => '3',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'image' => 
  array (
    'type' => 'int',
    'min' => '-128',
    'max' => '127',
    'column_name' => 'image',
    'column_default' => '0',
    'data_type' => 'tinyint',
    'is_nullable' => false,
    'ordinal_position' => 11,
    'display' => '1',
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
    'column_default' => '0',
    'data_type' => 'tinyint',
    'is_nullable' => false,
    'ordinal_position' => 12,
    'display' => '1',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
);

}