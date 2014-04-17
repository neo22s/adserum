<?php 

class Controller_Role extends Auth_Crud {

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('name','date_created');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'role';

	
}
