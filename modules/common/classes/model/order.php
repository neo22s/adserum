<?php
/**
 * order 
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Core
 * @copyright   (c) 2012 AdSerum.com
 * @license     GPL v3
 */
class Model_Order extends ORM {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'orders';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_order';

    /**
     * @var  array  ORM Dependency/hirerachy
     */
    protected $_belongs_to = array(
        'user' => array(
                'model'       => 'user',
                'foreign_key' => 'id_user',
            ),
  		'product' => array(
                'model'       => 'product',
                'foreign_key' => 'id_product',
            ),
    );

    /**
     * Status constants
     */
    const STATUS_CREATED        = 0;    // just created
    const STATUS_PAID           = 1;   // paid!
    const STATUS_REFUSED        = 5;   //tried to paid but not succeed
    const STATUS_REFUND         = 99;   //we refunded the money

    /**
     * @var  array  Available statuses array
     */
    public static $statuses = array(
        self::STATUS_CREATED      =>  'Created',
        self::STATUS_PAID         =>  'Paid',
        self::STATUS_REFUSED      =>  'Refused',
        self::STATUS_REFUND       =>  'Refund',
    );

    public function form_setup($form)
    {
        $form->fields['id_product']['caption']  = 'name';
        $form->fields['status']['display_as']      = 'text';
        //$form->fields['status']['options']      = self::$statuses;
        $form->fields['id_user']['display_as']  = 'text';
    }

    public function exclude_fields()
    {
       return array('created');
    }

    protected $_table_columns = array ();//@todo

}