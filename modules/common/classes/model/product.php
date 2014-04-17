<?php
/**
 * Product 
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Core
 * @copyright   (c) 2012 AdSerum.com
 * @license     GPL v3
 */
class Model_Product extends ORM {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'products';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_product';


    public static function get_all()
    {
        $products = new Model_Product();
        return $products->where('status','=','1')->order_by('price','asc')->cached()->find_all();
    }

   // protected $_table_columns = array ();//@todo

}