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
class Model_Language extends ORM {
	
	/**
     * Status constants
     */
    const STATUS_INACTIVE		     = 0;    // Inactive not displayed, not translated
    const STATUS_ACTIVE				 = 1;   // Active translated
    
    /**
     * Table name to use
     *
     * @access	protected
     * @var		string	$_table_name default [singular model name]
     */
    protected $_table_name = 'languages';

    /**
     * Column to use as primary key
     *
     * @access	protected
     * @var		string	$_primary_key default [id]
     */
    protected $_primary_key = 'id_language';


    /**
     * Rule definitions for validation
     *
     * @return array
     */
    public function rules()
    {
    	return array(
			        'id_language'	=> array(array('numeric')),
			        'language'	    => array(array('not_empty'), array('max_length', array(':value', 2)), ),
			        'language_name'  => array(array('not_empty'), array('max_length', array(':value', 45)), ),
    				'charset'  => array(array('max_length', array(':value', 15)),),
    				'locale'  => array(array('max_length', array(':value', 10)),),
			    );
    }
    
    /**
     * 
     * Get all the languages, we don't use the ORM due to poor performance
     * @return array
     */
    public static function get_all()
    {
    	$query = 'SELECT language, language_name, id_language, locale
					FROM  `as_languages`
					WHERE status='.self::STATUS_ACTIVE.' 
					ORDER BY 2 ASC';//AND id_language!='.Controller::$lang->id_language.'
    	
    	return Database::instance()->query(Database::SELECT, $query,TRUE)->as_array();
    }


    /**
     * get language class by param or cookie or browser
     * @return Model_Language
     */
    public static function get_by_param()
    {
        //Get language
        $language = Request::current()->param('lang',Core::get('lang'));

        if ($language===NULL)
        {
            //no param read cookie
            $language = Cookie::get('language');
            if ($language===NULL)
            {
                //no cookie by browser
                $language = substr(key(Request::accept_lang()),0,2);
            }
        }           
        
        $lang = new Model_Language();
        $lang ->where('language', '=', $language)->limit(1)->cached()->find();
        if (!$lang->loaded())
        {
            $lang ->where('language', '=', 'en')->limit(1)->cached()->find();
        }

        //store in cookie prefered language for next time
        $language = $lang->language;
        Cookie::set('language', $language, 7*24*60*60);

        return $lang;
    }

    /**
     * get language class by language
     * @return Model_Language
     */
    public static function get_by_lang($language)
    {          
        
        $lang = new Model_Language();
        $lang ->where('language', '=', $language)->limit(1)->cached()->find();
        if (!$lang->loaded())
        {
            $lang ->where('language', '=', 'en')->limit(1)->cached()->find();
        }
        return $lang;
    }


    public function form_setup($form)
    {
        $form->fields['status']['display_as']   = 'bool';
    }

    
    protected $_table_columns = array (
                              'id_language' => 
                              array (
                                'type' => 'int',
                                'min' => '0',
                                'max' => '4294967295',
                                'column_name' => 'id_language',
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
                              'language' => 
                              array (
                                'type' => 'string',
                                'exact' => true,
                                'column_name' => 'language',
                                'column_default' => NULL,
                                'data_type' => 'char',
                                'is_nullable' => false,
                                'ordinal_position' => 2,
                                'character_maximum_length' => '2',
                                'collation_name' => 'utf8_general_ci',
                                'comment' => '',
                                'extra' => '',
                                'key' => 'UNI',
                                'privileges' => 'select,insert,update,references',
                              ),
                              'language_name' => 
                              array (
                                'type' => 'string',
                                'column_name' => 'language_name',
                                'column_default' => NULL,
                                'data_type' => 'varchar',
                                'is_nullable' => false,
                                'ordinal_position' => 3,
                                'character_maximum_length' => '45',
                                'collation_name' => 'utf8_general_ci',
                                'comment' => '',
                                'extra' => '',
                                'key' => '',
                                'privileges' => 'select,insert,update,references',
                              ),
                              'charset' => 
                              array (
                                'type' => 'string',
                                'column_name' => 'charset',
                                'column_default' => 'UTF-8',
                                'data_type' => 'varchar',
                                'is_nullable' => true,
                                'ordinal_position' => 4,
                                'character_maximum_length' => '15',
                                'collation_name' => 'utf8_general_ci',
                                'comment' => '',
                                'extra' => '',
                                'key' => '',
                                'privileges' => 'select,insert,update,references',
                              ),
                              'locale' => 
                              array (
                                'type' => 'string',
                                'column_name' => 'locale',
                                'column_default' => 'en_GB',
                                'data_type' => 'varchar',
                                'is_nullable' => true,
                                'ordinal_position' => 5,
                                'character_maximum_length' => '10',
                                'collation_name' => 'utf8_general_ci',
                                'comment' => '',
                                'extra' => '',
                                'key' => '',
                                'privileges' => 'select,insert,update,references',
                              ),
                              'status' => 
                              array (
                                'type' => 'int',
                                'min' => '-2147483648',
                                'max' => '2147483647',
                                'column_name' => 'status',
                                'column_default' => '0',
                                'data_type' => 'int',
                                'is_nullable' => false,
                                'ordinal_position' => 6,
                                'display' => '1',
                                'comment' => '',
                                'extra' => '',
                                'key' => '',
                                'privileges' => 'select,insert,update,references',
                              ),
                            );

} // END Model_Visit