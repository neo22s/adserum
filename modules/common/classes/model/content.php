<?php
/**
 * content 
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Core
 * @copyright   (c) 2012 AdSerum.com
 * @license     GPL v3
 */
class Model_Content extends ORM {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'content';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_content';


    /**
     * @var  array  ORM Dependency/hirerachy
     */
    protected $_belongs_to = array(

  		'language' => array(
                'model'       => 'language',
                'foreign_key' => 'id_language',
            ),
  		'parent' => array(
                'model'       => 'content',
                'foreign_key' => 'id_content_parent',
            ),
    );

    /**
     * get the model filtered
     * @param  string $seotitle 
     * @param  model_language $language
     * @param  string $type   
     * @return model_language
     */
    public static function get($seotitle, $language = NULL, $type = 'page')
    {
        if ($language===NULL) $language = Controller::$lang;
        if ($language===NULL) $language = ControllerApi::$lang;

        $content = new self();
        $content = $content->where('seotitle','=', $seotitle)
                 ->where('id_language','=', $language->id_language)
                 ->where('type','=', $type)
                 ->where('status','=', 1)
                 ->limit(1)->cached()->find();
        
        //was not found try EN translation...
        if (!$content->loaded())
        {
            $language = new Model_Language();
            $language ->where('language', '=', 'en')->limit(1)->cached()->find();

            $content = $content->where('seotitle','=', $seotitle)
                 ->where('id_language','=', $language->id_language)
                 ->where('type','=', $type)
                 ->where('status','=', 1)
                 ->limit(1)->cached()->find();
        }

        return $content;
    }

    /**
     * get the model filtered
     * @param  string $seotitle 
     * @param  array $replace try to find the matches and replace them 
     * @param  model_language $language
     * @param  string $type   
     * @return model_language
     */
    public static function text($seotitle, $replace = NULL, $language = NULL, $type = 'page')
    {
        if ($replace===NULL) $replace = array(); 
        $content = self::get($seotitle,$language,$type);
        if ($content->loaded())
        {   
            $user = Auth::instance()->get_user();
            
            //adding extra replaces
            $replace+= array('[USER.NAME]' =>  $user->name,
                             '[USER.EMAIL]' =>  $user->email
                            );

            return str_replace(array_keys($replace), array_values($replace), $content->description);
        }
        return FALSE;
        
    }

    public function form_setup($form)
    {
        $form->fields['id_content_parent']['caption']   = 'seotitle';
        $form->fields['id_language']['caption']         = 'language_name';
        $form->fields['parent_deep']['display_as']      = 'select';
        $form->fields['parent_deep']['options']         = range(0, 3);
        $form->fields['order']['display_as']            = 'select';
        $form->fields['order']['options']               = range(0, 30);
    }

    public function exclude_fields()
    {
       return array('created');
    }


    protected $_table_columns = 
                            array (
                              'id_content' => 
                              array (
                                'type' => 'int',
                                'min' => '0',
                                'max' => '4294967295',
                                'column_name' => 'id_content',
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
                              'id_content_parent' => 
                              array (
                                'type' => 'int',
                                'min' => '0',
                                'max' => '4294967295',
                                'column_name' => 'id_content_parent',
                                'column_default' => '0',
                                'data_type' => 'int unsigned',
                                'is_nullable' => false,
                                'ordinal_position' => 2,
                                'display' => '10',
                                'comment' => '',
                                'extra' => '',
                                'key' => '',
                                'privileges' => 'select,insert,update,references',
                              ),
                              'id_language' => 
                              array (
                                'type' => 'int',
                                'min' => '0',
                                'max' => '4294967295',
                                'column_name' => 'id_language',
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
                              'order' => 
                              array (
                                'type' => 'int',
                                'min' => '0',
                                'max' => '4294967295',
                                'column_name' => 'order',
                                'column_default' => '0',
                                'data_type' => 'int unsigned',
                                'is_nullable' => false,
                                'ordinal_position' => 4,
                                'display' => '2',
                                'comment' => '',
                                'extra' => '',
                                'key' => '',
                                'privileges' => 'select,insert,update,references',
                              ),
                              'parent_deep' => 
                              array (
                                'type' => 'int',
                                'min' => '0',
                                'max' => '4294967295',
                                'column_name' => 'parent_deep',
                                'column_default' => '0',
                                'data_type' => 'int unsigned',
                                'is_nullable' => false,
                                'ordinal_position' => 5,
                                'display' => '2',
                                'comment' => '',
                                'extra' => '',
                                'key' => '',
                                'privileges' => 'select,insert,update,references',
                              ),
                              'title' => 
                              array (
                                'type' => 'string',
                                'column_name' => 'title',
                                'column_default' => NULL,
                                'data_type' => 'varchar',
                                'is_nullable' => false,
                                'ordinal_position' => 6,
                                'character_maximum_length' => '145',
                                'collation_name' => 'utf8_general_ci',
                                'comment' => '',
                                'extra' => '',
                                'key' => '',
                                'privileges' => 'select,insert,update,references',
                              ),
                              'seotitle' => 
                              array (
                                'type' => 'string',
                                'column_name' => 'seotitle',
                                'column_default' => NULL,
                                'data_type' => 'varchar',
                                'is_nullable' => false,
                                'ordinal_position' => 7,
                                'character_maximum_length' => '145',
                                'collation_name' => 'utf8_general_ci',
                                'comment' => '',
                                'extra' => '',
                                'key' => '',
                                'privileges' => 'select,insert,update,references',
                              ),
                              'description' => 
                              array (
                                'type' => 'string',
                                'character_maximum_length' => '65535',
                                'column_name' => 'description',
                                'column_default' => NULL,
                                'data_type' => 'text',
                                'is_nullable' => true,
                                'ordinal_position' => 8,
                                'collation_name' => 'utf8_general_ci',
                                'comment' => '',
                                'extra' => '',
                                'key' => '',
                                'privileges' => 'select,insert,update,references',
                              ),
                              'from_email' => 
                              array (
                                'type' => 'string',
                                'column_name' => 'from_email',
                                'column_default' => NULL,
                                'data_type' => 'varchar',
                                'is_nullable' => false,
                                'ordinal_position' => 9,
                                'character_maximum_length' => '145',
                                'collation_name' => 'utf8_general_ci',
                                'comment' => '',
                                'extra' => '',
                                'key' => '',
                                'privileges' => 'select,insert,update,references',
                              ),
                              'created' => 
                              array (
                                'type' => 'string',
                                'column_name' => 'created',
                                'column_default' => 'CURRENT_TIMESTAMP',
                                'data_type' => 'timestamp',
                                'is_nullable' => false,
                                'ordinal_position' => 10,
                                'comment' => '',
                                'extra' => 'on update CURRENT_TIMESTAMP',
                                'key' => '',
                                'privileges' => 'select,insert,update,references',
                              ),
                              'type' => 
                              array (
                                'type' => 'string',
                                'column_name' => 'type',
                                'column_default' => NULL,
                                'data_type' => 'enum',
                                'is_nullable' => false,
                                'ordinal_position' => 11,
                                'collation_name' => 'utf8_general_ci',
                                'options' => 
                                array (
                                  0 => 'page',
                                  1 => 'email',
                                  2 => 'help',
                                ),
                                'comment' => '',
                                'extra' => '',
                                'key' => '',
                                'privileges' => 'select,insert,update,references',
                              ),
                              'status' => 
                              array (
                                'type' => 'int',
                                'min' => '-128',
                                'max' => '127',
                                'column_name' => 'status',
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