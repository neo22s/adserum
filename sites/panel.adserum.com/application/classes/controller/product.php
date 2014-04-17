<?php 

class Controller_Product extends Auth_Crud {

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('id_product','name');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'product';

	
}
