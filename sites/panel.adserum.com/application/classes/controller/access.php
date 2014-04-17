<?php 

class Controller_Access extends Auth_Crud {

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('id_role','access');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'access';

	
}
