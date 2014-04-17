<?php 

class Controller_Adformat extends Auth_Crud {

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('name','width','height');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'adformat';

	
}
